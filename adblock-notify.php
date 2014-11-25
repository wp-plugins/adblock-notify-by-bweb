<?php
/**
 * Plugin Name: Adblock Notify by b*web
 * Plugin URI: http://b-website.com/
 * Description: An Adblock detection and nofitication plugin with get around options and a lot of settings. Dashboard widget with adblock counter included!
 * Version: 1.2
 * Author: Brice CAPOBIANCO
 * Author URI: b-website.com
 * Text Domain: an-translate
 * Domain Path: /languages
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
 * Set priority to properly load plugin translation
 ***************************************************************/
function an_translate_load_textdomain() {
	$path = basename( dirname( __FILE__ ) ) . '/languages/';
	load_plugin_textdomain( 'an-translate', false, $path);
}
add_action( 'plugins_loaded', 'an_translate_load_textdomain', 1 );



/***************************************************************
 * Load plugin files
 ***************************************************************/
require_once( AN_PATH . 'lib/titan-framework/titan-framework.php' );
require_once( AN_PATH . 'adblock-notify-options.php' );
require_once( AN_PATH . 'adblock-notify-functions.php' );


/***************************************************************
 * Front-End Scripts & Styles enqueueing
 ***************************************************************/
function an_enqueue_an_sripts(){
	if(!is_admin()){ 
		
		$anScripts = unserialize( get_option( 'adblocker_notify_selectors' ) );

		//JS
		if($anScripts['temp-path'] != false)
		wp_enqueue_script( 'an_scripts', $anScripts['temp-url'].$anScripts['files']['js'], array( 'jquery' ),  NULL, true);
		
		wp_enqueue_script( 'an_advertisement', AN_URL . 'js/advertisement.js', array( 'jquery' ),  NULL, true);
		
		//AJAX
		wp_localize_script( 'an_scripts', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );	
	
		//CSS
		if($anScripts['temp-path'] != false) {
			wp_register_style( 'an_style', $anScripts['temp-url'].$anScripts['files']['css'], array(), NULL, NULL);
			
			$adBlockeNotify = unserialize(get_option( 'adblocker_notify_options'));
			if( $adBlockeNotify['an_option_choice'] == 2 ) { 		
				//Enqeue AN style
				wp_enqueue_style('an_style');
			}
		}

		//CSS file does not exist anymore
		wp_dequeue_style('tf-compiled-options-adblocker_notify');
		
	}
}
add_action( 'wp_enqueue_scripts', 'an_enqueue_an_sripts', 100);


/***************************************************************
 * Front-End Scripts & Styles printing
 * Only if an-temp is not writable
 ***************************************************************/
function an_print_an_sripts(){
		$anScripts = unserialize( get_option( 'adblocker_notify_selectors' ) );
		
		if($anScripts['temp-path'] == false)
		an_print_change_files_css_selectors();
		
}
add_action('wp_footer', 'an_print_an_sripts');


/***************************************************************
 * Back-End Scripts & Styles enqueueing
 ***************************************************************/
function an_register_admin_scripts() {
	//JS
	wp_enqueue_script( 'an_admin_scripts', AN_URL . 'js/an-admin-scripts.js', array( 'jquery' ),  NULL, true);
	//CSS
	wp_enqueue_style( 'an_admin_style', AN_URL . 'css/an-admin-style.css', array(), NULL, NULL);
}
function an_enqueue_admin_scripts() {
	
	$screen = get_current_screen();
    if ( $screen->id != 'toplevel_page_'. AN_ID )
        return;
		
		an_register_admin_scripts();
		
}
add_action('admin_enqueue_scripts', 'an_enqueue_admin_scripts');
add_filter( 'user_contactmethods', 'user_contactmethods_example' );


/***************************************************************
 * Add settings link on plugin list page
 ***************************************************************/
function an_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page='.AN_ID.'">'. __( 'Settings', 'an-translate' ) .'</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
add_filter('plugin_action_links_'.AN_BASE, 'an_settings_link' );


/***************************************************************
 * Add custom meta link on plugin list page
 ***************************************************************/
if ( ! function_exists( 'an_meta_links' ) ) {
	function an_meta_links( $links, $file ) {
		if ( strpos( $file, 'adblock-notify.php' ) !== false ) {
			$links[0] = '<a href="http://b-website.com/" target="_blank"><img src="' . AN_URL . 'img/icon-bweb.png" style="margin-bottom: -4px;" alt="b*web"/></a>&nbsp;&nbsp;'. $links[0];
			$links = array_merge( $links, array( '<a href="http://b-website.com/category/plugins" target="_blank" title="'. __( 'More b*web Plugins', 'an-translate' ) .'">'. __( 'More b*web Plugins', 'an-translate' ) .'</a>' ) );
			$links = array_merge( $links, array( '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7Z6YVM63739Y8" target="_blank" title="'. __( 'Donate', 'an-translate' ) .'"><strong>'. __( 'Donate', 'an-translate' ) .'</strong></a>' ) );
		}
		return $links;
	}
	add_filter( 'plugin_row_meta', 'an_meta_links', 10, 2 );
}


/***************************************************************
 * Admin Panel Favico
 ***************************************************************/
function an_add_favicon() {
    $screen = get_current_screen();
    if ( $screen->id != 'toplevel_page_'. AN_ID )
        return;

  	$favicon_url = AN_URL . 'img/icon-bweb.svg';
	echo '<link rel="shortcut icon" href="' . $favicon_url . '" />';
}
add_action('admin_head', 'an_add_favicon');
 

/***************************************************************
 * Create random selectors and files on plugin activation
 ***************************************************************/
function adblocker_notify_activate() {
	add_action( 'tf_create_options', 'an_create_options' );
	an_save_setting_random_selectors();
}
if (function_exists('adblocker_notify_activate')) {
	register_activation_hook( __FILE__, 'adblocker_notify_activate');
}


/***************************************************************
 * Remove Plugin settings from DB on uninstallation (= plugin deletion) 
 ***************************************************************/
//Hooks for install
if (function_exists('register_uninstall_hook')) {
	register_uninstall_hook(__FILE__, 'adblocker_notify_uninstall');
}

//Remove directory
function an_delete_temp_folder($dirPath) {
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            self::deleteDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}

//Uninstall function
function adblocker_notify_uninstall() {
	// Remove temp files
	$anTempDir = unserialize( get_option( 'adblocker_notify_selectors' ) );
	an_delete_temp_folder($anTempDir['temp-path']);
	$uploadDir = wp_upload_dir();
	unlink(trailingslashit( $uploadDir['basedir'] ) . 'titan-framework-adblocker_notify-css.css');

	// Remove option from DB
	delete_option( 'adblocker_notify_options' );
	delete_option( 'adblocker_notify_counter' );
	delete_option( 'adblocker_notify_selectors' );
}