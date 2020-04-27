<?php
/*
 * Template Name: Resource Review Page Template
 *
 * Child Theme Name: Curriki Child Theme for Genesis 2.1
 * Author: Orange Blossom Media
 * Url: http://orangeblossommedia.com/
 */

if (!is_user_logged_in() and function_exists('curriki_redirect_login')) {
  curriki_redirect_login();
  die;
}

// Add custom body class to the head
add_filter('body_class', 'curriki_resource_page_add_body_class');

function curriki_resource_page_add_body_class($classes) {
  $classes[] = 'backend resource-page';
  return $classes;
}

// Execute custom style guide page
add_action('genesis_meta', 'curriki_custom_resource_page_loop');

function curriki_custom_resource_page_loop() {
  //* Force full-width-content layout setting
  add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');

  remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');
  remove_action('genesis_loop', 'genesis_do_loop');

  add_action('genesis_before', 'curriki_resource_page_scripts');
  //add_action('genesis_after_header', 'curriki_resource_manage_body', 10);
  //add_action('genesis_after_header', 'curriki_resource_page_body', 15);
  if (isset($_GET['rid']) || isset($_GET['pageurl'])) {

    if (isset($_GET['action']) AND trim($_GET['action']) == 'file_check')
      add_action('genesis_after_header', 'curriki_resource_file_check_body', 10);
    else
      add_action('genesis_after_header', 'curriki_resource_manage_body', 10);

    add_action('genesis_after_header', 'curriki_resource_page_body', 15);
  } else
    add_action('genesis_after_header', 'curriki_resource_page_body_search', 10);
}

function curriki_resource_page_scripts() {

  // Enqueue JQuery Tab and Accordion scripts
  wp_enqueue_script('jquery-ui-tabs');
  wp_enqueue_style('resource-font-awesome-css', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
  wp_enqueue_style('resource-legacy-css', get_stylesheet_directory_uri() . '/css/legacy.css');

  wp_enqueue_style('jquery-fancybox-css', get_stylesheet_directory_uri() . '/js/fancybox_v2.1.5/jquery.fancybox.css?v=2.1.5');
  wp_enqueue_script('jquery-fancybox-js', get_stylesheet_directory_uri() . '/js/fancybox_v2.1.5/jquery.fancybox.pack.js?v=2.1.5', 'jquery.fancybox', '2.1.5');
  wp_enqueue_script('jquery-mousewheel-js', get_stylesheet_directory_uri() . '/js/fancybox_v2.1.5/jquery.mousewheel-3.0.6.pack.js', 'jquery.mousewheel', '3.0.6');

  //wp_enqueue_style( 'jquery-mobile-css', 'https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.3/jquery.mobile.min.css', 'jquery', '1.4.3' );
  //wp_enqueue_script( 'jquery-mobile', 'https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.3/jquery.mobile.min.js', 'jquery', '1.4.3' );
  ?>
  <script>
    (function (jQuery) {
      "use strict";
      jQuery(function () {
        jQuery("#resource-tabs").tabs();
        //jQuery( "#alignments" ).listview();
      });

    }(jQuery));

    function resourceRating(star) {
      jQuery("#resource-rating-" + star).siblings().addClass('fa-star-o').removeClass('fa-star');

      for (i = 1; i <= star; i++)
      {
        jQuery("#resource-rating-" + i).addClass('fa-star');
        jQuery("#resource-rating-" + i).removeClass('fa-star-o');
      }
      jQuery("#resource-rating").val(star);
    }

    function resourceRating2(star) {
      jQuery("#resource-rating2-" + star).siblings().addClass('fa-star-o').removeClass('fa-star');

      for (i = 1; i <= star; i++)
      {
        jQuery("#resource-rating2-" + i).addClass('fa-star');
        jQuery("#resource-rating2-" + i).removeClass('fa-star-o');
      }
      jQuery("#resource-rating2").val(star);
    }

    function resourceFileDownload(id, url) {
      jQuery(document).ready(function () {
        jQuery.ajax({
          method: "POST",
          url: "<?php echo get_bloginfo('url'); ?>/oer/?resource_file_download=file",
          data: {id: id}
        })
                .done(function (msg) {
                  window.location.assign(url);
                });
      });
    }

    function resourceInappropriate(id) {
      jQuery(document).ready(function () {
        jQuery.ajax({
          method: "POST",
          url: "<?php echo get_bloginfo('url'); ?>/oer/?inappropriate=true",
          data: {id: id}
        })
                .done(function (msg) {
                  jQuery("#resourceInappropriate").hide();
                });
      });
    }

    function addToMyLibrary(id) {
      jQuery(document).ready(function () {
        jQuery.ajax({
          method: "POST",
          url: "<?php echo get_bloginfo('url'); ?>/oer/?addtolibrary=true",
          data: {id: id}
        })
                .done(function (msg) {
                  jQuery("#addtolibrary").hide();
                });
      });
    }

    function showModifiedContents() {
      jQuery("#showModificationInContents").html(jQuery("textarea#modificationInContents").val());
    }

    jQuery(document).ready(function () {
      /*jQuery(".fancybox").fancybox();*/

      /*
       jQuery(".search-fancybox").fancybox({
       type: "iframe",
       onStart: function (el, index) {
       var thisElement = jQuery(el[index]);
       jQuery.extend(this, {
       href: thisElement.data("href")
       });
       }
       });*/

      jQuery("#search-fancybox").click(function ()
      {
        document.location.href = jQuery("#search-fancybox").val();
        'http://cg.curriki.org/curriki/review/?rid=12508';
      });

    });

  </script>
  <?php
}

function curriki_resource_page_body_search() {

  $resource_search = '<div class="resource-header page-header">';
  $resource_search .= '<div class="wrap container_12">';
  $resource_search .= '<div class="resource-info page-info grid_10">';
  $resource_search .= '<h3 class="resource-title page-title">Search Resource</h3>';

  $resource_search .= '
	<form action="" method="post">
	<input type="text" name="rsearch" value="" />
	<input type="submit" value="Search" />
	</form>';


  $resource_search .= '</div>';
  $resource_search .= '</div>';
  $resource_search .= '</div>';

  echo $resource_search;
}

function curriki_resource_manage_body() {


  if (isset($_GET['rid']) || isset($_GET['pageurl'])) {
    $res = new CurrikiResources();

    if (isset($_POST['resource_reviewed']) || $_POST['resource_reviewed'])
      $res->setResourceReview();

    $resourceUser = $res->getResourceUserById((int) $_GET['rid'], rtrim($_GET['pageurl'], '/'));
    $res->setResourceViews((int) $resourceUser['resourceid']);
    //print_r($resourceUser);
  }

  $resource_header = '<div class="resource-header page-header">';
  $resource_header .= '<div class="wrap container_12">';
  if (is_user_logged_in()) {
    $resource_header .= '<div class="resource-join page-join grid_2">';
    $resource_header .= '<button id="addtolibrary" class="green-button" onclick="addToMyLibrary(' . $resourceUser['resourceid'] . ');">Next</button>';
    $resource_header .= '</div>';
  }

  $mediatype_Options = '';
  $mediatypes = $res->getMediatypes();
  foreach ($mediatypes AS $key => $value)
    if ($key == $resourceUser['mediatype'])
      $mediatype_Options .= '<option value="' . $key . '" selected="selected">' . $value . '</option>';
    else
      $mediatype_Options .= '<option value="' . $key . '">' . $value . '</option>';


  $type_Options = '';
  $types = array("collection" => "Collection");
  foreach ($types AS $key => $value)
    if ($key == $resourceUser['type'])
      $type_Options .= '<option value="' . $key . '" selected="selected">' . $value . '</option>';
    else
      $type_Options .= '<option value="' . $key . '">' . $value . '</option>';


  $resource_header .= '<br /><br /><br />
<form method="post" action="">
    <input type="hidden" value="1" name="resource_reviewed">
	<input type="hidden" value="' . $resourceUser['resourceid'] . '" name="resourceid">  
    <table width="80%">
	<tbody>
	<tr>
		<td>content<br><textarea cols="60" rows="5" id="modificationInContents" name="content" style="width: 812px; height: 162px;">' . $resourceUser['content'] . '</textarea><br /><span class="white-button" onclick="showModifiedContents();"> Show Now </span><div id="showModificationInContents"></div>
		</td>
	</tr>
	<tr>
		<td>Student Facing<br><select name="studentfacing"><option value="T"' . ( $resourceUser['studentfacing'] == 'T' ? ' selected="selected"' : '' ) . '>Yes</option><option value="F"' . ( $resourceUser['studentfacing'] == 'F' ? ' selected="selected"' : '' ) . '>No</option></select>
		</td>
	</tr>
	<tr>
		<td>Content displays as expected<br><select name="contentdisplayok"><option value="T"' . ( $resourceUser['contentdisplayok'] == 'T' ? ' selected="selected"' : '' ) . '>Yes</option><option value="F"' . ( $resourceUser['contentdisplayok'] == 'F' ? ' selected="selected"' : '' ) . '>No</option></select>
		</td>
	</tr>
	<tr>
		<td>Content is test data, related to the old site or inappropriate<br><select name="reviewresource"><option value="T"' . ( $resourceUser['reviewresource'] == 'T' ? ' selected="selected"' : '' ) . '>Yes</option><option value="F"' . ( $resourceUser['reviewresource'] == 'F' ? ' selected="selected"' : '' ) . '>No</option></select>
		</td>
	</tr>
	<tr>
		<td>Type<br><i>' . ( (isset($resourceUser['type']) && (strlen($resourceUser['type']) > 0)) ? ucwords($resourceUser['type']) : " --- " ) . '</i></td>
	</tr>
	<tr>
		<td>mediatype<br><select name="mediatype">' . $mediatype_Options . '</select></td>
	</tr>
	<tr>
		<td>oldurl<br><input type="text" size="100" value="' . $resourceUser['oldurl'] . '" name="oldurl"></td>
	</tr>
	<tr>
		<td align="center"><br><input type="submit" value="Update"><br></td>
	</tr>
    </tbody></table>
</form>
  ';

  /*
    Resource Review page - http://cg.curriki.org/curriki/resource-review/?rid=11416

    Please also display the oldurl - resources.oldurl
    Please also display type - resources.type
    For the mediatype, please use a dropdown populated by select *
    from mediatypes
    where active = 'T';
   */
  // r.partner, r.resourceid, r.title, r.description, r.content, r.type, r.reviewrating, r.memberrating, r.pageurl, r.contributiondate, r.reviewstatus,
  //  r.studentfacing,  r.contentdisplayok, r.reviewresource, r.oldurl, r.mediatype 

  $resource_header .= '</div>';
  $resource_header .= '</div>';

  echo $resource_header;
}

function curriki_resource_file_check_body() {

  $resourCecheckTypes = array(
      'R' => 'Rejected',
      'T' => 'Approved',
      'Q' => 'Requested',
      'I' => 'Improvement',
      'F' => 'Not Checked'
  );

  if (isset($_GET['rid']) || isset($_GET['pageurl'])) {
    $res = new CurrikiResources();
    $resource = $res->getResourceUserById((int) $_GET['rid'], rtrim($_GET['pageurl'], '/'));
  }

  $resource_header = '<div class="resource-header page-header">';
  $resource_header .= '<div class="wrap container_12">';

  $resource_header .= '
    <style>
      .fc_left_col{width:250px;text-align:right;font-weight:bold;padding-right:10px;vertical-align:top}
      .fc_right_col{text-align:left;}
    </style>
    <form method="post" action="' . site_url() . '/wp-admin/admin.php?page=curriki_file_check&time=' . time() . '">
      <input type="hidden" name="action" value="file_checked" >
      <input type="hidden" value="' . $resource['resourceid'] . '" name="resourceid">  
      <table width="80%"><tbody>
	
	<tr>
          <td class="fc_left_col">Resource Title : </td>
          <td class="fc_right_col">' . $resource['title'] . '</td>
	</tr>
	
	<tr>
          <td class="fc_left_col">Description : </td>
          <td class="fc_right_col">' . $resource['description'] . '</td>
	</tr>
	
	<tr>
          <td class="fc_left_col">Contributer : </td>
          <td class="fc_right_col">' . $resource['display_name'] . '</td>
	</tr>
	
	<tr>
          <td class="fc_left_col">Status : </td>
          <td class="fc_right_col">' . $resourCecheckTypes[$resource['resourcechecked']] . '</td>
	</tr>
	
	<tr>
          <td class="fc_left_col">Request Notes : </td>
          <td class="fc_right_col">' . $resource['resourcecheckrequestnote'] . '</td>
	</tr>
	
	<tr>
          <td class="fc_left_col">Check : </td>
          <td class="fc_right_col">
            <input type="hidden" name="status" value="F" />
            <label><input type="radio" name="status" value="T" ' . ($resource['resourcechecked'] == 'T' ? 'checked="checked"' : '') . ' /> Approved</label>
            <label><input type="radio" name="status" value="I" ' . ($resource['resourcechecked'] == 'I' ? 'checked="checked"' : '') . ' /> Improvement Requested</label>
            <label><input type="radio" name="status" value="R" ' . ($resource['resourcechecked'] == 'R' ? 'checked="checked"' : '') . ' /> Disapproved</label>
          </td>
	</tr>
	
	<tr>
          <td class="fc_left_col">Check Notes : </td>
          <td class="fc_right_col"><textarea name="notes" style="width:70%" cols="60" rows="3">' . $resource['resourcechecknote'] . '</textarea></td>
	</tr>
        
	<tr>
        <td class="fc_left_col"></td>
          <td class="fc_right_col"><input type="submit" value="Update"><br/></td>
	</tr>
      </tbody></table>
    </form>';

  $resource_header .= '</div>';
  $resource_header .= '</div>';

//  echo '<pre>';
//  print_r($resource);
//  echo '</pre>';

  echo $resource_header;
}

function curriki_resource_page_body() {

  if (isset($_GET['rid']) || isset($_GET['pageurl'])) {
    $res = new CurrikiResources();
    $resource = $res->getResourceById((int) $_GET['rid'], rtrim((isset($_GET['pageurl'])?$_GET['pageurl']:''), '/'), true);
    if ($resource['access'] == 'public')
      $resource['access'] = 'Public - Available to anyone';
    elseif ($resource['access'] == 'private')
      $resource['access'] = 'Private';
    elseif ($resource['access'] == 'members')
      $resource['access'] = 'Members';

    $componentRatings = $reviewerComments = '';
    $newRatings = false;

    if ((int) $resource['standardsalignment'] && (int) $resource['standardsalignment'] > 0) {
      //$componentRatings .= '<br />' . $resource['standardsalignmentcomment'] . ': ' . $resource['standardsalignment'];
      $componentRatings .= '<br />Standards Alignment: ' . $resource['standardsalignment'];
      $reviewerComments .= '<br />Standards Alignment: ' . $resource['standardsalignmentcomment'];
      $newRatings = true;
    }
    if ((int) $resource['subjectmatter'] && (int) $resource['subjectmatter'] > 0) {
      //$componentRatings .= '<br />' . $resource['subjectmattercomment'] . ': ' . $resource['subjectmatter'];
      $componentRatings .= '<br />Subject Matter: ' . $resource['subjectmatter'];
      $reviewerComments .= '<br />Subject Matter: ' . $resource['subjectmattercomment'];
      $newRatings = true;
    }
    if ((int) $resource['supportsteaching'] && (int) $resource['supportsteaching'] > 0) {
      //$componentRatings .= '<br />' . $resource['supportsteachingcomment'] . ': ' . $resource['supportsteaching'];
      $componentRatings .= '<br />Support Steaching: ' . $resource['supportsteaching'];
      $reviewerComments .= '<br />Support Steaching: ' . $resource['supportsteachingcomment'];
      $newRatings = true;
    }
    if ((int) $resource['assessmentsquality'] && (int) $resource['assessmentsquality'] > 0) {
      //$componentRatings .= '<br />' . $resource['assessmentsqualitycomment'] . ': ' . $resource['assessmentsquality'];
      $componentRatings .= '<br />Assessments Quality: ' . $resource['assessmentsquality'];
      $reviewerComments .= '<br />Assessments Quality: ' . $resource['assessmentsqualitycomment'];
      $newRatings = true;
    }
    if ((int) $resource['interactivityquality'] && (int) $resource['interactivityquality'] > 0) {
      //$componentRatings .= '<br />' . $resource['interactivityqualitycomment'] . ': ' . $resource['interactivityquality'];
      $componentRatings .= '<br />Interactivity Quality: ' . $resource['interactivityquality'];
      $reviewerComments .= '<br />Interactivity Quality: ' . $resource['interactivityqualitycomment'];
      $newRatings = true;
    }
    if ((int) $resource['instructionalquality'] && (int) $resource['instructionalquality'] > 0) {
      //$componentRatings .= '<br />' . $resource['instructionalqualitycomment'] . ': ' . $resource['instructionalquality'];
      $componentRatings .= '<br />Instructional Quality: ' . $resource['instructionalquality'];
      $reviewerComments .= '<br />Instructional Quality: ' . $resource['instructionalqualitycomment'];
      $newRatings = true;
    }
    if ((int) $resource['deeperlearning'] && (int) $resource['deeperlearning'] > 0) {
      //$componentRatings .= '<br />' . $resource['deeperlearningcomment'] . ': ' . $resource['deeperlearning'];
      $componentRatings .= '<br />Deeper Learning: ' . $resource['deeperlearning'];
      $reviewerComments .= '<br />Deeper Learning: ' . $resource['deeperlearningcomment'];
      $newRatings = true;
    }

    if (!$newRatings && ((int) $resource['technicalcompleteness'] || (int) $resource['contentaccuracy'] || (int) $resource['pedagogy'])) {
      $componentRatings = 'Technical Completeness: ' . $resource['technicalcompleteness'] . '<br />Content Accuracy: ' . $resource['contentaccuracy'] . '<br />Appropriate Pedagogy: ' . $resource['pedagogy'] . '';
      $reviewerComments = trim(str_replace('<strong>Reviewer Comments: </strong>', '', $resource['ratingcomment']));
    }
  }

  echo '<div id="resource-tabs">';

  $resource_tabs = '';
  $resource_tabs .= '<div class="resource-tabs page-tabs"><div class="wrap container_12">';
  $resource_tabs .= '<ul>';
  $resource_tabs .= '<li><a href="#content"><span class="tab-icon fa fa-file-text-o"></span> <span class="tab-text">Content</span></a></li>';
  $resource_tabs .= '<li><a href="#information"><span class="tab-icon fa fa-info-circle"></span> <span class="tab-text">Information</span></a></li>';
  $resource_tabs .= '<li><a href="#standards"><span class="tab-icon fa fa-graduation-cap"></span> <span class="tab-text">Standards</span></a></li>';
  $resource_tabs .= '<li><a href="#reviews"><span class="tab-icon fa fa-star"></span> <span class="tab-text">Reviews</span></a></li>';
  $resource_tabs .= '</ul>';
  $resource_tabs .= '</div></div>';

  echo $resource_tabs;


  echo '<div class="resource-content dashboard-tabs-content"><div class="wrap container_12">';

  // Content
  $content_tab = '';
  $content_tab .= '<div id="content" class="tab-contents">';
  $content_tab .= '</div>';

  echo $content_tab;


  // Information
  $information_tab = '';
  $information_tab .= '<div id="information" class="tab-contents">';
  $information_tab .= '<div class="grid_9">';
  $information_tab .= '<div class="information-section">';
  $information_tab .= '<h4 class="resource-subheadline">Type:</h4>';
  $typeName = '';
  if (isset($resource['typeName']))
    foreach ($resource['typeName'] as $type)
      $typeName .= $type['typeName'] . ', ';
  $information_tab .= substr($typeName, 0, -2);
  $information_tab .= '</div>';
  $information_tab .= '<div class="information-section">';
  $information_tab .= '<h4 class="resource-subheadline">Description:</h4>';
  $information_tab .= '<p>' . $resource['description'] . '</p>';
  $information_tab .= '</div>';
  $information_tab .= '<div class="information-section">';
  $information_tab .= '<div class="grid_6">';
  $information_tab .= '<h4 class="resource-subheadline">Subjects:</h4>';
  $information_tab .= '<ul>';
  if (isset($resource['subjects']))
    foreach ($resource['subjects'] as $subject)
      $information_tab .= '<li>' . $subject . '</li>';
  $information_tab .= '</ul>';
  $information_tab .= '</div>';
  $information_tab .= '<div class="grid_6">';
  $information_tab .= '<h4 class="resource-subheadline">Education Levels:</h4>';
  $information_tab .= '<ul>';
  if (isset($resource['educationlevels']))
    foreach ($resource['educationlevels'] as $educationlevel)
      $information_tab .= '<li>' . $educationlevel . '</li>';
  $information_tab .= '</ul>';
  $information_tab .= '</div>';
  $information_tab .= '</div>';
  $information_tab .= '<div class="information-section">';
  $information_tab .= '<div class="grid_6">';
  $information_tab .= '<h4 class="resource-subheadline">Keywords:</h4>';
  $information_tab .= $resource['keywords'];
  $information_tab .= '</div>';
  $information_tab .= '<div class="grid_6">';
  $information_tab .= '<h4 class="resource-subheadline">Language:</h4>';
  $information_tab .= $resource['languageName'];
  $information_tab .= '</div>';
  $information_tab .= '</div>';
  $information_tab .= '</div>';
  $information_tab .= '<div class="grid_2 push_1">';
  $information_tab .= '<div class="information-section">';
  $information_tab .= '<h4 class="resource-subheadline">Access Privileges:</h4>';
  $information_tab .= $resource['access'];
  $information_tab .= '</div>';
  //$information_tab .= '<div class="information-section">';
  //$information_tab .= '<h4 class="resource-subheadline">Hidden From Search:</h4>';
  //$information_tab .= 'No';
  //$information_tab .= '</div>';
  //$information_tab .= '<div class="information-section">';
  //$information_tab .= '<h4 class="resource-subheadline">Rights Holder:</h4>';
  //$information_tab .= 'Curriculum: Lesson Plan';
  //$information_tab .= '</div>';
  $information_tab .= '<div class="information-section">';
  $information_tab .= '<h4 class="resource-subheadline">License Deed:</h4>';
  $information_tab .= $resource['licenseName'];
  $information_tab .= '</div>';
  $information_tab .= '</div>';
  $information_tab .= '</div>';

  echo $information_tab;


  // Standards
  $standards_tab = '';
  $standards_tab .= '<div id="standards" class="tab-contents">';
  $standards_tab .= '<div class="grid_2">';
  $standards_tab .= '</div>';
  $standards_tab .= '<div class="grid_10">';
  //$standards_tab .= '<div class="alignment-standard-breadcrumbs">';
  //$standards_tab .= '<a href="#">Aligned Standards</a> > <a href="#">Core Standards</a> > <a href="#">English</a> > <a href="#">Grade 11</a>';
  //$standards_tab .= '</div>';

  if (isset($resource['standards'])) {
    $standards_tab .= '<div class="alignment-standard-section information-section">';
    $standards_tab .= 'Update Standards? <button class="modal-button green-button">Align Now</button>';
    $standards_tab .= '</div>';
  } else {
    $standards_tab .= '<div class="alignment-standard-section information-section">';
    $standards_tab .= 'This resource has not yet been aligned. <a href="' . get_bloginfo('url') . '/alignment/?rid=' . $resource['resourceid'] . '" class="fancybox fancybox.iframe"><button class="modal-button white-button">Align Now</button></a>';
    $standards_tab .= '</div>';
  }

  if (isset($resource['standards']))
    foreach ($resource['standards'] AS $standard) {
      $standards_tab .= '<div class="alignment-standard-section information-section">';
      $standards_tab .= '<h4 class="resource-subheadline">' . $standard['notation'] . ': ' . $standard['title'] . '</h4>';
      $standards_tab .= '<div class="alignment-standard-section">';
      //$standards_tab .= '<h4 class="resource-subheadline">Core Standard:</h4>';
      $standards_tab .= $standard['description'];
      $standards_tab .= '</div>';
      $standards_tab .= '</div>';
    }

  /*
    $standards_tab .= '<div class="alignment-standard-section information-section">';
    $standards_tab .= '<h4 class="resource-subheadline">LA.10.2 Informational Text: Structure, Comprehension and Analysis</h4>';
    $standards_tab .= '<div class="alignment-standard-section">';
    $standards_tab .= '<h4 class="resource-subheadline">Core Standard:</h4>';
    $standards_tab .= '<span class="standard">LA.10.2.1</span> Analyze arguments or defenses of claims, judge tone, features and arguments of each. Summarize and synthesize content from reliable sources for writing and speaking.';
    $standards_tab .= '</div>';
    $standards_tab .= '</div>';
   */

  $standards_tab .= '</div>';
  $standards_tab .= '</div>';

  echo $standards_tab;

  if ((int) $resource['memberrating'] == 0)
    $stars = '<span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span>';
  elseif ((int) $resource['memberrating'] == 1)
    $stars = '<span class="fa fa-star"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span>';
  elseif ((int) $resource['memberrating'] == 2)
    $stars = '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span>';
  elseif ((int) $resource['memberrating'] == 3)
    $stars = '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span>';
  elseif ((int) $resource['memberrating'] == 4)
    $stars = '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>';
  elseif ((int) $resource['memberrating'] == 5)
    $stars = '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span>';

  // Reviews
  $reviews_tab = '';
  $reviews_tab .= '<div id="reviews" class="tab-contents reviews-tab">';
  $reviews_tab .= '<div class="grid_6 review-curriki">';
  if ((int) $resource['reviewrating'])
    $reviews_tab .= '<div class="review-aggregate curriki-rating">Curriki Review <span class="rating-badge">' . (int) $resource['reviewrating'] . '</span></div>';
  else
    $reviews_tab .= '<div class="review-aggregate curriki-rating">Curriki Review <span class="rating-badge">NR</span></div>';
  $reviews_tab .= '<div class="review-content-box scrollbar rounded-borders-full border-grey">';
  if ((int) $resource['reviewrating'])
    $reviews_tab .= '<p>This resource was reviewed using the Curriki Review rubric and received an overall Curriki Review System rating of ' . (int) $resource['reviewrating'] . ', as of ' . date("Y-m-d", strtotime($resource['lastreviewdate'])) . '.</p>';
  else
    $reviews_tab .= '<p>This resource has not yet been reviewed.</p>';
  $reviews_tab .= '<h4 class="resource-subheadline">Component Ratings:</h4>';
  //$reviews_tab .= 'Technical Completeness:'.$resource['technicalcompleteness'].' Content Accuracy:'.$resource['contentaccuracy'].' Appropriate Pedagogy:'.$resource['pedagogy'].'';
  $reviews_tab .= $componentRatings;
  $reviews_tab .= '<h4 class="resource-subheadline">Reviewer Comments:</h4>';
  $reviews_tab .= $reviewerComments;
  $reviews_tab .= '</div>';
  $reviews_tab .= '</div>';
  $reviews_tab .= '<div class="grid_6 review-members">';
  $reviews_tab .= '<div class="review-aggregate member-rating rating">Member Rating ' . $stars . '</div>';
  $reviews_tab .= '<div class="review-content-box scrollbar rounded-borders-full border-grey">';
  $reviews_tab .= '<div class="review review-form">';
  $reviews_tab .= '<img class="border-grey circle" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
  $reviews_tab .= '<div class="review-content">';




  $reviews_tab .= '
									<div class="review-rating rating">
										<span class="member-name name">Member Name</span> <span>
										<span class="fa fa-star-o" id="resource-rating-1" onclick="resourceRating(1);"></span>
										<span class="fa fa-star-o" id="resource-rating-2" onclick="resourceRating(2);"></span>
										<span class="fa fa-star-o" id="resource-rating-3" onclick="resourceRating(3);"></span>
										<span class="fa fa-star-o" id="resource-rating-4" onclick="resourceRating(4);"></span>
										<span class="fa fa-star-o" id="resource-rating-5" onclick="resourceRating(5);"></span></span>
									</div>';



  //$reviews_tab .= '<div class="review-rating rating"><span class="member-name name">Member Name</span> <span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span></div>';
  $reviews_tab .= '<form action="" method="post"><input type="hidden" name="resourceid" value="' . $resource['resourceid'] . '" /><input type="hidden" id="resource-rating" name="resource-rating" /><textarea name="resource-comments"></textarea>';
  $reviews_tab .= '<button class="green-button">Submit Review</button></form>';
  $reviews_tab .= '</div>';
  $reviews_tab .= '</div>';

  if (isset($resource['comments']))
    foreach ($resource['comments'] AS $comment) {
      if ($comment['rating'] == 0)
        $stars = '<span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span>';
      elseif ($comment['rating'] == 1)
        $stars = '<span class="fa fa-star"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span>';
      elseif ($comment['rating'] == 2)
        $stars = '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span>';
      elseif ($comment['rating'] == 3)
        $stars = '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span>';
      elseif ($comment['rating'] == 4)
        $stars = '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>';
      elseif ($comment['rating'] == 5)
        $stars = '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span>';

      $reviews_tab .= '<div class="review">';
      if (trim($comment['uniqueavatarfile']) != '')
        $reviews_tab .= '<img class="border-grey circle" src="https://' . DBI_AWS_S3_CDN_URL . '//avatars/' . $comment['uniqueavatarfile'] . '" alt="member-name" />';
      else
        $reviews_tab .= '<img class="border-grey circle" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
      $reviews_tab .= '<div class="review-content">';
      $reviews_tab .= '<div class="review-rating rating"><span class="member-name name">' . $comment['display_name'] . '</span> ' . $stars . '</div>';
      if ($comment['date'] != '0000-00-00 00:00:00')
        $reviews_tab .= '<div class="review-date">' . date("F j, Y", strtotime($comment['date'])) . '</div>';
      $reviews_tab .= $comment['comment'];
      $reviews_tab .= '</div>';
      $reviews_tab .= '</div>';
    }

  $reviews_tab .= '</div>';
  $reviews_tab .= '</div>';
  $reviews_tab .= '</div>';

  echo $reviews_tab;

//print_r($resource);
  $video = '';
  /* if(trim($resource['uniquename']) != '' AND $resource['folder'] == 'sourcevideos/') {
    $video = '
    <!--<h5>https://archivecurrikicdn.s3-us-west-2.amazonaws.com/sourcevideos/'. $resource['uniquename'] .'</h5>-->
    <script type="text/javascript" src="'. get_bloginfo('url') .'/wp-content/libs/jwplayer/jwplayer.js"></script>
    <script>jwplayer.key="XlnEnswS1k0cpvBFXqJYwsnzSWECBaplnchsHRncTA4=";</script>
    <div id="myElement">Loading the player...</div>
    <script type="text/javascript">
    jwplayer("myElement").setup({
    file: "https://archivecurrikicdn.s3-us-west-2.amazonaws.com/'. $resource['folder'] . $resource['uniquename'] .'",
    width: 640,
    height: 360
    });
    </script>
    ';
    } */

  // Resource Content (always visible below tabs)
  $resource_content = '';
  $resource_content .= '<div class="resource-content-sidebar">';
  $resource_content .= '<div class="resource-sidebar page-sidebar grid_2">';
  if ($resource['type'] == 'collection' && isset($resource['collection'])) {
    $resource_content .= '<div class="toc toc-card card rounded-borders-full border-grey no-min-width">';
    $resource_content .= '<div class="toc-header">Table of Contents</div>';
    $resource_content .= '<div class="toc-body">';
    $resource_content .= '<h4 class="toc-collection-folder"><span class="fa fa-folder-open"></span> ' . $resource['title'] . '</h4>';
    $resource_content .= '<ul class="fa fa-ul toc-collection toc-folder">';

    foreach ($resource['collection'] AS $collection)
      $resource_content .= '<li class="toc-file toc-image"><span class="fa fa-li fa-file-image-o"></span> <a href="' . get_bloginfo('url') . '/oer/?rid=' . $collection['resourceid'] . '">' . $collection['title'] . '</a></li>';

    $resource_content .= '</ul>';
    $resource_content .= '</div>';
    $resource_content .= '</div>';
  }
  //$resource_content .= '<div class="curriki-ad-sidebar">';
  //$resource_content .= '<img src="http://placehold.it/160x600" />';
  //$resource_content .= '</div>';
  $resource_content .= '</div>';
  $resource_content .= '<div class="resource-content grid_10">';
  $resource_content .= '<div class="resource-content-content rounded-borders-full border-grey">';

  //$resource_content .= '<h3>'.$resource['content']. $video .'</h3>';
  //$resource_content .= $resource['content']. $video;
  //$resource_content .= $resource['originalcontent']. $video;

  /* $resource_content .= '$_GET["pageurl"] = '. $_GET['pageurl'] .'<br />';
    $resource_content .= '$_REQUEST["pageurl"] = '. $_REQUEST['pageurl'] .'<br />';
    $resource_content .= $resource['newcontent']; */

  if ($resource['type'] == 'collection' && isset($resource['collection']))
    $resource_content .= $resource['description'];
  else
    $resource_content .= $resource['content'] . $video;

  $resource_content .= '</div>';

  /* if($resource['type'] == 'collection' && isset($resource['collection'])) {
    $resource_content .= '<br /><div class="resource-content-content rounded-borders-full border-grey">';
    foreach($resource['collection'] AS $collection)
    $resource_content .= '<div class="resource-content-content rounded-borders-full border-grey"><h3>' . $collection['title'] . '</h3><p>' . $collection['description'] . '</p></div><br />';
    $resource_content .= '</div>';
    } */



  if ($resource['type'] == 'collection' && isset($resource['collection'])) {
    $resource_content .= '<br /><div class="resource-content-content rounded-borders-full border-grey">';

    foreach ($resource['collection'] AS $collection) {
      $url = get_bloginfo('url') . '/oer/?rid=' . $collection['resourceid'];
      if (trim($collection['description']) != '')
        $content = $collection['description'];
      else
        $content = $collection['content'];
      if ((int) $collection['reviewrating'])
        $reviewrating = (int) $collection['reviewrating'];
      else
        $reviewrating = 'NR';

      if ((int) $collection['memberrating'] == 0)
        $m_stars = '<span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span>';
      elseif ((int) $collection['memberrating'] == 1)
        $m_stars = '<span class="fa fa-star"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span>';
      elseif ((int) $collection['memberrating'] == 2)
        $m_stars = '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span>';
      elseif ((int) $collection['memberrating'] == 3)
        $m_stars = '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span>';
      elseif ((int) $collection['memberrating'] == 4)
        $m_stars = '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>';
      elseif ((int) $collection['memberrating'] == 5)
        $m_stars = '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span>';

      $resource_content .= '<div class="resource-content-content rounded-borders-full border-grey"><div class="collection-body-title"><div class="collection-title"><h3><a href="' . $url . '">' . $collection['title'] . '</a></h3> by <span class="member-name name">' . $collection['contributorid_Name'] . '</span></div></div><div class="collection-body-content"><div class="collection-description">' . $content . '</div><div class="collection-rating rating"><span class="member-rating-title">Member Rating</span>' . $m_stars . '<a href="#">Rate this collection</a></div><div class="collection-curriki-rating curriki-rating"><span class="curriki-rating-title">Curriki Rating</span><span class="rating-badge">' . $reviewrating . '</span></div></div></div><br />';
    }

    $resource_content .= '</div>';
  }






  $resource_content .= '</div>';
  $resource_content .= '</div>';

  echo $resource_content;


  echo '</div></div>';

  echo '</div>';



  $reviews_popup = '
									<div class="review-content-box scrollbar rounded-borders-full border-grey">
									<div class="review review-form">
									<img class="border-grey circle" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />
									<div class="review-content">
										<div class="review-rating rating">
											<span class="member-name name">Member Name</span> <span>
											<span class="fa fa-star-o" id="resource-rating2-1" onclick="resourceRating2(1);"></span>
											<span class="fa fa-star-o" id="resource-rating2-2" onclick="resourceRating2(2);"></span>
											<span class="fa fa-star-o" id="resource-rating2-3" onclick="resourceRating2(3);"></span>
											<span class="fa fa-star-o" id="resource-rating2-4" onclick="resourceRating2(4);"></span>
											<span class="fa fa-star-o" id="resource-rating2-5" onclick="resourceRating2(5);"></span></span>
										</div>
										<form action="" method="post"><input type="hidden" name="resourceid" value="' . $resource['resourceid'] . '" /><input type="hidden" id="resource-rating2" name="resource-rating" /><textarea name="resource-comments"></textarea>
										<button class="green-button">Submit Review</button></form>
									</div></div></div>
  ';



  echo '
	<div id="resource-member-review" class="join-oauth-modal modal border-grey rounded-borders-full grid_8" style="display: none; width: 50%;">
		<h3 class="modal-title">Member Review</h3>
		<div><span id="login_result" class="dialog_result"></span></div>
		' . $reviews_popup . '
		
		<div class="close"><span class="fa fa-close" onclick="jQuery(\'#resource-member-review\').hide();"></span></div>
	</div>';

  $asdf = '<!--<div class="join-login-section grid_5 resource-member-review" style="width: 100%; max-height: 50%; overflow: scroll;">
			contents goes here.
		</div>-->';
}

add_action('genesis_after', 'curriki_addthis_scripts');

genesis();
