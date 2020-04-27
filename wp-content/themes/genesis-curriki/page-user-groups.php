<?php
/*
* Template Name: User Groups Template
*
* Child Theme Name: Curriki Child Theme for Genesis 2.1
* Author: Orange Blossom Media
* Url: http://orangeblossommedia.com/
*/

// Add custom body class to the head
add_filter( 'body_class', 'curriki_user_groups_add_body_class' );
function curriki_user_groups_add_body_class( $classes ) {
   $classes[] = 'backend user-dashboard';
   return $classes;

}

// Execute custom style guide page
add_action( 'genesis_meta', 'curriki_custom_user_groups_loop' );
function curriki_custom_user_groups_loop() {
	//* Force full-width-content layout setting
	add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

	remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
	remove_action( 'genesis_loop', 'genesis_do_loop' );

	add_action( 'genesis_loop', 'curriki_user_groups_body', 15 );
}


function curriki_user_groups_body() {

	echo '<div class="user-groups-content clearfix"><div class="wrap container_12">';

	// Groups
	$group_page = '';

		$group_page .= '<div class="group-breadcrumbs breadcrumbs grid_12">Community > My Groups</div>';

		$group_page .= '<div class="actions-row grid_12 clearfix">';
			$group_page .= '<div class="grid_6 alpha"><button class="small-button green-button"><span class="fa fa-search"></span> Find New Groups</button><button class="small-button green-button"><span class="fa fa-plus-circle"></span> Start a Group</button></div>';
			$group_page .= '<div class="search-dropdown grid_6 omega"><strong>Sort by: </strong><select><option>All Groups</option></select></div>';
		$group_page .= '</div>';

		$group_page .= '<div class="groups clearfix">';
		//Loop through group cards
		$groups_cards = '';
		$groups_count = 0;
		while( $groups_count < 12 ) {

			$groups_cards .= '<div class="group-card card rounded-borders-full border-grey fixed_grid_3 group-member">';
				$groups_cards .= '<div class="card-header">';
					$groups_cards .= '<img class="border-grey" src="' . get_stylesheet_directory_uri() . '/images/group-icon-sample.png" alt="group-name" />';
					$groups_cards .= '<span class="group-name name">Group Name</span>';
				$groups_cards .= '</div>';
				$groups_cards .= '<div class="card-stats">';
					$groups_cards .= '<span class="stat"><span class="fa fa-users"></span>168</span>';
					$groups_cards .= '<span class="stat"><span class="fa fa-comments"></span>43</span>';
					$groups_cards .= '<span class="stat"><span class="fa fa-book"></span>259</span>';
				$groups_cards .= '</div>';
				$groups_cards .= '<div class="card-description">Group discription lorem ipsum dolor sit amet, adipiscing elit. Suspendisse fringilla nisl et velit aliquet faucibus. Morbi...</div>';
				$groups_cards .= '<button class="card-button">Leave Group</button>';
			$groups_cards .= '</div>';

			$groups_count++;
		}

		$group_page .= $groups_cards;
		$group_page .= '</div>';

		$group_page .= '<div class="pagination">';
			$group_page .= '<a class="pagination-first" href="#"><span class="fa fa-angle-double-left"></span></a>';
			$group_page .= '<a class="pagination-previous" href="#"><span class="fa fa-angle-left"></span> Previous</a>';
			$group_page .= '<a class="pagination-num current">1</a><a class="pagination-num">2</a><a class="pagination-num">3</a><a class="pagination-num">4</a><a class="pagination-num">5</a><a class="pagination-num">6</a><a class="pagination-num">7</a><a class="pagination-num">8</a><a class="pagination-num">9</a><a class="pagination-num">10</a>';
			$group_page .= '<a class="pagination-next" href="#">Next <span class="fa fa-angle-right"></span></a>';
			$group_page .= '<a class="pagination-last" href="#"><span class="fa fa-angle-double-right"></span></a>';
		$group_page .= '</div>';

		echo $group_page;

	echo '</div></div>';

}


genesis();