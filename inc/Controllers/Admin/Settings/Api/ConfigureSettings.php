<?php
/**
 * @package MiltonPlugin
 */

namespace GymBuilder\Inc\Controllers\Admin\Settings\Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

use GymBuilder\Inc\Controllers\Helpers\Functions;
use \GymBuilder\Inc\Controllers\Admin\Settings\Api\Callbacks\AdminCallbacks;
use \GymBuilder\Inc\Controllers\Admin\Settings\Api\SettingsApi;


class ConfigureSettings {

	private static $settings_api;

	public $callbacks;

	protected static $instance = null;

	private array $subpages = array();

	public static function getInstance(): ?ConfigureSettings {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function register() {

		self::$settings_api = new SettingsApi();

		$this->callbacks = new AdminCallbacks();


		$this->setSubPages();

		$this->set_settings_sections();


		self::$settings_api->addSubPages( $this->subpages )->register();

		$this->set_settings_fields();

	}

	public function setSubPages() {
		$menu_link_part = admin_url( 'admin.php?page=gym-builder-members' );
		$this->subpages = array(
//			array(
//				'parent_slug' => 'gym_builder',
//				'page_title'  => 'Settings',
//				'menu_title'  => 'Settings',
//				'capability'  => 'manage_options',
//				'menu_slug'   => 'gym-builder-settings',
//				'callback'    => array( $this->callbacks, 'gym_builder_settings_page' ),
//			),
			array(
				'parent_slug' => 'gym_builder',
				'page_title'  => 'Class Shortcode',
				'menu_title'  => 'Class Shortcode',
				'capability'  => 'manage_options',
				'menu_slug'   => 'edit.php?post_type=gb_class_shortcode',
				'callback'    => '',
			),
			array(
				'parent_slug' => 'gym_builder',
				'page_title'  => 'Trainer Shortcode',
				'menu_title'  => 'Trainer Shortcode',
				'capability'  => 'manage_options',
				'menu_slug'   => 'edit.php?post_type=gb_trainer_shortcode',
				'callback'    => '',
			),
			array(
				'parent_slug' => 'gym_builder',
				'page_title'  => 'Fitness Calc Shortcode',
				'menu_title'  => 'Fitness Calc Shortcode',
				'capability'  => 'manage_options',
				'menu_slug'   => 'edit.php?post_type=gb_fitness_shortcode',
				'callback'    => '',
			),
			array(
				'parent_slug' => 'gym_builder',
				'page_title'  => 'Membership Package',
				'menu_title'  => 'Membership Package',
				'capability'  => 'manage_options',
				'menu_slug'   => 'edit.php?post_type=gb_pricing_plan',
				'callback'    => '',
			),
			array(
				'parent_slug' => 'gym_builder',
				'page_title'  => 'All Members',
				'menu_title'  => 'All Members',
				'capability'  => 'manage_options',
				'menu_slug'   => 'gym-builder-members',
				'callback'    => array( $this->callbacks, 'gym_builder_members' ),
			),
			array(
				'parent_slug' => 'gym_builder',
				'page_title'  => 'Add Member',
				'menu_title'  => 'Add Member',
				'capability'  => 'manage_options',
				'menu_slug'   => $menu_link_part . '#/add-member',
				'callback' => ''
			),
			array(
				'parent_slug' => 'gym_builder',
				'page_title'  => 'Get Help',
				'menu_title'  => 'Get Help',
				'capability'  => 'manage_options',
				'menu_slug'   => 'gym-builder-get-help',
				'callback'    => array( $this->callbacks, 'gym_builder_get_help_page' ),
			),
		);
	}

	function set_settings_sections() {
		$sections = array(
			array(
				'id'    => 'gym_builder_page_settings',
				'title' => __( 'Page Settings', 'gym-builder' ),
			),
			array(
				'id'    => 'gym_builder_permalinks_settings',
				'title' => __( 'Permalinks Settings', 'gym-builder' ),
			),
			array(
				'id'    => 'gym_builder_class_settings',
				'title' => __( 'Class Settings', 'gym-builder' ),
			),
			array(
				'id'    => 'gym_builder_trainer_settings',
				'title' => __( 'Trainer Settings', 'gym-builder' ),
			),
			array(
				'id'    => 'gym_builder_style_settings',
				'title' => __( 'Style Settings', 'gym-builder' ),
			),
			array(
				'id'    => 'gym_builder_global_settings',
				'title' => __( 'Global Settings', 'gym-builder' ),
			),

		);
		self::$settings_api->set_sections( $sections );
	}

	public function set_settings_fields() {
		$settings_fields = array_merge(
			self::page_settings(),
			self::permalinks_settings(),
			self::class_settings(),
			self::trainer_settings(),
			self::style_settings(),
			self::global_settings()
		);

		self::$settings_api->set_fields( $settings_fields );
	}

	public static function page_settings() {
		$page_settings = array(
			'gym_builder_page_settings' => array(
				array(
					'name'    => 'classes',
					'label'   => __( 'Classes Page', 'gym-builder' ),
					'desc'    => __( 'you can select classes archive page from dropbox', 'gym-builder' ),
					'type'    => 'select',
					'default' => '',
					'options' => Functions::get_pages(),
				),
				array(
					'name'    => 'trainers',
					'label'   => __( 'Trainer\'s Page', 'gym-builder' ),
					'desc'    => __( 'you can select trainer archive page from dropbox', 'gym-builder' ),
					'type'    => 'select',
					'default' => '',
					'options' => Functions::get_pages(),
				),
			),
		);

		return apply_filters( 'gym_builder_page_settings', $page_settings );
	}

	public static function permalinks_settings() {
		$permelink_settings = array(
			'gym_builder_permalinks_settings' => array(
				array(
					'name'    => 'class_base',
					'label'   => __( 'Classe Base', 'gym-builder' ),
					'desc'    => __( 'you can change your class post type base name', 'gym-builder' ),
					'type'    => 'text',
					'default' => '',
				),
				array(
					'name'    => 'class_category_base',
					'label'   => __( 'Classe Category Base', 'gym-builder' ),
					'desc'    => __( 'you can change your class post type category base name', 'gym-builder' ),
					'type'    => 'text',
					'default' => '',
				),
				array(
					'name'    => 'trainer_base',
					'label'   => __( 'Trainer Base', 'gym-builder' ),
					'desc'    => __( 'you can change your trainer post type  base name', 'gym-builder' ),
					'type'    => 'text',
					'default' => '',
				),
				array(
					'name'    => 'trainer_category_base',
					'label'   => __( 'Trainer Category Base', 'gym-builder' ),
					'desc'    => __( 'you can change your trainer post type category base name', 'gym-builder' ),
					'type'    => 'text',
					'default' => '',
				),
			),
		);

		return apply_filters( 'gym_builder_permalinks_settings', $permelink_settings );
	}

	public static function class_settings() {

		$class_settings['gym_builder_class_settings'] = array_merge(
			ClassSettings::classCommonSettings(),
			ClassSettings::classArchiveSettings(),
			ClassSettings::classImageSettings(),
			ClassSettings::class_slider_settings(),
			ClassSettings::classFilteringSettings(),
			ClassSettings::classSingleSettings()
		);

		return apply_filters( 'gym_builder_class_settings', $class_settings );
	}

	public static function cm_settings_callback() {
		echo '<div class="wrap">';

		self::$settings_api->show_navigation();
		self::$settings_api->show_forms();

		echo '</div>';
	}



	public static function global_style_settings() {
		$settings = [
			array(
				'name'    => 'global_style_page_heading',
				'label'   => __( 'Global Style', 'gym-builder' ),
				'type'    => 'heading',
			),
			array(
				'name'    => 'gym_builder_primary_color',
				'label'   => __( 'Primary Color', 'gym-builder' ),
				'desc'    => __( 'This color is set for all primary color in this plugin', 'gym-builder' ),
				'type'    => 'color',
				'default' => '#005dd0'
			),
			array(
				'name'    => 'gym_builder_secondary_color',
				'label'   => __( 'Secondary Color', 'gym-builder' ),
				'desc'    => __( 'This color is set for all secondary color in this plugin', 'gym-builder' ),
				'type'    => 'color',
				'default' => '#0a4b78'
			),

		];

		return apply_filters( 'global_style_settings', $settings );
	}

	public static function trainer_settings() {
		$trainer_settings['gym_builder_trainer_settings'] = array_merge(
			TrainerSettings::trainerArchiveSettings(),
			TrainerSettings::trainerImageSettings(),
			TrainerSettings::trainerFilteringSettings(),
			TrainerSettings::trainerSingleSettings()
		);

		return apply_filters( 'gym_builder_trainer_settings', $trainer_settings );
	}

	public static function style_settings() {
		$style_settings['gym_builder_style_settings'] = array_merge(
			self::global_style_settings(),
			ClassStyleSettings::classStyle(),
			TrainerStyleSettings::styleSettings()
		);

		return apply_filters( 'gym_builder_style_settings', $style_settings );
	}
	public static function global_settings() {
		$global_settings = array(
			'gym_builder_global_settings' => array(
				array(
					'name'    => 'member_id_generate_title',
					'label'   => __( 'Member ID Card Title/Shop Name', 'gym-builder' ),
					'desc'    => __( 'This text will be used for member generated id card title or shop name', 'gym-builder' ),
					'type'    => 'text',
					'default' => '',
				),
				array(
					'name'    => 'member_sender_mail',
					'label'   => __( 'Sender Email Address', 'gym-builder' ),
					'desc'    => __( 'If you use WP Mail SMTP,then this sender mail will not be working.Your smtp mail setup will set then', 'gym-builder' ),
					'type'    => 'text',
				)
			),
		);
		return apply_filters( 'gym_builder_global_settings', $global_settings );
	}

}

