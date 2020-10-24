<?php
/*
Plugin Name: VaMus
Plugin URI: tainacan.org
Description: Plugin for tainacan config to VaMus
Author: VaMus
Version: 0.0.1
Text Domain: tainacan-vamus
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/
namespace Tainacan\VaMus;

class TainacanVaMus {

	public function __construct() {
		add_action("init", [$this, "init"]);
	}

	function init() {
		require_once( plugin_dir_path(__FILE__) . '/classes/src/collection-form-config.php' );
	}
}

$tainacanVaMusBootstrapt = new \Tainacan\VaMus\TainacanVaMus();
