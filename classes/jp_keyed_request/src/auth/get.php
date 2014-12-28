<?php
/**
 * Get the keys for making/verifying requests
 *
 * @package jp_keyed_request\auth
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2014 Josh Pollock
 */

namespace jp_keyed_request\auth;

/**
 * Class get
 * @package jp_keyed_request\auth
 */
class get extends keys {
	/**
	 * Add <strong>public key</strong> and token to url string
	 *
	 * @param $key
	 * @param $token
	 * @param $url
	 *
	 * @since 0.3.0
	 *
	 * @return string
	 */
	public static function add_to_url( $key, $token, $url ) {
		$args = array(
			'jp_keyed_access_key' => urlencode( $key ),
			'jp_keyed_access_token' => urlencode( $token ),
		);

		return add_query_arg( $args, $url );

	}

	/**
	 * Get public/private keys
	 *
	 * @param bool $remote Optional. If true, the default, gets saved keys for remote site to deploy to. If false get stored keys to use for deploying <em>to this site</em>.
	 *
	 * @since 0.0.3
	 *
	 * @return array
	 */
	public static function get_keys( $remote = true ) {
		if ( $remote ) {
			return array(
				'public' => get_option( self::$public_key_option_name ),
				'private' => get_option( self::$private_key_option_name ),
			);
		}

		return array(
			'public' => get_option( self::$public_key_option_name . '_local' ),
			'private' => get_option( self::$private_key_option_name . '_local' ),
		);


	}


}
