<?php
/*
 * Template Name: Search Old Resources Template
 *
 * Child Theme Name: Curriki Child Theme for Genesis 2.1
 * Author: Orange Blossom Media
 * Url: http://orangeblossommedia.com/
 */

// Add custom body class to the head
add_filter('body_class', 'curriki_search_old_resources_page_add_body_class');

function curriki_search_old_resources_page_add_body_class($classes) {
  $classes[] = 'backend search-page';
  return $classes;
}

// Execute custom style guide page
add_action('genesis_meta', 'curriki_custom_search_old_resources_page_loop');

function curriki_custom_search_old_resources_page_loop() {
  //* Force full-width-content layout setting
  add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');

  remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');
  remove_action('genesis_loop', 'genesis_do_loop');

  // add_action( 'genesis_before', 'curriki_search_old_resources_page_scripts' );
  // add_action( 'genesis_after_header', 'curriki_resource_header', 10 );
  add_action('genesis_loop', 'curriki_search_old_resources_page_body', 15);
}

function curriki_search_old_resources_page_scripts() {
  ?>
  <script>
    (function ($) {

      "use strict";
      $(function () {

      });
    }(jQuery));</script>
  <?php
}

function curriki_resource_header() {

  $resource_header = '<div class="resource-header page-header">';
  $resource_header .= '<div class="wrap container_12">';
  $resource_header .= '</div>';
  $resource_header .= '</div>';

  echo $resource_header;
}

function curriki_search_old_resources_page_body() {
  if(function_exists('check_old_resource_rating'))
    check_old_resource_rating();
  ?>
  <style>
    .search-input {
      border-radius: 8px;
    }
  </style>

  <div class="search-content" >
    <div class="wrap container_12" >
      <form action="<?php echo get_bloginfo('url'); ?>/oer/" method="POST" id="search_form">
        <div class="search-bar grid_12 rounded-borders-right rounded-borders-left ">
          <div class="search-input">
            <div class="search-field">
              <input class="rounded-borders-left" placeholder="Old Resource URL" type="text" name="q" value="<?php echo $_REQUEST['q']; ?>" >
            </div>
            <div class="search-button">
              <button type="submit" class="rounded-borders-right" ><span class="search-button-icon fa fa-search"></span>Search</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
  <?php
}

genesis();

