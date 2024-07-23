<?php
/**
 * @package GymBuilder
 */

namespace GymBuilder\Inc\Base;

use \GymBuilder\Inc\Base\BaseController;
use GymBuilder\Inc\Controllers\Admin\Settings\Api\SettingsApi;
use GymBuilder\Inc\Controllers\Helpers\Helper;
use \GymBuilder\Inc\Traits\Constants;



class Enqueue extends BaseController {
	use Constants;

	public function register() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_enqueue_scripts' ) );
	}

	public function enqueue( $screen ) {

		$_screen = get_current_screen();

		wp_register_style( 'gym-builder-admin-style', $this->plugin_url . 'assets/admin/css/gym-builder-admin.css', array(), self::$plugin_version );

		wp_register_style( 'jquery-ui-style', $this->plugin_url . 'assets/vendor/jquery-ui.css', array(), self::$plugin_version );

		wp_register_style( 'select2-style', $this->plugin_url . 'assets/vendor/select2.min.css', array(), self::$plugin_version );

		wp_register_style( 'jquery-timepicker-style', $this->plugin_url . 'assets/vendor/jquery.timepicker.css', array(), self::$plugin_version );

		wp_register_style( 'gym-builder-meta-fields-style', $this->plugin_url . 'assets/admin/css/meta-fields.css', array(), self::$plugin_version );

		wp_register_script( 'select2', $this->plugin_url . 'assets/vendor/select2.min.js', array(
			'jquery',
			'wp-color-picker'
		), self::$plugin_version, true );

//		wp_register_script( 'admin-settings-script', $this->plugin_url . 'assets/admin/js/admin-settings.js', array(
//			'jquery',
//			'wp-color-picker'
//		), self::$plugin_version, true );

		wp_register_script( 'jquery-timepicker-script', $this->plugin_url . 'assets/vendor/jquery.timepicker.min.js', array( 'jquery' ), self::$plugin_version, true );

		wp_register_script( 'gym-builder-meta-fields-script', $this->plugin_url . 'assets/admin/js/meta-fields.js', array(
			'jquery',
			'jquery-ui-core',
			'jquery-ui-datepicker',
			'wp-color-picker'
		), self::$plugin_version, true );

		//react admin settings page script
		wp_register_script( 'gym-builder-admin-settings-script', $this->plugin_url . 'assets/admin/js/admin-settings-page.js', array( 'jquery', ), self::$plugin_version, true );
		//member crud operation js
		wp_register_script( 'gym-builder-admin-page-script', $this->plugin_url . 'assets/admin/js/admin-page.js', array( 'jquery', ), self::$plugin_version, true );


		wp_enqueue_style( 'wp-color-picker' );

		wp_enqueue_style( 'select2-style' );

		wp_enqueue_script( 'wp-color-picker' );

		wp_enqueue_script( 'select2' );


		if ( ( 'edit.php' == $screen || 'post.php' == $screen || 'post-new.php' == $screen ) && ( $this->assets_enqueue_posts_type( $_screen ) ) ) {

			wp_enqueue_style( 'jquery-ui-style' );
			wp_enqueue_style( 'jquery-timepicker-style' );
			wp_enqueue_style( 'gym-builder-meta-fields-style' );


			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'jquery-timepicker-script' );
			wp_enqueue_script( 'gym-builder-meta-fields-script' );
			$admin_meta_localize_data = array(
				'memberPackageTaxUrl' => esc_url( admin_url( 'edit-tags.php?taxonomy=gb_pricing_plan_category&post_type=gb_pricing_plan' ) ),

			);
			wp_localize_script( 'gym-builder-meta-fields-script', 'adminMetaData', $admin_meta_localize_data );
		}
		//settings api page script
		if ( 'toplevel_page_gym_builder' == $screen ) {
//			wp_enqueue_script( 'admin-settings-script' );
			//react settings page script
			wp_enqueue_script('gym-builder-admin-settings-script');
			wp_localize_script(
				'gym-builder-admin-settings-script',
				'gymbuilderParams',
				$this->gym_builder_localize_params()
			);
		}
		if ( 'gym-builder_page_gym-builder-members' == $screen ) {
			
			wp_enqueue_media();
			wp_enqueue_script( 'gym-builder-admin-page-script' );
			wp_localize_script(
				'gym-builder-admin-page-script',
				'gymbuilderParams',
				$this->gym_builder_localize_params()
			);

		}
		wp_enqueue_script( 'gym-builder-meta-fields-script' );
		wp_enqueue_style( 'gym-builder-admin-style' );

	}

	public function frontend_enqueue_scripts() {
		$this->register_style();
		wp_enqueue_style( 'gym-builder-swiper' );
		wp_enqueue_style( 'gym-builder-icons' );
		wp_enqueue_style( 'gym-builder-style' );
		$this->dynamic_styles();
		$this->register_script();
		$this->load_swiper();
		$frontend_localize_data = Helper::fitness_calculator_translatable_text()
		                          + [];
		wp_localize_script( 'gym-builder-script', 'gymBuilderData', $frontend_localize_data );
		wp_enqueue_script( 'gym-builder-script' );
	}

	private function dynamic_styles() {
		ob_start();
		require_once $this->plugin_path . 'inc/DynamicStyles/Frontend.php';
		$dynamic_css = ob_get_clean();
		$dynamic_css = $this->optimized_css( $dynamic_css );
		wp_register_style( 'gym-builder-dynamic', false );
		wp_enqueue_style( 'gym-builder-dynamic' );
		wp_add_inline_style( 'gym-builder-dynamic', $dynamic_css );
	}

	private function optimized_css( $css ) {
		$css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
		$css = str_replace( [ "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ], ' ', $css );

		return $css;
	}

	public function register_style() {
		wp_register_style( 'gym-builder-icons', $this->plugin_url . 'assets/icons/css/wpdreamers-icons.css', array(), self::$plugin_version );
		wp_register_style( 'gym-builder-swiper', $this->plugin_url . 'assets/vendor/swiper.min.css', array(), self::$plugin_version );
		wp_register_style( 'gym-builder-style', $this->plugin_url . 'assets/public/css/gym-builder.css', array(), self::$plugin_version );
	}

	public function register_script() {
		wp_register_script( 'gym-builder-script', $this->plugin_url . 'assets/public/js/app.js', array( 'jquery' ), self::$plugin_version, true );
	}

	public function load_swiper() {
		$default_swiper_handle = 'swiper';
		$default_swiper_path   = $this->plugin_url . 'assets/vendor/swiper.min.js';
		if ( defined( 'ELEMENTOR_ASSETS_PATH' ) ) {
			$is_swiper8_enable = get_option( 'elementor_experiment-e_swiper_latest' );

			if ( $is_swiper8_enable == 'active' ) {
				$el_swiper_path = 'lib/swiper/v8/swiper.min.js';
			} else {
				$el_swiper_path = 'lib/swiper/swiper.min.js';
			}

			$elementor_swiper_path = ELEMENTOR_ASSETS_PATH . $el_swiper_path;

			if ( file_exists( $elementor_swiper_path ) ) {
				$default_swiper_path = ELEMENTOR_ASSETS_URL . $el_swiper_path;
			}
		}
		wp_register_script( $default_swiper_handle, $default_swiper_path, array( 'jquery' ), self::$plugin_version, true );
		wp_enqueue_script( $default_swiper_handle );
	}

	public function assets_enqueue_posts_type( $screen ) {
		if ( 'gym_builder_class' == $screen->post_type || 'gym_builder_trainer' == $screen->post_type || 'gb_class_shortcode' == $screen->post_type || 'gb_trainer_shortcode' == $screen->post_type || 'gb_pricing_plan' == $screen->post_type || 'gb_fitness_shortcode' == $screen->post_type ) {
			return true;
		} else {
			return false;
		}
	}
	public function gym_builder_localize_params(){
		$member_id_card_title = SettingsApi::get_option( 'member_id_generate_title', 'gym_builder_global_settings' ) ?: '';
		return [
			'member_id_card_title' => $member_id_card_title,
			'ajaxurl'              => esc_url( admin_url( 'admin-ajax.php' ) ),
			'homeurl'              => home_url(),
			'restApiUrl'           => esc_url_raw( rest_url() ),
			'rest_nonce'           => wp_create_nonce( 'wp_rest' ),
			'gb_admin_nonce'       => wp_create_nonce( 'gym_builder_nonce' ),
			'plugin_file_url'      => $this->plugin_url,
			'class_layout'         => Helper::class_page_layout()
		];
	}
}