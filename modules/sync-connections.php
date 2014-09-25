<?php

/**
 * Sync Connections for Zanto
 */

class ZantoSyncConnections extends ZantoSyncComponent
{
	public function __construct()
	{
		// Load ZantoSyncComponent
		parent::__construct();

		// Check requirements
		if ( $this->validate_requirements() ) return false;

		// Set hooks
		$this->set_hooks();
	}

	/**
	 * Validate requirements
	 * Checks if everything is alright
	 * @return boolean
	 */

	public function validate_requirements()
	{
		// Check for Posts 2 Posts
		if ( ! defined( 'P2P_PLUGIN_VERSION' ) ) return false;

		// Everything is goood
		return true;
	}

	/**
	 * Set hooks
	 * Straps all hooks to actions and filters
	 * @return null
	 */

	public function set_hooks()
	{
		add_action( 'the_post', array( $this, 'get_event' ) );
	}

	/**
	 * Batch process
	 * @todo implement this function
	 */

	public function batch_process()
	{
		// TODO: implement
	}

	/**
	 * Sync connections
	 * @return null
	 */

	public function sync_connections()
	{
		// TODO: implement
	}
}
