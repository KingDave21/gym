<?php
/**
 * @package MiltonPlugin
 */
namespace GymBuilder\Inc\Controllers\Admin\Settings\Api\Callbacks;

use \GymBuilder\Inc\Controllers\Admin\Settings\Api\SettingsApi;
use GymBuilder\Inc\Controllers\Helpers\Functions;

class CallbackMultiCheck{
    public static function callback_multicheck($args)
    {
        $value = SettingsApi::get_option( $args['id'], $args['section'], $args['std'] );
        $html  = '<fieldset>';
        $html .= sprintf( '<input type="hidden" name="%1$s[%2$s]" value="" />', $args['section'], $args['id'] );
        foreach ( $args['options'] as $key => $label ) {
            $checked = $value[$key] ?? '0';
            $html    .= sprintf( '<label for="gym-builder-%1$s[%2$s][%3$s]">', $args['section'], $args['id'], $key );
            $html    .= sprintf( '<input type="checkbox" class="checkbox" id="gym-builder-%1$s[%2$s][%3$s]" name="%1$s[%2$s][%3$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $key, checked( $checked, $key, false ) );
            $html    .= sprintf( '%1$s</label><br>',  $label );
        }

        $html .= SettingsApi::get_field_description( $args );
        $html .= '</fieldset>';

	    Functions::print_html($html,true);
    }
}