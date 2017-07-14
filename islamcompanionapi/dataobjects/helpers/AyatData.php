<?php

namespace IslamCompanionApi\DataObjects\Helpers;

use IslamCompanionApi\DataObjects\HolyQuran as HolyQuran;

/**
 * It provides functions for retrieving Ayat data
 *
 * It provides functions that allow searching Ayat data uses different conditions
 *
 * @category   IslamCompanionApi
 * @package    UiObjects\Helpers
 * @author     Nadir Latif <nadir@pakjiddat.pk>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 */
trait AyatData
{
    /**
     * Used to read the ayat meta data from database
     *
     * It returns the required ayat meta data
     * The meta data includes language, narrator and verse presentation information
     *
     * @param array $parameters the parameters needed for reading the ayat data
     * @param string $user_interface [plain text~list~navigator~email] the user interface where the ayat data will be displayed
     *
     * @return array $ayat_data the required ayat data
     */
    public function ReadVerseData($parameters, $user_interface)
    {
        /** The required ayat data */
        $ayat_data                      = $parameters;
        /** The language information is fetched */
        $language_data                  = $this->GetLanguageInformation($parameters["parameters"]["language"]);
        /** The language is set to the object's local data property */
        $ayat_data['language']          = $parameters['parameters']['language'];
        /** The narrator is set to the object's local data property */
        $ayat_data['narrator']          = $parameters['parameters']['narrator'];
        /** The language code is set to the object's local data property */
        $ayat_data['language_code']     = $language_data['language_code'];
        /** The rtl property of the language is set */
        $ayat_data['rtl']               = $language_data['rtl'];
        /** The css class property of the verse text is set */
        $ayat_data['css_class']         = $language_data['css_class'];
        /** The css list class property of the verse text is set */
        $ayat_data['css_attributes']    = $language_data['css_attributes'];
        /** The user interface property is set */
        $ayat_data['user_interface']    = $user_interface;
        /** If the user interface is email */
        if ($user_interface == "email") {
            /** The ayat list */
            list($ayat_data['ayat_list'], $ayat_data['total_results'])     = $this->SearchAyatData($parameters);
            /** The sura information for the first ayat */
            $ayat_data['sura_data']     = $this->GetComponent("suras")->GetSuraData($ayat_data['ayat_list'][0]['sura']);
        }
        /** If the user interface is plain text, list or table */
        if ($user_interface == "plain text" || $user_interface == "list") {
            /** The ayat list */
            $ayat_data['ayat_list']     = $this->GetAyatDataById($parameters);
        }
        /** If the user interface is navigator */
        else if ($user_interface == "navigator") {
            /** The start and end ayat for the ruku */
            $ayat_information           = $this->GetComponent("rukus")->GetStartAndEndAyatOfRuku($parameters["parameters"]["ruku"]);
            /** The ayat list */
            $ayat_data['ayat_list']     = $this->GetComponent("ayat")->GetAyasInSura($parameters["parameters"]["sura"], $ayat_information["start_ayat"], $ayat_information["end_ayat"]);
            /** The start ayat */
            $ayat_data['start_ayat']    = $ayat_information['start_ayat'];
            /** The sura information for the first ayat */
            $ayat_data['sura_data']     = $this->GetComponent("suras")->GetSuraData($ayat_data['ayat_list'][0]['sura']);
        }
        /** If the user interface is search results */
        if ($user_interface == "search results") {
            /** The ayat list */
            $ayat_data['ayat_list']     = $this->GetComponent("ayat")->SearchAyas($parameters['parameters']['search_text'], ($parameters['parameters']['narrator'] != 'Original Arabic') ? true : false, "sequence");
            /** The number of verses to display per page in the search results */
            $ayat_data['verses_per_page'] = $this->GetConfig("custom", "verses_per_page");
            /** The page number of the search results */
            $ayat_data['page_number']   = $parameters['parameters']['page_number'];
            /** The search text */
            $ayat_data['search_text']   = $parameters['parameters']['search_text'];
            /** The total number of results */
            $ayat_data['total_results'] = (count($ayat_data['ayat_list']));
            /** The total number of pages */
            $ayat_data['total_number_of_pages']      = ceil($ayat_data['total_results'] / $ayat_data['verses_per_page']);
            /** If no results were found then notification message is returned */
            if ($ayat_data['total_number_of_pages'] == 0) {
                /** The search results pagination */
                $ayat_data['ayat_list'] = $this->GetComponent("template")->Render("no_search_results", array());
            }            
            /** The ayas in the search results */
            else {
                $ayat_data['ayat_list'] = array_slice($ayat_data['ayat_list'], (($ayat_data['page_number'] - 1) * $ayat_data['verses_per_page']) , $ayat_data['verses_per_page']);
            }
        }

        return $ayat_data;
    }
    /**
     * Used to get the required ayat data by id
     *
     * It returns the ayat and sura data for the required ayas
     *
     * @param array $parameters the parameters for the function
     *    language => string the language for the translation
     *    narrator => string the narrator for the translation     
     *    ayas => string the comma separated list of ayas. each aya is of the form sura:ayat
     *    transformation => string [none~random~slideshow] the transformation to be applied to the text     
     *
     * @return array $ayat_data the required ayat data
     */
    public function GetAyatDataById($parameters) 
    {
        /** The required ayat data */
        $ayat_data                          = array();
        /** The list of required ayas */
        $required_ayat_list                 = explode(",", $parameters['parameters']['ayas']);
        /** Each ayat text is placed inside a list tag */
        for ($count = 0; $count < count($required_ayat_list); $count++)
        {
            list($sura, $ayat_id)           = explode(":", $required_ayat_list[$count]);
            /** The where clause used to search for the ayas */
            $where_clause                   = array(array('field' => "sura_ayat_id", 'value' => $ayat_id, 'operation' => " = ", 'operator' => " AND "), array('field' => "sura", 'value' => $sura, 'operation' => " = "));
            /** The translated ayat list */
            $ayat_details                   = $this->GetComponent("ayat")->SearchAyas("", true, "sequence", $where_clause);
            /** The ayat id */
            $ayat_id                        = $ayat_details[0]['sura'] . ":" . $ayat_details[0]['sura_ayat_id'];
            /** The required sura data */
            $sura_data                      = $this->GetComponent("suras")->GetSuraData($ayat_details[0]['sura']);
            /** The required ayat data is updated */
            $ayat_data                      = array_merge($ayat_data, array(array("translated_text" => $ayat_details[0]['translated_text'], "sura_ayat_id" => $ayat_details[0]['sura_ayat_id'], "sura_data" => $sura_data)));
        }
        
        return $ayat_data;        
    }
    /**
     * Used to get the ayat text requested by the subscribers
     *
     * It returns html containing the ayat text
     *
     * @param array $parameters the parameters for the function
     *    language => string the language for the translation
     *    narrator => string the narrator for the translation     
     *    search_text => string the search text    
     *    number_of_results => int the number of search results
     *    result_type => string [ayat~ruku] indicates if ruku should be fetched or ayas
     *    start => int the position from where the search results should be returned
     *    order => string [sequence~random] the order of the search results
     *
     * @return array $ayat_data the required ayat data
     *    ayat_list => array the required ayas
     *    total_results => int the total number of search results
     */
    public function SearchAyatData($parameters) 
    {
        /** The translated ayat list */
        $ayat_list                          = $this->GetComponent("ayat")->SearchAyas($parameters['parameters']['search_text'], true);
        /** The total number of results */
        $total_results                      = count($ayat_list);
        /** The last ayat id */
        $last_ayat_id                       = '-1';
        /** If no results were found then empty result is returned */
        if (count($ayat_list) == 0) return $ayat_list;
        /** The start ayat */
        $start_ayat                         = ($parameters['parameters']['order'] == 'random') ? rand(1, HolyQuran::GetMaxDivisionCount("ayas")) : $parameters['parameters']['start'];
        /** If the result type is ruku */
        if ($parameters['parameters']['result_type'] == "ruku") {
            /** The Ruku id is fetched */
            $ruku_id                    = $this->GetComponent("rukus")->GetRukuId($ayat_list[$start_ayat]['sura'], $ayat_list[$start_ayat]['sura_ayat_id']);
            /** The start and end ayas are fetched */
            $ayat_information           = $this->GetComponent("rukus")->GetStartAndEndAyatOfRuku($ruku_id);
            /** The information for the first ayat in the ruku is fetched */
            $first_ayat_data            = $this->GetComponent("ayat")->GetAyasInSura($ayat_list[$start_ayat]['sura'], $ayat_information['start_ayat'], $ayat_information['start_ayat']);
            /** The start ayat */
            $start_ayat                 = $first_ayat_data[0]['id'];
            /** The end ayat number is set */
            $end_ayat                   = $start_ayat + ($ayat_information['end_ayat'] - $ayat_information['start_ayat']);
        }
        else {
            /** The end ayat number is set */
            $end_ayat                   = ($start_ayat + $parameters['parameters']['number_of_results']);
        }
        /** The sura data */
        $sura_data                      = $this->GetComponent("suras")->GetSuraData($ayat_list[$start_ayat]['sura']);
        /** Each ayat text is placed inside a list tag */
        for ($count = ($start_ayat-1); $count < $end_ayat; $count++)
        {            
            /** The arabic text is fetched */
            $ayat_data                      = $this->GetComponent("ayat")->GetAyasInSura($ayat_list[$count]['sura'], $ayat_list[$count]['sura_ayat_id'], $ayat_list[$count]['sura_ayat_id']);
            /** The arabic text is set */
            $ayat_list[$count]['arabic_text'] = $ayat_data[0]['arabic_text'];
            /** The sura data is added to the ayat list */
            $ayat_list[$count]['sura_data']  = $sura_data;
            /** The meta data is set depending on order and result type */
            if ($parameters['parameters']['result_type'] == "ayat" && $parameters['parameters']['order'] == "random") {
                /** The meta data for the ayat is set */
                $ayat_list[$count]['meta_data']  = " (" . $ayat_list[$count]['sura_data']['tname'] . " - " . $ayat_list[$count]['sura_data']['sindex']. ":" . $ayat_list[$count]['sura_ayat_id'] . ")";
            }
            else {
                /** The meta data for the ayat is set */
                $ayat_list[$count]['meta_data']  = " (" . $ayat_list[$count]['sura_data']['sindex']. ":" . $ayat_list[$count]['sura_ayat_id'] . ")";
            }
            /** The ayat list is updated */
            $updated_ayat_list[]            = $ayat_list[$count];
            /** If the end ayat is reached then the loop ends */
            if ($ayat_information['end_ayat'] == $ayat_list[$count]['sura_ayat_id']) break;
        }

        /** The required ayat list */
        $ayat_list = $updated_ayat_list;

        $ayat_data = array($ayat_list, $total_results);
        
        return $ayat_data;        
    }
}
