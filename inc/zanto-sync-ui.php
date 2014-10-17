<?php

/**
 * Admin UI
 */

class ZantoSyncUI
{
	public function __construct()
	{
		// Load hooks for actions and filters
		add_action( 'admin_menu', array( $this, 'admin_navigation' ) );
		add_filter( 'plugin_action_links', 'plugin_action_links', 10, 2 );
	}

	/**
	 * Plugin action links
	 */

	public function plugin_action_links( $links, $file )
	{
		// Just return, todo: implement
		return $links;
	}

	/**
	 * Admin navigation
	 */

	public function admin_navigation()
	{
		// Register navigation item
		// /wp-admin/options-general.php?page=sync-for-zanto
	    add_options_page(
	    	'Sync for Zanto',			// Page title
	    	'Sync for Zanto',			// Navigation title
	    	'manage_options',			// Capabilities
	    	'sync-for-zanto',			// Menu slug
	    	array( $this, 'settings' )	// Callback function
	    	);
	}

	/**
	 * Settings
	 */

	public function settings()
	{
		// User has no access
		if ( ! current_user_can( 'manage_options' ) )
			wp_die( 'Nope!' );

		// Execute page code :)
		echo 'hellow';
	}
}

