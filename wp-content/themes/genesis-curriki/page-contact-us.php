<?php
/*
* Template Name: Contact Us Page Template
*
* Child Theme Name: Curriki Child Theme for Genesis 2.1
* Author: Sajid
* Url: http://curriki.com/
*/

remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
add_action( 'genesis_sidebar', 'curriki_genesis_do_sidebar' );
function curriki_genesis_do_sidebar() {
    dynamic_sidebar( 'contactus-sidebar' );
}
genesis();