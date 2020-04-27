<?php
/**
 * Plugin Name: Curriki Application
 * Description: Curriki Application.
 * Plugin URI:
 * Version:     1.0
 * Author:      Waqar Muneer
 * Author URI:  https://www.curriki.org
 */

require_once __DIR__.'/vendor/autoload.php';
require_once 'config.php';

use CurrikiSite\Applicaton;

add_action('plugins_loaded', array(Applicaton::getInstance(),'bootstrap'));