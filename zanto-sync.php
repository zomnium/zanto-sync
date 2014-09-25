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

	// Zanto object
	private $zanto;

	// Zanto settings
	private $zanto_settings;

	// ZantoSync Modules
	private $modules;

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

		// Check for Zanto settings
		if ( ! $this->zanto_settings ) return false;

		// Check for Zanto setup completion
		if ( 'complete' != $this->zanto_settings['setup_status']['setup_wizard'] ) return false;

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

		// Load Zanto settings
		$this->zanto_settings = get_option( 'zwt_zanto_settings', false );

		// Check requirements
		if ( ! $this->validate_requirements() ) return false;

		// Zanto is registered and found
		if ( isset( $zwt_site_obj ) )
		{
			// Get Zanto local
			$this->zanto = $zwt_site_obj;

			// Load core components
			require_once( 'inc/zanto-sync-module.php' );
			require_once( 'inc/zanto-sync-ui.php' );

			// Load core modules
			$this->module_load( __DIR__ . '/modules/helpers.php', 'ZantoSyncHelpers', 'helpers' );
		}
	}

	/**
	 * Load module
	 * Includes and loads Zanto Sync modules
	 * @param string $file
	 * @param string $class
	 * @return boolean
	 */

	public function module_load( $file, $class, $key = false )
	{
		// Get file if it exists
		if ( ! file_exists( $file ) ) return false;
		require_once( $file );

		// Load class if it exists
		if ( ! class_exists( $class ) ) return false;
		$key = ( ! $key ) ? $class : $key;
		$this->modules[$key] = new $class;

		// Done and you know it!
		return true;
	}

	/**
	 * Module
	 * Gives access to loaded modules
	 * @param string $module
	 * @return boolean or object
	 */

	public function module( $module )
	{
		// Requested module is not loaded
		if ( ! isset( $this->modules[$module] ) ) return false;

		// Return module object
		return $this->modules[$module];
	}

}

// Let's run this basterd :)
new ZantoSync;
