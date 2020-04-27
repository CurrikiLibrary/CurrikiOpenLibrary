<?php
add_filter ('bbp_has_replies', 'rw_bbp_has_replies') ;
function rw_bbp_has_replies( $args = '' ) {
	global $wp_rewrite;

	/** Defaults **************************************************************/

	// Other defaults
	$default_reply_search   = !empty( $_REQUEST['rs'] ) ? $_REQUEST['rs']    : false;
	$default_post_parent    = ( bbp_is_single_topic() ) ? bbp_get_topic_id() : 'any';
	$default_post_type      = ( bbp_is_single_topic() && bbp_show_lead_topic() ) ? bbp_get_reply_post_type() : array( bbp_get_topic_post_type(), bbp_get_reply_post_type() );
	$default_thread_replies = (bool) ( bbp_is_single_topic() && bbp_thread_replies() );

	// Default query args
	$default = array(
		'post_type'           => $default_post_type,         // Only replies
		'post_parent'         => $default_post_parent,       // Of this topic
		'posts_per_page'      => bbp_get_replies_per_page(), // This many
		'paged'               => bbp_get_paged(),            // On this page
		'orderby'             => 'date',                     // Sorted by date
		'order'               => 'ASC',                      // Oldest to newest
		'hierarchical'        => $default_thread_replies,    // Hierarchical replies
		'ignore_sticky_posts' => true,                       // Stickies not supported
		's'                   => $default_reply_search,      // Maybe search
	);
	//FIX to unset 's'
	if ($default['s'] == False) unset ($default['s']) ;

	// What are the default allowed statuses (based on user caps)
	if ( bbp_get_view_all() ) {

		// Default view=all statuses
		$post_statuses = array(
			bbp_get_public_status_id(),
			bbp_get_closed_status_id(),
			bbp_get_spam_status_id(),
			bbp_get_trash_status_id()
		);

		// Add support for private status
		if ( current_user_can( 'read_private_replies' ) ) {
			$post_statuses[] = bbp_get_private_status_id();
		}

		// Join post statuses together
		$default['post_status'] = implode( ',', $post_statuses );

	// Lean on the 'perm' query var value of 'readable' to provide statuses
	} else {
		$default['perm'] = 'readable';
	}

	/** Setup *****************************************************************/

	// Parse arguments against default values
	$r = bbp_parse_args( $args, $default, 'has_replies' );

	// Set posts_per_page value if replies are threaded
	$replies_per_page = $r['posts_per_page'];
	if ( true === $r['hierarchical'] ) {
		$r['posts_per_page'] = -1;
	}

	// Get bbPress
	$bbp = bbpress();

	// Call the query
	$bbp->reply_query = new WP_Query( $r );

	// Add pagination values to query object
	$bbp->reply_query->posts_per_page = $replies_per_page;
	$bbp->reply_query->paged          = $r['paged'];

	// Never home, regardless of what parse_query says
	$bbp->reply_query->is_home        = false;

	// Reset is_single if single topic
	if ( bbp_is_single_topic() ) {
		$bbp->reply_query->is_single = true;
	}

	// Only add reply to if query returned results
	if ( (int) $bbp->reply_query->found_posts ) {

		// Get reply to for each reply
		foreach ( $bbp->reply_query->posts as &$post ) {

			// Check for reply post type
			if ( bbp_get_reply_post_type() === $post->post_type ) {
				$reply_to = bbp_get_reply_to( $post->ID );

				// Make sure it's a reply to a reply
				if ( empty( $reply_to ) || ( bbp_get_reply_topic_id( $post->ID ) === $reply_to ) ) {
					$reply_to = 0;
				}

				// Add reply_to to the post object so we can walk it later
				$post->reply_to = $reply_to;
			}
		}
	}

	// Only add pagination if query returned results
	if ( (int) $bbp->reply_query->found_posts && (int) $bbp->reply_query->posts_per_page ) {

		// If pretty permalinks are enabled, make our pagination pretty
		if ( $wp_rewrite->using_permalinks() ) {

			// User's replies
			if ( bbp_is_single_user_replies() ) {
				$base = bbp_get_user_replies_created_url( bbp_get_displayed_user_id() );

			// Root profile page
			} elseif ( bbp_is_single_user() ) {
				$base = bbp_get_user_profile_url( bbp_get_displayed_user_id() );

			// Page or single post
			} elseif ( is_page() || is_single() ) {
				$base = get_permalink();

			// Single topic
			} else {
				$base = get_permalink( bbp_get_topic_id() );
			}

			$base = trailingslashit( $base ) . user_trailingslashit( $wp_rewrite->pagination_base . '/%#%/' );

		// Unpretty permalinks
		} else {
			$base = add_query_arg( 'paged', '%#%' );
		}

		// Figure out total pages
		if ( true === $r['hierarchical'] ) {
			$walker      = new BBP_Walker_Reply;
			$total_pages = ceil( (int) $walker->get_number_of_root_elements( $bbp->reply_query->posts ) / (int) $replies_per_page );
		} else {
			$total_pages = ceil( (int) $bbp->reply_query->found_posts / (int) $replies_per_page );

			// Add pagination to query object
			$bbp->reply_query->pagination_links = paginate_links( apply_filters( 'bbp_replies_pagination', array(
				'base'      => $base,
				'format'    => '',
				'total'     => $total_pages,
				'current'   => (int) $bbp->reply_query->paged,
				'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
				'next_text' => is_rtl() ? '&larr;' : '&rarr;',
				'mid_size'  => 1,
				'add_args'  => ( bbp_get_view_all() ) ? array( 'view' => 'all' ) : false
			) ) );

			// Remove first page from pagination
			if ( $wp_rewrite->using_permalinks() ) {
				$bbp->reply_query->pagination_links = str_replace( $wp_rewrite->pagination_base . '/1/', '', $bbp->reply_query->pagination_links );
			} else {
				$bbp->reply_query->pagination_links = str_replace( '&#038;paged=1', '', $bbp->reply_query->pagination_links );
			}
		}
	}

	// Return object
	return apply_filters( 'rw_bbp_has_replies', $bbp->reply_query->have_posts(), $bbp->reply_query );
}



add_filter ('bbp_has_topics', 'rw_bbp_has_topics') ;
function rw_bbp_has_topics( $args = '' ) {
	global $wp_rewrite;

	/** Defaults **************************************************************/

	// Other defaults
	$default_topic_search  = !empty( $_REQUEST['ts'] ) ? $_REQUEST['ts'] : false;
	$default_show_stickies = (bool) ( bbp_is_single_forum() || bbp_is_topic_archive() ) && ( false === $default_topic_search );
	$default_post_parent   = bbp_is_single_forum() ? bbp_get_forum_id() : 'any';

	// Default argument array
	$default = array(
		'post_type'      => bbp_get_topic_post_type(), // Narrow query down to bbPress topics
		'post_parent'    => $default_post_parent,      // Forum ID
		'meta_key'       => '_bbp_last_active_time',   // Make sure topic has some last activity time
		'orderby'        => 'meta_value',              // 'meta_value', 'author', 'date', 'title', 'modified', 'parent', rand',
		'order'          => 'DESC',                    // 'ASC', 'DESC'
		'posts_per_page' => bbp_get_topics_per_page(), // Topics per page
		'paged'          => bbp_get_paged(),           // Page Number
		's'              => $default_topic_search,     // Topic Search
		'show_stickies'  => $default_show_stickies,    // Ignore sticky topics?
		'max_num_pages'  => false,                     // Maximum number of pages to show
	);
//FIX to unset 's'
	if ($default['s'] == False) unset ($default['s']) ;
	
	
	// What are the default allowed statuses (based on user caps)
	if ( bbp_get_view_all() ) {

		// Default view=all statuses
		$post_statuses = array(
			bbp_get_public_status_id(),
			bbp_get_closed_status_id(),
			bbp_get_spam_status_id(),
			bbp_get_trash_status_id()
		);

		// Add support for private status
		if ( current_user_can( 'read_private_topics' ) ) {
			$post_statuses[] = bbp_get_private_status_id();
		}

		// Join post statuses together
		$default['post_status'] = implode( ',', $post_statuses );

	// Lean on the 'perm' query var value of 'readable' to provide statuses
	} else {
		$default['perm'] = 'readable';
	}

	// Maybe query for topic tags
	if ( bbp_is_topic_tag() ) {
		$default['term']     = bbp_get_topic_tag_slug();
		$default['taxonomy'] = bbp_get_topic_tag_tax_id();
	}

	/** Setup *****************************************************************/

	// Parse arguments against default values
	$r = bbp_parse_args( $args, $default, 'has_topics' );
	// Get bbPress
	$bbp = bbpress();

	// Call the query
	$bbp->topic_query = new WP_Query( $r );
        
	// Set post_parent back to 0 if originally set to 'any'
	if ( 'any' === $r['post_parent'] )
		$r['post_parent'] = 0;

	// Limited the number of pages shown
	if ( !empty( $r['max_num_pages'] ) )
		$bbp->topic_query->max_num_pages = $r['max_num_pages'];

	/** Stickies **************************************************************/

	// Put sticky posts at the top of the posts array
	if ( !empty( $r['show_stickies'] ) && $r['paged'] <= 1 ) {

		// Get super stickies and stickies in this forum
		$stickies = bbp_get_super_stickies();

		// Get stickies for current forum
		if ( !empty( $r['post_parent'] ) ) {
			$stickies = array_merge( $stickies, bbp_get_stickies( $r['post_parent'] ) );
		}

		// Remove any duplicate stickies
		$stickies = array_unique( $stickies );

		// We have stickies
		if ( is_array( $stickies ) && !empty( $stickies ) ) {

			// Start the offset at -1 so first sticky is at correct 0 offset
			$sticky_offset = -1;

			// Loop over topics and relocate stickies to the front.
			foreach ( $stickies as $sticky_index => $sticky_ID ) {

				// Get the post offset from the posts array
				$post_offsets = wp_filter_object_list( $bbp->topic_query->posts, array( 'ID' => $sticky_ID ), 'OR', 'ID' );

				// Continue if no post offsets
				if ( empty( $post_offsets ) ) {
					continue;
				}

				// Loop over posts in current query and splice them into position
				foreach ( array_keys( $post_offsets ) as $post_offset ) {
					$sticky_offset++;

					$sticky = $bbp->topic_query->posts[$post_offset];

					// Remove sticky from current position
					array_splice( $bbp->topic_query->posts, $post_offset, 1 );

					// Move to front, after other stickies
					array_splice( $bbp->topic_query->posts, $sticky_offset, 0, array( $sticky ) );

					// Cleanup
					unset( $stickies[$sticky_index] );
					unset( $sticky );
				}

				// Cleanup
				unset( $post_offsets );
			}

			// Cleanup
			unset( $sticky_offset );

			// If any posts have been excluded specifically, Ignore those that are sticky.
			if ( !empty( $stickies ) && !empty( $r['post__not_in'] ) ) {
				$stickies = array_diff( $stickies, $r['post__not_in'] );
			}

			// Fetch sticky posts that weren't in the query results
			if ( !empty( $stickies ) ) {

				// Query to use in get_posts to get sticky posts
				$sticky_query = array(
					'post_type'   => bbp_get_topic_post_type(),
					'post_parent' => 'any',
					'meta_key'    => '_bbp_last_active_time',
					'orderby'     => 'meta_value',
					'order'       => 'DESC',
					'include'     => $stickies
				);

				// Cleanup
				unset( $stickies );

				// Conditionally exclude private/hidden forum ID's
				$exclude_forum_ids = bbp_exclude_forum_ids( 'array' );
				if ( ! empty( $exclude_forum_ids ) ) {
					$sticky_query['post_parent__not_in'] = $exclude_forum_ids;
				}

				// What are the default allowed statuses (based on user caps)
				if ( bbp_get_view_all() ) {
					$sticky_query['post_status'] = $r['post_status'];

				// Lean on the 'perm' query var value of 'readable' to provide statuses
				} else {
					$sticky_query['post_status'] = $r['perm'];
				}

				// Get all stickies
				$sticky_posts = get_posts( $sticky_query );
				if ( !empty( $sticky_posts ) ) {

					// Get a count of the visible stickies
					$sticky_count = count( $sticky_posts );

					// Merge the stickies topics with the query topics .
					$bbp->topic_query->posts       = array_merge( $sticky_posts, $bbp->topic_query->posts );

					// Adjust loop and counts for new sticky positions
					$bbp->topic_query->found_posts = (int) $bbp->topic_query->found_posts + (int) $sticky_count;
					$bbp->topic_query->post_count  = (int) $bbp->topic_query->post_count  + (int) $sticky_count;

					// Cleanup
					unset( $sticky_posts );
				}
			}
		}
	}

	// If no limit to posts per page, set it to the current post_count
	if ( -1 === $r['posts_per_page'] )
		$r['posts_per_page'] = $bbp->topic_query->post_count;

	// Add pagination values to query object
	$bbp->topic_query->posts_per_page = $r['posts_per_page'];
	$bbp->topic_query->paged          = $r['paged'];

	// Only add pagination if query returned results
	if ( ( (int) $bbp->topic_query->post_count || (int) $bbp->topic_query->found_posts ) && (int) $bbp->topic_query->posts_per_page ) {

		// Limit the number of topics shown based on maximum allowed pages
		if ( ( !empty( $r['max_num_pages'] ) ) && $bbp->topic_query->found_posts > $bbp->topic_query->max_num_pages * $bbp->topic_query->post_count )
			$bbp->topic_query->found_posts = $bbp->topic_query->max_num_pages * $bbp->topic_query->post_count;

		// If pretty permalinks are enabled, make our pagination pretty
		if ( $wp_rewrite->using_permalinks() ) {

			// User's topics
			if ( bbp_is_single_user_topics() ) {
				$base = bbp_get_user_topics_created_url( bbp_get_displayed_user_id() );

			// User's favorites
			} elseif ( bbp_is_favorites() ) {
				$base = bbp_get_favorites_permalink( bbp_get_displayed_user_id() );

			// User's subscriptions
			} elseif ( bbp_is_subscriptions() ) {
				$base = bbp_get_subscriptions_permalink( bbp_get_displayed_user_id() );

			// Root profile page
			} elseif ( bbp_is_single_user() ) {
				$base = bbp_get_user_profile_url( bbp_get_displayed_user_id() );

			// View
			} elseif ( bbp_is_single_view() ) {
				$base = bbp_get_view_url();

			// Topic tag
			} elseif ( bbp_is_topic_tag() ) {
				$base = bbp_get_topic_tag_link();

			// Page or single post
			} elseif ( is_page() || is_single() ) {
				$base = get_permalink();

			// Forum archive
			} elseif ( bbp_is_forum_archive() ) {
				$base = bbp_get_forums_url();

			// Topic archive
			} elseif ( bbp_is_topic_archive() ) {
				$base = bbp_get_topics_url();

			// Default
			} else {
				$base = get_permalink( (int) $r['post_parent'] );
			}

			// Use pagination base
			$base = trailingslashit( $base ) . user_trailingslashit( $wp_rewrite->pagination_base . '/%#%/' );

		// Unpretty pagination
		} else {
			$base = add_query_arg( 'paged', '%#%' );
		}

		// Pagination settings with filter
		$bbp_topic_pagination = apply_filters( 'bbp_topic_pagination', array (
			'base'      => $base,
			'format'    => '',
			'total'     => $r['posts_per_page'] === $bbp->topic_query->found_posts ? 1 : ceil( (int) $bbp->topic_query->found_posts / (int) $r['posts_per_page'] ),
			'current'   => (int) $bbp->topic_query->paged,
			'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
			'next_text' => is_rtl() ? '&larr;' : '&rarr;',
			'mid_size'  => 1
		) );

		// Add pagination to query object
		$bbp->topic_query->pagination_links = paginate_links( $bbp_topic_pagination );

		// Remove first page from pagination
		$bbp->topic_query->pagination_links = str_replace( $wp_rewrite->pagination_base . "/1/'", "'", $bbp->topic_query->pagination_links );
	}
        
        //=========== Fix Topic Page Topic View =============
        global $bp;
        
        if( is_array($bp->unfiltered_uri) && in_array("topic", $bp->unfiltered_uri) && bp_current_component() === "groups" )
        {                                        
            $current_post_arr = array_filter($bbp->topic_query->posts,"map_topic_posts_for_current_topic");
            $current_post_arr = array_values($current_post_arr);  

            if(is_array($current_post_arr) && count($current_post_arr)===1)
            {                        
                $bbp->topic_query->posts = $current_post_arr;                                
            }
        }                            
        
	// Return object
	return apply_filters( 'rw_bbp_has_topics', $bbp->topic_query->have_posts(), $bbp->topic_query );
}
function map_topic_posts_for_current_topic($obj)
{
    global $bp;
    $rtn = null;
    $topic_key = array_search("topic", $bp->unfiltered_uri);
    if($topic_key !== false)
    {
        $post_name = $bp->unfiltered_uri[($topic_key+1)];
        
        if($obj->post_name === $post_name)
        {
            $rtn = $obj;
            return $rtn;
        }
    }    
}

