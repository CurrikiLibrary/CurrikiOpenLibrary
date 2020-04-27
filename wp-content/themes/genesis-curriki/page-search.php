<?php

/*
 * Template Name: Search Page Template
 *
 * Child Theme Name: Curriki Child Theme for Genesis 2.1
 * Author: Orange Blossom Media
 * Url: http://orangeblossommedia.com/
 * 
 * Query: UPDATE `cur_postmeta` set meta_value = 'page-search.php' WHERE meta_key = '_wp_page_template' AND post_id in ('7','6017');
 * 
 */
if(isset($_REQUEST['api']) && $_REQUEST['api'] == 'true'){
    global $wpdb;
    $results = $wpdb->get_row('select resourceid, type, content, contributiondate from resources where resourceid = '.$_REQUEST['resourceid'] . '  and (content = "" or content like "&darr%") and active = "T" ');
    echo json_encode($results);

    die();
}


if(!(
    isset($_REQUEST['type'])
    && isset($_REQUEST['start'])
    && isset($_REQUEST['partnerid'])
    && isset($_REQUEST['branding'])
    && isset($_REQUEST['sort'])
)){
    $_GET['type'] = 'Resource';
    $_GET['start'] = 0;
    $_GET['partnerid'] = 1;
    $_GET['branding'] = 'common';
    $_GET['sort'] = 'rank1 desc';
    $_GET['size'] = 10;
    $_GET['phrase'] = '';
    $_GET['searchall'] = '';
    $_GET['viewer'] = '';
    $_GET['approvalstatus'] = '';
    $_GET['resourcetype'] = '';
}

//get_template_part('modules/search/functions');
get_template_part('modules/search/classes/search');
$search = new search();
$search->curriki_search_page_init();

add_action('genesis_meta', array(&$search, 'curriki_search_page_layout'));
add_filter('body_class', array(&$search, 'curriki_search_page_body_class'));
add_action('genesis_before', array(&$search, 'curriki_search_page_scripts'));
add_action('genesis_loop', array(&$search, 'curriki_search_page_header'), 14);
add_action('genesis_loop', array(&$search, 'curriki_search_page_body'), 15);
add_action('genesis_loop', array(&$search, 'curriki_search_page_footer'), 16);

add_action('genesis_after', 'curriki_library_scripts');
add_action('genesis_after', 'curriki_addthis_scripts');

genesis();
