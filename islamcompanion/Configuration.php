<?php
namespace IslamCompanion;
/**
 * Application configuration class
 *
 * Contains application configuration information
 * It provides configuration information and helper objects to the application
 *
 * @category   IslamCompanion
 * @package    Configuration
 * @author     Nadir Latif <nadir@islamcompanion.org>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 * @version    3.0.6
 */
final class Configuration extends \Framework\Frameworks\WordPress\Configuration
{
    /**
     * Used to set the user configuration
     *
     * Defines the user configuration
     * The user configuration is used to override the default configuration
     *
     * @param array $parameters the application parameters given by the user
     */
    public function __construct($parameters) 
    {
        /** The user defined application configuration */
        $this->user_configuration['general']['parameters'] = $parameters;
        /** The name of the plugin */
        $this->user_configuration['wordpress']['plugin_name'] = "Islam Companion";
        /** The plugin version */
        $this->user_configuration['wordpress']['plugin_version'] = "3.0.6";
        /** Test parameters */
        /** Test mode indicates the application will be tested when its run */
        $this->user_configuration['testing']['test_mode'] = false;
        /** Test type indicates the type of application testing. i.e script, functional or unit */
        $this->user_configuration['testing']['test_type'] = 'unit';
        /** The list of classes to unit test */
        $this->user_configuration['testing']['test_classes'] = array(
            "testing"
        );
        /** Used to indicate if application is being developed */
        $this->user_configuration['general']['development_mode'] = false;
        /** API key for the api function call */
        $this->user_configuration['general']['api_key'] = 'cfFFmhZjuLsy7W3KVrnT8CGg';
        /** If the application is in development mode */
        if ($this->user_configuration['general']['development_mode']) 
        {
            /** The url for the Islam Companion http api */
            $this->user_configuration['general']['api_url'] = "http://dev.islamcompanion.org/index.php";
        }
        /** If the application is in production mode */
        else 
        {
            /** The url for the Islam Companion http api */
            $this->user_configuration['general']['api_url'] = "http://www.islamcompanion.org/index.php";
        }
        /** The url of the plugin readme.txt file */
        $this->user_configuration['path']['readme_file_url'] = "http://plugins.svn.wordpress.org/islam-companion/trunk/README.txt";
        /** Used to indicate the number of verses to import at a time */
        $this->user_configuration['custom']['verse_import_count'] = 20;
        /** Used to indicate the number of verses to delete at a time */
        $this->user_configuration['custom']['verse_delete_count'] = 100;
        /** The required framework classes are specified */
        $this->user_configuration['required_objects']['application']['class_name'] = '\IslamCompanion\IslamCompanion';
        $this->user_configuration['required_objects']['settings']['class_name'] = '\IslamCompanion\IslamCompanionSettings';
        $this->user_configuration['required_objects']['testing']['class_name'] = '\IslamCompanion\Testing';
        $this->user_configuration['required_objects']['filesystem']['class_name'] = '\Framework\Utilities\FileSystem';
        $this->user_configuration['required_objects']['logging']['class_name'] = '\Framework\Utilities\Logging';
        $this->user_configuration['required_objects']['profiling']['class_name'] = '\Framework\Utilities\Profiling';
        $this->user_configuration['required_objects']['encryption']['class_name'] = '\Framework\Utilities\Encryption';
        $this->user_configuration['required_objects']['template_helper']['class_name'] = '\Framework\Utilities\Template';
        $this->user_configuration['required_objects']['template']['class_name'] = '\Framework\Templates\BasicSite\Presentation\BasicSiteTemplate';
        $this->user_configuration['required_objects']['holyqurandashboardwidget']['class_name'] = '\IslamCompanion\HolyQuranDashboardWidget';
        $this->user_configuration['required_objects']['hadithdashboardwidget']['class_name'] = '\IslamCompanion\HadithDashboardWidget';
        $this->user_configuration['required_objects']['holyquranshortcodes']['class_name'] = '\IslamCompanion\HolyQuranShortCodes';
        $this->user_configuration['required_objects']['hadithshortcodes']['class_name'] = '\IslamCompanion\HadithShortCodes';
        $this->user_configuration['required_objects']['api']['class_name'] = '\Framework\Application\Api';
        $this->user_configuration['required_objects']['errorhandler']['class_name'] = '\Framework\Utilities\ErrorHandler';
        $this->user_configuration['required_objects']['errorhandler']['parameters']['application_folder'] = 'islamcompanion';
        $this->user_configuration['required_objects']['errorhandler']['parameters']['development_mode'] = $this->user_configuration['general']['development_mode'];
        $this->user_configuration['required_objects']['errorhandler']['parameters']['custom_error_handler'] = array(
            "application",
            "CustomErrorHandler"
        );
        /** If the application is not in test mode, then the custom filters and action are registered */
        if (!$this->user_configuration['testing']['test_mode']) 
        {
            /** Used to specify the WordPress settings menu */
            /** Used to indicate that a settings menu is required */
            $this->user_configuration['wordpress']['use_settings'] = true;
            /** The page title of the settings option */
            $this->user_configuration['wordpress']['settings_page_title'] = $this->user_configuration['wordpress']['plugin_name'];
            /** The menu title of the settings option */
            $this->user_configuration['wordpress']['settings_menu_title'] = $this->user_configuration['wordpress']['plugin_name'];
            /** The minimum access rights for accessing the settings page */
            $this->user_configuration['wordpress']['settings_menu_permissions'] = 'manage_options';
            /** The url of the settings page */
            $this->user_configuration['wordpress']['settings_page_url'] = 'islam-companion-settings-admin';
            /** The callback used to create the settings page content */
            $this->user_configuration['wordpress']['settings_page_content_callback'] = array(
                "application",
                "DisplaySettingsPage"
            );
            /** The callback used to initialize the admin page. This callback can be used to register fields using the WordPress settings api */
            $this->user_configuration['wordpress']['admin_init_callback'] = array(
                "application",
                "InitAdminPage"
            );
            /** The localization information for islam-companion-admin.js */
            /** Used to indicate if application should use sessions */
            $this->user_configuration['general']['enable_sessions'] = true;
            $admin_script_localization = array(
                "name" => "ic-navigators",
                "variable_name" => "IC_L10n",
                "data" => array(
                    'language_alert' => __("Please select a language", "islam-companion") ,
                    'narrator_alert' => __("Please select a narrator", "islam-companion") ,
                    'sura_alert' => __("Please select a sura", "islam-companion") ,
                    'ruku_alert' => __("Please select a ruku", "islam-companion") ,
                    'selected_text_alert' => __("Please select a word", "islam-companion") ,
                    'data_fetch_alert' => __("An error occurred in the Islam Companion Plugin. Please contact the plugin author at https://wordpress.org/support/plugin/islam-companion.", "islam-companion") ,
                    'general_error' => __("An error occurred in the Islam Companion Plugin. Please contact the plugin author at https://wordpress.org/support/plugin/islam-companion.", "islam-companion") ,
                    'language_select_text' => __("Please select a language", "islam-companion") ,
                    'sura_select_text' => __("Please select a sura first", "islam-companion") ,
                    'division_select_text' => __("Please select a division first", "islam-companion") ,
                    'division_number_select_text' => __("Please select a division number first", "islam-companion") ,
                    'select_text' => __("Please Select", "islam-companion") ,
                    'division_alert' => __("Please select a division first", "islam-companion") ,
                    'division_number_alert' => __("Please select a division number first", "islam-companion") ,
                    'ayat_text' => __("Please select an ayat first", "islam-companion") ,
                    'data_import_message' => __("Please click on the Import Data button to start the data import.\nThis will import all the required Quran and Hadith data to your WordPress installation.\nIf you dont want to import the data then select Remote as the Data Source", "islam-companion") ,
                    'data_import_complete' => __("The data has been successfully imported!. Please click Save Changes", "islam-companion") ,
                     'data_removal_complete' => __("The data has been successfully removed!. Please click Save Changes", "islam-companion") ,
                    'data_import_not_completed' => __("Please click on Import Data and complete the data import process", "islam-companion") ,
                    'data_import_error' => __("Data could not be fetched from server. Please reload the page and try again", "islam-companion") ,
                    'shortcode_copied_alert' => __("Shortcode has been copied to the clipboard", "islam-companion"),
                    'hadith_copied_alert' => __("Hadith has been copied to the clipboard", "islam-companion"),
                    'ayat_copied_alert' => __("Ayat has been copied to the clipboard", "islam-companion"),
                    'searchbox_empty_alert' => __("Please enter a search term", "islam-companion")
                )
            );
            /** The list of script names */
            $script_names              = array("ajax", "event-handlers", "load-data", "state");
            /** The WordPress admin javascript files are defined */
            $this->user_configuration['wordpress']['admin_scripts'] = array(
                array(
                    "name" => "ic-admin",
                    "file" => "js/ic-admin.js",
                    "dependencies" => array(
                        "jquery"
                    ) ,
                    "localization" => false
                ) , 
                array(
                    "name" => "ic-navigators",
                    "file" => "js/ic-navigators.js",
                    "dependencies" => array(
                        "jquery"
                    ) ,
                    "localization" => $admin_script_localization
                ) , 
                array(
                    "name" => "ic-utilities",
                    "file" => "../framework/templates/basicsite/js/utilities.js",
                    "dependencies" => array(
                        "jquery"
                    ) ,
                    "localization" => false
                ) , 
                array(
                    "name" => "ic-holy-quran-dashboard-widget",
                    "file" => "js/ic-holy-quran-dashboard-widget.js",
                    "dependencies" => array(
                        "jquery"
                    ) ,
                    "localization" => false
                ) ,
                array(
                    "name" => "ic-hadith-dashboard-widget",
                    "file" => "js/ic-hadith-dashboard-widget.js",
                    "dependencies" => array(
                        "jquery"
                    ) ,
                    "localization" => false
                )
            );            
            /** The WordPress admin css files are defined */
            $this->user_configuration['wordpress']['admin_styles'] = array(
                array(
                    "name" => "ic-admin",
                    "file" => "css/ic-admin.css",
                    "dependencies" => "",
                    "media" => "all"
                ) ,
                array(
                    "name" => "ic-dashboard-widgets",
                    "file" => "css/ic-dashboard-widgets.css",
                    "dependencies" => "",
                    "media" => "all"
                )
            );
            /** The WordPress public javascript files are defined */
            $this->user_configuration['wordpress']['public_scripts'] = array_slice($this->user_configuration['wordpress']['admin_scripts'], 1);
            /** The WordPress public css files are defined */
            $this->user_configuration['wordpress']['public_styles'] = array(
                array(
                    "name" => "ic-frontend-widgets",
                    "file" => "css/ic-frontend-widgets.css",
                    "dependencies" => "",
                    "media" => "all"
                )
            );
            /** The custom wordpress actions are defined. for example ajax callbacks */
            $this->user_configuration['wordpress']['custom_actions'] = array(
                /** Used to add Arabic and Urdu font files to the header */
                array(
                    "name" => "admin_head",
                    "callback" => array(
                        "holyqurandashboardwidget",
                        "AddHolyQuranWidgetFontFile"
                    )
                ) ,
                array(
                    "name" => "wp_head",
                    "callback" => array(
                        "holyqurandashboardwidget",
                        "AddHolyQuranWidgetFontFile"
                    )
                ) ,
                /** Used to setup the Holy Quran and Hadith dashboard widgets */
                array(
                    "name" => "wp_dashboard_setup",
                    "callback" => array(
                        "application",
                        "SetupHolyQuranAndHadithWidgets"
                    )
                ) ,
                /** Ajax call for Holy Quran backend widget */
                array(
                    "name" => "wp_ajax_holyqurandashboardwidget",
                    "callback" => array(
                        "holyqurandashboardwidget",
                        "HolyQuranDashboardWidgetAjax"
                    )
                ) ,
                /** Ajax call for Holy Quran frontend widget */
                array(
                    "name" => "wp_ajax_nopriv_holyqurandashboardwidget",
                    "callback" => array(
                        "holyqurandashboardwidget",
                        "HolyQuranDashboardWidgetAjax"
                    )
                ) ,
                /** Ajax call for Hadith dashboard widget */
                array(
                    "name" => "wp_ajax_hadithdashboardwidget",
                    "callback" => array(
                        "hadithdashboardwidget",
                        "HadithDashboardWidgetAjax"
                    )
                )  ,
                /** Ajax call for Hadith dashboard widget */
                array(
                    "name" => "wp_ajax_nopriv_hadithdashboardwidget",
                    "callback" => array(
                        "hadithdashboardwidget",
                        "HadithDashboardWidgetAjax"
                    )
                ) ,
                /** Ajax call for Holy Quran data import */
                array(
                    "name" => "wp_ajax_dataimport",
                    "callback" => array(
                        "settings",
                        "DataImportAjax"
                    )
                ) ,
                /** Used to add custom post types to WordPress */
                array(
                    "name" => "init",
                    "callback" => array(
                        "application",
                        "AddCustomPostTypes"
                    )
                ) ,
                /** Used to add custom post types to WordPress */
                array(
                    "name" => "admin_notices",
                    "callback" => array(
                        "application",
                        "DisplayAdminNotices"
                    )
                )
            );
            /** The custom wordpress shortcodes are defined */
            $this->user_configuration['wordpress']['custom_shortcodes'] = array(
                /** Shortcode for displaying Holy Quran verses */
                array(
                    "name" => "get-verses",
                    "callback" => array(
                        "holyquranshortcodes",
                        "GetVerses"
                    )
                ) ,
                array(
                    "name" => "get-hadith",
                    "callback" => array(
                        "hadithshortcodes",
                        "GetHadithText"
                    )
                )  ,
                array(
                    "name" => "get-holy-quran-navigator",
                    "callback" => array(
                        "holyquranshortcodes",
                        "GetHolyQuranNavigator"
                    )
                ) ,
                array(
                    "name" => "get-hadith-navigator",
                    "callback" => array(
                        "hadithshortcodes",
                        "GetHadithNavigator"
                    )
                ) ,              
            );
            /** The custom wordpress widgets are defined */
            $this->user_configuration['wordpress']['custom_widgets'] = array(
                /** Widget for displaying Holy Quran verses */
                array(
                    "name" => "Holy Quran Sidebar Widget",
                    "class" => "\IslamCompanion\HolyQuranSidebarWidget"
                ) ,
                /** Widget for displaying Hadith text */
                array(
                    "name" => "Hadith Sidebar Widget",
                    "class" => "\IslamCompanion\HadithSidebarWidget"
                ) ,
            );
        }
        else
        {
            /** The list of callbacks to add to the wordpress xml-rpc interface */
            $this->user_configuration['wordpress']['custom_xmlrpc_methods'] = array(
                array(
                    "testing",
                    "RpcAddHolyQuranData"
                ) ,
                array(
                    "testing",
                    "RpcAddHadithData"
                ) ,
                 array(
                    "testing",
                    "RpcAddHadithMetaData"
                ) ,
                array(
                    "testing",
                    "RpcAddHolyQuranMetaData"
                ) ,
                array(
                    "testing",
                    "RpcDeleteHolyQuranData"
                ) ,
                array(
                    "testing",
                    "RpcDeleteHolyQuranMetaData"
                ) ,                 
                array(
                    "testing",
                    "RpcDeleteHadithMetaData"
                ) ,
                array(
                    "testing",
                    "RpcDeleteHadithData"
                ) ,
                array(
                    "testing",
                    "RpcGetHolyQuranNavigator"
                ) ,
                array(
                    "testing",
                    "RpcGetMetaData"
                ) ,
                array(
                    "testing",
                    "RpcGetVerseText"
                ) ,
                array(
                    "testing",
                    "RpcGetVisitorStatistics"
                ) ,               
                array(
                    "testing",
                    "RpcGetHadithText"
                ) ,
                array(
                    "testing",
                    "RpcGetHadithNavigator"
                ) ,
                array(
                    "testing",
                    "RpcGetHadithSearchResults"
                ) ,
                array(
                    "testing",
                    "RpcGetHolyQuranSearchResults"
                )
            );
            /** The custom wordpress actions are defined. for example ajax callbacks */
            $this->user_configuration['wordpress']['custom_actions'] = array(
                /** Used to add custom post types to WordPress */
                array(
                    "name" => "init",
                    "callback" => array(
                        "application",
                        "AddCustomPostTypes"
                    )
                )
            );
        }
    }
}


