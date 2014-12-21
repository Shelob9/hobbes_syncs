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


	public $post_id;

	function __construct( $post_id ) {
		$this->post_id = $post_id;
	}

	/**
	 * @return object
	 */
	protected function prepare_data() {

		return (object) array();
	}

	protected function request() {

	}
} 
