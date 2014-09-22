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

	// Singleton instance
	private static $instance;

	// Zanto settings
	private $settings;

	// Zanto Sync Components
	private $components;

	/**
	 * Constructor
	 * Initalizes the plugin
	 */
	public function __construct() {
		self::$instance = $this;
		$this->settings = get_option( 'zwt_zanto_settings', null );
	}

	/**
	 * Get instance
	 * Returns the singleton instance
	 */
	public static function get_instance() {
		return self::$instance;
	}

	/**
	 * Validate requirements
	 * Executes a checklist for all plugin requirements
	 */
	public function validate_requirements() {}

	/**
	 * Found
	 */
	public function zwt_found() {}

	/**
	 * Network
	 */
	public function zwt_network() {}

	/**
	 * Primary
	 */
	public function zwt_primary() {}

	/**
	 * Current
	 */
	public function zwt_current() {}
}

// Let's run this basterd :)
new ZantoSync;
