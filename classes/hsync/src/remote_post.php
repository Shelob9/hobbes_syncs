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
	protected   $post;

	function __construct( $post_id, $post ) {
		$this->post_id = $post_id;
		$this->can_send_class = new is_post_to_sync( $post_id, $post );
		if ( $this->can_send_class->is_good ) {
			$this->post = $post;
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

		return $this->post_id;

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
			return $pods->export();
		}

	}

	public function send( $site_info, $data ) {
		$root_url = $site_info[ 'json_url' ];

		if ( $root_url  ) {
			$url = trailingslashit( $root_url ).'pods/' . $this->post_type;
			$id = $this->find_id( $data, $root_url );

			if ( $id ) {
				$url = $url.'/'.$id;
			}


		}



		$r = jp_keyed_request_make( $url, json_encode( $data  ) );
		pods_error( var_Dump( $r ) );

	}

	public function find_id( $data, $root_url ) {
		$post_name = pods_v( 'post_name', $data );
		if ( $post_name ) {
			$id = find_remote_id::find( $root_url, $this->post_type, $post_name );

			if ( intval( $id ) > 0 ) {
				return $id;

			}
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
