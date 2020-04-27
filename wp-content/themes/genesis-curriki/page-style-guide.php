<?php
/*
* Template Name: Style Guide
*
* Child Theme Name: Curriki Child Theme for Genesis 2.1
* Author: Orange Blossom Media
* Url: http://orangeblossommedia.com/
*
*
* NOTES:
*	The code on this page has been placed in PHP variables to be echo'd in chunks to make insertion of functionality easier upon development.
*	Portions of code below are intended to be guides for structure and styling of features, not fully functional.
*
*
*/

// Add custom body class to the head
add_filter( 'body_class', 'curriki_style_guide_add_body_class' );
function curriki_style_guide_add_body_class( $classes ) {
   $classes[] = 'backend style-guide';
   return $classes;

}

// Execute custom style guide page
add_action( 'genesis_meta', 'curriki_custom_style_guide_loop' );
function curriki_custom_style_guide_loop() {
	//* Force full-width-content layout setting
	add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

	remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
	remove_action( 'genesis_loop', 'genesis_do_loop' );

	add_action( 'genesis_loop', 'curriki_style_guide_body' );
}


function curriki_style_guide_body() {

	curriki_group_cards_demo_display();

	curriki_member_cards_demo_display();

	curriki_preview_modals_demo_display();

	curriki_search_bars_demo_display();

	curriki_search_results_demo_display();

	curriki_library_demo_display();

	curriki_collection_demo_display();

	curriki_color_type_demo_display();

}


function curriki_group_cards_demo_display() {

	echo '<h2>Group Cards</h2>';
	echo '<strong>In Group, Not in Group, In Group Hover, Not in Group Hover</strong><div class="clearfix"></div>';

	$group_cards = '';

	// Member not in Group
	$group_cards .= '<div class="group-card card rounded-borders-full border-grey fixed_grid_3 join-group">';
		$group_cards .= '<div class="card-header">';
			$group_cards .= '<img class="border-grey" src="' . get_stylesheet_directory_uri() . '/images/group-icon-sample.png" alt="group-name" />';
			$group_cards .= '<span class="group-name name">Group Name</span>';
		$group_cards .= '</div>';
		$group_cards .= '<div class="card-stats">';
			$group_cards .= '<span class="stat"><span class="fa fa-users"></span>168</span>';
			$group_cards .= '<span class="stat"><span class="fa fa-comments"></span>43</span>';
			$group_cards .= '<span class="stat"><span class="fa fa-book"></span>259</span>';
		$group_cards .= '</div>';
		$group_cards .= '<div class="card-description">Group discription lorem ipsum dolor sit amet, adipiscing elit. Suspendisse fringilla nisl et velit aliquet faucibus. Morbi...</div>';
		$group_cards .= '<button class="card-button">Join Group</button>';
	$group_cards .= '</div>';

	// Member already in Group
	$group_cards .= '<div class="group-card card rounded-borders-full border-grey fixed_grid_3 group-member">';
		$group_cards .= '<div class="card-header">';
			$group_cards .= '<img class="border-grey" src="' . get_stylesheet_directory_uri() . '/images/group-icon-sample.png" alt="group-name" />';
			$group_cards .= '<span class="group-name name">Group Name</span>';
		$group_cards .= '</div>';
		$group_cards .= '<div class="card-stats">';
			$group_cards .= '<span class="stat"><span class="fa fa-users"></span>168</span>';
			$group_cards .= '<span class="stat"><span class="fa fa-comments"></span>43</span>';
			$group_cards .= '<span class="stat"><span class="fa fa-book"></span>259</span>';
		$group_cards .= '</div>';
		$group_cards .= '<div class="card-description">Group discription lorem ipsum dolor sit amet, adipiscing elit. Suspendisse fringilla nisl et velit aliquet faucibus. Morbi...</div>';
		$group_cards .= '<button class="card-button">Member</button>';
	$group_cards .= '</div>';

	// Member not in Group - Hover State
	$group_cards .= '<div class="group-card card rounded-borders-full border-grey fixed_grid_3 join-group hover">';
		$group_cards .= '<div class="card-header-overlay"></div><div class="card-header-overlay-buttons"><button>Preview</button><button>Full Profile</button></div>';
		$group_cards .= '<div class="card-header">';
			$group_cards .= '<img class="border-grey" src="' . get_stylesheet_directory_uri() . '/images/group-icon-sample.png" alt="group-name" />';
			$group_cards .= '<span class="group-name name">Group Name</span>';
		$group_cards .= '</div>';
		$group_cards .= '<div class="card-stats">';
			$group_cards .= '<span class="stat"><span class="fa fa-users"></span>168</span>';
			$group_cards .= '<span class="stat"><span class="fa fa-comments"></span>43</span>';
			$group_cards .= '<span class="stat"><span class="fa fa-book"></span>259</span>';
		$group_cards .= '</div>';
		$group_cards .= '<div class="card-description">Group discription lorem ipsum dolor sit amet, adipiscing elit. Suspendisse fringilla nisl et velit aliquet faucibus. Morbi...</div>';
		$group_cards .= '<button class="card-button">Join Group</button>';
	$group_cards .= '</div>';

	// Member already in Group - Hover State
	$group_cards .= '<div class="group-card card rounded-borders-full border-grey fixed_grid_3 group-member hover">';
		$group_cards .= '<div class="card-header">';
			$group_cards .= '<img class="border-grey" src="' . get_stylesheet_directory_uri() . '/images/group-icon-sample.png" alt="group-name" />';
			$group_cards .= '<span class="group-name name">Group Name</span>';
		$group_cards .= '</div>';
		$group_cards .= '<div class="card-stats">';
			$group_cards .= '<span class="stat"><span class="fa fa-users"></span>168</span>';
			$group_cards .= '<span class="stat"><span class="fa fa-comments"></span>43</span>';
			$group_cards .= '<span class="stat"><span class="fa fa-book"></span>259</span>';
		$group_cards .= '</div>';
		$group_cards .= '<div class="card-description">Group discription lorem ipsum dolor sit amet, adipiscing elit. Suspendisse fringilla nisl et velit aliquet faucibus. Morbi...</div>';
		$group_cards .= '<button class="card-button">Leave Group</button>';
	$group_cards .= '</div>';

	echo $group_cards;

	echo '<div class="clearfix" style="margin-bottom: 50px;"></div>';

}


function curriki_member_cards_demo_display() {

	echo '<h2>Member Cards</h2>';
	echo '<strong>Follow, Following, Follow Hover, Following Hover</strong><div class="clearfix"></div>';

	$member_cards = '';

	// Follow
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

	// Following
	$member_cards .= '<div class="member-card card rounded-borders-full border-grey fixed_grid_2 following">';
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
		$member_cards .= '<button class="card-button">Following</button>';
	$member_cards .= '</div>';

	// Follow - Hover State
	$member_cards .= '<div class="member-card card rounded-borders-full border-grey fixed_grid_2 follow hover">';
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

	// Following - Hover State
	$member_cards .= '<div class="member-card card rounded-borders-full border-grey fixed_grid_2 following hover">';
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
		$member_cards .= '<button class="card-button">Unfollow</button>';
	$member_cards .= '</div>';

	echo $member_cards;

	echo '<div class="clearfix" style="margin-bottom: 50px;"></div>';

}


function curriki_preview_modals_demo_display() {

	echo '<h2>Modals</h2>';

	$modals = '';

	// Group
	$modals .= '<div class="group-modal modal border-grey rounded-borders-full fixed_grid_6">';
		$modals .= '<div class="modal-left fixed_grid_8">';
			$modals .= '<img class="border-grey" src="' . get_stylesheet_directory_uri() . '/images/group-icon-sample.png" alt="group-name" />';
			$modals .= '<span class="group-name name">Group Name</span>';
			$modals .= '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages.</p>';
			$modals .= '<div class="modal-buttons">';
				$modals .= '<button class="modal-button white-button view">View Full Profile</button>';
				$modals .= '<button class="modal-button green-button join">Join Group</button>';
			$modals .= '</div>';
		$modals .= '</div>';
		$modals .= '<div class="modal-right scrollbar fixed_grid_4">';
			$modals .= '<ul class="info">';
				$modals .= '<li>Subjects:<ul><li>Mathematics</li><li>Science</li></ul></li>';
				$modals .= '<li>Education Levels:<ul><li>Mathematics</li><li>Science</li></ul></li>';
				$modals .= '<li>Language:<ul><li>Mathematics</li><li>Science</li></ul></li>';
				$modals .= '<li>Member Policy:<ul><li>Mathematics</li><li>Science</li></ul></li>';
				$modals .= '<li>Created:<ul><li>August, 12 2012</li></ul></li>';
				$modals .= '<li>Last Activity:<ul><li>December, 28 2014</li></ul></li>';
			$modals .= '</ul>';
		$modals .= '</div>';
		$modals .= '<div class="close"><span class="fa fa-close"></span></div>';
	$modals .= '</div>';


	// Group
	$modals .= '<div class="member-modal modal border-grey rounded-borders-full fixed_grid_6">';
		$modals .= '<div class="modal-left fixed_grid_8">';
			$modals .= '<img class="border-grey" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
			$modals .= '<span class="member-name name">Member Name</span><span class="occupation">Occupation</span> - <span class="location">City, State, Country</span>';
			$modals .= '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries.</p>';
			$modals .= '<div class="modal-buttons">';
				$modals .= '<button class="modal-button white-button view">View Full Profile</button>';
				$modals .= '<button class="modal-button green-button join">Join Group</button>';
			$modals .= '</div>';
		$modals .= '</div>';
		$modals .= '<div class="modal-right scrollbar fixed_grid_4">';
			$modals .= '<ul class="info">';
				$modals .= '<li>Subjects of Interest:<ul><li>Career & Technical Education</li><li>Educational Technology</li><li>Information & Media</li><li>Language Arts</li></ul></li>';
				$modals .= '<li>Education Levels of Interest:<ul><li>Grades 9-10 / Ages 14-16</li><li>Grades 11-12 / Ages 16-18</li><li>College & Beyond</li><li>Professional Development</li></ul></li>';
				$modals .= '<li>Organization:<ul><li>University of Indiana</li></ul></li>';
				$modals .= '<li>Website/Blogs<ul><li>http://jennamcwil</li></ul></li>';
			$modals .= '</ul>';
		$modals .= '</div>';
		$modals .= '<div class="close"><span class="fa fa-close"></span></div>';
	$modals .= '</div>';


	// Search Widget
	// $modals .= '<h2 style="padding-top: 30px; clear: both;">Search Widget Modal</h2>';
	// $modals .= '<div class="search_modal modal border-grey rounded-borders-full fixed_grid_6">';
	// 		$modals .= '<div class="search-bar">';
	// 			$modals .= '<div class="search-input">';
	// 				$modals .= '<div class="search-field"><input type="text" class="rounded-borders-left" placeholder="Start Searching" /></div>';
	// 				$modals .= '<div class="search-button"><button type="submit" class="rounded-borders-right"><span class="search-button-icon fa fa-search"></span>Search</button></div>';
	// 			$modals .= '</div>';
				// $modals .= '<div class="search-options rounded-borders-bottom border-grey toggled">';
					// $modals .= '<div class="search-dropdown rounded-borders-full border-grey">English<span class="search-dropdown-icon fa fa-caret-down"></span></div>';
					// $modals .= '<div class="advanced-search">';
					// 	$modals .= '<div class="optionset">';
					// 		$modals .= '
					// 			<div class="optionset-title">Subject</div>
					// 			<ul>
					// 				<li><input type="checkbox" name="subject" value="">Arts</li>
					// 				<li><input type="checkbox" name="subject" value="">Career & Technical Education</li>
					// 				<li><input type="checkbox" name="subject" value="">Education</li>
					// 				<li><input type="checkbox" name="subject" value="">Educational Technology</li>
					// 				<li><input type="checkbox" name="subject" value="">Health</li>
					// 				<li><input type="checkbox" name="subject" value="">Information & Media Literacy</li>
					// 				<li><input type="checkbox" name="subject" value="">Language Arts</li>
					// 				<li><input type="checkbox" name="subject" value="">Mathematics</li>
					// 				<li><input type="checkbox" name="subject" value="">Science</li>
					// 				<li><input type="checkbox" name="subject" value="">Social Studies</li>
					// 				<li><input type="checkbox" name="subject" value="">World Languages</li>
					// 				<li><input type="checkbox" name="subject" value="">Uncategorized</li>
					// 			</ul>';
					// 	$modals .= '</div>';
					// 	$modals .= '<div class="optionset two-col grey-border">';
					// 		$modals .= '
					// 			<div class="optionset-title"></div>
					// 			<ul>
					// 				<li><input type="checkbox" name="subject_2" value="">Anthropology</li>
					// 				<li><input type="checkbox" name="subject_2" value="">Careers</li>
					// 				<li><input type="checkbox" name="subject_2" value="">Civic</li>
					// 				<li><input type="checkbox" name="subject_2" value="">Current Events</li>
					// 				<li><input type="checkbox" name="subject_2" value="">Health</li>
					// 				<li><input type="checkbox" name="subject_2" value="">Information & Media Literacy</li>
					// 				<li><input type="checkbox" name="subject_2" value="">Language Arts</li>
					// 				<li><input type="checkbox" name="subject_2" value="">Mathematics</li>
					// 				<li><input type="checkbox" name="subject_2" value="">Science</li>
					// 				<li><input type="checkbox" name="subject_2" value="">Social Studies</li>
					// 				<li><input type="checkbox" name="subject_2" value="">World Languages</li>
					// 				<li><input type="checkbox" name="subject_2" value="">Anthropology</li>
					// 				<li><input type="checkbox" name="subject_2" value="">Careers</li>
					// 				<li><input type="checkbox" name="subject_2" value="">Civic</li>
					// 				<li><input type="checkbox" name="subject_2" value="">Current Events</li>
					// 				<li><input type="checkbox" name="subject_2" value="">Health</li>
					// 				<li><input type="checkbox" name="subject_2" value="">Information & Media Literacy</li>
					// 				<li><input type="checkbox" name="subject_2" value="">Language Arts</li>
					// 				<li><input type="checkbox" name="subject_2" value="">Mathematics</li>
					// 				<li><input type="checkbox" name="subject_2" value="">Science</li>
					// 				<li><input type="checkbox" name="subject_2" value="">Social Studies</li>
					// 				<li><input type="checkbox" name="subject_2" value="">World Languages</li>
					// 				<li><input type="checkbox" name="subject_2" value="">Uncategorized</li>
					// 			</ul>';
					// 	$modals .= '</div>';
					// 	$modals .= '<div class="clearfix"></div>';
					// $modals .= '</div>';
					// $modals .= '<div class="show-hide-options"><span class="show-hide-icon fa fa-minus-circle"></span>Hide Options</div>';
	// 			$modals .= '</div>';
	// 		$modals .= '</div>';
	// 	$modals .= '<div class="close"><span class="fa fa-close"></span></div>';
	// $modals .= '</div>';


	// // Alignment
	// $modals .= '<h2 style="padding-top: 30px; clear: both;">Alignment Modal</h2>';
	// $modals .= '<div class="alignment-modal modal border-grey rounded-borders-full fixed_grid_8">';
	// 	$modals .= '<div class="alignment-left grid_6">';
	// 		$modals .= '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries.</p>';
	// 	$modals .= '</div>';
	// 	$modals .= '<div class="alignment-right grid_6">';
	// 		$modals .= '<ul class="info">';
	// 			$modals .= '<li>Subjects of Interest:<ul><li>Career & Technical Education</li><li>Educational Technology</li><li>Information & Media</li><li>Language Arts</li></ul></li>';
	// 			$modals .= '<li>Education Levels of Interest:<ul><li>Grades 9-10 / Ages 14-16</li><li>Grades 11-12 / Ages 16-18</li><li>College & Beyond</li><li>Professional Development</li></ul></li>';
	// 			$modals .= '<li>Organization:<ul><li>University of Indiana</li></ul></li>';
	// 			$modals .= '<li>Website/Blogs<ul><li>http://jennamcwil</li></ul></li>';
	// 		$modals .= '</ul>';
	// 	$modals .= '</div>';
	// 	$modals .= '<div class="alignment-bottom grid_12">';
	// 		$modals .= '<ul class="info">';
	// 			$modals .= '<li>Subjects of Interest:<ul><li>Career & Technical Education</li><li>Educational Technology</li><li>Information & Media</li><li>Language Arts</li></ul></li>';
	// 			$modals .= '<li>Education Levels of Interest:<ul><li>Grades 9-10 / Ages 14-16</li><li>Grades 11-12 / Ages 16-18</li><li>College & Beyond</li><li>Professional Development</li></ul></li>';
	// 			$modals .= '<li>Organization:<ul><li>University of Indiana</li></ul></li>';
	// 			$modals .= '<li>Website/Blogs<ul><li>http://jennamcwil</li></ul></li>';
	// 		$modals .= '</ul>';
	// 	$modals .= '</div>';
	// 	$modals .= '<div class="close"><span class="fa fa-close"></span></div>';
	// $modals .= '</div>';


	// Add Resource Modal
	$modals .= '<h2 style="padding-top: 30px; clear: both;">Add Resource to My Library Modal</h2>';
	$modals .= '<div class="my-library-modal modal border-grey rounded-borders-full grid_8">';
		$modals .= '<h3 class="modal-title">Add Resource to My Library</h3>';
		$modals .= '<div class="grid_4">';
			$modals .= 'Select a location in your library that you would like to place this resource.';
		$modals .= '</div>';
		$modals .= '<div class="grid_8">';
			$modals .= '<div class="my-library-folders rounded-borders-full border-grey scrollbar">';
				$modals .= '<a class="toc-selection"><h4 class="toc-collection-folder selected-collection"><span class="fa fa-folder-open"></span> Name of Collection</h4></a>';
				$modals .= '<ul class="fa fa-ul toc-collection toc-folder">';
					$modals .= '<li class="toc-file toc-image"><span class="fa fa-li fa-file-image-o"></span> Resource Name</li>';
					$modals .= '<li class="toc-file toc-image"><span class="fa fa-li fa-file-image-o"></span> Resource With a Longer Name</li>';
					$modals .= '<li class="toc-file toc-image"><span class="fa fa-li fa-file-image-o"></span> Resource Name</li>';
					$modals .= '<li class="toc-file toc-image"><span class="fa fa-li fa-file-image-o"></span> Resource Name</li>';
				$modals .= '</ul>';
				$modals .= '<a class="toc-selection"><h4 class="toc-collection-folder"><span class="fa fa-folder"></span> Name of Collection</h4></a>';
				$modals .= '<a class="toc-selection"><h4 class="toc-collection-folder"><span class="fa fa-folder-open"></span> Name of Collection</h4></a>';
				$modals .= '<ul class="fa fa-ul toc-collection toc-folder">';
					$modals .= '<li class="toc-file toc-image"><span class="fa fa-li fa-file-image-o"></span> Resource Name</li>';
					$modals .= '<li class="toc-file toc-image"><span class="fa fa-li fa-file-image-o"></span> Resource With a Longer Name</li>';
					$modals .= '<li class="toc-file toc-image"><span class="fa fa-li fa-file-image-o"></span> Resource Name</li>';
					$modals .= '<li class="toc-file toc-image"><span class="fa fa-li fa-file-image-o"></span> Resource Name</li>';
				$modals .= '</ul>';
				$modals .= '<a class="toc-selection"><h4 class="toc-collection-folder"><span class="fa fa-folder"></span> Name of Collection</h4></a>';
				$modals .= '<a class="toc-selection"><h4 class="toc-collection-folder"><span class="fa fa-folder"></span> Name of Collection</h4></a>';
			$modals .= '</div>';
			$modals .= '<div class="my-library-actions"><button class="button-cancel">Cancel</button><button class="button-save">Save</button></div>';
		$modals .= '</div>';
		$modals .= '<div class="close"><span class="fa fa-close"></span></div>';
	$modals .= '</div>';


	// Alignment Modal
	$modals .= '<h2 style="padding-top: 30px; clear: both;">Alignment Modal</h2>';
	$modals .= '<div class="my-library-modal modal border-grey rounded-borders-full grid_8">';
		$modals .= '<h3 class="modal-title">Align Resource</h3>';
		$modals .= '<div class="grid_6 alpha">';
			$modals .= '<div class="alignments rounded-borders-full border-grey scrollbar">';
				$modals .= '<a class="toc-selection"><h4 class="toc-collection-folder selected-collection"><span class="fa fa-folder-open"></span> Name of Standard</h4></a>';
				$modals .= '<ul class="fa fa-ul toc-collection toc-folder">';
					$modals .= '<li class="toc-file toc-image">Sub-Standard</li>';
					$modals .= '<li class="toc-file toc-image">Sub-Standard</li>';
					$modals .= '<li class="toc-file toc-image">Sub-Standard</li>';
					$modals .= '<li class="toc-file toc-image">Sub-Standard</li>';
				$modals .= '</ul>';
				$modals .= '<a class="toc-selection"><h4 class="toc-collection-folder"><span class="fa fa-folder"></span> Name of Standard</h4></a>';
				$modals .= '<a class="toc-selection"><h4 class="toc-collection-folder"><span class="fa fa-folder-open"></span> Name of Standard</h4></a>';
				$modals .= '<ul class="fa fa-ul toc-collection toc-folder">';
					$modals .= '<li class="toc-file toc-image">Sub-Standard</li>';
					$modals .= '<li class="toc-file toc-image">Sub-Standard</li>';
					$modals .= '<li class="toc-file toc-image">Sub-Standard</li>';
					$modals .= '<li class="toc-file toc-image">Sub-Standard</li>';
				$modals .= '</ul>';
				$modals .= '<a class="toc-selection"><h4 class="toc-collection-folder"><span class="fa fa-folder"></span> Name of Standard</h4></a>';
				$modals .= '<a class="toc-selection"><h4 class="toc-collection-folder"><span class="fa fa-folder"></span> Name of Standard</h4></a>';
			$modals .= '</div>';
		$modals .= '</div>';
		$modals .= '<div class="grid_6 omega">';
			$modals .= '<div class="alignments rounded-borders-full border-grey scrollbar">';
			$modals .= '</div>';
		$modals .= '</div>';
		$modals .= '<div class="grid_12 alpha omega clearfix">';
			$modals .= '<div class="alignment-info rounded-borders-full border-grey scrollbar">';
				$modals .= '
					<p><strong>ASN URI:</strong> http://asn.jesandco.org/resources/S2454362</p>
					<p><strong>Authority Status:</strong> Original Statement</p>
					<p><strong>Indexing Status:</strong> No</p>
					<p><strong>Education Level:</strong> 2</p>
					<p><strong>Subject:</strong> Science</p>
					<p><strong>Statement Notation:</strong> 2-LS2</p>
					<p><strong>en-US:</strong> Disciplinary Core Idea</p>
					<p><strong>en-US:</strong> Ecosystems: Interactions, Energy, and Dynamics</p>
					<p><strong>Language:</strong> English</p>
					';
			$modals .= '</div>';
		$modals .= '</div>';
		$modals .= '<div class="close"><span class="fa fa-close"></span></div>';
	$modals .= '</div>';


	// Join Curriki OAuth Modal
	$modals .= '<h2 style="padding-top: 30px; clear: both;">Join Curriki OAuth Modal</h2>';
	$modals .= '<div class="join-oauth-modal modal border-grey rounded-borders-full grid_8">';
		$modals .= '<h3 class="modal-title">Join Curriki</h3>';
		$modals .= '<div class="join-login-section grid_5">';
			$modals .= '<div class="signup-form">';
				$modals .= '<form>';
					$modals .= '<input type="text" placeholder="Username" />';
					$modals .= '<input type="text" placeholder="Password" />';
					$modals .= '<input type="text" placeholder="Re-Enter Password" />';
					$modals .= '<input type="submit" value="Sign Up" class="small-button green-button join" />';
					$modals .= '<a href="#">Didn\'t work or on a school network?</a>';
				$modals .= '</form>';
			$modals .= '</div>';
		$modals .= '</div>';
		$modals .= '<div class="modal-split grid_2">Or</div>';
		$modals .= '<div class="join-login-section grid_5">';
			$modals .= '<div class="signup-oauth">';
				$modals .= '<p>Oauth Signup Cards (integrated as 3rd Party)</p>';
			$modals .= '</div>';
		$modals .= '</div>';
		$modals .= '<div class="join-login-bottom rounded-borders-bottom">';
			$modals .= '<a href="#">Already have an account? Log In</a>';
		$modals .= '</div>';
		$modals .= '<div class="close"><span class="fa fa-close"></span></div>';
	$modals .= '</div>';


	// Join Curriki Form Modal
	$modals .= '<h2 style="padding-top: 30px; clear: both;">Join Curriki Form Modal</h2>';
	$modals .= '<div class="join-oauth-modal modal border-grey rounded-borders-full grid_4">';
		$modals .= '<h3 class="modal-title">Join Curriki</h3>';
		$modals .= '<p>Please complete all of the fields below to join our 400,000+ members and access 58,000+ resources.</p>';
		$modals .= '<div class="signup-form">';
			$modals .= '<form>';
				$modals .= '<input type="text" placeholder="First Name" />';
				$modals .= '<input type="text" placeholder="Last Name" />';
				$modals .= '<select><option value="" disabled selected>Country</option></select>';
				$modals .= '<input type="text" placeholder="Zip Code" />';
				$modals .= '<select><option value="" disabled selected>Membership Type</option></select>';
				$modals .= '<div class="join-check"><input type="checkbox" /> I agree to Curriki\'s <a href="#">Privacy Policy and Terms of Service</a></div>';
				$modals .= '<input type="submit" value="Log In" class="small-button green-button login" />';
			$modals .= '</form>';
		$modals .= '</div>';
		$modals .= '<div class="close"><span class="fa fa-close"></span></div>';
	$modals .= '</div>';


	// Login Curriki OAuth Modal
	$modals .= '<h2 style="padding-top: 30px; clear: both;">Login Curriki OAuth Modal</h2>';
	$modals .= '<div class="join-oauth-modal modal border-grey rounded-borders-full grid_8">';
		$modals .= '<h3 class="modal-title">Log in to Your Account</h3>';
		$modals .= '<div class="join-login-section grid_5">';
			$modals .= '<div class="signup-form">';
				$modals .= '<form>';
					$modals .= '<input type="text" placeholder="Username" />';
					$modals .= '<input type="text" placeholder="Password" />';
					$modals .= '<input type="submit" value="Log In" class="small-button green-button login" />';
					$modals .= '<a href="#">Forgot Username or Password?</a>';
					$modals .= '<a href="#">Didn\'t work or on a school network?</a>';
				$modals .= '</form>';
			$modals .= '</div>';
		$modals .= '</div>';
		$modals .= '<div class="modal-split grid_2">Or</div>';
		$modals .= '<div class="join-login-section grid_5">';
			$modals .= '<div class="signup-oauth">';
				$modals .= '<p>Oauth Signup Cards (integrated as 3rd Party)</p>';
			$modals .= '</div>';
		$modals .= '</div>';
		$modals .= '<div class="join-login-bottom rounded-borders-bottom">';
			$modals .= '<a href="#">Don\'t have an account? Join Now</a>';
		$modals .= '</div>';
		$modals .= '<div class="close"><span class="fa fa-close"></span></div>';
	$modals .= '</div>';


	// General Modal
	$modals .= '<h2 style="padding-top: 30px; clear: both;">General Modal</h2>';
	$modals .= '<div class="my-library-modal modal border-grey rounded-borders-full grid_6">';
		$modals .= '<h3 class="modal-title">Are you sure you\'d like to delete?</h3>';
		$modals .= '<div class="grid_8 center">';
			$modals .= 'Some details and warnings about deleting files forever. This is another sentence explaining this further.';
			$modals .= '<div class="my-library-actions"><button class="button-cancel">Don\'t Delete</button><button class="button-save">Yes, Delete Resource</button></div>';
		$modals .= '</div>';
		$modals .= '<div class="close"><span class="fa fa-close"></span></div>';
	$modals .= '</div>';

	echo $modals;

	echo '<div class="clearfix" style="margin-bottom: 50px;"></div>';

}


function curriki_search_bars_demo_display() {

	echo '<h2>Search Bars</h2>';

	$search_bars = '';

	// Group Tab Active
	$search_bars .= '<div class="search-bar fixed_grid_12">';
		$search_bars .= '<div class="search-tabs">';
			$search_bars .= '<div class="resource-tab tab rounded-borders-top"><strong>Resources</strong> (9,654)</div>';
			$search_bars .= '<div class="groups-tab tab rounded-borders-top selected"><strong>Groups</strong> (22)</div>';
			$search_bars .= '<div class="members-tab tab rounded-borders-top"><strong>Members</strong> (713)</div>';
			$search_bars .= '<div class="search-tips"><a>Search Tips</a></div>';
		$search_bars .= '</div>';
		$search_bars .= '<div class="search-input">';
			$search_bars .= '<div class="search-field"><input type="text" class="rounded-borders-left" placeholder="Start Searching" /></div>';
			$search_bars .= '<div class="search-button"><button type="submit" class="rounded-borders-right"><span class="search-button-icon fa fa-search"></span>Search</button></div>';
		$search_bars .= '</div>';
		$search_bars .= '<div class="search-options rounded-borders-bottom border-grey">';
			$search_bars .= '<div class="search-dropdown rounded-borders-full border-grey">English<span class="search-dropdown-icon fa fa-caret-down"></span></div>';
			$search_bars .= '<div class="show-hide-options"><span class="show-hide-icon fa fa-plus-circle"></span>More Search Options</div>';
		$search_bars .= '</div>';
	$search_bars .= '</div>';

	// Resource Tab Active Expanded Advanced Search
	$search_bars .= '<div class="search-bar fixed_grid_12">';
		$search_bars .= '<div class="search-tabs">';
			$search_bars .= '<div class="resource-tab tab rounded-borders-top selected"><strong>Resources</strong> (9,654)</div>';
			$search_bars .= '<div class="groups-tab tab rounded-borders-top"><strong>Groups</strong> (22)</div>';
			$search_bars .= '<div class="members-tab tab rounded-borders-top"><strong>Members</strong> (713)</div>';
			$search_bars .= '<div class="search-tips"><a>Search Tips</a></div>';
		$search_bars .= '</div>';
		$search_bars .= '<div class="search-input">';
			$search_bars .= '<div class="search-field"><input type="text" class="rounded-borders-left" placeholder="Start Searching" /></div>';
			$search_bars .= '<div class="search-button"><button type="submit" class="rounded-borders-right"><span class="search-button-icon fa fa-search"></span>Search</button></div>';
		$search_bars .= '</div>';
		$search_bars .= '<div class="search-options rounded-borders-bottom border-grey toggled">';
			$search_bars .= '<div class="search-dropdown rounded-borders-full border-grey">English<span class="search-dropdown-icon fa fa-caret-down"></span></div>';
			$search_bars .= '<div class="advanced-search">';
				$search_bars .= '<div class="optionset">';
					$search_bars .= '
						<div class="optionset-title">Subject</div>
						<ul>
							<li><input type="checkbox" name="subject" value="">Arts</li>
							<li><input type="checkbox" name="subject" value="">Career & Technical Education</li>
							<li><input type="checkbox" name="subject" value="">Education</li>
							<li><input type="checkbox" name="subject" value="">Educational Technology</li>
							<li><input type="checkbox" name="subject" value="">Health</li>
							<li><input type="checkbox" name="subject" value="">Information & Media Literacy</li>
							<li><input type="checkbox" name="subject" value="">Language Arts</li>
							<li><input type="checkbox" name="subject" value="">Mathematics</li>
							<li><input type="checkbox" name="subject" value="">Science</li>
							<li><input type="checkbox" name="subject" value="">Social Studies</li>
							<li><input type="checkbox" name="subject" value="">World Languages</li>
							<li><input type="checkbox" name="subject" value="">Uncategorized</li>
						</ul>';
				$search_bars .= '</div>';
				$search_bars .= '<div class="optionset two-col grey-border">';
					$search_bars .= '
						<div class="optionset-title"></div>
						<ul>
							<li><input type="checkbox" name="subject_2" value="">Anthropology</li>
							<li><input type="checkbox" name="subject_2" value="">Careers</li>
							<li><input type="checkbox" name="subject_2" value="">Civic</li>
							<li><input type="checkbox" name="subject_2" value="">Current Events</li>
							<li><input type="checkbox" name="subject_2" value="">Health</li>
							<li><input type="checkbox" name="subject_2" value="">Information & Media Literacy</li>
							<li><input type="checkbox" name="subject_2" value="">Language Arts</li>
							<li><input type="checkbox" name="subject_2" value="">Mathematics</li>
							<li><input type="checkbox" name="subject_2" value="">Science</li>
							<li><input type="checkbox" name="subject_2" value="">Social Studies</li>
							<li><input type="checkbox" name="subject_2" value="">World Languages</li>
							<li><input type="checkbox" name="subject_2" value="">Anthropology</li>
							<li><input type="checkbox" name="subject_2" value="">Careers</li>
							<li><input type="checkbox" name="subject_2" value="">Civic</li>
							<li><input type="checkbox" name="subject_2" value="">Current Events</li>
							<li><input type="checkbox" name="subject_2" value="">Health</li>
							<li><input type="checkbox" name="subject_2" value="">Information & Media Literacy</li>
							<li><input type="checkbox" name="subject_2" value="">Language Arts</li>
							<li><input type="checkbox" name="subject_2" value="">Mathematics</li>
							<li><input type="checkbox" name="subject_2" value="">Science</li>
							<li><input type="checkbox" name="subject_2" value="">Social Studies</li>
							<li><input type="checkbox" name="subject_2" value="">World Languages</li>
							<li><input type="checkbox" name="subject_2" value="">Uncategorized</li>
						</ul>';
				$search_bars .= '</div>';
				$search_bars .= '<div class="optionset">';
					$search_bars .= '
						<div class="optionset-title">Education Level</div>
						<ul>
							<li><input type="checkbox" name="education_level" value="">Preschool (Ages 0-4)</li>
							<li><input type="checkbox" name="education_level" value="">Kindergarten-Grade 2 (Ages 5-7)</li>
							<li><input type="checkbox" name="education_level" value="">Grades 3-5 (Ages 8-10)</li>
							<li><input type="checkbox" name="education_level" value="">Grades 6-8 (Ages 11-13)</li>
							<li><input type="checkbox" name="education_level" value="">Grades 9-10 (Ages 14-16)</li>
							<li><input type="checkbox" name="education_level" value="">Grades 11-12 (Ages 16-18)</li>
							<li><input type="checkbox" name="education_level" value="">College & Beyond</li>
							<li><input type="checkbox" name="education_level" value="">Professional Development</li>
							<li><input type="checkbox" name="education_level" value="">Special Education</li>
						</ul>';
				$search_bars .= '</div>';
				$search_bars .= '<div class="optionset">';
					$search_bars .= '
						<div class="optionset-title">Type</div>
						<ul>
							<li><input type="checkbox" name="type" value="">Activity</li>
							<li><input type="checkbox" name="type" value="">Asset</li>
							<li><input type="checkbox" name="type" value="">Book</li>
							<li><input type="checkbox" name="type" value="">Curriculum</li>
							<li><input type="checkbox" name="type" value="">Other</li>
						</ul>
						<div class="optionset-title">Rating</div>
						<ul>
							<li><input type="checkbox" name="type" value="">Partners</li>
							<li><input type="checkbox" name="type" value="">Top Rated by Curriki</li>
							<li><input type="checkbox" name="type" value="">Top Rated by Members</li>
						</ul>';
				$search_bars .= '</div>';
				$search_bars .= '<div class="clearfix"></div>';
			$search_bars .= '</div>';
			$search_bars .= '<div class="show-hide-options"><span class="show-hide-icon fa fa-minus-circle"></span>Hide Options</div>';
		$search_bars .= '</div>';
	$search_bars .= '</div>';

	// Resource Tab Active Expanded Standards Search
	$search_bars .= '<div class="search-bar fixed_grid_12">';
		$search_bars .= '<div class="search-tabs">';
			$search_bars .= '<div class="resource-tab tab rounded-borders-top selected"><strong>Resources</strong> (9,654)</div>';
			$search_bars .= '<div class="groups-tab tab rounded-borders-top"><strong>Groups</strong> (22)</div>';
			$search_bars .= '<div class="members-tab tab rounded-borders-top"><strong>Members</strong> (713)</div>';
			$search_bars .= '<div class="search-tips"><a>Search Tips</a></div>';
		$search_bars .= '</div>';
		$search_bars .= '<div class="search-input">';
			$search_bars .= '<div class="search-field"><input type="text" class="rounded-borders-left" placeholder="Start Searching" /></div>';
			$search_bars .= '<div class="search-button"><button type="submit" class="rounded-borders-right"><span class="search-button-icon fa fa-search"></span>Search</button></div>';
		$search_bars .= '</div>';
		$search_bars .= '<div class="search-options rounded-borders-bottom border-grey toggled">';
			$search_bars .= '<div class="search-dropdown rounded-borders-full border-grey">English<span class="search-dropdown-icon fa fa-caret-down"></span></div>';
			$search_bars .= '<div class="advanced-search">';
				$search_bars .= '<ul id="standards-accordion" class="border-grey rounded-borders-full">';
					$search_bars .= '<li class="standards-accordion-tab"><div class="standards-tab-header"><h3>Jurisdiction/Organization</h3></div>
							<ul class="standards-accordion-content scrollbar">
								<li>Alabama Course of Study: Mathematics</li>
								<li>Alabama Course of Study: English Language Arts</li>
								<li>Alabama Course of Study: Social Studies</li>
								<li>Alabama Course of Study: Physical Education</li>
								<li>Alabama Course of Study: Health Education</li>
								<li>Alabama Course of Study: Technology Eucation</li>
								<li>Alabama Course of Study: Arts Education Music</li>
								<li>Alabama Course of Study: Mathematics</li>
								<li>Alabama Course of Study: English Language Arts</li>
								<li>Alabama Course of Study: Social Studies</li>
								<li>Alabama Course of Study: Physical Education</li>
								<li>Alabama Course of Study: Health Education</li>
								<li>Alabama Course of Study: Technology Eucation</li>
								<li>Alabama Course of Study: Arts Education Music</li>
							</ul>
						</li>';
					$search_bars .= '<li class="standards-accordion-tab"><div class="standards-tab-header"><h3>Document Title</h3></div>
							<ul class="standards-accordion-content scrollbar">
								<li>Alabama Course of Study: Mathematics</li>
								<li>Alabama Course of Study: English Language Arts</li>
								<li>Alabama Course of Study: Social Studies</li>
								<li>Alabama Course of Study: Physical Education</li>
								<li>Alabama Course of Study: Health Education</li>
								<li>Alabama Course of Study: Technology Eucation</li>
								<li>Alabama Course of Study: Arts Education Music</li>
								<li>Alabama Course of Study: Mathematics</li>
								<li>Alabama Course of Study: English Language Arts</li>
								<li>Alabama Course of Study: Social Studies</li>
								<li>Alabama Course of Study: Physical Education</li>
								<li>Alabama Course of Study: Health Education</li>
								<li>Alabama Course of Study: Technology Eucation</li>
								<li>Alabama Course of Study: Arts Education Music</li>
							</ul>
						</li>';
					$search_bars .= '<li class="standards-accordion-tab"><div class="standards-tab-header"><h3>Course of Study</h3></div>
							<ul class="standards-accordion-content optionset scrollbar">
								<li><input type="checkbox" name="subject" value="">Arts</li>
								<li><input type="checkbox" name="subject" value="">Career & Technical Education</li>
								<li><input type="checkbox" name="subject" value="">Education</li>
								<li><input type="checkbox" name="subject" value="">Educational Technology</li>
								<li><input type="checkbox" name="subject" value="">Health</li>
								<li><input type="checkbox" name="subject" value="">Information & Media Literacy</li>
								<li><input type="checkbox" name="subject" value="">Language Arts</li>
								<li><input type="checkbox" name="subject" value="">Mathematics</li>
								<li><input type="checkbox" name="subject" value="">Science</li>
								<li><input type="checkbox" name="subject" value="">Social Studies</li>
								<li><input type="checkbox" name="subject" value="">World Languages</li>
								<li><input type="checkbox" name="subject" value="">Uncategorized</li>
							</ul>
						</li>';
				$search_bars .= '</ul>';
				$search_bars .= '<div class="clearfix"></div>';
			$search_bars .= '</div>';
			$search_bars .= '<div class="show-hide-options"><span class="show-hide-icon fa fa-minus-circle"></span>Hide Options</div>';
		$search_bars .= '</div>';
	$search_bars .= '</div>';

	$search_bars .= '<script>
	jQuery(document).ready(function($) {

	    activeItem = $("#standards-accordion li:first");
	    $(activeItem).addClass("active");

	    $("#standards-accordion li").click(function(){
	    	$(activeItem).removeClass("active");
	        activeItem = this;
		    $(activeItem).addClass("active");
	    });

	}(jQuery));
	</script>';

	// Group Tab Active Expanded Advanced Search
	$search_bars .= '<div class="search-bar fixed_grid_12">';
		$search_bars .= '<div class="search-tabs">';
			$search_bars .= '<div class="resource-tab tab rounded-borders-top"><strong>Resources</strong> (9,654)</div>';
			$search_bars .= '<div class="groups-tab tab rounded-borders-top selected"><strong>Groups</strong> (22)</div>';
			$search_bars .= '<div class="members-tab tab rounded-borders-top"><strong>Members</strong> (713)</div>';
			$search_bars .= '<div class="search-tips"><a>Search Tips</a></div>';
		$search_bars .= '</div>';
		$search_bars .= '<div class="search-input">';
			$search_bars .= '<div class="search-field"><input type="text" class="rounded-borders-left" placeholder="Start Searching" /></div>';
			$search_bars .= '<div class="search-button"><button type="submit" class="rounded-borders-right"><span class="search-button-icon fa fa-search"></span>Search</button></div>';
		$search_bars .= '</div>';
		$search_bars .= '<div class="search-options rounded-borders-bottom border-grey toggled">';
			$search_bars .= '<div class="search-dropdown rounded-borders-full border-grey">English<span class="search-dropdown-icon fa fa-caret-down"></span></div>';
			$search_bars .= '<div class="advanced-search">';
				$search_bars .= '<div class="optionset two-col">';
					$search_bars .= '
						<div class="optionset-title">Subject</div>
						<ul>
							<li><input type="checkbox" name="subject" value="">Arts</li>
							<li><input type="checkbox" name="subject" value="">Career & Technical Education</li>
							<li><input type="checkbox" name="subject" value="">Education</li>
							<li><input type="checkbox" name="subject" value="">Educational Technology</li>
							<li><input type="checkbox" name="subject" value="">Health</li>
							<li><input type="checkbox" name="subject" value="">Information & Media Literacy</li>
							<li><input type="checkbox" name="subject" value="">Language Arts</li>
							<li><input type="checkbox" name="subject" value="">Mathematics</li>
							<li><input type="checkbox" name="subject" value="">Science</li>
							<li><input type="checkbox" name="subject" value="">Social Studies</li>
							<li><input type="checkbox" name="subject" value="">World Languages</li>
							<li><input type="checkbox" name="subject" value="">Uncategorized</li>
						</ul>';
				$search_bars .= '</div>';
				$search_bars .= '<div class="optionset two-col">';
					$search_bars .= '
						<div class="optionset-title">Education Level</div>
						<ul>
							<li><input type="checkbox" name="education_level" value="">Preschool (Ages 0-4)</li>
							<li><input type="checkbox" name="education_level" value="">Kindergarten-Grade 2 (Ages 5-7)</li>
							<li><input type="checkbox" name="education_level" value="">Grades 3-5 (Ages 8-10)</li>
							<li><input type="checkbox" name="education_level" value="">Grades 6-8 (Ages 11-13)</li>
							<li><input type="checkbox" name="education_level" value="">Grades 9-10 (Ages 14-16)</li>
							<li><input type="checkbox" name="education_level" value="">Grades 11-12 (Ages 16-18)</li>
							<li><input type="checkbox" name="education_level" value="">College & Beyond</li>
							<li><input type="checkbox" name="education_level" value="">Professional Development</li>
							<li><input type="checkbox" name="education_level" value="">Special Education</li>
						</ul>';
				$search_bars .= '</div>';
				$search_bars .= '<div class="clearfix"></div>';
			$search_bars .= '</div>';
			$search_bars .= '<div class="show-hide-options"><span class="show-hide-icon fa fa-minus-circle"></span>Hide Options</div>';
		$search_bars .= '</div>';
	$search_bars .= '</div>';

	// Members Tab Active Expanded Advanced Search
	$search_bars .= '<div class="search-bar fixed_grid_12">';
		$search_bars .= '<div class="search-tabs">';
			$search_bars .= '<div class="resource-tab tab rounded-borders-top"><strong>Resources</strong> (9,654)</div>';
			$search_bars .= '<div class="groups-tab tab rounded-borders-top"><strong>Groups</strong> (22)</div>';
			$search_bars .= '<div class="members-tab tab rounded-borders-top selected"><strong>Members</strong> (713)</div>';
			$search_bars .= '<div class="search-tips"><a>Search Tips</a></div>';
		$search_bars .= '</div>';
		$search_bars .= '<div class="search-input">';
			$search_bars .= '<div class="search-field"><input type="text" class="rounded-borders-left" placeholder="Start Searching" /></div>';
			$search_bars .= '<div class="search-button"><button type="submit" class="rounded-borders-right"><span class="search-button-icon fa fa-search"></span>Search</button></div>';
		$search_bars .= '</div>';
		$search_bars .= '<div class="search-options rounded-borders-bottom border-grey toggled">';
			$search_bars .= '<div class="search-dropdown rounded-borders-full border-grey">English<span class="search-dropdown-icon fa fa-caret-down"></span></div>';
			$search_bars .= '<div class="advanced-search">';
				$search_bars .= '<div class="optionset two-col">';
					$search_bars .= '
						<div class="optionset-title">Subject</div>
						<ul>
							<li><input type="checkbox" name="subject" value="">Arts</li>
							<li><input type="checkbox" name="subject" value="">Career & Technical Education</li>
							<li><input type="checkbox" name="subject" value="">Education</li>
							<li><input type="checkbox" name="subject" value="">Educational Technology</li>
							<li><input type="checkbox" name="subject" value="">Health</li>
							<li><input type="checkbox" name="subject" value="">Information & Media Literacy</li>
							<li><input type="checkbox" name="subject" value="">Language Arts</li>
							<li><input type="checkbox" name="subject" value="">Mathematics</li>
							<li><input type="checkbox" name="subject" value="">Science</li>
							<li><input type="checkbox" name="subject" value="">Social Studies</li>
							<li><input type="checkbox" name="subject" value="">World Languages</li>
							<li><input type="checkbox" name="subject" value="">Uncategorized</li>
						</ul>';
				$search_bars .= '</div>';
				$search_bars .= '<div class="optionset">';
					$search_bars .= '
						<div class="optionset-title">Member Type</div>
						<ul>
							<li><input type="checkbox" name="member_type" value="">Student</li>
							<li><input type="checkbox" name="member_type" value="">Teacher</li>
							<li><input type="checkbox" name="member_type" value="">Professional</li>
							<li><input type="checkbox" name="member_type" value="">School/District Admin</li>
							<li><input type="checkbox" name="member_type" value="">Non-Profit Organizer</li>
							<li><input type="checkbox" name="member_type" value="">Educational Institution</li>
							<li><input type="checkbox" name="member_type" value="">Corporate Organizer</li>
						</ul>';
				$search_bars .= '</div>';
				$search_bars .= '<div class="optionset">';
					$search_bars .= '
						<div class="optionset-title">Education Level</div>
						<ul>
							<li><input type="checkbox" name="education_level" value="">Preschool (Ages 0-4)</li>
							<li><input type="checkbox" name="education_level" value="">Kindergarten-Grade 2 (Ages 5-7)</li>
							<li><input type="checkbox" name="education_level" value="">Grades 3-5 (Ages 8-10)</li>
							<li><input type="checkbox" name="education_level" value="">Grades 6-8 (Ages 11-13)</li>
							<li><input type="checkbox" name="education_level" value="">Grades 9-10 (Ages 14-16)</li>
							<li><input type="checkbox" name="education_level" value="">Grades 11-12 (Ages 16-18)</li>
							<li><input type="checkbox" name="education_level" value="">College & Beyond</li>
							<li><input type="checkbox" name="education_level" value="">Professional Development</li>
							<li><input type="checkbox" name="education_level" value="">Special Education</li>
						</ul>';
				$search_bars .= '</div>';
				$search_bars .= '<div class="clearfix"></div>';
			$search_bars .= '</div>';
			$search_bars .= '<div class="show-hide-options"><span class="show-hide-icon fa fa-minus-circle"></span>Hide Options</div>';
		$search_bars .= '</div>';
	$search_bars .= '</div>';


	// 8 Grid
	$search_bars .= '<div class="search-bar fixed_grid_8">';
		$search_bars .= '<div class="search-tabs">';
			$search_bars .= '<div class="resource-tab tab rounded-borders-top"><strong>Resources</strong> (9,654)</div>';
			$search_bars .= '<div class="groups-tab tab rounded-borders-top selected"><strong>Groups</strong> (22)</div>';
			$search_bars .= '<div class="members-tab tab rounded-borders-top"><strong>Members</strong> (713)</div>';
			$search_bars .= '<div class="search-tips"><a>Search Tips</a></div>';
		$search_bars .= '</div>';
		$search_bars .= '<div class="search-input">';
			$search_bars .= '<div class="search-field"><input type="text" class="rounded-borders-left" placeholder="Start Searching" /></div>';
			$search_bars .= '<div class="search-button"><button type="submit" class="rounded-borders-right"><span class="search-button-icon fa fa-search"></span>Search</button></div>';
		$search_bars .= '</div>';
		$search_bars .= '<div class="search-options rounded-borders-bottom border-grey">';
			$search_bars .= '<div class="search-dropdown rounded-borders-full border-grey">English<span class="search-dropdown-icon fa fa-caret-down"></span></div>';
			$search_bars .= '<div class="show-hide-options"><span class="show-hide-icon fa fa-plus-circle"></span>More Search Options</div>';
		$search_bars .= '</div>';
	$search_bars .= '</div>';

	$search_bars .= '<div class="clearfix"></div>';


	// 6 Grid
	$search_bars .= '<div class="search-bar fixed_grid_6">';
		$search_bars .= '<div class="search-tabs">';
			$search_bars .= '<div class="resource-tab tab rounded-borders-top"><strong>Resources</strong> (19,654)</div>';
			$search_bars .= '<div class="groups-tab tab rounded-borders-top selected"><strong>Groups</strong> (22)</div>';
			$search_bars .= '<div class="members-tab tab rounded-borders-top"><strong>Members</strong> (713)</div>';
			$search_bars .= '<div class="search-tips"><a>Search Tips</a></div>';
		$search_bars .= '</div>';
		$search_bars .= '<div class="search-input">';
			$search_bars .= '<div class="search-field"><input type="text" class="rounded-borders-left" placeholder="Start Searching" /></div>';
			$search_bars .= '<div class="search-button"><button type="submit" class="rounded-borders-right"><span class="search-button-icon fa fa-search"></span>Search</button></div>';
		$search_bars .= '</div>';
		$search_bars .= '<div class="search-options rounded-borders-bottom border-grey">';
			$search_bars .= '<div class="search-dropdown rounded-borders-full border-grey">English<span class="search-dropdown-icon fa fa-caret-down"></span></div>';
			$search_bars .= '<div class="show-hide-options"><span class="show-hide-icon fa fa-plus-circle"></span>More Search Options</div>';
		$search_bars .= '</div>';
	$search_bars .= '</div>';

	$search_bars .= '<div class="clearfix"></div>';


	// 3 Grid
	$search_bars .= '<div class="search-bar fixed_grid_3">';
		$search_bars .= '<div class="search-tabs">';
			$search_bars .= '<div class="resource-tab tab rounded-borders-top"><span class="fa fa-book strong"></span></div>';
			$search_bars .= '<div class="groups-tab tab rounded-borders-top selected"><span class="fa fa-users strong"></span></div>';
			$search_bars .= '<div class="members-tab tab rounded-borders-top"><span class="fa fa-user strong"></span></div>';
			$search_bars .= '<div class="search-tips"><a>Search Tips</a></div>';
		$search_bars .= '</div>';
		$search_bars .= '<div class="search-input">';
			$search_bars .= '<div class="search-field"><input type="text" class="rounded-borders-left" placeholder="Start Searching" /></div>';
			$search_bars .= '<div class="search-button"><button type="submit" class="rounded-borders-right"><span class="search-button-icon fa fa-search"></span>Search</button></div>';
		$search_bars .= '</div>';
		$search_bars .= '<div class="search-options rounded-borders-bottom border-grey">';
			$search_bars .= '<div class="search-dropdown rounded-borders-full border-grey">English<span class="search-dropdown-icon fa fa-caret-down"></span></div>';
			$search_bars .= '<div class="show-hide-options"><span class="show-hide-icon fa fa-plus-circle"></span>More Search Options</div>';
		$search_bars .= '</div>';
	$search_bars .= '</div>';


	echo $search_bars;

	echo '<div class="clearfix" style="margin-bottom: 50px;"></div>';

}


function curriki_search_results_demo_display() {

	echo '<h2>Search Results</h2>';

	$search_results = '';

	// Group Tab Active
	$search_results .= '<div class="search-result search-collection grid_12">';
		$search_results .= '<div class="search-result-icon"><span class="fa fa-folder-open"></span></div>';
		$search_results .= '<div class="search-result-title">Name of a Collection</div>';
		$search_results .= '<div class="search-result-author">';
			$search_results .= '<img class="border-grey" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
			$search_results .= '<span class="member-name name">Member Name</span><span class="occupation">Occupation</span><span class="location">City, State, Country</span>';
		$search_results .= '</div>';
		$search_results .= '<div class="search-result-rating rating">';
			$search_results .= '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>';
			$search_results .= '<a href="#">Rate this resource</a>';
		$search_results .= '</div>';
		$search_results .= '<div class="search-result-curriki-rating curriki-rating"><span class="curriki-rating-title">Curriki Rating</span><span class="rating-badge">3</span></div>';
		$search_results .= '<div class="search-result-date">Jan 5, 2012</div>';
		$search_results .= '<div class="search-result-actions">';
			$search_results .= '<a href="#"><span class="fa fa-star"></span> </a>';
			$search_results .= '<a href="#"><span class="fa fa-star"></span> </a>';
			$search_results .= '<a href="#"><span class="fa fa-star"></span> </a>';
			$search_results .= '<a href="#"><span class="fa fa-star"></span> </a>';
		$search_results .= '</div>';
	$search_results .= '</div>';

	// echo $search_results;

	echo '<div class="clearfix" style="margin-bottom: 50px;"></div>';

}


function curriki_library_demo_display() {

	echo '<h2>Library</h2>';

	$library = '';

	// Collection - First Level
	$library .= '<div class="library-asset rounded-borders-full border-grey library-collection">';
		$library .= '<div class="library-icon"><span class="fa fa-folder-open"></span></div>';
		$library .= '<div class="library-title vertical-align"><a href="#">Name of a Collection</a></div>';
		$library .= '<div class="library-author vertical-align">';
			$library .= '<img src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
			$library .= '<div class="library-author-info">';
				$library .= '<span class="member-name name">Member Name</span><span class="location">Toronto, Ontario, Canada</span>';
			$library .= '</div>';
			$library .= '<div class="member-more"><a href="#">More from this member</a></div>';
		$library .= '</div>';
		$library .= '<div class="library-rating rating vertical-align"><span class="member-rating-title">Member Rating</span>';
			$library .= '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>';
			$library .= '<a href="#">Rate this resource</a>';
		$library .= '</div>';
		$library .= '<div class="library-curriki-rating curriki-rating vertical-align"><span class="curriki-rating-title">Curriki Rating</span><span class="rating-badge">3</span></div>';
		$library .= '<div class="library-date vertical-align">Jan 5, 2012</div>';
		$library .= '<div class="library-actions vertical-align">';
			$library .= '<a href="#"><span class="fa fa-edit"></span> <span>Edit</span></a>';
			$library .= '<a href="#"><span class="fa fa-trash"></span> <span>Delete</span></a>';
			$library .= '<a href="#"><span class="fa fa-copy"></span> <span>Duplicate</span></a>';
			$library .= '<a href="#"><span class="fa fa-share-alt-square"></span> <span>Share</span></a>';
		$library .= '</div>';
	$library .= '</div>';

	// Resource - Image - Second Level
	$library .= '<div class="library-asset rounded-borders-full border-grey library-collection asset-second-level">';
		$library .= '<div class="library-icon"><span class="fa fa-image"></span></div>';
		$library .= '<div class="library-title vertical-align"><a href="#">Name of a Resource that is pretty darn long, like it could seriously take up two or three lines. That is a lot.</a></div>';
		$library .= '<div class="library-author vertical-align">';
			$library .= '<img src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
			$library .= '<div class="library-author-info">';
				$library .= '<span class="member-name name">MemberHas ALongerLastName</span><span class="location">Toronto, Ontario, Canada</span>';
			$library .= '</div>';
			$library .= '<div class="member-more"><a href="#">More from this member</a></div>';
		$library .= '</div>';
		$library .= '<div class="library-rating rating vertical-align"><span class="member-rating-title">Member Rating</span>';
			$library .= '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>';
			$library .= '<a href="#">Rate this resource</a>';
		$library .= '</div>';
		$library .= '<div class="library-curriki-rating curriki-rating vertical-align"><span class="curriki-rating-title">Curriki Rating</span><span class="rating-badge">3</span></div>';
		$library .= '<div class="library-date vertical-align">Jan 5, 2012</div>';
		$library .= '<div class="library-actions vertical-align">';
			$library .= '<a href="#"><span class="fa fa-edit"></span> <span>Edit</span></a>';
			$library .= '<a href="#"><span class="fa fa-trash"></span> <span>Delete</span></a>';
			$library .= '<a href="#"><span class="fa fa-copy"></span> <span>Duplicate</span></a>';
			$library .= '<a href="#"><span class="fa fa-share-alt-square"></span> <span>Share</span></a>';
		$library .= '</div>';
	$library .= '</div>';

	// Resource - Closed Folder - Second Level
	$library .= '<div class="library-asset rounded-borders-full border-grey library-collection asset-second-level">';
		$library .= '<div class="library-icon"><span class="fa fa-folder"></span></div>';
		$library .= '<div class="library-title vertical-align"><a href="#">Name of a Resource</a></div>';
		$library .= '<div class="library-author vertical-align">';
			$library .= '<img src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
			$library .= '<div class="library-author-info">';
				$library .= '<span class="member-name name">Member Name</span><span class="location">Toronto, Ontario, Canada</span>';
			$library .= '</div>';
			$library .= '<div class="member-more"><a href="#">More from this member</a></div>';
		$library .= '</div>';
		$library .= '<div class="library-rating rating vertical-align"><span class="member-rating-title">Member Rating</span>';
			$library .= '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>';
			$library .= '<a href="#">Rate this resource</a>';
		$library .= '</div>';
		$library .= '<div class="library-curriki-rating curriki-rating vertical-align"><span class="curriki-rating-title">Curriki Rating</span><span class="rating-badge">3</span></div>';
		$library .= '<div class="library-date vertical-align">Jan 5, 2012</div>';
		$library .= '<div class="library-actions vertical-align">';
			$library .= '<a href="#"><span class="fa fa-edit"></span> <span>Edit</span></a>';
			$library .= '<a href="#"><span class="fa fa-trash"></span> <span>Delete</span></a>';
			$library .= '<a href="#"><span class="fa fa-copy"></span> <span>Duplicate</span></a>';
			$library .= '<a href="#"><span class="fa fa-share-alt-square"></span> <span>Share</span></a>';
		$library .= '</div>';
	$library .= '</div>';

	// Resource - Open Folder - Second Level
	$library .= '<div class="library-asset rounded-borders-full border-grey library-collection asset-second-level">';
		$library .= '<div class="library-icon"><span class="fa fa-folder-open"></span></div>';
		$library .= '<div class="library-title vertical-align"><a href="#">Name of a Resource that is a folder and it is much longer</a></div>';
		$library .= '<div class="library-author vertical-align">';
			$library .= '<img src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
			$library .= '<div class="library-author-info">';
				$library .= '<span class="member-name name">Member Name</span><span class="location">Toronto, Ontario, Canada</span>';
			$library .= '</div>';
			$library .= '<div class="member-more"><a href="#">More from this member</a></div>';
		$library .= '</div>';
		$library .= '<div class="library-rating rating vertical-align"><span class="member-rating-title">Member Rating</span>';
			$library .= '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>';
			$library .= '<a href="#">Rate this resource</a>';
		$library .= '</div>';
		$library .= '<div class="library-curriki-rating curriki-rating vertical-align"><span class="curriki-rating-title">Curriki Rating</span><span class="rating-badge">3</span></div>';
		$library .= '<div class="library-date vertical-align">Jan 5, 2012</div>';
		$library .= '<div class="library-actions vertical-align">';
			$library .= '<a href="#"><span class="fa fa-edit"></span> <span>Edit</span></a>';
			$library .= '<a href="#"><span class="fa fa-trash"></span> <span>Delete</span></a>';
			$library .= '<a href="#"><span class="fa fa-copy"></span> <span>Duplicate</span></a>';
			$library .= '<a href="#"><span class="fa fa-share-alt-square"></span> <span>Share</span></a>';
		$library .= '</div>';
	$library .= '</div>';

	// Resource - Image - Third Level
	$library .= '<div class="library-asset rounded-borders-full border-grey library-collection asset-third-level">';
		$library .= '<div class="library-icon"><span class="fa fa-image"></span></div>';
		$library .= '<div class="library-title vertical-align"><a href="#">Name of a Resource that is pretty darn long, like it could seriously take up two or three lines. That is a lot.</a></div>';
		$library .= '<div class="library-author vertical-align">';
			$library .= '<img src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
			$library .= '<div class="library-author-info">';
				$library .= '<span class="member-name name">Member Name</span><span class="location">Toronto, Ontario, Canada</span>';
			$library .= '</div>';
			$library .= '<div class="member-more"><a href="#">More from this member</a></div>';
		$library .= '</div>';
		$library .= '<div class="library-rating rating vertical-align"><span class="member-rating-title">Member Rating</span>';
			$library .= '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>';
			$library .= '<a href="#">Rate this resource</a>';
		$library .= '</div>';
		$library .= '<div class="library-curriki-rating curriki-rating vertical-align"><span class="curriki-rating-title">Curriki Rating</span><span class="rating-badge">3</span></div>';
		$library .= '<div class="library-date vertical-align">Jan 5, 2012</div>';
		$library .= '<div class="library-actions vertical-align">';
			$library .= '<a href="#"><span class="fa fa-edit"></span> <span>Edit</span></a>';
			$library .= '<a href="#"><span class="fa fa-trash"></span> <span>Delete</span></a>';
			$library .= '<a href="#"><span class="fa fa-copy"></span> <span>Duplicate</span></a>';
			$library .= '<a href="#"><span class="fa fa-share-alt-square"></span> <span>Share</span></a>';
		$library .= '</div>';
	$library .= '</div>';

	$library .= '<div class="library-asset rounded-borders-full border-grey library-collection asset-third-level hover">';
		$library .= '<div class="library-icon"><span class="fa fa-image"></span></div>';
		$library .= '<div class="library-title vertical-align"><a href="#">Name of a Resource</a></div>';
		$library .= '<div class="library-author vertical-align">';
			$library .= '<img src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
			$library .= '<div class="library-author-info">';
				$library .= '<span class="member-name name">Member Name</span><span class="location">Toronto, Ontario, Canada</span>';
			$library .= '</div>';
			$library .= '<div class="member-more"><a href="#">More from this member</a></div>';
		$library .= '</div>';
		$library .= '<div class="library-rating rating vertical-align"><span class="member-rating-title">Member Rating</span>';
			$library .= '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>';
			$library .= '<a href="#">Rate this resource</a>';
		$library .= '</div>';
		$library .= '<div class="library-curriki-rating curriki-rating vertical-align"><span class="curriki-rating-title">Curriki Rating</span><span class="rating-badge">3</span></div>';
		$library .= '<div class="library-date vertical-align">Jan 5, 2012</div>';
		$library .= '<div class="library-actions vertical-align">';
			$library .= '<a href="#"><span class="fa fa-edit"></span> <span>Edit</span></a>';
			$library .= '<a href="#"><span class="fa fa-trash"></span> <span>Delete</span></a>';
			$library .= '<a href="#"><span class="fa fa-copy"></span> <span>Duplicate</span></a>';
			$library .= '<a href="#"><span class="fa fa-share-alt-square"></span> <span>Share</span></a>';
		$library .= '</div>';
	$library .= '</div>';

	// Collection - Half Size
	$library .= '<div class="library-asset rounded-borders-full border-grey library-collection grid_6">';
		$library .= '<div class="library-icon grid_2 alpha"><span class="fa fa-folder-open"></span></div>';
		$library .= '<div class="library-title grid_4 vertical-align"><a href="#">Name of a Collection</a></div>';
		$library .= '<div class="library-author grid_1">';
			$library .= '<img class="grid_3 vertical-align" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
			$library .= '<div class="library-author-info grid_9 vertical-align">';
				$library .= '<span class="member-name name">Member Name</span><span class="occupation">Occupation</span><span class="location">City, State, Country</span>';
			$library .= '</div>';
		$library .= '</div>';
		$library .= '<div class="library-rating rating grid_2 vertical-align">';
			$library .= '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>';
			$library .= '<a href="#">Rate this resource</a>';
		$library .= '</div>';
		$library .= '<div class="library-curriki-rating curriki-rating grid_1 vertical-align"><span class="rating-badge">3</span></div>';
		$library .= '<div class="library-date grid_1 vertical-align">Jan 5, 2012</div>';
		$library .= '<div class="library-actions grid_1 vertical-align omega">';
			$library .= '<a href="#"><span class="fa fa-edit"></span> <span>Edit</span></a>';
			$library .= '<a href="#"><span class="fa fa-trash"></span> <span>Delete</span></a>';
			$library .= '<a href="#"><span class="fa fa-copy"></span> <span>Duplicate</span></a>';
			$library .= '<a href="#"><span class="fa fa-share-alt-square"></span> <span>Share</span></a>';
		$library .= '</div>';
	$library .= '</div>';

	// Collection - Quarter Size
	$library .= '<div class="library-asset rounded-borders-full border-grey library-collection grid_3">';
		$library .= '<div class="library-icon alpha"><span class="fa fa-folder-open"></span></div>';
		$library .= '<div class="library-title"><a href="#">Name of a Collection Spanning Two Lines</a></div>';
		$library .= '<div class="library-author">';
			$library .= '<img class="vertical-align" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
			$library .= '<div class="library-author-info vertical-align">';
				$library .= '<span class="member-name name">Member Name</span><span class="occupation">Occupation</span><span class="location">City, State, Country</span>';
			$library .= '</div>';
		$library .= '</div>';
		$library .= '<div class="library-rating rating">';
			$library .= '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>';
			$library .= '<a href="#">Rate this resource</a>';
		$library .= '</div>';
		$library .= '<div class="library-curriki-rating curriki-rating"><span class="curriki-rating-title">Curriki Rating</span><span class="rating-badge">3</span></div>';
		$library .= '<div class="library-date vertical-align">Jan 5, 2012</div>';
		$library .= '<div class="library-actions omega">';
			$library .= '<a href="#"><span class="fa fa-edit"></span> <span>Edit</span></a>';
			$library .= '<a href="#"><span class="fa fa-trash"></span> <span>Delete</span></a>';
			$library .= '<a href="#"><span class="fa fa-copy"></span> <span>Duplicate</span></a>';
			$library .= '<a href="#"><span class="fa fa-share-alt-square"></span> <span>Share</span></a>';
		$library .= '</div>';
	$library .= '</div>';

	echo $library;

	echo '<div class="clearfix" style="margin-bottom: 50px;"></div>';

}


function curriki_collection_demo_display() {

	echo '<h2>Collections</h2>';

	$collections = '';

	// Regular Collection
	$collections .= '<div class="collection-card card rounded-borders-full border-grey library-collection">';
		$collections .= '<div class="collection-body">';
			$collections .= '<div class="collection-image">';
				$collections .= '<img src="http://placehold.it/120x100" alt="group-name" />';
			$collections .= '</div>';
			$collections .= '<div class="collection-body-inner">';
				$collections .= '<div class="collection-body-title">';
					$collections .= '<div class="collection-title">';
						$collections .= '<h3><a href="#">Name of Collection</a></h3>';
						$collections .= '<span class="collection-grade"><strong>Grades 3-5</strong> (ages 8-10)</span>';
					$collections .= '</div>';
					$collections .= '<div class="collection-author">';
						$collections .= '<img class="alignleft" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
						$collections .= '<span class="member-name name vertical-align">Member Name</span>';
					$collections .= '</div>';
				$collections .= '</div>';
				$collections .= '<div class="collection-body-content">';
					$collections .= '<div class="collection-description">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the standard dummy text ever since the 1500s, when an unknown...,</div>';
					$collections .= '<div class="collection-rating rating">';
						$collections .= '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>';
						$collections .= '<a href="#">Rate this collection</a>';
					$collections .= '</div>';
					$collections .= '<div class="collection-curriki-rating curriki-rating"><span class="curriki-rating-title">Curriki Rating</span><span class="rating-badge">3</span></div>';
				$collections .= '</div>';
			$collections .= '</div>';
		$collections .= '</div>';
		$collections .= '<div class="collection-actions" id="collection-tabs">';
			$collections .= '<div class="more-collection-info"><span class="fa fa-caret-down"></span> More Info</div>';
			$collections .= '<div class="share-collection"><span class="fa fa-share-alt-square"></span> Share</div>';
			$collections .= '<div class="add-to-library"><span class="fa fa-plus-circle"></span> Add <span class="hide-small">to my Library</span></div>';
		$collections .= '</div>';
	$collections .= '</div>';

	// Regular Collection Hover States
	$collections .= '<h3>Hover</h3>';
	$collections .= '<div class="collection-card card rounded-borders-full border-grey library-collection hover">';
		$collections .= '<div class="collection-body">';
			$collections .= '<div class="collection-image">';
				$collections .= '<img src="http://placehold.it/120x100" alt="group-name" />';
			$collections .= '</div>';
			$collections .= '<div class="collection-body-inner">';
				$collections .= '<div class="collection-body-title">';
					$collections .= '<div class="collection-title">';
						$collections .= '<h3><a href="#">Name of Collection</a></h3>';
						$collections .= '<span class="collection-grade"><strong>Grades 3-5</strong> (ages 8-10)</span>';
					$collections .= '</div>';
					$collections .= '<div class="collection-author">';
						$collections .= '<img class="alignleft" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
						$collections .= '<span class="member-name name vertical-align">Member Name</span>';
					$collections .= '</div>';
				$collections .= '</div>';
				$collections .= '<div class="collection-body-content">';
					$collections .= '<div class="collection-description">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the standard dummy text ever since the 1500s, when an unknown...,</div>';
					$collections .= '<div class="collection-rating rating">';
						$collections .= '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>';
						$collections .= '<a href="#">Rate this collection</a>';
					$collections .= '</div>';
					$collections .= '<div class="collection-curriki-rating curriki-rating"><span class="curriki-rating-title">Curriki Rating</span><span class="rating-badge">3</span></div>';
				$collections .= '</div>';
			$collections .= '</div>';
		$collections .= '</div>';
		$collections .= '<div class="collection-actions" id="collection-tabs">';
			$collections .= '<div class="more-collection-info"><span class="fa fa-caret-down"></span> More Info</div>';
			$collections .= '<div class="share-collection"><span class="fa fa-share-alt-square"></span> Share</div>';
			$collections .= '<div class="add-to-library"><span class="fa fa-plus-circle"></span> Add <span class="hide-small">to my Library</span></div>';
		$collections .= '</div>';
	$collections .= '</div>';

	// Regular Collection More Information
	$collections .= '<h3>More Information</h3>';
	$collections .= '<div class="collection-card card rounded-borders-full border-grey library-collection">';
		$collections .= '<div class="collection-body">';
			$collections .= '<div class="collection-image">';
				$collections .= '<img src="http://placehold.it/120x100" alt="group-name" />';
			$collections .= '</div>';
			$collections .= '<div class="collection-body-inner">';
				$collections .= '<div class="collection-body-title">';
					$collections .= '<div class="collection-title">';
						$collections .= '<h3><a href="#">Name of Collection</a></h3>';
						$collections .= '<span class="collection-grade"><strong>Grades 3-5</strong> (ages 8-10)</span>';
					$collections .= '</div>';
					$collections .= '<div class="collection-author">';
						$collections .= '<img class="alignleft" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
						$collections .= '<span class="member-name name vertical-align">Member Name</span>';
					$collections .= '</div>';
				$collections .= '</div>';
				$collections .= '<div class="collection-body-content">';
					$collections .= '<div class="collection-description">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the standard dummy text ever since the 1500s, when an unknown...,</div>';
					$collections .= '<div class="collection-rating rating">';
						$collections .= '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>';
						$collections .= '<a href="#">Rate this collection</a>';
					$collections .= '</div>';
					$collections .= '<div class="collection-curriki-rating curriki-rating"><span class="curriki-rating-title">Curriki Rating</span><span class="rating-badge">3</span></div>';
				$collections .= '</div>';
			$collections .= '</div>';
		$collections .= '</div>';
		$collections .= '<div class="collection-more-info">';
			$collections .= '<div class="collection-views-license">';
				$collections .= '<p>2567 Views</p>';
				$collections .= '<p>22 Collections</p>';
				$collections .= '<p><img src="' . get_stylesheet_directory_uri() . '/images/cc-ncsa.png" /></p>';
			$collections .= '</div>';
			$collections .= '<div class="collection-more-info-content">';
				$collections .= '<strong>Alignment:</strong> CCSS Math, TX TEKS Math';
				$collections .= '<div class="collection-resources">';
					$collections .= '<strong>Resources in Collection:</strong>';
					$collections .= '<ul>
							<li>Resource Name 1</li>
							<li>Resource Name 2</li>
							<li>Resource Name 3</li>
							<li>Resource Name 4</li>
							<li>Resource Name 5</li>
							<li>Resource Name 6</li>
							<li>Resource Name 7</li>
							<li>Resource Name 8</li>
							<li>Resource Name 9</li>
							<li>Resource Name 10</li>
						</ul>';
				$collections .= '</div>';
			$collections .= '</div>';
		$collections .= '</div>';
		$collections .= '<div class="collection-actions" id="collection-tabs">';
			$collections .= '<div class="more-collection-info"><span class="fa fa-caret-up"></span> Less Info</div>';
			$collections .= '<div class="share-collection"><span class="fa fa-share-alt-square"></span> Share</div>';
			$collections .= '<div class="add-to-library"><span class="fa fa-plus-circle"></span> Add <span class="hide-small">to my Library</span></div>';
		$collections .= '</div>';
	$collections .= '</div>';

	// Regular Collection More Information
	$collections .= '<h3>Share Colelction</h3>';
	$collections .= '<div class="collection-card card rounded-borders-full border-grey library-collection">';
		$collections .= '<div class="collection-body">';
			$collections .= '<div class="collection-image">';
				$collections .= '<img src="http://placehold.it/120x100" alt="group-name" />';
			$collections .= '</div>';
			$collections .= '<div class="collection-body-inner">';
				$collections .= '<div class="collection-body-title">';
					$collections .= '<div class="collection-title">';
						$collections .= '<h3><a href="#">Name of Collection</a></h3>';
						$collections .= '<span class="collection-grade"><strong>Grades 3-5</strong> (ages 8-10)</span>';
					$collections .= '</div>';
					$collections .= '<div class="collection-author">';
						$collections .= '<img class="alignleft" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
						$collections .= '<span class="member-name name vertical-align">Member Name</span>';
					$collections .= '</div>';
				$collections .= '</div>';
				$collections .= '<div class="collection-body-content">';
					$collections .= '<div class="collection-description">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the standard dummy text ever since the 1500s, when an unknown...,</div>';
					$collections .= '<div class="collection-rating rating">';
						$collections .= '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>';
						$collections .= '<a href="#">Rate this collection</a>';
					$collections .= '</div>';
					$collections .= '<div class="collection-curriki-rating curriki-rating"><span class="curriki-rating-title">Curriki Rating</span><span class="rating-badge">3</span></div>';
				$collections .= '</div>';
			$collections .= '</div>';
		$collections .= '</div>';
		$collections .= '<div class="collection-actions" id="collection-tabs">';
			$collections .= '<div class="more-collection-info"><span class="fa fa-caret-up"></span> Less Info</div>';
			$collections .= '<div class="share-collection"><span class="fa fa-share-alt-square"></span> Share</div>';
			$collections .= '<div class="add-to-library"><span class="fa fa-plus-circle"></span> Add <span class="hide-small">to my Library</span></div>';
		$collections .= '</div>';
		$collections .= '<div class="collection-share">';
			$collections .= '<div class="collection-share-buttons share-icons">';
			$collections .= '<p>Share this link via</p>';
				$collections .= '<a href="#" class="share-facebook"><span class="fa fa-facebook"></span></a>';
				$collections .= '<a href="#" class="share-twitter"><span class="fa fa-twitter"></span></a>';
				$collections .= '<a href="#" class="share-pinterest"><span class="fa fa-pinterest"></span></a>';
				$collections .= '<a href="#" class="share-email"><span class="fa fa-envelope-o"></span></a>';
			$collections .= '</div>';
			$collections .= '<div class="collection-share-link">';
			$collections .= '<p>Or copy and paste this link</p>';
			$collections .= '<input type="text" value="http://bit.ly/curriki-link" readonly />';
			$collections .= '</div>';
		$collections .= '</div>';
	$collections .= '</div>';

	// Regular Collection Grid_8
	$collections .= '<h3>2/3 Width</h3>';
	$collections .= '<div class="collection-card card rounded-borders-full border-grey library-collection responsive-800" style="max-width: 800px;">';
		$collections .= '<div class="collection-body">';
			$collections .= '<div class="collection-image">';
				$collections .= '<img src="http://placehold.it/120x100" alt="group-name" />';
			$collections .= '</div>';
			$collections .= '<div class="collection-body-inner">';
				$collections .= '<div class="collection-body-title">';
					$collections .= '<div class="collection-title">';
						$collections .= '<h3><a href="#">Name of Collection</a></h3>';
						$collections .= '<span class="collection-grade"><strong>Grades 3-5</strong> (ages 8-10)</span>';
					$collections .= '</div>';
					$collections .= '<div class="collection-author">';
						$collections .= '<img class="alignleft" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
						$collections .= '<span class="member-name name vertical-align">Member Name</span>';
					$collections .= '</div>';
				$collections .= '</div>';
				$collections .= '<div class="collection-body-content">';
					$collections .= '<div class="collection-description">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the standard dummy text ever since the 1500s, when an unknown...,</div>';
					$collections .= '<div class="collection-rating rating">';
						$collections .= '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>';
						$collections .= '<a href="#">Rate this collection</a>';
					$collections .= '</div>';
					$collections .= '<div class="collection-curriki-rating curriki-rating"><span class="curriki-rating-title">Curriki Rating</span><span class="rating-badge">3</span></div>';
				$collections .= '</div>';
			$collections .= '</div>';
		$collections .= '</div>';
		$collections .= '<div class="collection-actions" id="collection-tabs">';
			$collections .= '<div class="more-collection-info"><span class="fa fa-caret-down"></span> More Info</div>';
			$collections .= '<div class="share-collection"><span class="fa fa-share-alt-square"></span> Share</div>';
			$collections .= '<div class="add-to-library"><span class="fa fa-plus-circle"></span> Add <span class="hide-small">to my Library</span></div>';
		$collections .= '</div>';
	$collections .= '</div>';

	$collections .= '<div class="clearfix"></div>';

	// Regular Collection Grid_6
	$collections .= '<h3>1/2 Width</h3>';
	$collections .= '<div class="collection-card card rounded-borders-full border-grey library-collection responsive-600" style="max-width: 600px;">';
		$collections .= '<div class="collection-body">';
			$collections .= '<div class="collection-image">';
				$collections .= '<img src="http://placehold.it/120x100" alt="group-name" />';
			$collections .= '</div>';
			$collections .= '<div class="collection-body-inner">';
				$collections .= '<div class="collection-body-title">';
					$collections .= '<div class="collection-title">';
						$collections .= '<h3><a href="#">Name of Collection</a></h3>';
						$collections .= '<span class="collection-grade"><strong>Grades 3-5</strong> (ages 8-10)</span>';
					$collections .= '</div>';
					$collections .= '<div class="collection-author">';
						$collections .= '<img class="alignleft" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
						$collections .= '<span class="member-name name vertical-align">Member Name</span>';
					$collections .= '</div>';
				$collections .= '</div>';
				$collections .= '<div class="collection-body-content">';
					$collections .= '<div class="collection-description">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the standard dummy text ever since the 1500s, when an unknown...,</div>';
					$collections .= '<div class="collection-rating rating">';
						$collections .= '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>';
						$collections .= '<a href="#">Rate this collection</a>';
					$collections .= '</div>';
					$collections .= '<div class="collection-curriki-rating curriki-rating"><span class="curriki-rating-title">Curriki Rating</span><span class="rating-badge">3</span></div>';
				$collections .= '</div>';
			$collections .= '</div>';
		$collections .= '</div>';
		$collections .= '<div class="collection-actions" id="collection-tabs">';
			$collections .= '<div class="more-collection-info"><span class="fa fa-caret-down"></span> More Info</div>';
			$collections .= '<div class="share-collection"><span class="fa fa-share-alt-square"></span> Share</div>';
			$collections .= '<div class="add-to-library"><span class="fa fa-plus-circle"></span> Add <span class="hide-small">to my Library</span></div>';
		$collections .= '</div>';
	$collections .= '</div>';

	$collections .= '<div class="clearfix"></div>';

	// Regular Collection Grid_3
	$collections .= '<h3>1/4 Width</h3>';
	$collections .= '<div class="collection-card card rounded-borders-full border-grey library-collection responsive-300" style="max-width: 300px;">';
		$collections .= '<div class="collection-body">';
			$collections .= '<div class="collection-image">';
				$collections .= '<img src="http://placehold.it/120x100" alt="group-name" />';
			$collections .= '</div>';
			$collections .= '<div class="collection-body-inner">';
				$collections .= '<div class="collection-body-title">';
					$collections .= '<div class="collection-title">';
						$collections .= '<h3><a href="#">Name of Collection</a></h3>';
						$collections .= '<span class="collection-grade"><strong>Grades 3-5</strong> (ages 8-10)</span>';
					$collections .= '</div>';
					$collections .= '<div class="collection-author">';
						$collections .= '<img class="alignleft" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
						$collections .= '<span class="member-name name vertical-align">Member Name</span>';
					$collections .= '</div>';
				$collections .= '</div>';
				$collections .= '<div class="collection-body-content">';
					$collections .= '<div class="collection-description">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the standard dummy text ever since the 1500s, when an unknown...,</div>';
					$collections .= '<div class="collection-rating rating">';
						$collections .= '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>';
						$collections .= '<a href="#">Rate this collection</a>';
					$collections .= '</div>';
					$collections .= '<div class="collection-curriki-rating curriki-rating"><span class="curriki-rating-title">Curriki Rating</span><span class="rating-badge">3</span></div>';
				$collections .= '</div>';
			$collections .= '</div>';
		$collections .= '</div>';
		$collections .= '<div class="collection-actions" id="collection-tabs">';
			$collections .= '<div class="more-collection-info"><span class="fa fa-caret-down"></span> More Info</div>';
			$collections .= '<div class="share-collection"><span class="fa fa-share-alt-square"></span> Share</div>';
			$collections .= '<div class="add-to-library"><span class="fa fa-plus-circle"></span> Add <span class="hide-small">to my Library</span></div>';
		$collections .= '</div>';
	$collections .= '</div>';

	echo $collections;

	echo '<div class="clearfix" style="margin-bottom: 50px;"></div>';

}


function curriki_color_type_demo_display() {

	echo '<h2>Color Palette</h2>';

	$colors = '';

	// Colors
	$colors .= '<table class="color-palette">';
		$colors .= '<tr><td style="background:#F1F1F1;">#F1F1F1</td><td style="background:#A7A9AC;">#A7A9AC</td><td style="background:#BCBEC0;">#BCBEC0</td></tr>';
		$colors .= '<tr><td style="background:#3463C2;">#3463C2</td><td style="background:#1D3998;">#1D3998</td><td style="background:#031770;">#031770</td></tr>';
		$colors .= '<tr><td style="background:#00A8C8;">#00A8C8</td><td style="background:#106F8E;">#106F8E</td><td style="background:#124C72;">#124C72</td><td style="background:#003956;">#003956</td></tr>';
		$colors .= '<tr><td style="background:#F0CA43;">#F0CA43</td><td style="background:#D1B643;">#D1B643</td><td style="background:#9B8840;">#9B8840</td></tr>';
		$colors .= '<tr><td style="background:#99C736;">#99C736</td><td style="background:#7DA941;">#7DA941</td><td style="background:#59722E;">#59722E</td></tr>';
		$colors .= '<tr><td style="background:#BD48B9;">#BD48B9</td><td style="background:#9F4E92;">#9F4E92</td><td style="background:#683564;">#683564</td></tr>';
		$colors .= '<tr><td style="background:#FF5541;">#FF5541</td><td style="background:#C45543;">#C45543</td><td style="background:#8E483E;">#8E483E</td></tr>';
	$colors .= '</table>';

	echo $colors;

	echo '<div class="clearfix" style="margin-bottom: 50px;"></div>';

	echo '<h2>Font Styles</h2>';

	$fonts = '';

	// Fonts
	$fonts .= '<div class="fonts">';
		$fonts .= '<h1>H1 Headline</h1>';
$fonts .= '
<pre>
	color: #393938;
	font-size: 2.25em;
	font-family: Museo, serif;
	font-weight: 300;
	line-height: 1.96em;
</pre>';
		$fonts .= '<h2>H2 Headline</h2>';
$fonts .= '
<pre>
	color: #393938;
	font-size: 1.5em;
	font-family: Museo, serif;
	font-weight: 500;
	line-height: 1.31em;
</pre>';
		$fonts .= '<h3>H3 Headline</h3>';
$fonts .= '
<pre>
	color: #414042;
	font-size: 1.5em;
	font-family: Proxima Nova, sans-serif;
	font-weight: 700;
	line-height: 1.45em;
</pre>';
		$fonts .= '<h4>H4 Headline</h4>';
$fonts .= '
<pre>
	color: #70706E;
	font-size: 1.31em;
	font-family: Proxima Nova, sans-serif;
	font-weight: 300;
	line-height: 1.27em;
</pre>';
		$fonts .= '<h5>H5 Headline</h5>';
$fonts .= '
<pre>
	color: #70706E;
	font-size: 1.13em;
	font-family: Proxima Nova, sans-serif;
	font-weight: 300;
	line-height: 1.23em;
</pre>';
		$fonts .= '<h6>H6 Headline</h6>';
$fonts .= '
<pre>
	color: #393938;
	font-size: 0.88em;
	font-family: Proxima Nova, sans-serif;
	font-weight: 500;
	line-height: 0.95em;
	text-decoration: underline;
</pre>';
	$fonts .= '</div>';

	echo $fonts;

	echo '<div class="clearfix" style="margin-bottom: 50px;"></div>';

}






genesis();