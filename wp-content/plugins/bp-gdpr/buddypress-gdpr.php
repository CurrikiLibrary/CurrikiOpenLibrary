<?php
/**
 * BuddyPress GDPR
 *
 * @package     BuddyBoss GDPR
 * @category    Core
 *
 */

/**
 * Plugin Name: BuddyPress GDPR
 * Plugin URI:  https://www.buddyboss.com
 * Description: BuddyPress GDPR helps website owners to comply with European privacy regulations (GDPR).
 * Author:      BuddyBoss
 * Author URI:  https://www.buddyboss.com
 * Version:     1.0.1
 * Text Domain: buddyboss-bp-gdpr
 * Domain Path: /languages
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$plugin_folder = dirname( __FILE__ );
require_once $plugin_folder . '/includes/Controller.php';
require_once $plugin_folder . '/includes/functions.php';

/**
 * Get the main plugin object.
 *
 * @return \BuddyBoss\BpGdpr\Controller the singleton object
 */
function buddyboss_bp_gdpr() {
	return \BuddyBoss\BpGdpr\Controller::instance( __FILE__ );
}

add_action( 'plugins_loaded', 'buddyboss_bp_gdpr' );
