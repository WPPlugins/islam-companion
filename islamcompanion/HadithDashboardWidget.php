<?php
namespace IslamCompanion;
use \Framework\Configuration\Base as Base;
/** 
 * This class implements the functionality of the Hadith dashboard widget
 *
 * It contains functions that are used to display the Hadith Dashboard Widget
 *
 * @category   IslamCompanion
 * @package    IslamCompanion
 * @author     Nadir Latif <nadir@islamcompanion.org>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 * @version    3.0.6
 */
final class HadithDashboardWidget extends Base
{
    /**
     * Displays Hadith text
     *
     * It displays Hadith text on a dashboard widget
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
                "hadith_language" => $plugin_options['hadith_language'],                                
                "hadith_book" => $plugin_options['hadith_book'],                
                "hadith_title" => $plugin_options['hadith_title'],      
                "hadith_source" => $plugin_options['hadith_source']                
            );
            /** The Hadith Dashboard widget html and state */
            $hadith_dashboard_widget = $this->GetHadithDashboardWidget($state, "dashboard");
            /** The response is displayed */
            $this->GetComponent("application")->DisplayOutput($hadith_dashboard_widget['html']);
        }
        catch(\Exception $e) 
        {
            $this->GetComponent("errorhandler")->ExceptionHandler($e);
        }
    }
    /**
     * The parameters for making the api request for getting the Hadith Dashboard Widget search results
     *
     * @param array $state the current search results state of the navigator
     *    page_number => the page number
     *    search_text => the search text
     *
     * @return array $parameters the parameters used to make api call for fetching The Hadith Dashboard widget search results
     */
    public function GetSearchParameters($state) 
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
            $parameters['language'] = $state['hadith_language'];
            /** The page number of the search results */
            $parameters['page_number'] = $state['page_number'];       
            /** The search text to be searched */
            $parameters['search_text'] = $state['search_text'];            
            /** The response format for the function output. If the data source is remote then the response format is set to array. Otherwise it is set to json */
            $parameters['output_format'] = "array";
            /** The navigator tools */
            $parameters['tools'] = "copy,dictionary links,scroll to top";
            /** API key for the api function call */
            $parameters['api_key'] = $this->GetConfig("general", "api_key");
            /** Used to indicate the type of database that should be used */
            $parameters['database_type'] = "wordpress";
            /** The action performed */
            $parameters['action'] = "search";
            /** The parameters are returned */
            return $parameters;
        }
        catch(\Exception $e) 
        {
            $this->GetComponent("errorhandler")->ExceptionHandler($e);
        }
    }
    /**
     * Used to get the Hadith dashboard widget html
     *
     * It returns the Hadith dashboard widget html
     *
     * @param array state the state of the Hadith Dashboard widget
     *     action => string [next~previous~current~book_box~title_box] the action performed on the widget
     *     book => string [custom] the current hadith book
     *     title => string [custom] the current hadith title
     * @param string $view [frontend~dashboard] the view for the dashboard widget
     *     
     * @return array $hadith_dashboard_widget the Hadith Dashboard widget
     *     html => the Hadith Dashboard widget html string. it can be echoed to the browser
     *     state => the state of the Hadith Dashboard widget
     */
    public function GetHadithDashboardWidget($state, $view) 
    {
        try
        {
            /** The options id is fetched */
            $options_id = $this->GetComponent("application")->GetOptionsId("options");
            /** The current plugin options */
            $plugin_options = $this->GetComponent("application")->GetPluginOptions($options_id);            
            /** The name of the module to call */
            $module_name = "IslamCompanionApi";
            /** If the action value is not search */
            if ($state['action'] != 'search') {
                /** The parameters for making the api request for getting the Hadith Navigator */
                $parameters = $this->GetHadithDashboardParameters($state, $view);
                /** The name of the function to call. this function fetches language and narrator data */
                $option = "get_hadith_navigator";
            }
            else {
                /** The parameters for making the api request for getting the Holy Quran Navigator */
                $parameters = $this->GetSearchParameters($state);
                /** The name of the function to call. this function fetches language and narrator data */
                $option = "get_hadith_search_results";
            }
            /** The response format for the function output. If the data source is remote then the response format is set to array. Otherwise it is set to json */
            $output_format = "array";
            /** Used to indicate the type of request */
            $parameters['request_type'] = "local";
            /** The api response. The current url contents are fetched and assigned to api response */
            $response = $this->GetComponent("api")->MakeApiRequest($parameters['request_type'], $option, $module_name, $output_format, $parameters, "POST");
            /** The ajax nonce. It is used to validate the ajax response */
            $ajax_nonce = wp_create_nonce("islam-companion");
            /** The template parameters */
            $template_parameters = array(
                "ajax_nonce_id" => "ic_ajax_nonce_hadith",
                "ajax_nonce_name" => "ic_ajax_nonce_hadith",
                "value" => $ajax_nonce,
                "css_class" => "widefat"
            );
            /** The html template is rendered using the given parameters */
            $ajax_nonce_html = $this->GetComponent("template")->Render("ajax_nonce", $template_parameters);
            /** The ajax response is added to the html */
            $hadith_dashboard_widget_html = $response['data']['html'] . $ajax_nonce_html;            
            /** The id of the main container is updated, so that there is no conflict of id */
            $hadith_dashboard_widget_html = str_replace("ic-hadith-dashboard-widget-text", "ic-hadith-dashboard-widget-text-inner", $hadith_dashboard_widget_html);
	    /** The id of the main container is updated, so that there is no conflict of id */
	    $hadith_dashboard_widget_html = str_replace("ic-hadith-widget-text", "ic-hadith-dashboard-widget-text", $hadith_dashboard_widget_html);           
	    /** The hadith widget object name is set to the current object name */
	    $hadith_dashboard_widget_html = str_replace("hadith_navigator_object", "IC_Hadith_Dashboard_Widget", $hadith_dashboard_widget_html);
	    /** The navigator object name is set to the current object name */
	    $hadith_dashboard_widget_html = str_replace("navigator_object", "IC_Navigators", $hadith_dashboard_widget_html);
            /** The Hadith Dashboard widget */
            $hadith_dashboard_widget = array(
                "html" => $hadith_dashboard_widget_html,
                "state" => $response['data']['state']
            );
            return $hadith_dashboard_widget;
        }
        catch(\Exception $e) 
        {
            $this->GetComponent("errorhandler")->ExceptionHandler($e);
        }
    }
    /**
     * The parameters for making the api request for getting the Hadith Dashboard
     *
     * @param array $state the current state of the navigator. it is an array with following keys:
     *    book => the hadith book
     *    title => the hadith title
     * @param string $view [frontend~dashboard] the view for the dashboard widget
     *     
     * @return array $parameters the parameters used to made api call for fetching The Hadith Dashboard
     */
    public function GetHadithDashboardParameters($state, $view) 
    {
        try
        {
            /** The options id is fetched */
            $options_id = $this->GetComponent("application")->GetOptionsId("options");
            /** The current plugin options */
            $plugin_options = $this->GetComponent("application")->GetPluginOptions($options_id);
            /** The parameters used to make the api request */
            $parameters = array();
            /** The language of the hadith */
            $parameters['hadith_language'] = $state['hadith_language'];
            /** The hadith source */
            $parameters['hadith_source'] = $state['hadith_source'];
            /** The hadith book */
            $parameters['hadith_book'] = $state['hadith_book'];            
            /** The hadith number */
            $parameters['hadith_title'] = $state['hadith_title'];
            /** The navigator options */
            $parameters['options'] = "";
            /** The navigator tools. They are different depending on the navigator view */
            $parameters['tools'] = ($view == "frontend") ? "copy,dictionary links,scroll to top" : "copy,dictionary links,shortcode,scroll to top";
            /** The navigator layout to use */
            $parameters['template'] = "dashboard";                
            /** The response format for the function output. If the data source is remote then the response format is set to array. Otherwise it is set to json */
            $parameters['output_format'] = "array";
            /** API key for the api function call */
            $parameters['api_key'] = $this->GetConfig("general", "api_key");
            /** Used to indicate the type of database that should be used */
            $parameters['database_type'] = "wordpress";
            /** The action performed */
            $parameters['action'] = $state['action'];
            /** The css styles for the Hadith navigator */
            $parameters['style'] = "";
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
     * The wordpress dashboard options for the Hadith Dashboard widget are updated
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
     * It handles the ajax call made by Hadith Dashboard widget
     */
    public function HadithDashboardWidgetAjax() 
    {
        try
        {
            /** The application parameters */
            $parameters = $this->GetConfig("general", "parameters");
            /** The state argument is decoded */
            $parameters['state']    = $this->GetComponent("encryption")->DecodeData($parameters['state']);
            /** The ajax referer value is checked for security */
            if ($parameters['view'] != "frontend") check_ajax_referer('islam-companion', 'security');
            /** If verse data needs to be fetched */
            if ($parameters['plugin_action'] == 'fetch_hadith_data') 
            {
                /** The Hadith Dashboard widget html and state */
                $hadith_dashboard_widget = $this->GetHadithDashboardWidget($parameters['state'], $parameters['view']);
                              
                /** The dashboard widget settings are updated using response from api call to local module. The settings are updated only if then navigator is not being displayed on the website frontend */
                if ($parameters['view'] != "frontend") $this->UpdateSettings($hadith_dashboard_widget['state']);
                /** The response to ajax call */
                $response = json_encode(array(
                    "result" => "success",
                    "text" => $hadith_dashboard_widget['html'],                    
                    "state" => $hadith_dashboard_widget['state']
                ));
                /** The response is displayed */
                $this->GetComponent("application")->DisplayOutput($response);
                /** The script is terminated so correct response can be sent to the browser */
                wp_die();
            }
            /** If verse data needs to be searched */
            else if ($parameters['plugin_action'] == 'search_hadith_data') 
            {
                /** The Hadith Dashboard widget html and state */
                $hadith_dashboard_widget = $this->GetHadithDashboardWidget($parameters['state'], $parameters['view']);
                /** The response to ajax call */
                $response = json_encode(array(
                    "result" => "success",
                    "text" => $hadith_dashboard_widget['html']
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
}

