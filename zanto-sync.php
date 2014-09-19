<?php

/**
 * @package ZantoSync
 * @version 0.0.1
 */

/*
Plugin Name: Sync for Zanto
Plugin URI: http://zomnium.com/
Description: Syncs posts between translation network sites using Zanto's language relations.
Author: Zomnium, Tim van Bergenhenegouwen
Version: 0.0.1
Author URI: http://zomnium.com/
*/

class ZantoSync {

	public function __construct() {
		return true;
	}

	public function validateRequirements() {
		return false;
	}
}

new ZantoSync;
