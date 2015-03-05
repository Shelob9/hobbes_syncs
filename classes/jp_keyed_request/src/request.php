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
namespace jp_keyed_request;

class request {
	/**
	 * The URL for request. Will get the auth key/token added
	 *
	 * @since 0.0.1
	 *
	 * @var string
	 */
	public $url;

	/**
	 * Transport method for request
	 *
	 * @since 0.0.1
	 *
	 * @var string
	 */
	public $method;

	/**
	 * The data for the request. Will get json_encoded if it does not enter as string
	 *
	 * @var string
	 */
	public $data;

	/**
	 * Constructor for class
	 *
	 * @param string $url Url to make request
	 * @param array|object|JSON $data Body of request
	 * @param string $method HTTP method
	 */
	function __construct( $url, $data, $method = 'POST' ) {

		$url = $this->prepare_url( $url );
		$data = $this->prepare_body( $data );

		if ( is_string( $data ) && is_string( $url ) ) {
			$this->data = $data;
			$this->url = $url;
			$this->method = $method;
		}else{
			return;
		}

	}

	/**
	 * Make the request
	 *
	 *
	 * @since 0.0.1
	 *
	 * @return array|\WP_Error
	 */
	function make() {

		$body = $this->data;
		$args = array(  'body' => $body, 'timeout' => 60 );
		$r = wp_remote_post( $this->url, $args, $this->method );

		return $r;

	}

	/**
	 * Prepare URL
	 *
	 * @since 0.0.1
	 *
	 * @param $url
	 *
	 * @return string
	 */
	protected function prepare_url( $url ) {
		$url = auth::keyed_url( $url );
		if (is_string( $url ) ) {
			return $url;
		}

	}

	/**
	 * Prepare/encode body of request
	 *
	 * @param object|array $data Body of request
	 *
	 * @return bool|string
	 */
	protected function prepare_body( $data ) {
		if ( is_array( $data ) || is_object( $data ) ) {
			$data = wp_json_encode( $data );
		}

		if ( is_object( json_decode( $data ) ) ) {
			return $data;
		}

	}

	/**
	 * USE?
	 *
	 * @return bool|string
	 */
	protected function timestamp() {
		return date('Y-m-d\TH:i:s.Z\Z', time());
	}


	/**
	 * USE?
	 *
	 * @return mixed
	 */
	protected function generate_uri() {
		$parsed = parse_url( $this->url );
		if ( isset( $parsed[ 'path']) ) {
			return $parsed['path' ];
		}

	}


}
