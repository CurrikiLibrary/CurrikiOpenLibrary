<?php

use IMSGlobal\LTI\ToolProvider;
use IMSGlobal\LTI\ToolProvider\DataConnector;
require_once('lib.php');

$request_action = $_POST["request_action"];
if(isset($request_action))
{
    
    //var_dump($_POST);
    /*
    echo session_name();*/
    /*
    echo " =======1111======== ";
    var_dump($ss);        
    echo " =======11000011======== ";
    var_dump($_SESSION);
    */    
    $request_action_arr = explode("_", $request_action);
    $output = new stdClass();
    $output->controller = $request_action_arr[0];
    $output->action = $request_action_arr[1];
    // Initialise session and database
    $db = NULL;
    $ok = init($db, TRUE);
    $output->ok = $ok;
    
    if($ok) 
    {        
        $data_connector = DataConnector\DataConnector::getDataConnector(DB_TABLENAME_PREFIX, $db);
        if(isset($_SESSION['consumer_pk']))
        {
            $consumer = ToolProvider\ToolConsumer::fromRecordId($_SESSION['consumer_pk'], $data_connector);
            $output->consumer = $consumer;
        }
        if(isset($_SESSION['resource_pk']))
        {          
            $resource_link = ToolProvider\ResourceLink::fromRecordId($_SESSION['resource_pk'], $data_connector);
            $output->resource_link = $resource_link;
        }        
    }
    // Check for any messages to be displayed
    if (isset($_SESSION['error_message'])) 
    {            
        $output->error_message = $_SESSION['error_message'];
        unset($_SESSION['error_message']);
    }
    if (isset($_SESSION['message'])) 
    {         
        $output->error_message = $_SESSION['message'];
        unset($_SESSION['message']);
    }    
    
    $output->session = $_SESSION;
    
    echo json_encode($output);
    exit();
    
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

