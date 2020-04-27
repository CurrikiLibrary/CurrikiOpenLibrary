<?php
/*
* Template Name: Rate Resource Template
*
* Child Theme Name: Curriki Child Theme for Genesis 2.1
* Author: Orange Blossom Media
* Url: http://orangeblossommedia.com/
*/

// Add custom body class to the head
add_filter( 'body_class', 'curriki_rate_resource_page_add_body_class' );
function curriki_rate_resource_page_add_body_class( $classes ) {
   $classes[] = 'backend rate-resource';
   return $classes;

}

// Execute custom style guide page
add_action( 'genesis_meta', 'curriki_custom_rate_resource_page_loop' );
function curriki_custom_rate_resource_page_loop() {
	//* Force full-width-content layout setting
	add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

	remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
	remove_action( 'genesis_loop', 'genesis_do_loop' );

	add_action( 'genesis_before', 'curriki_rate_resource_page_scripts' );
	add_action( 'genesis_loop', 'curriki_rate_resource_page_body', 15 );
}

function curriki_rate_resource_page_scripts() {

	// Enqueue JQuery Tab and Accordion scripts
   	wp_enqueue_script( 'jquery-ui-accordion' );
   	?>
	<script>
		(function( $ ) {

			"use strict";

			$(function() {

			  var icons = {
			    header: "fa-plus-circle",
			    activeHeader: "fa-minus-circle"
			  };

			  $( "#rate-resource" ).accordion({
			    collapsible: true,
			    icons: icons,
			    active: false,
			  	heightStyle: "content",
			  });


			  $( "#toggle" ).button().click(function() {
			    if ( $( "#member-info-accordion" ).accordion( "option", "icons" ) ) {
			      $( "#member-info-accordion" ).accordion( "option", "icons", null );
			    } else {
			      $( "#member-info-accordion" ).accordion( "option", "icons", icons );
			    }
			  });

			});

		}(jQuery));
	</script>
	<?php
}


function curriki_rate_resource_page_body() {

	$rate_resource = '';

	$rate_resource .= '<div class="rate-resource-modal modal border-grey rounded-borders-full grid_6">';

		$rate_resource .= '<div class="rate-resource-accordion scrollbar"><div class="wrap container_12"><div id="rate-resource">';
			$rate_resource .= '<h3 class="fa section-header">Guidelines & Reminders</h3>';
			$rate_resource .= '<div class="section-body">';
				$rate_resource .= '
				<p>Achieve has developed a series of rubrics for this tool to help gauge various aspects of quality, allowing an additional filter for sorting and another way to discover OER. These kinds of ratings are essential, especially for states and districts looking to recommend specific OER to their teachers.</p>
				<p>This tool requires the input of knowledgeable and experienced teachers and administrators to evaluate these resources to, in turn, help educators use them. Please take the time to evaluate this resource in full for the future benefit of these educators and their students.</p>
				<p>Before assessing the alignment of OER to standards, you should have access to the Common Core State Standards (CCSS). These can be found at www.corestandards.org.</p>
				<p>The rubrics are intended to be applied to the smallest, meaningful unit.</p>
				<p>Each rubric should be scored independently of the others; you may apply up to 7 rubrics to an OER.</p>
				<p>Mark “N/A” on any rubric that doesn’t apply to the resource you are evaluating.</p>
				<p>Your review of an object is complete once the ratings are submitted through the “Finalize OER Review” button at the bottom of the last rubric.</p>
				<p>By using this tool, you agree to license all of your content and comments to the public domain.</p>';
			$rate_resource .= '</div>';
			$rate_resource .= '<h3 class="fa section-header">Quality of Explanation of the Subject Matter</h3>';
			$rate_resource .= '<div class="section-body">';
				$rate_resource .= '<p>Applies to objects designed to explain subject matter. Used to rate how thoroughly subject matter is explained or otherwise revealed in the resource. Teachers might use object with whole class, small group, or individual student. Students might use this object to self-tutor.</p>';
				$rate_resource .= '
					<div class="rating-option tooltip"><input type="radio" value="3" id="3" name="rating-rubric" /><label for="3">3</label><span><p>An object is rated <em>superior</em> if all of the following are true:</p><ul><li>Materials are comprehensive and easy to understand and use. Includes suggestions for use with a variety of learners.</li><li>All components are provided and function as intended, including estimate of planning time and materials list.</li><li>For larger objects, materials facilitate mixed instructional approaches.</li></ul></span></div>
					<div class="rating-option tooltip"><input type="radio" value="2" id="2" name="rating-rubric" /><label for="2">2</label><span><p>An object is rated <em>strong</em> if it offers comprehensive, easy to understand and use materials. Falls short of Superior because either:</p><ol><li>Object does not include suggestions for ways to use materials with variety of learners, or</li><li>core components are underdeveloped in the object.</li></ol></span></div>
					<div class="rating-option tooltip"><input type="radio" value="1" id="1" name="rating-rubric" /><label for="1">1</label><span><p>An object is rated <em>limited</em> if it includes a useful approach or idea to teach an important topic but falls short of a higher rating because either:</p><ol><li>the object is missing important elements, e.g. directions for some parts of a lesson are not included or </li><li>important elements do not do what they should, e.g. directions are unclear. Teachers would need to supplement this object to use it effectively.</li></ol></span></div>
					<div class="rating-option tooltip"><input type="radio" value="0" id="0" name="rating-rubric" /><label for="0">0</label><span><p>An object is rated <em>very weak</em> or no value for instructional purposes if confusing, contains errors, is missing many important elements, or is simply not useful for some other reason.</p></span></div>
					<div class="rating-option tooltip"><input type="radio" value="N/A" id="N/A" name="rating-rubric" /><label for="N/A">N/A</label><span><p>This rubric is not applicable for an object that is not designed as a teacher’s instructional tool.</p><p>An enterprising educator may find an application for such an object during a lesson, but that would be an unintended use.</p></span></div>';
				$rate_resource .= '<h5>Rating Comment</h5><textarea class="rating-comment" placeholder="comment"></textarea>';
				$rate_resource .= '<button class="submit green-button small-button">Save</button>';
			$rate_resource .= '</div>';
			$rate_resource .= '<h3 class="fa section-header">Utility of Materials Designed to Support Teaching</h3>';
			$rate_resource .= '<div class="section-body">';
				$rate_resource .= '<p>Applies to objects designed to support teachers in planning or presenting subject matter. Primary user would be teacher. Evaluates the potential utility of an object for the majority of instructors at the intended grade level.</p>';
				$rate_resource .= '
					<div class="rating-option tooltip"><input type="radio" value="3" id="3" name="rating-rubric" /><label for="3">3</label><span><p>An object is rated <em>superior</em> if all of the following are true:</p><ul><li>Materials are comprehensive and easy to understand and use. Includes suggestions for use with a variety of learners.</li><li>All components are provided and function as intended, including estimate of planning time and materials list.</li><li>For larger objects, materials facilitate mixed instructional approaches.</li></ul></span></div>
					<div class="rating-option tooltip"><input type="radio" value="2" id="2" name="rating-rubric" /><label for="2">2</label><span><p>An object is rated <em>strong</em> if it offers comprehensive, easy to understand and use materials. Falls short of Superior because either:</p><ol><li>Object does not include suggestions for ways to use materials with variety of learners, or</li><li>core components are underdeveloped in the object.</li></ol></span></div>
					<div class="rating-option tooltip"><input type="radio" value="1" id="1" name="rating-rubric" /><label for="1">1</label><span><p>An object is rated <em>limited</em> if it includes a useful approach or idea to teach an important topic but falls short of a higher rating because either:</p><ol><li>the object is missing important elements, e.g. directions for some parts of a lesson are not included or </li><li>important elements do not do what they should, e.g. directions are unclear. Teachers would need to supplement this object to use it effectively.</li></ol></span></div>
					<div class="rating-option tooltip"><input type="radio" value="0" id="0" name="rating-rubric" /><label for="0">0</label><span><p>An object is rated <em>very weak</em> or no value for instructional purposes if confusing, contains errors, is missing many important elements, or is simply not useful for some other reason.</p></span></div>
					<div class="rating-option tooltip"><input type="radio" value="N/A" id="N/A" name="rating-rubric" /><label for="N/A">N/A</label><span><p>This rubric is not applicable for an object that is not designed as a teacher’s instructional tool.</p><p>An enterprising educator may find an application for such an object during a lesson, but that would be an unintended use.</p></span></div>';
				$rate_resource .= '<h5>Rating Comment</h5><textarea class="rating-comment" placeholder="comment"></textarea>';
				$rate_resource .= '<button class="submit green-button small-button">Save</button>';
			$rate_resource .= '</div>';
			$rate_resource .= '<h3 class="fa section-header">Quality of Assessments</h3>';
			$rate_resource .= '<div class="section-body">';
				$rate_resource .= '<p>Applies to objects designed to determine what a student knows before, during, or after a topic is taught. When many assessments are included in one object, the rubric is applied to the entire set.</p>';
				$rate_resource .= '
					<div class="rating-option tooltip"><input type="radio" value="3" id="3" name="rating-rubric" /><label for="3">3</label><span><p>An object is rated <em>superior</em> if all of the following are true:</p><ul><li>Materials are comprehensive and easy to understand and use. Includes suggestions for use with a variety of learners.</li><li>All components are provided and function as intended, including estimate of planning time and materials list.</li><li>For larger objects, materials facilitate mixed instructional approaches.</li></ul></span></div>
					<div class="rating-option tooltip"><input type="radio" value="2" id="2" name="rating-rubric" /><label for="2">2</label><span><p>An object is rated <em>strong</em> if it offers comprehensive, easy to understand and use materials. Falls short of Superior because either:</p><ol><li>Object does not include suggestions for ways to use materials with variety of learners, or</li><li>core components are underdeveloped in the object.</li></ol></span></div>
					<div class="rating-option tooltip"><input type="radio" value="1" id="1" name="rating-rubric" /><label for="1">1</label><span><p>An object is rated <em>limited</em> if it includes a useful approach or idea to teach an important topic but falls short of a higher rating because either:</p><ol><li>the object is missing important elements, e.g. directions for some parts of a lesson are not included or </li><li>important elements do not do what they should, e.g. directions are unclear. Teachers would need to supplement this object to use it effectively.</li></ol></span></div>
					<div class="rating-option tooltip"><input type="radio" value="0" id="0" name="rating-rubric" /><label for="0">0</label><span><p>An object is rated <em>very weak</em> or no value for instructional purposes if confusing, contains errors, is missing many important elements, or is simply not useful for some other reason.</p></span></div>
					<div class="rating-option tooltip"><input type="radio" value="N/A" id="N/A" name="rating-rubric" /><label for="N/A">N/A</label><span><p>This rubric is not applicable for an object that is not designed as a teacher’s instructional tool.</p><p>An enterprising educator may find an application for such an object during a lesson, but that would be an unintended use.</p></span></div>';
				$rate_resource .= '<h5>Rating Comment</h5><textarea class="rating-comment" placeholder="comment"></textarea>';
				$rate_resource .= '<button class="submit green-button small-button">Save</button>';
			$rate_resource .= '</div>';
			$rate_resource .= '<h3 class="fa section-header">Quality of Technological Interactivity</h3>';
			$rate_resource .= '<div class="section-body">';
				$rate_resource .= '<p>Applies to objects that have a technology-based interactive component. Used to rate degree and quality of an object’s interactivity. Interactivity broadly means that the object responds to the user – the object behaves differently based on what the user does. This is not a rating for technology in general, but for technological interactivity.</p>';
				$rate_resource .= '
					<div class="rating-option tooltip"><input type="radio" value="3" id="3" name="rating-rubric" /><label for="3">3</label><span><p>An object is rated <em>superior</em> if all of the following are true:</p><ul><li>Materials are comprehensive and easy to understand and use. Includes suggestions for use with a variety of learners.</li><li>All components are provided and function as intended, including estimate of planning time and materials list.</li><li>For larger objects, materials facilitate mixed instructional approaches.</li></ul></span></div>
					<div class="rating-option tooltip"><input type="radio" value="2" id="2" name="rating-rubric" /><label for="2">2</label><span><p>An object is rated <em>strong</em> if it offers comprehensive, easy to understand and use materials. Falls short of Superior because either:</p><ol><li>Object does not include suggestions for ways to use materials with variety of learners, or</li><li>core components are underdeveloped in the object.</li></ol></span></div>
					<div class="rating-option tooltip"><input type="radio" value="1" id="1" name="rating-rubric" /><label for="1">1</label><span><p>An object is rated <em>limited</em> if it includes a useful approach or idea to teach an important topic but falls short of a higher rating because either:</p><ol><li>the object is missing important elements, e.g. directions for some parts of a lesson are not included or </li><li>important elements do not do what they should, e.g. directions are unclear. Teachers would need to supplement this object to use it effectively.</li></ol></span></div>
					<div class="rating-option tooltip"><input type="radio" value="0" id="0" name="rating-rubric" /><label for="0">0</label><span><p>An object is rated <em>very weak</em> or no value for instructional purposes if confusing, contains errors, is missing many important elements, or is simply not useful for some other reason.</p></span></div>
					<div class="rating-option tooltip"><input type="radio" value="N/A" id="N/A" name="rating-rubric" /><label for="N/A">N/A</label><span><p>This rubric is not applicable for an object that is not designed as a teacher’s instructional tool.</p><p>An enterprising educator may find an application for such an object during a lesson, but that would be an unintended use.</p></span></div>';
				$rate_resource .= '<h5>Rating Comment</h5><textarea class="rating-comment" placeholder="comment"></textarea>';
				$rate_resource .= '<button class="submit green-button small-button">Save</button>';
			$rate_resource .= '</div>';
			$rate_resource .= '<h3 class="fa section-header">Quality of Instructional and Practice Exercises</h3>';
			$rate_resource .= '<div class="section-body">';
				$rate_resource .= '<p>Applies to objects that contain exercises to help foundational skills and procedures become routine. When concepts and skills are introduced, providing a sufficient number of exercises to support skill acquisition is critical. However when integrating skills in complex tasks, as few as one or two may be sufficient. A group of practice exercises is treated as a single object, with the rubric applied to the entire set.</p>';
				$rate_resource .= '
					<div class="rating-option tooltip"><input type="radio" value="3" id="3" name="rating-rubric" /><label for="3">3</label><span><p>An object is rated <em>superior</em> if all of the following are true:</p><ul><li>Materials are comprehensive and easy to understand and use. Includes suggestions for use with a variety of learners.</li><li>All components are provided and function as intended, including estimate of planning time and materials list.</li><li>For larger objects, materials facilitate mixed instructional approaches.</li></ul></span></div>
					<div class="rating-option tooltip"><input type="radio" value="2" id="2" name="rating-rubric" /><label for="2">2</label><span><p>An object is rated <em>strong</em> if it offers comprehensive, easy to understand and use materials. Falls short of Superior because either:</p><ol><li>Object does not include suggestions for ways to use materials with variety of learners, or</li><li>core components are underdeveloped in the object.</li></ol></span></div>
					<div class="rating-option tooltip"><input type="radio" value="1" id="1" name="rating-rubric" /><label for="1">1</label><span><p>An object is rated <em>limited</em> if it includes a useful approach or idea to teach an important topic but falls short of a higher rating because either:</p><ol><li>the object is missing important elements, e.g. directions for some parts of a lesson are not included or </li><li>important elements do not do what they should, e.g. directions are unclear. Teachers would need to supplement this object to use it effectively.</li></ol></span></div>
					<div class="rating-option tooltip"><input type="radio" value="0" id="0" name="rating-rubric" /><label for="0">0</label><span><p>An object is rated <em>very weak</em> or no value for instructional purposes if confusing, contains errors, is missing many important elements, or is simply not useful for some other reason.</p></span></div>
					<div class="rating-option tooltip"><input type="radio" value="N/A" id="N/A" name="rating-rubric" /><label for="N/A">N/A</label><span><p>This rubric is not applicable for an object that is not designed as a teacher’s instructional tool.</p><p>An enterprising educator may find an application for such an object during a lesson, but that would be an unintended use.</p></span></div>';
				$rate_resource .= '<h5>Rating Comment</h5><textarea class="rating-comment" placeholder="comment"></textarea>';
				$rate_resource .= '<button class="submit green-button small-button">Save</button>';
			$rate_resource .= '</div>';
			$rate_resource .= '<h3 class="fa section-header">Opportunities for Deeper Learning</h3>';
			$rate_resource .= '<div class="section-body">';
				$rate_resource .= '<p>Applies to objects that engage learners to: Think critically and solve complex problems. Reason abstractly. Work collaboratively. Learn how to learn. Communicate effectively. Construct viable arguments and critique the reasoning of others. Apply discrete knowledge and skills to real-world situations. Construct, use, or analyze models.</p>';
				$rate_resource .= '
					<div class="rating-option tooltip"><input type="radio" value="3" id="3" name="rating-rubric" /><label for="3">3</label><span><p>An object is rated <em>superior</em> if all of the following are true:</p><ul><li>Materials are comprehensive and easy to understand and use. Includes suggestions for use with a variety of learners.</li><li>All components are provided and function as intended, including estimate of planning time and materials list.</li><li>For larger objects, materials facilitate mixed instructional approaches.</li></ul></span></div>
					<div class="rating-option tooltip"><input type="radio" value="2" id="2" name="rating-rubric" /><label for="2">2</label><span><p>An object is rated <em>strong</em> if it offers comprehensive, easy to understand and use materials. Falls short of Superior because either:</p><ol><li>Object does not include suggestions for ways to use materials with variety of learners, or</li><li>core components are underdeveloped in the object.</li></ol></span></div>
					<div class="rating-option tooltip"><input type="radio" value="1" id="1" name="rating-rubric" /><label for="1">1</label><span><p>An object is rated <em>limited</em> if it includes a useful approach or idea to teach an important topic but falls short of a higher rating because either:</p><ol><li>the object is missing important elements, e.g. directions for some parts of a lesson are not included or </li><li>important elements do not do what they should, e.g. directions are unclear. Teachers would need to supplement this object to use it effectively.</li></ol></span></div>
					<div class="rating-option tooltip"><input type="radio" value="0" id="0" name="rating-rubric" /><label for="0">0</label><span><p>An object is rated <em>very weak</em> or no value for instructional purposes if confusing, contains errors, is missing many important elements, or is simply not useful for some other reason.</p></span></div>
					<div class="rating-option tooltip"><input type="radio" value="N/A" id="N/A" name="rating-rubric" /><label for="N/A">N/A</label><span><p>This rubric is not applicable for an object that is not designed as a teacher’s instructional tool.</p><p>An enterprising educator may find an application for such an object during a lesson, but that would be an unintended use.</p></span></div>';
				$rate_resource .= '<h5>Rating Comment</h5><textarea class="rating-comment" placeholder="comment"></textarea>';
				$rate_resource .= '<button class="submit green-button small-button">Save</button>';
			$rate_resource .= '</div>';
		$rate_resource .= '</div></div></div>';

		$rate_resource .= '<div class="close"><span class="fa fa-close"></span></div>';
	$rate_resource .= '</div>';

	echo $rate_resource;

}


genesis();