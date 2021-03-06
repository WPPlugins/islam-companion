<?php
namespace IslamCompanion;
use Framework\Frameworks\WordPress\Widgets as Widgets;
use \IslamCompanionApi\DataObjects\Hadith as Hadith;
/** 
 * This class implements the functionality of the Hadith sidebar widget
 *
 * It contains functions that are used to display the Hadith Sidebar Widget
 *
 * @category   Application
 * @package    IslamCompanion
 * @author     Nadir Latif <nadir@islamcompanion.org>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 * @version    3.0.6
 */
final class HadithSidebarWidget extends Widgets
{
    /**
     * Used to return the widget information
     *
     * @return array $widget_information the widget meta information
     *    application_module => string the name of the application module
     *    widget_id => int the widget id
     *    widget_name => string the widget name
     *    widget_description => string the widget description
     */
    protected function GetWidgetInformation() 
    {
        /** The widget information */
        $widget_information = array(
            "application_configuration_class" => "IslamCompanion\Configuration",
            "widget_id" => 'hadith_sidebar_widget',
            "widget_name" => __('Hadith Widget', 'islam-companion') ,
            "widget_description" => array(
                'description' => __('Hadith Widget', 'islam-companion')
            )
        );
        return $widget_information;
    }
    /**
     * Returns the main content of the widget frontend
     *
     * It returns html for the main widget content
     *
     * @param array $instance saved values from database
     *
     * @return string $main_widget_content main content of the widget
     */
    protected function GetMainWidgetContent($instance) 
    {
        /** The main widget contents */
        $main_widget_content = '[get-hadith container="' . $instance['container'] . '"
                                            css_classes="' . $instance['css_classes'] . '" 
		                            hadith_numbers="' . $instance['hadith_numbers'] . '"]';
        /** The multiple white spaces are removed */
        $main_widget_content = preg_replace('/(\s{2,})/i', " ", $main_widget_content);
        /** The shortcode html. It is generated by rendering the shortcode */
        $main_widget_content = do_shortcode($main_widget_content);
        
        return $main_widget_content;
    }
    /**
     * Back-end widget form
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database
     *
     * @return array $field_list the list of form fields is returned
     */
    protected function RenderFormFields($instance) 
    {
        /** The widget form html */
        $widget_form_html = "";
        /** The current plugin options are fetched */
        $options = $this->options;
        /** The field names to fetch */
        $field_list = array(
            "title" => array(
                "type" => "text"
            ) ,
            "hadith_numbers" => array(
                "type" => "text",
                "value" => ""
            ) ,
            "container" => array(
                "css_class" => "widefat",
                "type" => "dropdown",
                "value" => "list"
            ) ,
            "css_classes" => array(
                "type" => "text"
            )
        );
        /** The extra label text is set */
        $field_list["hadith_numbers"]["extra_label_text"] = '(e.g 5058,5059)';
        /** The extra label text is set */
        $field_list["css_classes"]["extra_label_text"] = '(e.g class1 class2)';
        /** The dropdown options are formatted */
        $formatted_dropdown_options = $this->GetDropdownOptions($instance);
        /** The container options */
        $field_list["container"]['options'] = $formatted_dropdown_options['container'];
        
        return $field_list;
    }
    /**
     * Returns the formatted options for the dropdowns
     *
     * It returns dropdown options for the meta data
     * The dropdown options are formatted so they can be displayed in a select box
     *
     * @param array $instance Previously saved values from database
     *
     * @return array $formatted_dropdown_options the formatted dropdown options for the dropdown fields
     *    hadith_source => array the list of hadith sources
     *    hadith_language => array the list of hadith languages
     *    hadith_book => array the list of hadith books
     *    hadith_title => array the list of hadith titles
     */
    protected function GetDropdownOptions($instance) 
    {
        /** The container dropdown options */
        $meta_data['container'] = array(
            "list",
            "paragraph"
        );

        /** The container dropdown options are formatted */
        for ($count = 0; $count < count($meta_data['container']); $count++) 
        {
            $formatted_dropdown_options['container'][] = array(
                "text" => $meta_data['container'][$count],
                "value" => $meta_data['container'][$count]
            );
        }       
        
        return $formatted_dropdown_options;
    }
}

