<?php

/*
 * Template Name: Curriki Community Pages
 *
 * Child Theme Name: Curriki Child Theme for Genesis 2.1
 * Author: Waqar Muneer
 * Url: http://xyz.com/
 * 
 * Query: UPDATE `cur_postmeta` set meta_value = 'page-search.php' WHERE meta_key = '_wp_page_template' AND post_id in ('7','6017');
 * 
 */

//get_template_part('modules/search/functions');

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
        
if( isset($_GET["comm_url"]) )
{
    
    
    $_GET["comm_url"] = str_replace("/", "", $_GET["comm_url"]);
    
    // Removing sharing from top
    remove_filter( 'the_content', 'sharing_display', 19 ); 
    remove_filter( 'the_excerpt', 'sharing_display', 19 );

    get_template_part('modules/community-pages/classes/community-pages');
    $community_pages = new CommunityPages();
    
    //$community_pages->loginRedirect();
    $community_pages->curriki_module_targeted_init();
    //$community_pages->curriki_module_page_body();


    add_action('genesis_meta', array(&$community_pages, 'curriki_module_page_layout'));
    add_filter('body_class', array(&$community_pages, 'curriki_module_page_body_class'));
    add_action('genesis_before', array(&$community_pages, 'curriki_module_page_scripts'));    
    add_action('genesis_loop', array(&$community_pages, 'curriki_module_page_body'), 15);
    

    add_action('genesis_after', 'curriki_library_scripts');
    add_action('genesis_after', 'curriki_addthis_scripts');

    remove_action('genesis_after_content', 'genesis_get_sidebar');
    
    genesis();

}else{
    
    add_filter('body_class', 'curr_community_pages_remove_class');
    add_action('get_header', 'cur_comm_child_sidebar_logic');
    get_template_part('page-right-column');
}

function curr_community_pages_remove_class($classes) 
{
        
    if( in_array("page-template", $classes) )
    {        
        unset( $classes[array_search("page-template", $classes)] );        
        $classes = array_values($classes);        
    }
    
    return $classes;
}

function cur_comm_child_sidebar_logic() {
  
    remove_action('genesis_after_content', 'genesis_get_sidebar');
    add_action('genesis_after_content', 'cur_comm_child_get_blog_sidebar');
    add_filter('body_class', 'cur_comm_class_names');
    
}

function cur_comm_class_names($classes) {
  if (($key = array_search('page-template', $classes)) !== false) {
    unset($classes[$key]);
  }
  $classes[] = 'page-template-default';
  return $classes;
}

function cur_comm_child_get_blog_sidebar() {
  $my_post_meta = get_post_meta(get_the_id(), 'rightcontent_value', true);
  if (!empty($my_post_meta)) {
    $input = get_post_meta(get_the_id(), 'rightcontent_value', true);
    $str = html_entity_decode($input);
  }
  echo '<aside class="sidebar sidebar-primary widget-area" role="complementary" itemscope="itemscope" itemtype="http://schema.org/WPSideBar">
	<section id="text-12" class="widget widget_text">' . $str . '</section>
	</aside>';
}
