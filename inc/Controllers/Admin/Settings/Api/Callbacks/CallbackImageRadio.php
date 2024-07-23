<?php
/**
 * @package MiltonPlugin
 */
namespace GymBuilder\Inc\Controllers\Admin\Settings\Api\Callbacks;

use \GymBuilder\Inc\Controllers\Admin\Settings\Api\SettingsApi;
use GymBuilder\Inc\Controllers\Helpers\Functions;
use GymBuilder\Inc\Traits\FileLocations;

class CallbackImageRadio{
	use FileLocations;
    public static function callback_image_radio($args){

        $value = esc_attr( SettingsApi::get_option( $args['id'], $args['section'], $args['std'] ) );
        $html  = '<fieldset>';
	    $html .= '<div class="image-radio-wrapper">';
	    foreach ($args['options'] as $layouts=>$layout){
		    $img_path=self::get_file_locations('plugin_url').'assets/admin/layouts/class-'.$layout['img_source'].'.png';
		    $html .= sprintf( '<label for="%1$s[%2$s][%3$s]">',  $args['section'], $args['id'], $layouts );
		    $html .= sprintf( '<input type="radio" class="radio" id="%1$s[%2$s][%3$s]" name="%1$s[%2$s]" value="%3$s" %4$s/>', $args['section'], $args['id'], $layouts ,checked($value,$layouts,false) );
			$html .= sprintf('<img src="%s" title="%2$s" alt="%2$s"/>',esc_url($img_path),__($layout['title'],'gym-builder'));
		    $html .=  '</label>';
	    }
	    $html .= '</div>';
        $html       .= SettingsApi::get_field_description( $args );
        $html .= '</fieldset>';

	    Functions::print_html($html,true);


    }
}