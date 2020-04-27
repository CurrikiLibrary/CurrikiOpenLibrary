<?php

/*
 * Template Name: Curriki Manage LTI
 *
 * Child Theme Name: Curriki Child Theme for Genesis 2.1
 * Author: Orange Blossom Media
 * Url: http://orangeblossommedia.com/
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
// Removing sharing from top
remove_filter( 'the_content', 'sharing_display', 19 ); 
remove_filter( 'the_excerpt', 'sharing_display', 19 );

get_template_part('modules/404/classes/Page404');
$page404 = new Page404();
$page404->curriki_module_targeted_init();

add_action('genesis_meta', array(&$page404, 'curriki_module_page_layout'));
add_filter('body_class', array(&$page404, 'curriki_module_page_body_class'));
add_action('genesis_before', array(&$page404, 'curriki_module_page_scripts'));
add_action('genesis_loop', array(&$page404, 'curriki_module_page_header'), 14);
add_action('genesis_loop', array(&$page404, 'curriki_module_page_body'), 15);
add_action('genesis_loop', array(&$page404, 'curriki_module_page_footer'), 16);

add_action('genesis_after', 'curriki_library_scripts');
add_action('genesis_after', 'curriki_addthis_scripts');

genesis();

