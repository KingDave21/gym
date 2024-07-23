<?php
/**
 * @package MiltonPlugin
 */
namespace GymBuilder\Inc\Controllers\Admin\Settings\Api\Callbacks;

use \GymBuilder\Inc\Controllers\Admin\Settings\Api\SettingsApi;

class CallbackHeading{
    public static function callback_heading($args): void
    {

        $label        = $args['name'] ?? '';

        $html        = sprintf( '<h2>%s</h2>', $label );

        echo wp_kses_post($html);
    }
}