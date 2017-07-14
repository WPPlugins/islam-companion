<?php

namespace IslamCompanion;

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
final class Testing extends \Framework\Testing\Testing
{
    /**
     * Used to get the meta data used by the plugin
     *
     * It fetches all the languages and translators supported by the Islam Companion Api
     * It also fetches the list of suras
     *
     * @param array $args the data containing login information and parameters for the function call
     *
     * @return array $api_response the list of supported languages and translators
     *    result => string [success~error] the result of the api function
     *    data => array the supported meta data
     */
    public function RpcGetMetaData($args) 
    {
        try
        {
            /** The meta data */
            $meta_data = $this->CallIslamCompanionApiFunction($args);
            /** The api function response */
            $api_response = array(
                "result" => "success",
                "data" => $meta_data
            );
            return $api_response;
        }
        catch(\Exception $e) 
        {
            die($e->getMessage());
        }
    }
    /**
     * Used to add the Holy Quran data
     *
     * It adds Wordpress posts of type Ayas
     *
     * @param array $args the data containing login information and parameters for the function call
     *
     * @return array $api_response the number of aya data posts added
     *    result => string [success~error] the result of the api function
     *    data => string the number of added Wordpress posts
     */
    public function RpcAddHolyQuranData($args) 
    {
        try
        {
            /** The number of added Wordpress posts */
            $api_response = $this->CallIslamCompanionApiFunction($args);
            /** The api function response */
            $api_response = array(
                "result" => "success",
                "data" => array(
                    "number_of_posts_added" => $api_response['data']['posts_added']
                )
            );
            return $api_response;
        }
        catch(\Exception $e) 
        {
            die($e->getMessage());
        }
    }
    /**
     * Used to add the Hadith meta data
     *
     * It adds Wordpress posts of type Books
     *
     * @param array $args the data containing login information and parameters for the function call
     *
     * @return array $api_response the number of books data posts added
     *    result => string [success~error] the result of the api function
     *    data => string the number of added Wordpress posts
     */
    public function RpcAddHadithMetaData($args) 
    {
        try
        {
            /** The number of added Wordpress posts */
            $api_response = $this->CallIslamCompanionApiFunction($args);
            /** The api function response */
            $api_response = array(
                "result" => "success",
                "data" => array(
                    "number_of_posts_added" => $api_response['data']['posts_added']
                )
            );
            return $api_response;
        }
        catch(\Exception $e) 
        {
            die($e->getMessage());
        }
    }        
    /**
     * Used to add the Hadith data
     *
     * It adds Wordpress posts of type Hadith
     *
     * @param array $args the data containing login information and parameters for the function call
     *
     * @return array $api_response the number of aya data posts added
     *    result => string [success~error] the result of the api function
     *    data => string the number of added Wordpress posts
     */
    public function RpcAddHadithData($args) 
    {
        try
        {
            /** The number of added Wordpress posts */
            $api_response = $this->CallIslamCompanionApiFunction($args);
            /** The api function response */
            $api_response = array(
                "result" => "success",
                "data" => array(
                    "number_of_posts_added" => $api_response['data']['posts_added']
                )
            );
            return $api_response;
        }
        catch(\Exception $e) 
        {
            die($e->getMessage());
        }
    }
    /**
     * Used to add the Holy Quran meta data
     *
     * It adds Wordpress posts of type Suras and Authors
     *
     * @param array $args the data containing login information and parameters for the function call
     *
     * @return array $api_response the number of meta data posts added
     *    result => string [success~error] the result of the api function
     *    data => int the number of meta data posts added
     */
    public function RpcAddHolyQuranMetaData($args) 
    {
        try
        {
            /** The number of added Wordpress posts */
            $api_response = $this->CallIslamCompanionApiFunction($args);
            /** The api function response */
            $api_response = array(
                "result" => "success",
                "data" => array(
                    "number_of_posts_added" => $api_response['data']['posts_added']
                )
            );
            return $api_response;
        }
        catch(\Exception $e) 
        {
            die($e->getMessage());
        }
    }
    /**
     * Used to delete Hadith data
     *
     * It deletes all data for the Hadith custom post type
     *
     * @param array $args the data containing login information and parameters for the function call
     *
     * @return array $api_response the number of deleted posts
     *    result => string [success~error] the result of the api function
     *    data => int the number of deleted posts
     */
    public function RpcDeleteHadithData($args) 
    {
        try
        {
            /** The number of deleted Wordpress posts */
            $api_response = $this->CallIslamCompanionApiFunction($args);
            /** The api function response */
            $api_response = array(
                "result" => "success",
                "data" => array(
                    "number_of_posts_deleted" => $api_response['data']['posts_deleted']
                )
            );
            return $api_response;
        }
        catch(\Exception $e) 
        {
            die($e->getMessage());
        }
    }
    /**
     * Used to delete Suras and Authors data
     *
     * It deletes all data for the Suras and Authors custom post type
     *
     * @param array $args the data containing login information and parameters for the function call
     *
     * @return array $api_response the number of deleted posts
     *    result => string [success~error] the result of the api function
     *    data => int the number of deleted posts

     */
    public function RpcDeleteHolyQuranMetaData($args) 
    {
        try
        {
            /** The number of deleted Wordpress posts */
            $api_response = $this->CallIslamCompanionApiFunction($args);
            /** The api function response */
            $api_response = array(
                "result" => "success",
                "data" => array(
                    "number_of_posts_deleted" => $api_response['data']['posts_deleted']
                )
            );
            return $api_response;
        }
        catch(\Exception $e) 
        {
            die($e->getMessage());
        }
    }
    /**
     * Used to delete Ayas data
     *
     * It deletes all data for the Ayas custom post type
     *
     * @param array $args the data containing login information and parameters for the function call
     *
     * @return array $api_response the number of deleted posts
     *    result => string [success~error] the result of the api function
     *    data => int the number of deleted posts
     */
    public function RpcDeleteHolyQuranData($args) 
    {
        try
        {
            /** The number of deleted Wordpress posts */
            $api_response = $this->CallIslamCompanionApiFunction($args);
            /** The api function response */
            $api_response = array(
                "result" => "success",
                "data" => array(
                    "number_of_posts_deleted" => $api_response['data']['posts_deleted']
                )
            );
            return $api_response;
        }
        catch(\Exception $e) 
        {
            die($e->getMessage());
        }
    }
    /**
     * Used to fetch the Holy Quran Navigator
     *
     * It fetches the html of the Holy Quran Navigator
     *
     * @param array $args the data containing login information and parameters for the function call
     *
     * @return array $api_response the result of validating the Holy Quran Navigator html using W3C validator
     *    result => string [success~error] the result of the api function
     *    message => string validation message
     */
    public function RpcGetHolyQuranNavigator($args) 
    {
        try
        {
            /** The Holy Quran Navigator is fetched */
            $holy_quran_navigator_html = $this->CallIslamCompanionApiFunction($args);
            /** The result of validating the Holy Quran Navigator html */
            $api_response = $this->ValidateOutput("html", $holy_quran_navigator_html['data']['html']);
            return $api_response;
        }
        catch(\Exception $e) 
        {
            die($e->getMessage());
        }
    }
    /**
     * Used to fetch the Holy Quran Verse Text
     *
     * It fetches the html for the Holy Quran Verse Text
     *
     * @param array $args the data containing login information and parameters for the function call
     *
     * @return array $api_response the result of validating the Holy Quran verse html using W3C validator
     *    result => string [success~error] the result of the api function
     *    message => string validation message
     */
    public function RpcGetVerseText($args) 
    {
        try
        {
            /** The Holy Quran Verse text is fetched */
            $verse_text = $this->CallIslamCompanionApiFunction($args);
            /** The result of validating the Holy Quran Navigator html */
            $api_response = $this->ValidateOutput("html", $verse_text['data']);
            return $api_response;
        }
        catch(\Exception $e) 
        {
            die($e->getMessage());
        }
    }
    /**
     * Used to fetch the Hadith Text
     *
     * It fetches the html for the Hadith Text
     *
     * @param array $args the data containing login information and parameters for the function call
     *
     * @return array $api_response the result of validating the Hadith html using W3C validator
     *    result => string [success~error] the result of the api function
     *    message => string validation message
     */
    public function RpcGetHadithText($args) 
    {
        try
        {
            /** The Hadith text is fetched */
            $hadith_text = $this->CallIslamCompanionApiFunction($args);
            /** The result of validating the Hadith html */
            $api_response = $this->ValidateOutput("html", $hadith_text['data']);
            return $api_response;
        }
        catch(\Exception $e) 
        {
            die($e->getMessage());
        }
    }
    /**
     * Used to fetch the Visitor Statistics
     *
     * It fetches the html for the Visitor Statistics
     *
     * @param array $args the data containing login information and parameters for the function call
     *
     * @return array $api_response the result of validating the Visitor Statistics html using W3C validator
     *    result => string [success~error] the result of the api function
     *    message => string validation message
     */
    public function RpcGetVisitorStatistics($args) 
    {
        try
        {
            /** The Visitor Statistics are fetched */
            $visitor_statistics_html = $this->CallIslamCompanionApiFunction($args);
            /** The result of validating the Hadith html */
            $api_response = $this->ValidateOutput("html", $visitor_statistics_html);
            return $api_response;
        }
        catch(\Exception $e) 
        {
            die($e->getMessage());
        }
    }
    /**
     * Used to fetch the Hadith Navigator
     *
     * It fetches the html of the Hadith Navigator
     *
     * @param array $args the data containing login information and parameters for the function call
     *
     * @return array $api_response the result of validating the Hadith Navigator html using W3C validator
     *    result => string [success~error] the result of the api function
     *    message => string validation message
     */
    public function RpcGetHadithNavigator($args) 
    {
        try
        {
            /** The Hadith Navigator is fetched */
            $hadith_navigator_html = $this->CallIslamCompanionApiFunction($args);
            /** The result of validating the Hadith Navigator html */
            $api_response = $this->ValidateOutput("html", $hadith_navigator_html['data']['html']);
            
            return $api_response;
        }
        catch(\Exception $e) 
        {
            die($e->getMessage());
        }
    }
    /**
     * Used to fetch the Hadith Search Results
     *
     * It fetches the results of searching Hadith data
     * It returns the Hadith Navigator html that contains the Hadith data
     *
     * @param array $args the data containing login information and parameters for the function call
     *
     * @return array $api_response the result of validating the Hadith Navigator html using W3C validator
     *    result => string [success~error] the result of the api function
     *    message => string validation message
     */
    public function RpcGetHadithSearchResults($args) 
    {
        try
        {
            /** The Hadith search results are fetched */
            $verse_text = $this->CallIslamCompanionApiFunction($args);
            /** The result of validating the Hadith search results */
            $api_response = $this->ValidateOutput("html", $verse_text['data']['html']);
            return $api_response;
        }
        catch(\Exception $e) 
        {
            die($e->getMessage());
        }
    }
    /**
     * Used to fetch the Holy Quran Search Results
     *
     * It fetches the results of searching Holy Quran data
     * It returns the Holy Quran html that contains the Holy Quran data
     *
     * @param array $args the data containing login information and parameters for the function call
     *
     * @return array $api_response the result of validating the Holy Quran html using W3C validator
     *    result => string [success~error] the result of the api function
     *    message => string validation message
     */
    public function RpcGetHolyQuranSearchResults($args) 
    {
        try
        {
            /** The Holy Quran search results are fetched */
            $verse_text = $this->CallIslamCompanionApiFunction($args);
            /** The result of validating the Hadith search results */
            $api_response = $this->ValidateOutput("html", $verse_text['data']['html']);
            return $api_response;
        }
        catch(\Exception $e) 
        {
            die($e->getMessage());
        }
    } 
    /**
     * Used to call the Islam Companion Api function
     *
     * It first validates the given login information
     * It then calls the Islam Companion Api function using the given parameters
     *
     * @param array $args the data containing login information and parameters for the function call
     *
     * @return array $api_function_response the response of the api function
     */
    private function CallIslamCompanionApiFunction($args) 
    {
        /** The blog id, user name and password */
        $blog_id = $args['blog_id'];
        $username = $args['user_name'];
        $password = $args['user_password'];
        $user_id = $args['user_id'];
        /** The blog id, user name and password */
        $authentication_result = $this->GetComponent("application")->RpcAuthentication(array(
            $blog_id,
            $username,
            $password,
            $user_id
        ));
        /** If an object was returned then the login information was not correct and the error is returned */
        if (is_object($authentication_result) && isset($authentication_result->message)) die($authentication_result->message);
        try
        {
            /** The parameters used to make the api request */
            $parameters = $args;
            /** The api function response */
            $api_function_response = $this->GetComponent("api")->MakeAPIRequest($parameters['request_type'], $parameters['option'], $parameters['module'], $parameters['output_format'], $parameters, "POST");
            return $api_function_response;            
        }
        catch(\Exception $e) 
        {
            die($e->getMessage());
        }
    }
}

