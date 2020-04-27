<?php

// Execute custom style guide page
add_action( 'genesis_meta', 'curriki_custom_member_page_loop' );
function curriki_custom_member_page_loop() {

	//* Force full-width-content layout setting
	add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

	$bp = buddypress();

	// echo bp_current_action();
	// echo bp_current_component(); exit;

	if ( bp_current_action() != "just-me" && bp_current_action() != "my-friends" && bp_current_action() != "my-groups" && bp_current_action() != "my-library" ) { return; }

	remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
	remove_action( 'genesis_loop', 'genesis_do_loop' );

	add_action( 'genesis_before', 'curriki_member_page_scripts' );
	add_action( 'genesis_after_header', 'curriki_member_header', 10 );
	add_action( 'genesis_after_header', 'curriki_member_page_body', 15 );
}

function curriki_member_header() {

	global $bp;

	$user_id = 10015;  
	$avatar_url = bp_core_fetch_avatar(array('item_id' => $user_id, 'type' => 'thumb', 'width' => 103, 'height' => 103, 'class' => 'friend-avatar','html'=>false));
	$full_name = bp_get_displayed_user_fullname();
	$member_url = bp_displayed_user_domain();
	$city = cur_get_user_nonwp_data ( $user_id, 'city' );
	$state = cur_get_user_nonwp_data ( $user_id, 'state' );
	$country = cur_get_user_nonwp_data ( $user_id, 'country' );
	if ( $city || $state || $country ) {
		$location = $city . ', ' . $state . ' ' . $country;
	}
	$bio = cur_get_user_nonwp_data ( $user_id, 'bio' );
	$profession = false;

	$member_header = '<div class="member-header page-header">';
		$member_header .= '<div class="wrap container_12">';
			$member_header .= '<div class="member-join page-join grid_2">';
				$member_header .= '<img class="circle aligncenter" src="' . $avatar_url . '" alt="member-name" />';
				$member_header .= '<button class="green-button">Follow</button>';
			$member_header .= '</div>';
			$member_header .= '<div class="member-info page-info grid_10">';
				$member_header .= '<h3 class="member-title page-title">'.$full_name.'</h3>';
				$member_header .= '<div class="member-profile">';
				if ( $profession ) {
					$member_header .= $profession . '-';
				}
				if ( $location ) {
					$member_header .= $location;
				}
				$member_header .= '</div>';
				$member_header .= '<div class="member-link page-link"><a href="'.$member_url.'">'.$member_url.'</a></div>';
				$member_header .= '<div id="member-info-accordion">';
					$member_header .= '<h4 class="member-more-info fa"> More Information</h4>';
					$member_header .= '<div>';
						$member_header .= '<p>'.$bio.'</p>';
						$member_header .= '';
						$member_header .= '<ul class="info">';
							$member_header .= '<div class="grid_3">';
								$member_header .= '<li>Subjects of Interest:<ul><li>Career & Technical Education</li><li>Educational Technology</li><li>Information & Media</li><li>Language Arts</li></ul></li>';
							$member_header .= '</div>';
							$member_header .= '<div class="grid_3">';
								$member_header .= '<li>Organization:<ul><li>University of Indiana</li></ul></li>';
								$member_header .= '<li>Website/Blogs<ul><li>http://jennamcwil</li></ul></li>';
							$member_header .= '</div>';
							$member_header .= '<div class="grid_3">';
									$member_header .= '<li>Language:<ul><li>Mathematics</li><li>Science</li></ul></li>';
							$member_header .= '<li>Member Policy:<ul><li>Mathematics</li><li>Science</li></ul></li>';
							$member_header .= '</div>';
							$member_header .= '<div class="grid_3">';
								$member_header .= '<li>Joined:<ul><li>August, 12 2012</li></ul></li>';
								$member_header .= '<li>Last Activity:<ul><li>December, 28 2014</li></ul></li>';
							$member_header .= '</div>';
						$member_header .= '</ul>';
					$member_header .= '</div>';
				$member_header .= '</div>';
			$member_header .= '</div>';
		$member_header .= '</div>';
	$member_header .= '</div>';

	echo $member_header;

}

function curriki_member_page_body( $activity_tab_desired = false ) {

	$user_id = bp_displayed_user_id();

	$bp = buddypress();

	$member_url = bp_displayed_user_domain();
	$friend_count = friends_get_total_friend_count ( $user_id );
	$group_count = groups_get_total_member_count ( $user_id );
	$library_count = 0;

	echo '<div id="member-tabs">';

		$member_tabs = '';
		$member_tabs .= '<div class="member-tabs page-tabs"><div class="wrap container_12">';
		$member_tabs .= '<ul>';
			$member_tabs .= '<li><a href="'.$member_url.'"><span class="tab-icon fa fa-home"></span> <span class="tab-text">Activity</span></a></li>';
			$member_tabs .= '<li><a href="'.$member_url.'friends"><span class="tab-icon fa fa-user"></span> <span class="tab-text">Friends <span class="member-number">('.$friend_count.')</span></span></a></li>';
			$member_tabs .= '<li><a href="'.$member_url.'groups"><span class="tab-icon fa fa-users"></span> <span class="tab-text">Groups <span class="member-number">('.$group_count.')</span></span></a></li>';
			$member_tabs .= '<li><a href="'.$member_url.'library"><span class="tab-icon fa fa-list"></span> <span class="tab-text">Library <span class="member-number">('.$library_count.')</span></span></a></li>';
		$member_tabs .= '</ul>';
		$member_tabs .= '</div></div>';

		echo $member_tabs;

		if ( bp_current_action() == "just-me" ) { $tab_desired = "activity"; }
		if ( bp_current_action() == "my-friends" ) { $tab_desired = "friends"; }
		if ( bp_current_action() == "my-groups" ) { $tab_desired = "groups"; }
		if ( bp_current_action() == "my-library" ) { $tab_desired = "library"; }

		echo '<div class="member-content dashboard-tabs-content"><div class="wrap container_12">';

		if ( !$tab_desired || ( $tab_desired && $tab_desired == "activity" ) ) { 

			// Activity
			$activity_tab = '';
			$activity_tab .= '<div id="activity" class="tab-contents">';
				$activity_tab .= '<div class="activity-sidebar page-sidebar grid_2">';
					$activity_tab .= '<h4 class="sidebar-title">Friends</h4>';

					$activity_tab .= '<div class="friends member-card card rounded-borders-full border-grey">';
					
					if ( $friend_count == 0 ) { 

					$activity_tab .= '<p>This user currently has no friends.</p>';

					} else { 


						$activity_tab .= '<ul>';
							$activity_tab .= '<li class="member">';
								$activity_tab .= '<img class="border-grey" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
								$activity_tab .= '<div class="member-info"><span class="member-name name">Member Name</span><span class="occupation">Occupation</span><span class="location">City, State, Country</span></div>';
							$activity_tab .= '</li>';
							$activity_tab .= '<li class="member">';
								$activity_tab .= '<img class="border-grey" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
								$activity_tab .= '<div class="member-info"><span class="member-name name">MemberLong NameLong</span><span class="occupation">Occupation</span><span class="location">City, State, Country</span></div>';
							$activity_tab .= '</li>';
							$activity_tab .= '<li class="member">';
								$activity_tab .= '<img class="border-grey" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
								$activity_tab .= '<div class="member-info"><span class="member-name name">Member Name</span><span class="occupation">Occupation</span><span class="location">City, State, Country</span></div>';
							$activity_tab .= '</li>';
						$activity_tab .= '</ul>';
						$activity_tab .= '<a href="#friends"><div class="card-button">See All Friends</div></a>';

					} 

					$activity_tab .= '</div>';
					$activity_tab .= '<h4 class="sidebar-title">Groups</h4>';
					$activity_tab .= '<div class="groups card rounded-borders-full border-grey">';

					if ( $friend_count == 0 ) { 

					$activity_tab .= '<p>This user currently has no groups.</p>';

					} else { 

						$activity_tab .= '<ul class="discussion">';
							$activity_tab .= '<li class="group">';
								$activity_tab .= '<img class="border-grey" src="' . get_stylesheet_directory_uri() . '/images/group-icon-sample.png" alt="member-name" />';
								$activity_tab .= '<div class="group-info"><span class="group-name name">Group Name</div>';
							$activity_tab .= '</li>';
							$activity_tab .= '<li class="group">';
								$activity_tab .= '<img class="border-grey" src="' . get_stylesheet_directory_uri() . '/images/group-icon-sample.png" alt="member-name" />';
								$activity_tab .= '<div class="group-info"><span class="group-name name">Group Names Can Be Very Long</div>';
							$activity_tab .= '</li>';
							$activity_tab .= '<li class="group">';
								$activity_tab .= '<img class="border-grey" src="' . get_stylesheet_directory_uri() . '/images/group-icon-sample.png" alt="member-name" />';
								$activity_tab .= '<div class="group-info"><span class="group-name name">Group Name</div>';
							$activity_tab .= '</li>';
						$activity_tab .= '</ul>';
						$activity_tab .= '<a href="#groups"><div class="card-button">See All Groups</div></a>';

					}

					$activity_tab .= '</div>';
				$activity_tab .= '</div>';
				$activity_tab .= '<div class="activity-content grid_10">';
					$activity_tab .= '<div class="group-search page-search">';
						$activity_tab .= '<div class="search-input grid_6 alpha"><div class="search-field"><input type="text" class="rounded-borders-left" placeholder="Search"></div><div class="search-button"><button type="submit" class="rounded-borders-right"><span class="search-button-icon fa fa-search"></span></button></div></div>';
						$activity_tab .= '<div class="search-dropdown grid_4 omega"><select><option>English</option></select></div>';
					$activity_tab .= '</div>';
					$activity_tab .= '<div class="group-activity-container page-container rounded-borders-full border-grey">';

					ob_start(); ?>

					<?php do_action( 'bp_before_member_activity_post_form' ); ?>

					<?php
					if ( is_user_logged_in() && bp_is_my_profile() && ( !bp_current_action() || bp_is_current_action( 'just-me' ) ) )
						gconnect_locate_template( array( 'activity/post-form' ), true );

					do_action( 'bp_after_member_activity_post_form' );
					do_action( 'bp_before_member_activity_content' ); ?>

	                <div class="entry-content">

	                    <div id="buddypress">

							<div class="activity" role="main">

								<?php gconnect_locate_template( array( 'activity/activity-loop.php' ), true ); ?>						

							</div>

						</div>

					</div><!-- .activity -->

					<?php do_action( 'bp_after_member_activity_content' ); ?>

					<?php

						$member_activity = ob_get_clean();

						// Loop through group activity
						/* $member_activity = '';
						$member_activity_count = 0;
						while( $member_activity_count < 4 ) {
							$member_activity .= '<div class="group-activity-card page-activity-card">';
								$member_activity .= '<div class="group-activity-member page-activity-member">';
									$member_activity .= '<img class="border-grey circle" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
								$member_activity .= '</div>';
								$member_activity .= '<div class="group-activity page-activity">';
									$member_activity .= '<div class="group-activity-header page-activity-header">';
										$member_activity .= '<div class="group-activity-info page-activity-info">';
											$member_activity .= '<a href="#">Firstname Lastname</a> contributed to <a href="#">This Group Name</a>';
										$member_activity .= '</div>';
										$member_activity .= '<div class="group-activity-time page-activity-time">';
											$member_activity .= 'August 14, 2014  5:15 PM EST';
										$member_activity .= '</div>';
									$member_activity .= '</div>';
									$member_activity .= '<div class="group-activity-body page-activity-body resource-pdf border-grey">';
										$member_activity .= '<div class="group-activity-body-content page-activity-body-content">';
											$member_activity .= '<a class="resource-name" href="#">This Resource Name</a>';
											$member_activity .= 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less...';
											$member_activity .= '<div class="rate-align"><a href="#">Rate Resource</a><a href="#">Align to Standards</a></div>';
										$member_activity .= '</div>';
									$member_activity .= '</div>';
								$member_activity .= '</div>';
							$member_activity .= '</div>';

							$member_activity_count++;
						} */
						$activity_tab .= $member_activity;

						$activity_tab .= '<a class="view-more" href="#">View More</a>';
					$activity_tab .= '</div>';
				$activity_tab .= '</div>';
			$activity_tab .= '</div>';

			echo $activity_tab;

			}

			if ( $tab_desired && $tab_desired == "friends" ) { 

			// Friends
			$friends_tab = '';
			$friends_tab .= '<div id="friends" class="tab-contents">';
				$friends_tab .= '<div class="search-sort clearfix">';
					$friends_tab .= '<div class="search-dropdown grid_4 omega"><strong>Sort by: </strong><select><option>All Members</option></select></div>';
				$friends_tab .= '</div>';

				$friends_tab .= '<div class="members">';

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

					$friends_tab .= $friends_cards;

				$friends_tab .= '</div>';

				$friends_tab .= '<div class="pagination">';
					$friends_tab .= '<a class="pagination-first" href="#"><span class="fa fa-angle-double-left"></span></a>';
					$friends_tab .= '<a class="pagination-previous" href="#"><span class="fa fa-angle-left"></span> Previous</a>';
					$friends_tab .= '<a class="pagination-num current">1</a><a class="pagination-num">2</a><a class="pagination-num">3</a><a class="pagination-num">4</a><a class="pagination-num">5</a><a class="pagination-num">6</a><a class="pagination-num">7</a><a class="pagination-num">8</a><a class="pagination-num">9</a><a class="pagination-num">10</a>';
					$friends_tab .= '<a class="pagination-next" href="#">Next <span class="fa fa-angle-right"></span></a>';
					$friends_tab .= '<a class="pagination-last" href="#"><span class="fa fa-angle-double-right"></span></a>';
				$friends_tab .= '</div>';
			$friends_tab .= '</div>';

			echo $friends_tab;

			} 

			if ( $tab_desired && $tab_desired == "groups" ) { 

			// Groups
			$groups_tab = '';
			$groups_tab .= '<div id="groups" class="tab-contents">';
				$groups_tab .= '<div class="search-sort clearfix">';
					$groups_tab .= '<div class="search-dropdown grid_4 omega"><strong>Sort by: </strong><select><option>All Groups</option></select></div>';
				$groups_tab .= '</div>';

				$groups_tab .= '<div class="groups">';

				//Loop through group cards
				$groups_cards = '';
				$groups_count = 0;
				while( $groups_count < 12 ) {

					$groups_cards .= '<div class="group-card card rounded-borders-full border-grey fixed_grid_3 join-group">';
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
						$groups_cards .= '<button class="card-button">Join Group</button>';
					$groups_cards .= '</div>';

					$groups_count++;
				}

				$groups_tab .= $groups_cards;

				$groups_tab .= '</div>';

				$groups_tab .= '<div class="pagination">';
					$groups_tab .= '<a class="pagination-first" href="#"><span class="fa fa-angle-double-left"></span></a>';
					$groups_tab .= '<a class="pagination-previous" href="#"><span class="fa fa-angle-left"></span> Previous</a>';
					$groups_tab .= '<a class="pagination-num current">1</a><a class="pagination-num">2</a><a class="pagination-num">3</a><a class="pagination-num">4</a><a class="pagination-num">5</a><a class="pagination-num">6</a><a class="pagination-num">7</a><a class="pagination-num">8</a><a class="pagination-num">9</a><a class="pagination-num">10</a>';
					$groups_tab .= '<a class="pagination-next" href="#">Next <span class="fa fa-angle-right"></span></a>';
					$groups_tab .= '<a class="pagination-last" href="#"><span class="fa fa-angle-double-right"></span></a>';
				$groups_tab .= '</div>';
			$groups_tab .= '</div>';

			echo $groups_tab;

			} 

			if ( $tab_desired && $tab_desired == "library" ) { 

			// Library
			$library_tab = '';
			$library_tab .= '<div id="library" class="tab-contents">';
				$library_tab .= '<div class="search-sort clearfix">';
					$library_tab .= '<div class="search-dropdown grid_4 omega"><strong>Sort by: </strong><select><option>All Resources</option></select></div>';
				$library_tab .= '</div>';

				//Loop through Collection cards
				$collection_cards = '';
				$collection_count = 0;
				while( $collection_count < 10 ) {
					$collection_cards .= '<div class="collection-card card rounded-borders-full border-grey library-collection">';
						$collection_cards .= '<div class="collection-body">';
							$collection_cards .= '<div class="collection-image">';
								$collection_cards .= '<img src="http://placehold.it/120x100" alt="group-name" />';
							$collection_cards .= '</div>';
							$collection_cards .= '<div class="collection-body-inner">';
								$collection_cards .= '<div class="collection-body-title">';
									$collection_cards .= '<div class="collection-title">';
										$collection_cards .= '<h3><a href="#">Name of Collection</a></h3> by <span class="member-name name">Member Name</span>';
										$collection_cards .= '<span class="collection-grade"><strong>Grades 3-5</strong> (ages 8-10)</span>';
									$collection_cards .= '</div>';
									// $collection_cards .= '<div class="collection-author">';
									// 	$collection_cards .= '<img class="alignleft" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
									// 	$collection_cards .= '<span class="member-name name vertical-align">Member Name</span>';
									// $collection_cards .= '</div>';
								$collection_cards .= '</div>';
								$collection_cards .= '<div class="collection-body-content">';
									$collection_cards .= '<div class="collection-description">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the standard dummy text ever since the 1500s, when an unknown...,</div>';
									$collection_cards .= '<div class="collection-rating rating"><span class="member-rating-title">Member Rating</span>';
										$collection_cards .= '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>';
										$collection_cards .= '<a href="#">Rate this collection</a>';
									$collection_cards .= '</div>';
									$collection_cards .= '<div class="collection-curriki-rating curriki-rating"><span class="curriki-rating-title">Curriki Rating</span><span class="rating-badge">3</span></div>';
								$collection_cards .= '</div>';
							$collection_cards .= '</div>';
						$collection_cards .= '</div>';
						$collection_cards .= '<div class="collection-actions" id="collection-tabs">';
							$collection_cards .= '<div class="more-collection-info"><span class="fa fa-caret-down"></span> More Info</div>';
							$collection_cards .= '<div class="share-collection"><span class="fa fa-share-alt-square"></span> Share</div>';
							$collection_cards .= '<div class="add-to-library"><span class="fa fa-plus-circle"></span> Add <span class="hide-small">to my Library</span></div>';
						$collection_cards .= '</div>';
					$collection_cards .= '</div>';

					$collection_count++;
				}

				$library_tab .= $collection_cards;

				$library_tab .= '<div class="pagination">';
					$library_tab .= '<a class="pagination-first" href="#"><span class="fa fa-angle-double-left"></span></a>';
					$library_tab .= '<a class="pagination-previous" href="#"><span class="fa fa-angle-left"></span> Previous</a>';
					$library_tab .= '<a class="pagination-num current">1</a><a class="pagination-num">2</a><a class="pagination-num">3</a><a class="pagination-num">4</a><a class="pagination-num">5</a><a class="pagination-num">6</a><a class="pagination-num">7</a><a class="pagination-num">8</a><a class="pagination-num">9</a><a class="pagination-num">10</a>';
					$library_tab .= '<a class="pagination-next" href="#">Next <span class="fa fa-angle-right"></span></a>';
					$library_tab .= '<a class="pagination-last" href="#"><span class="fa fa-angle-double-right"></span></a>';
				$library_tab .= '</div>';
			$library_tab .= '</div>';

			echo $library_tab;

			}

		echo '</div></div>';

	echo '</div>';



}
