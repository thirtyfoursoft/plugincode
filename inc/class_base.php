<?php 

class Base {
	
	
	
	public function __construct() {
		
		echo '<script src="//code.jquery.com/jquery-1.12.4.js"></script>';	
		echo '<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">';
		echo '<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">';	
						
		wp_register_script( 'datatable', PLUGINS_URL . '/assets/js/jquery.dataTables.min.js' );		
		wp_register_script( 'custom', PLUGINS_URL . '/assets/js/custom.js' );		
		wp_register_style( 'style', PLUGINS_URL . '/assets/css/style.css' );
		
	}
	
	public function message( $message ) {
		return $HTML = '<div class="updated notice is-dismissible" id="message"><p>'.$message.'</p><button class="notice-dismiss" type="button"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
	}
	
	public function getMembershipsFromDB() {
		global $wpdb;
		return $wpdb->get_results( "SELECT * FROM ".PLUGINS_PREFIX.'memberships');
	}
	
	public function getPlansFromDB() {
		global $wpdb;
		return $wpdb->get_results( "SELECT * FROM ".PLUGINS_PREFIX.'plans');
	}
}


?>