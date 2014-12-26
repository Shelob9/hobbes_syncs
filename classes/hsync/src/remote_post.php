<?php
/**
 * @TODO What this does.
 *
 * @package   @TODO
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2014 Josh Pollock
 */
namespace hsync;

class remote_post {

	protected  $can_send_class;
	public     $post_id;

	function __construct( $post_id ) {
		$this->post_id = $post_id;
		$this->can_send_class = new is_post_to_sync( $post_id );
		if ( $this->can_send_class->is_good ) {
			include_once( HSYNCC_PATH . 'vendor/webdevstudios/wds-wp-json-api-connect/wds-wp-json-api-connect.php');
			$this->run();
		}

	}

	public function run() {
		if ( ! $this->can_send_class->is_good ) {
			return;
		}

		$data = $this->get_post_json();

		if ( is_wp_error( $data ) ) {
			return $data;
		}

		$sites = $this->get_remote_sites();
		if ( ! empty( $sites ) ) {
			foreach( $sites as $site ) {
				$this->send( $site, $data );
			}

		}

	}

	protected function can_send() {
		$class = $this->can_send_class;
		return $class->is_good;

	}

	/**
	 * @return object
	 */
	protected function get_post_json() {
		$id = $this->post_id;
		$post = get_post( $id );
		$post = (array) $post;
		$convert_keys = array(
			'title' => 'post_title',
			'content' => 'post_content',
			'slug' => 'post_name',
			'status' => 'post_status',
			'parent' => 'post_parent',
			'excerpt' => 'post_excerpt',
			'date' => 'post_date',
			'type' => 'post_type',
		);

		foreach( $convert_keys as $api_wants => $post_has ) {
			$value = $post[ $post_has ];
			unset($post[ $post_has ] );
			$post[ $api_wants ] = $value;
		}

		$post = json_encode( $post );

		return $post;

	}

	public function send( $site_info, $data ) {
		$consumer = array(
			'consumer_key'    => $site_info[ 'consumer_key'],
			'consumer_secret' => $site_info[ 'consumer_secret' ],
			'json_url'        => $site_info[ 'json_url' ],
		);


		$api = new \WDS_WP_JSON_API_Connect( $consumer );
		$auth_url = $api->get_authorization_url();
		die( $auth_url );
		if ( $auth_url ) {
			echo '<div id="message" class="updated">';
			echo '<p><a href="'. esc_url( $auth_url ) .'" class="button">Authorize Connection</a></p>';
			echo '</div>';

			// Do not proceed
			return;
		}

		$response = $api->auth_post_request( 'posts/'. $this->post_id, $data );

		if ( is_wp_error( $response ) ) {

			echo '<div id="message" class="error">';
			echo wpautop( $response->get_error_message() );
			echo '</div>';

		} else {

			echo '<div id="message" class="updated">';
			echo '<p><strong>Post updated!</strong></p>';
			echo '<xmp>auth_post_request $response: '. print_r( $response, true ) .'</xmp>';
			echo '</div>';

		}



	}



	protected function get_remote_sites() {
		$value = \Hobbes_Syncs_Options::get( 'remote_sites', array() );
		$sites = array();

		foreach( $value as $node ) {
			$sites[] = $node;
		}

		return $sites;

	}



} 
