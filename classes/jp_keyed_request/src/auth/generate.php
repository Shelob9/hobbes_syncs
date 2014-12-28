<?php
/**
 * Generate auth keys
 *
 * @package jp_keyed_request\auth
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2014 Josh Pollock
 */

namespace jp_keyed_request\auth;

/**
 * Class generate
 * @package jp_keyed_request\auth
 */
class generate extends keys {

	/**
	 * Generate public/private keys
	 *
	 * @since 0.0.1
	 */
	public static function generate_keys() {

		update_option( self::$public_key_option_name, self::generate_public_key() );
		update_option( self::$private_key_option_name, self::generate_private_key() );

	}

	/**
	 * Save keys for authorizing this site
	 *
	 * @param string $public
	 * @param string $private
	 *
	 * @since 0.0.1
	 */
	public static function save_local_keys( $public, $private ) {

		update_option( self::$public_key_option_name . '_local', $public );
		update_option( self::$private_key_option_name . '_local', $private );

	}

	/**
	 * Clear stored local keys
	 *
	 * @since 0.4.0
	 */
	public static function clear_local_keys() {

		delete_option( self::$public_key_option_name . '_local' );
		delete_option( self::$private_key_option_name . '_local' );

	}

	/**
	 * Generates a public key
	 *
	 * @since 0.0.1
	 *
	 * @return string
	 */
	private static function generate_public_key() {
		$auth_key = defined( 'AUTH_KEY' ) ? AUTH_KEY : '';
		$public   = hash( 'md5', self::random_string() . $auth_key . date( 'U' ) );


		return $public;

	}

	/**
	 * Generates a public key
	 *
	 * @since 0.0.1
	 *
	 * @return string
	 */
	private static function generate_private_key( ) {
		$auth_key = defined( 'AUTH_KEY' ) ? AUTH_KEY : '12345';
		$secret   = hash( 'md5', get_current_user_id() . $auth_key . date( 'U' ) );

		return $secret;

	}

	/**
	 * Generates a private key
	 *
	 * @since 0.0.1
	 *
	 * @return string
	 */
	public static function generate_token( $public, $private ) {
		$token = md5( $private.$public );
		return $token;

	}

	/**
	 * Create a random string
	 *
	 * @since 0.0.1
	 *
	 * @return string
	 */
	private static function random_string() {
		return substr( str_shuffle( '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' ), 0, 42);

	}


}
