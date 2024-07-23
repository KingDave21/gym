<?php
/**
 * @package MiltonPlugin
 */
namespace GymBuilder\Inc\Controllers\Admin\Settings\Api\Callbacks;

use \GymBuilder\Inc\Controllers\Admin\Settings\Api\SettingsApi;
use GymBuilder\Inc\Controllers\Helpers\Functions;

class CallbackCheckbox{
	public static function callback_checkbox( $args ) {

		$value = SettingsApi::get_option( $args['id'], $args['section'], $args['std'] );

		$html  = '<fieldset>';
		$html  .= sprintf( '<label for="gym-builder-%1$s[%2$s]">', $args['section'], $args['id'] );
		$html  .= sprintf( '<input type="hidden" name="%1$s[%2$s]" value="off" />', $args['section'], $args['id'] );
		$html  .= sprintf( '<input type="checkbox" class="checkbox" id="gymbuilder-%1$s[%2$s]" name="%1$s[%2$s]" value="on" %3$s />', $args['section'], $args['id'], checked( $value, 'on', false ) );
		$html  .= sprintf( '%1$s</label>', $args['desc'] );
		$html  .= '</fieldset>';

		Functions::print_html($html,true);
	}
}