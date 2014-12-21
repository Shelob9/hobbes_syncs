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