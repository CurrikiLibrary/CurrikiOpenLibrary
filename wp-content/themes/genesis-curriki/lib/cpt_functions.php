<?php
/*
* CHILD SETUP FUNCTIONS
*
* Child Theme Name: Curriki Child Theme for Genesis 2.1
* Author: Orange Blossom Media
* Url: http://orangeblossommedia.com/
*
*/

// Contributor CPT
add_action( 'init', 'curriki_register_cpt_contributor' );

function curriki_register_cpt_contributor() {

    $labels = array(
        'name' => _x( 'Contributors', 'contributor' ),
        'singular_name' => _x( 'Contributor', 'contributor' ),
        'add_new' => _x( 'Add New', 'contributor' ),
        'add_new_item' => _x( 'Add New Contributor', 'contributor' ),
        'edit_item' => _x( 'Edit Contributor', 'contributor' ),
        'new_item' => _x( 'New Contributor', 'contributor' ),
        'view_item' => _x( 'View Contributor', 'contributor' ),
        'search_items' => _x( 'Search Contributors', 'contributor' ),
        'not_found' => _x( 'No Contributors Found', 'contributor' ),
        'not_found_in_trash' => _x( 'No Contributors Found in Trash', 'contributor' ),
        'parent_item_colon' => _x( 'Parent Contributor:', 'contributor' ),
        'menu_name' => _x( 'Contributors', 'contributor' ),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'supports' => array( 'title', 'editor', 'thumbnail' ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type( 'contributor', $args );
}

