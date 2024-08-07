<?php
/**
 * @package GymBuilder
 */
namespace GymBuilder\Inc\Controllers\Admin\Api;

use GymBuilder\Inc\Controllers\Helpers\Helper;
use GymBuilder\Inc\Traits\Constants;
use GymBuilder\Inc\Traits\SingleTonTrait;
use GymBuilder\Inc\Controllers\Helpers\Functions;
use WP_REST_Request;
use WP_Error;
use WP_Query;
use WP_REST_Response;

class RestApi{
	use Constants,SingleTonTrait;
	public function init(){
		add_action('rest_api_init', [$this,'gym_builder_rest_api_posts_endpoint']);
	}

	public function gym_builder_rest_api_posts_endpoint(  ) {
		register_rest_route(self::$classes_endpoint_namespace, '/posts', array(
			'methods' => 'GET',
			'callback' => [$this,'get_all_rest_classes'],
			'permission_callback' => '__return_true',
		));
		register_rest_route(self::$classes_endpoint_namespace2, '/posts', array(
			'methods' => 'GET',
			'callback' => [$this,'get_all_rest_classes_json'],
			'permission_callback' => '__return_true',
		));
		register_rest_route(self::$classes_endpoint_namespace, '/categories', array(
			'methods' => 'GET',
			'callback' => [$this,'get_all_rest_class_categories'],
			'permission_callback' => '__return_true',
		));
		register_rest_route(self::$trainers_endpoint_namespace, '/posts', array(
			'methods' => 'GET',
			'callback' => [$this,'get_all_rest_trainers'],
			'permission_callback' => '__return_true',
		));
		register_rest_route(self::$trainers_endpoint_namespace, '/categories', array(
			'methods' => 'GET',
			'callback' => [$this,'get_all_rest_trainer_categories'],
			'permission_callback' => '__return_true',
		));
		register_rest_route('get_gym_builder/v1', '/pages', array(
			'methods' => 'GET',
			'callback' => [$this,'get_all_pages'],
			'permission_callback' => '__return_true',
		));
		register_rest_route(self::$membership_package_endpoint_namespace, '/posts', array(
			'methods' => 'GET',
			'callback' => [$this,'get_all_rest_membership_package'],
			'permission_callback' => array($this, 'check_admin_permission'),
		));
		register_rest_route('get_gym_builder_settings/v1', '/options', array(
			'methods' => 'GET',
			'callback' => [$this,'get_all_settings_options'],
			'permission_callback' => array($this, 'check_admin_permission'),
		));
		register_rest_route(self::$membership_package_endpoint_namespace, '/types', array(
			'methods' => 'GET',
			'callback' => [$this,'get_all_rest_membership_package_types'],
			'permission_callback' => array($this, 'check_admin_permission'),
		));
		register_rest_route(self::$membership_package_endpoint_namespace, '/category/(?P<id>\d+)', array(
			'methods' => 'GET',
			'callback' => [$this,'get_all_rest_membership_package_by_cat_id'],
			'permission_callback' => '__return_true',
			'args' => array(
				'id' => array(
					'validate_callback' => function($param, $request, $key) {
						return is_numeric( $param );
					}
				),
			),
		));
		register_rest_route(self::$gym_builder_members_endpoint_namespace, '/data', array(
			'methods' => 'GET',
			'callback' => [$this,'get_all_members_data'],
			'permission_callback' => array($this, 'check_admin_permission'),
		));
		register_rest_route(self::$gym_builder_members_endpoint_namespace, '/data/(?P<id>\d+)', array(
			'methods' => 'GET',
			'callback' => [$this,'get_single_member_data'],
			'permission_callback' => array($this, 'check_admin_permission'),
			'args' => array(
				'id' => array(
					'validate_callback' => function($param, $request, $key) {
						return is_numeric( $param );
					}
				),
			),
		));

	}

	public function get_all_pages( $data ) {
		$page_list = [];
		$pages     = get_pages(
			[
				'sort_column'  => 'menu_order',
				'sort_order'   => 'ASC',
				'hierarchical' => 0,
			]
		);
		if ($pages){
			foreach ( $pages as $page ) {
				$page_list[]=[
					'value' => $page->ID,
					'label' => ! empty( $page->post_title ) ? $page->post_title : '#' . $page->ID
				];
			}
		}
		return wp_json_encode($page_list);
	}

	public function get_all_rest_classes( $data ) {
		$args = array(
			'post_type' => self::$class_post_type,
			'post_status'  => 'publish',
			'posts_per_page' => -1,
		);

		$query = new WP_Query($args);

		if ( $query->have_posts() ) {
			return $query->posts;
		}else{
			return [];
		}


	}
	public function get_all_rest_classes_json(){
		$class_list=[];
		$args = array(
			'post_type'  => self::$class_post_type,
			'post_status'  => 'publish',
			'numberposts' => -1,
		);
		$classes = get_posts($args);
		if($classes){
			foreach ( $classes as $class ) {
				$class_list[]=[
					'value' => $class->ID,
					'label' => ! empty( $class->post_title ) ? $class->post_title : '#' . $class->ID
				];
			}
		}
		return wp_json_encode($class_list);
	}

	public function get_all_rest_trainers(  ) {
		$trainer_list=[];
		$args = array(
			'post_type'  => self::$trainer_post_type,
			'post_status'  => 'publish',
			'numberposts' => -1,
		);
		$trainers = get_posts($args);
		if($trainers){
			foreach ( $trainers as $trainer ) {
				$trainer_list[]=[
					'value' => $trainer->ID,
					'label' => ! empty( $trainer->post_title ) ? $trainer->post_title : '#' . $trainer->ID
				];
			}
		}
		return wp_json_encode($trainer_list);
		
	}

	public function get_all_rest_class_categories(  ) {
		$terms_list = [];
		$terms           = get_terms( [
			'taxonomy'   => self::$class_taxonomy,
			'hide_empty' => false
		] );
		if ( ! is_wp_error( $terms ) && $terms ) {
			foreach ( $terms as $term ) {
				$terms_list[] = [
					'value' => $term->term_id,
					'label' => $term->name,
				];
			}
		}
		return wp_json_encode( $terms_list );
		
	}

	public function get_all_rest_trainer_categories(  ) {
		$terms_list = [];
		$terms           = get_terms( [
			'taxonomy'   => self::$trainer_taxonomy,
			'hide_empty' => false
		] );
		if ( ! is_wp_error( $terms ) && $terms ) {
			foreach ( $terms as $term ) {
				$terms_list[] = [
					'value' => $term->term_id,
					'label' => $term->name,
				];
			}
		}
		return wp_json_encode( $terms_list );
	}
	public function get_all_rest_membership_package(  ) {
		 $args = array(
		 	'post_type' => self::$membership_package_post_type,
		 	'posts_per_page' => -1,
			'post_status'  => 'publish',
		 );

		 $query = new WP_Query($args);
		 if ( $query->have_posts()) {
			 return $query->posts;
		 }else{
			return [];
		 }
		
	}

	public function get_all_rest_membership_package_by_cat_id($data) {
		$args = array(
			'post_type' => self::$membership_package_post_type,
			'posts_per_page' => -1,
			'post_status'  => 'publish',
			'tax_query' =>[
				[
					'taxonomy' => self::$membership_package_taxonomy,
					'field'    => 'ID',
					'terms'    => $data['id'],
				]
			]
		);

		$query = new WP_Query($args);

		if ( $query->have_posts()) {
			return $query->posts;
		}else{
			return [];
		}
	}

	public function get_all_rest_membership_package_types($data){
		$terms           = get_terms( [
			'taxonomy'   => self::$membership_package_taxonomy,
			'hide_empty' => false
		] );
		if ( $terms) {
			return $terms;
		}else{
			return[];
		}
	}

	public function get_all_members_data( $request ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'gym_builder_members';
		$page = $request->get_param('page') ? (int) $request->get_param('page') : 1;
		$per_page = $request->get_param('per_page') ? (int) $request->get_param('per_page') : 10;
		$offset = ($page - 1) * $per_page;
		$search = $request->get_param('search') ? $request->get_param('search') : '';
		$order = $request->get_param('order') ? $request->get_param('order') : 'DESC';
		$sql = "SELECT id,member_name,member_gender,member_address,member_phone,member_email,membership_status,file_url 
            FROM $table_name";
		if (!empty($search)) {
			$sql .= $wpdb->prepare(" WHERE member_name LIKE %s OR member_email LIKE %s", '%' . $wpdb->esc_like($search) . '%', '%' . $wpdb->esc_like($search) . '%');
		}

		$sql .= " ORDER BY id $order";

		$sql .= $wpdb->prepare(" LIMIT %d OFFSET %d", $per_page, $offset);

		$results = $wpdb->get_results(apply_filters('get_members_data_sql',$sql), ARRAY_A);
		$total = $wpdb->get_var("SELECT COUNT(*) FROM $table_name" . (!empty($search) ? $wpdb->prepare(" WHERE member_name LIKE %s OR member_email LIKE %s", '%' . $wpdb->esc_like($search) . '%', '%' . $wpdb->esc_like($search) . '%') : ''));

		if ($results) {
			$response = new WP_REST_Response( $results );
			$response->set_status( 200 );
			$response->header( 'X-WP-Total', $total );
			$response->header( 'X-WP-TotalPages', ceil( $total / $per_page ) );
		}else{
			$response = [];
		}
		return $response;
	}
	public function get_single_member_data( $request ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'gym_builder_members';
		$member_id = $request['id'];
		$sql = $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $member_id);
		$result = $wpdb->get_row($sql, ARRAY_A);
		if ($result) {
			if ($result['membership_package_type']){
				$package_type_obj =  get_term_by('name', $result['membership_package_type'], self::$membership_package_taxonomy);
				$package_type_id= $package_type_obj->term_id;
				$result['package_type_id'] = $package_type_id;
			}
			$response = new WP_REST_Response( $result );
			$response->set_status( 200 );
		}else{
			$response = [];
		}
		return $response;
	}


	public function check_admin_permission( WP_REST_Request $request ) {
		$nonce      = $request->get_header( 'X-WP-Nonce' );
		
		if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) || ! current_user_can( 'manage_options' )) {
			return new WP_Error('rest_forbidden', __('You are not allowed to access this endpoint.','gym-builder'), array('status' => 403));
		}

		return true;
	}

	public function get_all_settings_options(  ) {
		return Helper::gym_builder_get_options();
	}
}