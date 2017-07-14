<?php

namespace IslamCompanion;

use \IslamCompanionApi\DataObjects\HolyQuran as HolyQuran;
use \IslamCompanionApi\DataObjects\Hadith as Hadith;

/**
 * This class implements the main plugin class
 * It contains functions that implement the filter, actions and hooks defined in the application configuration
 *
 * It is used to implement the main functions of the plugin
 *
 * @category   IslamCompanion
 * @package    IslamCompanion
 * @author     Nadir Latif <nadir@islamcompanion.org>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 * @version    3.0.6
 */
final class IslamCompanionSettings extends \Framework\Frameworks\WordPress\Settings
{
    /**
     * Holds the plugin settings values. Including default values and callback function names
     */
    private $plugin_settings;
    /**
     * Used to decode the meta data
     *
     * It decodes the extra field in the api response
     * It returns the contents of the decoded extra field
     *
     * @param array $encoded_meta_data the extra field in the plugin options
     *
     * @return array $meta_data the decoded meta data is returned
     *    narrators_languages => array the list of narrators and languages
     *    suras => array the list of suras
     */
    private function DecodeMetaData($encoded_meta_data) 
    {
        try
        {
            /** If the extra options contains '@' sign then it is parsed */
            if (strpos($encoded_meta_data, "@") !== false) 
            {
                /** The extra options are parsed */
                $temp_arr = explode("@", $encoded_meta_data);
                /** The extra options are updated */
                $encoded_meta_data = $temp_arr[0];
            }
            /** The encoded data in the response is decoded */
            $meta_data = $this->GetComponent("encryption")->DecodeData($encoded_meta_data);
            return $meta_data;
        }
        catch(\Exception $e) 
        {
            $this->GetComponent("errorhandler")->ExceptionHandler($e);
        }
    }
    /**
     * Used to fetch the meta data from Islam Companion API
     *
     * It fetches language, narrator, sura, hadith book and hadith title data from Islam Companion API
     * It first checks if data is present in wordpress options
     * If not present, then data is fetched from API
     *
     * @param $meta_data_type string [all~sura~language~translator~language and translator~sura, language and translator~hadith meta data] the type of data that is required
     *
     * @return array $meta_data the decoded meta data is returned
     *    narrators_languages => array the list of narrators and languages
     *    suras => array the list of suras
     *    hadith_books => array the list of hadith books     
     *    hadith_titles => array the list of hadith titles     
     */
    public function GetMetaData($meta_data_type) 
    {
        try
        {
            /** The options id is fetched */
            $options_id = $this->GetComponent("application")->GetOptionsId("options");
            /** The current plugin options are fetched */
            $options = $this->GetComponent("application")->GetPluginOptions($options_id);
            /** The required meta data */
            $meta_data = array();
            /** Used to indicate that the meta data needs to be fetched from api */
            $fetch_from_api = false;
            /** If the extra field option is set */
            if (isset($options['extra'])) 
            {
                /** The extra field in the plugin options is decoded */
                $meta_data = $this->DecodeMetaData($options['extra']);
                /** If the meta data option is sura, language and translator and sura option is not set then the data needs to be fetched from api */
                if ($meta_data_type == "sura, language and translator" && !isset($meta_data['suras'])) $fetch_from_api = true;
                /** If the meta data option is hadith meta data and hadith_meta option is not set then the data needs to be fetched from api */
                else if ($meta_data_type == "hadith meta data" && !isset($meta_data['hadith_meta'])) $fetch_from_api = true;
            }
            else
            {
                $fetch_from_api = true;
            }
         
            /** If the meta data needs to be fetched from api, then the data is fetched */
            if ($fetch_from_api) 
            {
                /** The parameters used to make the api request */
                $parameters = array();
                /** The name of the module to call */
                $module_name = "IslamCompanionApi";
                /** The name of the function to call. this function fetches language and narrator data */
                $option = "get_holy_quran_meta_data";
                /** Used to indicate the type of database */
                $parameters['database_type'] = "wordpress";
                /** Used to indicate the type of data to return */
                $parameters['type'] = $meta_data_type;
                /** API key for the api function call */
                $parameters['api_key'] = $this->GetConfig("general", "api_key");
                /** Used to indicate the type of request */
                $parameters['request_type'] = "local";
                /** The response format for the function output. If the data source is remote then the response format is set to array. Otherwise it is set to json */
                $output_format = "array";
                /** The api response. The current url contents are fetched and assigned to api response */
                $response = $this->GetComponent("api")->MakeApiRequest($parameters['request_type'], $option, $module_name, $output_format, $parameters, "POST");
                /** The required meta data */
                $meta_data = array_merge($meta_data, $response['data']);
                /** The meta data is encoded */
                $options['extra'] = $this->GetComponent("encryption")->EncodeData($meta_data);
                /** The plugin options are saved */
                $this->GetComponent("application")->SavePluginOptions($options, $options_id);
            }
            return $meta_data;
        }
        catch(\Exception $e) 
        {
            $this->GetComponent("errorhandler")->ExceptionHandler($e);
        }
    }
    /** 
     * Fetches data used to populate the settings form fields
     *
     * It fetches the data used to populate the settings form select boxes
     *
     * @return array $field_data the data used to populate to the settings fields
     *    narrator_language_mapping => contains all the language and narrator data
     *                              it is an array of values
     *                              each value is an array with following keys: language and narrator
     *    division => the Holy Quran divisions
     *    language => the list of all supported languages. it is an array of language strings
     *    narrator => the list of all supported narrators. it is an array of narrator strings
     */
    public function GetSettingsFieldData() 
    {
        /** The meta data is fetched from Islam Companion API */
        $meta_data = $this->GetMetaData("sura, language and translator");
        /** The list of distinct languages */
        $languages = array();
        /** The list of distinct narrators */
        $narrators = array();
        /** The list of distinct suras */
        $suras = $meta_data['suras'];       
        /** The list of distinct languages and narrators is determined */
        for ($count = 0;$count < count($meta_data['languages_narrators']);$count++) 
        {
            /** Single narrator language mapping */
            $language_narrator = $meta_data['languages_narrators'][$count];
            /** The language */
            $language = $language_narrator["language"];
            /** The narrator */
            $narrator = $language_narrator["narrator"];
            /** If the language has not yet been added to the list of distinct languages, then it is added */
            if (!in_array($language, $languages)) $languages[] = $language;
            /** If the narrator has not yet been added to the list of distinct narrators, then it is added */
            if (!in_array($narrator, $narrators)) $narrators[] = $narrator;
        }
        /** The encoded meta data */
        $field_data['encoded_meta_data'] = $this->GetComponent("encryption")->EncodeData($meta_data);
        /** The divisions data */
        $field_data['division'] = array(
            "Hizb",
            "Juz",
            "Manzil",
            "Page",
            "Ruku"
        );
        /** The language data */
        $field_data['language'] = $languages;
        /** The narrator data */
        $field_data['narrator'] = $narrators;
        /** The hadith languages */
        $field_data['hadith_language'] = array("English");
        /** The suras data */
        $field_data['suras'] = $suras;
        /** The imported data */
        $field_data['imported_data'] = array(
            "None",
            "Holy Quran",
            "Hadith",
            "Holy Quran and Hadith"
        );
        return $field_data;
    }
    /**
     * Used to display the settings page
     *
     * It displays the forms on the settings page
     */
    public function DisplaySettingsPage() 
    {
        try
        {
            /** The settings fields are displayed */
            $settings_fields_html = $this->GetSettingsFieldsHtml();
            $plugin_template_path = $this->GetConfig("wordpress", "plugin_template_path") . DIRECTORY_SEPARATOR . "settings.html";
            $plugin_text_domain   = $this->GetConfig("wordpress", "plugin_text_domain");
            /** The options id is fetched */
            $options_id           = $this->GetComponent("application")->GetOptionsId("options");
            /** The current plugin options are fetched */
            $options              = $this->GetComponent("application")->GetPluginOptions($options_id);
            /** The data source. It indicates whether data should be fetched from local WordPress data or remote database */
            $imported_data        = $options['imported_data'];

            /** The css class for the Import Data button. The button is hidden if the imported data is set to 'none' */
            $ic_import_classes    = ($imported_data == 'none') ? "ic-hidden" : "button button-primary";
            /** The css class for the right settings section */
            $settings_right_class = "ic-hidden";
            /** The text for the import button */
            $import_button_text   = ($imported_data == "none") ? "Import Data" : "Delete Imported Data";
            /** The tag replacement array is built */
            $tag_replacement_arr  = array(
                array(
                    "heading" => __("Islam Companion", $plugin_text_domain) ,
                    "data_import_heading" => __("Data Import Status", $plugin_text_domain) ,
                    "ajax_nonce" => wp_create_nonce("islam-companion") ,
                    "ic_import_classes" => $ic_import_classes,
                    "form_fields" => $settings_fields_html,
                    "import_button_text" => $import_button_text,
                    "settings_right_class" => $settings_right_class,
                    "quranic_data_source_text" => __("Quranic Data Provided By", $plugin_text_domain) ,
                    "hadith_data_source_text" => __("Hadith Data Provided By", $plugin_text_domain) ,                    
                    "report_bug_text" => __("Report a bug", $plugin_text_domain) ,
                    "suggest_feature_text" => __("Suggest a feature", $plugin_text_domain) ,
                    "learn_arabic_text" => __("Learn Arabic", $plugin_text_domain) ,
                    "discuss_islam_text" => __("Discuss Islam", $plugin_text_domain) ,
                    "urdu_fonts_source_text" => __("Urdu fonts provided by", $plugin_text_domain) ,
                    "arabic_fonts_source_text" => __("Arabic fonts provided by", $plugin_text_domain)
                )
            );
            /** The settings page template is rendered */
            $settings_page_html    = $this->GetComponent("template_helper")->RenderTemplateFile($plugin_template_path, $tag_replacement_arr);
            /** The settings page html is displayed */
            $this->GetComponent("application")->DisplayOutput($settings_page_html);
        }
        catch(\Exception $e) 
        {
            $this->GetComponent("errorhandler")->ExceptionHandler($e);
        }
    }
    /**
     * Registers and adds settings using the WordPress api
     */
    public function InitializeAdminPage() 
    {
        try
        {
            /** The settings field data is fetched */
            $field_data = $this->GetSettingsFieldData();
            /** The plugin text domain */
            $plugin_text_domain = $this->GetConfig("wordpress", "plugin_text_domain");
            /** The options id is fetched */
            $options_id = $this->GetComponent("application")->GetOptionsId("options");
            /** The current plugin options are fetched */
            $options = $this->GetComponent("application")->GetPluginOptions($options_id);
            /** The narrator */
            $narrator = (isset($options['narrator'])) ? array(
                "narrator" => $options['narrator']
            ) : array(
                "narrator" => "Mohammed Marmaduke William Pickthall"
            );
            /** The narrator data is encoded */
            $narrator = $this->GetComponent("encryption")->EncodeData($narrator);
            /** The number of verses to import in one try */
            $verse_import_count = $this->GetConfig("custom", "verse_import_count");
            /** The ajax nonce */
            $ajax_nonce = wp_create_nonce('islam-companion');
            /** The value of the extra hidden field is set */
            $extra = ($field_data['encoded_meta_data'] . "@" . $narrator . "@" . $ajax_nonce . "@" . $verse_import_count);
            /** The plugin settings are initialized */
            $this->plugin_settings = array(
                /** The visible fields */
                "language" => array(
                    "name" => __('Holy Quran Language', $plugin_text_domain) ,
                    "callback" => array(
                        "settings",
                        "DropdownFieldCallback"
                    ) ,
                    "hidden" => false,
                    "short_name" => "language",
                    "args" => array(
                        "use_lowercase_value" => false,
                        "multi_select_size" => "1",
                        "options" => $field_data['language'],
                        "default_value" => (isset($options['language'])) ? $options['language'] : "English"
                    )
                ) ,
                "hadith_language" => array(
                    "name" => __('Hadith Language', $plugin_text_domain) ,
                    "callback" => array(
                        "settings",
                        "DropdownFieldCallback"
                    ) ,
                    "hidden" => false,
                    "short_name" => "hadith_language",          
                    "args" => array(
                        "use_lowercase_value" => false,
                        "multi_select_size" => "1",
                        "options" => $field_data['hadith_language'],
                        "default_value" => (isset($options['hadith_language'])) ? $options['hadith_language'] : "English"
                    )
                ) ,                
                "narrator" => array(
                    "name" => __('Holy Quran Narrator', $plugin_text_domain) ,
                    "callback" => array(
                        "settings",
                        "DropdownFieldCallback"
                    ) ,
                    "hidden" => false,
                    "short_name" => "narrator",          
                    "args" => array(
                        "use_lowercase_value" => false,
                        "multi_select_size" => "1",                        
                        "options" => $field_data['narrator'],
                        "default_value" => isset($options['narrator']) ? $options['narrator'] : "Mohammed Marmaduke William Pickthall"
                    )
                ) ,
                "division" => array(
                    "name" => __('Holy Quran Division', $plugin_text_domain) ,
                    "callback" => array(
                        "settings",
                        "DropdownFieldCallback"
                    ) ,
                    "hidden" => false,
                    "short_name" => "division",               
                    "args" => array(
                        "use_lowercase_value" => true,
                        "multi_select_size" => "1",                           
                        "options" => $field_data['division'],
                        "default_value" => isset($options['division']) ? $options['division'] : "ruku"
                    )
                ) ,
                "imported_data" => array(
                    "name" => __('Imported Data', $plugin_text_domain) ,
                    "callback" => array(
                        "settings",
                        "DropdownFieldCallback"
                    ) ,
                    "hidden" => false,
                    "short_name" => "imported_data",
                    "args" => array(
                        "multi_select_size" => "1",
                        "use_lowercase_value" => true,
                        "options" => $field_data['imported_data'],
                        "default_value" => isset($options['imported_data']) ? $options['imported_data'] : "none"
                    )
                ) ,
                /** The hidden fields */
                "division_number" => array(
                    "name" => __('Division Number', $plugin_text_domain) ,
                    "callback" => array(
                        "settings",
                        "HiddenFieldCallback"
                    ) ,
                    "hidden" => true,
                    "short_name" => "division_number",
                    "args" => array(
                        "default_value" => "1"
                    )
                ) ,
                "sura" => array(
                    "name" => __('Sura', $plugin_text_domain) ,
                    "callback" => array(
                        "settings",
                        "HiddenFieldCallback"
                    ) ,
                    "hidden" => true,
                    "short_name" => "sura",
                    "args" => array(
                        "default_value" => "1"
                    )
                ) ,
                "ayat" => array(
                    "name" => __('Ayat', $plugin_text_domain) ,
                    "callback" => array(
                        "settings",
                        "HiddenFieldCallback"
                    ) ,
                    "hidden" => true,
                    "short_name" => "ayat",
                    "args" => array(
                        "default_value" => '1'
                    )
                ) ,
                "ruku" => array(
                    "name" => __('Ruku', $plugin_text_domain) ,
                    "callback" => array(
                        "settings",
                        "HiddenFieldCallback"
                    ) ,
                    "hidden" => true,
                    "short_name" => "ruku",
                    "args" => array(
                        "default_value" => '1'
                    )
                ) ,
                "hadith_book" => array(
                    "name" => __('Hadith Book', $plugin_text_domain) ,
                    "callback" => array(
                        "settings",
                        "HiddenFieldCallback"
                    ) ,
                    "hidden" => true,
                    "short_name" => "hadith_book",
                    "args" => array(
                        "default_value" => ''
                    )
                ) ,
                "hadith_title" => array(
                    "name" => __('Hadith Title', $plugin_text_domain) ,
                    "callback" => array(
                        "settings",
                        "HiddenFieldCallback"
                    ) ,
                    "hidden" => true,
                    "short_name" => "hadith_title",
                    "args" => array(
                        "default_value" => ''
                    )
                ) ,
                "hadith_source" => array(
                    "name" => __('Hadith Source', $plugin_text_domain) ,
                    "callback" => array(
                        "settings",
                        "HiddenFieldCallback"
                    ) ,
                    "hidden" => true,
                    "short_name" => "hadith_source",
                    "args" => array(
                        "default_value" => (isset($options['hadith_source'])) ? $options['hadith_source'] : "Sahih Bukhari"
                    )
                ) ,
                "extra" => array(
                    "name" => __('Extra', $plugin_text_domain) ,
                    "callback" => array(
                        "settings",
                        "HiddenFieldCallback"
                    ) ,
                    "hidden" => true,
                    "short_name" => "extra",
                    "args" => array(
                        "default_value" => $extra
                    )
                )
            );
            $this->RegisterPluginOptions($this->plugin_settings);
        }
        catch(\Exception $e) 
        {
            $this->GetComponent("errorhandler")->ExceptionHandler($e);
        }
    }
    /**
     * Used to display the section information
     *
     * The section information is displayed
     */
    public function PrintSectionInfo() 
    {
        /** The plugin text domain */
        $plugin_text_domain = $this->GetConfig("wordpress", "plugin_text_domain");
        echo __('Plugin Settings', $plugin_text_domain);
    }
    /**
     * Used to delete Holy Quran and Hadith data
     *
     * It deletes the WordPress posts containing Holy Quran and Hadith data
     * It first deletes the custom posts containing the Holy Quran meta data. i.e Suras and Authors posts
     * After that it deletes the Ayas custom posts
     * Then it deletes the Hadith meta data custom posts and then the Hadith custom posts
     * Each time this function is called it deletes 200 custom posts in total
     * After deleting the custom posts, the function returns the status of the import process
     * This status information is used by the ajax function call
     *
     * @param array $parameters the parameters containing the verse import data
     *     next_verse => int the verse number of the next verse to be deleted
     *     language => string the language for the translation
     *     narrator => string the narrator for the verses
     */
    private function DeleteHolyQuranHadithData($parameters) 
    {
        /** The options id is fetched */
        $options_id = $this->GetComponent("application")->GetOptionsId("options");
        /** The current plugin options are fetched */
        $plugin_options = $this->GetComponent("application")->GetPluginOptions($options_id);
        /** The parameters used to make the api request */
        $api_parameters = array();
        /** Used to indicate the type of database */
        $api_parameters['database_type'] = 'wordpress';
        /** Used to set the user id of the logged in user */
        $api_parameters['user_id'] = $this->GetConfig("wordpress", "user_id");
        /** The number of posts to delete */
        $api_parameters['post_count'] = $this->GetConfig("custom", "verse_delete_count");
        /** The number of posts to delete. It is not used by the function but is required for saving test data */
        $api_parameters['next_verse'] = $parameters['next_verse'];
        /** API key for the api function call */
        $api_parameters['api_key'] = $this->GetConfig("general", "api_key");
        /** Used to indicate the type of request */
        $api_parameters['request_type'] = "local";
        /** Used to indicate the format of the api response */
        $output_format = 'array';
        /** The name of the local module to call */
        $module_name = "IslamCompanionApi";
        /** The type of data to import after the existing data has been deleted */
        $data_to_import = $parameters['imported_data'];
        /** The verse number of the next verse to be deleted */
        $delete_start = $parameters['next_verse'];
        /** The total number of suras + authors */
        $sura_author_count = (HolyQuran::GetMaxDivisionCount("sura") + HolyQuran::GetMaxDivisionCount("author"));
        /** The total number of ayas */
        $ayat_count        = HolyQuran::GetMaxDivisionCount("ayas");
        /** The total number of hadith */
        $hadith_count      = Hadith::GetTotalHadithCount();
        /** The total number of hadith books */
        $hadith_books      = Hadith::GetTotalHadithBooksCount();
        /** If the verse number is less than sura count + author count */
        if ($delete_start < $sura_author_count) $option = "delete_holy_quran_meta_data";
        /** If the verse number is greater than (sura count + author count) but less than or equal to (sura count + author count + total ayat count) */
        else if ($delete_start > ($sura_author_count) && $delete_start <= ($sura_author_count + $ayat_count)) $option = "delete_holy_quran_data";
        /** If the verse number is greater than (sura count + author count + total ayat count)  but less or equal to than (sura count + author count + total ayat count + hadith books count) */
        else if ($delete_start > ($sura_author_count + $ayat_count) && $delete_start <= ($sura_author_count + $ayat_count + $hadith_books)) $option = "delete_hadith_meta_data";
        /** If the verse number is greater than (sura count + author count + total ayat count + hadith books count) */
        else if ($delete_start > ($sura_author_count + $ayat_count + $hadith_books)) $option = "delete_hadith_data";
        /** The total number of posts to delete is the sum of total number of verses in Holy Quran added to total number of suras added to the total number of translations which is 111 */
        $total_post_count = ($sura_author_count + $ayat_count + $hadith_count + $hadith_books);
        /** All custom posts of type Suras, Ayas and Authors are deleted */
        $response = $this->GetComponent("api")->MakeApiRequest($api_parameters['request_type'], $option, $module_name, $output_format, $api_parameters, "POST");
        /** The number of deleted posts */
        $posts_deleted = $response['data']['posts_deleted'];
        /** The total progress. If no posts were deleted, then the delete task is completed and total progress is set to 33%. If posts were deleted then the total progress is calculated */
        $total_progress = floor((($delete_start + $posts_deleted) / $total_post_count) * 100);
        /** If no data was deleted then the next import action is set to empty and progress is set to 100% */
        if ($posts_deleted == 0) 
        {
            $next_import_action = "";
            $total_progress     = "100";
        }
        else
        {
            $next_import_action = "deleting custom posts";
        }
        /** The data to be displayed */
        $data = array(
            "result" => "success",
            "data" => array(
                "import_action" => $next_import_action,
                "progress" => $total_progress,
                "next_verse" => ($delete_start + $posts_deleted)
            )
        );
        /** The data is displayed */
        $this->GetComponent("application")->DisplayOutput($data);
        /** The script is terminated so correct response can be sent to the browser */
        wp_die();
    }
    /**
     * Used to add Holy Quran meta data
     *
     * It adds custom posts containing Holy Quran meta data
     * It adds custom posts of type Suras and Authors
     * After adding the custom posts, the function displays the status of the import process
     * This status information is used by the ajax function call
     *
     * @param array $parameters the parameters containing the verse import data
     *    next_verse => int the verse number of the next verse to be deleted
     *    language => string the language for the translation
     *    narrator => string the narrator for the verses
     *    plugin_action => string [importing quranic author data~importing quranic sura data] the current plugin action
     */
    private function AddHolyQuranMetaData($parameters) 
    {
        /** The options id is fetched */
        $options_id = $this->GetComponent("application")->GetOptionsId("options");
        /** The current plugin options are fetched */
        $plugin_options = $this->GetComponent("application")->GetPluginOptions($options_id);
        /** Used to indicate the type of database */
        $api_parameters['database_type'] = 'wordpress';
        /** The user id of the logged in user */
        $api_parameters['user_id'] = $this->GetConfig("wordpress", "user_id");
        /** API key for the api function call */
        $api_parameters['api_key'] = $this->GetConfig("general", "api_key");
        /** Used to indicate the type of request */
        $api_parameters['request_type'] = "local";
        /** The type of data to import */
        $api_parameters['data_type'] = ($parameters['plugin_action'] == 'importing quranic author data') ? 'author' : 'sura';
        /** Used to indicate the format of the api response */
        $output_format = 'array';
        /** The name of the local module to call */
        $module_name = "IslamCompanionApi";
        /** The name of the function to call. this function adds Holy Quran author and sura data */
        $option = "add_holy_quran_meta_data";
        /** The total progress */
        $total_progress = ($api_parameters['data_type'] == "author") ? "68" : "75";
        /** The api response. The current url contents are fetched and assigned to api response */
        $response = $this->GetComponent("api")->MakeApiRequest($api_parameters['request_type'], $option, $module_name, $output_format, $api_parameters, "POST");
        /** The next import action */
        $import_action = ($parameters['plugin_action'] == 'importing quranic author data') ? 'importing quranic sura data' : 'importing quranic data';
        /** The data to be displayed */
        $data = array(
            "result" => "success",
            "data" => array(
                "import_action" => $import_action,
                "progress" => $total_progress,
                "next_verse" => '1'
            )
        );
        /** The data is displayed */
        $this->GetComponent("application")->DisplayOutput($data);
        /** The script is terminated so correct response can be sent to the browser */
        wp_die();
    }
    /**
     * Used to add Holy Quran data
     *
     * It adds custom posts containing Holy Quran data
     * It adds custom posts of type Ayas
     * After adding the custom posts, the function displays the status of the import process
     * This status information is used by the ajax function call
     *
     * @param array $parameters the parameters containing the verse import data
     *     next_verse => int the verse number of the next verse to be deleted
     *     language => string the language for the translation
     *     narrator => string the narrator for the verses
     */
    private function AddHolyQuranData($parameters) 
    {
        /** The options id is fetched */
        $options_id = $this->GetComponent("application")->GetOptionsId("options");
        /** The current plugin options are fetched */
        $plugin_options = $this->GetComponent("application")->GetPluginOptions($options_id);
        /** The parameters used to make the api request */
        $api_parameters = array();
        /** Used to indicate the type of database */
        $api_parameters['database_type'] = 'wordpress';
        /** The user id of the logged in user */
        $api_parameters['user_id'] = $this->GetConfig("wordpress", "user_id");
        /** The start ayat */
        $api_parameters['start_ayat'] = $parameters['next_verse'];
        /** The total number of ayas to add */
        $api_parameters['total_ayat_count'] = $this->GetConfig("custom", "verse_import_count");
        /** The translator name */
        $api_parameters['narrator'] = $parameters['narrator'];
        /** The language for the translation */
        $api_parameters['language'] = $parameters['language'];
        /** API key for the api function call */
        $api_parameters['api_key'] = $this->GetConfig("general", "api_key");
        /** Used to indicate the type of request */
        $api_parameters['request_type'] = "local";
        /** Used to indicate the format of the api response */
        $output_format = 'array';
        /** The name of the local module to call */
        $module_name = "IslamCompanionApi";
        /** The name of the function to call. this function deletes all Holy Quran data from WordPress */
        $option = "add_holy_quran_data";
        /** The ayas data is added to WordPress */
        $response = $this->GetComponent("api")->MakeApiRequest($api_parameters['request_type'], $option, $module_name, $output_format, $api_parameters, "POST");
        /** The number of posts added */
        $posts_added = $response['data']['posts_added'];
        /** The total number of posts added */
        $total_posts_added = ($api_parameters['start_ayat'] + $posts_added);
        /** The total number of verses in the Holy Quran */
        $total_verse_count = HolyQuran::GetMaxDivisionCount("ayas");
        /** The progress of adding Holy Quran data. It is expressed as a percentage of the remaining tasks */
        $progress = floor((($total_posts_added / $total_verse_count) * 25));
        /** 
         * The total progress
         * It is calculated by adding the progress to the sum of the progress of deleting the
         * Holy Quran data and adding the Holy Quran meta data
         */
        $total_progress = (75 + $progress);
        /** The verse number of the next verse to import. The import process will start with this verse */
        $next_verse = ($api_parameters['start_ayat'] + $posts_added);
        /** The import action is set to importing quranic data */
        $import_action = "importing quranic data";
        /** The data to be displayed */
        $data = array(
            "result" => "success",
            "data" => array(
                "import_action" => $import_action,
                "progress" => $total_progress,
                "next_verse" => $next_verse
            )
        );
        /** The data is displayed */
        $this->GetComponent("application")->DisplayOutput($data);
        /** The script is terminated so correct response can be sent to the browser */
        wp_die();
    }
    /**
     * Used to add Hadith Meta data
     *
     * It adds WordPress posts containing Hadith Meta data
     * It adds custom posts of type Books
     * After adding the custom posts, the function displays the status of the import process
     * This status information is used by the ajax function call
     */
    private function AddHadithMetaData($parameters) 
    {
        /** The options id is fetched */
        $options_id = $this->GetComponent("application")->GetOptionsId("options");
        /** The current plugin options are fetched */
        $plugin_options = $this->GetComponent("application")->GetPluginOptions($options_id);
        /** The parameters used to make the api request */
        $api_parameters = array();
        /** Used to indicate the type of database */
        $api_parameters['database_type'] = 'wordpress';

        /** The user id of the logged in user */
        $api_parameters['user_id'] = $this->GetConfig("wordpress", "user_id");        
        /** API key for the api function call */
        $api_parameters['api_key'] = $this->GetConfig("general", "api_key");
        /** Used to indicate the type of request */
        $api_parameters['request_type'] = "local";
        /** Used to indicate the format of the api response */
        $output_format = 'array';
        /** The name of the local module to call */
        $module_name = "IslamCompanionApi";
        /** The name of the function to call. This function adds the Hadith Meta data to WordPress */
        $option = "add_hadith_meta_data";
        /** The hadith meta data is added to WordPress */
        $response = $this->GetComponent("api")->MakeApiRequest($api_parameters['request_type'], $option, $module_name, $output_format, $api_parameters, "POST");
        /** The number of posts added */
        $total_posts_added = $response['data']['posts_added'];
        /** The type of data to import */
        $data_to_import = $parameters['imported_data'];
        /** The total number of posts of type Hadith added to total number of posts of type Books */
        $total_hadith_count = (Hadith::GetTotalHadithCount() + Hadith::GetTotalHadithBooksCount());
        /** The progress of adding Hadith data. It is expressed as a percentage of the remaining tasks */
        $total_progress = ($data_to_import == "holy quran and hadith") ? "1" : "2";
        /** The hadith number of the next hadith to import. The import process will start with this hadith */
        $next_hadith = 0;
        /** The next import action is set to importing hadith data */
        $import_action = "importing hadith data";
        /** The data to be displayed */
        $data = array(
            "result" => "success",
            "data" => array(
                "import_action" => $import_action,
                "progress" => $total_progress,
                "next_verse" => $next_hadith
            )
        );
        /** The data is displayed */
        $this->GetComponent("application")->DisplayOutput($data);
        /** The script is terminated so correct response can be sent to the browser */
        wp_die();
    }
    /**
     * Used to add Hadith data
     *
     * It adds WordPress posts containing Hadith data
     * It adds custom posts of type Hadith
     * After adding the custom posts, the function displays the status of the import process
     * This status information is used by the ajax function call
     *
     * @param array $parameters the parameters containing the hadith import data
     *     next_verse => int the hadith number of the next hadith to be added
     *     hadith_language => string [english] the hadith language
     */
    private function AddHadithData($parameters) 
    {
        /** The options id is fetched */
        $options_id = $this->GetComponent("application")->GetOptionsId("options");
        /** The current plugin options are fetched */
        $plugin_options = $this->GetComponent("application")->GetPluginOptions($options_id);
        /** The parameters used to make the api request */
        $api_parameters = array();
        /** Used to indicate the type of database */
        $api_parameters['database_type'] = 'wordpress';
        /** The user id of the logged in user */
        $api_parameters['user_id'] = $this->GetConfig("wordpress", "user_id");
        /** The start hadith */
        $api_parameters['start_hadith'] = $parameters['next_verse'];
        /** The total number of hadith verses to add */
        $api_parameters['hadith_count'] = $this->GetConfig("custom", "verse_import_count");
        /** API key for the api function call */
        $api_parameters['api_key'] = $this->GetConfig("general", "api_key");
        /** Used to indicate the type of request */
        $api_parameters['request_type'] = "local";
        /** Used to indicate the format of the api response */
        $output_format = 'array';
        /** The name of the local module to call */
        $module_name = "IslamCompanionApi";
        /** The name of the function to call. This function adds the Hadith data to WordPress */
        $option = "add_hadith_data";
        /** The hadith data is added to WordPress */
        $response = $this->GetComponent("api")->MakeApiRequest($api_parameters['request_type'], $option, $module_name, $output_format, $api_parameters, "POST");
        /** The number of posts added */
        $posts_added = $response['data']['posts_added'];
        /** The total number of posts added */
        $total_posts_added = ($api_parameters['start_hadith'] + $posts_added);
        /** The type of data to import */
        $data_to_import = $parameters['imported_data'];        
        /** The total number of items to add */
        $total_item_count = ($data_to_import == "holy quran and hadith") ? 26486 : 20025;
        /** The progress of adding Hadith data. It is expressed as a percentage of the remaining tasks */
        $total_progress = floor((($total_posts_added / $total_item_count) * 100));
        /** The hadith number of the next hadith to import. The import process will start with this hadith */
        $next_hadith = ($total_posts_added < Hadith::GetTotalHadithCount()) ? $total_posts_added : 0;
        /** If the hadith data has been imported then the next import action is set to importing quranic author data */
        $import_action = ($total_posts_added >= Hadith::GetTotalHadithCount() && ($data_to_import == "holy quran" || $data_to_import == "holy quran and hadith")) ? "importing quranic author data" : "importing hadith data";        
        /** The data to be displayed */
        $data = array(
            "result" => "success",
            "data" => array(
                "import_action" => $import_action,
                "progress" => $total_progress,
                "next_verse" => $next_hadith
            )
        );
        /** The data is displayed */
        $this->GetComponent("application")->DisplayOutput($data);
        /** The script is terminated so correct response can be sent to the browser */
        wp_die();
    }
    /**
     * Used to handle ajax requests
     *
     * It handles ajax requests used to import the Holy Quran data to WordPress
     */
    public function DataImportAjax() 
    {
        try
        {
            /** The application parameters */
            $parameters = $this->GetConfig("general", "parameters");
            /** The ajax referer value is checked for security */
            check_ajax_referer('islam-companion', 'security');
            /** If custom posts need to be deleted */
            if ($parameters['plugin_action'] == 'deleting custom posts') 
            {
                $this->DeleteHolyQuranHadithData($parameters);
            }
            /** If the quranic meta data needs to be imported to WordPress */
            else if ($parameters['plugin_action'] == 'importing quranic author data' || $parameters['plugin_action'] == 'importing quranic sura data')
            {
                $this->AddHolyQuranMetaData($parameters);
            }
            /** If quranic data needs to be imported to WordPress */
            else if ($parameters['plugin_action'] == 'importing quranic data') 
            {
                $this->AddHolyQuranData($parameters);
            }
            /** If hadith mea data needs to be imported to WordPress */
            else if ($parameters['plugin_action'] == 'importing hadith meta data') 
            {
                $this->AddHadithMetaData($parameters);
            }
            /** If hadith data needs to be imported to WordPress */
            else if ($parameters['plugin_action'] == 'importing hadith data') 
            {
                $this->AddHadithData($parameters);
            }
        }
        catch(\Exception $e) 
        {
            $this->GetComponent("errorhandler")->ExceptionHandler($e);
        }
    }
}


