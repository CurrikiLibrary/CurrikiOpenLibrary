<?php

/**
 * Plugin Name: WP LTI
 * Description: Wordpress LTI (IMS Global - Learning Tools Interoperability).
 * Plugin URI:
 * Version:     1.0
 * Author:      Curriki
 * Author URI:  https://www.curriki.org
 */

require_once 'bootstrap.php';
global $wp_cur_lti;
CurrikiLti\WP\Bootstrap::pluginSetup();
$wp_cur_lti = CurrikiLti\WP\Bootstrap::getInstance();