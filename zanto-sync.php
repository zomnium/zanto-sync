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

	// Zanto object
	private $zanto;

	// Zanto Sync Components
	private $components;

	/**
	 * Constructor
	 * Initalizes the plugin
	 */

	public function __construct()
	{
		// Create ZantoSync instance
		self::$instance = $this;

		// Wait for all plugins to load, then execute
		add_action( 'plugins_loaded', array( $this, 'bootstrap' ) );
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
	 * @return boolean
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
	 * Bootstrap
	 * Executes this plugin after all plugins are loaded
	 * @return null 
	 */

	public function bootstrap()
	{
		// Get Zanto object
		global $zwt_site_obj;

		// Check requirements
		if ( ! $this->validate_requirements() ) return false;

		// Load Zanto settings
		$this->settings = get_option( 'zwt_zanto_settings', null );

		// Zanto is registered and found
		if ( isset( $zwt_site_obj ) )
		{
			// Get Zanto local
			$this->zanto = $zwt_site_obj;
		}
	}

	/**
	 * Get network
	 * Returns the translation network
	 * @return array
	 */

	public function get_network()
	{
		return zwt_get_languages();
	}

	/**
	 * Get network blogs
	 * Returns the translation network's language codes and blog id's
	 * @return array
	 */

	public function get_network_blogs()
	{
		return $this->zanto->modules['trans_network']->get_transnet_blogs();
	}

	/**
	 * Get primary language
	 * Returns the primary language
	 * @return string
	 */

	public function get_primary_language()
	{
		return $this->settings['translation_settings']['default_admin_locale'];
	}

	/**
	 * Get current language
	 * Returns the current language
	 * @return string
	 */

	public function get_current_language()
	{
		return get_bloginfo( 'language' );
	}
}

// Let's run this basterd :)
new ZantoSync;
