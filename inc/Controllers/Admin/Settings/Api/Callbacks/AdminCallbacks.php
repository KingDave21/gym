<?php
/**
 * @package MiltonPlugin
 */
namespace GymBuilder\Inc\Controllers\Admin\Settings\Api\Callbacks;

use \GymBuilder\Inc\Base\BaseController;
use GymBuilder\Inc\Controllers\Helpers\Functions;

class AdminCallbacks extends BaseController{

    public function adminDashboard(){
        return require_once("$this->plugin_path/templates/admin/admin.php");
    }

    public function about_callback(){
        return require_once("$this->plugin_path/templates/admin/about.php");
    }
//	public function about_callback(){
//		return require_once("$this->plugin_path/templates/admin/about.php");
//	}
	public function add_member() {
		Functions::renderView('add-member');
	}

	public function gym_builder_get_help_page(  ) {
		Functions::renderView('get-help');
	}
	public function gym_builder_members() {
		Functions::renderView('react-view');
	}

	public function gym_builder_settings_page(  ) {
		Functions::renderView('react-admin-settings');
	}

}