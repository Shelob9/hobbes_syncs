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

	protected $post_id;

	protected $post;

	public $is_good;

	function __construct( $post_id ) {
		$this->post_id = $post_id;
		$post = get_post( $post_id, OBJECT );
		if ( $post  ) {
			$this->post = (object) $post;
			$this->check_post_type();
		}else {
			$this->is_good = false;
		}



	}

	protected function check_post_type() {
		if ( ! $this->post  ) {
			return;

		}

		$allowed = $this->active_post_types();
		$allowed = array_flip( $allowed );
		$post_type = $this->post->post_type;
		if ( in_array( $post_type, $allowed ) ) {
			$this->is_good = true;
		}

	}


	protected function active_post_types() {
		return \Hobbes_Syncs_Options::get( 'post_type' );

	}
} 
