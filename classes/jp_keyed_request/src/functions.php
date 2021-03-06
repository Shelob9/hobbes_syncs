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
	if ( function_exists( 'pods_v_sanitized' ) && ! is_null( pods_v_sanitized( \jp_keyed_request\auth\keys::$request_key ) ) ) {
		$removed = remove_action( 'save_post', 'hsync_make_it_so', 25 );
		if ( $removed ) {
			$jp_keyed_request_auth = jp_keyed_request\auth\verify::allow_access();

			if ( ! is_wp_error( $jp_keyed_request_auth ) && $jp_keyed_request_auth ) {
				add_filter( 'pods_json_api_access_pods_save_item', '__return_true' );
				add_filter( 'pods_json_api_access_pods_add_item', '__return_true' );
			}

			//run on post save the update featured
			if ( pods_v_sanitized( 'feature_set' ) ) {
				add_action( 'pods_api_post_save_pod_item', function( $p, $n, $id ) {
					\hsync\set_featured::add_image( pods_v_sanitized( 'feature_set' ), $id );

				}, 10, 3 );

			}

			//sync image fields
			if ( pods_v_sanitized( 'requesting_url' ) && pods_v_sanitized( 'requesting_id' ) ) {
				add_action( 'pods_api_post_save_pod_item', function ( $p, $n, $id ) {
					$pod_name     = $p['name'];
					$pods_to_sync = \Hobbes_Syncs_Options::get( 'post_type' );
					if ( in_array( $pod_name, $pods_to_sync ) ) {
						new hsync\image_sync(
							pods( $pod_name, $id ),
							pods_v_sanitized( 'requesting_url' ),
							$pod_name,
							pods_v_sanitized( 'requesting_id' ),
							$id
						);

					}

				}, 10, 3 );

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
 * @param array|object $data Body of request. Must be an object or array. If is already JSON encoded failure will occur.
 * @param string $method HTTP method
 *
 * @return array|WP_Error
 */
function jp_keyed_request_make( $url, $data, $method = 'POST' ) {
	$make = new jp_keyed_request\request( $url, $data, $method );

	return $make->make();
}

