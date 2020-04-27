<?php
/*
* Template Name: User Following Template
*
* Child Theme Name: Curriki Child Theme for Genesis 2.1
* Author: Orange Blossom Media
* Url: http://orangeblossommedia.com/
*/

// Add custom body class to the head
add_filter( 'body_class', 'curriki_user_following_add_body_class' );
function curriki_user_following_add_body_class( $classes ) {
   $classes[] = 'backend user-dashboard';
   return $classes;

}

// Execute custom style guide page
add_action( 'genesis_meta', 'curriki_custom_user_following_loop' );
function curriki_custom_user_following_loop() {
	//* Force full-width-content layout setting
	add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

	remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
	remove_action( 'genesis_loop', 'genesis_do_loop' );

	add_action( 'genesis_loop', 'curriki_user_following_body', 15 );
}


function curriki_user_following_body() {

	echo '<div class="user-following-content clearfix"><div class="wrap container_12">';

	// Access
	$following = '';

		$following .= '<div class="following-breadcrumbs breadcrumbs grid_12">Community > Following</div>';

		$following .= '<div class="actions-row grid_12 clearfix">';
			$following .= '<div class="grid_6 alpha"><button class="small-button green-button"><span class="fa fa-search"></span> Find Contacts</button><button class="small-button green-button"><span class="fa fa-plus-circle"></span> My Contacts</button></div>';
			$following .= '<div class="search-dropdown grid_6 omega"><strong>Sort by: </strong><select><option>All Members</option></select></div>';
		$following .= '</div>';

		$following .= '<div class="members clearfix">';

			//Loop through member cards
			$friends_cards = '';
			$friends_count = 0;
			while( $friends_count < 18 ) {
				$friends_cards .= '<div class="member-card card rounded-borders-full border-grey fixed_grid_2 follow">';
					$friends_cards .= '<div class="card-header">';
						$friends_cards .= '<img class="border-grey" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
						$friends_cards .= '<span class="member-name name">Member Name</span><span class="occupation">Occupation</span><span class="location">City, State, Country</span>';
					$friends_cards .= '</div>';
					$friends_cards .= '<div class="card-stats">';
						$friends_cards .= '<span class="stat"><span class="fa fa-users"></span>168</span>';
						$friends_cards .= '<span class="stat"><span class="fa fa-user"></span>7</span>';
						$friends_cards .= '<span class="stat"><span class="fa fa-comments"></span>43</span>';
						$friends_cards .= '<span class="stat"><span class="fa fa-book"></span>259</span>';
					$friends_cards .= '</div>';
					$friends_cards .= '<button class="card-button">Follow</button>';
				$friends_cards .= '</div>';

				$friends_count++;
			}

			$following .= $friends_cards;

		$following .= '</div>';

		$following .= '<div class="pagination">';
			$following .= '<a class="pagination-first" href="#"><span class="fa fa-angle-double-left"></span></a>';
			$following .= '<a class="pagination-previous" href="#"><span class="fa fa-angle-left"></span> Previous</a>';
			$following .= '<a class="pagination-num current">1</a><a class="pagination-num">2</a><a class="pagination-num">3</a><a class="pagination-num">4</a><a class="pagination-num">5</a><a class="pagination-num">6</a><a class="pagination-num">7</a><a class="pagination-num">8</a><a class="pagination-num">9</a><a class="pagination-num">10</a>';
			$following .= '<a class="pagination-next" href="#">Next <span class="fa fa-angle-right"></span></a>';
			$following .= '<a class="pagination-last" href="#"><span class="fa fa-angle-double-right"></span></a>';
		$following .= '</div>';

		echo $following;

	echo '</div></div>';

}


genesis();