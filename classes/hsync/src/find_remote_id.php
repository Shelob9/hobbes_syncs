<?php
/**
 * Find the ID of the post on the other site
 *
 * @package hsync
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2014 Josh Pollock
 */

namespace hsync;

/**
 * Class find_remote_id
 * @package hsync
 */
class find_remote_id {

	/**
	 * Find ID of remote post, by querying for it on remote site.
	 *
	 * @param string $root_url
	 * @param string $post_type
	 * @param string $post_name
	 *
	 * @return int|null The ID of post or null if doesn't exist.
	 */
	public static function find( $root_url, $post_type, $post_name ) {
		$url = self::build_url( $root_url, $post_type, $post_name  );
		$response = wp_remote_get( $url );

		if ( ! is_wp_error( $response ) ) {
			$body = wp_remote_retrieve_body( $response );
			$body = (array)json_decode( $body );
			$id = array_keys($body );
			if ( is_string( $id ) ) {
				return $id;
			}else{
				if ( isset( $id[0]) ) {
					return $id[0];
				}
				reset( $id );
				$key = key( $id );
				if ( isset( $id[ $key ]) ) {
					return $id[ $key ];

				}
			}

		}

	}

	/**
	 * Build URL for request, exploiting custom args set in this plugin.
	 *
	 *
	 * @since 0.0.1
	 *
	 * @access protected
	 *
	 * @param string $root_url
	 * @param string $post_type
	 * @param string $post_name
	 *
	 * @return string
	 */
	protected static function build_url( $root_url, $post_type, $post_name  ) {
		$parts = array(
			untrailingslashit( $root_url ),
			'pods',
		    $post_type,

		);
		$url = implode( '/', $parts );

		$args = self::set_args( $post_name );

		$url = add_query_arg( $args, $url );

		return $url;

	}

	/**
	 * Args for add_query_arg() in self::build_url()
	 *
	 * @since 0.0.1
	 *
	 * @access protected
	 *
	 * @param string $post_name
	 *
	 * @return array
	 */
	protected static function set_args( $post_name) {
		$args = array(
			'x-search-by' => 'post_name',
			'x-search-prefix' => 't',
			'x-search-value' => $post_name
		);

		return $args;
		
	}
}
