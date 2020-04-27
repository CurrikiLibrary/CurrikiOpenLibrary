<?php
/*
  Created on : Mar 21, 2016, 8:58:41 PM
  Author     : waqar-muneer
  Purpose    : to manage all advance analytics
 */
add_action( 'wp_ajax_nopriv_cur_ajax_curriki_login', 'cur_ajax_curriki_login' ); 
add_action( 'wp_ajax_cur_ajax_curriki_login', 'cur_ajax_curriki_login'); 

function cur_ajax_curriki_login() {
    
  if (empty($_POST['log'])) 
  {
    echo __("User Login is required.",'curriki');
    die;
  }
  $creds = array();
  $creds['user_login'] = $_POST['log'];
  $creds['user_password'] = $_POST['pwd'];
  //$creds['remember'] = $_POST['rememberme'];
  remove_action('wp_login_failed', '');    
  
  $user = wp_signon($creds, false);
  header('HTTP/1.1 200 OK');
  if (!is_wp_error($user)) {      
    fn_curriki_wp_login($user);

    setcookie('visit_ip', null, -1, SITECOOKIEPATH);
    setcookie('visit_counter', null, -1, SITECOOKIEPATH);
    echo 'login-done';
    die;
  } else {
            
    $error = $user->get_error_message();    
    if (strstr($error, 'Invalid username', true)) {
      $creds['user_login'] = strtolower($_POST['log']);      
      $user = wp_signon($creds, false);
      header('HTTP/1.1 200 OK');
      if (!is_wp_error($user)) {
        fn_curriki_wp_login($user);
        setcookie('visit_ip', null, -1, SITECOOKIEPATH);
        setcookie('visit_counter', null, -1, SITECOOKIEPATH);
        echo 'login-done';
        die;
      }
      $error = $user->get_error_message();
      if (strstr($error, 'The password you entered for the username', true)) {          
        echo __("Password is incorrect.","curriki");
      } else {
        echo __("Username not found.",'curriki');
      }
    } elseif (strstr($error, 'The password you entered for the username', true)) {
      echo __("Password is incorrect.",'curriki');
    }
    /* if (SAVEQUERIES and $_GET['test']) {
      global $wpdb;
      echo "<!--\n";
      print_r($wpdb->queries);
      echo "\n-->\n";
      } */
    die;
  }
  die;
}


add_action( 'wp_ajax_nopriv_cur_ajax_curriki_signup', 'cur_ajax_curriki_signup' ); 
add_action( 'wp_ajax_cur_ajax_curriki_signup', 'cur_ajax_curriki_signup'); 

function cur_ajax_curriki_signup() {
    
  $dashboard_page = 6015;
  if (empty($_POST['firstname']))
    $errors[] = "\"First Name\" is required.";
  if (empty($_POST['lastname']))
    $errors[] = "\"Last Name\" is required.";
  if (empty($_POST['username']))
    $errors[] = "Username is required.";
  if (!preg_match('/^[a-zA-Z0-9,. ]*$/', $_POST['username']))
    $errors[] = "Username should not have special characters.";
  if (empty($_POST['email']))
    $errors[] = "Email is required.";
  if (empty($_POST['pwd']))
    $errors[] = "Password is required.";
  elseif (strlen($_POST['pwd']) < 6)
    $errors[] = "Password should be at least 6 characters long.";
  elseif (substr_count($_POST['pwd'], ' ') > 0)
    $errors[] = "Password should not contain spaces.";
  if (empty($_POST['confirm_pwd']))
    $errors[] = "Confirm Password is required.";
  if (username_exists($_POST['username']))
    $errors[] = "Username already exists.";
  if (email_exists($_POST['email']))
    $errors[] = "Email already exists.";
  if ($_POST['pwd'] != $_POST['confirm_pwd'])
    $errors[] = "Password and Confirm Password dont match.";
  //if(empty( $_POST['accept'] ))
  //    $errors[] = "Accepting terms and policy is required.";

  if (isset($_POST["email"]) && strlen($_POST["email"]) > 0 && !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format";
  }

  if (!empty($_POST['zipcode'])) {
    $zip = $_POST['zipcode'];
    if (strlen($zip) <= 6 && ctype_digit($zip)) {
      //valid            
    } else {
      //invalid            
      $errors[] = "Enter valid Zip/Postal code.";
    }
  }

  if (empty($errors)) {

    $userid = register_new_user($_POST['username'], $_POST['email']);
    wp_set_password($_POST['pwd'], $userid);
    //update_user_meta( $userid, "country", $_POST['country'] );
    //update_user_meta( $userid, "member_type", $_POST['member_type'] );

    global $wpdb;

    $q_newuserid = "select userid from users where userid = '" . $userid . "'";
    $newuserid = $wpdb->get_var($q_newuserid);
    if (!$newuserid > 0) {
      $user_record = get_user_by("ID", $userid);            
      $wpdb->insert('users', 
                        array(
                              'userid' => $userid,'user_login'=>$user_record->data->user_login,
                              'registerdate' => $user_record->data->user_registered,
                              'sitename' => 'curriki',
                              'featured' => 'F'
                              ),
                    array('%d','%s','%s','%s'));            
    }
    
    $wpdb->update(
            'users', array(
        'firstname' => $_POST['firstname'],
        'lastname' => $_POST['lastname'],
        'user_login' => $_POST['username'],
        'password' => $_POST['pwd'],
        'indexrequired' => 'T',
        'indexrequireddate' => date('Y-m-d H:i:s')
            ), array('userid' => $userid), array('%s', '%s', '%s', '%s'), array('%d')
    );
    $wpdb->update(
            'users', array(
        'country' => $_POST['country'],
        'membertype' => $_POST['member_type'],
        'state' => $_POST['state'],
        'city' => $_POST['city'],
        'postalcode' => $_POST['zipcode'],
            ), array('userid' => $userid), array('%s', '%s', '%s', '%s'), array('%d')
    );
    //echo $wpdb->last_query;
    $profile_meta = array(
        "gender" => $_POST["gender"]
    );
    add_user_meta($userid, "profile", json_encode($profile_meta));

    $creds = array();
    $creds['user_login'] = $_POST['username'];
    $creds['user_password'] = $_POST['pwd'];
    $user = wp_signon($creds, false);    
    //curriki_signup_mail($_POST['email']);
    //add_filter('wp_mail', 'disable_email_filter');    
    if (!is_wp_error($user)) 
    {              
        fn_curriki_wp_login($user);
    }
    echo "1";
    die;
  } else {      
    foreach ($errors as $error) {
      echo __($error,'curriki') . '<br />';
    }
    die;
  }
}

//add_action("groups_creation_tabs","cur_groups_creation_tabs");

//bbp_get_forum_title
add_filter( 'bbp_get_forum_title', 'cur_bbp_get_forum_title', 99, 2 );
function cur_bbp_get_forum_title($arg1, $arg2)
{    
    $title = $f_title;
    if( bp_current_component() === 'groups' )
        $title = "";

    return $title;
}
require_once 'includes/bbpress-wp4-fix.php';