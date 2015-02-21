<?php
/**
 * Class for setting featured image
 *
 * @package   @hysnc
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2015 Josh Pollock
 */
namespace hsync;

class set_featured {



	/**
	 * Upload an image and make it the featured image
	 *
	 * Much copypasta from http://theme.fm/2011/10/how-to-upload-media-via-url-programmatically-in-wordpress-2657/
	 *
	 * @param $img_url
	 * @param $post_id
	 * @param string $img_desc
	 *
	 * @return int|object
	 */
	public static function add_image( $img_url, $post_id, $img_desc = '' ) {
		if ( filter_var( $img_url, FILTER_VALIDATE_URL ) && is_object( get_post( $post_id ) ) ) {
			$current = get_post_thumbnail_id( $post_id );

			$current = get_attached_file( $current );

			if ( ! self::is_same( $img_url, $current ) ) {
				$tmp = download_url( $img_url );

				$file_array = array();

				// Set variables for storage
				// fix file filename for query strings
				preg_match('/[^\?]+\.(jpg|jpe|jpeg|gif|png)/i', $img_url, $matches);
				$file_array['name'] = basename($matches[0]);
				$file_array['tmp_name'] = $tmp;

				// If error storing temporarily, unlink
				if ( is_wp_error( $tmp ) ) {
					@unlink($file_array['tmp_name']);
					$file_array['tmp_name'] = '';
				}

				// do the validation and storage stuff
				$id = media_handle_sideload( $file_array, $post_id, $img_desc );

				// If error storing permanently, unlink
				if ( is_wp_error($id) ) {
					@unlink($file_array['tmp_name']);

					return new wp_error( __FUNCTION__, $id, $file_array );

				}

				set_post_thumbnail( $post_id, $id );
				return $id;
			}

		}

	}

	/**
	 * Check if incoming image URL is the same file name as current post image.
	 *
	 * @param $img_url
	 * @param $current
	 *
	 * @return bool
	 */
	public static function is_same( $img_url, $current ) {
		$current = self::last_segment( $current );
		$new = self::last_segment( $img_url );
		$new = self::strip_ext( $new );
		$current = self::strip_ext( $current );

		$strpos = strpos( $new, $current );

		if ( 0 === $strpos || $strpos || $new === $current ) {
			return true;

		}

	}

	/**
	 * Strip extension from a file.
	 *
	 * @param $string
	 *
	 * @return mixed
	 */
	protected static function strip_ext( $string ) {
		$pathinfo = pathinfo( $string );
		$path = $pathinfo['extension'];
		return str_replace( '.'.$path, '', $string );
	}

	/**
	 * Get last segment of URL
	 *
	 * @param $url
	 *
	 * @return mixed
	 */
	protected static function last_segment( $url  )  {
		$url = parse_url( $url );
		$url = explode( '/', $url[ 'path' ] );
		end( $url );
		$key = key( $url );


		return $url[ $key ];
	}


}
/*
\hsync\set_featured::add_image( 'http://cdn.mysitemyway.com/etc-mysitemyway/icons/legacy-previews/icons-256/glossy-black-icons-food-beverage/056943-glossy-black-icon-food-beverage-knife-fork4.png', 7)
*/
