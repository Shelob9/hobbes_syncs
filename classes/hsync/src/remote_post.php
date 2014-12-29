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
	protected  $post_type;

	function __construct( $post_id ) {
		$this->post_id = $post_id;
		$this->can_send_class = new is_post_to_sync( $post_id );
		if ( $this->can_send_class->is_good ) {
			$this->run();
		}

	}

	public function run() {
		if ( ! $this->can_send_class->is_good ) {
			return;
		}

		$data = $this->get_post_json();
		if ( is_null( $data ) ) {
			return;
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
		$post_type = get_post_type( $id );
		$this->post_type = $post_type;
		$pods = pods( $post_type, $id, true );
		if ( is_object( $pods ) ) {
			return wp_json_encode( $pods->export() );
		}

	}

	public function send( $site_info, $data ) {
		$url = trailingslashit( $site_info[ 'json_url' ] ). $this->post_type .'/pods/'.$this->post_id;
		$r =jp_keyed_request_make( $url, $data );
		$v= 1;

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
