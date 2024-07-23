<?php
/**
 * @package MiltonPlugin
 */
namespace GymBuilder\Inc\Controllers\Admin\Settings\Api\Callbacks;

use \GymBuilder\Inc\Controllers\Admin\Settings\Api\SettingsApi;
use GymBuilder\Inc\Controllers\Helpers\Functions;

class CallbackSelect{
    public static function callback_select($args)
    {
        $value = esc_attr( SettingsApi::get_option( $args['id'], $args['section'], $args['std'] ) );

        $size  = $args['size'] ?? 'regular';
        $html  = sprintf( '<select class="%1$s" name="%2$s[%3$s]" id="%2$s[%3$s]">', $size, $args['section'], $args['id'] );

        foreach ( $args['options'] as $key => $label ) {
            $html .= sprintf( '<option value="%s" %s>%s</option>', $key, selected( $value, $key, false ), $label );
        }

        $html .= '</select>';
        $html .= SettingsApi::get_field_description( $args );

	    Functions::print_html($html,true);

    }
}