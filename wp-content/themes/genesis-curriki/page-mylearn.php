<?php

/*
 * Template Name: Curriki Learn Dashboard
 *
 * Child Theme Name: Curriki Child Theme for Genesis 2.1
 * Author: Curriki
 * Url: https://www.curriki.org
 * 
 * 
 */

// Removing sharing from top
remove_filter( 'the_content', 'sharing_display', 19 ); 
remove_filter( 'the_excerpt', 'sharing_display', 19 );

get_template_part('modules/learn/classes/learn');
$program_collections = [];
$learn = new Learn();

//$learn->loginRedirect();
$learn->curriki_module_targeted_init();
//$learn->curriki_module_page_body();


add_action('genesis_meta', array(&$learn, 'curriki_module_page_layout'));
add_filter('body_class', array(&$learn, 'curriki_module_page_body_class'));
add_action('genesis_before', array(&$learn, 'curriki_module_page_scripts'));    
add_action('genesis_loop', array(&$learn, 'curriki_module_page_body'), 15);


add_action('genesis_after', 'curriki_library_scripts');
add_action('genesis_after', 'curriki_addthis_scripts');

remove_action('genesis_after_content', 'genesis_get_sidebar');

genesis();