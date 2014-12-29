<?php
/**
 * Hobbes Syncs.
 *
 * @package   Hobbes_Syncs
 * @author    Josh Pollock
 * @license   GPL-2.0+
 * @link      
 * @copyright 2014 Josh Pollock
 */

/**
 * Plugin class.
 * @package Hobbes_Syncs
 * @author  Josh Pollock
 */
class Hobbes_Syncs {

	/**
	 * @var      string
	 */
	protected $plugin_slug = 'hobbes-syncs';
	/**
	 * @var      object
	 */
	protected static $instance = null;
	/**
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;
	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_stylescripts' ) );

		//do on save post
		add_action( 'save_post', array( $this, 'make_it_so' ), 25, 2 );

		//add sanitzation/validation filters
		add_action( 'init', array( $this, 'filters' ) );

	}

	public function make_it_so( $id, $post ) {
		remove_action( 'save_post', array( $this, 'make_it_so' ), 25 );
		$class = new \hsync\remote_post( $id, $post );
		add_action( 'all_admin_notices', array( $class, 'run') );
	}

	public function filters() {
		new \jp_keyed_request\cye_save\filters();
	}


	/**
	 * Return an instance of this class.
	 *
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain( $this->plugin_slug, FALSE, basename( HSYNCC_PATH ) . '/languages');

	}
	
	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 *
	 * @return    null
	 */
	public function enqueue_admin_stylescripts() {

		$screen = get_current_screen();

		
		if( false !== strpos( $screen->base, 'hobbes_syncs' ) ){

			wp_enqueue_style( 'hobbes_syncs-core-style', HSYNCC_URL . '/assets/css/styles.css' );
			wp_enqueue_style( 'hobbes_syncs-baldrick-modals', HSYNCC_URL . '/assets/css/modals.css' );
			wp_enqueue_script( 'hobbes_syncs-wp-baldrick', HSYNCC_URL . '/assets/js/wp-baldrick-full.js', array( 'jquery' ) , false, true );
			wp_enqueue_script( 'jquery-ui-autocomplete' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_style( 'hobbes_syncs-codemirror-style', HSYNCC_URL . '/assets/css/codemirror.css' );
			wp_enqueue_script( 'hobbes_syncs-codemirror-script', HSYNCC_URL . '/assets/js/codemirror.js', array( 'jquery' ) , false );
			wp_enqueue_script( 'hobbes_syncs-core-script', HSYNCC_URL . '/assets/js/scripts.js', array( 'hobbes_syncs-wp-baldrick' ) , false );
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );			
		
		}


	}


}















