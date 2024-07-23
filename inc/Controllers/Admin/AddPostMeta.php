<?php
/**
 * @package GymBuilder
 */
namespace GymBuilder\Inc\Controllers\Admin;

if ( ! defined( 'ABSPATH' ) ) exit;

use \GymBuilder\Inc\Controllers\Helpers\Functions;
use \GymBuilder\Inc\Controllers\Admin\Settings\Api\SettingsApi;
use \GymBuilder\Inc\Controllers\Admin\Models\Metabox\RegisterPostMeta;

class AddPostMeta{

    public static $time_picker_format;

    public static function init(){

        self::$time_picker_format = (SettingsApi::get_option( 'class_time_format','gym_builder_class_settings')==24 ? 'time_picker_24':'time_picker');

        $Postmeta = RegisterPostMeta::getInstance();

        /**class post meta */

        $Postmeta->add_meta_box( 'gym_builder_class_schedule', __( 'Schedule', 'gym-builder' ), array( 'gym_builder_class' ), '', '', 'high', array(
            'fields' => array(
                'gym_builder_class_button_text' => array(
                    'label' => __( 'Button Text', 'gym-builder' ),
                    'type'  => 'text',
                    'desc'  => __( 'Enter button text eg. Join Now!', 'gym-builder' ),
                ),
                'gym_builder_class_button_url' => array(
                    'label' => __( 'Button URL', 'gym-builder' ),
                    'type'  => 'text',
                    'desc'  => __( 'Enter button url', 'gym-builder' ),
                ),
                'gym_builder_class_schedule' => array(
                    'type'  => 'repeater',
                    'button' => __( 'Add New Schedule', 'gym-builder' ),
                    'value'  => array(
                        'trainer' => array(
                            'label' => __( 'Trainer', 'gym-builder' ),
                            'type'  => 'select',
                            'options' => Functions::get_trainers(),
                            'default'  => 'default',
                        ),
                        'week' => array(
                            'label' => __( 'Weekday', 'gym-builder' ),
                            'type'  => 'select',
                            'options' => array(
                                'none' => __( 'Select a Weekday', 'gym-builder' ),
                                'mon'  => __( 'Monday', 'gym-builder' ),
                                'tue'  => __( 'Tuesday', 'gym-builder' ),
                                'wed'  => __( 'Wednesday', 'gym-builder' ),
                                'thu'  => __( 'Thursday', 'gym-builder' ),
                                'fri'  => __( 'Friday', 'gym-builder' ),
                                'sat'  => __( 'Saturday', 'gym-builder' ),
                                'sun'  => __( 'Sunday', 'gym-builder' ),
                            ),
                        ),				
                        'start_time' => array(
                            'label' => __( 'Start Time', 'gym-builder' ),
                            'type'  => self::$time_picker_format,
                        ),
                        'end_time' => array(
                            'label' => __( 'End Time', 'gym-builder' ),
                            'type'  => self::$time_picker_format,
                        ),
                    )
                ),
            )
        ) );

        /**trainer post meta */

        $Postmeta->add_meta_box( 'gym_builder_trainer_info', __( 'Trainer Info', 'gym-builder' ), array( 'gym_builder_trainer' ), '', '', 'high', array(
            'fields' => array(
                'gym_builder_trainer_designation' => array(
                    'label' => __( 'Trainer Designation', 'gym-builder' ),
                    'type'  => 'text',
                ),
                'gym_builder_trainer_header' => array(
                    'label' => __( 'Trainer Socials Links', 'gym-builder' ),
                    'type'  => 'header',
                    'desc'  => __( 'Enter trainer social links here', 'gym-builder' ),
                ),
                'gym_builder_trainer_socials' => array(
                    'type'  => 'group',
                    'value'  => Functions::trainer_socials()
                ),
            )
        ) );

        /**membership package post meta */

        $Postmeta->add_meta_box( 'gym_builder_member_package_options', __( 'Membership Package Options', 'gym-builder' ), array( 'gb_pricing_plan' ), '', '', 'high', array(
            'fields' => array(
                'gym_builder_package_price' => array(
                    'label' => __( 'Package Price', 'gym-builder' ),
                    'type'  => 'text',
                ),
                'gym_builder_package_features' => array(
                    'type'  => 'repeater',
                    'button' => __( 'Add New Package Feature', 'gym-builder' ),
                    'value'  => array(
                        'feature_icon' => array(
                            'label' => __( 'Feature Item Icon', 'gym-builder' ),
                            'type'  => 'select',
                            'options' => array(
                                'none' => __( 'Select a Icon Name', 'gym-builder' ),
                                'check'  => __( 'Check', 'gym-builder' ),
                                'uncheck'  => __( 'Uncheck', 'gym-builder' ),
                            ),
                        ),	
                        'feature_item' => array(
                            'label' => __( 'Package Feature Item', 'gym-builder' ),
                            'type'  => 'text',
                        ),
                    )
                ),
                'gym_builder_package_button_text' => array(
                    'label' => __( 'Button Text', 'gym-builder' ),
                    'type'  => 'text',
                    'desc'  => __( 'Enter button text eg. Buy Now!', 'gym-builder' ),
                ),
                'gym_builder_package_button_url' => array(
                    'label' => __( 'Button URL', 'gym-builder' ),
                    'type'  => 'text',
                    'desc'  => __( 'Enter button url', 'gym-builder' ),
                ),
            )
        ) );
    }
}