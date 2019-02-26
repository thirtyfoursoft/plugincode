<?php

add_action( 'init', 'createTable'); 
function createTable() {
	global $wpdb;
	global $charset_collate;
		
	$membershipsQRY = "CREATE TABLE IF NOT EXISTS ".PLUGINS_PREFIX."memberships (
			ID INT(11) NOT NULL auto_increment,
			name VARCHAR(255) NOT NULL ,
			description LONGTEXT NOT NULL ,
			status INT(2) NOT NULL default '1', 
			date datetime NOT NULL default '0000-00-00 00:00:00',
			PRIMARY KEY (ID)
		) $charset_collate";
	$plansQRY = "CREATE TABLE IF NOT EXISTS ".PLUGINS_PREFIX."plans (
			ID INT(11) NOT NULL auto_increment,
			name VARCHAR(255) NOT NULL ,
			description LONGTEXT NOT NULL ,
			status INT(2) NOT NULL default '1', 
			date datetime NOT NULL default '0000-00-00 00:00:00',
			PRIMARY KEY (ID)
		) $charset_collate";
	$plansDataQRY = "CREATE TABLE IF NOT EXISTS ".PLUGINS_PREFIX."plans_data (
			ID INT(11) NOT NULL auto_increment,
			membershipID INT(11) NOT NULL,
			planID INT(11) NOT NULL,
			age_from INT(3) NOT NULL,
			age_to INT(3) NOT NULL,
			single DECIMAL(13,4) NOT NULL,
			member DECIMAL(13,4) NOT NULL,
			family DECIMAL(13,4) NOT NULL,
			description LONGTEXT NOT NULL ,
			status INT(2) NOT NULL default '1', 
			date datetime NOT NULL default '0000-00-00 00:00:00',
			PRIMARY KEY (ID)
		) $charset_collate";	
	$assignPlan = "CREATE TABLE IF NOT EXISTS ".PLUGINS_PREFIX."assign_plans (
			ID INT(11) NOT NULL auto_increment,
			membershipID INT(11) NOT NULL,
			planID INT(11) NOT NULL,
			status INT(2) NOT NULL default '1', 
			date datetime NOT NULL default '0000-00-00 00:00:00',
			PRIMARY KEY (ID)
		) $charset_collate";	
	 
	dbDelta( $membershipsQRY );
	dbDelta( $plansQRY );
	dbDelta( $plansDataQRY );
	dbDelta( $assignPlan );
}
// Create tables on plugin activation
register_activation_hook( __FILE__, 'createTable' );
//register_deactivation_hook($file, $function);
//register_uninstall_hook( __FILE__, $function );

