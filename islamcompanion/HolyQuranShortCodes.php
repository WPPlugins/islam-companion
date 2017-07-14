<?php
namespace IslamCompanion;
use \Framework\Configuration\Base as Base;
/** 
 * This class implements the functionality of the Holy Quran shortcodes
 *
 * It contains functions that implement shortcode functionality
 *
 * @category   Application
 * @package    IslamCompanion
 * @author     Nadir Latif <nadir@islamcompanion.org>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 * @version    3.0.6
 */
final class HolyQuranShortCodes extends Base
{
    /**
     * It implements the functionality of the Holy Quran Navigator shortcode
     *
     * It returns html of holy quran navigator
     *
     * @param array $parameters the shortcode parameters given by the user
     *    language => string [custom] the language for the quran translation
     *    narrator => string [custom] the narrator for the quran translation
     *    sura => int [custom] the current sura number
     *    ruku => int [custom] the current sura ruku number
     *    division => string [manzil~juz~hizb~ruku] the current division
     *    division_number => int [custom] the current division number
     *    ayat => int [custom] the current sura ayat
     *    action => string [next~previous~current~sura_box~ruku_box~division_number_box] the action performed by the user on the Holy Quran Navigator
     *    tools => string [custom] the tools to use for the navigator. for example: "copy", "dictionary links", "shortcode", "scroll to top", "highlight text"  
     *
     * @return string $shortcode_html the shortcode html output
     */
    public function GetHolyQuranNavigator($parameters) 
    {
        try
        {
            /** The default shortcode parameters */
            $parameters = shortcode_atts(array(
                'narrator' => 'Mohammed Marmaduke William Pickthall',
                'language' => 'English',
                'sura' => '1',
                'ruku' => '1',
                'division' => 'ruku',
                'division_number' => '1',
                'ayat' => '1',
                'api_key' => '',
                'tools' => '',
                'template' => 'dashboard',
                'layout' => 'double column',
                'action' => 'current'
            ) , $parameters);            
            /** If the cookie named 'HolyQuranNavigatorState' is set then its value is retrieved */
            if (isset($_COOKIE['HolyQuranNavigatorState'])) {
                $cookie_value = $this->GetComponent("encryption")->DecodeData($_COOKIE['HolyQuranNavigatorState']);
                $parameters['sura'] = $cookie_value['sura'];
                $parameters['division_number'] = $cookie_value['division_number'];
                $parameters['ruku'] = $cookie_value['ruku'];
                $parameters['ayat'] = $cookie_value['ayat'];
            }
            /** The name of the module to call */
            $module_name = "IslamCompanionApi";
            /** The name of the function to call */
            $option = "get_holy_quran_navigator";                              
            /** The shortcode parameters are fetched */
            $api_parameters = $this->GetHolyQuranNavigatorShortCodeParameters($parameters);
            /** The api response. The current url contents are fetched and assigned to api response */
            $response = $this->GetComponent("api")->MakeApiRequest($api_parameters['request_type'], $option, $module_name, $api_parameters['output_format'], $api_parameters, "POST");            
            /** The shortcode html text */
            $shortcode_html = $response['data']['html'];
            /** The response text is updated */
            $shortcode_html = str_replace("ic-quran-widget-text", "ic-quran-frontend-widget-text", $shortcode_html);
            /** The response text is updated */
            $shortcode_html = str_replace("holy_quran_navigator_object", "IC_Holy_Quran_Frontend_Widget", $shortcode_html);
            $shortcode_html = str_replace("holy_quran_navigator_event_object", "IC_Holy_Quran_Frontend_Widget", $shortcode_html);
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
     * It implements the functionality of the Holy Quran shortcode
     *
     * It returns html for the Holy Quran navigator
     *
     * @param array $api_parameters the shortcode parameters given by the user
     *    narrator => string the narrator for the translation
     *    language => string the language for the translation
     *    sura => int the sura id
     *    start_ayat => int the start sura ayat number
     *    end_ayat => int the end sura ayat number
     *    container => string [plain text~list] the html element used to contain the verse text  
     *    css_class => string the css class for the container element
     *
     * @return string $shortcode_html the shortcode html output
     */
    public function GetHolyQuranNavigatorShortCodeParameters($parameters) 
    {
        /** The options id is fetched */
        $options_id = $this->GetComponent("application")->GetOptionsId("options");
        /** The current plugin options */
        $plugin_options = $this->GetComponent("application")->GetPluginOptions($options_id);        
        /** The parameters are updated */
        $api_parameters = $parameters;
        /** The division name */
        $api_parameters['division'] = strtolower($parameters['division']); 
        /** The response format for the function output. If the data source is local then the response format is set to array. Otherwise it is set to json */
        $api_parameters['output_format'] = "array";
        /** API key for the api function call */
        $api_parameters['api_key'] = $this->GetConfig("general", "api_key");
        /** The request type for the api function call */
        $api_parameters['request_type'] = "local";
        /** Used to indicate the type of database that should be used */
        $api_parameters['database_type'] = "wordpress";
        /** The navigator tools */
        $api_parameters['tools'] = "copy,dictionary links,scroll to top";
        /** The navigator options */
        $api_parameters['options'] = "search,sura-ruku-ayat";
            
        /** The parameters are returned */
        return $api_parameters;   
    }    
    /**
     * It implements the functionality of the Holy Quran shortcode
     *
     * It returns verse text and audio player depending on the shortcode parameters
     *
     * @param array $parameters the shortcode parameters given by the user
     *    narrator => string the narrator for the translation
     *    language => string the language for the translation
     *    sura => int the sura id
     *    start_ayat => int the start sura ayat number
     *    end_ayat => int the end sura ayat number
     *    container => string [plain text~list] the html element used to contain the verse text
     *    transformation => string [none~random~slideshow] the transformation to be applied to the text        
     *    css_class => string the css class for the container element
     *
     * @return string $shortcode_html the shortcode html output
     */
    public function GetVerses($parameters) 
    {
        try
        {
            $parameters = shortcode_atts(array(
                'narrator' => 'Mohammed Marmaduke William Pickthall',
                'language' => 'English',
                'ayas' => '1:1-7',               
                'container' => 'list',
                'transformation' => 'none',
                'css_class' => ''
            ) , $parameters);
            /** The shortcode parameters are fetched */
            $api_parameters = $this->GetVerseShortCodeParameters($parameters);
            /** The name of the module to call */
            $module_name = "IslamCompanionApi";
            /** The name of the function to call */
            $option = "get_verse_text";
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
     * The parameters for making the api request for getting the Holy Quran Shortcode text
     *
     * @param array $parameters the shortcode parameters given by the user
     *    narrator => string the narrator for the translation
     *    language => string the language for the translation
     *    ayas => string the list of ayas in sura
     *    container => string [plain text~list] the html element used to contain the verse text
     *    transformation => string [none~random~slideshow] the transformation to be applied to the text     
     *    css_class => string the css classes for the container elements
     *
     * @return array $api_parameters the parameters used to made api call for fetching The Holy Quran Shortcode text
     */
    public function GetVerseShortCodeParameters($parameters) 
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
            /** Used to indicate the type of request */
            $api_parameters['request_type'] = "local";
            /** The language for the verse text */
            $api_parameters['language'] = ($parameters['language'] != '') ? $parameters['language'] : $plugin_options['language'];
            /** The narrator for the verse text */
            $api_parameters['narrator'] = ($parameters['narrator'] != '') ? $parameters['narrator'] : $plugin_options['narrator'];
            /** The spaces are removed from the ayas */
            $api_parameters['ayas'] = str_replace(" ", "", $api_parameters['ayas']);
            
            /** The parameters are returned */
            return $api_parameters;
        }
        catch(\Exception $e) 
        {
            $this->GetComponent("errorhandler")->ExceptionHandler($e);
        }
    }
}

