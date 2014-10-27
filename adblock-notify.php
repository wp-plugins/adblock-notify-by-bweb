<?php
/**
 * Plugin Name: Adblock Notify by b*web
 * Plugin URI: http://b-website.com/
 * Description: An Adblock detection and nofitication plugin with get around options and a lot of settings
 * Version: 0.1
 * Author: Brice CAPOBIANCO
 * Author URI: b-website.com
 */
 
 
/***************************************************************
 * SECURITY : Exit if accessed directly
***************************************************************/
if ( !function_exists('add_action') ) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if ( !defined('ABSPATH') ) {
	exit;
}



/***************************************************************
 * Define constants
 ***************************************************************/
if ( !defined('AN_PATH') ) {
	define( 'AN_PATH', plugin_dir_path( __FILE__ ) ); 
}
if ( !defined('AN_URL') ) {
	define( 'AN_URL', plugin_dir_url( __FILE__ ) ); 
}
if ( !defined('AN_BASE') ) {
	define( 'AN_BASE', plugin_basename(__FILE__) ); 
}
if ( !defined('AN_NAME') ) {
	define( 'AN_NAME', 'Adblock Notify' ); 
}
if ( !defined('AN_ID') ) {
	define( 'AN_ID', 'adblock-notify' ); 
}
if ( !defined('AN_COOKIE') ) {
	define( 'AN_COOKIE', 'anCookie' ); 
}


/***************************************************************
 * Load plugin files
 ***************************************************************/
//require_once( ABSPATH . 'wp-admin/includes/screen.php' );
require_once( AN_PATH . 'adblock-notify-options.php' );
require_once( AN_PATH . 'adblock-notify-functions.php' );


/***************************************************************
 * Front-End Scripts & Styles enqueueing
 ***************************************************************/
function an_enqueue_modal(){
	if(!is_admin()){ 
		//JS
		wp_enqueue_script( 'an_scripts', AN_URL . 'js/an_scripts.min.js', array( 'jquery' ),  NULL, true);
		wp_enqueue_script( 'an_advertisement', AN_URL . 'js/advertisement.js', array( 'jquery' ),  NULL, true);
		//AJAX
		wp_localize_script( 'an_scripts', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );	
	
		$adBlockerNotify = unserialize(get_option( 'adblocker_notify_options'));
		if( $adBlockerNotify['an_option_choice'] == 2 ) { 		
			//CSS
			wp_enqueue_style( 'an_style', AN_URL . 'css/an_style.min.css', array(), NULL, NULL);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'an_enqueue_modal' );


/***************************************************************
 * Back-End Scripts & Styles enqueueing
 ***************************************************************/
function an_admin_scripts() {
 		//JS
    	wp_enqueue_script( 'an_chart_js', AN_URL . 'lib/chart-js/Chart.min.js', array( 'jquery' ),  NULL);
   
		$screen = get_current_screen();
    if ( $screen->id != 'toplevel_page_'. AN_ID )
        return;
		//JS
    	wp_enqueue_script( 'an_admin_scripts', AN_URL . 'js/an_admin_scripts.js', array( 'jquery' ),  NULL, true);
		//CSS
		wp_enqueue_style( 'an_admin_', AN_URL . 'css/an_admin_style.css', array(), NULL, NULL);
}
add_action('admin_enqueue_scripts', 'an_admin_scripts');


/***************************************************************
 * Add settings link on plugin page
 ***************************************************************/
function an_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page='.AN_ID.'">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
add_filter("plugin_action_links_".AN_BASE, 'an_settings_link' );

/***************************************************************
 * Remove Plugin settings from DB on uninstallation (= plugin deletion) 
 ***************************************************************/
//Hooks for install
if (function_exists('register_uninstall_hook')) {
	register_uninstall_hook(__FILE__, 'adblocker_notify_uninstall');
}

function adblocker_notify_uninstall() {
	// Remove option from DB
	delete_option( 'adblocker_notify_options' );
	delete_option( 'adblocker_notify_counter' );
}