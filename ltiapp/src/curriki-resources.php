<?php
/**
 * rating - Rating: an example LTI tool provider
 *
 * @author  Stephen P Vickers <svickers@imsglobal.org>
 * @copyright  IMS Global Learning Consortium Inc
 * @date  2016
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3.0
 */
/*
 * This page processes a launch request from an LTI tool consumer.
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use IMSGlobal\LTI\ToolProvider\DataConnector;
require_once('rating_tp.php');

// Cancel any existing session
session_name(SESSION_NAME);
session_start();
$_SESSION = array();
session_destroy();

  /*
  $tim = time();
  echo "<pre>me there... $tim <br />";
  var_dump($_REQUEST);
  die;
  */
  
  /*
  $oauth_nonce = $_POST["oauth_nonce"];
  $oauth_timestamp = $_POST["oauth_timestamp"];
  $oauth_signature = $_POST["oauth_signature"];
  echo "oauth_nonce = $oauth_nonce <br />";
  echo "oauth_timestamp = $oauth_timestamp <br />";
  echo "oauth_signature = $oauth_signature <br />";  
  die();
  */
  
  
// Initialise database
  $db = NULL;
  if (init($db)) {
    
    $data_connector = DataConnector\DataConnector::getDataConnector(DB_TABLENAME_PREFIX, $db);
    
    $tool = new RatingToolProvider($data_connector);    
    //$tool->setParameterConstraint('oauth_consumer_key', TRUE, 50, array('basic-lti-launch-request'));
    $tool->setParameterConstraint('oauth_consumer_key', TRUE, 50, array('basic-lti-launch-request', 'ContentItemSelectionRequest', 'DashboardRequest'));
    $tool->setParameterConstraint('resource_link_id', TRUE, 50, array('basic-lti-launch-request'));
    
    //$tool->setParameterConstraint('resource_link_id', TRUE, 50, array('basic-lti-launch-request'));
    /*
    $tool->setParameterConstraint('oauth_consumer_key', TRUE, 50, array('basic-lti-launch-request', 'ContentItemSelectionRequest', 'DashboardRequest'));
    $tool->setParameterConstraint('resource_link_id', TRUE, 50, array('basic-lti-launch-request'));
    $tool->setParameterConstraint('user_id', TRUE, 50, array('basic-lti-launch-request'));
    */
    //$tool->setParameterConstraint('roles', TRUE, NULL, array('basic-lti-launch-request'));
    /*
    echo "<pre>*-*-*-*-*-*-*-*<br />";
    var_dump($tool);
    echo "<br />**************************************<br />";
    //var_dump($_REQUEST);
    die;
    */
  } else {
    $tool = new RatingToolProvider(NULL);
    $tool->reason = $_SESSION['error_message'];
  }  
  
  $tool->handleRequest();
  die();
?>
