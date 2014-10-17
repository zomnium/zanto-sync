<?php

/**
 * Zanto Sync Translation Redirect
 * Redirects to a translation of a post.
 *
 * Some notes and resources:
 * Output Buffer functions are being used because when the action hook the_post is called get_header() is already executed and redirection will fail.
 * The redirection takes place after getting the main post because of Zanto's initialization.
 * http://stackoverflow.com/questions/12133200/how-do-i-create-a-route-in-wordpress
 * http://codex.wordpress.org/Rewrite_API/add_rewrite_rule
 * http://codex.wordpress.org/Rewrite_API/add_rewrite_tag
 */

class ZantoSyncTranslationRedirect extends ZantoSyncModule
{
	private $request_id;
	private $redirection_request;

	public function __construct()
	{
		// Load ZantoSyncModule
		parent::__construct();

		// Set hooks
		$this->set_hooks();
	}

	/**
	 * Set Hooks
	 * Registers all filter and action hooks for this class
	 */

	private function set_hooks()
	{
		add_filter( 'query_vars', array( $this, 'custom_variables' ) );
		add_filter( 'translation_redirect', array( $this, 'filter_create_link' ), 10, 2 );
		add_action( 'parse_request', array( $this, 'variable_handler') );
	}

	/**
	 * Custom Variables
	 * Creates custom variables to be registered by WordPress
	 * Called by the custom_variables filter hook
	 * @param array $vars
	 * @return array
	 */

	public function custom_variables( $vars )
	{
		// Add custom variables
		$vars[] = 'translation_redirect';
		$vars[] = 'translation_redirect_language';
		return $vars;
	}

	/**
	 * Variable Handler
	 * Validates input to be processed later
	 * Called by the parse_request action hook
	 * @param WP object $wp
	 * @return null
	 */

	public function variable_handler( $wp )
	{
		// Request is incomplete
		if ( empty( $wp->query_vars['translation_redirect'] ) )
			return false;

		// Save request
		$this->request_id = url_to_postid( $wp->request );
		$this->redirection_request = $wp->query_vars['translation_redirect'];

		// Prevent output from being send
		ob_start();

		// Register hook for redirection handler
		add_action( 'the_post', array( $this, 'redirector' ) );
	}

	/**
	 * Redirector
	 * Handles the redirection headers and already build output cleaning/flushing
	 * Called by the the_post action hook
	 * @param WP_Post object $post
	 * @return null
	 */

	public function redirector( $post )
	{
		// Current call is not the main request, stop further execution
		if ( ! $this->is_main_post( $post->ID ) )
			return false;

		// Filter request input
		$this->redirection_request = htmlspecialchars( $this->redirection_request );

		// Redirection request is equal to current language, do not redirect
		if ( $this->redirection_request == $this->zanto_sync->module('helpers')->get_current_language() )
			return $this->quit_redirection();

		// Get translation permalink
		$redirection_request = str_replace( '-', '_', $this->redirection_request );
		$translations = zwt_get_languages();

		// Not translation found, do not redirect
		if ( ! array_key_exists( $redirection_request, $translations ) )
			return $this->quit_redirection();

		// Clear created output, so the redirection headers will be executed properly
		ob_clean();

		// Redirect - 302 is temporary, 301 is permanent
		wp_redirect( $translations[ $redirection_request ]['url'], 302 );

		// Send output (redirection headers)
		ob_end_flush();

		// Exit, to stop further execution
		exit;
	}

	/**
	 * Is Main Post
	 * Checks if the current post id is from the main request
	 * @param int $post_id
	 * @return boolean
	 */

	public function is_main_post( $post_id )
	{
		// Check if post id's match and return result
		return ( $post_id == $this->request_id ) ? true : false;
	}

	/**
	 * Quit redirection
	 * Flushes already build output
	 * @return boolean (false only)
	 */

	public function quit_redirection()
	{
		// Flush output, to send the created output
		ob_end_flush();

		// Return false, to prevent proceeding function execution
		return false;
	}

	/**
	 * Create Link
	 * Creates a redirection permalink
	 * @param string $permalink
	 * @param string $language Use language code like en-US or nl-NL
	 * @return string
	 */

	public function filter_create_link( $permalink, $language = false )
	{
		// Use current language as default
		if ( ! $language )
			$language = $this->zanto_sync->module('helpers')->get_current_language();

		// TODO:
		// Build an current is source detection, to prevent messy URL's when possible

		// Create and return permalink
		return $permalink . '?translation_redirect=' . $language;
	}
}
