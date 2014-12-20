<?php
/**
 * Plugin Name: Adblock Notify by b*web
 * Plugin URI: http://b-website.com/
 * Description: An Adblock detection and nofitication plugin with get around options and a lot of settings. Dashboard widget with adblock counter included!
 * Version: 1.4
 * Author: Brice CAPOBIANCO
 * Author URI: b-website.com
 * Text Domain: an-translate
 * Domain Path: /languages
 */

/***************************************************************
 * SECURITY : Exit if accessed directly
 ***************************************************************/
if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if (!defined('ABSPATH')) {
    exit;
}


/***************************************************************
 * Define constants
 ***************************************************************/
if (!defined('AN_PATH')) {
    define('AN_PATH', plugin_dir_path(__FILE__));
}
if (!defined('AN_URL')) {
    define('AN_URL', plugin_dir_url(__FILE__));
}
if (!defined('AN_BASE')) {
    define('AN_BASE', plugin_basename(__FILE__));
}
if (!defined('AN_NAME')) {
    define('AN_NAME', 'Adblock Notify');
}
if (!defined('AN_ID')) {
    define('AN_ID', 'adblock-notify');
}
if (!defined('AN_COOKIE')) {
    define('AN_COOKIE', 'anCookie');
}


/***************************************************************
 * Set priority to properly load plugin translation
 ***************************************************************/
function an_translate_load_textdomain() {
    $path = basename(dirname(__FILE__)) . '/languages/';
    load_plugin_textdomain('an-translate', false, $path);
}

add_action('plugins_loaded', 'an_translate_load_textdomain', 1);


/***************************************************************
 * Load plugin files
 ***************************************************************/
require_once( AN_PATH . 'lib/titan-framework/titan-framework-embedder.php' );

$anFiles = array('options', 'functions', 'widget', 'files');
foreach ($anFiles as $anFile) {
    require_once( AN_PATH . 'adblock-notify-' . $anFile . '.php' );
}


/***************************************************************
 * Front-End Scripts & Styles enqueueing
 ***************************************************************/

function an_enqueue_an_sripts() {
    if (!is_admin()) {

        $anScripts = unserialize(get_option('adblocker_notify_selectors'));
        $an_option = TitanFramework::getInstance('adblocker_notify');

        wp_enqueue_script('an_advertisement', AN_URL . 'js/advertisement.js', array('jquery'), NULL, true);

        if ( $an_option->getOption('an_option_selectors') == false ) {

            wp_register_script('an_scripts', AN_URL . 'js/an-scripts.min.js', array('jquery'), NULL, true);
            wp_register_style('an_style', AN_URL . 'css/an-style.min.css', array(), NULL, NULL);
       
        } else if ($anScripts['temp-path'] != false) {

			//check if server is SSL
			if ( is_ssl() )
			$anScripts['temp-url'] = preg_replace("/^http:/i", "https:", $anScripts['temp-url']);
			
            wp_register_script('an_scripts', $anScripts['temp-url'] . $anScripts['files']['js'], array('jquery'), NULL, true);
            wp_register_style('an_style', $anScripts['temp-url'] . $anScripts['files']['css'], array(), NULL, NULL);
       
        }

        if ( $anScripts['temp-path'] == false && $an_option->getOption('an_option_selectors') == true ) {

			//Print Style and script in the footer with an_prepare (functions.php)
            //CSS file does not exist anymore
            wp_dequeue_style('tf-compiled-options-adblocker_notify');
       
        } 

		wp_enqueue_script('an_scripts');
		wp_enqueue_style('an_style');

		//AJAX
		wp_localize_script('an_scripts', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
	
		//CSS file does not exist anymore
		if( $an_option->getOption('an_option_selectors') == true ){
			wp_dequeue_style('tf-compiled-options-adblocker_notify');
		}

    }
}
add_action('wp_enqueue_scripts', 'an_enqueue_an_sripts', 100);


/***************************************************************
 * Back-End Scripts & Styles enqueueing
 ***************************************************************/

function an_register_admin_scripts() {
    //JS
    wp_enqueue_script('an_admin_scripts', AN_URL . 'js/an-admin-scripts.js', array('jquery'), NULL, true);
    //CSS
    wp_enqueue_style('an_admin_style', AN_URL . 'css/an-admin-style.css', array(), NULL, NULL);
}

function an_enqueue_admin_scripts() {
    $screen = get_current_screen();
    if ($screen->id != 'toplevel_page_' . AN_ID)
        return;

    an_register_admin_scripts();
}

add_action('admin_enqueue_scripts', 'an_enqueue_admin_scripts');
add_filter('user_contactmethods', 'user_contactmethods_example');


/***************************************************************
 * Add settings link on plugin list page
 ***************************************************************/
function an_settings_link($links) {
    $links[] = '<a href="options-general.php?page=' . AN_ID . '">' . __('Settings', 'an-translate') . '</a>';
    return $links;
}

add_filter('plugin_action_links_' . AN_BASE, 'an_settings_link');


/***************************************************************
 * Add custom meta link on plugin list page
 ***************************************************************/
function an_meta_links($links, $file) {
	if (strpos($file, 'adblock-notify.php') !== false) {
		$links[0] = '<a href="http://b-website.com/" target="_blank"><img src="' . AN_URL . 'img/icon-bweb.svg" style="margin-bottom: -4px; width: 18px;" alt="b*web"/></a>&nbsp;&nbsp;' . $links[0];
		$links[] = '<a href="http://b-website.com/category/plugins" target="_blank" title="' . __('More b*web Plugins', 'an-translate') . '">' . __('More b*web Plugins', 'an-translate') . '</a>';
		$links[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7Z6YVM63739Y8" target="_blank" title="' . __('Donate', 'an-translate') . '"><strong>' . __('Donate', 'an-translate') . '</strong></a>';
	}
	return $links;
}

add_filter('plugin_row_meta', 'an_meta_links', 10, 2);


/***************************************************************
 * Admin Panel Favico
 ***************************************************************/
function an_add_favicon() {
    $screen = get_current_screen();
    if ($screen->id != 'toplevel_page_' . AN_ID)
        return;

    $favicon_url = AN_URL . 'img/icon-bweb.svg';
    echo '<link rel="shortcut icon" href="' . $favicon_url . '" />';
}

add_action('admin_head', 'an_add_favicon');


/***************************************************************
 * Create random selectors and files on plugin activation
 ***************************************************************/
function adblocker_notify_activate() {
    add_action('tf_create_options', 'an_create_options');
    an_save_setting_random_selectors();
}

if (function_exists('adblocker_notify_activate')) {
    register_activation_hook(__FILE__, 'adblocker_notify_activate');
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
	
	if( file_exists( $dirPath ) ) {
  
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
		
}

//Uninstall function
function adblocker_notify_uninstall() {
   
    // Remove temp files
    $anTempDir = unserialize(get_option('adblocker_notify_selectors'));
    an_delete_temp_folder($anTempDir['temp-path']);
   
    //Remove TitanFramework Generated Style
    $uploadDir = wp_upload_dir();
    $TfCssFile = trailingslashit( $uploadDir['basedir']  ) . 'titan-framework-adblocker_notify-css.css';
	
	if( file_exists( $TfCssFile ) )
    	unlink( $TfCssFile );
		
    // Remove option from DB
    delete_option('adblocker_notify_options');
    delete_option('adblocker_notify_counter');
    delete_option('adblocker_notify_selectors');
	
}