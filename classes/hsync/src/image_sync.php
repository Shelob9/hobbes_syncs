<?php
/**
 * Sync images in fields
 *
 * @package   hsync
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2015 Josh Pollock
 */

namespace hsync;

/**
 * Class image_sync
 *
 * @package hsync
 */
class image_sync {

	/**
	 * @var object|\Pods
	 */
	protected $pods;


	/**
	 * Sync image fields.
	 *
	 * @param object|\Pods $pods
	 * @param string $remote_url The requesting URL
	 * @param string $post_type Post type
	 * @param int $remote_id ID of remote post
	 * @param int $local_id ID of local id.
	 */
	function __construct( $pods, $remote_url, $post_type, $remote_id, $local_id ) {
		$this->post_type = $post_type;
		$this->pods = $pods;

		$image_fields = $this->find_image_fields();

		$url = trailingslashit( $remote_url ) . '/pods/' . $post_type . '/' . $remote_id;

		if ( ! empty( $image_fields ) ) {
			$r = wp_remote_get( $url, array( 'timeout' => 9999 ) );
			if ( ! is_wp_error( $r ) ) {
				$r = (array) json_decode( wp_remote_retrieve_body( $r ) );
				$local_pod = pods( $post_type, $local_id );
				if ( ! empty( $r ) && ! is_array( $r ) ) {
					foreach ( $image_fields as $field ) {
						if ( isset( $r[ $field ] )  ) {
							$remote_field_values = $r[ $field ];
							$local_field_values = $local_pod->field( $field );
							foreach( $remote_field_values as $remote_value ) {

								foreach( $local_field_values as $local_field ) {
									if ( $local_field[ 'name' ] == $remote_value [ 'name' ] ) {

										//check if same
										$current = get_attached_file( $local_field[ 'ID' ] );
										if ( set_featured::is_same( $remote_value[ 'guid' ], $current ) ) {
											continue;
										}

										$pods->add_to( $field, pods_attachment_import( $remote_value[ 'guid' ], $local_id  ), $local_id );

									}

								}

							}

						}

					}

				}

			}

		}

	}

	/**
	 * Find the image fields
	 *
	 * @return array
	 */
	protected function find_image_fields( ) {
		$img_fields = apply_filters( 'hsyncs_image_fields', array( 'img' ), $this->post_type );

		return $img_fields;
	}





}
