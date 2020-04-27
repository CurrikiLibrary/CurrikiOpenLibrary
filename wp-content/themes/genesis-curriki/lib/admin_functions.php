<?php
/*
* ADMIN FUNCTIONS
*
* Child Theme Name: Curriki Child Theme for Genesis 2.1
* Author: Orange Blossom Media
* Url: http://orangeblossommedia.com/
*
* This file handles the admin area and functions.
* You can use this file to make changes to the
* dashboard.
*/

/************* DASHBOARD WIDGETS *****************/

// disable default dashboard widgets
function curriki_disable_dashboard_widgets() {
	// remove_meta_box('dashboard_right_now', 'dashboard', 'core');    // Right Now Widget
	remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'core' ); // Comments Widget
	remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'core' );  // Incoming Links Widget
	remove_meta_box( 'dashboard_plugins', 'dashboard', 'core' );         // Plugins Widget
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'core' );  // Quick Press Widget
	remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'core' );   // Recent Drafts Widget
	remove_meta_box( 'dashboard_primary', 'dashboard', 'core' );         // Wordpress Blog
	remove_meta_box( 'dashboard_secondary', 'dashboard', 'core' );       // Other Wordpress News
}

// removing the dashboard widgets
add_action('admin_menu', 'curriki_disable_dashboard_widgets');

/************* CUSTOM LOGIN PAGE *****************/

// calling our own login css so you can style it
function curriki_login_css() { ?>
    <link rel="stylesheet" id="custom_wp_admin_css"  href="<?php echo get_stylesheet_directory_uri() . '/css/login.css'; ?>" type="text/css" media="all" />
<?php }

// changing the logo link from wordpress.org to your site
function curriki_login_url() { return get_bloginfo( 'url' ); }

// changing the alt text on the logo to show your site name
function curriki_login_title() { return get_option( 'blogname' ); }

// calling it only on the login page
//add_action( 'login_enqueue_scripts', 'curriki_login_css' );
//add_filter( 'login_headerurl', 'curriki_login_url' );
//add_filter( 'login_headertitle', 'curriki_login_title' );


/************* CUSTOMIZE ADMIN *******************/

// Custom Backend Footer
add_filter( 'admin_footer_text', 'curriki_custom_admin_footer' );
function curriki_custom_admin_footer() {
	echo '<span id="footer-thankyou">Developed by <a href="http://orangeblossommedia.com" target="_blank">Orange Blossom Media</a></span>. Built using <a href="http://studiopress.com" target="_blank">the Genesis Framework</a> on <a href="http://wordpress.org">WordPress</a>.';
}

