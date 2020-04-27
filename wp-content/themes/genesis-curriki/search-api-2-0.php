<?php

/*
 * Template Name: Search API 2.0 Template
 *
 * Child Theme Name: Curriki Child Theme for Genesis 2.1
 * Author: Muhammad Furqan Aziz
 */

//get_template_part('modules/search/functions');
require_once "./wp-content/libs/functions.php";
get_template_part('modules/search/classes/search');
$search = new search();
$search->curriki_search_api20_init();
$search->curriki_search_api20_auth();
$search->curriki_search_api20_make_topofsearch_query();
$search->curriki_search_api20_print_output();
exit;
