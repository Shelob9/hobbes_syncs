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

namespace jp_keyed_request\cye_save;

use jp_keyed_request\auth\keys;


class filters extends keys {
	function __construct() {

		//add_filter( 'hobbes_syncs_remote_sites_public_key', array( $this, 'remote_public' ) );
		//add_filter( 'hobbes_syncs_remote_sites_private_key', array( $this, 'remote_private' ) );
		add_filter( 'hobbes_syncs_remote_sites', array( $this, 'verify' ) );
	}

	/**
	 * Verify and save to regular options
	 *
	 * @since 0.0.1
	 *
	 * @param $values
	 *
	 * @return mixed
	 */
	function verify( $values ) {
		foreach( $values as $value ) {
			$v = self::remote_public( $value [ 'public_key' ] );
			$values[ $value ][ 'public_key' ] = $v;
			$v = self::remote_private( $value [ 'private_key' ] );
			$values[ $value ][ 'private_key' ] = $v;
		}

		return $values;
	}


	/**
	 * Validate and save to the regular option the private key
	 *
	 * @since 0.0.1
	 *
	 * @param $value
	 */
	static function remote_private( $value ) {
		if ( ctype_alnum( $value )  ) {
			$option = self::$private_key_option_name;
			update_option( $option, $value );
			return $value;

		}

	}


	/**
	 * Validate and save to the regular option the public key
	 *
	 * @since 0.0.1
	 *
	 * @param $value
	 */
	static function remote_public( $value ) {
		if ( ctype_alnum( $value )) {
			$option = self::$public_key_option_name;
			update_option( $option, $value );
			return $value;

		}

	}






}


