<?php

/*
 * CHILD THEME FUNCTIONS (SITEWIDE)
 *
 * Child Theme Name: Curriki Child Theme for Genesis 2.1
 * Author: Orange Blossom Media
 * Url: http://orangeblossommedia.com/
 *
 * Add any functions here that you wish to use sitewide.
 */

// Change favicon location
function curriki_custom_favicon_location($favicon_url) {
    return get_stylesheet_directory_uri() . '/images/favicon.ico';
}

// Remove Edit Links
add_filter('edit_post_link', '__return_false');

// Add Features to Menu
function curriki_theme_menu_extras($menu, $args) {

    //* Change 'primary' to 'secondary' to add extras to the secondary navigation menu
    if ('primary' !== $args->theme_location)
        return $menu;

    //* Uncomment this block to add a search form to the navigation menu
    ob_start();
    curriki_search_bar();
    $search = ob_get_clean();
    $menu .= '<li class="right search">' . $search . '</li>';

    //* Uncomment this block to add the date to the navigation menu
    /*
      $menu .= '<li class="right date">' . date_i18n( get_option( 'date_format' ) ) . '</li>';
     */

    return $menu;
}

// Change the footer credits
function curriki_footer_cred($curriki_ft) {
    // $curriki_copy = '<p>&copy; ' . date("Y") .' | Curriki</p>';
    // return '<div class="copy">' . $curriki_copy . '</div>';
    genesis_widget_area('logo-footer', array(
        'before' => '<div class="logo-footer widget-area"><div class="wrap">',
        'after' => '</div></div>',
    ));
}

// Gravity Forms Placeholder Text
add_action('wp_print_scripts', 'curriki_gf_placeholder_enqueue_scripts');

function curriki_gf_placeholder_enqueue_scripts() {
    wp_enqueue_script('curriki_gf_placeholders', get_stylesheet_directory_uri() . '/js/gf-placeholder.js', array('jquery'), '1.0');
}

//* Customize the post meta function
add_filter('genesis_post_meta', 'sp_post_meta_filter');

function sp_post_meta_filter($post_meta) {
    if (!is_page()) {
        $post_meta = '[post_categories before="Posted in: "] [post_tags before="Tagged: "]';
        return $post_meta;
    }
}

// Edit the read more link text
add_filter('excerpt_more', 'curriki_read_more_link');
add_filter('get_the_content_more_link', 'curriki_read_more_link');
add_filter('the_content_more_link', 'curriki_read_more_link');

function curriki_read_more_link() {
    return '<a class="more-link" href="' . get_permalink() . '" rel="nofollow">Read More</a>';
}

// Force full width layout on all archive pages
add_filter('genesis_pre_get_option_site_layout', 'curriki_full_width_layout_archives');

function curriki_full_width_layout_archives($layout) {
    if (is_archive() || is_search()) {
        $layout = 'content-sidebar';
        return $layout;
    }
}

// Force Content/Sidebar layout on archives
add_action('genesis_meta', 'curriki_force_page_layout');

function curriki_force_page_layout() {

    if (is_archive() || is_search()) {
        add_filter('genesis_pre_get_option_site_layout', '__genesis_return_content_sidebar');
    }
}

// Customize search form input box text
add_filter('genesis_search_text', 'curriki_search_text');

function curriki_search_text($text) {
    return esc_attr( __('Search Blog Posts','curriki') );
}

//* Customize search form input button text
add_filter('genesis_search_button_text', 'curriki_search_button_text');

function curriki_search_button_text($text) {
    return esc_attr('');
}

// Curriki Search Bar
function curriki_search_bar() {
    // get_search_form();
    $search_bar = '';
    $search_bar .= '<div class="search-resources rounded-borders-full"><form action="' . get_bloginfo('url') . '/search/" method="GET">';
    $search_bar .= '<div class="search-dropdown rounded-borders-left">'
            . '<select class="search-dropdown-icon fa-caret-down top-search-bar-dropdown" name="type">'
            . '<option value="Resource">' . __('Resources', 'curriki') . '</option>'
            . '<option value="Member">' . __('Members', 'curriki') . '</option>'
            . '<option value="Group">' . __('Groups', 'curriki') . '</option>'
            . '</select></div>';
    $search_bar .= '<div class="search-input"><input name="phrase" type="text" placeholder="' . __('Start Searching', 'curriki') . '" /></div>';
    $search_bar .= '<input type="hidden" name="language" value="" />';
    $search_bar .= '<input type="hidden" name="start" value="0" />';
    $search_bar .= '<input type="hidden" name="partnerid" id="partnerid" value="1" />';
    $search_bar .= '<input type="hidden" name="searchall" value="" />';
    $search_bar .= '<input type="hidden" name="viewer" value="" />';
    $search_bar .= '<div class="search-button rounded-borders-right"><button type="submit"><span class="search-button-icon fa fa-search"></span></button></div>';
    $search_bar .= '<input type="hidden" name="branding" id="branding" value="common"/>';
    $search_bar .= '<input type="hidden" name="sort" value="rank1 desc" />';
    $search_bar .= '</form></div>';

    echo $search_bar;
}

/**
 * Register Sidebar
 */
function textdomain_register_sidebars() {

    /* Register the primary sidebar. */
    register_sidebar(
            array(
                'id' => 'primary-sidebar-one-column',
                'name' => __('One Column Sidebar', 'genesis'),
                'description' => __('A short description of the sidebar.', 'genesis'),
                'before_widget' => '<section class="widget"><div class="widget-wrap">',
                'after_widget' => '</div></section>',
                'before_title' => '<h4 class="widget-title widgettitle">',
                'after_title' => '</h4>'
            )
    );

    /* Repeat register_sidebar() code for additional sidebars. */
}

add_action('widgets_init', 'textdomain_register_sidebars');
