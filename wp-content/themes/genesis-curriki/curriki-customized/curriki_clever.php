<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
putenv("CLEVER_CLIENT_ID=e5da6ddd7da309c332b6");
putenv("CLEVER_CLIENT_SECRET=d845cb31f01fb21d5e72d3a70641ed2e7c93863a");
putenv("CLEVER_REDIRECT_BASE=".get_bloginfo('url'));


error_reporting(E_ALL);


if(isset($_GET['clever_login'])){
    if($_SERVER && array_key_exists('REQUEST_URI', $_SERVER)) {   

      process_incoming_requests($_SERVER['REQUEST_URI'], curr_clever_set_options()); 
    }
    die;
}


function curr_clever_set_options(array $override_options = NULL) {
   
  $options = array(
    // Obtain your Client ID and secret from your Clever developer dashboard at https://account.clever.com/partner/applications
    'client_id' => getenv('CLEVER_CLIENT_ID'),
    'client_secret' => getenv('CLEVER_CLIENT_SECRET'),
    'clever_redirect_base' => getenv('CLEVER_REDIRECT_BASE'),
    'clever_oauth_base' => 'https://clever.com/oauth',
    'clever_api_base' => 'https://api.clever.com',
  );
  if (isset($override_options)) {
    array_merge($options, $override_options);
  }

  $options['clever_oauth_tokens_url'] = $options['clever_oauth_base'] . "/tokens";
  $options['clever_oauth_authorize_url'] = $options['clever_oauth_base'] . "/authorize";
  $options['clever_api_me_url'] = $options['clever_api_base'] . '/me';

  // Clever redirect URIs must be preregistered on your developer dashboard.
  // If using the default PORT set above, make sure to register "http://localhost:2587/oauth"
  $options['client_redirect_url'] = $options['clever_redirect_base'] . "/oauth";
  if (!empty($options['client_id']) && !empty($options['client_secret']) && !empty($options['clever_redirect_base'])) {
    return $options;
  } else {
    throw new Exception("Cannot communicate with Clever without configuration.");
  }
}


function process_incoming_requests($incoming_request_uri, array $options) {          
    
  if(preg_match('/oauth/', $incoming_request_uri)) {
  
    try {
        
      $me = process_client_redirect($_GET['code'], $options);                    
      echo "<pre>";
      var_dump($me);
      
      echo("<p>Here's some information about the user:</p>");
      echo("<ul>");
      $fields = array('type' => 'User type', 'id' => 'User ID', 'district' => 'District ID');
      foreach($fields as $key => $label) {
        echo("<li>{$label}: {$me['data'][$key]}");
      }
      echo("</ul>");
    } catch (Exception $e) {
      echo("<p>Something exceptional happened while interacting with Clever.");
      echo("<pre>");
      print_r($e);
      echo("</pre>");
    }
  } else {
    // Our home page route will create a Clever Instant Login button for users
    $sign_in_link = generate_sign_in_with_clever_link($options);
    echo("<h1>clever_oauth_examples: Login!</h1>");
    echo('<p>' . $sign_in_link . '</p>');
    echo("<p>Ready to handle OAuth 2.0 client redirects on {$options['client_redirect_url']}.</p>");
  }
}


function process_client_redirect($code, array $options) {
   
  $bearer_token = exchange_code_for_bearer_token($code, $options);
  
   
  $request_options = array('method' => 'GET', 'bearer_token' => $bearer_token);
  $me_response = curr_retrieve_me_response_for_bearer_token($bearer_token, $options);

  // Real world applications would store the bearer token and relevant information about the user at this stage.
  return $me_response;
}


function exchange_code_for_bearer_token($code, array $options) {
  $data = array('code' => $code,
                'grant_type' => 'authorization_code',
                'redirect_uri' => $options['client_redirect_url']);
  
  $request_options = array('method' => 'POST', 'data' => $data);
  $response = request_from_clever_curr($options['clever_oauth_tokens_url'], $request_options, $options);
  
  // Evaluate if the response is successful
  if ($response && $response['response_code'] && $response['response_code'] == '200') {
    $bearer_token = $response['response']['access_token'];
    return $bearer_token;
  } else {
    // Handle condition when $code cannot be exchanged for bearer token from Clever
    throw new Exception("Cannot retrieve bearer token.");
  }
}


function curr_retrieve_me_response_for_bearer_token($bearer_token, array $options) {
  $request_options = array('method' => 'GET', 'bearer_token' => $bearer_token);
  $response = request_from_clever_curr($options['clever_api_me_url'], $request_options, $options);
  // Evaluate if the response is successful
  if ($response && $response['response_code'] && $response['response_code'] == '200') {
    $oauth_response = $response['response'];
    return $oauth_response;
  } else {
    // Handle condition when /me response cannot be retrieved for bearer token
    throw new Exception("Could not retrieve /me response for bearer token.");
  }
}

function request_from_clever_curr($url, array $request_options, array $clever_options) {
      
  $ch = curl_init($url);
  $request_headers = array('Accept: application/json');
  if ($request_options && array_key_exists('bearer_token', $request_options)) {
    $auth_header = 'Authorization: Bearer ' . $request_options['bearer_token'];
    $request_headers[] = $auth_header;
  } else {
    // When we don't have a bearer token, assume we're performing client auth.
    curl_setopt($ch, CURLOPT_USERPWD, $clever_options['client_id'] . ':' . $clever_options['client_secret']);
  }
  if ($request_options && array_key_exists('method', $request_options) && $request_options['method'] == 'POST') {
    curl_setopt($ch, CURLOPT_POST, 1);
    if ($request_options['data']) {
      curl_setopt($ch, CURLOPT_POSTFIELDS, $request_options['data']);
    }
  }
  // Set prepared HTTP headers
  curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $raw_response = curl_exec($ch);
  $parsed_response = json_decode($raw_response, true);
  $curl_info = curl_getinfo($ch);

  // Provide the HTTP response code for easy error handling.
  $response_code = $curl_info['http_code'];

  if($curl_error = curl_errno($ch)) {
    $error_message = curl_strerror($curl_error);
    throw new Exception("cURL failure #{$curl_error}: {$error_message}");
  }
  
  // Prepare the parsed and raw response for further use.
  $normalized_response = array('response_code' => $response_code, 'response' => $parsed_response, 'raw_response' => $raw_response, 'curl_info' => $curl_info);  
  return $normalized_response;
}


function generate_sign_in_with_clever_url_curr(array $options) {
  $request_params = array(
    'response_type' => 'code',
    'redirect_uri' => $options['client_redirect_url'],
    'client_id' => $options['client_id'],
    'scope' => 'read:user_id read:teachers read:students'
  );
  $querystring = http_build_query($request_params);
  $url = $options['clever_oauth_authorize_url'] . '?' . $querystring;
  return $url;
}

function generate_sign_in_with_clever_link(array $options) {
  $html = "<a href='" . generate_sign_in_with_clever_url_curr($options) . "'><img src='http://assets.clever.com/sign-in-with-clever/sign-in-with-clever-small.png'/></a>";
  return $html;
}
*/
    
    
    
    
    
    
    
    
    
    /*
    $client_id = "e5da6ddd7da309c332b6";
    //$client_secret = "989c154e7c57a3cd06e5cd78814ed86e6b090dde";
    $client_secret = "d845cb31f01fb21d5e72d3a70641ed2e7c93863a";
    echo "<pre>";
    print_r($_POST);
    print_r($_GET);
    
    $redirect_url = "http://cg.curriki.org/curriki/?clever_login=true";
    $redirect_url_encoded = rawurlencode($redirect_url);
    $url = "https://clever.com/oauth/tokens?code=" . $_POST['code'] . "&grant_type=".$_POST['grant_type']."&redirect_uri=" . $redirect_url_encoded;
    ?>
<form method="POST" action="<?php echo $url;?>">
    <input name="code" value="<?php echo $_GET['code']?>" />
    <input name="grant_type" value="<?php echo $_GET['grant_type']?>" />
    <input name="redirect_uri" value="<?php echo $redirect_url_encoded?>" />
    <input name="basic_auth_header" value="Authorization: Basic <?php echo base64_encode($client_id.":". $client_secret)?>" />
    <input type="submit" />
</form>
    <?php
    $ch = curl_init();
    //$url = "https://clever.com/oauth/tokens?code=".$_GET['code']."&grant_type=authorization_code&redirect_uri=http%3A%2F%2Fcg.curriki.org%2Fcurriki%2F?clever_login=true";
    curl_setopt($s,CURLOPT_POST,true); 
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, TRUE);
    curl_setopt($ch, CURLOPT_NOBODY, TRUE); // remove body
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $head = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    print_r($head);
    curl_close($ch); 
    die;*/



function curriki_clever_authentication($email, $username, $password , $firstname , $middlename , $lastname ,$me){
    global $wpdb;
    
    $username = trim($username);
    $email = trim($email);
    
    if($password == '') // do encryption
        $password = 'currikiclever';
    
    
   
    $userid = 0;
    
    if( username_exists($username) === false )
    {

        $userid = register_new_user( $username, $email );                
        wp_set_password( $password, $userid );                
        $user_record = get_user_by("ID", $userid);                


        $userid = property_exists($user_record, "data") ? ( property_exists($user_record->data, "ID") ? $user_record->data->ID : 0 ) : 0;
        $userid = intval($userid);
            
        if($userid > 0)
        {
            
            $q_user = "SELECT userid FROM users where userid = ".$user_record->data->ID."";
            $user_id_exist = $wpdb->get_var($q_user);                 
            if($user_id_exist > 0)
            {
                $rd = $wpdb->delete( 'users', array( 'userid' => $user_record->data->ID ), array( '%d' ) );                
            }
            
            /*
            $r = $wpdb->insert('users', 
                                array(
                                  'userid' => $user_record->data->ID,
                                  'user_login'=>$user_record->data->user_login,
                                  'password' => $password,
                                  'registerdate' => $user_record->data->user_registered,
                                  'sitename' => 'curriki',
                                  'featured' => 'F'
                                ),
                                array('%d','%s','%s','%s','%s','%s')
                            );
             * 
             */
            $r = $wpdb->query( $wpdb->prepare("INSERT INTO users (userid, user_login, ifneeded, registerdate, sitename, featured)
                            VALUES (%d, %s, AES_ENCRYPT(%s, '".AES_KEY."'), %s, %s, %s) ",
                    $user_record->data->ID,
                    $user_record->data->user_login,
                    $password,
                    $user_record->data->user_registered,
                    'curriki',
                    'F'
                    )
            );
        }
                    
    }else{

        $user_record = get_user_by("login", $username);
        $userid = $user_record->data->ID;             
    }

    if($userid > 0)
    {
        $wpdb->update( 
                'users', 
                array( 
                    'firstname' => $firstname,
                    'lastname' => $lastname
                ), 
                array( 'userid' => $userid ), 
                array( '%s', '%s' ), 
                array( '%d' ) 
        );
        $wpdb->update( 
                'users', 
                array( 
                    'source' => 'clever'
                ), 
                array( 'userid' => $userid ), 
                array( '%s' ), 
                array( '%d' ) 
        );
        $creds = array();
        $creds['user_login'] = $username;
        $creds['user_password'] = $password;
        $user = wp_signon( $creds, false );
    }
              
    
    /*
    $q_user = "SELECT ID FROM cur_users where user_email = '".$email."'";
    $user_id = $wpdb->get_var($q_user);    
    
    if($user_id > 0){
        $wpdb->update( 
            'cur_users', 
            array( 
                'user_login' => $username,
                'user_pass' => md5($password)
            ), 
            array( 'ID' => $user_id ), 
            array( '%s', '%s' ), 
            array( '%d' ) 
        );
    }else{
        $userid = register_new_user( $username, $email );
        wp_set_password( $password, $userid );

            $wpdb->update( 
                    'users', 
                    array( 
                        'user_login' => $username,
                        'password' => $password
                    ), 
                    array( 'userid' => $userid ), 
                    array( '%s', '%s' ), 
                    array( '%d' ) 
            );
    }
    $wpdb->update( 
                    'users', 
                    array( 
                        'firstname' => $firstname,
                        'lastname' => $lastname
                    ), 
                    array( 'userid' => $userid ), 
                    array( '%s', '%s' ), 
                    array( '%d' ) 
            );
    $wpdb->update( 
                    'users', 
                    array( 
                        'source' => 'clever'
                    ), 
                    array( 'userid' => $userid ), 
                    array( '%s' ), 
                    array( '%d' ) 
            );
    $creds = array();
    $creds['user_login'] = $username;
    $creds['user_password'] = $password;
    $user = wp_signon( $creds, false );
    */
}