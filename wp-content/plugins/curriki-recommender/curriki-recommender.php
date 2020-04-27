<?php

/**
 * Plugin Name: Curriki Recommendation Engine
 * Description: Curriki recommender for it's top ranked Resources to the users.
 * Plugin URI:
 * Version:     1.0
 * Author:      Waqar Muneer
 * Author URI:  https://www.curriki.org
 */

require_once __DIR__.'/vendor/autoload.php';

use CurrikiRecommender\Recommender;
add_action('plugins_loaded', array(Recommender::getInstance(),'pluginSetup'));
