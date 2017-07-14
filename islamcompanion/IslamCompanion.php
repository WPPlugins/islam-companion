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
final class IslamCompanion extends \Framework\Frameworks\WordPress\Application
{
    /**
     * The settings page is created
     *
     * This page is displayed for an option under settings menu
     * It displays the settings page for the plugin
     */
    public function DisplaySettingsPage() 
    {
        $this->GetComponent("settings")->DisplaySettingsPage();
    }
    /**
     * The admin page is initialized
     *
     * It initializes the admin page
     * It registers all the fields used by the settings page
     */
    public function InitAdminPage() 
    {
        $this->GetComponent("settings")->InitializeAdminPage();
    }
    /**
     * The dashboard setup function is called
     * The Holy Quran dashboard widget is registered
     */
    public function SetupHolyQuranAndHadithWidgets() 
    {        
        /** The current url is fetched */
        $current_url      = $this->GetConfig("path", "current_url");
        /** If the user is not logged in to Wordpress admin, then the Dashboard widgets are not setup */
        if (strpos($current_url, "/wp-admin/") === false) return;
        /** If the ayas custom post type exists, then the Holy Quran dashboard widget is added */
        if (post_type_exists("ayas")) {
            /** The Holy Quran dashboard widget is added */
            $this->AddDashboardWidget('holy-quran-dashboard-widget', __('Holy Quran', $this->GetConfig("wordpress", "plugin_text_domain")) , array(
            $this->GetComponent("holyqurandashboardwidget") ,
            'DisplayDashboardWidget'
            ));
        }
        /** If the hadith custom post type exists, then the Hadith dashboard widget is added */
        if (post_type_exists("hadith")) {
            /** The Hadith dashboard widget is added */
            $this->AddDashboardWidget('hadith-dashboard-widget', __('Hadith', $this->GetConfig("wordpress", "plugin_text_domain")) , array(
                $this->GetComponent("hadithdashboardwidget") ,
                'DisplayDashboardWidget'
            ));
        }
    }
    /**
     * Used to register custom post types and custom taxonomies with WordPress
     *
     * It adds following custom post types: aya, sura and author
     */
    public function AddCustomPostTypes() 
    {
        /** The current url is fetched */
        $current_url      = $this->GetConfig("path", "current_url");
        /** If the user is not logged in to Wordpress admin, then the Dashboard widgets are not setup */
        if (strpos($current_url, "/wp-admin/") === false) return;

        /** The current plugin options are fetched */
        $options = $this->GetComponent("application")->GetPluginOptions("ic_options_1");
        /** If no data has been imported, then the function returns */
        if ($options['imported_data'] == 'none') return;

        /** If the plugin is in development mode, then the custom post types are shown */
        $display_sidebar_links = ($this->GetConfig("general", "development_mode")) ? true : false;
        /** The default arguments for the new custom post types */
        $default_args = array(
            'public' => $display_sidebar_links,
            'publicly_queryable' => $display_sidebar_links,
            'show_ui' => $display_sidebar_links,
            'show_in_menu' => $display_sidebar_links,
            'query_var' => $display_sidebar_links,
            'menu_position' => 5,
            'supports' => array(
                'title',
                'custom-fields'
            )
        );
        /** If the holy quran data was imported */
        if ($options['imported_data'] == 'holy quran' || $options['imported_data'] == 'holy quran and hadith') {        
            /** The ayat custom post type is added */
            $this->GetComponent("application")->AddNewCustomPostType("Ayas", "Aya", array() , $default_args);
            /** The sura custom post type is added */
            $this->GetComponent("application")->AddNewCustomPostType("Suras", "Sura", array() , $default_args);
            /** The author custom post type is added */
            $this->GetComponent("application")->AddNewCustomPostType("Authors", "Author", array() , $default_args);
        }
        /** If the hadith data was imported */
        if ($options['imported_data'] == 'hadith' || $options['imported_data'] == 'holy quran and hadith') {        
            /** The hadith custom post type is added */
            $this->GetComponent("application")->AddNewCustomPostType("Hadith", "Hadith", array() , $default_args);
            /** The books custom post type is added */
            $this->GetComponent("application")->AddNewCustomPostType("Books", "Books", array() , $default_args);
        }
    }
    /**
     * Used to display admin notices
     *
     * This function is used to display admin notice messages
     * It is called each time an admin page is loaded
     * It displays a notifcation message if the plugin has not been configured from the settings page
     *
     * @param string $information_message the information message to display to the user     
     */
    public function DisplayAdminNotices($information_message) 
    {
        /** The current plugin options are fetched */
        $options = $this->GetComponent("application")->GetPluginOptions("ic_options_1");
        /** If the options are not set then an information message is displayed to the user */
        if (!is_array($options)) 
        {
            /** The information message that is displayed to the user */
            $information_message = "You have not configured the settings for the Islam Companion plugin. Please <a href='/wp-admin/options-general.php?page=islam-companion-settings-admin'>Click here</a> to configure the plugin";
        }
        /** If the current plugin version is not the latest version then an information message is displayed to the user */
        else if (!$this->IsLatestPluginVersionInstalled()) 
        {
            /** The information message that is displayed to the user */
            $information_message = "You are running an old version of the Islam Companion plugin. Please update the plugin to the latest version. <a href='/wp-admin/plugins.php'>Click here</a> to update the plugin to the latest version";
        }
        /** If the Ayas and Hadith custom post type does not exist, then an information message is displayed to the user */
        else if (!post_type_exists("ayas") && !post_type_exists("hadith")) 
        {
            /** The current url */
            $current_url         = $this->GetConfig("path", "current_request_url");
            /** If the user is on the settings page */
            if ($current_url == '/wp-admin/options-general.php?page=islam-companion-settings-admin') {
                /** The information message that is displayed to the user */
                $information_message = "You have not imported the data for the Islam Companion plugin. To import the data, please select from the 'Imported Data' list and click on 'Import Data'";
            }
            else {
                /** The information message that is displayed to the user */
                $information_message = "You have not imported the data for the Islam Companion plugin. Please <a href='/wp-admin/options-general.php?page=islam-companion-settings-admin'>Click here</a> to import the data";
            }
        }
        /** If no information message needs to be displayed, then the function returns */
        else return;    
        /** The parent class function is called, which displays the information message */
        parent::DisplayAdminNotices($information_message);
    }
    /**
     * Custom error handling function
     *
     * Used to handle an error
     *
     * @param string $log_message the error log message
     * @param array $error_parameters the error parameters
     *    error_level => int the error level
     *    error_type => int [Error~Exception] the error type. it is either Error or Exception
     *    error_message => string the error message
     *    error_file => string the error file name
     *    error_line => int the error line number
     *    error_context => array the error context
     */
    public function CustomErrorHandler($log_message, $error_parameters) 
    {
        /** The error message is logged using a web hook. It saves the error message to remote database */
        $this->GetComponent("api")->LogErrorToWebHook("IslamCompanionApi", $error_parameters, false);
        /** The error message is displayed and the script ends */
        die($log_message);
    }
}

