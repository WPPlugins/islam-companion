<?php
namespace IslamCompanion;
use \Framework\Configuration\Base as Base;
/** 
 * This class implements the functionality of the Hadith shortcodes
 *
 * It contains functions that implement shortcode functionality
 *
 * @category   Application
 * @package    IslamCompanion
 * @author     Nadir Latif <nadir@islamcompanion.org>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 * @version    3.0.6
 */
final class HadithShortCodes extends Base
{
    /**
     * It implements the functionality of the Hadith Navigator shortcode
     *
     * It returns html of hadith navigator
     *
     * @param array $parameters the shortcode parameters given by the user
     *    hadith_language => string [custom] the current hadith language
     *    hadith_source => string [custom] the current hadith source
     *    hadith_book => string [custom] the current hadith book number
     *    hadith_title => string [custom] the current hadith title
     *    tools => string [custom] the tools to use for the navigator. for example: "copy", "dictionary links", "shortcode", "scroll to top", "highlight text"  
     *    action => string [next~previous~current~hadith_book_box~hadith_title_box~hadith_source_box] the action performed by the user on the Hadith Navigator
     *
     * @return string $shortcode_html the shortcode html output
     */
    public function GetHadithNavigator($parameters) 
    {
        try
        {
            /** The default shortcode parameters */
            $parameters = shortcode_atts(array(
                'hadith_language' => 'english',
                'hadith_source' => 'Sahih Muslim',
                'hadith_book' => 'Book : 1. Faith',
                'hadith_title' => 'Chapter 001',                
                'action' => 'current',
                'tools' => '',
                'template' => 'dashboard'
            ) , $parameters);
            /** If the cookie named 'HadithNavigatorState' is set then its value is retrieved */
            if (isset($_COOKIE['HadithNavigatorState'])) {
                $cookie_value = $this->GetComponent("encryption")->DecodeData($_COOKIE['HadithNavigatorState']);
                $parameters['hadith_source'] = $cookie_value['hadith_source'];
                $parameters['hadith_book'] = $cookie_value['hadith_book'];
                $parameters['hadith_title'] = $cookie_value['hadith_title'];
            }
            /** The name of the module to call */
            $module_name = "IslamCompanionApi";
            /** The name of the function to call */
            $option = "get_hadith_navigator";            
            /** The shortcode parameters are fetched */
            $api_parameters = $this->GetHadithNavigatorParameters($parameters);
            /** The api response. The current url contents are fetched and assigned to api response */
            $response = $this->GetComponent("api")->MakeApiRequest($api_parameters['request_type'], $option, $module_name, $api_parameters['output_format'], $api_parameters, "POST");
            /** The shortcode html text */
            $shortcode_html = $response['data']['html'];
            /** The response text is updated */
            $shortcode_html = str_replace("ic-hadith-widget-text", "ic-hadith-dashboard-widget-text", $shortcode_html);
            /** The response text is updated */
            $shortcode_html = str_replace("hadith_navigator_object", "IC_Hadith_Dashboard_Widget", $shortcode_html);
	    /** The response text is updated */
            $shortcode_html = str_replace("navigator_object", "IC_Navigators", $shortcode_html);
            
            return $shortcode_html;
        }
        catch(\Exception $e) 
        {
            $this->GetComponent("errorhandler")->ExceptionHandler($e);
        }
    }
    /**
     * It implements the functionality of the Hadith shortcode
     *
     * It returns hadith text depending on the shortcode parameters
     *
     * @param array $parameters the shortcode parameters given by the user
     *    hadith_source => string [Sahih Muslim~Sahih Bukhari~Abu Dawud~Authentic Supplications of the Prophet~Hadith Qudsi~An Nawawi's Fourty Hadiths~Maliks Muwatta~Shamaa-il Tirmidhi]
     *    hadith_language => string [english] the language for the hadith text
     *    hadith_book => string the hadith book
     *    hadith_title => string the hadith title
     *    hadith_number_start => string the start hadith number
     *    hadith_number_end => string the end hadith number
     *    css_class => string the css class for the hadith text
     *
     * @return string $shortcode_html the shortcode html output
     */
    public function GetHadithText($parameters) 
    {
        try
        {
            $parameters = shortcode_atts(array(                
                'hadith_numbers' => '',
                'css_classes' => '',
                'container' => 'paragraph'
            ) , $parameters);
            
            /** The options id is fetched */
            $options_id = $this->GetComponent("application")->GetOptionsId("options");
            /** The current plugin options */
            $plugin_options = $this->GetComponent("application")->GetPluginOptions($options_id);
            /** The api parameters are set */
            $api_parameters = $parameters;
            /** API key for the api function call */
            $api_parameters['api_key'] = $this->GetConfig("general", "api_key");
            /** Used to indicate the type of database that should be used */
            $api_parameters['database_type'] = "wordpress";
            /** The response format for the function output. If the data source is remote then the response format is set to array. Otherwise it is set to json */
            $api_parameters['output_format'] = "array";
            /** Used to indicate if html of navigator should be in full html page */
            $api_parameters['full_page'] = 0;
            /** Used to indicate the type of request */
            $api_parameters['request_type'] = "local";
            /** The name of the module to call */
            $module_name = "IslamCompanionApi";
            /** The name of the function to call */
            $option = "get_hadith_text";
            /** The api response. The current url contents are fetched and assigned to api response */
            $response = $this->GetComponent("api")->MakeApiRequest($api_parameters['request_type'], $option, $module_name, $api_parameters['output_format'], $api_parameters, "POST");
            /** The shortcode html text */
            $shortcode_html = $response['data'];
            
            return $shortcode_html;
        }
        catch(\Exception $e) 
        {
            $this->GetComponent("errorhandler")->ExceptionHandler($e);
        }
    }       
    /**
     * The parameters for making the api request for getting the Hadith shortcode text
     *
     * @param array $parameters the shortcode parameters given by the user
     *    hadith_source => string [Sahih Muslim~Sahih Bukhari~Abu Dawud~Authentic Supplications of the Prophet~Hadith Qudsi~An Nawawi's Fourty Hadiths~Maliks Muwatta~Shamaa-il Tirmidhi]
     *    hadith_language => string [english] the language for the hadith text
     *    hadith_book => string the hadith book
     *    hadith_title => string the hadith title
     *    hadith_number_start => string the start hadith number
     *    hadith_number_end => string the end hadith number
     *    container => string [paragraph~list] the html element used to contain the verse text     
     *    css_class => string the css class for the hadith text
     *
     * @return array $api_parameters the parameters used to made api call for fetching The Hadith shortcode text
     */
    public function GetHadithNavigatorParameters($parameters) 
    {
        try
        {
            /** The options id is fetched */
            $options_id = $this->GetComponent("application")->GetOptionsId("options");
            /** The current plugin options */
            $plugin_options = $this->GetComponent("application")->GetPluginOptions($options_id);
            /** The api parameters are set */
            $api_parameters = $parameters;
            /** API key for the api function call */
            $api_parameters['api_key'] = $this->GetConfig("general", "api_key");
            /** Used to indicate the type of database that should be used */
            $api_parameters['database_type'] = "wordpress";
            /** The response format for the function output. If the data source is remote then the response format is set to array. Otherwise it is set to json */
            $api_parameters['output_format'] = "array";
            /** The navigator tools */
            $api_parameters['tools'] = "copy,dictionary links,scroll to top";
            /** The navigator options */
            $api_parameters['options'] = "book-title,source,search,subscription";
            /** Used to indicate if html of navigator should be in full html page */
            $api_parameters['full_page'] = 0;
            /** Used to indicate the type of request */
            $api_parameters['request_type'] = "local";
            
            /** The parameters are returned */
            return $api_parameters;
        }
        catch(\Exception $e) 
        {
            $this->GetComponent("errorhandler")->ExceptionHandler($e);
        }
    }
}

