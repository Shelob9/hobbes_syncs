<?php


class Settings_Hobbes_Syncs extends Hobbes_Syncs{


	/**
	 * Start up
	 */
	public function __construct(){

		// add admin page
		add_action( 'admin_menu', array( $this, 'add_settings_pages' ), 25 );
		// save config
		add_action( 'wp_ajax_hsyncc_save_config', array( $this, 'save_config') );
		
	}

	/**
	 * saves a config
	 */
	public function save_config(){

		if( empty( $_POST['hobbes-syncs-setup'] ) || !wp_verify_nonce( $_POST['hobbes-syncs-setup'], 'hobbes-syncs' ) ){
			if( empty( $_POST['config'] ) ){
				return;
			}
		}

		if( !empty( $_POST['hobbes-syncs-setup'] ) && empty( $_POST['config'] ) ){
			$config = stripslashes_deep( $_POST );
			$config = $this->add_sanitization_and_validation( $config );
			update_option( '_hobbes_syncs', $config );
			wp_redirect( '?page=hobbes_syncs&updated=true' );
			exit;
		}

		if( !empty( $_POST['config'] ) ){
			$config = json_decode( stripslashes_deep( $_POST['config'] ), true );
			$config = $this->add_sanitization_and_validation( $config );
			if(	wp_verify_nonce( $config['hobbes-syncs-setup'], 'hobbes-syncs' ) ){
				update_option( '_hobbes_syncs', $config );
				wp_send_json_success( $config );
			}
		}


		// nope
		wp_send_json_error( $config );

	}

	/**
	 * Adds the filter for sanization and/ or validation of each setting when saving.
	 *
	 * @param array $config Data being saved
	 *
	 * @return array
	 */
	protected function add_sanitization_and_validation( $config ) {
		foreach( $config as $setting => $value ) {
			if ( ! in_array( $setting, $this->internal_config_fields() ) ) {
				include_once( dirname( __FILE__ ) . '/sanatize.php' );
				$filtered = Settings_Hobbes_Syncs_Sanitize::apply_sanitization_and_validation( $setting, $value, $config );
				$config = $filtered;
			}

		}

		return $config;

	}

	/**
	 * Array of "internal" fields not to mess with
	 *
	 * @return array
	 */
	protected function internal_config_fields() {
		return array( 'hobbes-syncs-setup', '_wp_http_referer', 'id', '_current_tab' );
	}

	

	/**
	 * Add options page
	 */
	public function add_settings_pages(){
		// This page will be under "Settings"
		
	
			$this->plugin_screen_hook_suffix['hobbes_syncs'] =  add_submenu_page( 'options-general.php', __( 'Hobbes Syncs', $this->plugin_slug ), __( 'Hobbes Syncs', $this->plugin_slug ), 'manage_options', 'hobbes_syncs', array( $this, 'create_admin_page' ) );
			add_action( 'admin_print_styles-' . $this->plugin_screen_hook_suffix['hobbes_syncs'], array( $this, 'enqueue_admin_stylescripts' ) );


	}


	/**
	 * Options page callback
	 */
	public function create_admin_page(){
		// Set class property        
		$screen = get_current_screen();
		$base = array_search($screen->id, $this->plugin_screen_hook_suffix);
			
		// include main template
		include HSYNCC_PATH .'includes/edit.php';

		// php based script include
		if( file_exists( HSYNCC_PATH .'assets/js/inline-scripts.php' ) ){
			echo "<script type=\"text/javascript\">\r\n";
				include HSYNCC_PATH .'assets/js/inline-scripts.php';
			echo "</script>\r\n";
		}

	}


}

if( is_admin() )
	$settings_hobbes_syncs = new Settings_Hobbes_Syncs();
