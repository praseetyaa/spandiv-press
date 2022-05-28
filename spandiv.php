<?php

/*
Plugin Name: Spandiv
Plugin URI: https://spandiv.xyz/
Description: Custom WordPress Features.
Version: 1.5.4
Author: Spandiv
Author URI: https://spandiv.xyz/
License: GPL2
*/


// Require classes
require_once('includes/Spandiv_Plugin.php');
require_once('includes/Spandiv_REST.php');

// Require functions
require_once('functions/notice.php');
require_once('functions/enqueue.php');

// Create table on register activation hook
register_activation_hook(__FILE__, array('Spandiv_Plugin', 'create_table'));

// Add action
add_action('admin_menu', array('Spandiv_Plugin', 'add_admin_page'));

// Add shortcode
add_shortcode('spandiv', array('Spandiv_Plugin', 'get_value_for_shortcode'));

// REST API
add_action('rest_api_init', function() {	
	register_rest_route('spandiv/v1', 'information', array(
		'methods'  => 'GET',
		'callback' => array('Spandiv_REST', 'get_info')
	));
});

// Plugin update checker
require 'plugin-update/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
  'https://github.com/praseetyaa/spandiv-press', // GitHub repo
  __FILE__, // Full path to the main plugin file or functions.php
  'spandiv' // Plugin slug
);

// Set the branch that contains the stable release
$myUpdateChecker->setBranch('main');

?>
