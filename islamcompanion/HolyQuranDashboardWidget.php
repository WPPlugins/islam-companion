<?php

namespace IslamCompanion;
use \Framework\Configuration\Base as Base;

/** 
 * This class implements the functionality of the Holy Quran dashboard widget
 *
 * It contains functions that are used to display the Holy Quran Dashboard Widget
 *
 * @category   IslamCompanion
 * @package    IslamCompanion
 * @author     Nadir Latif <nadir@islamcompanion.org>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 * @version    3.0.6
 */
final class HolyQuranDashboardWidget extends Base
{
    /**
     * Displays Quranic verses
     *
     * It displays Holy Quran verses on a dashboard widget
     */
    public function DisplayDashboardWidget() 
    {
        try
        {
            /** The options id is fetched */
            $options_id = $this->GetComponent("application")->GetOptionsId("options");
            /** The current plugin options */
            $plugin_options = $this->GetComponent("application")->GetPluginOptions($options_id);
            /** The state parameter is initialized */
            $state = array(
                "action" => "current",
                "sura" => $plugin_options['sura'],
                "language" => $plugin_options['language'],
                "narrator" => $plugin_options['narrator'],
                "division" => $plugin_options['division'],
                "ayat" => $plugin_options['ayat'],
                "ruku" => $plugin_options['ruku'],                                                                
                "division_number" => $plugin_options['division_number']
            );
            /** The Holy Quran Dashboard widget html and state */
            $holy_quran_dashboard_widget = $this->GetHolyQuranDashboardWidget($state, "dashboard");
            /** The response is displayed */
            $this->GetComponent("application")->DisplayOutput($holy_quran_dashboard_widget['html']);
        }
        catch(\Exception $e) 
        {
            $this->GetComponent("errorhandler")->ExceptionHandler($e);
        }
    }
    /**
     * Used to get the Holy Quran dashboard widget html
     *
     * It returns the Holy Quran dashboard widget html
     *
     * @param array state the state of the Holy Quran Dashboard widget
     *     action => string [next~previous~current~sura_box~ruku_box~division_number_box] the action performed on the widget
     *     sura => int [custom] the current sura number
     *     ruku => int [custom] the current sura ruku number
     *     division_number => int [custom] the current division number
     * @param string $view [frontend~dashboard] the view for the dashboard widget
     *     
     * @return array $holy_quran_dashboard_widget the Holy Quran Dashboard widget
     *     html => the Holy Quran Dashboard widget html string. it can be echoed to the browser
     *     state => the state of the Holy Quran Dashboard widget
     */
    public function GetHolyQuranDashboardWidget($state, $view) 
    {
        try
        {
            /** The options id is fetched */
            $options_id = $this->GetComponent("application")->GetOptionsId("options");
            /** The current plugin options */
            $plugin_options = $this->GetComponent("application")->GetPluginOptions($options_id);
            /** If the action value is not search */
            if ($state['action'] != 'search') {
                /** The parameters for making the api request for getting the Holy Quran Navigator */
                $parameters = $this->GetNavigationParameters($state, $view);
                /** The name of the function to call. this function fetches language and narrator data */
                $option = "get_holy_quran_navigator";
            }
            else {
                /** The parameters for making the api request for getting the Holy Quran Navigator */
                $parameters = $this->GetSearchParameters($state, $view);
                /** The name of the function to call. this function fetches language and narrator data */
                $option = "get_holy_quran_search_results";
            }
            /** The name of the module to call */
            $module_name = "IslamCompanionApi";            
            /** The response format for the function output. If the data source is remote then the response format is set to array. Otherwise it is set to json */
            $output_format =  "array";
            /** Used to indicate the type of request */
            $parameters['request_type'] = "local";
            /** The api response. The current url contents are fetched and assigned to api response */
            $response = $this->GetComponent("api")->MakeApiRequest($parameters['request_type'], $option, $module_name, $output_format, $parameters, "POST");
            /** The ajax nonce. It is used to validate the ajax response */
            $ajax_nonce = wp_create_nonce("islam-companion");
            /** The template parameters */
            $template_parameters = array(
                "ajax_nonce_id" => "ic_ajax_nonce_holy_quran",
                "ajax_nonce_name" => "ic_ajax_nonce_holy_quran",
                "value" => $ajax_nonce,
                "css_class" => "widefat"                
            );
            /** The html template is rendered using the given parameters */
            $ajax_nonce_html = $this->GetComponent("template")->Render("ajax_nonce", $template_parameters);
            /** The ajax response is added to the html */
            $holy_quran_dashboard_widget_html = $response['data']['html'] . $ajax_nonce_html;
            /** The id of the main container is updated, so that there is no conflict of id */
	    $holy_quran_dashboard_widget_html = str_replace("ic-navigator-text", "ic-navigator-text-inner", $holy_quran_dashboard_widget_html);
            /** The id of the main container is updated, so the scroll to top function works */
            $holy_quran_dashboard_widget_html = str_replace("ic-quran-widget-text", "ic-navigator-text", $holy_quran_dashboard_widget_html);		        		   
            /** The holy quran widget object name is set to the current object name */
            $holy_quran_dashboard_widget_html = str_replace("holy_quran_navigator_object", "IC_Holy_Quran_Dashboard_Widget", $holy_quran_dashboard_widget_html);
            /** The navigator object name is set to the current object name */
	    $holy_quran_dashboard_widget_html = str_replace("navigator_object", "IC_Navigators", $holy_quran_dashboard_widget_html);
            /** The Holy Quran Dashboard widget */
            $holy_quran_dashboard_widget = array(
                "html" => $holy_quran_dashboard_widget_html,
                "state" => $response['data']['state']
            );
            return $holy_quran_dashboard_widget;
        }
        catch(\Exception $e) 
        {
            $this->GetComponent("errorhandler")->ExceptionHandler($e);
        }
    }
    /**
     * The parameters for making the api request for getting the Holy Quran Dashboard
     *
     * @param array $state the current state of the navigator
     *    division_number => the division number
     *    sura => the selected sura
     *    ruku => the selected ruku
     * @param string $view [frontend~dashboard] the view for the dashboard widget
     *     
     * @return array $parameters the parameters used to make api call for fetching The Holy Quran Dashboard
     */
    public function GetNavigationParameters($state, $view) 
    {
        try
        {
            /** The options id is fetched */
            $options_id = $this->GetComponent("application")->GetOptionsId("options");
            /** The current plugin options */
            $plugin_options = $this->GetComponent("application")->GetPluginOptions($options_id);
            /** The parameters used to make the api request */
            $parameters = array();
            /** The language of the verses */
            $parameters['language'] = $state['language'];
            /** The narrator name */
            $parameters['narrator'] = $state['narrator'];
            /** The sura number */
            $parameters['sura'] = $state['sura'];
            /** The ruku number */
            $parameters['ruku'] = $state['ruku'];
            /** The navigator options */
            $parameters['options'] = "";
            /** The navigator tools. They are different depending on the navigator view */
            $parameters['tools'] = ($view == "frontend") ? "copy,dictionary links,scroll to top" : "copy,dictionary links,shortcode,scroll to top";
            /** The navigator template to use */
            $parameters['template'] = 'dashboard';
            /** The navigator layout to use */
            $parameters['layout'] = 'double column';
            /** The division name */
            $parameters['division'] = strtolower($state['division']);
            /** The division number */
            $parameters['division_number'] = ($parameters['division'] == "ruku") ? $parameters['ruku'] : $state['division_number'];
            /** The start ayat */
            $parameters['ayat'] = $state['ayat'];
            /** The response format for the function output. If the data source is local then the response format is set to array. Otherwise it is set to json */
            $parameters['output_format'] = "array";
            /** API key for the api function call */
            $parameters['api_key'] = $this->GetConfig("general", "api_key");
            /** Used to indicate the type of database that should be used */
            $parameters['database_type'] = "wordpress";
            /** The action performed */
            $parameters['action'] = $state['action'];
            /** The parameters are returned */
            return $parameters;
        }
        catch(\Exception $e) 
        {
            $this->GetComponent("errorhandler")->ExceptionHandler($e);
        }
    }
    /**
     * The parameters for making the api request for getting the Holy Quran Dashboard Widget search results
     *
     * @param array $parameters the current search results state of the navigator
     *    page_number => the page number
     *    search_text => the search text
     * @param string $view [frontend~dashboard] the view for the dashboard widget
     *
     * @return array $parameters the parameters used to make api call for fetching The Holy Quran Dashboard widget search results
     */
    public function GetSearchParameters($state, $view) 
    {
        try
        {
            /** The options id is fetched */
            $options_id = $this->GetComponent("application")->GetOptionsId("options");
            /** The current plugin options */
            $plugin_options = $this->GetComponent("application")->GetPluginOptions($options_id);
            /** The parameters used to make the api request */
            $parameters = array();
            /** The language of the verses */
            $parameters['language'] = $plugin_options['language'];
            /** The narrator name */
            $parameters['narrator'] = $plugin_options['narrator'];
            /** The page number of the search results */
            $parameters['page_number'] = $state['page_number'];       
            /** The search text to be searched */
            $parameters['search_text'] = $state['search_text'];                  
            /** The response format for the function output. If the data source is remote then the response format is set to array. Otherwise it is set to json */
            $parameters['output_format'] = "array";
            /** The navigator options */
            $parameters['options'] = "";
            /** The navigator search results layout */
            $parameters['layout'] = "navigator";
            /** The navigator tools. They are different depending on the navigator view */
            $parameters['tools'] = ($view == "frontend") ? "copy,dictionary links,scroll to top" : "copy,dictionary links,shortcode,scroll to top";
            /** API key for the api function call */
            $parameters['api_key'] = $this->GetConfig("general", "api_key");
            /** Used to indicate the type of database that should be used */
            $parameters['database_type'] = "wordpress";
            /** The action performed */
            $parameters['action'] = "search";
            /** The css styles for the Holy Quran navigator */
            $parameters['style'] = array();
            /** The parameters are returned */
            return $parameters;
        }
        catch(\Exception $e) 
        {
            $this->GetComponent("errorhandler")->ExceptionHandler($e);
        }
    }
    /**
     * The dashboard widget settings are updated
     *
     * The wordpress dashboard options for the Holy Quran Dashboard widget are updated
     * using the information returned by module api call
     *
     * @param array $parameters the navigation settings returned by call to local api module
     */
    public function UpdateSettings($parameters) 
    {
        try
        {
            /** The options id is fetched */
            $options_id = $this->GetComponent("application")->GetOptionsId("options");
            /** The current plugin options */
            $plugin_options = $this->GetComponent("application")->GetPluginOptions($options_id);
            /** The navigator state returned by api call is merged with current plugin options */
            $plugin_options = array_merge($plugin_options, $parameters);
            /** The plugin options are saved */
            $this->GetComponent("application")->SavePluginOptions($plugin_options, $options_id);
        }
        catch(\Exception $e) 
        {
            $this->GetComponent("errorhandler")->ExceptionHandler($e);
        }
    }
    /**
     * Function that is used to handle ajax request
     *
     * It handles the ajax call made by Holy Quran Dashboard widget
     */
    public function HolyQuranDashboardWidgetAjax() 
    {
        try
        {
            /** The application parameters */
            $parameters = $this->GetConfig("general", "parameters");
            /** The state argument is decoded */
            $parameters['state']    = $this->GetComponent("encryption")->DecodeData($parameters['state']);
            /** If the navigator is not being displayed on the frontend, then the ajax referer value is checked for security */
            if ($parameters['view'] != "frontend") check_ajax_referer('islam-companion', 'security');
            /** If verse data needs to be fetched */
            if ($parameters['plugin_action'] == 'fetch_navigator_data') 
            {
                /** The Holy Quran Dashboard widget html and state */
                $holy_quran_dashboard_widget = $this->GetHolyQuranDashboardWidget($parameters['state'], $parameters['view']);
                /** The navigator settings are updated using response from api call to local module. The settings are only updated if navigator is not being displayed on the frontend */
                if ($parameters['view'] != "frontend") $this->UpdateSettings($holy_quran_dashboard_widget['state']);
                /** The response to ajax call */
                $response = json_encode(array(
                    "result" => "success",
                    "text" => $holy_quran_dashboard_widget['html'],
                    "state" => $holy_quran_dashboard_widget['state']
                ));
                /** The response is displayed */
                $this->GetComponent("application")->DisplayOutput($response);
                /** The script is terminated so correct response can be sent to the browser */
                wp_die();
            }
            /** If verse data needs to be searched */
            else if ($parameters['plugin_action'] == 'search_verse_data') 
            {
                /** The Holy Quran Dashboard widget html and state */
                $holy_quran_dashboard_widget = $this->GetHolyQuranDashboardWidget($parameters['state'], $parameters['view']);
                /** The response to ajax call */
                $response = json_encode(array(
                    "result" => "success",
                    "text" => $holy_quran_dashboard_widget['html']
                ));
                /** The response is displayed */
                $this->GetComponent("application")->DisplayOutput($response);
                /** The script is terminated so correct response can be sent to the browser */
                wp_die();
            }
        }
        catch(\Exception $e) 
        {
            $this->GetComponent("errorhandler")->ExceptionHandler($e);
        }
    }
    /**
     * Adds the Arabic font file to the page header
     *
     * It adds the font-face tag to the page header
     * It includes the Arabic font file
     */
    public function AddHolyQuranWidgetFontFile() 
    {
        try
        {
            /** The list of extra font files */
            $font_file_list = array(
                "amiri-quran",
                "NafeesWeb"
            );
            /** Each font file is displayed */
            for ($count = 0;$count < count($font_file_list);$count++) 
            {
                /** The url of the Arab Type font file */
                $font_file_url = $this->GetConfig("path", "application_folder_url") . "/data/" . $font_file_list[$count] . ".ttf";
                /** The font face tag used to include the ttf file */
                $font_face_tag = '<style>@font-face {font-family: ' . $font_file_list[$count] . ';src: url(' . $font_file_url . ');}</style>' . "\n";
                /** The font face tags are displayed */
                $this->GetComponent("application")->DisplayOutput($font_face_tag);
            }
        }
        catch(\Exception $e) 
        {
            $this->GetComponent("errorhandler")->ExceptionHandler($e);
        }
    }
}

