<?php

/**
 * Sync Connections for Zanto
 */

class ZantoSyncConnections extends ZantoSyncComponent {

	public function __construct() {
	}

	public function set_hooks() {
		add_action( 'the_post', array( $this, 'get_event' ) );
	}

	public function batch_process() {
		//
	}

	public function sync_connections() {
		//
	}
}
