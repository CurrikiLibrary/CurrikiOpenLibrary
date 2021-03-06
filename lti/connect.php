<?php
/**
 * curriki - Curriki: LTI tool provider
 *
 * @author  Curriki.org <svickers@imsglobal.org>
 * @copyright  IMS Global Learning Consortium Inc
 * @date  2016
 * @version 1.0.0
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3.0
 */

/*
 * This page processes a launch request from an LTI tool consumer.
 */

  use IMSGlobal\LTI\ToolProvider\DataConnector;

  require_once('curriki_tp.php');
 
  /*
  echo "<pre>";
  var_dump($_REQUEST);
  die;
  */
// Cancel any existing session
  //session_name(SESSION_NAME);
  session_start();
  $_SESSION = array();
  session_destroy();

// Initialise database
  $db = NULL;
  if (init($db)) {    
    $data_connector = DataConnector\DataConnector::getDataConnector(DB_TABLENAME_PREFIX, $db);
    $tool = new CurrikiToolProvider($data_connector);
    $tool->setParameterConstraint('resource_link_id', TRUE, 50, array('basic-lti-launch-request'));
    $tool->setParameterConstraint('oauth_consumer_key', TRUE, 50, array('basic-lti-launch-request','ContentItemSelectionRequest'));
    $tool->setParameterConstraint('user_id', TRUE, 50, array('basic-lti-launch-request'));
    $tool->setParameterConstraint('roles', TRUE, NULL, array('basic-lti-launch-request'));
    
    /*
    $tool->setParameterConstraint('oauth_consumer_key', TRUE, 50, array('basic-lti-launch-request', 'ContentItemSelectionRequest', 'DashboardRequest'));
    $tool->setParameterConstraint('resource_link_id', TRUE, 50, array('basic-lti-launch-request'));
    $tool->setParameterConstraint('user_id', TRUE, 50, array('basic-lti-launch-request'));
    $tool->setParameterConstraint('roles', TRUE, NULL, array('basic-lti-launch-request'));
     * 
     */
  } else {
    $tool = new CurrikiToolProvider(NULL);
    $tool->reason = $_SESSION['error_message'];
  }
  $tool->handleRequest();

?>
