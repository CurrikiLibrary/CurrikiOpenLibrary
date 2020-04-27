<?php
/*
  Created on : Mar 21, 2016, 8:58:41 PM
  Author     : waqar-muneer
  Purpose    : to manage all advance analytics
 */
use Aws\Common\Aws;

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

  $user_meta=get_userdata($user->ID);

  $user_roles=$user_meta->roles;

    // if ( !in_array( 'administrator', (array) $user_roles ) ) {
    //     wp_logout();
    //     header(get_site_url());
    //     exit();
    // }
  header('HTTP/1.1 200 OK');
  if (!is_wp_error($user)) {
    fn_curriki_wp_login($user);
    

    setcookie('visit_ip', null, -1, SITECOOKIEPATH);
    setcookie('visit_counter', null, -1, SITECOOKIEPATH);
    if( isset($_SESSION["complete_porfile_displayed"]) )
        unset ( $_SESSION["complete_porfile_displayed"] );
    echo 'login-done';
    die;
  } else {
            die('testing...');
    $error = $user->get_error_message();    
    if (strstr($error, 'Invalid username', true)) {
      $creds['user_login'] = strtolower($_POST['log']);      
      $user = wp_signon($creds, false);
      header('HTTP/1.1 200 OK');
      if (!is_wp_error($user)) {
        fn_curriki_wp_login($user);
        setcookie('visit_ip', null, -1, SITECOOKIEPATH);
        setcookie('visit_counter', null, -1, SITECOOKIEPATH);
        if( isset($_SESSION["complete_porfile_displayed"]) )
            unset ( $_SESSION["complete_porfile_displayed"] );
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
  if (isset($_POST['gdpr_store_info']) && $_POST['gdpr_store_info'] == "false")
    $errors[] = "\"You need to accept Store my Personal Information\"";
  if (empty( trim($_POST['firstname']) ))
    $errors[] = "\"First Name\" is required.";
  if (empty( trim($_POST['lastname']) ))
    $errors[] = "\"Last Name\" is required.";      
  
  if ( empty( trim($_POST['username']) ) )
  {
    $errors[] = "\"Username\" is required.";  
  }
  $username_error = false;
  //check white space
  if( !empty( trim($_POST['username']) ) && preg_match('/\s/', trim($_POST['username']) ) > 0 )
  {      
      $errors[] = "Invalid \"Username\" with space(s)";
  }  
  //check all lower case
  /*if( !empty( trim($_POST['username']) ) && $_POST['username'] !== strtolower($_POST['username']) )
  {      
      $errors[] = "\"Username\" must be in lower case characters";
  }*/
  
  if (empty( trim($_POST['email']) ))
    $errors[] = "\"Email\" is required.";
  if (empty( $_POST['pwd'] ))
    $errors[] = "\"Password\" is required.";
  elseif (strlen( $_POST['pwd'] ) < 6)
    $errors[] = "\"Password\" should be at least 6 characters long.";
  elseif (substr_count( $_POST['pwd'] , ' ') > 0)
    $errors[] = "\"Password\" should not contain spaces.";
  if (empty( $_POST['confirm_pwd'] ))
    $errors[] = "\"Confirm Password\" is required.";
  if (username_exists( trim($_POST['username']) ))
    $errors[] = "\"Username\" already exists.";
  if (email_exists( trim($_POST['email']) ))
    $errors[] = "\"Email\" already exists.";
  if ($_POST['pwd'] != $_POST['confirm_pwd'] )
    $errors[] = "\"Password\" and \"Confirm Password\" dont match.";
  //if(empty( $_POST['accept'] ))
  //    $errors[] = "Accepting terms and policy is required.";

  if (isset($_POST["email"]) && strlen($_POST["email"]) > 0 && !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format";
  }
  
  if (!empty($_POST['zipcode'])) {
    $zip = $_POST['zipcode'];
    if (strlen($zip) <= 50) {
      //valid            
    } else {
      //invalid            
      $errors[] = "Enter valid Zip/Postal code.";
    }
  }

  if (empty($errors)) {
    global $wpdb;
    
    $userid = register_new_user(trim($_POST['username']), trim($_POST['email']));    
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
//    $wpdb->show_errors();
    $wpdb->query( $wpdb->prepare("UPDATE users
                    SET
                    firstname = %s,
                    lastname = %s,
                    user_login = %s,
                    ifneeded = AES_ENCRYPT(%s, '".AES_KEY."'), 
                    indexrequired = 'T',
                    indexrequireddate = %s
                    where userid = %d",
            trim($_POST['firstname']),
            trim($_POST['lastname']),
            trim($_POST['username']),
            $_POST['pwd'],
            date('Y-m-d H:i:s'),
            $userid
            )
    );
//    echo $wpdb->last_query;
//    echo $wpdb->last_error;
//    die();
//    $wpdb->update(
//            'users', array(
//        'firstname' => trim($_POST['firstname']),
//        'lastname' => trim($_POST['lastname']),
//        'user_login' => trim($_POST['username']),
//        'password' => $_POST['pwd'],
//        'indexrequired' => 'T',
//        'indexrequireddate' => date('Y-m-d H:i:s')
//            ), array('userid' => $userid), array('%s', '%s', '%s', '%s'), array('%d')
//    );
    $wpdb->update(
            'users', array(
        'country' => $_POST['country'],
        'membertype' => $_POST['member_type'],
        'state' => $_POST['state'],
        'city' => trim($_POST['city']),
        'postalcode' => trim($_POST['zipcode']),
        'school' => trim($_POST['school'])
            ), array('userid' => $userid), array('%s', '%s', '%s', '%s' , '%s'), array('%d')
    );
    //echo $wpdb->last_query;
    $profile_meta = array(
        "gender" => $_POST["gender"]
    );
    add_user_meta($userid, "profile", json_encode($profile_meta));

    $creds = array();
    $creds['user_login'] = trim($_POST['username']);
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


add_action( 'wp_ajax_nopriv_cur_ajax_survey_modal', 'cur_ajax_survey_modal' ); 
add_action( 'wp_ajax_cur_ajax_survey_modal', 'cur_ajax_survey_modal'); 

function cur_ajax_survey_modal() {
    $result = new stdClass();
    $result->current_url = $_POST["currentUrl"];    
    
    $current_url_arr = array();
    if(isset($_COOKIE["current_url_arr"])) 
    {
         $current_url_ck = $_COOKIE["current_url_arr"];
         $current_url_arr_strip = stripslashes($current_url_ck);
         $current_url_arr = json_decode($current_url_arr_strip);
         
         //var_dump($current_url_arr);die;
         
        if(!in_array($result->current_url, $current_url_arr) )
        {
            //$current_url_arr[""];
            $current_url_arr[] = $result->current_url;
            $secure = ( 'https' === parse_url(site_url(), PHP_URL_SCHEME) );    
            setcookie('current_url_arr', json_encode($current_url_arr) ,0, SITECOOKIEPATH, null, $secure);
            //var_dump( $_COOKIE["current_url_arr"] );
            //die;
        }else{
            //echo "link already set... ";
            //var_dump($current_url_arr);die;
        }
        
    }else{
        
        echo "in the ELSE ...";
        $current_url_arr[] = $result->current_url;
        $secure = ( 'https' === parse_url(site_url(), PHP_URL_SCHEME) );    
        setcookie('current_url_arr', json_encode($current_url_arr) ,0, SITECOOKIEPATH, null, $secure);        
        //var_dump( $_COOKIE["current_url_arr"] );
        //die;
    }
    
    
    //$result->current_url_arr = $current_url_arr;    
    echo json_encode($result);    
    die();
}


add_action( 'wp_ajax_nopriv_cur_ajax_profile_complete_modal', 'cur_ajax_profile_complete_modal' ); 
add_action( 'wp_ajax_cur_ajax_profile_complete_modal', 'cur_ajax_profile_complete_modal'); 

function cur_ajax_profile_complete_modal() {
        
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        
        $current_language = "eng";
        if( defined('ICL_LANGUAGE_CODE') )
            $current_language = cur_get_current_language(ICL_LANGUAGE_CODE); 
               
        $errors = array();
        global $wpdb;
        $my_id = get_current_user_id();
        
    
        $user_table_fields = array(            
            'city' => $_POST['city'],            
            'country' => $_POST['country'],
            'indexrequired' => 'T',
            'indexrequireddate' => date('Y-m-d H:i:s'),            
        );
        
        if( isset($_POST['membertype']) )
        {
            $user_table_fields["membertype"] = $_POST['membertype'];
        }        
        if( isset($_POST['firstname']) )
        {
            $user_table_fields["firstname"] = $_POST['firstname'];
        }        
        if( isset($_POST['lastname']) )
        {
            $user_table_fields["lastname"] = $_POST['lastname'];
        }        
        
        $ref_arr = array('%s','%s', '%s', '%s', '%s');
        if(isset($_POST['state']) && $user_table_fields["country"] === "US")
        {
            $user_table_fields["state"] = $_POST['state'];
            $ref_arr[] = '%s';
        }        
        if(isset($user_table_fields["membertype"]) && $user_table_fields["membertype"] === "teacher")
        {
            $user_table_fields["school"] = $_POST['school']; 
            $ref_arr[] = '%s';
        }
        
        $wpdb->update(
            'users', $user_table_fields , array('userid' => $my_id), $ref_arr , array('%d')
        );
        
        
        if( isset($user_table_fields["membertype"]) && $user_table_fields["membertype"] === "teacher" && !empty($_POST['subjectarea']) )
        {
            foreach ($_POST['subjectarea'] as $sa) {
                $wpdb->query($wpdb->prepare(
                                "
                                INSERT INTO user_subjectareas
                                ( userid, subjectareaid )
                                VALUES ( %d, %d )
                        ", $my_id, $sa
                ));
            }
        }
        
        if(isset($user_table_fields["membertype"]) && $user_table_fields["membertype"] === "teacher" && !empty($_POST['educationlevel']) )
        {            
            $wpdb->delete('user_educationlevels', array('userid' => $my_id), array('%d'));
             if(!empty($_POST['educationlevel']))
             {
                foreach ($_POST['educationlevel'] as $el) {
                    $wpdb->query($wpdb->prepare(
                                    "
                                    INSERT INTO user_educationlevels
                                    ( userid, educationlevelid )
                                    VALUES ( %d, %d )
                            ", $my_id, $el
                    ));
                }
             }
        }
        
                
        if(isset($_POST["gender"]))
        {
            $profile = get_user_meta(get_current_user_id(),"profile",true);    
            $profile = isset($profile) ? json_decode($profile) : null;            
            
            if(isset($profile))
            {
                $profile->gender = $_POST["gender"];
                update_user_meta(get_current_user_id(), "profile", json_encode($profile));
            }else{
                $profile = new stdClass();
                $profile->gender = $_POST["gender"];
                add_user_meta(get_current_user_id(), "profile", json_encode($profile));
            }
        }
        
        
        if (isset($_FILES['my_photo']) && $_FILES['my_photo']['tmp_name']) {
            
            
            $upload_folder = '/uploads/tmp/';
            $MaxSizeUpload = 5242880; //Bytes

            $sub_dir = dirname( strtok( $_SERVER['REQUEST_URI'] , '?') );                        
            
            if( $current_language != "eng" )
            {
                $sub_dir = dirname( strtok( $sub_dir , 'eng') );                
            }
            $sub_dir = str_replace('wp-admin', '', $sub_dir);                                    
            $wp_contents = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'] . $sub_dir . '/wp-content');                                    
            
            require_once($wp_contents . '/libs/aws_sdk/aws-autoloader.php');

            $base_url = ($_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $sub_dir . $upload_folder;
            $current_path = $wp_contents . $upload_folder; // relative path from filemanager folder to upload files folder
            //**********************
            //Allowed extensions
            //**********************

            $ext_img = array('jpg', 'jpeg', 'pjpeg', 'png', 'gif', 'bmp', 'tiff', 'tif'); //Images
            $ext_video = array('mov', 'mpeg', 'mp4', 'avi', 'mpg', 'wma', 'flv', 'wmv'); //Videos
            //$ext_file = array('doc', 'docx', 'pdf', 'xls', 'xlsx', 'txt', 'csv', 'html', 'psd', 'sql', 'log', 'fla', 'xml', 'ade', 'adp', 'ppt', 'pptx'); //Files
            //$ext_music = array('mp3', 'm4a', 'ac3', 'aiff', 'mid'); //Music
            //$ext_misc = array('zip', 'rar', 'gzip'); //Archives
            //$ext = array_merge($ext_img, $ext_file, $ext_misc, $ext_video, $ext_music); //allowed extensions

            $ds = DIRECTORY_SEPARATOR;
                        
            
            $aws = Aws::factory($wp_contents . '/libs/aws_sdk/config.php');
            $s3_client = $aws->get('S3');           
            $bucket = 'currikicdn';
            $ext = pathinfo($_FILES['my_photo']['name'], PATHINFO_EXTENSION);
            $name = preg_replace("/[^a-zA-Z0-9_]+/", "", str_replace(" ", '_', pathinfo($_FILES['my_photo']['name'], PATHINFO_FILENAME))) . time() . rand();
            $tempFile = $_FILES['my_photo']['tmp_name'];
            $targetFile = $current_path . $name . '.' . $ext;                        
            if(!move_uploaded_file($_FILES['my_photo']['tmp_name'], $targetFile))
            {
                $errors[]="Please upload picture with valid size and format. Try again!";
            }

            if (file_exists($targetFile)) {
                $pic = uniqid();
                $upload = $s3_client->putObject(array(
                            'ACL' => 'public-read',
                            'Bucket' => $bucket,
                            'Key' => 'avatars/' . $pic . '.' . $ext,
                            'Body' => fopen($targetFile, 'r+')
                        ))->toArray();
                
                $wpdb->update(
                    'users', array(
                        'uniqueavatarfile' => $pic . '.' . $ext,
                    ), array('userid' => $my_id), array('%s'), array('%d')
                );
            }
        }
        
        //$errors[] = "Test Error";        
        $success = array("Thank You!");
        
        $output = new stdClass();
        $output->errors = $errors;
        $output->success = $success;
        
        if( count($errors)===0 && count($success)>0 )
        {
            $_SESSION["complete_porfile_displayed"] = true;
        }
        
        echo json_encode($output);
        die();
}

//add_filter( 'wpml_hreflangs', 'cur_wpml_hreflangs',10,1 );
function cur_wpml_hreflangs($hreflang_items) {
    //****** Bulding hreflangs urls for resources ******
    global $post,$bp;        
    if(is_array($bp->unfiltered_uri) && count($bp->unfiltered_uri[0]) > 0 && $bp->unfiltered_uri[0] == "oer")
    {
         if (isset($_GET['rid']) || isset($_GET['pageurl'])) {
            foreach ( $hreflang_items as $hreflang_code => $hreflang_url ) {
                $res = new CurrikiResources();
                $resourceUser = $res->getResourceUserById((int) $_GET['rid'], rtrim($_GET['pageurl'], '/'));                                
                $hreflang_url .= $resourceUser["pageurl"];            
                $hreflang_items[$hreflang_code] = $hreflang_url;
            }
          }
    }
    
    return $hreflang_items;
}

add_filter( 'comments_open', 'cur_dummy_comments_template',101,1 );
function cur_dummy_comments_template($post_id) {                        
    return false;
}
function wpd_do_stuff_on_404(){
    if( is_404() ){        
        // do stuff
        echo "<h1>Error: 404 - Page Not Found</h1>";        
        die();
    }
}
//add_action( 'template_redirect', 'wpd_do_stuff_on_404' );


require_once 'includes/bbpress-wp4-fix.php';



// block WP enum scans
if (!is_admin()) {
	// default URL format
	if (preg_match('/author=([0-9]*)/i', $_SERVER['QUERY_STRING'])) die("block WP enum scans");
	add_filter('redirect_canonical', 'shapeSpace_check_enum', 10, 2);
}
function shapeSpace_check_enum($redirect, $request) {
	// permalink URL format
	if (preg_match('/\?author=([0-9]*)(\/*)/i', $request)) die();
	else return $redirect;
}

add_action('admin_head', 'curr_admin_head');

function curr_admin_head() {
  echo '<style>
    #the-list .username img{
      width:100px !important;
    } 
  </style>';
}

function redirect_member_with_email_to_its_slug(){
    global $bp , $wpdb;        
    if( is_object($bp) && property_exists($bp, "unfiltered_uri") && is_array($bp->unfiltered_uri) && in_array("members", $bp->unfiltered_uri) && count($bp->unfiltered_uri) >= 2 ){
        
        $index = array_search("members", $bp->unfiltered_uri);        
        $member_slug_index = $index + 1;
        $member_given_slug = urldecode($bp->unfiltered_uri[$member_slug_index]);        
        
        $user = $wpdb->get_row("select * from cur_users where user_login = '{$member_given_slug}'");        
        //if user found by login with this current slug                        
        if( $user && $user->user_login !== $user->user_nicename ){
            $bp->unfiltered_uri[$member_slug_index] = $user->user_nicename;
            $location = home_url( implode("/", $bp->unfiltered_uri) );            
            wp_redirect($location);
        }
    }
}
add_action( 'template_redirect', 'redirect_member_with_email_to_its_slug' ,101,1 );


if( file_exists(get_stylesheet_directory()."/modules/resource/functions/index.php") ){    
    require_once get_stylesheet_directory()."/modules/resource/functions/index.php";
}

add_action('wp_ajax_nopriv_cur_ajax_curriki_demo', 'cur_ajax_curriki_demo');
add_action('wp_ajax_cur_ajax_curriki_demo', 'cur_ajax_curriki_demo');

function cur_ajax_curriki_demo()
{
  global $wpdb;

  if (empty($_POST['name'])) {
    echo __("Name is required.", 'curriki');
    die;
  } else if (empty($_POST['email'])) {
    echo __("Email is required.", 'curriki');
    die;
  } else if (empty($_POST['phone'])) {
    echo __("Phone is required.", 'curriki');
    die;
  }

  $source = isset($_POST['source']) ? $_POST['source'] : 'demo';
  $organization = isset($_POST['organization']) ? '<p>Organization: '.$_POST['organization'].'</p>'  : '';
  $description = isset($_POST['description']) ? '<p>Description: '.$_POST['description'].'</p>'  : '';

  $wpdb->query(
    $wpdb->prepare(
      "
      INSERT INTO demo_requests
      ( name, email, phone, organization, description, source)
      VALUES ( %s, %s, %s, %s, %s, %s )
    ",
      $_POST['name'],
      $_POST['email'],
      $_POST['phone'],
      isset($_POST['organization']) ? $_POST['organization'] : null,
      isset($_POST['description']) ? $_POST['description'] : null,
      $source
    )
  );

  $home_url = home_url( '/' );
  $subject = 'New '.ucfirst($source).' Request - ' . $_POST['name'];
  $body = <<<EOD
  <p>Hi,</p><p>There is a new {$source} request by following user.</p>
  <p>Name: {$_POST['name']}</p>
  <p>E-mail: {$_POST['email']}</p>
  <p>Phone No.: {$_POST['phone']}</p>
  {$organization}
  {$description}
  <p>You can view {$source} request listing at : <a href='{$home_url}wp-admin/admin.php?page=demo_requests' target='_blank'>Here</a></p>
  <p>Thanks!</p>
EOD;

  $headers = array('Content-Type: text/html; charset=UTF-8');

  if($source == 'create') {
    wp_mail('create@curriki.org', $subject, $body, $headers);
  } else {
    wp_mail('jpinto@curriki.org', $subject, $body, $headers);
    wp_mail('webmaster@curriki.org', $subject, $body, $headers);
  }

  echo 'demo-done';
  die;
}

add_action('wp_ajax_nopriv_cur_ajax_curriki_donation_checkout', 'cur_ajax_curriki_donation_checkout');
add_action('wp_ajax_cur_ajax_curriki_donation_checkout', 'cur_ajax_curriki_donation_checkout');

function cur_ajax_curriki_donation_checkout()
{
  if (empty($_POST['amount'])) {
    http_response_code(400);
    echo json_encode([ 'error' => 'Amount is required.' ]);
    exit;
  } elseif (empty($_POST['email'])) {
    http_response_code(400);
    echo json_encode([ 'error' => 'Email is required.' ]);
    exit;
  }

  require_once get_stylesheet_directory()."/lib/stripe-php-7.5.0/init.php";

  $siteUrl = get_site_url();
  $currency = STRIPE_CURRENCY;
  $amount = $_POST['amount'] * 100;
  $email = $_POST['email'];
  $siteUrl .= $_POST['url'];

  \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

  // Create new Checkout Session for the order
  // Other optional params include:
  // [billing_address_collection] - to display billing address details on the page
  // [customer] - if you have an existing Stripe Customer ID
  // [payment_intent_data] - lets capture the payment later
  // [customer_email] - lets you prefill the email input in the form
  // For full details see https://stripe.com/docs/api/checkout/sessions/create

  // ?session_id={CHECKOUT_SESSION_ID} means the redirect will have the session ID set as a query param
  $checkout_session = \Stripe\Checkout\Session::create([
    'success_url' => $siteUrl . '?status=success&session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => $siteUrl . '?status=cancel',
    'payment_method_types' => ['card'],
    'customer_email' => $email,
    'line_items' => [[
      'name' => 'Curriki Donation Pledge - Stripe Payment',
      'quantity' => 1,
      'amount' => $amount,
      'currency' => $currency,
      'description' => 'Flat donation to Curriki'
    ]],
    'payment_intent_data' => [
      'metadata' => [
        'curriki_flat_donation' => true
      ]
    ]
  ]);

  echo json_encode(['sessionId' => $checkout_session['id']]);
  die;
}

add_action('wp_ajax_nopriv_cur_ajax_curriki_donation_checkout_session', 'cur_ajax_curriki_donation_checkout_session');
add_action('wp_ajax_cur_ajax_curriki_donation_checkout_session', 'cur_ajax_curriki_donation_checkout_session');

function cur_ajax_curriki_donation_checkout_session()
{
  if (empty($_GET['sessionId'])) {
    http_response_code(400);
    echo json_encode([ 'error' => 'Session Id is required.' ]);
    exit;
  }

  require_once get_stylesheet_directory()."/lib/stripe-php-7.5.0/init.php";

  \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

  // Fetch the Checkout Session to display the JSON result on the success page
  $id = $_GET['sessionId'];
  $checkout_session = \Stripe\Checkout\Session::retrieve($id);
  echo json_encode($checkout_session);
  die;
}