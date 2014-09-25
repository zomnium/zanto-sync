<?php

/**
 * Sync Posts for Zanto
 */

class ZantoSyncPosts extends ZantoSyncModule
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
		// add_action( 'wp_insert_post', array( $this, 'new_post' ) );
		// add_action( 'post_updated', array( $this, 'update_post' ), 10, 3 );
		add_action( 'save_post', array( $this, 'updated_post' ) );
		add_action( 'trashed_post ', array( $this, 'trash_post' ) );
		add_action( 'untrashed_post  ', array( $this, 'untrash_post' ) );
		add_action( 'before_delete_post  ', array( $this, 'delete_post' ) );
	}

	/**
	 * Batch process
	 * @todo implement this function
	 */

	public function batch_process()
	{
		//
	}

	/**
	 * Updated post
	 * For new and updated posts
	 * @param int $post_id
	 * @return unknown
	 */

	public function updated_post( $post_id )
	{
		// Get post
		$post = get_post( $post_id, ARRAY_A );

		// Remove id
		unset( $post['ID'] );

		// Sync post
		$this->sync_post()
	}

	public function new_post() {}

	public function update_post( $post_id, $post_after, $post_before ) {}

	public function trash_post() {}

	public function untrash_post() {}

	public function delete_post() {}

	/**
	 * Sync post
	 * Handles post network syncing
	 * @param array $post
	 * @return null
	 */

	public function sync_post( $post )
	{
		// Get network blogs
		$network = $this->zanto_sync->get_network_blogs();

		// Get current language
		$current_language = $this->zanto_sync->get_current_language();

		// Loop through network blogs
		foreach ($network as $blogs)
		{
			// Exclude current blog from syncing
			if ( $blog['lang_code'] != $current_language )
			{
				// Sync post
				switch_to_blog( $blog['blog_id'] );
				wp_insert_post( $post );
				// TODO: add translation link
			}
		}

		// Restore current blog
		restore_current_blog();
	}
}
