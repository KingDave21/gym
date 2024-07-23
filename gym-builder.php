<?php
/****
* Plugin Name:Gym Builder
* Plugin URI: https://wordpress.org/plugins/gym-builder/
* Author: WPDreamers
* Author URI: https://profiles.wordpress.org/wpdreamers/
* Description: The Best Gym Building Plugin for WordPress to Create Gym,Fitness,Body Building,Yoga Website
* Version: 2.1.0
* License: GPLv3
* License URI: http://www.gnu.org/licenses/gpl-3.0.html
* Text Domain:gym-builder
******/



if(! defined('ABSPATH')){
    die;
}

if(file_exists(dirname(__FILE__).'/vendor/autoload.php')){
    require_once dirname(__FILE__).'/vendor/autoload.php';
}

if(class_exists('GymBuilder\\Inc\\Init')){
    GymBuilder\Inc\Init::register_services();
}
