<?php
/*
* Template Name: DIVI Full Width
*
* Child Theme Name: Curriki Child Theme for Genesis 2.1
* Author: John Hamman
* Url: http://curriki.com/
*/

//* Add landing page body class to the head
add_filter( 'body_class', 'add_body_class_to_genesis' );
function add_body_class_to_genesis( $classes ) {
$classes[] = 'divi-page';
return $classes;
}

//* Remove page title for one single page
add_action( 'get_header', 'remove_page_title' );
function remove_page_title() {
        remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
   
}

//* Force full width content layout
add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );

//* Run the Genesis loop
genesis();