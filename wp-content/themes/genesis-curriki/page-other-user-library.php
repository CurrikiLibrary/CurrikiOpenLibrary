<?php
/*
 * Template Name: User Library Template
 *
 * Child Theme Name: Curriki Child Theme for Genesis 2.1
 * Author: Orange Blossom Media
 * Url: http://orangeblossommedia.com/
 */

// Add custom body class to the head
add_filter('body_class', 'curriki_user_my_library_add_body_class');

function curriki_user_my_library_add_body_class($classes) {
  $classes[] = 'backend user-dashboard';
  return $classes;
}

// Execute custom style guide page
add_action('genesis_meta', 'curriki_custom_user_my_library_loop');

function curriki_custom_user_my_library_loop() {
  //* Force full-width-content layout setting
  add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');

  remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');
  remove_action('genesis_loop', 'genesis_do_loop');

  add_action('genesis_loop', 'curriki_user_my_library_body', 15);
}

/* function curriki_sharethis($url, $title = ''){
  return '<a href="https://www.addthis.com/bookmark.php?source=tbx32nj-1.0&v=300&url='.$url.'" target="_blank"><span class="fa fa-share-alt-square"></span> <span>Share</span></a>';
  } */

function curriki_user_my_library_body() {
  global $wpdb;
  $userid = addslashes($_GET['userid']);
  if (empty($userid) and ! empty($_GET['user'])) {
    $userid = $wpdb->get_var("select ID from cur_users where user_nicename = '" . addslashes($_GET['user']) . "'");
  }
  $q_user = "SELECT * FROM users WHERE userid = '" . $userid . "'";
  $user = $wpdb->get_row($q_user);

  $order_by = "";
  if (empty($_GET['library_sorting']))
    $order_by = "order by title ASC";
  //elseif($_GET['library_sorting'] == 'displayseqno')  $order_by = "";
  elseif ($_GET['library_sorting'] == 'oldest')
    $order_by = "order by contributiondate ASC";
  elseif ($_GET['library_sorting'] == 'newest')
    $order_by = "order by contributiondate DESC";
  elseif ($_GET['library_sorting'] == 'rtc')
    $order_by = "order by type DESC";
  elseif ($_GET['library_sorting'] == 'ctr')
    $order_by = "order by type ASC";
  elseif ($_GET['library_sorting'] == 'aza')
    $order_by = "order by title ASC";
  elseif ($_GET['library_sorting'] == 'azd')
    $order_by = "order by title DESC";
  elseif ($_GET['library_sorting'] == 'ru')
    $order_by = "order by lasteditdate DESC";

  
  $total_resources_q = "select count(*) from resources where contributorid = '" . $userid . "' and not (type = 'collection' and title = 'Favorites')";
  $total_resources = $wpdb->get_var($total_resources_q);

  $q_resources = "select * from resources where contributorid = '" . $userid . "' and not (type = 'collection' and title = 'Favorites') $order_by";
  
  if(empty($_GET['page_no']) or $_GET['page_no'] < 1) $_GET['page_no'] = 1;
    $start = (10 * $_GET['page_no']) - 10;
    
    $q_resources .= " limit $start, 10";
    $resources = $wpdb->get_results($q_resources);
  
  echo '<div class="user-library-content clearfix"><div class="wrap container_12">';

  // Access
  $user_library = '';

  $user_library .= '<div class="user-library-breadcrumbs breadcrumbs grid_12">'.__('Resource Library','curriki').' > ' . $user->firstname . ' ' . $user->lastname . '</div>';

  $user_library .= '<div class="actions-row grid_12 clearfix">';
  $user_library .= '<div class="grid_8 alpha">';
  // $user_library .= '<button class="small-button green-button"><span class="fa fa-search"></span> '.__('New Collection','curriki').'</button>';
  // $user_library .= '<button class="small-button green-button" onclick="window.location=\'' . get_bloginfo('url') . '/create-resource\';"><span class="fa fa-plus-circle"></span> '.__('Upload Resource','curriki').'</button>';
  $user_library .= '<button class="small-button green-button" onclick="window.location=\'' . get_bloginfo('url') . '/organize-collections\'"><span class="fa fa-list"></span> '.__('Organize Collections','curriki').'</button>';
  $user_library .= '</div>';
  $user_library .= '<div class="search-dropdown grid_4 omega">' . curriki_library_sorting('user', 'top', $_GET['library_sorting'], $userid) . '</div>';
  $user_library .= '</div>';

  $user_library .= '<div class="clearfix grid_12">';

  $library = '';
  foreach ($resources as $resource) {
    // Collection - First Level
    $library .= '<div class="library-asset rounded-borders-full border-grey library-collection">';
    if ($resource->type == 'collection')
      $type_class = "fa-folder";
    elseif ($resource->type == 'resource')
      $type_class = "fa-image";
    else
      $type_class = "fa-folder-open";
    $library .= '<div class="library-icon"><span class="fa ' . $type_class . '"></span></div>';
    $library .= '<div class="library-title vertical-align"><a href="' . get_bloginfo('url') . '/oer/?rid=' . $resource->resourceid . '">' . ($resource->title ? stripslashes($resource->title) : 'Go to Resource') . '</a></div>';
    $library .= '<div class="library-author vertical-align">';
    //$library .= '<img src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
    //$library .= '<div class="library-author-info">';
    //	$library .= '<span class="member-name name">Member Name</span><span class="location">Toronto, Ontario, Canada</span>';
    //$library .= '</div>';
    //$library .= '<div class="member-more"><a href="'.get_bloginfo('url').'/user-library">More from this member</a></div>';
    $library .= '</div>';
    $library .= '<div class="library-rating rating vertical-align"><span class="member-rating-title">'.__('Member Rating','curriki').'</span>';

    $library .= curriki_member_rating($resource->memberrating);
    //$library .= '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>';
    
    if(get_current_user_id() > 0)
    {
        $library .= '<a href="javascript:;" onclick="jQuery(\'#rate_resource-dialog\').show(); jQuery(\'#review-resource-id\').val(' . $resource->resourceid . '); jQuery(\'.curriki-review-title\').html(\'' . $collection->title . '\'); setInterval(function () {jQuery( \'#rate_resource-dialog\' ).center()}, 1);">Rate this resource</a>';
    }
    $library .= '</div>';
    $reviewrating = round($resource->reviewrating);
    if ($reviewrating == 0)
      $library .= '<div class="library-curriki-rating curriki-rating vertical-align"><span class="curriki-rating-title">'.__('Curriki Rating','curriki').'</span><span class="rating-badge-nr">NR</span></div>';
    else
      $library .= '<div class="library-curriki-rating curriki-rating vertical-align"><span class="curriki-rating-title">'.__('Curriki Rating','curriki').'</span><span class="rating-badge">' . $reviewrating . '</span></div>';
    $library .= '<div class="library-date vertical-align">' . date('M d, Y', strtotime($resource->contributiondate)) . '</div>';
    $library .= '<div class="library-actions vertical-align">';
    //$library .= '<a href="#"><span class="fa fa-edit"></span> <span>Edit</span></a>';
    //$library .= '<a href="#"><span class="fa fa-trash"></span> <span>Delete</span></a>';
    // $library .= '<a href="' . get_bloginfo('url') . '/create-resource/?resourceid=' . $resource->resourceid . '&copy=1"><span class="fa fa-copy"></span> <span>'.__('Duplicate','curriki').'</span></a>';
    $library .= curriki_sharethis($resource->resourceid, ($resource->title?$resource->title:'Go to Resource'));
    $library .= '</div>';
    $library .= '</div>';
  }
  $user_library .= $library;

  $user_library .= '</div>';



  $user_library .= library_pagination(get_bloginfo('url') . '/user-library/?userid=' . $userid . '&library_sorting=' . $_GET['library_sorting'], $_GET['page_no'], ceil($total_resources / 10));

  $user_library .= '<div class="actions-row grid_12 clearfix">';
  $user_library .= '<div class="grid_8 alpha">';
  // $user_library .= '<button class="small-button green-button"><span class="fa fa-search"></span> '.__('New Collection','curriki').'</button>';
  // $user_library .= '<button class="small-button green-button" onclick="window.location=\'' . get_bloginfo('url') . '/create-resource\';"><span class="fa fa-plus-circle"></span> '.__('Upload Resource','curriki').'</button>';
  $user_library .= '<button class="small-button green-button" onclick="window.location=\'' . get_bloginfo('url') . '/organize-collections\'"><span class="fa fa-list"></span> '.__('Organize Collections','curriki').'</button>';
  $user_library .= '</div>';
  $user_library .= '<div class="search-dropdown grid_4 omega">' . curriki_library_sorting('user', 'bottom', $_GET['library_sorting'], $userid) . '</div>';
  $user_library .= '</div>';

  echo $user_library;

  echo '</div></div>';
}

add_action('genesis_after', 'curriki_library_scripts');
add_action('genesis_after', 'curriki_addthis_scripts');

genesis();
