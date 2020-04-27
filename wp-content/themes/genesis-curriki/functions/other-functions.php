<?php
/*
 * Resource file ajax
 */

include_once(dirname(dirname(dirname(__DIR__))) . '/libs/functions.php');

function resource_thumb_ajax() {
    $response = array();
//    echo json_encode(['success'=>false, 'path'=>dirname(dirname(dirname(__DIR__))) . '/libs/functions.php']);
//    die();
    if ( 0 < $_FILES['file']['error'] ) {
        echo json_encode(['success'=>false]);
        
    }
    else {
        $ext = "";
        $info   = getimagesize($_FILES['file']['tmp_name']);
        if ($info === FALSE)
        {
            echo json_encode(['success'=>false, 'msg'=>'Please select a valid image']);
            wp_die();
        } else if($info['mime'] == 'image/png'){
            $ext = ".png";
        } else if($info['mime'] == 'image/jpeg') {
            $ext = ".jpg";
        } else if($info['mime'] == 'image/gif') {
            $ext = ".gif";
        } else {
            echo json_encode(['success'=>false, 'msg'=>'File type can only be png, gif, jpg']);
            wp_die();
        }
       
        
        
       
        validateUploadFile('file', 'file', $response);
        if ($response['status']) {
            uploadFileS3($response);
        }
        echo json_encode(['success'=>true,'response'=>$response]);
            
            
            
        
        
    }
    // Always die in functions echoing ajax content
   die();
}
 
add_action( 'wp_ajax_nopriv_resource_thumb_ajax', 'resource_thumb_ajax' );
add_action( 'wp_ajax_resource_thumb_ajax', 'resource_thumb_ajax' );


function update_resource_status_ajax() {
    $response = array();
//        validateUploadFile('file', 'file', $response);
//        if ($response['status']) {
//            uploadFileS3($response);
//        }
    
        $msg = awsResourceStatusUpdateCloudSearch($_POST['resourceid'], $_POST['approvalStatus'], 'resources');
        echo json_encode(['success'=>true, 'response'=>$_POST, 'msg'=>$msg]);
    
    // Always die in functions echoing ajax content
   die();
}
 
add_action( 'wp_ajax_nopriv_update_resource_status_ajax', 'update_resource_status_ajax' );
add_action( 'wp_ajax_update_resource_status_ajax', 'update_resource_status_ajax' );
