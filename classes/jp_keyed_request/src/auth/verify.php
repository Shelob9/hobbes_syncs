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

namespace jp_keyed_request\auth;


class verify extends keys {
	/**
	 * Allows access to the needed end points if key auth matches
	 *
	 * @since 0.0.1
	 *
	 * @return bool|WP_Error
	 */
	public static function allow_access() {
		if ( true == self::check_auth() ) {
			return true;
		}
		else{
			$info = self::check_auth();
			return new \WP_Error( 'jp-keyed-request-key-auth-fail', __( 'Key auth failed.', "jp-keyed-request" ), $info );

		}

	}


	/**
	 * Checks if key auth is legit
	 *
	 * @since 0.0.1
	 *
	 * @return bool
	 */
	public static function check_auth() {

		$token = self::get_request_token();
		$public  = self::get_request_key();
 		if ( $public && $token ) {

			$private = pods_v( 'private', get::get_keys( false ) );

			if ( $private && generate::generate_token( $public, $private ) === $token ) {

				return true;

			}else{
				pods_error( $public );
				$p = get::get_keys();
				$p= $p[ 'public' ];
				pods_error( var_dump( array( 'gotPub'=> $public, 'pubSHOULDBE' => $p ) ));
				pods_error( var_dump( array('keys' => get::get_keys() , 'pr' => $private, 'public' => $public, 'tokien' => $token, 'genToken' => generate::generate_token( $public, $private ) )) );
			}

		}


	}

	/**
	 * Gets token from current request
	 *
	 * @since 0.0.1
	 *
	 * @return string
	 */
	private static function get_request_token() {

		if ( ! is_null( $token = pods_v_sanitized( self::$request_token, 'get' ) ) ) {

			return urldecode( $token  );

		}

	}

	/**
	 * Gets public key from current request
	 *
	 * @since 0.0.1
	 *
	 * @return string
	 */
	private static function get_request_key() {

		if ( ! is_null( $key = pods_v_sanitized( self::$request_key, 'get' ) ) ) {

			return urldecode( $key );

		}

	}


}
