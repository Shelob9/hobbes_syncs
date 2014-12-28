<?php
/**
 * Base class for other key-based class. Hold the static vars
 *
 * @package jp_keyed_request\auth
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2014 Josh Pollock
 */

namespace jp_keyed_request\auth;

/**
 * Class keys
 * @package jp_keyed_request\auth
 */
abstract class keys {
	public static $public_key_option_name = 'jp_keyed_request_public_key';
	public static $private_key_option_name = 'jp_keyed_request_private_key';
	public static  $allow_option_name = 'jp_keyed_request_allow_deploy';
	public static $request_key = 'jp-keyed-request-key';
	public static $request_token = 'jp-keyed-request-token';
}
