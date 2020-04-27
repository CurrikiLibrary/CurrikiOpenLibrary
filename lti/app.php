<?php
    require_once 'processor.php';
    
     
  /*
    launch_presentation_document_target=iframe
    launch_presentation_locale=en_US
    launch_presentation_return_url=https://www.imsglobal.org/lti/cert/tp_return.php/basic-lti-launch-request
    
    
    launch_presentation_document_target=window
    launch_presentation_locale=en_US
    launch_presentation_return_url=https://www.imsglobal.org/lti/cert/tp_return.php/basic-lti-launch-request
   */
  
    
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,"https://www.curriki.org/lti/processor.php?tm=".time());
curl_setopt($ch, CURLOPT_POST, 1);
// in real life you should use something like:
 curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query(array('request_action' => 'index_home')) );

// receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$server_output = curl_exec ($ch);
curl_close ($ch);
// further processing ....
if ($server_output) {

} else { 

}
   
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
<meta charset="UTF-8" />
<title>Curriki | Inspiring Learning Everywhere</title>


<script type='text/javascript' src='https://www.curriki.org/wp-includes/js/jquery/jquery.js?ver=1.12.4'></script>
<script type='text/javascript' src='https://www.curriki.org/wp-includes/js/jquery/jquery-migrate.min.js?ver=1.4.1'></script>

<script type="text/javascript">
    
    jQuery(function($) {
        var timeStamp = Math.floor(Date.now());
        $.ajax({
            method: "POST",
            url: "https://www.curriki.org/lti/app.php?t="+timeStamp,
            data: { request_action: "index_home"}
          }).done(function( data ) {
              $("#loader").html("");
              var rs = JSON.parse(data);
              console.log( "Return: " , rs );
              //error_message
              if(rs.hasOwnProperty("error_message"))
              {
                  $("#error-message-lti").html(rs.error_message);
              }
              if(rs.hasOwnProperty("message"))
              {
                  $("#message-lti").html(rs.message);
              }              
          });
    });
    
</script>
<style type="text/css">
    .message-lti
    {
        color: blue;
    }
    .error-message-lti
    {
        color: red;
    }
</style>
</head>
<body>
    <div id="loader">Loading.... </div>
    <div class="message-lti"></div>
    <div id="error-message-lti"></div>
    
    <pre>
        <?php    
            var_dump($server_output);
        ?>
    </pre>
</body>