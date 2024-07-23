<?php
/**
 * @package MiltonPlugin
 */

namespace GymBuilder\Inc\Controllers\Admin\Settings\Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

use GymBuilder\Inc\Controllers\Helpers\Helper;
use GymBuilder\Inc\Controllers\Models\GymBuilderClass;

class ClassSettings {
	public static function classCommonSettings() {
		$settings = [
			array(
				'name'    => 'class_time_format',
				'label'   => __( 'Class Time Format', 'gym-builder' ),
				'desc'    => __( 'you can select class time format from here', 'gym-builder' ),
				'type'    => 'radio',
				'default' => '12',
				'options' => array(
					'24' => '24-hour',
					'12' => '12-hour',
				),
			),
		];

		return apply_filters( 'class_common_settings', $settings );
	}

	public static function classArchiveSettings() {
		$settings = [
			array(
				'name'    => 'class_archive_style',
				'label'   => __( 'Class Archive Style', 'gym-builder' ),
				'type'    => 'image-radio',
				'default' => 'layout-1',
				'options' =>self::class_page_layout()
			),
			array(
				'name'    => 'class_posts_per_page',
				'label'   => __( 'Posts Per Page', 'gym-builder' ),
				'desc'    => __( 'you can select class post item per page', 'gym-builder' ),
				'type'    => 'number',
				'default' => '9',
			),
			array(
				'name'    => 'class_grid_columns',
				'label'   => __( 'Grid Columns', 'gym-builder' ),
				'desc'    => __( 'you can select class archive page grid columns', 'gym-builder' ),
				'type'    => 'select',
				'default' => '3',
				'options' => array(
					'1' => '1 Columns',
					'2' => '2 Columns',
					'3' => '3 Columns',
					'4' => '4 Columns',
					'5' => '5 Columns',
					'6' => '6 Columns',
				),
			),
			array(
				'name'    => 'class_page_layout',
				'label'   => __( 'Page Layout', 'gym-builder' ),
				'desc'    => __( 'you can select class archive page page layout', 'gym-builder' ),
				'type'    => 'select',
				'default' => 'full-width',
				'options' => array(
					'full-width'    => 'Full Width',
					'left-sidebar'  => 'Left Sidebar',
					'right-sidebar' => 'Right Sidebar',
				),
			),
		];

		return apply_filters( 'class_archive_page_settings', $settings );
	}

	public static function classSingleSettings() {
		$settings = [
			array(
				'name'  => 'class_single_page_heading',
				'label' => __( 'Class Single Settings', 'gym-builder' ),
				'type'  => 'heading',
			),
			array(
				'name'    => 'class_single_page_layout',
				'label'   => __( 'Single Page Layout', 'gym-builder' ),
				'desc'    => __( 'you can select class single page page layout', 'gym-builder' ),
				'type'    => 'select',
				'default' => 'full-width',
				'options' => array(
					'full-width'    => 'Full Width',
					'left-sidebar'  => 'Left Sidebar',
					'right-sidebar' => 'Right Sidebar',
				),
			),
		];

		return apply_filters( 'class_single_page_settings', $settings );
	}

	public static function classFilteringSettings() {
		$settings = [
			array(
				'name'  => 'class_filtering_page_heading',
				'label' => __( 'Class Filtering', 'gym-builder' ),
				'type'  => 'heading',
			),
			array(
				'name'    => 'include_class',
				'label'   => __( 'Include Class', 'gym-builder' ),
				'desc'    => __( 'Select the classes that you want to display on class archive page', 'gym-builder' ),
				'type'    => 'multiselect',
				'default' => '0',
				'options' => self::classesOptionsValue()
			),
			array(
				'name'    => 'exclude_class',
				'label'   => __( 'Exclude Class', 'gym-builder' ),
				'desc'    => __( 'Select the classes that you don\'t want to display on class archive page', 'gym-builder' ),
				'type'    => 'multiselect',
				'options' => self::classesOptionsValue()
			),
			array(
				'name'    => 'class_categories',
				'label'   => __( 'Class Categories', 'gym-builder' ),
				'desc'    => __( 'Select the categories that  want to filter classes on class archive page', 'gym-builder' ),
				'type'    => 'multiselect',
				'options' => GymBuilderClass::get_categories_array()
			),
			array(
				'name'    => 'class_orderBy',
				'label'   => __( 'Class Order By', 'gym-builder' ),
				'type'    => 'select',
				'default' => 'none',
				'options' => Helper::orderbyQueryOptions()
			),
			array(
				'name'    => 'class_order',
				'label'   => __( 'Class Order', 'gym-builder' ),
				'type'    => 'radio',
				'default' => 'ASC',
				'options' => array(
					'ASC'  => 'Ascending',
					'DESC' => 'Descending',
				),
			),
		];

		return apply_filters( 'class_filtering_settings', $settings );
	}

	public static function classesOptionsValue() {
		$options = [];

		$classes = GymBuilderClass::get_classes();

		foreach ( $classes as $class ) {
			$options[ $class->ID ] = $class->post_title;
		}

		return $options;
	}

	public static function class_page_layout() {
		$layout = [
			'layout-1' => [
				'title'      => 'Layout 1',
				'img_source' => 'layout-1'
			],
			'layout-2' => [
				'title'      => 'Slider Layout',
				'img_source' => 'layout-2'
			]
		];

		return apply_filters( 'gym_builder_class_page_layout', $layout );
	}

	public static function classImageSettings() {
		$settings = [
			array(
				'name'  => 'class_image_page_heading',
				'label' => __( 'Image Settings', 'gym-builder' ),
				'type'  => 'heading',
			),
			array(
				'name'    => 'class_thumbnail_width',
				'label'   => __( 'Class Thumbnail Width', 'gym-builder' ),
				'desc'    => __( 'you can set class post thumbnail image width', 'gym-builder' ),
				'type'    => 'number',
				'default' => '570',
			),
			array(
				'name'    => 'class_thumbnail_height',
				'label'   => __( 'Class Thumbnail Height', 'gym-builder' ),
				'desc'    => __( 'you can set class post thumbnail image height', 'gym-builder' ),
				'type'    => 'number',
				'default' => '400',
			),
			array(
				'name'  => 'class_thumbnail_hard_crop',
				'label' => __( 'Hard Crop', 'gym-builder' ),
				'default' =>'on',
				'desc'    => __( 'you can set image hard crop', 'gym-builder' ),
				'type'  => 'checkbox'
			),
		];

		return apply_filters( 'class_image_settings', $settings );
	}
	public static function class_slider_settings() {
		$settings = [
			array(
				'name'  => 'class_slider_settings',
				'label' => __( 'Slider Settings', 'gym-builder' ),
				'type'  => 'heading',
			),
			array(
				'name'    => 'slider_autoplay',
				'label'   => __( 'Slider Autoplay', 'gym-builder' ),
				'desc'    => __( 'you can set slider autoplay. Default:True', 'gym-builder' ),
				'type'    => 'checkbox',
				'default' => 'on',
			),
			array(
				'name'    => 'slider_loop',
				'label'   => __( 'Slider Loop', 'gym-builder' ),
				'desc'    => __( 'you can set slider loop play. Default:True', 'gym-builder' ),
				'type'    => 'checkbox',
				'default' => 'on',
			),
			array(
				'name'    => 'centered_slider',
				'label'   => __( 'Centered Slider', 'gym-builder' ),
				'desc'    => __( 'you can set slider position. Default:False', 'gym-builder' ),
				'type'    => 'checkbox',
				'default' => 'off',
			),
			array(
				'name'    => 'slides_per_view',
				'label'   => __( 'Slides Per View', 'gym-builder' ),
				'desc'    => __( 'you can set how many sliders show on desktop. Default:3', 'gym-builder' ),
				'type'    => 'number',
				'default' => '3',
			),
		];

		return apply_filters( 'class_slider_settings', $settings );
	}
}