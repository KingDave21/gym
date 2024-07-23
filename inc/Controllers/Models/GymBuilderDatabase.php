<?php
/**
 * @package GymBuilder
 */

namespace GymBuilder\Inc\Controllers\Models;

use GymBuilder\Inc\Traits\Constants;

class GymBuilderDatabase {
	use Constants;

	public static function create_member_db_table() {
		global $wpdb;
		if ( ! function_exists( 'dbDelta' ) ) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		}
		$charset_collate = $wpdb->get_charset_collate();
		$sql_query       = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}gym_builder_members` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `member_name` VARCHAR(255) NOT NULL,
            `member_address` TEXT NOT NULL,
            `member_email` VARCHAR(100) DEFAULT NULL,
            `member_phone` VARCHAR(30) NOT NULL,
            `member_age` INT DEFAULT NULL,
            `membership_status` TINYINT(1) DEFAULT 0,
            `member_joining_date` DATE DEFAULT NULL,
            `membership_duration_start` DATE DEFAULT NULL,
            `membership_duration_end` DATE DEFAULT NULL,
            `member_gender` VARCHAR(10) DEFAULT NULL,
            `membership_package_type` VARCHAR(30) DEFAULT NULL,
            `membership_package_name` VARCHAR(30) DEFAULT NULL,
            `membership_classes` TEXT DEFAULT NULL,
            `file_url` VARCHAR(255) DEFAULT NULL
        ) $charset_collate;";
		dbDelta( $sql_query );

	}

	public static function check_and_update_db_table() {
		$installed_version       = get_option( 'gym_builder_version' );
		$member_db_table_version = get_option( 'gb_members_db_table_version' );
		if ( $installed_version !== self::$plugin_version && ! $member_db_table_version) {
			self::create_member_db_table();
			update_option( 'gym_builder_version', self::$plugin_version );
			update_option( 'gb_members_db_table_version', self::$members_db_table_version );
		}
	}
}