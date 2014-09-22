<?php

/*
Plugin Name: Sync for Zanto
Plugin URI: http://zomnium.com/
Description: Syncs posts between translation network sites using Zanto's language relations.
Author: Zomnium, Tim van Bergenhenegouwen
Version: 0.0.1
Author URI: http://zomnium.com/
*/

class ZantoSync
{
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

	public function __construct()
	{
		self::$instance = $this;
		$this->settings = get_option( 'zwt_zanto_settings', null );
	}

	/**
	 * Get instance
	 * Returns the singleton instance
	 */

	public static function get_instance()
	{
		return self::$instance;
	}

	/**
	 * Validate requirements
	 * Executes a checklist for all plugin requirements
	 */

	public function validate_requirements()
	{
		// Check if Zanto is found
		if ( ! defined( 'GTP_ZANTO_VERSION' ) ) return false;

		// Check for Zanto setup completion
		if ( 'complete' != $this->settings['setup_status']['setup_wizard'] ) return false;

		// Everything is okay :D
		return true;
	}

	/**
	 * Get network
	 * Returns the translation network
	 */

	public function get_network()
	{
		return zwt_get_languages();
	}

	/**
	 * Get primary language
	 * Returns the primary language
	 */

	public function get_primary_language()
	{
		return $this->settings['translation_settings']['default_admin_locale'];
	}

	/**
	 * Get current language
	 * Returns the current language
	 */

	public function get_current_language()
	{
		return get_bloginfo( 'language' );
	}
}

// Let's run this basterd :)
new ZantoSync;
