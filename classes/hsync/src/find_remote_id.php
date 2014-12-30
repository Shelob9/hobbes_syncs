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

namespace hsync;


class find_remote_id {




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

	protected static function set_args( $post_name) {
		$args = array(
			'x-search-by' => 'post_name',
			'x-search-prefix' => 't',
			'x-search-value' => $post_name
		);

		return $args;
	}
}
