<?php
/**
 * @package MiltonPlugin
 */
namespace GymBuilder\Inc\Controllers\Admin\Settings\Api;
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}
use GymBuilder\Inc\Controllers\Helpers\Helper;
use GymBuilder\Inc\Controllers\Models\GymBuilderTrainer;

class TrainerSettings{
    public static function trainerArchiveSettings()
    {
        $settings = [
            array(
                'name'    => 'trainer_posts_per_page',
                'label'   => __( 'Posts Per Page', 'gym-builder' ),
                'desc'    => __( 'you can select trainer post item per page', 'gym-builder' ),
                'type'    => 'number',
                'default' => '9',
            ),
            array(
                'name'    => 'trainer_grid_columns',
                'label'   => __( 'Grid Columns', 'gym-builder' ),
                'desc'    => __( 'you can select trainer archive page grid columns', 'gym-builder' ),
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
                'name'    => 'trainer_page_layout',
                'label'   => __( 'Page Layout', 'gym-builder' ),
                'desc'    => __( 'you can select trainer archive page page layout', 'gym-builder' ),
                'type'    => 'select',
                'default' => 'full-width',
                'options' => array(
                    'full-width'    => 'Full Width',
                    'left-sidebar'  => 'Left Sidebar',
                    'right-sidebar' => 'Right Sidebar',
                ),
            ),
        ];
        return apply_filters('trainer_archive_page_settings',$settings);
    }

    public static function trainerSingleSettings()
    {
        $settings = [
            array(
                'name'    => 'trainer_single_page_heading',
                'label'   => __( 'Trainer Single Settings', 'gym-builder' ),
                'type'    => 'heading',
            ),
            array(
                'name'    => 'trainer_single_page_layout',
                'label'   => __( 'Single Page Layout', 'gym-builder' ),
                'desc'    => __( 'you can select trainer single page page layout', 'gym-builder' ),
                'type'    => 'select',
                'default' => 'full-width',
                'options' => array(
                    'full-width'    => 'Full Width',
                    'left-sidebar'  => 'Left Sidebar',
                    'right-sidebar' => 'Right Sidebar',
                ),
            ),
        ];
        return apply_filters('trainer_single_page_settings',$settings);
    }
	public static function trainerFilteringSettings()
	{
		$settings = [
			array(
				'name'    => 'trainer_filtering_page_heading',
				'label'   => __( 'Trainer Filtering', 'gym-builder' ),
				'type'    => 'heading',
			),
			array(
				'name'    => 'include_trainer',
				'label'   => __( 'Include Trainer', 'gym-builder' ),
				'desc'    => __( 'Select the trainer that you want to display on trainer archive page', 'gym-builder' ),
				'type'    => 'multiselect',
				'default' =>'0',
				'options' => self::trainersOptionsValue()
			),
			array(
				'name'    => 'exclude_trainer',
				'label'   => __( 'Exclude Trainer', 'gym-builder' ),
				'desc'    => __( 'Select the traineres that you don\'t want to display on trainer archive page', 'gym-builder' ),
				'type'    => 'multiselect',
				'options' => self::trainersOptionsValue()
			),
			array(
				'name'    => 'trainer_categories',
				'label'   => __( 'Trainer Categories', 'gym-builder' ),
				'desc'    => __( 'Select the categories that  want to filter trainer on trainer archive page', 'gym-builder' ),
				'type'    => 'multiselect',
				'options' => GymBuilderTrainer::get_categories_array()
			),
			array(
				'name'    => 'trainer_orderBy',
				'label'   => __( 'Trainer Order By', 'gym-builder' ),
				'type'    => 'select',
				'default' => 'none',
				'options' => Helper::orderbyQueryOptions()
			),
			array(
				'name'    => 'trainer_order',
				'label'   => __( 'Trainer Order', 'gym-builder' ),
				'type'    => 'radio',
				'default' => 'ASC',
				'options' => array(
					'ASC' => 'Ascending',
					'DESC' => 'Descending',
				),
			),
		];
		return apply_filters('trainer_filtering_settings',$settings);
	}
	public static function trainersOptionsValue()
	{
		$options=[];

		$trainers=GymBuilderTrainer::get_trainers();

		foreach ($trainers as $trainer){
			$options[$trainer->ID] = $trainer->post_title;
		}

		return $options;
	}
	public static function trainerImageSettings(  ) {
		$settings = [
			array(
				'name'    => 'trainer_image_page_heading',
				'label'   => __( 'Image Settings', 'gym-builder' ),
				'type'    => 'heading',
			),
			array(
				'name'    => 'trainer_thumbnail_width',
				'label'   => __( 'Trainer Thumbnail Width', 'gym-builder' ),
				'desc'    => __( 'you can set trainer post thumbnail image width', 'gym-builder' ),
				'type'    => 'number',
				'default' => '570',
			),
			array(
				'name'    => 'trainer_thumbnail_height',
				'label'   => __( 'Trainer Thumbnail Height', 'gym-builder' ),
				'desc'    => __( 'you can set trainer post thumbnail image height', 'gym-builder' ),
				'type'    => 'number',
				'default' => '400',
			),
			array(
				'name'  => 'trainer_thumbnail_hard_crop',
				'label' => __( 'Hard Crop', 'gym-builder' ),
				'default' =>'on',
				'desc'    => __( 'you can set image hard crop or soft crop', 'gym-builder' ),
				'type'  => 'checkbox'
			),
		];
		return apply_filters('trainer_image_settings',$settings);
	}
}