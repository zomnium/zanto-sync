<?php

/**
 * Sync Users for Zanto
 */

class ZantoSyncUsers extends ZantoSyncComponent {

	public function __construct() {
	}

	public function set_hooks() {
		add_action( 'user_register', array( $this, 'sync_user' ) );
		add_action( 'profile_update', array( $this, 'sync_user' ) );
	}

	public function batch_process() {
		//
	}

	public function sync_user() {
		return add_user_to_blog( $blog_id, $user_id, $role );
	}
}
