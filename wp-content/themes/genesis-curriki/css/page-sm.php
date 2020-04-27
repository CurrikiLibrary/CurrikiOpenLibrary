<?php
/*
* Template Name: Search Members Template
*
* Child Theme Name: Curriki Child Theme for Genesis 2.1
* Author: Orange Blossom Media
* Url: http://orangeblossommedia.com/
*/

// Add custom body class to the head
add_filter( 'body_class', 'curriki_search_members_page_add_body_class' );
function curriki_search_members_page_add_body_class( $classes ) {
   $classes[] = 'backend search-page';
   return $classes;

}

// Execute custom style guide page
add_action( 'genesis_meta', 'curriki_custom_search_members_page_loop' );
function curriki_custom_search_members_page_loop() {
	//* Force full-width-content layout setting
	add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

	remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
	remove_action( 'genesis_loop', 'genesis_do_loop' );

	add_action( 'genesis_loop', 'curriki_search_members_page_body', 15 );
}


function curriki_search_members_page_body() {

	echo '<div class="search-content"><div class="wrap container_12">';

		$search_content = '';

		$search_content .= '<div class="search-bar grid_12">';
			$search_content .= '<div class="search-tabs">';
				$search_content .= '<div class="resource-tab tab rounded-borders-top"><span class="tab-icon fa fa-book strong"></span><span class="tab-text"><strong>Resources</strong> (9,654)</span></div>';
				$search_content .= '<div class="groups-tab tab rounded-borders-top"><span class="tab-icon fa fa-users strong"></span><span class="tab-text"><strong>Groups</strong> (22)</span></div>';
				$search_content .= '<div class="members-tab tab rounded-borders-top selected"><span class="tab-icon fa fa-user strong"></span><span class="tab-text"><strong>Members</strong> (713)</span></div>';
				$search_content .= '<div class="search-tips"><a>Search Tips</a></div>';
			$search_content .= '</div>';
			$search_content .= '<div class="search-input">';
				$search_content .= '<div class="search-field"><input type="text" class="rounded-borders-left" placeholder="Start Searching" /></div>';
				$search_content .= '<div class="search-button"><button type="submit" class="rounded-borders-right"><span class="search-button-icon fa fa-search"></span>Search</button></div>';
			$search_content .= '</div>';
			$search_content .= '<div class="search-options rounded-borders-bottom border-grey">';
				$search_content .= '<div class="search-dropdown rounded-borders-full border-grey">English<span class="search-dropdown-icon fa fa-caret-down"></span></div>';
				$search_content .= '<div class="show-hide-options"><span class="show-hide-icon fa fa-plus-circle"></span>More Search Options</div>';
			$search_content .= '</div>';
		$search_content .= '</div>';

		$search_content .= '<div class="search-results-showing grid_12 clearfix">';
			$search_content .= '<div class="search-term grid_8 alpha"><h4>Showing results for "Math", "Geometry", "First Grade"</h4>Did you mean to search "Math Geometry First Grade"?</div>';
			$search_content .= '<div class="search-dropdown grid_4 omega"><strong>Sort by: </strong><select><option>All Members</option></select></div>';
		$search_content .= '</div>';

		$search_content .= '<div class="members grid_12">';

				//Loop through member cards
				$members_cards = '';
				$members_count = 0;
				while( $members_count < 18 ) {
					$members_cards .= '<div class="member-card card rounded-borders-full border-grey fixed_grid_2 follow">';
						$members_cards .= '<div class="card-header">';
							$members_cards .= '<img class="border-grey" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
							$members_cards .= '<span class="member-name name">Member Name</span><span class="occupation">Occupation</span><span class="location">City, State, Country</span>';
						$members_cards .= '</div>';
						$members_cards .= '<div class="card-stats">';
							$members_cards .= '<span class="stat"><span class="fa fa-users"></span>168</span>';
							$members_cards .= '<span class="stat"><span class="fa fa-user"></span>7</span>';
							$members_cards .= '<span class="stat"><span class="fa fa-comments"></span>43</span>';
							$members_cards .= '<span class="stat"><span class="fa fa-book"></span>259</span>';
						$members_cards .= '</div>';
						$members_cards .= '<button class="card-button">Follow</button>';
					$members_cards .= '</div>';

					$members_count++;
				}

				$search_content .= $members_cards;

		$search_content .= '</div>';

		$search_content .= '<div class="pagination">';
			$search_content .= '<a class="pagination-first" href="#"><span class="fa fa-angle-double-left"></span></a>';
			$search_content .= '<a class="pagination-previous" href="#"><span class="fa fa-angle-left"></span> Previous</a>';
			$search_content .= '<a class="pagination-num current">1</a><a class="pagination-num">2</a><a class="pagination-num">3</a><a class="pagination-num">4</a><a class="pagination-num">5</a><a class="pagination-num">6</a><a class="pagination-num">7</a><a class="pagination-num">8</a><a class="pagination-num">9</a><a class="pagination-num">10</a>';
			$search_content .= '<a class="pagination-next" href="#">Next <span class="fa fa-angle-right"></span></a>';
			$search_content .= '<a class="pagination-last" href="#"><span class="fa fa-angle-double-right"></span></a>';
		$search_content .= '</div>';

		echo $search_content;

	echo '</div></div>';

}


genesis();