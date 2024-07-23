<?php
/**
 * @package GymBuilder
 */

namespace GymBuilder\Inc\Controllers;

use GymBuilder\Inc\Controllers\Admin\Settings\Api\SettingsApi;
use GymBuilder\Inc\Controllers\Models\GymBuilderMail;
use GymBuilder\Inc\Traits\Constants;
use GymBuilder\Inc\Traits\SingleTonTrait;

class AjaxController {
	use Constants, SingleTonTrait;

	public function init() {
		add_action( 'wp_ajax_gym_builder_insert_members', [ $this, 'gym_builder_insert_members' ] );
		add_action( 'wp_ajax_gym_builder_delete_member', [ $this, 'delete_single_member_data' ] );
		add_action( 'wp_ajax_gym_builder_edit_members', [ $this, 'edit_member_data' ] );
		add_action( 'wp_ajax_gym_builder_send_member_email', [ $this, 'send_member_mail' ] );
		add_action( 'wp_ajax_gym_builder_save_settings', [ $this, 'save_options_settings' ] );
	}

	public function gym_builder_insert_members() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'gym_builder_nonce' ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Nonce verification failed.' );
		}
		global $wpdb;
		$membership_package_type_name = null;
		$membership_package_type_id   = intval( $_POST['packageType'] );
		if ( $membership_package_type_id != null ) {
			$membership_package_type_obj  = get_term_by( 'id', intval( $_POST['packageType'] ), self::$membership_package_taxonomy );
			$membership_package_type_name = $membership_package_type_obj->name;
		}
		$data     = [
			'member_name'               => sanitize_text_field( $_POST['memberName'] ),
			'member_address'            => sanitize_textarea_field( $_POST['memberAddress'] ),
			'member_email'              => sanitize_email( $_POST['memberEmail'] ),
			'member_phone'              => sanitize_text_field( $_POST['memberPhone'] ),
			'member_age'                => intval( $_POST['memberAge'] ),
			'membership_status'         => $_POST['membershipStatus'] ? 1 : 0,
			'member_joining_date'       => sanitize_text_field( date( 'Y-m-d', strtotime( $_POST['memberJoiningDate'] ) ) ),
			'membership_duration_start' => sanitize_text_field( date( 'Y-m-d', strtotime( $_POST['membershipDuration'][0] ) ) ),
			'membership_duration_end'   => sanitize_text_field( date( 'Y-m-d', strtotime( $_POST['membershipDuration'][1] ) ) ),
			'member_gender'             => sanitize_text_field( $_POST['memberGender'] ),
			'membership_package_type'   => sanitize_text_field( $membership_package_type_name ),
			'membership_package_name'   => sanitize_text_field( $_POST['packageName'] ),
			'membership_classes'        => sanitize_text_field( $_POST['classesName'] ),
			'file_url'                  => esc_url_raw( $_POST['fileUrl'] )
		];
		$inserted = $wpdb->insert(
			"{$wpdb->prefix}gym_builder_members",
			$data,
			[
				'%s',
				'%s',
				'%s',
				'%s',
				'%d',
				'%d',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s'
			]
		);

		if ( $inserted ) {
			wp_send_json_success( __( 'Member added successfully.', 'gym-builder' ) );
		} else {
			wp_send_json_error( __( 'Failed to add member.', 'gym-builder' ) );
		}

	}

	public function delete_single_member_data() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'gym_builder_nonce' ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Nonce verification failed.' );
		}
		$member_id = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : 0;
		if ( $member_id <= 0 ) {
			wp_send_json_error( 'Invalid member ID.' );
		}
		global $wpdb;
		$table_name    = $wpdb->prefix . 'gym_builder_members';
		$delete_sql    = $wpdb->prepare( "DELETE FROM $table_name WHERE id = %d", $member_id );
		$delete_result = $wpdb->query( $delete_sql );
		if ( $delete_result !== false ) {
			wp_send_json_success( __( 'Member deleted successfully.', 'gym-builder' ) );
		} else {
			wp_send_json_error( __( 'Failed to delete member.', 'gym-builder' ) );
		}

	}

	public function edit_member_data() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'gym_builder_nonce' ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Nonce verification failed.' );
		}
		$member_id = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : 0;
		if ( $member_id <= 0 ) {
			wp_send_json_error( 'Invalid member ID.' );
		}
		global $wpdb;
		$membership_package_type_name = null;
		$membership_package_type_id   = intval( $_POST['packageType'] );
		if ( $membership_package_type_id != null ) {
			$membership_package_type_obj  = get_term_by( 'id', intval( $_POST['packageType'] ), self::$membership_package_taxonomy );
			$membership_package_type_name = $membership_package_type_obj->name;
		}
		$data    = [
			'member_address'            => sanitize_textarea_field( $_POST['memberAddress'] ),
			'member_email'              => sanitize_email( $_POST['memberEmail'] ),
			'member_phone'              => sanitize_text_field( $_POST['memberPhone'] ),
			'member_age'                => intval( $_POST['memberAge'] ),
			'membership_status'         => $_POST['membershipStatus'] ? 1 : 0,
			'membership_duration_start' => sanitize_text_field( date( 'Y-m-d', strtotime( $_POST['membershipDuration'][0] ) ) ),
			'membership_duration_end'   => sanitize_text_field( date( 'Y-m-d', strtotime( $_POST['membershipDuration'][1] ) ) ),
			'membership_package_type'   => sanitize_text_field( $membership_package_type_name ),
			'membership_package_name'   => sanitize_text_field( $_POST['packageName'] ),
			'membership_classes'        => sanitize_text_field( $_POST['classesName'] ),
			'file_url'                  => esc_url_raw( $_POST['fileUrl'] )
		];
		$updated = $wpdb->update(
			"{$wpdb->prefix}gym_builder_members",
			$data,
			[ 'id' => $member_id ],
			[
				'%s',
				'%s',
				'%s',
				'%d',
				'%d',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s'
			]

		);
		if ( $updated ) {
			wp_send_json_success( __( 'Member Info Updated successfully.', 'gym-builder' ) );
		} else {
			wp_send_json_error( __( 'Failed updated member info.', 'gym-builder' ) );
		}

	}

	public function send_member_mail() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'gym_builder_nonce' ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Nonce verification failed.' );
		}

		$name           = sanitize_text_field( $_POST['name'] );
		$email_to       = sanitize_email( $_POST['email'] );
		$message        = sanitize_textarea_field( $_POST['message'] );
		$mail_subject   = esc_html__( 'Hello ' . $name, 'gym-builder' );
		$mail_from_name = SettingsApi::get_option( 'member_id_generate_title', 'gym_builder_global_settings' ) ?: get_bloginfo( 'name' );
		$mail_from      = SettingsApi::get_option( 'member_sender_mail', 'gym_builder_global_settings' ) ?: wp_get_current_user()->data->user_email;
		$email_args     = [
			'to'        => $email_to,
			'subject'   => $mail_subject,
			'mail_body' => $message,
			'from'      => $mail_from,
			'from_name' => $mail_from_name,
		];

		if ( isset( $_FILES['mail_file'] ) ) {
			$email_args['file'] = $_FILES;
			$mail_sent          = GymBuilderMail::send_mail( true, $email_args );
		} else {
			$mail_sent = GymBuilderMail::send_mail( false, $email_args );
		}

		if ( $mail_sent ) {
			wp_send_json_success( __( 'Mail Sent Successfully.', 'gym-builder' ) );
		} else {
			wp_send_json_error( __( 'Failed to mail sent.', 'gym-builder' ) );
		}

	}

	public function save_options_settings() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'gym_builder_nonce' ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Nonce verification failed.' );
		}

		$this->page_options_settings_save( 'gym_builder_page_settings', $_POST['gym_builder_page_settings'] );
		$this->permalink_options_settings_save( 'gym_builder_permalinks_settings', $_POST['gym_builder_permalinks_settings'] );
		$this->class_options_settings_save( 'gym_builder_class_settings', $_POST['gym_builder_class_settings'] );
		$this->trainer_options_settings_save( 'gym_builder_trainer_settings', $_POST['gym_builder_trainer_settings'] );
		$this->style_options_settings_save( 'gym_builder_style_settings', $_POST['gym_builder_style_settings'] );
		$this->global_options_settings_save( 'gym_builder_global_settings', $_POST['gym_builder_global_settings'] );

		wp_send_json_success( __( 'Settings Save Successfully.', 'gym-builder' ) );
	}

	public function class_options_settings_save( $key, $data ) {

		$class_settings = get_option( $key, [] );
		$class_settings['class_time_format']         = sanitize_text_field( $data['class_time_format'] ) ?? '12';
		$class_settings['class_archive_style']       = sanitize_text_field( $data['class_archive_style'] ) ?? 'layout-1';
		$class_settings['class_posts_per_page']      = sanitize_text_field( $data['class_posts_per_page'] ) ?? '9';
		$class_settings['class_grid_columns']        = sanitize_text_field( $data['class_grid_columns'] ) ?? '3';
		$class_settings['class_page_layout']         = sanitize_text_field( $data['class_page_layout'] ) ?? 'full-width';
		$class_settings['class_single_page_layout']  = sanitize_text_field( $data['class_single_page_layout'] ) ?? 'full-width';
		$class_settings['include_class']             = ! empty( $data['include_class'] ) ? array_map( 'sanitize_text_field', $data['include_class'] ) : [];
		$class_settings['exclude_class']             = ! empty( $data['exclude_class'] ) ? array_map( 'sanitize_text_field', $data['exclude_class'] ) : [];
		$class_settings['class_categories']          = ! empty( $data['class_categories'] ) ? array_map( 'sanitize_text_field', $data['class_categories'] ) : [];
		$class_settings['class_orderBy']             = sanitize_text_field( $data['class_orderBy'] ) ?? 'none';
		$class_settings['class_order']               = sanitize_text_field( $data['class_order'] ) ?? 'ASC';
		$class_settings['class_thumbnail_width']     = sanitize_text_field( $data['class_thumbnail_width'] ) ?? '570';
		$class_settings['class_thumbnail_height']    = sanitize_text_field( $data['class_thumbnail_height'] ) ?? '400';
		$class_settings['class_thumbnail_hard_crop'] = sanitize_text_field( $data['class_thumbnail_hard_crop'] ) ?? 'on';
		$class_settings['slider_autoplay']           = sanitize_text_field( $data['slider_autoplay'] ) ?? 'on';
		$class_settings['slider_loop']               = sanitize_text_field( $data['slider_loop'] ) ?? 'on';
		$class_settings['centered_slider']           = sanitize_text_field( $data['centered_slider'] ) ?? 'off';
		$class_settings['slides_per_view']           = sanitize_text_field( $data['slides_per_view'] ) ?? '3';

		update_option( $key, $class_settings );
	}

	public function trainer_options_settings_save( $key, $data ) {
		$trainer_settings                                = get_option( $key, [] );
		$trainer_settings['trainer_posts_per_page']      = sanitize_text_field( $data['trainer_posts_per_page'] ) ?? '9';
		$trainer_settings['trainer_grid_columns']        = sanitize_text_field( $data['trainer_grid_columns'] ) ?? '3';
		$trainer_settings['trainer_page_layout']         = sanitize_text_field( $data['trainer_page_layout'] ) ?? 'full-width';
		$trainer_settings['trainer_single_page_layout']  = sanitize_text_field( $data['trainer_single_page_layout'] ) ?? 'full-width';
		$trainer_settings['include_trainer']             = ! empty( $data['include_trainer'] ) ? array_map( 'sanitize_text_field', $data['include_trainer'] ) : [];
		$trainer_settings['exclude_trainer']             = ! empty( $data['exclude_trainer'] ) ? array_map( 'sanitize_text_field', $data['exclude_trainer'] ) : [];
		$trainer_settings['trainer_categories']          = ! empty( $data['trainer_categories'] ) ? array_map( 'sanitize_text_field', $data['trainer_categories'] ) : [];
		$trainer_settings['trainer_orderBy']             = sanitize_text_field( $data['trainer_orderBy'] ) ?? 'none';
		$trainer_settings['trainer_order']               = sanitize_text_field( $data['trainer_order'] ) ?? 'ASC';
		$trainer_settings['trainer_thumbnail_width']     = sanitize_text_field( $data['trainer_thumbnail_width'] ) ?? '570';
		$trainer_settings['trainer_thumbnail_height']    = sanitize_text_field( $data['trainer_thumbnail_height'] ) ?? '400';
		$trainer_settings['trainer_thumbnail_hard_crop'] = sanitize_text_field( $data['trainer_thumbnail_hard_crop'] ) ?? 'on';

		update_option( $key, $trainer_settings );
	}

	public function style_options_settings_save( $key, $data ) {
		$style_settings                                          = get_option( $key, [] );
		$style_settings['gym_builder_primary_color']             = sanitize_text_field( $data['gym_builder_primary_color'] ) ?? '#005dd0';
		$style_settings['gym_builder_secondary_color']           = sanitize_text_field( $data['gym_builder_secondary_color'] ) ?? '#0a4b78';
		$style_settings['gym_builder_class_title_color']         = sanitize_text_field( $data['gym_builder_class_title_color'] ) ?? '';
		$style_settings['gym_builder_class_content_color']       = sanitize_text_field( $data['gym_builder_class_content_color'] ) ?? '';
		$style_settings['gym_builder_class_schedule_color']      = sanitize_text_field( $data['gym_builder_class_schedule_color'] ) ?? '';
		$style_settings['gym_builder_class_trainer_color']       = sanitize_text_field( $data['gym_builder_class_trainer_color'] ) ?? '';
		$style_settings['gym_builder_class_table_title_color']   = sanitize_text_field( $data['gym_builder_class_table_title_color'] ) ?? '';
		$style_settings['gym_builder_class_table_border_color']  = sanitize_text_field( $data['gym_builder_class_table_border_color'] ) ?? '';
		$style_settings['gym_builder_class_table_heading_color'] = sanitize_text_field( $data['gym_builder_class_table_heading_color'] ) ?? '';
		$style_settings['gym_builder_trainer_title_color']       = sanitize_text_field( $data['gym_builder_trainer_title_color'] ) ?? '';
		$style_settings['gym_builder_trainer_designation_color'] = sanitize_text_field( $data['gym_builder_trainer_designation_color'] ) ?? '';
		$style_settings['gym_builder_trainer_content_color']     = sanitize_text_field( $data['gym_builder_trainer_content_color'] ) ?? '';
		$style_settings['gym_builder_trainer_bg_color']          = sanitize_text_field( $data['gym_builder_trainer_bg_color'] ) ?? '';

		update_option( $key, $style_settings );
	}

	public function global_options_settings_save( $key, $data ) {
		$global_settings                             = get_option( $key, [] );
		$global_settings['member_id_generate_title'] = sanitize_text_field( $data['member_id_generate_title'] ) ?? '';
		$global_settings['member_sender_mail']       = sanitize_text_field( $data['member_sender_mail'] ) ?? '';
		update_option( $key, $global_settings );
	}

	public function permalink_options_settings_save( $key, $data ) {
		$permalinks_settings                          = get_option( $key, [] );
		$permalinks_settings['class_base']            = sanitize_text_field( $data['class_base'] ) ?? '';
		$permalinks_settings['class_category_base']   = sanitize_text_field( $data['class_category_base'] ) ?? '';
		$permalinks_settings['trainer_base']          = sanitize_text_field( $data['trainer_base'] ) ?? '';
		$permalinks_settings['trainer_category_base'] = sanitize_text_field( $data['trainer_category_base'] ) ?? '';

		update_option( $key, $permalinks_settings );
	}

	public function page_options_settings_save( $key, $data ) {
		$page_settings             = get_option( $key, [] );
		$page_settings['classes']  = sanitize_text_field( $data['classes'] ) ?? '';
		$page_settings['trainers'] = sanitize_text_field( $data['trainers'] ) ?? '';
		update_option( $key, $page_settings );
	}
}
