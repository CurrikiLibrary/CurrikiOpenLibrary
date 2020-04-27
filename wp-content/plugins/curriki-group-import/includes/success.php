<?php

/**
 * Instagram PHP API
 *
 * @link https://github.com/cosenary/Instagram-PHP-API
 * @author Christian Metz
 * @since 01.10.2013
 */

include '../../../../wp-load.php';
include '../../../../wp-admin/includes/file.php';
include '../../../../wp-admin/includes/image.php';

require_once 'Instagram.php';
use MetzWeb\Instagram\Instagram;

$bpbi_settings = get_option( 'bpbi_settings' );

// initialize class
  $instagram = new Instagram(array(
    'apiKey'      => $bpbi_settings['bpbi_client_id'], // 'd0255239c3ca4d1c8e1eb7d77efc69db',
    'apiSecret'   => $bpbi_settings['bpbi_client_secret'], // 'bf519f8dce2549359ac649c5dab0b1ad',
    'apiCallback' => home_url().'/wp-content/plugins/bp-bailiwik-instagram/includes/success.php' // must point to success.php
  ));

// receive OAuth code parameter
$code = $_GET['code'];

// check whether the user has granted access
if (isset($code)) {

  // receive OAuth token object
  $data = $instagram->getOAuthToken($code);
  $username = $username = $data->user->username;

  // store user access token
  $instagram->setAccessToken($data);

  // now you have access to all authenticated user methods
  $result = $instagram->getUserMedia();

  if ( $result->meta->code == "200" ) { 
    $link = bp_loggedin_user_domain() . '/settings/instagram/?success=true';
  } else {
    $link = bp_loggedin_user_domain() . '/settings/instagram/?success=false';
  }  
  wp_redirect( $link ); die();

} else {

  // check whether an error occurred
  if (isset($_GET['error'])) {
    echo 'An error occurred: ' . $_GET['error_description'];
  }

}

?>