<?php

/*
 * Template Name: Search Targeted Landing Page Template
 *
 * Child Theme Name: Curriki Child Theme for Genesis 2.1
 * Author: Orange Blossom Media
 * Url: http://orangeblossommedia.com/
 * 
 * Query: UPDATE `cur_postmeta` set meta_value = 'page-search.php' WHERE meta_key = '_wp_page_template' AND post_id in ('7','6017');
 * 
 */

//get_template_part('modules/search/functions');

// Removing sharing from top
remove_filter( 'the_content', 'sharing_display', 19 ); 
remove_filter( 'the_excerpt', 'sharing_display', 19 );

get_template_part('modules/search/classes/search');
$search = new search();
$search->curriki_search_targeted_init();

add_action('genesis_meta', array(&$search, 'curriki_search_page_layout'));
add_filter('body_class', array(&$search, 'curriki_search_page_body_class'));
add_action('genesis_before', array(&$search, 'curriki_search_page_scripts'));
add_action('genesis_loop', array(&$search, 'curriki_search_page_header'), 14);
add_action('genesis_loop', array(&$search, 'curriki_search_targeted_body'), 15);
add_action('genesis_loop', array(&$search, 'curriki_search_page_footer'), 16);

add_action('genesis_after', 'curriki_library_scripts');
add_action('genesis_after', 'curriki_addthis_scripts');

genesis();
