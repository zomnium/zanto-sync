<?php

/**
 * Sync Gigpress for Zanto
 */

class ZantoSyncGigpress extends ZantoSyncComponent {

	public function __construct() {
		if ( defined( 'GIGPRESS_VERSION') ) {
			return $this->set_hooks();
		}
	}

	public function set_hooks() {
		add_action( 'the_post', array( $this, 'get_event' ) );
	}

	public function get_event( $post ) {
		if ( 'post' == $post->get_post_type() ) {
			$post_id = get_metadata( 'post', $post->ID, 'zwt_post_network' );
			return $this->gigpress_show_related( array(), $post, $post_id );
		}
	}

	/**
	 * Credits: Gigpress
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
