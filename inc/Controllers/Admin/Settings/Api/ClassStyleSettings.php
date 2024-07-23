<?php
/**
 * @package MiltonPlugin
 */
namespace GymBuilder\Inc\Controllers\Admin\Settings\Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

class ClassStyleSettings{

	public static function classStyle(  ) {
		$settings =[
			array(
				'name'    => 'class_style_page_heading',
				'label'   => __( 'Class Style', 'gym-builder' ),
				'type'    => 'heading',
			),
			array(
				'name'    => 'gym_builder_class_title_color',
				'label'   => __( 'Class Title', 'gym-builder' ),
				'desc'    => __( 'This color is set for class title color', 'gym-builder' ),
				'type'    => 'color',
				'default' => ''
			),
			array(
				'name'    => 'gym_builder_class_content_color',
				'label'   => __( 'Class Content', 'gym-builder' ),
				'desc'    => __( 'This color is set for class content color', 'gym-builder' ),
				'type'    => 'color',
				'default' => ''
			),
			array(
				'name'    => 'gym_builder_class_schedule_color',
				'label'   => __( 'Class Schedule', 'gym-builder' ),
				'desc'    => __( 'This color is set for class schedule color', 'gym-builder' ),
				'type'    => 'color',
				'default' => ''
			),
			array(
				'name'    => 'gym_builder_class_trainer_color',
				'label'   => __( 'Class Trainer', 'gym-builder' ),
				'desc'    => __( 'This color is set for class trainer color', 'gym-builder' ),
				'type'    => 'color',
				'default' => ''
			),
			array(
				'name'    => 'gym_builder_class_table_title_color',
				'label'   => __( 'Single Class Table Title', 'gym-builder' ),
				'desc'    => __( 'This color is set for class table title color', 'gym-builder' ),
				'type'    => 'color',
				'default' => ''
			),
			array(
				'name'    => 'gym_builder_class_table_border_color',
				'label'   => __( 'Single Class Table Border', 'gym-builder' ),
				'desc'    => __( 'This color is set for class table border color', 'gym-builder' ),
				'type'    => 'color',
				'default' => ''
			),
			array(
				'name'    => 'gym_builder_class_table_heading_color',
				'label'   => __( 'Single Class Table Heading', 'gym-builder' ),
				'desc'    => __( 'This color is set for class table heading color', 'gym-builder' ),
				'type'    => 'color',
				'default' => ''
			),
		];
		return apply_filters('class_style_settings',$settings);
	}


}