<?php
/**
 * Functions for this lib
 *
 * @package   @hsync
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2014 Josh Pollock
 */
/**
 * Allow using 'x-id-search' GET var for searching by ID in Pods API
 */
add_filter( 'pods_json_api_pods_get_items_params', function( $params, $pod ) {
	$id = pods_v_sanitized( 'x-id-search' );
	if ( $id ) {
		$params[ 'where' ] = 't.ID="'.$id.'"';
	}

	$column = pods_v_sanitized( 'x-search-by' );
	$prefix = pods_v_sanitized( 'x-search-prefix' );
	$value = pods_v_sanitized( 'x-search-value' );
	if ( $column && $prefix && $value ) {
		$params[ 'where' ] = $prefix.'.'.$column.'='.$value;
	}

	return $params;

}, 10, 2 );
