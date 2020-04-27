<?php
/*
* Template Name: Group Page Template
*
* Child Theme Name: Curriki Child Theme for Genesis 2.1
* Author: Orange Blossom Media
* Url: http://orangeblossommedia.com/
*/

// Add custom body class to the head
add_filter( 'body_class', 'curriki_group_page_add_body_class' );
function curriki_group_page_add_body_class( $classes ) {
   $classes[] = 'backend group-page';
   return $classes;

}

// Execute custom style guide page
add_action( 'genesis_meta', 'curriki_custom_group_page_loop' );
function curriki_custom_group_page_loop() {
	//* Force full-width-content layout setting
	add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

	remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
	remove_action( 'genesis_loop', 'genesis_do_loop' );

	add_action( 'genesis_before', 'curriki_group_page_scripts' );
	add_action( 'genesis_after_header', 'curriki_group_header', 10 );
	add_action( 'genesis_after_header', 'curriki_group_page_body', 15 );
}

function curriki_group_page_scripts() {

	// Enqueue JQuery Tab and Accordion scripts
   	wp_enqueue_script( 'jquery-ui-tabs' );
   	wp_enqueue_script( 'jquery-ui-accordion' );

	?>
	<script>
		(function( $ ) {

			"use strict";

			$(function() {

			  $( "#group-tabs" ).tabs();

			  var icons = {
			    header: "fa-plus-circle",
			    activeHeader: "fa-minus-circle"
			  };

			  $( "#group-info-accordion" ).accordion({
			    collapsible: true,
			    icons: icons,
			    active: false
			  });


			  $( "#toggle" ).button().click(function() {
			    if ( $( "#group-infoaccordion" ).accordion( "option", "icons" ) ) {
			      $( "#group-infoaccordion" ).accordion( "option", "icons", null );
			    } else {
			      $( "#group-infoaccordion" ).accordion( "option", "icons", icons );
			    }
			  });

			});

		}(jQuery));
	</script>
	<?php
}


function curriki_group_header() {

	$group_header = '<div class="group-header page-header">';
		$group_header .= '<div class="wrap container_12">';
			$group_header .= '<div class="group-join page-join grid_2">';
				$group_header .= '<img class="circle aligncenter" src="' . get_stylesheet_directory_uri() . '/images/group-icon-sample.png" alt="group-name" />';
				$group_header .= '<button class="green-button">Join Group</button>';
			$group_header .= '</div>';
			$group_header .= '<div class="group-info page-info grid_10">';
				$group_header .= '<h3 class="group-title page-title">Group Name Here</h3>';
				$group_header .= '<div class="group-link page-link">Website Address: <a href="#">http://BAKEDevMathFC.groups.curriki.org</a></div>';
				$group_header .= '<div id="group-info-accordion">';
					$group_header .= '<h4 class="group-more-info fa"> More Information</h4>';
					$group_header .= '<div>';
						$group_header .= '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages.</p>';
						$group_header .= '';
						$group_header .= '<ul class="info">';
							$group_header .= '<div class="grid_3">';
								$group_header .= '<li>Subjects:<ul><li>Mathematics</li><li>Science</li></ul></li>';
							$group_header .= '</div>';
							$group_header .= '<div class="grid_3">';
								$group_header .= '<li>Education Levels:<ul><li>Mathematics</li><li>Science</li></ul></li>';
							$group_header .= '</div>';
							$group_header .= '<div class="grid_3">';
									$group_header .= '<li>Language:<ul><li>Mathematics</li><li>Science</li></ul></li>';
							$group_header .= '<li>Member Policy:<ul><li>Mathematics</li><li>Science</li></ul></li>';
							$group_header .= '</div>';
							$group_header .= '<div class="grid_3">';
								$group_header .= '<li>Created:<ul><li>August, 12 2012</li></ul></li>';
								$group_header .= '<li>Last Activity:<ul><li>December, 28 2014</li></ul></li>';
							$group_header .= '</div>';
						$group_header .= '</ul>';
					$group_header .= '</div>';
				$group_header .= '</div>';
			$group_header .= '</div>';
		$group_header .= '</div>';
	$group_header .= '</div>';

	echo $group_header;

}


function curriki_group_page_body() {


	echo '<div id="group-tabs">';

		$group_tabs = '';
		$group_tabs .= '<div class="group-tabs page-tabs"><div class="wrap container_12">';
		$group_tabs .= '<ul>';
			$group_tabs .= '<li><a href="#activity"><span class="tab-icon fa fa-home"></span> <span class="tab-text">Activity</span></a></li>';
			$group_tabs .= '<li><a href="#members"><span class="tab-icon fa fa-user"></span> <span class="tab-text">Members <span class="group-number">(244)</span></span></a></li>';
			$group_tabs .= '<li><a href="#resources"><span class="tab-icon fa fa-book"></span> <span class="tab-text">Resources <span class="group-number">(46)</span></span></a></li>';
			$group_tabs .= '<li><a href="#forums"><span class="tab-icon fa fa-comments"></span> <span class="tab-text">Forums <span class="group-number">(12)</span></span></a></li>';
		$group_tabs .= '</ul>';
		$group_tabs .= '</div></div>';

		echo $group_tabs;


		echo '<div class="group-content dashboard-tabs-content"><div class="wrap container_12">';

			$activity_tab = '';
			$activity_tab .= '<div id="activity" class="tab-contents">';
				$activity_tab .= '<div class="activity-sidebar page-sidebar grid_2">';
					$activity_tab .= '<h4 class="sidebar-title">Recently Active</h4>';
					$activity_tab .= '<div class="recently-active member-card card rounded-borders-full border-grey">';
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
						$activity_tab .= '<a href="#members"><div class="card-button">Browse All Members</div></a>';
					$activity_tab .= '</div>';
					$activity_tab .= '<h4 class="sidebar-title">Recent Discussions</h4>';
					$activity_tab .= '<div class="recent-discussion card rounded-borders-full border-grey">';
						$activity_tab .= '<ul class="discussion">';
							$activity_tab .= '<li><a href="#">Discussion Topic Can be Very Long at Times, so We Need Space</a></li>';
							$activity_tab .= '<li><a href="#">Discussion Topic Goes Here</a></li>';
							$activity_tab .= '<li><a href="#">Discussion Topic Can be Very Long at Times, so We Need Space</a></li>';
						$activity_tab .= '</ul>';
						$activity_tab .= '<a href="#forums"><div class="card-button">Browse All Conversations</div></a>';
					$activity_tab .= '</div>';
				$activity_tab .= '</div>';
				$activity_tab .= '<div class="activity-content grid_10">';
					$activity_tab .= '<div class="group-search page-search">';
						$activity_tab .= '<div class="search-input grid_6 alpha"><div class="search-field"><input type="text" class="rounded-borders-left" placeholder="Search"></div><div class="search-button"><button type="submit" class="rounded-borders-right"><span class="search-button-icon fa fa-search"></span></button></div></div>';
						$activity_tab .= '<div class="search-dropdown grid_4 omega"><select><option>English</option></select></div>';
					$activity_tab .= '</div>';
					$activity_tab .= '<div class="group-activity-container page-container rounded-borders-full border-grey">';

						// Loop through group activity
						$group_activity = '';
						$group_activity_count = 0;
						while( $group_activity_count < 4 ) {
							$group_activity .= '<div class="group-activity-card page-activity-card">';
								$group_activity .= '<div class="group-activity-member page-activity-member">';
									$group_activity .= '<img class="border-grey circle" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
								$group_activity .= '</div>';
								$group_activity .= '<div class="group-activity page-activity">';
									$group_activity .= '<div class="group-activity-header page-activity-header">';
										$group_activity .= '<div class="group-activity-info page-activity-info">';
											$group_activity .= '<a href="#">Mark Bunker</a> contributed to <a href="#">This Group Name</a>';
										$group_activity .= '</div>';
										$group_activity .= '<div class="group-activity-time page-activity-time">';
											$group_activity .= 'August 14, 2014  5:15 PM EST';
										$group_activity .= '</div>';
									$group_activity .= '</div>';
									$group_activity .= '<div class="group-activity-body page-activity-body resource-pdf border-grey">';
										$group_activity .= '<div class="group-activity-body-content page-activity-body-content">';
											$group_activity .= '<a class="resource-name" href="#">This Resource Name</a>';
											$group_activity .= 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less...';
											$group_activity .= '<div class="rate-align"><a href="#">Rate Resource</a><a href="#">Align to Standards</a></div>';
										$group_activity .= '</div>';
									$group_activity .= '</div>';
								$group_activity .= '</div>';
							$group_activity .= '</div>';

							$group_activity_count++;
						}
						$activity_tab .= $group_activity;

						$activity_tab .= '<a class="view-more" href="#">View More</a>';
					$activity_tab .= '</div>';
				$activity_tab .= '</div>';
			$activity_tab .= '</div>';

			echo $activity_tab;



			$member_tab = '';
			$member_tab .= '<div id="members" class="tab-contents">';
				$member_tab .= '<div class="search-sort clearfix">';
					$member_tab .= '<div class="search-dropdown grid_4 omega"><strong>Sort by: </strong><select><option>All Members</option></select></div>';
				$member_tab .= '</div>';

				$member_tab .= '<div class="members">';

					//Loop through member cards
					$member_cards = '';
					$member_count = 0;
					while( $member_count < 18 ) {
						$member_cards .= '<div class="member-card card rounded-borders-full border-grey fixed_grid_2 follow">';
							$member_cards .= '<div class="card-header">';
								$member_cards .= '<img class="border-grey" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
								$member_cards .= '<span class="member-name name">Member Name</span><span class="occupation">Occupation</span><span class="location">City, State, Country</span>';
							$member_cards .= '</div>';
							$member_cards .= '<div class="card-stats">';
								$member_cards .= '<span class="stat"><span class="fa fa-users"></span>168</span>';
								$member_cards .= '<span class="stat"><span class="fa fa-user"></span>7</span>';
								$member_cards .= '<span class="stat"><span class="fa fa-comments"></span>43</span>';
								$member_cards .= '<span class="stat"><span class="fa fa-book"></span>259</span>';
							$member_cards .= '</div>';
							$member_cards .= '<button class="card-button">Follow</button>';
						$member_cards .= '</div>';

						$member_count++;
					}

					$member_tab .= $member_cards;

				$member_tab .= '</div>';

				$member_tab .= '<div class="pagination">';
					$member_tab .= '<a class="pagination-first" href="#"><span class="fa fa-angle-double-left"></span></a>';
					$member_tab .= '<a class="pagination-previous" href="#"><span class="fa fa-angle-left"></span> Previous</a>';
					$member_tab .= '<a class="pagination-num current">1</a><a class="pagination-num">2</a><a class="pagination-num">3</a><a class="pagination-num">4</a><a class="pagination-num">5</a><a class="pagination-num">6</a><a class="pagination-num">7</a><a class="pagination-num">8</a><a class="pagination-num">9</a><a class="pagination-num">10</a>';
					$member_tab .= '<a class="pagination-next" href="#">Next <span class="fa fa-angle-right"></span></a>';
					$member_tab .= '<a class="pagination-last" href="#"><span class="fa fa-angle-double-right"></span></a>';
				$member_tab .= '</div>';
			$member_tab .= '</div>';

			echo $member_tab;



			$resource_tab = '';
			$resource_tab .= '<div id="resources" class="tab-contents">';
				$resource_tab .= '<div class="search-sort clearfix">';
					$resource_tab .= '<div class="search-dropdown grid_4 omega"><strong>Sort by: </strong><select><option>All Resources</option></select></div>';
				$resource_tab .= '</div>';

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

				$resource_tab .= $collection_cards;

				$resource_tab .= '<div class="pagination">';
					$resource_tab .= '<a class="pagination-first" href="#"><span class="fa fa-angle-double-left"></span></a>';
					$resource_tab .= '<a class="pagination-previous" href="#"><span class="fa fa-angle-left"></span> Previous</a>';
					$resource_tab .= '<a class="pagination-num current">1</a><a class="pagination-num">2</a><a class="pagination-num">3</a><a class="pagination-num">4</a><a class="pagination-num">5</a><a class="pagination-num">6</a><a class="pagination-num">7</a><a class="pagination-num">8</a><a class="pagination-num">9</a><a class="pagination-num">10</a>';
					$resource_tab .= '<a class="pagination-next" href="#">Next <span class="fa fa-angle-right"></span></a>';
					$resource_tab .= '<a class="pagination-last" href="#"><span class="fa fa-angle-double-right"></span></a>';
				$resource_tab .= '</div>';
			$resource_tab .= '</div>';

			echo $resource_tab;


			$forum_tab = '';
			$forum_tab .= '<div id="forums" class="tab-contents">';

				$forum_tab .= '<div class="forum-header grid_12">';
					$forum_tab .= '<div class="forum-card-content">Forum</div>';
					$forum_tab .= '<div class="forum-card-comments">Comments</div>';
					$forum_tab .= '<div class="forum-card-dateactivity-content ">Last Activity</div>';
				$forum_tab .= '</div>';

				//Loop through member cards
				$forum_cards = '';
				$forum_count = 0;
				while( $forum_count < 12 ) {
					$forum_cards .= '<div class="forum-card card rounded-borders-full border-grey">';
						$forum_cards .= '<div class="forum-card-content vertical-align">';
							$forum_cards .= '<h5><a href="#">Standard in our Schools</a></h5>';
							$forum_cards .= 'Lorem Ipsum is simply dummy text of the printing and typesettin dummy text ever since the 1500s, when an unknown...';
						$forum_cards .= '</div>';
						$forum_cards .= '<div class="forum-card-comments vertical-align">';
							$forum_cards .= '<div class="comment-count">35</div>';
						$forum_cards .= '</div>';
						$forum_cards .= '<div class="forum-card-date vertical-align">';
							$forum_cards .= '<div class="comment-date">Sept 15, 2014</div>';
						$forum_cards .= '</div>';
					$forum_cards .= '</div>';

					$forum_count++;
				}

				$forum_tab .= $forum_cards;

			echo $forum_tab;

		echo '</div></div>';

	echo '</div>';

}





genesis();