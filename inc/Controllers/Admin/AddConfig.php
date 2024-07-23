<?php
/**
 * @package GymBuilder
 */
namespace GymBuilder\Inc\Controllers\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}
use GymBuilder\Inc\Controllers\Helpers\Functions;

class AddConfig{
    public function __construct()
    {
        add_filter( 'display_post_states', array( $this, 'add_display_post_states' ), 10, 2 );
    }
    static function get_custom_page_list() {
		$pages = array(
			'classes'     => array(
				'title'   => esc_html__( 'Classes', 'gym-builder' ),
				'content' => ''
			),
            'trainers'     => array(
				'title'   => esc_html__( 'Trainers', 'gym-builder' ),
				'content' => ''
			),
		);

		return apply_filters( 'gym_builder_custom_pages_list', $pages );
	}
    public function add_display_post_states( $post_states, $post ) {
        $page_settings = Functions::get_page_ids();
        $pList         = $this->get_custom_page_list();
        foreach ( $page_settings as $type => $id ) {
            if ( $post->ID == $id ) {
                $post_states[] = $pList[ $type ]['title'] . " " . esc_html__( "Page", "gym-builder" );
            }
        }

        return $post_states;
    }

}