<?php

/**
 * Sync Users for Zanto
 */

class ZantoSyncUsers extends ZantoSyncComponent
{
	public function __construct()
	{
		// Load ZantoSyncComponent
		parent::__construct();
	}

	/**
	 * Set hooks
	 */

	public function set_hooks()
	{
		add_action( 'user_register', array( $this, 'sync_user', 10, 1 ) );
		add_action( 'profile_update', array( $this, 'sync_user' ) );
	}

	/**
	 * Batch process
	 */

	public function batch_process() {}

	/**
	 * New user
	 */

	public function new_user( $user_id ) {}

	/**
	 * Update user
	 */

	public function update_user() {}

	/**
	 * Delete user
	 */

	public function delete_user() {}

	/**
	 * Sync user
	 */

	public function sync_user()
	{
		return add_user_to_blog( $blog_id, $user_id, $role );
	}
}
