<?php 
/* 
*	Plugin Name: U65
*   Plugin URI: http://example.com/
*	Description: Monthly contribution requests for standard and advantage memberships.
*	Version: 1.0
*	Author: Example
*	Author URI: http://example.com/
*/

if(! defined( 'ABSPATH' )) exit; // Exit if accessed directly

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
require_once("u65_create_table.php");
require_once('inc/class_base.php');
require_once('inc/class_u65_memberships.php');
require_once('inc/class_u65_plans.php');
require_once('inc/class_u65_records.php');
require_once('inc/class_u65_overview.php');

define ("PLUGINS_PREFIX", "u65_");
define ("PLUGINS_URL", plugins_url("U65"));	
define ("PLUGIN_NANE", "U65");

function theme_options_panel() {
	
	$capability	= 'manage_options';
	$mainMenuSlug = 'u65_memberships';
	$icon = 'dashicons-category'; 
	
	add_menu_page("U65", "U65", $capability, $mainMenuSlug, "", $icon, $position = 6);
	add_submenu_page($mainMenuSlug, $pageTitle = 'Memberships', $menuTitle = 'Add Memberships', $capability, $mainMenuSlug, $function = 'u65_memberships');
	add_submenu_page($mainMenuSlug, $pageTitle = 'Add Plans', $menuTitle = 'Add Plans', $capability, $slug = 'u65_plans', $function = "u65_plans");
	add_submenu_page($mainMenuSlug, $pageTitle = 'Add Records', $menuTitle = 'Add Records', $capability, $slug = 'u65_records', $function = "u65_records");
	add_submenu_page($mainMenuSlug, $pageTitle = 'Overview', $menuTitle = 'Overview', $capability, $slug = 'u65_overview', $function = "u65_overview");
}
add_action('admin_menu', 'theme_options_panel');



function u65_memberships() {	
	$member = new U65_Memberships;
	$member->display();	
}
		
function u65_plans() {
    $member = new U65_Plans;
	$member->display();
}

function u65_records() {
    $member = new U65_Records;
	$member->display();
}

function u65_overview() {
	$member = new U65_Overview;	
	$result = $member->display();
	
}	
	