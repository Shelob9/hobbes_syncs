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
class is_post_to_sync {

	public $post_id;

	public $post;

	function __construct( $post_id ) {
		$this->post_id = $post_id;
		$post = get_post( $post_id );
		if ( is_object( $post_id ) ) {
			$this->post = $post;
			if ( $this->check_post_type() ) {
				return true;

			}
		}

		return false;

	}

	protected function check_post_type() {
		if ( in_array( $this->post->post_type, $this->active_post_types() ) ) {
			return true;
		}

	}

	private function all_post_types() {
		$post_types = get_post_types( array( 'public' => true ), 'objects' );
		return $post_types;

	}

	private function active_post_types() {
		return array();
	}
} 
