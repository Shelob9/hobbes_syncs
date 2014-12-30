<?php
/**
 * Functions for this lib
 *
 * @package   @jp_keyed_access
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2014 Josh Pollock
 */

/**
 * Set access to pods API if request has right key/token
 *
 * @since 0.0.1
 */
add_action( 'init', function() {
	if ( ! is_null( pods_v_sanitized( \jp_keyed_request\auth\keys::$request_key ) ) ) {
		$removed = remove_action( 'save_post', 'hsync_make_it_so', 25 );
		if ( $removed ) {
			$jp_keyed_request_auth = jp_keyed_request\auth\verify::allow_access();

			if ( ! is_wp_error( $jp_keyed_request_auth ) && $jp_keyed_request_auth ) {
				add_filter( 'pods_json_api_access_pods_save_item', '__return_true' );
				add_filter( 'pods_json_api_access_pods_add_item', '__return_true' );
			}

		} else {
			pods_error( __FILE__ );
		}

	}

});

/**
 * Make a request with key args
 *
 * @since 0.0.1
 *
 * @param string $url Url to make request
 * @param array|object|JSON $data Body of request
 * @param string $method HTTP method
 *
 * @return array|WP_Error
 */
function jp_keyed_request_make( $url, $data, $method = 'POST' ) {
	$make = new jp_keyed_request\request( $url, $data, $method );

	return $make->make();
}

