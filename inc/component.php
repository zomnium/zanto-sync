<?php

/**
 * Component for Zanto Sync
 */

abstract class ZantoSyncComponent {

	protected $zanto_sync;

	public function __construct()
	{
		$this->zanto_sync = ZantoSync::get_instance();
	}
}
