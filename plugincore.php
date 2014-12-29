<?php
/**
 * @package   Hobbes_Syncs
 * @author    Josh Pollock
 * @license   GPL-2.0+
 * @link      
 * @copyright 2014 Josh Pollock
 *
 * @wordpress-plugin
 * Plugin Name: Hobbes Syncs
 * Plugin URI:  
 * Description: Syncs content in a custom post type between sites.
 * Version:     0.0.1
 * Author:      Josh Pollock
 * Author URI:  http://JoshPress.net
 * Text Domain: hobbes-syncs
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define('HSYNCC_PATH',  plugin_dir_path( __FILE__ ) );
define('HSYNCC_URL',  plugin_dir_url( __FILE__ ) );
define('HSYNCC_VER',  '0.0.1' );

//autoload dependencies
if ( file_exists( HSYNCC_PATH . 'vendor/autoload.php' ) )
require_once( HSYNCC_PATH . 'vendor/autoload.php' );

// load internals
require_once( HSYNCC_PATH . '/classes/hobbes-syncs.php' );
require_once( HSYNCC_PATH . '/classes/options.php' );
require_once( HSYNCC_PATH . 'includes/settings.php' );

// Load instance
add_action( 'plugins_loaded', array( 'Hobbes_Syncs', 'get_instance' ) );


add_action( 'plugins_loaded',
	function() {
		$class_dir = HSYNCC_PATH.'classes';
		include( $class_dir . '/jp_auto_loader/src/jp_auto_loader.php' );
		$libraries = array(
			'jp_keyed_request' => false,
			'hsync' => false,
			'jp_keyed_request_cye_save' => 'jp_keyed_request\cye_save'
		);


		$classLoader = new JP_Auto_Loader();
		foreach ( $libraries as $lib => $namespace ) {
			$root = $class_dir . '/'. $lib .'/src';

			if ( ! $namespace ) {
				$namespace = $lib;
			}

			$classLoader->addNamespace( $namespace, untrailingslashit( $root ) );
			if ( file_exists( $root . '/functions.php' ) ) {
				include( $root . '/functions.php' );
			}

		}

		$classLoader->register();

	}, 3

);

/**
 * Process the generate local keys requests in admin.
 */
add_action( 'wp_ajax_hsync_generate_keys', 'hsync_generate_keys_ajax_cb' );
function hsync_generate_keys_ajax_cb() {
	if ( function_exists('pods_v_sanitized') && wp_verify_nonce( pods_v_sanitized( 'nonce' , 'post'),  'hobbes-syncs') ) {
		\jp_keyed_request\auth\generate::generate_keys();
		wp_send_json_success();
	}

}

/**
 * Deactivate plugin on load if dependencies do not check out
 *
 * @since 0.0.1
 */
add_action( 'plugins_loaded', 'hsyncs_check_dependencies', 1 );
function hsyncs_check_dependencies() {
	$version = false;
	if ( version_compare(PHP_VERSION, '5.3.0') >= 0 ) {
		$version = true;
	}
	
	if ( ! $version || ! defined( 'PODS_VERSION' ) || ! defined( 'PODS_JSON_API_VERSION' ) || ! function_exists( 'json_url' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_redirect( admin_url() );
	}
}
