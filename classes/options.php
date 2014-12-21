<?php
/**
 * Hobbes Syncs Options.
 *
 * @package   Hobbes_Syncs
 * @author    Josh Pollock
 * @license   GPL-2.0+
 * @link
 * @copyright 2014 Josh Pollock
 */

/**
 * Plugin class.
 * @package Hobbes_Syncs
 * @author  Josh Pollock
 */
class Hobbes_Syncs_Options {


	/**
	 * Get an option from this plugin.
	 *
	 * @param string $option The name of a specific option to get.
	 * @param mixed $default Optional. Default to return if no value found. Default is false.
	 *
	 * @return string|null|array Returns the option or null if it doesn't exist
	 */
	public static function get ( $option, $default = false ) {
		$option = self::get_options( $option );
		if ( is_array( $option ) && empty( $option ) ) {
			return null;

		}

		if ( is_null( $option ) ) {
			return $default;

		}

		return $option;

	}

	/**
	 * Get all option from this plugin.
	 *
	 * @return null|array Returns the options or null if none are set
	 */
	public static function get_all () {
		return self::get_options( null );

	}

	/**
	 * Get an option or all option from this plugin
	 *
	 * @access private
	 *
	 * @param null|string $option Optional. If null, the default, all options for this plugin are returned. Provide the name of a specific option to get just that one.
	 *
	 * @return array|null|string
	 */
	private static function get_options( $option = null ) {
		$options = get_option( "_hobbes_syncs", array() );
		if ( empty( $options ) ) {
			return $options;

		}

		if ( ! is_null( $option ) ) {
			if ( isset( $options[ $option ] ) ) {
				return $options[ $option ];
			}
			else {
				return null;

			}

		}

		return $options;

	}

	/**
	 * Get a "node"--a field stored as an array, or one key from said array
	 *
	 * @param string $node The node to get
	 * @param bool|string $key Optional. The specific key of array to get. If false, the default, full array is returned.
	 *
	 * @return array|null|string The value, or null if it couldn't be found.
	 */
	public static function get_node( $node, $key = false ) {
		$value = self::get( $node );
		if ( $key && isset( $value[ $key ] ) ) {
			$value = $value[ $key ];
		}elseif ( $key && ! isset( $value[ $key ] ) ) {
			return null;

		}

		return $value;

	}

}

