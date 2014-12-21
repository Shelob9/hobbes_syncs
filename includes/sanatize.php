<?php
/**
 * Handles sanitizion of settings
 *
 * @package   Hobbes_Syncs
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link
 * @copyright 2014 Josh Pollock
 */

class Settings_Hobbes_Syncs_Sanitize {

	/**
	 * Prepares for application of the sanization and/ or validation filter based on setting type
	 *
	 * @access protected
	 *
	 * @param string $setting The name of the setting being saved.
	 * @param mixed $value The value being saved
	 * @param array $config Data being saved
	 *
	 * @return array The configuration with a specific setting sanatized.
	 */
	public static function apply_sanitization_and_validation( $setting, $value, $config ) {

		$stored = Hobbes_Syncs_Options::get_all();
		$existing_value = $stored[ $setting ];
		if ( $value == $existing_value ) {
			return $config;
		}

		if ( is_array( $value) ) {
			//do for full array
			$filtered = self::apply_sanitization_filter( $setting, $value, $config );
			$config[ $setting ]  = $filtered;

			foreach( $value as $sub_setting => $val ) {
				if ( is_array( $val ) ) {

					//do for parts of field groups
					foreach ( $val as $k => $v  ) {
						$existing_value = $stored[ $setting ][ $sub_setting ][ $k ];
						if ( $existing_value != $v ) {
							$v = self::apply_sanitization_filter( $k, $v, $config, $setting );
							$config[ $setting][ $sub_setting ][ $k ] = $v;
						}

					}

				}

			}



		}else {
			$config [ $setting ] =  self::apply_sanitization_filter( $setting, $value, $config );
		}


		return $config;

	}

	/**
	 * Actually applies the sanization and/ or validation filter
	 *
	 * @access protected
	 *
	 * @param string $setting The name of the setting being saved.
	 * @param mixed $value The value being saved
	 * @param array $config Data being saved
	 * @param string|bool $sub_setting Optional. The "sub_setting" when saving fields that are arrays.
	 */
	protected static function apply_sanitization_filter( $setting, $value, $config, $sub_setting = false ) {
		$filter_name = "hobbes_syncs_{$setting}";
		if (  $sub_setting ) {
			$filter_name = "hobbes_syncs_{$sub_setting}_{$setting}";
		}

		/**
		 * Hook here to sanatize/validate settings
		 *
		 * @param string $setting The name of the setting being saved.
		 * @param mixed $value The value being saved
		 * @param array $config Data being saved
		 */
		return apply_filters( $filter_name, $value, $config );

	}


} 
