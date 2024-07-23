<?php
/**
 * @package MiltonPlugin
 */
namespace GymBuilder\Inc\Controllers\Admin\Settings\Api\Callbacks;

use \GymBuilder\Inc\Controllers\Admin\Settings\Api\SettingsApi;
use GymBuilder\Inc\Controllers\Helpers\Functions;

class CallbackText{
    public static function callback_text($args){

        $value       = esc_attr( SettingsApi::get_option( $args['id'], $args['section'], $args['std'] ) );
        $size        = isset( $args['size'] ) ? $args['size'] : 'regular';
        $type        = $args['type'] ?? 'text';
        $placeholder = empty( $args['placeholder'] ) ? '' : ' placeholder="' . $args['placeholder'] . '"';

        $html        = sprintf( '<input type="%1$s" class="%2$s-text" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s"%6$s/>', $type, $size, $args['section'], $args['id'], $value, $placeholder );
        $html       .= SettingsApi::get_field_description( $args );

		Functions::print_html($html,true);
    }
}