<?php

/**
 * @package ZantoSync
 * @version 0.0.1
 */

/*
Plugin Name: Sync for Zanto
Plugin URI: http://zomnium.com/
Description: Syncs posts between translation network sites using Zanto's language relations.
Author: Zomnium, Tim van Bergenhenegouwen
Version: 0.0.1
Author URI: http://zomnium.com/
*/

class ZantoSync {

	private static $instance;
	private $settings;
	private $components;

	public function __construct() {
		self::$instance = $this;
	}

	public static function get_instance() {
		return self::$instance;
	}

	public function validate_requirements() {}

	public function zwt_found() {}

	public function zwt_network() {}

	public function zwt_primary() {}

	public function zwt_current() {}
}

new ZantoSync;
