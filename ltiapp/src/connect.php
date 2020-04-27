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

//Test App New = 7~Lzj5sshTF5WpZ4Rl3RhJQfT2olMD6CrxoWqpGRX6gp2bR0Z4ajb2ipbGrCY8giAN
/*Key: w1632c3e0dae7750862cec613b030c8r
Secret: la2e6f89e258ccd802efd1243e0e3fdrq
 * 
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
  
  
    if( isset($_GET["apiexe"]) )
    {
        $method = "GET";
        $url = "https://canvas.instructure.com/api/v1/users/self/communication_channels?access_token=7~WVamBr0eiZpTClbcmR45vjyKH3reJ8Lj9a7hvo6JX84g3g5hm3Gc7oPRwTcIwU2Y";
        //$data = array("access_token"=>"7~WVamBr0eiZpTClbcmR45vjyKH3reJ8Lj9a7hvo6JX84g3g5hm3Gc7oPRwTcIwU2Y");
        $rs = CallAPI($method, $url, $data = false);
        
        echo "<pre>";
        var_dump($rs);
        die();
    }
  
    function CallAPI($method, $url, $data = false)
    {
        $curl = curl_init();
        /*
        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }*/

        // Optional Authentication:
        //curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        //curl_setopt($curl, CURLOPT_USERPWD, "username:password");

        //echo $url;die;
        
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }
    
        
// Initialise database
  $db = NULL;
  if (init($db)) {
    
    $data_connector = DataConnector\DataConnector::getDataConnector(DB_TABLENAME_PREFIX, $db);
    
    $tool = new RatingToolProvider($data_connector);
    
    $tool->setParameterConstraint('oauth_consumer_key', TRUE, 50, array('basic-lti-launch-request', 'ContentItemSelectionRequest', 'DashboardRequest'));
    $tool->setParameterConstraint('resource_link_id', TRUE, 50, array('basic-lti-launch-request'));
    $tool->setParameterConstraint('user_id', TRUE, 50, array('basic-lti-launch-request'));
    //$tool->setParameterConstraint('roles', TRUE, NULL, array('basic-lti-launch-request'));
    /*
    echo "<pre>*-*-*-*-*-*-*-*<br />";
    var_dump($tool);
    echo "<br />**************************************<br />";
    var_dump($_REQUEST);
    die;
    */
  } else {
    $tool = new RatingToolProvider(NULL);
    $tool->reason = $_SESSION['error_message'];
  }  
  
  $tool->handleRequest();
  die();
?>
