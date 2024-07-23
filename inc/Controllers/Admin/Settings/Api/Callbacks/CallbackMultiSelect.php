<?php
/**
 * @package MiltonPlugin
 */
namespace GymBuilder\Inc\Controllers\Admin\Settings\Api\Callbacks;

use \GymBuilder\Inc\Controllers\Admin\Settings\Api\SettingsApi;
use GymBuilder\Inc\Controllers\Helpers\Functions;

class CallbackMultiSelect{
    public static function callback_multiselect($args)
    {
        $section_name = $args['section'];

        $id = $args['id'];

        $option_name = $section_name."[".$id."]"."["."]";

        $section_values = get_option($section_name);

        $single_selected_values = $section_values[$id] ?? null;


        $html = sprintf('<select name="%s" multiple="multiple" class="gym-builder-select2">',$option_name);

        foreach ($args['options'] as $key=>$value){
            $selected = '';

            if (isset($single_selected_values) && is_array($section_values) && !empty($section_values)){
                if(in_array($key,$single_selected_values)){
                    $selected = 'selected';
                }
            }
            $html    .= sprintf("<option value='%s' %s >%s</option>",$key,$selected,$value);
        }
        $html .="</select>";

	    $html .= SettingsApi::get_field_description( $args );

	    Functions::print_html($html,true);
    }
}
