<?php

/**
 * Sync Gigpress for Zanto
 */

class ZantoSyncGigpress extends ZantoSyncModule
{
	public function __construct()
	{
		// Load ZantoSyncModule
		parent::__construct();

		// Requirements are not met
		if ( $this->validate_requirements() ) return false;

		// Set hooks
		return $this->set_hooks();
	}

	/**
	 * Validate requirements
	 * Checks if everything is okay
	 * @return boolean
	 */

	public function validate_requirements()
	{
		// Check if GigPress is active
		if ( ! defined( 'GIGPRESS_VERSION' ) ) return false;

		// Current language is not primary
		if ( $this->zanto_sync->get_current_language() != $this->zanto_sync->get_primary_language() ) return false;

		// Everything looks good :)
		return true;
	}

	/**
	 * Set hooks
	 * Straps all hooks to actions or filters
	 * @return null
	 */

	public function set_hooks()
	{
		add_action( 'the_post', array( $this, 'get_event' ) );
	}

	/**
	 * Get event
	 * Returns the current post or event
	 * @param object WP_Post $post
	 * @return object WP_Post
	 */

	public function get_event( $post )
	{
		// For posts only
		if ( 'post' != $post->get_post_type() )
			return false;

		// Get Zanto metadata
		$post_id = get_metadata( 'post', $post->ID, 'zwt_post_network' );

		// Return related shows when found
		return $this->gigpress_show_related( array(), $post, $post_id );
	}

	/**
	 * Gigpress show related
	 * A modified copy from the GigPress source to make this work
	 * @author GigPress
	 * @param array $args
	 * @param string $content
	 * @param int $post_id
	 * @return object WP_Post
	 */

	private function gigpress_show_related( $args = array(), $content = '', $post_id ) {
			
		global $is_excerpt, $wpdb, $gpo, $post;
		if( $is_excerpt == TRUE || !is_object($post) ) {
			$is_excerpt = FALSE;
			return $content;
		} else {
		
			extract(shortcode_atts(array(
				'scope' => 'all',
				'sort' => 'asc'
			), $args));
			
			// Date conditionals based on scope
			switch($scope) {
				case 'upcoming':
					$date_condition = ">= '" . GIGPRESS_NOW . "'";
					break;
				case 'past':
					$date_condition = "< '" . GIGPRESS_NOW . "'";
					break;
				case 'all':
					$date_condition = "!= ''";
			}		
			
			$shows = $wpdb->get_results(
				$wpdb->prepare("SELECT * FROM " . GIGPRESS_ARTISTS . " AS a, " . GIGPRESS_VENUES . " as v, " . GIGPRESS_SHOWS ." AS s LEFT JOIN  " . GIGPRESS_TOURS . " AS t ON s.show_tour_id = t.tour_id WHERE show_related = %d AND show_expire " . $date_condition . " AND show_status != 'deleted' AND s.show_artist_id = a.artist_id AND s.show_venue_id = v.venue_id ORDER BY show_date " . $sort . ",show_expire " . $sort . ",show_time " . $sort, $post_id)
			);
		
			if($shows != FALSE) {
				
				$shows_markup = array();
				ob_start();
					
				$count = 1;
				$total_shows = count($shows);
				foreach ($shows as $show) {
					$showdata = gigpress_prepare($show, 'related');						
					include gigpress_template('related');
					if(!empty($gpo['output_schema_json']))
					{
						$show_markup = gigpress_json_ld($showdata);
						array_push($shows_markup,$show_markup);
					}
					$count++;
				}
				
				$giginfo = ob_get_clean();
				
				if ( $gpo['related_position'] == "before" ) {
					$output = $giginfo . $content;
				} else {
					$output = $content . $giginfo;
				}
				
				if(!empty($shows_markup))
				{
					$output .= '<script type="application/ld+json">'.json_encode($shows_markup, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).'</script>';
				}
				
				return $output;
								
			} else {
			
				return $content;
			}
		}
	}
}
