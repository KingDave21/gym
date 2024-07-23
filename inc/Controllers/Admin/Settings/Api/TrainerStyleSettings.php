<?php
/**
 * @package MiltonPlugin
 */
namespace GymBuilder\Inc\Controllers\Admin\Settings\Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

class TrainerStyleSettings{
	public static function styleSettings()
	{
		$settings =[
			array(
				'name'    => 'trainer_style_page_heading',
				'label'   => __( 'Trainer Style', 'gym-builder' ),
				'type'    => 'heading',
			),
			array(
				'name'    => 'gym_builder_trainer_title_color',
				'label'   => __( 'Trainer Title', 'gym-builder' ),
				'desc'    => __( 'This color is set for trainer title color', 'gym-builder' ),
				'type'    => 'color',
				'default' => ''
			),
			array(
				'name'    => 'gym_builder_trainer_designation_color',
				'label'   => __( 'Trainer Designation', 'gym-builder' ),
				'desc'    => __( 'This color is set for trainer designation color', 'gym-builder' ),
				'type'    => 'color',
				'default' => ''
			),
			array(
				'name'    => 'gym_builder_trainer_content_color',
				'label'   => __( 'Trainer Content', 'gym-builder' ),
				'desc'    => __( 'This color is set for trainer content color', 'gym-builder' ),
				'type'    => 'color',
				'default' => ''
			),
			array(
				'name'    => 'gym_builder_trainer_bg_color',
				'label'   => __( 'Trainer Background', 'gym-builder' ),
				'desc'    => __( 'This color is set for trainer background color', 'gym-builder' ),
				'type'    => 'color',
				'default' => ''
			),

		];
		return apply_filters('trainer_style_settings',$settings);
	}

}