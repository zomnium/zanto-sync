<?php

/**
 * Sync Posts for Zanto
 */

class ZantoSyncPosts extends ZantoSyncComponent
{
	public function __construct()
	{
		// Load ZantoSyncComponent
		parent::__construct();

		// Set hooks
		return $this->set_hooks();
	}

	/**
	 * Set hooks
	 */

	public function set_hooks()
	{
		add_action( 'wp_insert_post', array( $this, 'new_post' ) );
		add_action( 'post_updated', array( $this, 'update_post' ), 10, 3 );
		add_action( 'trashed_post ', array( $this, 'trash_post' ) );
		add_action( 'untrashed_post  ', array( $this, 'untrash_post' ) );
		add_action( 'before_delete_post  ', array( $this, 'delete_post' ) );
	}

	public function batch_process()
	{
		//
	}

	public function new_post()
	{
		switch_to_blog( $site_id );
		// do something
		restore_current_blog();
	}

	public function update_post( $post_id, $post_after, $post_before ) {}

	public function trash_post() {}

	public function untrash_post() {}

	public function delete_post() {}

	public function sync_post() {}
}
