<?php
namespace jp_keyed_request;



use jp_keyed_request\auth\generate;
use jp_keyed_request\auth\get;
use jp_keyed_request\auth\keys;

class auth extends keys {

	/**
	 * Add <strong>public key</strong> and token to url string
	 *
	 * @param $key
	 * @param $token
	 * @param $url
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public static function add_to_url( $key, $token, $url ) {
		$args = array(
			self::$request_key   => urlencode( $key ),
			self::$request_token => urlencode( $token ),
		);

		return add_query_arg( $args, $url );

	}

	/**
	 * Return a keyed URL
	 *
	 *
	 * @since 0.0.1
	 *
	 * @param $url
	 *
	 * @return string
	 */
	public static function keyed_url( $url ) {
		$keys = self::keys();
		$public = pods_v( 'public', $keys );
		$private = pods_v( 'private', $keys );
		$token = generate::generate_token( $public, $private );
		$url = self::add_to_url( $public, $token, $url );

		return $url;

	}

	/**
	 * Get the keys we need for this
	 *
	 * @since 0.0.1
	 *
	 * @return array
	 */
	protected static function keys() {
		$keys = get::get_keys();
		$public = pods_v( 'public', $keys );
		$private = pods_v( 'private', $keys );
		if ( $public && $private ) {
			return $keys;
		}else{
			generate::generate_keys();
			return self::keys();
		}

	}

}
