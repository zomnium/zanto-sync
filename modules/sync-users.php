<?php

/**
 * Sync Users for Zanto
 */

class ZantoSyncUsers extends ZantoSyncModule
{
	public function __construct()
	{
		// Load ZantoSyncModule
		parent::__construct();

		// Set hooks
		$this->set_hooks();
	}

	/**
	 * Set hooks
	 * @return null
	 */

	public function set_hooks()
	{
		add_action( 'user_register', array( $this, 'updated_user', 10, 1 ) );
		add_action( 'profile_update', array( $this, 'updated_user' ), 10, 2 );
		add_action( 'edit_user_profile_update', array( $this, 'updated_user' ), 10, 2 );
	}

	/**
	 * Batch process
	 * @todo implement this function
	 */

	public function batch_process() {}

	/**
	 * Updated user
	 * For new or updated users
	 * @param int $user_id
	 * @param object $misc - optional
	 * @return null
	 */

	public function updated_user( $user_id, $misc = false )
	{
		// Get user
		$user = get_user_by( 'id', $user_id );

		// User is found
		if ( $user )
		{
			// Get roles
			$user_roles = $user->roles;

			// TODO:
			// Process role(s)? (Must be one of the WordPress roles.)

			// Let's sync!
			$this->sync_user( $user_id, $user_roles );
		}
	}

	/**
	 * Delete user
	 * @todo implement this function
	 */

	public function delete_user() {}

	/**
	 * Sync user
	 * Handles user network syncing
	 * @param int $user_id
	 * @param array $roles
	 * @return null
	 */

	public function sync_user( $user_id, $roles )
	{
		// Get network blogs
		$network = $this->zanto_sync->get_network_blogs();

		// Get current language
		$current_language = $this->zanto_sync->get_current_language();

		// Loop through network blogs
		foreach ($network as $blog)
		{
			// Exclude current blog from syncing
			if ( $blog['lang_code'] != $current_language )
			{
				// Sync user
				add_user_to_blog( $blog['blog_id'], $user_id, $roles );
			}
		}
	}
}
