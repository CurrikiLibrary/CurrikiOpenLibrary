<?php
 add_filter('body_class', 'curriki_print_resource_page_print_add_body_class');

function curriki_print_resource_page_print_add_body_class($classes) {
  $classes[] = 'backend resource-page';
  return $classes;
}

 
// Execute custom style guide page
add_action('genesis_meta', 'curriki_custom_print_resource_page_loop');
 
function curriki_custom_print_resource_page_loop() {
  //* Force full-width-content layout setting
  add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');
  remove_action( 'genesis_after_header', 'genesis_do_nav' );

  remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');
  remove_action('genesis_loop', 'genesis_do_loop');
remove_action( 'genesis_header', 'genesis_do_header' );
  add_action('genesis_before', 'curriki_print_resource_page_print_scripts');
  add_action('genesis_header', 'custom_header_pr_page', 10);
  add_action('genesis_after_header', 'curriki_print_resource_header', 10);
  add_action('genesis_after_header', 'curriki_print_resource_page_print_body', 15);
  
  remove_theme_support( 'genesis-footer-widgets', 3 );
 
remove_action( 'genesis_footer', 'genesis_do_footer' );
}

function curriki_print_resource_page_print_scripts() {

  // Enqueue JQuery Tab and Accordion scripts
  wp_enqueue_script('jquery-ui-tabs');
  wp_enqueue_style('resource-font-awesome-css', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
  wp_enqueue_style('resource-legacy-css', get_stylesheet_directory_uri() . '/css/legacy.css');
  wp_enqueue_style('resource-print-css', get_stylesheet_directory_uri() . '/css/print-resource.css');
  
  wp_enqueue_style('jquery-fancybox-css', get_stylesheet_directory_uri() . '/js/fancybox_v2.1.5/jquery.fancybox.css?v=2.1.5');
  wp_enqueue_script('jquery-fancybox-js', get_stylesheet_directory_uri() . '/js/fancybox_v2.1.5/jquery.fancybox.pack.js?v=2.1.5', 'jquery.fancybox', '2.1.5' );
  wp_enqueue_script('jquery-mousewheel-js', get_stylesheet_directory_uri() . '/js/fancybox_v2.1.5/jquery.mousewheel-3.0.6.pack.js', 'jquery.mousewheel', '3.0.6' );

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
		.done(function (msg) { jQuery("#resourceInappropriate").hide(); });
      });
    }
	
	function addToMyLibrary(id) {
      jQuery(document).ready(function () {
        jQuery.ajax({
          method: "POST",
          url: "<?php echo get_bloginfo('url'); ?>/oer/?addtolibrary=true",
          data: {id: id}
        })
		.done(function (msg) { jQuery("#addtolibrary").hide(); });
      });
    }
	
	jQuery(document).ready(function() {
		jQuery(".fancybox").fancybox();
	});
  </script>
  <?php
}
function custom_header_pr_page ()
{

echo '<div class="site-container"><div class="sitehead"><img src="http://cg.curriki.org/curriki/wp-content/themes/genesis-curriki/images/CurrikiLogo_2x.jpg"/></div>';
}
function curriki_print_resource_header() {

  if (function_exists('check_old_resource_rating'))
    check_old_resource_rating();

  if (isset($_GET['rid']) || isset($_GET['pageurl'])) {
    $res = new CurrikiResources();
    $resourceUser = $res->getResourceUserById((int) $_GET['rid'], rtrim($_GET['pageurl'], '/'));
    $res->setResourceViews((int) $resourceUser['resourceid']);
    //print_r($resourceUser);
  }

  $location = '';
  if (trim($resourceUser['city']) != '')
    $location .= $resourceUser['city'] . ', ';
  if (trim($resourceUser['state']) != '')
    $location .= $resourceUser['state'] . ', ';
  if (trim($resourceUser['country']) != '')
    $location .= $resourceUser['country'] . ', ';

 
  $resource_header = '<div class="resource-header page-header">';
  $resource_header .= '<div class="wrap container_12">';
 
  $resource_header .= '<div class="resource-info page-info g_10">';
  $resource_header .= '<h3 class="resource-title page-title">' . $resourceUser['title'] . '</h3>';
  $resource_header .= '<div class="resource-link page-link"><strong>Website Address:</strong> <a href="www.curriki.org/oer/' . $resourceUser['pageurl'] . '">www.curriki.org/oer/' . $resourceUser['pageurl'] . '</a></div>';
  
 
  $resource_header .= '</div>';
   $resource_header .= '</div>';
  $resource_header .= '</div>';
  $resource_header .= '</div>';
  

  echo $resource_header;
}

function curriki_print_resource_page_print_body() {

if (isset($_GET['rid']) || isset($_GET['pageurl'])) {
    $res = new CurrikiResources();
    $resource = $res->getResourceById((int) $_GET['rid'], rtrim($_GET['pageurl'], '/'), true);
    if ($resource['access'] == 'public')
      $resource['access'] = 'Public - Available to anyone';
    elseif ($resource['access'] == 'private')
      $resource['access'] = 'Private';
    elseif ($resource['access'] == 'members')
      $resource['access'] = 'Members';

 
}
 

  $resource_content = '<div class="wrap container_12">';
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
  $resource_content .= '<div class="resource-content">';
  $resource_content .= '<div class="resource-content-content">';

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
  echo '</div>';
	 
	


}
add_action('genesis_after', 'curriki_addthis_scripts');

genesis();
echo "<script>javascript:window.print();</script>";