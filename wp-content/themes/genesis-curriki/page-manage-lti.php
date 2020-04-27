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
//echo "aaaaaaaaaaaa";die;

// Removing sharing from top
remove_filter( 'the_content', 'sharing_display', 19 ); 
remove_filter( 'the_excerpt', 'sharing_display', 19 );

get_template_part('modules/lti-front/classes/lti');
$lti = new Lti();
//$lti->loginRedirect();
$lti->curriki_module_targeted_init();

$lti->curriki_module_page_body();


//add_action('genesis_meta', array(&$lti, 'curriki_module_page_layout'));
//add_filter('body_class', array(&$lti, 'curriki_module_page_body_class'));
//add_action('genesis_before', array(&$lti, 'curriki_module_page_scripts'));
//add_action('genesis_loop', array(&$lti, 'curriki_module_page_header'), 14);
//add_action('genesis_loop', array(&$lti, 'curriki_module_page_body'), 15);
//add_action('genesis_loop', array(&$lti, 'curriki_module_page_footer'), 16);

//add_action('genesis_after', 'curriki_library_scripts');
//add_action('genesis_after', 'curriki_addthis_scripts');

//genesis();

