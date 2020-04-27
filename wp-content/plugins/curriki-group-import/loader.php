<?php
/*
Plugin Name: Curriki Group Importer
Version: 1.0
Author: David Bisset
*/

/***************************
* constants
***************************/

if ( !defined( 'CURGI_BASE_DIR' ) ) {
	define( 'CURGI_BASE_DIR', dirname( __FILE__ ) );
}
if ( !defined( 'CURGI_BASE_URL' ) ) {
	define( 'CURGI_BASE_URL', plugin_dir_url( __FILE__ ) );
}
if ( !defined( 'CURGI_BASE_FILE' ) ) {
	define( 'CURGI_BASE_FILE', __FILE__ );
}

if ( !defined( 'CURGI_PLUGIN_VERSION' ) ) define( 'CURGI_PLUGIN_VERSION', '1.0' );

$curgi_options = get_option( 'curgi_settings' );


/***************************
* language files
***************************/

function curgi_textdomain() {
	load_plugin_textdomain( 'curriki_group_import', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'curgi_textdomain' );

/***************************
* includes
***************************/

if ( is_admin() ) {
	// include CURGI_BASE_DIR . '/includes/settings.php';
}

/**
 * Load only when BuddyPress is present.
 */
function curgi_include() {
	require( CURGI_BASE_DIR . '/curriki-group-import.php' );
}
add_action( 'bp_include', 'curgi_include' );



/**
 * Settings link in the plugins page
 *
 * @since 0.1
 *
 * @param array $links Plugin links
 * @return array Plugins links with settings added
 */
function curgi_settings_link( $links ) {

	$links[] = '<a href="options-general.php?page=curriki-group-import-settings">' . __( 'Settings', 'curriki-group-import' ) . '</a>';

	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ),'curgi_settings_link' );



















