<?php
/*
* Template Name: zz Add/Edit Collection Template
*
* Child Theme Name: Curriki Child Theme for Genesis 2.1
* Author: Orange Blossom Media
* Url: http://orangeblossommedia.com/
*/

// Add custom body class to the head
add_filter( 'body_class', 'curriki_create_collection_add_body_class' );
function curriki_create_collection_add_body_class( $classes ) {
   $classes[] = 'backend create-collection';
   return $classes;

}

// Execute custom style guide page
add_action( 'genesis_meta', 'curriki_custom_create_collection_loop' );
function curriki_custom_create_collection_loop() {
	//* Force full-width-content layout setting
	add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

	remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
	remove_action( 'genesis_loop', 'genesis_do_loop' );

	add_action( 'genesis_loop', 'curriki_create_collection_body', 15 );
}

function curriki_create_collection_body() {

	echo '<div class="collection-content clearfix"><div class="wrap grid_12">';

	// Describe
	$describe_tab = '';
	$describe_tab .= '<div id="describe" class="tab-contents">';
		$describe_tab .= '<h3 class="section-header">Add/Edit Collection</h3>';
		$describe_tab .= '<div class="describe-contents grid_10 alpha">';
			$describe_tab .= '<p class="desc">What details will best describe and present this collection in search results and other listings?</p>';
			// Resource Description
			$describe_tab .= '<div class="collection-content-section"><h4>Title</h4><div class="tooltip fa fa-question-circle" id="collection-title"></div>';
				$describe_tab .= '<input class="collection-title" id="collection-title" placeholder="'.__('Enter Resource Title','curriki').'" />';
			$describe_tab .= '</div>';
			$describe_tab .= '<div class="collection-content-section"><h4>Description</h4><div class="tooltip fa fa-question-circle" id="collection-description"></div>';
				$describe_tab .= '<textarea></textarea>';
			$describe_tab .= '</div>';
			// Resource Subject & Education Level
			$describe_tab .= '<div class="grid_12 alpha omega">';
				$describe_tab .= '<div class="grid_6 alpha">';
					$describe_tab .= '<div class="collection-content-section"><h4>Subject</h4><div class="tooltip fa fa-question-circle" id="collection-subject"></div>';
						$describe_tab .= '
							<ul>
								<li><input type="checkbox" id="collection-skill" />Arts</li>
								<li><input type="checkbox" id="collection-skill" />Career & Technology</li>
								<li><input type="checkbox" id="collection-skill" />Education</li>
								<li><input type="checkbox" id="collection-skill" />Educational Technology</li>
								<li><input type="checkbox" id="collection-skill" />Health</li>
								<li><input type="checkbox" id="collection-skill" />Information & Media Literacy</li>
								<li><input type="checkbox" id="collection-skill" />Language Arts</li>
								<li><input type="checkbox" id="collection-skill" />Mathematics</li>
								<li><input type="checkbox" id="collection-skill" />Science</li>
								<li><input type="checkbox" id="collection-skill" />Social Studies</li>
								<li><input type="checkbox" id="collection-skill" />World Language</li>
							</ul>
						';
					$describe_tab .= '</div>';
				$describe_tab .= '</div>';
				$describe_tab .= '<div class="grid_6 omega">';
					$describe_tab .= '<div class="collection-content-section"><h4>Education Level</h4><div class="tooltip fa fa-question-circle" id="collection-education-level"></div>';
						$describe_tab .= '
							<ul>
								<li><input type="checkbox" id="collection-education-level" />Preschool(Ages 0-4)</li>
								<li><input type="checkbox" id="collection-education-level" />Kindergarten-Grade 2(Ages 5-7)</li>
								<li><input type="checkbox" id="collection-education-level" />Grades 3-5 <span class="ages">(Ages 8-10)</span></li>
								<li><input type="checkbox" id="collection-education-level" />Grades 6-8 <span class="ages">(Ages 11-13)</span></li>
								<li><input type="checkbox" id="collection-education-level" />Grades 9-10 <span class="ages">(Ages 14-16)</span></li>
								<li><input type="checkbox" id="collection-education-level" />Grades 11-12 <span class="ages">(Ages 16-18)</span></li>
								<li><input type="checkbox" id="collection-education-level" />College & Beyond</li>
								<li><input type="checkbox" id="collection-education-level" />Professional Development</li>
								<li><input type="checkbox" id="collection-education-level" />Special Education</li>
							</ul>
						';
					$describe_tab .= '</div>';
				$describe_tab .= '</div>';
			$describe_tab .= '</div>';
			// Keywords
			$describe_tab .= '<div class="collection-content-section"><h4>Keywords</h4><div class="tooltip fa fa-question-circle" id="collection-keywords"></div>';
				$describe_tab .= '<input type="text" id="collection-keywords" value="Auto fill based on above questions" />';
			$describe_tab .= '</div>';
			// Type
			$describe_tab .= '<div class="collection-content-section"><h4>Type</h4><div class="tooltip fa fa-question-circle" id="collection-type"></div>';
				$describe_tab .= '<p class="desc">Scroll to find the best option. You can select multiple options by hitting the Ctrl (PC) or Apple (Mac) key as you click to select. Click here for definitions.</p>';
				$describe_tab .= '
					<ul class="three-col">
						<li><input type="checkbox" id="collection-type" />Activity: Game</li>
						<li><input type="checkbox" id="collection-type" />Activity: Graphic Organizer/Worksheet Asset: Interactive</li>
						<li><input type="checkbox" id="collection-type" />Asset: Article/Essay</li>
						<li><input type="checkbox" id="collection-type" />Asset: Audio Recording</li>
						<li><input type="checkbox" id="collection-type" />Asset: Diagram/Illustration/Map</li>
						<li><input type="checkbox" id="collection-type" />Asset: Vocabulary</li>
						<li><input type="checkbox" id="collection-type" />Asset: Photograph</li>
						<li><input type="checkbox" id="collection-type" />Asset: Table/Graph/Chart</li>
						<li><input type="checkbox" id="collection-type" />Asset: Presentation</li>
						<li><input type="checkbox" id="collection-type" />Asset: Video</li>
						<li><input type="checkbox" id="collection-type" />Book: Fiction</li>
						<li><input type="checkbox" id="collection-type" />Book: Non-Fiction</li>
						<li><input type="checkbox" id="collection-type" />Book: Readings/Excerpts</li>
						<li><input type="checkbox" id="collection-type" />Book: Text Book</li>
						<li><input type="checkbox" id="collection-type" />Curriculum: Assessment</li>
						<li><input type="checkbox" id="collection-type" />Curriculum: Full Course</li>
						<li><input type="checkbox" id="collection-type" />Curriculum: Lesson Plan</li>
						<li><input type="checkbox" id="collection-type" />Curriculum: Rubric</li>
						<li><input type="checkbox" id="collection-type" />Curriculum: Scope & Sequence</li>
						<li><input type="checkbox" id="collection-type" />Curriculum: Unit</li>
						<li><input type="checkbox" id="collection-type" />Other</li>
						<li><input type="checkbox" id="collection-type" />Multiple</li>
					</ul>
				';
			$describe_tab .= '</div>';
			// Align to Standards
			$describe_tab .= '<div class="collection-content-section"><h4>Align to Standards</h4><div class="tooltip fa fa-question-circle" id="collection-standards"></div>';
				$describe_tab .= '<div class="standards-alignment-box rounded-borders-full">';
					$describe_tab .= '<h4>Add a Standard</h4>';
					$describe_tab .= '<select><option>Select a Standard</option></select>';
					$describe_tab .= '<select><option>Select a Grade Level</option></select>';
					$describe_tab .= '<select><option>Select a Learning Domain</option></select>';
					$describe_tab .= '<select><option>Select an Alignment Tag</option></select>';
				$describe_tab .= '</div>';
				$describe_tab .= '<div class="standards-alignment-box rounded-borders-full">';
					$describe_tab .= '<h4>Common Core State Standards English Language Arts Grade 1, Writing</h4>';
					$describe_tab .= '<p><strong>Cluster:</strong> Text Types and Purposes.</p>';
					$describe_tab .= '<p><strong>Standard:</strong> Write narratives in which they recount two or more appropriately sequenced events, include some details regarding what happened, use temporal words to signal event order, and provide some sense of closure.</p>';
				$describe_tab .= '</div>';
				$describe_tab .= '<div class="standards-alignment-actions"><button class="small-button green-button save">Add Standard</button><button class="small-button grey-button cancel">Cancel</button></div>';
			$describe_tab .= '</div>';
		$describe_tab .= '</div>';
		$describe_tab .= '<div class="describe-save grid_2 omega">';
			$describe_tab .= '<button class="collection-button small-button green-button submit"><strong>Save</strong></button>';
			$describe_tab .= '<button class="collection-button small-button grey-button cancel">Cancel</button>';
		$describe_tab .= '</div>';
	$describe_tab .= '</div>';

	echo $describe_tab;


	echo '</div></div>';

}


genesis();