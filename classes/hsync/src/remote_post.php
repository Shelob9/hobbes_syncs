<?php
/**
 * POSTs to remote
 *
 * @package   @hsync
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2014 Josh Pollock
 */
namespace hsync;

/**
 * Class remote_post
 * @package hsync
 */
class remote_post {

	/**
	 * Check if can send class instance
	 *
	 * @access protected
	 *
	 * @since 0.0.1
	 *
	 * @var object|is_post_to_sync
	 */
	protected  $can_send_class;

	/**
	 * Post ID to POST
	 *
	 * @since 0.0.1
	 *
	 * @var int
	 */
	public     $post_id;

	/**
	 * Post type of post being synced
	 *
	 * @access protected
	 *
	 * @since 0.0.1
	 *
	 * @var string
	 */
	protected  $post_type;

	/**
	 * Post object
	 *
	 * @access protected
	 *
	 * @since 0.0.1
	 *
	 * @var object
	 */
	protected   $post;

	function __construct( $post_id, $post ) {
		$this->post_id = $post_id;
		$this->can_send_class = new is_post_to_sync( $post_id, $post );
		if ( $this->can_send_class->is_good ) {
			$this->post = $post;
			$this->run();
		}


	}

	/**
	 * Run the updates
	 *
	 * @since 0.0.1
	 */
	public function run() {
		if ( ! $this->can_send_class->is_good ) {
			return;
		}

		$data = $this->get_post_json();
		if ( is_null( $data ) ) {
			return;
		}

		$sites = $this->get_remote_sites();
		if ( ! empty( $sites ) ) {
			foreach( $sites as $site ) {
				$this->send( $site, $data );
			}

		}

		return $this->post_id;

	}

	/**
	 * Check if we can send
	 *
	 * @since 0.0.1
	 *
	 * @access protected
	 *
	 * @return bool
	 */
	protected function can_send() {
		$class = $this->can_send_class;
		return $class->is_good;

	}

	/**
	 * Prepare post
	 *
	 * @since 0.0.1
	 *
	 * @access protected
	 *
	 *
	 * @return object
	 */
	protected function get_post_json() {
		$id = $this->post_id;

		$post_type = get_post_type( $id );
		$this->post_type = $post_type;
		$pods = pods( $post_type, $id, true );
		if ( is_object( $pods ) ) {
			$data = $pods->export();
			$data = $this->prepare_data( $data );
			return $data;
		}

	}

	/**
	 * Send to remote site
	 *
	 * @since 0.0.1
	 *
	 * @param array $site_info
	 * @param object|array $data
	 */
	public function send( $site_info, $data ) {
		$root_url = $site_info[ 'json_url' ];

		if ( $root_url  ) {
			$url = trailingslashit( $root_url ).'pods/' . $this->post_type;
			$id = $this->find_id( $data, $root_url );

			if ( $id ) {
				$url = $url.'/'.$id;
			}

			unset( $data->id );

			$args = array(
				'requesting_url' => urlencode( json_url() ),
				'requesting_id' => $id,
			);

			$featured = get_post_thumbnail_id( $id );
			$featured = get_attached_file( $featured );

			if ( filter_var( $featured, FILTER_VALIDATE_URL ) ) {
				$args ['feature_set'] =  urlencode( $featured );
			}

			$url = add_query_arg( $args, $url );
		
			$r = jp_keyed_request_make( $url, $data );

		}


	}

	/**
	 * Find ID of remote post
	 *
	 * @since 0.0.1
	 *
	 * @param array|object $data
	 * @param string $root_url
	 *
	 * @return array
	 */
	public function find_id( $data, $root_url ) {
		$post_name = pods_v( 'post_name', $data );
		if ( $post_name ) {
			$id = find_remote_id::find( $root_url, $this->post_type, $post_name );

			if ( intval( $id ) > 0 ) {
				return $id;

			}
		}
	}


	/**
	 * Get remote sites to POST to
	 *
	 * @since 0.0.1
	 *
	 * @access protected
	 *
	 * @return array
	 */
	protected function get_remote_sites() {
		$value = \Hobbes_Syncs_Options::get( 'remote_sites', array() );
		$sites = array();

		foreach( $value as $node ) {
			$sites[] = $node;
		}

		return $sites;

	}

	/**
	 * Prepare data for transmission.
	 *
	 * @since 0.0.2
	 *
	 * @param array $data The data
	 *
	 * @return array
	 */
	public function prepare_data( $data ) {
		$img_fields = apply_filters( 'hsyncs_image_fields', array( 'img' ), $this->post_type, $data );

		unset( $data[ 'guid' ] );

		foreach( $data as $key => $val ) {


			if ( in_array( $key, $img_fields ) ) {
				$img = pods_v( 'ID', $val );
				$img = wp_get_attachment_image_src( $img );
				if ( is_array( $img ) && isset( $img[0] ) ) {
					$data[ $key ] = $val = $img[0];
				}else{
					unset( $data[ $key ] );
					$val = null;
				}

				continue;

			}

			if ( is_array( $val ) ) {
				if ( ! empty( $val ) ) {
					if ( array_key_exists( 'guid', $val ) ) {
						unset( $val[ 'guid' ] );
					}

					foreach ( $val as $i => $v ) {
						if ( ! $v ) {
							$val[ $i ] = "0";
						}


					}
				}else{
					$data[ $key ] = $val = "0";
				}

				$data[ $key ] = $val;

			}

		}

		return $data;

	}



} 
