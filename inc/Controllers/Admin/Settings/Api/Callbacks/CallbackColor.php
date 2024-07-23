<?php
/**
 * @package MiltonPlugin
 */
namespace GymBuilder\Inc\Controllers\Admin\Settings\Api\Callbacks;

use \GymBuilder\Inc\Controllers\Admin\Settings\Api\SettingsApi;
use GymBuilder\Inc\Controllers\Helpers\Functions;

class CallbackColor{
    public static function callback_color($args){


	    $value = esc_attr( SettingsApi::get_option( $args['id'], $args['section'], $args['std'] ) );
	    $size  = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';

	    $html  = sprintf( '<input type="text" class="%1$s-text wp-color-picker-field" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s" data-default-color="%5$s" />', $size, $args['section'], $args['id'], $value, $args['std'] );
	    $html  .= SettingsApi::get_field_description( $args );

	    Functions::print_html($html,true);
    }
}