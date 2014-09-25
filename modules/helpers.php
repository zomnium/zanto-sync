<?php

/**
 * Zanto Sync Helpers
 */

class ZantoSyncHelpers extends ZantoSyncModule
{
	/**
	 * Get network
	 * Returns the translation network
	 * @return array
	 */

	public function get_network()
	{
		return zwt_get_languages();
	}

	/**
	 * Get network blogs
	 * Returns the translation network's language codes and blog id's
	 * @return array
	 */

	public function get_network_blogs()
	{
		return $this->zanto->modules['trans_network']->get_transnet_blogs();
	}

	/**
	 * Get primary language
	 * Returns the primary language
	 * @return string
	 */

	public function get_primary_language()
	{
		return $this->zanto_settings['translation_settings']['default_admin_locale'];
	}

	/**
	 * Get current language
	 * Returns the current language
	 * @return string
	 */

	public function get_current_language()
	{
		return get_bloginfo( 'language' );
	}
}
