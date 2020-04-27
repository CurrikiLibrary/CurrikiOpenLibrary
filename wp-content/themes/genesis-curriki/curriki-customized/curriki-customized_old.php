<?php
/*
  Description: All curriki common functions are added to this plugin.
  Author: sajidpersonal@hotmail.com
 */
@include("curriki_resources.php");
@include("curriki_clever.php");
@include("search.php");
@include("helpers/translation-functions.php");
@include("helpers/common-functions.php");

function cur_set_global_vars() 
{    
    global $resourceUserGlobal;        
    $pagename = get_query_var('pagename'); 
    if( $pagename === "oer")
    {                        
        $res = new CurrikiResources();
        $resourceUserGlobal = $res->getResourceUserById((int) $_GET['rid'], rtrim($_GET['pageurl'], '/'));       
        if($_GET['testali']){
            print_r($resourceUserGlobal);
            die();
        }
    }        
    
}
add_action( 'parse_query', 'cur_set_global_vars' , 10 );


//wp_enqueue_style('curriki-new-styles', get_stylesheet_directory_uri() . '/new_styles.css');
wp_enqueue_style('curriki-custom-style-alpha', get_stylesheet_directory_uri() . '/curriki-customized/css/curriki-custom-style-alpha.css');
wp_enqueue_script('curriki-custom-script-alpha', get_stylesheet_directory_uri() . '/curriki-customized/js/curriki-custom-script-alpha.js', array(), '1.0.0', true);

wp_enqueue_style('curriki-tooltip', get_stylesheet_directory_uri() . '/curriki-customized/css/jquery.tooltip.css');
wp_enqueue_script('curriki-tooltip', get_stylesheet_directory_uri() . '/curriki-customized/js/jquery.tooltip.min.js', array(), '1.0.0', true);

wp_enqueue_style('curriki-custom-style', get_stylesheet_directory_uri() . '/css/curriki-custom-style.css');

wp_enqueue_script('ext-base-js', get_stylesheet_directory_uri() . '/js/xwiki/ext-base.js', array(), '1.0.0', true);
//wp_enqueue_script( 'ext-all-debug-js', get_stylesheet_directory_uri() . '/js/xwiki/ext-all-debug.js', array(), '1.0.0', true );
wp_enqueue_script('curriki-ext-js', get_stylesheet_directory_uri() . '/js/xwiki/curriki-ext.js', array(), '1.0.0', true);

//wp_enqueue_script( 'curriki-main-debug-js', get_stylesheet_directory_uri() . '/js/xwiki/curriki-main-debug.js', array(), '1.0.0', true );
wp_enqueue_script('xwiki-js', get_stylesheet_directory_uri() . '/js/xwiki/xwiki.js', array(), '1.0.0', true);


add_action('genesis_meta', 'curriki_head_baseurl');

function curriki_head_baseurl() {
  echo '<script type="text/javascript">var base_url = "' . get_bloginfo('url') . '";</script>';
}

add_filter('wp_nav_menu_items', 'your_custom_menu_item', 10, 2);
function your_custom_menu_item($items, $args) {
  if ($args->theme_location == 'primary') {
    global $wpdb;
    $q_userinfo = "select * from cur_users where ID = '" . get_current_user_id() . "'";
    $userinfo = $wpdb->get_row($q_userinfo);
    if (is_object($userinfo))
      $items = str_replace("members", "members/" . $userinfo->user_nicename . "/following", $items);
  }
  return $items;
}
  
class CurrikiCountVisitorsWidget extends WP_Widget {

  function CurrikiCountVisitorsWidget() {
    // Instantiate the parent object
    parent::__construct(false, 'Count Visitors Widget');
  }

  function widget($args, $instance) {
    // Widget output
    global $wpdb;
    $q = "SELECT visitors FROM sites WHERE sitename = 'curriki'";
    $v = $wpdb->get_var($q);
    echo number_format($v, '0', '.', ',');
    //echo '100000';
  }

  function update($new_instance, $old_instance) {
    // Save widget options
  }

  function form($instance) {
    // Output admin widget options form
  }

}

function curriki_register_widgets() {
  register_widget('CurrikiCountVisitorsWidget');
}

function getCurrikiStats($field) {
  global $wpdb;
  $q = "SELECT $field FROM sites WHERE sitename = 'curriki'";
  $v = $wpdb->get_var($q);
  return number_format($v, '0', '.', ',');
}

add_action('widgets_init', 'curriki_register_widgets');

function curriki_custom_login() {
  if (empty($_POST['log']))
    return;
  $dashboard_page = 6015;
  $creds = array();
  $creds['user_login'] = $_POST['log'];
  $creds['user_password'] = $_POST['pwd'];
  $creds['remember'] = $_POST['rememberme'];
  $user = wp_signon($creds, false);
  if (!is_wp_error($user)) {
    curriki_redirect(get_permalink($dashboard_page));
    return "Please wait while we redirect you to dashboard.";
  } else {

    //echo $user->get_error_message();
    //die;
    curriki_user_login_screen($user->get_error_message());
  }
}

// run it before the headers and cookies are sent
add_action('init', 'curriki_custom_login');

function curriki_user_login_screen($error = '') {
  $dashboard_page = 6015;
  if (is_user_logged_in()) {
    $redirect_url = get_permalink($dashboard_page);
    curriki_redirect($redirect_url);
    return "You are already Logged in!";
  } else {
    ob_start();
    @include("curriki_user_login_screen.php");
    $login_screen = ob_get_contents();
    ob_end_clean();
    return $login_screen;
  }
}

add_shortcode("user_login_screen", "curriki_user_login_screen");

function fn_curriki_forgot_password() {
  /* if($_POST['reset_email']){
    global $wpdb;
    $q = $wpdb->prepare( "SELECT * FROM $wpdb->users WHERE user_email = %s ", $_POST['reset_email']);
    $user = $wpdb->get_row($q);
    //print_r($user);
    if(is_object($user)){
    //$user->user_email = "sajid_154@hotmail.com";
    $reset = md5($user->user_email);
    update_user_meta($user->ID, "forgot_password_value", $reset);
    ob_start();
    @include("password_reset_mail_content.php");
    echo "Your username is: ".$user->user_name."<br />Please click <a href='".get_bloginfo('url')."/reset-password?reset=".$reset."'>here</a> to reset your password.";
    $password_reset_mail_content = ob_get_contents();
    ob_end_clean();
    wp_mail("sajidpersonal@hotmail.com", "Password Reset", 'test password reset');
    wp_mail($user->user_email, "Password Reset", $password_reset_mail_content);
    wp_mail("sajidpersonal@hotmail.com", "Password Reset", $password_reset_mail_content);
    $reset_screen = "Please check your email address and follow instructions to reset password.";
    //return $password_reset_mail_content;
    }
    else{
    $reset_screen = "We don't have this email address registered!";
    }
    }else{
    ob_start();
    @include("curriki_forgot_password.php");
    $reset_screen = ob_get_contents();
    ob_end_clean();
    }
    return $reset_screen; */
}

if (!empty($_GET['curriki_ajax_action']) and $_GET['curriki_ajax_action'] == 'reset_email') {
  if ($_POST['reset_email']) {
    global $wpdb;
    $q = $wpdb->prepare("SELECT * FROM $wpdb->users WHERE user_email = %s ", $_POST['reset_email']);
    $user = $wpdb->get_row($q);
    if (is_object($user)) {
      //$user->user_email = "sajid_154@hotmail.com";
      $reset = md5($user->user_email);
      update_user_meta($user->ID, "forgot_password_value", $reset);
      ob_start();
      @include("password_reset_mail_content.php");
      echo "Your username is: " . $user->user_login . ". Please click this link " . get_bloginfo('url') . "/reset-password?reset=" . $reset . " to reset your password.";
      $password_reset_mail_content = ob_get_contents();
      ob_end_clean();
      wp_mail($user->user_email, "Password Reset", $password_reset_mail_content);
      //wp_mail("sajidpersonal@hotmail.com", "Password Reset", $password_reset_mail_content);
      $reset_screen = "1";
    } else {
      $reset_screen = __("We don't have this email address registered!","curriki");
    }
  } else {
    $reset_screen = __("Please enter your email address below.","curriki");
  }
  echo $reset_screen;
  die;
}

add_shortcode("curriki_forgot_password", "fn_curriki_forgot_password");

function fn_curriki_reset_password() {    
    
  global $wpdb;
  $q = $wpdb->prepare("SELECT * FROM $wpdb->usermeta WHERE meta_value = %s ", $_GET['reset']);
  $user = $wpdb->get_row($q);
  if (!is_object($user)) {
    return;
  } else {
    if ($_POST['pwd']) {
      if ($_POST['pwd'] == $_POST['confirm_pwd']) {
        wp_set_password($_POST['pwd'], $user->user_id);
        delete_user_meta($user->user_id, "forgot_password_value", $_GET['reset']);
        return "Password changed.";
      } else {
        ob_start();
        echo "Password and confirm Password don't match";
        @include("curriki_reset_password.php");
        $screen = ob_get_contents();
        ob_end_clean();
        return $screen;
      }
    }
    ob_start();
    @include("curriki_reset_password.php");
    $screen = ob_get_contents();
    ob_end_clean();
    return $screen;
  }
}

add_shortcode("curriki_reset_password", "fn_curriki_reset_password");

function curriki_custom_signup() {
  if (empty($_POST['signup']))
    return;
  $dashboard_page = 6015;
  if (empty($_POST['username']))
    $errors[] = "Username is required.";
  if (empty($_POST['email']))
    $errors[] = "Email is required.";
  if (empty($_POST['pwd']))
    $errors[] = "Password is required.";
  if (empty($_POST['confirm_pwd']))
    $errors[] = "Confirm Password is required.";
  if (username_exists($_POST['username']))
    $errors[] = "Username already exists.";
  if (email_exists($_POST['email']))
    $errors[] = "Email already exists.";
  if ($_POST['pwd'] != $_POST['confirm_pwd'])
    $errors[] = "Password and Confirm Password dont match.";


  if (empty($errors)) {
    $userid = register_new_user($_POST['username'], $_POST['email']);
    wp_set_password($_POST['pwd'], $userid);
    //update_user_meta( $userid, "country", $_POST['country'] );
    //update_user_meta( $userid, "member_type", $_POST['member_type'] );

    global $wpdb;

    $q_newuserid = "select userid from users where userid = '" . $userid . "'";
    $newuserid = $wpdb->get_var($q_newuserid);
    if (!$newuserid > 0) {
      $wpdb->insert('users', array('userid' => $userid), array('%d'));
      //echo $wpdb->last_query;
    }
    $wpdb->update(
            'users', array(
        'user_login' => $_POST['username'],
        'password' => $_POST['password'],
        'indexrequired' => 'T',
        'indexrequireddate' => date('Y-m-d H:i:s')
            ), array('userid' => $userid), array('%s', '%s', '%s', '%s'), array('%d')
    );
    $wpdb->update(
            'users', array(
        'country' => $_POST['country'],
        'membertype' => $_POST['member_type'],
            ), array('userid' => $userid), array('%s', '%s'), array('%d')
    );
    //echo $wpdb->last_query;
    $creds = array();
    $creds['user_login'] = $_POST['username'];
    $creds['user_password'] = $_POST['pwd'];
    $user = wp_signon($creds, false);

    curriki_redirect(get_permalink($dashboard_page));
    return "Please wait while we redirect you to dashboard.";
  } else {
    $_POST['curriki_errors'] = $errors;
    curriki_user_signup_screen($errors);
  }
}

// run it before the headers and cookies are sent
add_action('init', 'curriki_custom_signup');

function curriki_user_signup_screen($error = '') {
  if (!empty($_POST) and empty($_POST['curriki_errors']))
    return "Registration complete.";
  $dashboard_page = 6015;
  if (is_user_logged_in()) {
    $redirect_url = get_permalink($dashboard_page);
    curriki_redirect($redirect_url);
    return "You are already Logged in!";
  } else {
    ob_start();
    @include("curriki_user_signup_screen.php");
    $signup_screen = ob_get_contents();
    ob_end_clean();
    return $signup_screen;
  }
}

add_shortcode("curriki_user_signup", "curriki_user_signup_screen");

function curriki_custom_newsletter() {
  if (empty($_POST['signup_newsletter']))
    return;
  global $wpdb;
  $dashboard_page = 6015;
  if (empty($_POST['nl_name']))
    $errors[] = "Name is required.";
  if (empty($_POST['nl_email']))
    $errors[] = "Email is required.";
  if (!empty($_POST['nl_email'])) {
    $wpdb->prepare("SELECT * FROM newsletters WHERE email = %s ", $_POST['nl_email']);
    $q_newsletter_email = $wpdb->prepare("SELECT * FROM newsletters WHERE email =  %s", $_POST['nl_email']);
    $newsletter_email = $wpdb->get_row($q_newsletter_email);
    if (!empty($newsletter_email))
      $errors[] = "This email has already been signup for newsletter.";
  }


  if (empty($errors)) {
    $wpdb->insert(
            'newsletters', array(
        'name' => $_POST['nl_name'],
        'email' => $_POST['nl_email']
            ), array(
        '%s',
        '%s'
            )
    );
    //curriki_redirect(get_permalink($dashboard_page));
    return "Thanks for signing up!";
  } else {
    $_POST['curriki_errors'] = $errors;
    curriki_newsletter_screen($errors);
  }
}

// run it before the headers and cookies are sent
//add_action( 'init', 'curriki_custom_newsletter' );

function curriki_newsletter_screen($error = '') {
  if (!empty($_POST) and empty($_POST['curriki_errors']))
    return "You have been signed up for newsletter.";
  //return "newsletter";
  ob_start();
  @include("curriki_user_newsletter_screen.php");
  $signup_screen = ob_get_contents();
  ob_end_clean();
  return $signup_screen;
}

add_shortcode("curriki_newsletter", "curriki_newsletter_screen");

if (isset($_GET['test_gapi'])) {
  ?>
  <form method="post" action="">
    <input type="text" name="email" placeholder="Email" value="<?php echo $_POST['email'] ?>" />
    <input type="text" name="password" placeholder="Password" value="<?php echo $_POST['password'] ?>" />
    <input type="submit" value="Send" />
  </form>
  <?php
  include "gapi.class.php";
  //$ga = new gapi('rgreenawalt@curriki.org','Stagewin1!');
  //$profileId = 1841781;
  $ga = new gapi($_POST['email'], $_POST['password']);

  $ga->requestReportData(145141242, array('browser', 'browserVersion'), array('pageviews', 'visits'));

  foreach ($ga->getResults() as $result) {
    echo '<strong>' . $result . '</strong><br />';
    echo 'Pageviews: ' . $result->getPageviews() . ' ';
    echo 'Visits: ' . $result->getVisits() . '<br />';
  }

  echo '<p>Total pageviews: ' . $ga->getPageviews() . ' total visits: ' . $ga->getVisits() . '</p>';
  die;
}

if (isset($_GET['test_newgapi'])) {
  // api dependencies
  require 'vendor/autoload.php';

  // create client object and set app name
  $client = new Google_Client();
  $client->setApplicationName('Curriki'); // name of your app
  // set assertion credentials
  $client->setAssertionCredentials(
          new Google_Auth_AssertionCredentials(
          '298993209434-82tqk9pn83cit80ifhqmaupubdgflmqd@developer.gserviceaccount.com', // email you added to GA
          array('https://www.googleapis.com/auth/analytics.readonly'),
          //file_get_contents('vendor/keys/client_secret_298993209434-82tqk9pn83cit80ifhqmaupubdgflmqd.apps.googleusercontent.com.json') // keyfile you downloaded
          '{"web":{"auth_uri":"https://accounts.google.com/o/oauth2/auth","client_secret":"y5juDWQhl0dQNPKXh2PrTj8U","token_uri":"https://accounts.google.com/o/oauth2/token","client_email":"298993209434-82tqk9pn83cit80ifhqmaupubdgflmqd@developer.gserviceaccount.com","redirect_uris":["http://cg.curriki.org/oauth2callback"],"client_x509_cert_url":"https://www.googleapis.com/robot/v1/metadata/x509/298993209434-82tqk9pn83cit80ifhqmaupubdgflmqd@developer.gserviceaccount.com","client_id":"298993209434-82tqk9pn83cit80ifhqmaupubdgflmqd.apps.googleusercontent.com","auth_provider_x509_cert_url":"https://www.googleapis.com/oauth2/v1/certs","javascript_origins":["http://cg.curriki.org"]}}'
          )
  );

  // other settings
  $client->setClientId('298993209434-82tqk9pn83cit80ifhqmaupubdgflmqd.apps.googleusercontent.com'); // from API console
  // create service and get data
  $service = new Google_Service_Analytics($client);
  echo '<pre>';
  var_dump($service->management_accounts->listManagementAccounts());
  die;
}

class CurrikiNewsletterWidget extends WP_Widget {

  function CurrikiNewsletterWidget() {
    // Instantiate the parent object
    parent::__construct(false, 'Newsletter');
  }

  function widget($args, $instance) {
    //echo "newsletter";
    echo curriki_newsletter_screen();
  }

  function update($new_instance, $old_instance) {
    // Save widget options
  }

  function form($instance) {
    // Output admin widget options form
  }

}

function curriki_register_widgets_newsletter() {
  register_widget('CurrikiNewsletterWidget');
}

add_action('widgets_init', 'curriki_register_widgets_newsletter');

function curriki_redirect($url = "") {
  if (empty($url))
    return;
  echo '<meta http-equiv="refresh" content="0; url=' . $url . '" />';
}

function curriki_redirect_login() {
  //$login_page_url = get_permalink(212);
  curriki_redirect(home_url() . '?modal=login');
}

function curriki_show_featured_item($item = 'homepagealigned') {
    
  $current_language = "eng";
  $current_language_slug = "";
  if( defined('ICL_LANGUAGE_CODE') )
  {
      $current_language = cur_get_current_language(ICL_LANGUAGE_CODE); 
      $current_language_slug = ( ICL_LANGUAGE_CODE == "en" ? "":"/".ICL_LANGUAGE_CODE );
  }
    
  global $wpdb;
  $groups = '';
  $cur_date = date('Y-m-d H:i:s');
  if ($item == 'homepagequote')
    $location = "quote";
  else
    $location = $item;

  $q_featured_items = "SELECT * FROM featureditems WHERE location = '$location' "
          . "AND (active = 'T' OR active = '1') "
          . "AND featuredstartdate < '" . $cur_date . "' AND featuredenddate > '" . $cur_date . "' AND displayseqno != '' ORDER BY displayseqno ASC";
  
  if($current_language!='eng')
  {    
    $q_featured_items = cur_featureditems_ml_query($current_language,$location,$cur_date);        
  }
  
  $featured_items = $wpdb->get_results($q_featured_items);
  $ic = "";
  if (count($featured_items) > 0)
    $site_url = site_url();
  if ($item == 'dashboarduser') {
    $ic .= '<ul>';
    foreach ($featured_items as $fi) {
      $ic .= '<li class="member">';
      $q_user = "SELECT * FROM users u inner join cur_users cu on cu.ID = u.userid WHERE userid = '" . $fi->itemid . "'";
      $user = $wpdb->get_row($q_user);

      if (isset($user) && isset($user->uniqueavatarfile)) {
        $ic .= '<img class="border-grey" src="' . 'https://' . DBI_AWS_S3_CDN_URL . '//avatars/' . $user->uniqueavatarfile . '" alt="member-name" />';
      } else {

        $profile = get_user_meta($user->userid, "profile", true);
        $profile = isset($profile) ? json_decode($profile) : null;
        $gender_img = isset($profile) ? "-" . $profile->gender : "";
        $ic .= '<img class="border-grey" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample' . $gender_img . '.png' . '" alt="member-name" />';
      }
      
      //$user_display_name = $user->firstname . ' ' . $user->lastname;      
      $user_display_name = $fi->displaytitle;      
      $ic .= '<div class="member-info"><span class="member-name name"><A href="' . get_bloginfo('url') . '/members/' . $user->user_nicename . '">' . $user_display_name . '</a></span><span class="occupation">' . __(UCWords($user->membertype),"curriki") . '</span><span class="location">' . $user->city . ', ' . $user->state . ', ' . $user->country . '</span></div>';
      $ic .= '</li>';
    }
    $ic .= '</ul>';
  } elseif ($item == 'partner') {
        
    $ic .= '<ul>';
    foreach ($featured_items as $fi) {
      $ic .= '<li class="partner"><a href="' . $fi->link . '">';
      if (!empty($fi->image))
        $ic .= '<img class="border-grey partners_img" src="' . $fi->image . '" alt="' . $fi->featuredtext . '" />';
      else
        $ic .= '<img class="border-grey partners_img" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="partner-name" />';
      $ic .= '</a><div class="partner-info"><span class="partner-name name"><a href="' . $fi->link . '">' . __($fi->featuredtext,'curriki') . '</a></span></div>';
      $ic .= '</li>';
    }
    $ic .= '</ul>';
  }elseif ($item == 'dashboardresource') {
    $ic .= '<ul>';
    foreach ($featured_items as $fi) {
      $ic .= '<li class="member">';
      $q_resource = "SELECT * FROM resources WHERE resourceid = '" . $fi->itemid . "'";
      $resource = $wpdb->get_row($q_resource);
      if (!empty($fi->image))
        $ic .= '<img class="border-grey" src="' . $fi->image . '" alt="member-name" />';
      else
        $ic .= '<img class="border-grey" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';

      
      $resource_title = $fi->displaytitle;
      
      $ic .= '<div class="member-info"><span class="member-name name"><a href="' . get_bloginfo('url') . '/oer/' . $resource->pageurl . '">' . $resource_title . '</a></span></div>';
      $ic .= '</li>';
    }
    $ic .= '</ul>';
  }elseif ($item == 'homepageresource') {
    foreach ($featured_items as $fi) {
      $q_resource = "SELECT * FROM resources WHERE resourceid = '" . $fi->itemid . "'";
      $resource = $wpdb->get_row($q_resource);
      if (!empty($fi->image))
        $ic .= '<img src="' . $fi->image . '" class="circle border-white" />';
      else
        $ic .= '<img src="' . $site_url . '/wp-content/uploads/2015/03/user-icon-sample.png" class="circle border-white" />';
      $ic .= '<div class="side-tab-title"><a href="' . get_bloginfo('url') . '/oer/' . $resource->pageurl . '">' . $fi->displaytitle . '</a></div>';
      $ic .= __($fi->featuredtext,'curriki') . '<div class="clear">&nbsp;</div>';
    }
  }elseif ($item == 'homepagealigned') {
    foreach ($featured_items as $fi) {
      $q_resource = "SELECT * FROM resources WHERE resourceid = '" . $fi->itemid . "'";
      $resource = $wpdb->get_row($q_resource);
      if (!empty($fi->image))
        $ic .= '<img src="' . $fi->image . '" class="circle border-white" />';
      else
        $ic .= '<img src="' . $site_url . '/wp-content/uploads/2015/03/user-icon-sample.png" class="circle border-white" />';
      $ic .= '<div class="side-tab-title"><a href="' . get_bloginfo('url') . '/oer/' . $resource->pageurl . '">' . __($fi->displaytitle,'curriki') . '</a></div>';
      $ic .= __($fi->featuredtext,'curriki') . '<div class="clear">&nbsp;</div>';
    }
  }elseif ($item == 'quote') {
    ob_start();
    echo '<div id="content" class="activity" role="main">';
    gconnect_locate_template(array('activity/activity-loop.php'), true);
    echo '</div><!-- .activity -->';

    /* if ( is_user_logged_in() ) {
      echo bp_loggedin_user_domain();
      } */
    $activity = ob_get_contents();
    ob_end_clean();
    $ic .= $activity;
    /* foreach($featured_items as $fi){
      $member_activity .= '<div class="group-activity-card page-activity-card">';
      $member_activity .= '<div class="group-activity-member page-activity-member">';
      $member_activity .= '<img class="border-grey circle" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
      $member_activity .= '</div>';
      $member_activity .= '<div class="group-activity page-activity">';
      $member_activity .= '<div class="group-activity-header page-activity-header">';
      $member_activity .= '<div class="group-activity-info page-activity-info">';
      $member_activity .= '<a href="#">Firstname Lastname</a> contributed to <a href="#">This Group Name</a>';
      $member_activity .= '</div>';
      $member_activity .= '<div class="group-activity-time page-activity-time">';
      $member_activity .= 'August 14, 2014  5:15 PM EST';
      $member_activity .= '</div>';
      $member_activity .= '</div>';
      $member_activity .= '<div class="group-activity-body page-activity-body resource-pdf border-grey">';
      $member_activity .= '<div class="group-activity-body-content page-activity-body-content">';
      $member_activity .= '<a class="resource-name" href="#">This Resource Name</a>';
      $member_activity .= 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less...';
      $member_activity .= '<div class="rate-align"><a href="#">Rate Resource</a><a href="#">Align to Standards</a></div>';
      $member_activity .= '</div>';
      $member_activity .= '</div>';
      $member_activity .= '</div>';
      $member_activity .= '</div>';

      $ic .= $member_activity;
      } */
  } elseif ($item == 'homepagequote') {
    foreach ($featured_items as $fi) {
      $user_q = "SELECT * FROM users WHERE userid='" . $fi->itemid . "'";
      $user = $wpdb->get_row($user_q);
      $testimonials = "";
      $testimonials .= '<div class="grid_6 testimonial">';
      $testimonials .= '<div class="testimonial-person grid_4">';
      if ($user->uniqueavatarfile)
        $testimonials .= '<img width="103" height="103" alt="user-icon-sample" class="circle" src="https://' . DBI_AWS_S3_CDN_URL . '//avatars/' . $user->uniqueavatarfile . '">';
      else
        $testimonials .= '<img width="103" height="103" alt="user-icon-sample" class="circle" src="' . get_bloginfo('url') . '/wp-content/uploads/2015/03/user-icon-sample.png">';

      $testimonials .= '<div class="testimonial-name">' . $user->firstname . ' ' . $user->lastname . '</div>';
      $testimonials .= '<div class="testimonial-place">' . $fi->displaytitle . '</div>';
      $testimonials .= '</div>';
      $testimonials .= '<div class="grid_8"><div class="testimonial-text rounded-borders-full">' . __($fi->featuredtext,'curriki') . '</div></div>';
      $testimonials .= '</div>';
      $ic .= $testimonials;
    }
  }elseif ($item == 'dashboardgroup') {
    $groups .= '<ul>';
    if (count($featured_items) > 0)
      foreach ($featured_items as $fi) {
        $members_q = "SELECT slug FROM cur_bp_groups WHERE id='" . $fi->itemid . "'";
        $slug = $wpdb->get_var($members_q);
        $groups .= '<li class="group">';
        if ($fi->image != '')
          $groups .= '<img class="border-grey" src="' . $fi->image . '" alt="$fi->displaytitle" />';
        else
          $groups .= '<img class="border-grey" src="' . get_stylesheet_directory_uri() . '/images/group-icon-sample.png" alt="$fi->displaytitle" />';
        $groups .= '<div class="group-info"><span class="group-name name"><a href="' . get_bloginfo('url') . '/groups/' . $slug . '">' . $fi->displaytitle . '</a></span></div>';
        $groups .= '</li>';
      }
    $groups .= '</ul>';
    $ic .= $groups;
  }else {
    foreach ($featured_items as $fi) {
      $gom = 'groups';
      $slug = $fi->itemid;
      if ($fi->itemidtype == 'user') {
        $gom = 'members';
        $members_q = "SELECT user_nicename FROM cur_users WHERE ID='" . $fi->itemid . "'";
        $slug = $wpdb->get_var($members_q);
      } else {
        $groups_q = "SELECT slug FROM cur_bp_groups WHERE id='" . $fi->itemid . "'";
        $slug = $wpdb->get_var($groups_q);
      }
      if (!empty($fi->image))
        $ic .= '<img src="' . $fi->image . '" class="circle border-white" />';
      else
        $ic .= '<img src="' . site_url() . '/wp-content/uploads/2015/03/user-icon-sample.png" class="circle border-white" />';
      $ic .= '<div class="side-tab-title"><a href="' . get_bloginfo('url') . '/' . $gom . '/' . $slug . '">' . __($fi->displaytitle,'curriki') . '</a></div>';
      $ic .= __($fi->featuredtext,'curriki') . '<div class="clear">&nbsp;</div>';
    }
  }
  return $ic;
}

function curriki_name_scripts() {
  wp_enqueue_style('curriki-jquery-smoothness', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css');
  wp_enqueue_script('curriki-script-jquery', '//code.jquery.com/jquery-1.11.1.js', array(), '1.0.0', true);
  wp_enqueue_script('curriki-script-jquery-ui', '//code.jquery.com/ui/1.11.4/jquery-ui.js', array(), '1.0.0', true);
}

if (isset($_GET['test_login']) and $_GET['test_login'] == 'yes') {
  //add_action( 'wp_enqueue_scripts', 'curriki_name_scripts' );
}

function render_logout_only() {
    require_once 'logout-modal.php';
}

add_action('genesis_after', 'fn_curriki_modal_login_signup');
function fn_curriki_modal_login_signup() {
    global $bp,$wpdb;
    
    $current_language = "eng";
    $current_language_slug = "";
    if( defined('ICL_LANGUAGE_CODE') )
    {
        $current_language = cur_get_current_language(ICL_LANGUAGE_CODE); 
        $current_language_slug = ( ICL_LANGUAGE_CODE == "en" ? "":"/".ICL_LANGUAGE_CODE );
    }

    if( get_current_user_id() > 0 )
    {
        render_logout_only();
        return false;
    }        
    if(is_array($bp->unfiltered_uri) && in_array("register",$bp->unfiltered_uri) )
            return false;
    
    
  if (isset($_GET['test_login']) and $_GET['test_login'] != 'yes')
    return;
  ?>
  <!--<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>-->
  <script type="text/javascript">
    
    function getAjxURLParameter(name) {
        return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null;
    }
      
    function hideshowcenter($id1, $id2) {
      jQuery($id1).hide();
      jQuery($id2).show();

      if (jQuery.fn.center_func == undefined)
      {
        jq($id2).center_func();
      } else
      {
        jQuery($id2).center_func();
      }

      //setInterval(function () {jQuery( $id2 ).center()}, 1);
    }
    jQuery.fn.center_func = function () {
      var h = jQuery(this).height();
      var w = jQuery(this).width();
      var wh = jQuery(window).height();
      var ww = jQuery(window).width();
      var wst = 0; //jQuery(window).scrollTop();
      var wsl = 0; //jQuery(window).scrollLeft();
      this.css("position", "absolute");
      var $top = Math.round((wh - h) / 2 + wst);
      var $left = Math.round((ww - w) / 2 + wsl);


      this.css("top", $top + "px");
      this.css("left", $left + "px");
      this.css("z-index", "1000");
      return this;
    };

    jQuery(function () {
  <?php
  if (isset($_GET['modal'])) {
    if ($_GET['modal'] == 'login')
      echo "hideshowcenter('#login-dialog', '#login-dialog');";
  }
  ?>

      jQuery('#curriki-editable-firstname').click(function () {
        update_profile(jQuery(this), 'firstname');
      });
      jQuery('#curriki-editable-lastname').click(function () {
        update_profile(jQuery(this), 'lastname');
      });
      jQuery('#curriki-editable-bio').click(function () {
        update_profile(jQuery(this), 'bio');
      });
      jQuery('#curriki-editable-city').click(function () {
        update_profile(jQuery(this), 'city');
      });
      jQuery('#curriki-editable-state').click(function () {
        update_profile(jQuery(this), 'state');
      });
      function update_profile($this, field) {
        if (jQuery('input[name=' + field + ']').length)
          return;
        var $val = $this.html();
        $this.html('<input type="text" name="' + field + '" value="' + $val + '" />');
        jQuery('input[name=' + field + ']').focus();
        jQuery("input[name=" + field + "]").focusout(function () {
          $_val = jQuery("input[name=" + field + "]").val();
          $this.html($_val);
          var posting = jQuery.post('?curriki_ajax_action=update_profile', {field: field, value: $_val, curriki_ajax_action: 'update_profile'});
        });
      }
      /*jQuery( "#login-dialog" ).dialog({
       autoOpen: false,
       show: {
       effect: "blind",
       duration: 1000
       },
       hide: {
       effect: "explode",
       duration: 1000
       }
       });*/


      jQuery.fn.center = function () {
        var h = jQuery(this).height();
        var w = jQuery(this).width();
        var wh = jQuery(window).height();
        var ww = jQuery(window).width();
        var wst = 0; //jQuery(window).scrollTop();
        var wsl = 0; //jQuery(window).scrollLeft();
        this.css("position", "absolute");
        var $top = Math.round((wh - h) / 2 + wst);
        var $left = Math.round((ww - w) / 2 + wsl);


        this.css("top", $top + "px");
        this.css("left", $left + "px");
        this.css("z-index", "1000");
        return this;
      }
      jQuery(".class-header-menu-login").click(function () {
        jQuery("#login-dialog").hide();
        jQuery("#logout-dialog").hide();
        jQuery("#forgotpassword-dialog").hide();
        jQuery("#signup-dialog").hide();
        
        jQuery("#login_result").text("");
        jQuery(".dialog_result_div").removeAttr("style");
        
        jQuery("#login-dialog").show('slow');
        setInterval(function () {
          jQuery("#login-dialog").center();          
          
        }, 1);
        jQuery("#loginform input[name='log']").val("").focus();
        jQuery("#loginform input[name='pwd']").val("");
      });
      
      jQuery(".class-header-menu-logout").click(function () {
        jQuery("#login-dialog").hide();
        jQuery("#logout-dialog").hide();
        jQuery("#forgotpassword-dialog").hide();
        jQuery("#signup-dialog").hide();
        jQuery("#logout-dialog").show();
        setInterval(function () {
          jQuery("#logout-dialog").center()
        }, 1);
      });
      jQuery(".class-header-menu-signup").click(function () {
        jQuery("#login-dialog").hide();
        jQuery("#logout-dialog").hide();
        jQuery("#forgotpassword-dialog").hide();
        jQuery("#signup-dialog").hide();
        
        jQuery("#signup_result").text("");
        jQuery(".dialog_result_div").removeAttr("style");
        
        jQuery("#signup-dialog").show('slow');
        setInterval(function () {
          jQuery("#signup-dialog").center();          
        }, 1);
        jQuery("#signupform input[name='firstname']").val("").focus();
      });      

      // Attach a submit handler to the form
      jQuery("#loginform").submit(function (event) {
        jQuery("#login_result").empty().append( jQuery("#please-wait-text-login").val() );
        jQuery(".dialog_result_div").css('background-color', '#031770');
        // Stop form from submitting normally
        event.preventDefault();
        // Get some values from elements on the page:
        var $form = jQuery(this),
        login = $form.find("input[name='log']").val(),
        password = $form.find("input[name='pwd']").val();
        
        
        var found_lang_param_url = false;
        var ajaxurl_arr = ajaxurl.split("?");
        if(ajaxurl_arr.length == 2)
        {            
            var lang_param = ajaxurl_arr[1].indexOf("lang");
            if(lang_param > -1)
            {
                found_lang_param_url = true;
            }
        }        
        //var url = ajaxurl+'?curriki_ajax_action=signup&t=' + new Date().getTime();
        var url = ajaxurl+"?&t=" + new Date().getTime();
        if(found_lang_param_url)
        {
            url = ajaxurl+'&t=' + new Date().getTime();                
        }                      
        // Send the data using post        
        //var posting = jQuery.post(url, {action:'cur_ajax_curriki_login',log: login, pwd: password});
        var posting = jQuery.post(url, {action:'cur_ajax_curriki_login',log:login,pwd: password});        
        // Put the results in a div
            
        posting.done(function (data) {
          //if (data.trim() != '1') {            
          if (data.indexOf('login-done') == -1) {              
            jQuery("#login_result").empty().append(data);
            jQuery(".dialog_result_div").css('background-color', '#031770');
          } else {
            jQuery("#login_result").empty().append( jQuery("#please-wait-text-login").val());
            jQuery(".dialog_result_div").css('background-color', '#031770');            
            
            if( jQuery("#fwdreq").val() !== undefined && jQuery("#fwdreq").val().length > 0 )
            {
                window.location = decodeURI( jQuery("#fwdreq").val() );
            }else{            
                window.location = "<?php echo site_url()."$current_language_slug"; ?>/dashboard";
            }
            
          }
        });
        return false;

      });
      
      // Attach a submit handler to the form
      jQuery("#signupform").submit(function (event) {
                
        jQuery("#signup_result").empty().append( jQuery("#please-wait-text").val() );
        jQuery(".dialog_result_div").css('background-color', '#031770');
        // Stop form from submitting normally
        event.preventDefault();
        // Get some values from elements on the page:
        var $form = jQuery(this),
                firstname = $form.find("input[name='firstname']").val(),
                lastname = $form.find("input[name='lastname']").val(),
                username = $form.find("input[name='username']").val(),
                email = $form.find("input[name='email']").val(),
                pwd = $form.find("input[name='pwd']").val(),
                confirm_pwd = $form.find("input[name='confirm_pwd']").val(),
                member_type = $form.find("select[name='member_type'] option:selected").val(),
                country = $form.find("select[name='country'] option:selected").val(),
                state = $form.find("select[name='state'] option:selected").val(),
                city = $form.find("input[name='city']").val(),
                zipcode = $form.find("input[name='zipcode']").val(),
                school = $form.find("input[name='school']").val(),
                accept = $form.find("input[name='accept']").attr('checked') ? '1' : '0',
                gender = $form.find("input[name='gender']:checked").val();
                //url = '?curriki_ajax_action=signup&t=' + new Date().getTime();
                
                var found_lang_param_url = false;
                var ajaxurl_arr = ajaxurl.split("?");
                if(ajaxurl_arr.length == 2)
                {
                    console.log("ajaxurl_arr = " , ajaxurl_arr);
                    var lang_param = ajaxurl_arr[1].indexOf("lang");
                    if(lang_param > -1)
                    {
                        found_lang_param_url = true;
                    }
                }    
                
                var url = ajaxurl+'?curriki_ajax_action=signup&t=' + new Date().getTime();
                if(found_lang_param_url)
                {
                    url = ajaxurl+'&curriki_ajax_action=signup&t=' + new Date().getTime();                
                }
        // Send the data using post
        var posting = jQuery.post(url, {action:'cur_ajax_curriki_signup',firstname: firstname, lastname: lastname, username: username, email: email, pwd: pwd, confirm_pwd: confirm_pwd, member_type: member_type, country: country, state: state, city: city, accept: accept, zipcode: zipcode, gender: gender, school: school});
        // Put the results in a div
        posting.done(function (data) {                      
          if (data.trim() != '1') {
            jQuery("#signup_result").empty().append(data);
            jQuery(".dialog_result_div").css('background-color', '#031770');
          } else {
            jQuery("#signup_result").empty().append( jQuery("#please-wait-text-login").val() );
            jQuery(".dialog_result_div").css('background-color', '#031770');
            //window.location="<?php // echo get_permalink(6015); ?>";
//            window.location = "<?php // echo site_url() ?>/dashboard/?cn=1";
            window.location = "<?php echo site_url()."$current_language_slug"; ?>/dashboard/?cn=1";
          }          
        });
                   
        return false;

      });

      jQuery("#resetform").submit(function (event) {
        jQuery("#resetform_result").empty().append(jQuery("#please-wait-text-login").val() );
        jQuery("#resetform_result").show();
        // Stop form from submitting normally
        event.preventDefault();
        // Get some values from elements on the page:
        var $form = jQuery(this),
                reset_email = $form.find("input[name='reset_email']").val(),
                url = '?curriki_ajax_action=reset_email&t=' + new Date().getTime();
        // Send the data using post
        var posting = jQuery.post(url, {reset_email: reset_email, action: 'curriki_resetpassword'});
        // Put the results in a div
        posting.done(function (data) {

          if (data.trim() != '1') {
            jQuery("#resetform_result").empty().append(data);
            jQuery(".dialog_result_div").css('background-color', '#031770');
          } else {
            jQuery("#resetform_result").empty().append('<?php echo __("Please check your email.", "curriki"); ?>');
            jQuery(".dialog_result_div").css('background-color', '#031770');
            setTimeout(function () {
              jQuery("#forgotpassword-dialog").hide('slow')
            }, 5000);
          }
        });

        return false;

      });
      jQuery('#country').change(function () {
        var $selected_country = jQuery('#country').val();
        if ($selected_country != 'US') {
          jQuery('#state').val("");
          jQuery('#city').val("");
          jQuery('#state').hide();
          jQuery('#city').hide();
        } else {
          jQuery('#state').show();
          jQuery('#city').show();
        }
      });

  <?php
  if (isset($_GET["a"]) && $_GET["a"] == "login") {
    ?>
        jQuery('.class-header-menu-login a').trigger("click");
        jQuery('#login-dialog').find(".modal-title").after('<p class="message_para">Please Log in or Create an Account to continue to access our free Resources</p>');
        
        //jQuery("#login-dialog").focus();
        
        jQuery('html, body').animate({
            scrollTop: jQuery("div.site-container").offset().top
        }, 300);
                        
    <?php
  }
  ?>

    });
  </script>
  <div id="login-dialog" class="join-oauth-modal modal border-grey rounded-borders-full grid_8" style="display: none;">
    <h3 class="modal-title"><?php echo __('Log in to Your Account','curriki'); ?></h3>
    <div class="dialog_result_div"><div id="login_result" class="dialog_result"></div></div>
    <div class="join-login-section grid_5">
      <div class="signup-form">
        <form method="post" id="loginform">
          <input type="text" name="log" placeholder="<?php echo __("Username","curriki"); ?>">
          <input type="password" name="pwd" placeholder="<?php echo __('Password','curriki'); ?>">
          <input type="submit" class="small-button green-button login" value="<?php echo __('Log In','curriki'); ?>">
          <a href="javascript:hideshowcenter('#login-dialog', '#forgotpassword-dialog');"><?php echo __('Forgot Username or Password?','curriki'); ?></a>

        </form>
          <input type="hidden" name="please-wait-text-login" id="please-wait-text-login" value="<?php echo __('Please wait!','curriki'); ?>" />
          <input type="hidden" id="fwdreq" name="fwdreq" value="<?php echo isset($_GET["fwdreq"])?$_GET["fwdreq"]:""; ?>" />
      </div>
    </div>
    <div class="modal-split modal-split-style grid_2"><?php echo __('Or','curriki'); ?></div>
    <div class="join-login-section grid_5">
      <div class="signup-oauth"><p><?php do_action('oa_social_login'); ?></p>
        <?php $app_auth_url = urlencode(get_bloginfo('url')) . "%2Fclever_login%2Foauth"; ?>
          <!--<a href="https://clever.com/oauth/authorize?response_type=code&client_id=e5da6ddd7da309c332b6&redirect_uri=<?php echo $app_auth_url ?>" alt="Log in with Clever"><img src="https://s3.amazonaws.com/assets.clever.com/sign-in-with-clever/sign-in-with-clever-full.png" /></a>-->
      </div>
    </div>
    
    <div class="join-login-bottom rounded-borders-bottom"><a href="javascript:hideshowcenter('#login-dialog', '#signup-dialog');"><?php echo __("Don't have an account? Join Now",'curriki'); ?></a></div>
    
    <div class="join-login-bottom-term-of-services">
        <p>
            <?php echo __('Our','curriki'); ?> <a href="<?php echo site_url() ?>/terms-of-service/"><?php echo __('Terms of Service','curriki'); ?></a> <?php echo __('and','curriki'); ?> <a href="<?php echo site_url() ?>/privacy-policy/"><?php echo __('Privacy Policies','curriki'); ?></a> <?php echo __('have changed','curriki'); ?>. <?php echo __('By logging in, you agree to our updated Terms and Policies.','curriki'); ?>
        </p>
    </div>
    
    <div class="close"><span class="fa fa-close" onclick="jQuery('#login-dialog').hide();"></span></div>
  </div>
  <div id="logout-dialog" class="join-oauth-modal modal border-grey rounded-borders-full grid_8" style="display: none;">
    <h3 class="modal-title"><?php echo __('Logout?','curriki'); ?></h3>
    <div class="join-login-section grid_5">
      <div class="signup-form">
        <?php echo __('Are you sure you want to logout?','curriki'); ?>
        <input type="reset" onclick="window.location = '<?php echo wp_logout_url(home_url()); ?>';" class="small-button green-button login" value="<?php echo __('Yes','curriki'); ?>">
      </div>
    </div>

    <div class="close"><span class="fa fa-close" onclick="jQuery('#logout-dialog').hide();"></span></div>
  </div>
  <div id="forgotpassword-dialog" class="join-oauth-modal modal border-grey rounded-borders-full grid_8" style="display: none;">
    <h3 class="modal-title"><?php echo __('Please enter your email address and we will email you instructions.','curriki'); ?></h3>
    <div class="dialog_result_div"><div id="resetform_result" class="dialog_result"></div></div>
    <div class="join-login-section grid_5">
      <div class="signup-form">
        <form method="post" id="resetform" name="resetform">
          <input type="text" name="reset_email" placeholder="<?php echo __('Your Email Address','curriki'); ?>">
          <input type="submit" class="small-button green-button login" value="<?php echo __('Send','curriki'); ?>">
        </form>
      </div>
    </div>

    <div class="close"><span class="fa fa-close" onclick="jQuery('#forgotpassword-dialog').hide();"></span></div>
  </div>


  <style type="text/css">
    #login-dialog{
      width: 700px !important;
    }
    #signup-dialog{
      width: 700px !important;
    }
    .join-login-section{
      text-align: left;
    }               
  </style>

  <div id="signup-dialog" class="join-oauth-modal modal border-grey rounded-borders-full grid_8" style="width: 100%; display: none;" title="Sign Up">
    <h3 class="modal-title"><?php echo __('Sign Up','curriki'); ?></h3>
    <div class="dialog_result_div"><div id="signup_result" class="dialog_result"></div></div>
    <div class="join-login-section grid_5"><div class="signup-form">
        <form id="signupform" name="signupform" method="post">
          <input name="signup" value="yes" type="hidden" /> 
          <input type="text" name="firstname" placeholder="<?php echo __('First Name','curriki'); ?>" value="<?php if (isset($_POST['firstname'])) echo $_POST['firstname'] ?>" />
          <input type="text" name="lastname" placeholder="<?php echo __('Last Name','curriki'); ?>" value="<?php if (isset($_POST['lastname'])) echo $_POST['lastname'] ?>" />
          <input type="text" name="username" placeholder="<?php echo __('Username','curriki'); ?>" value="<?php if (isset($_POST['username'])) echo $_POST['username'] ?>">
          <input type="text" name="email" placeholder="<?php echo __('Email','curriki'); ?>" value="<?php if (isset($_POST['email'])) echo $_POST['email'] ?>">
          <input type="password" name="pwd" placeholder="<?php echo __('Password','curriki'); ?>">
          <input type="password" name="confirm_pwd" placeholder="<?php echo __('Confirm Password','curriki'); ?>">
          <input type="text" name="school" placeholder="<?php echo __('School','curriki'); ?>" value="<?php if (isset($_POST['school'])) echo $_POST['school'] ?>" />
          
          <?php if (!isset($_POST['member_type'])) $_POST['member_type'] = ""; ?>
          <select name="member_type">
            <option value="">--- <?php echo __('Member Type','curriki'); ?> ---</option>
            <option value="professional" <?php
            if ($_POST['member_type'] == 'professional') {
              echo 'selected="selected"';
            }
            ?>><?php echo __("Professional","curriki") ?></option>
            <option value="student" <?php
            if ($_POST['member_type'] == 'student') {
              echo 'selected="selected"';
            }
            ?>><?php echo __('Student','curriki'); ?></option>
            <option value="parent" <?php
                    if ($_POST['member_type'] == 'parent') {
                      echo 'selected="selected"';
                    }
                    ?>><?php echo __('Parent','curriki'); ?></option>
            <option value="teacher" <?php
            if ($_POST['member_type'] == 'teacher') {
              echo 'selected="selected"';
            }
            ?>><?php echo __('Teacher','curriki'); ?></option>
            <option value="administration" <?php
            if ($_POST['member_type'] == 'administration') {
              echo 'selected="selected"';
            }
            ?>><?php echo __('School/District Administrator','curriki'); ?></option>
            <option value="nonprofit" <?php
            if (isset($_POST['member_type']) and $_POST['member_type'] == 'nonprofit') {
              echo 'selected="selected"';
            }
            ?>><?php echo __('Non-profit Organization','curriki'); ?></option>
          </select>
          
          <?php
            $q_usa_ml = cur_countries_query($current_language,"US");
            $usa_ml_obj = $wpdb->get_row($q_usa_ml);                     
          ?>
          <select name="country" id="country">
            <option value="US"><?php echo cur_convert_to_utf_to_html($usa_ml_obj->displayname); ?></option>
  <?php  
  $q_countries = cur_countries_query($current_language);
  $countries = $wpdb->get_results($q_countries, ARRAY_A);
  foreach ($countries as $country) {
    $selected = "";
    if (isset($_POST['country']) and $_POST['country'] == $country['country']) {
      $selected = "selected='selected'";
    }
    echo "<option value='" . $country['country'] . "' $selected>" . cur_convert_to_utf_to_html($country['displayname']) . "</option>";
  }
  ?>
          </select>
          <select id="state" name="state">
            <option value="US"> --- <?php echo __('Select State','curriki'); ?> --- </option>
  <?php
  global $wpdb;      
  $q_states = cur_states_query($current_language);
  $states = $wpdb->get_results($q_states, ARRAY_A);
  foreach ($states as $state) {
    $selected = "";
    if (isset($_POST['state']) && $_POST['state'] == $country['state']) {
      $selected = "selected='selected'";
    }
    echo "<option value='" . $state['state_name_orignal'] . "' $selected>" . $state['state_name'] . "</option>";
  }
  ?>
          </select>
          <input id="city" type="text" name="city" placeholder="<?php echo __('City','curriki'); ?>" />
          <input id="zipcode" type="text" name="zipcode" placeholder="<?php echo __('Zip/Postal Code','curriki'); ?>" />

          <fieldset class="filed-set-radio-cls">                                
            <input type="radio" name="gender" id="gender-male" value="male" checked="checked" /> <label for="gender-male"><?php echo __('Male','curriki'); ?></label>
            <input type="radio" name="gender" id="gender-female" value="female" /> <label for="gender-female"><?php echo __('Female','curriki'); ?></label>
            <input type="radio" name="gender" id="gender-other" value="other" /> <label for="gender-other"><?php echo __('Other','curriki'); ?></label>
          </fieldset>
          <div><?php echo __("By creating an account I agree to Curriki's",'curriki'); ?> <span style="text-decoration:underline; cursor:pointer;" onclick="window.location = '<?php echo get_permalink('1998'); ?>';"><?php echo __('Privacy Policy','curriki'); ?></span> <?php echo __('and','curriki'); ?> <span style="text-decoration:underline; cursor:pointer;" onclick="window.location = '<?php echo get_permalink('2005'); ?>';"><?php echo __('Terms of Service','curriki'); ?></span> <?php echo ICL_LANGUAGE_CODE == 'es' ? 'de Curriki':'' ?></div>
          <input type="submit" class="small-button green-button login" value="<?php echo __('Sign Up','curriki'); ?>">
          <a href="javascript:hideshowcenter('#signup-dialog', '#forgotpassword-dialog');"><?php echo __('Forgot Username or Password','curriki'); ?>?</a>
        </form>
        <input type="hidden" name="please-wait-text" id="please-wait-text" value="<?php echo __('Please wait!','curriki'); ?>" />
      </div></div>
    <div class="modal-split modal-split-style grid_2"><?php echo __('Or','curriki'); ?></div><div class="join-login-section grid_5">
        
      <div class="signup-oauth"><p>
  <?php do_action('oa_social_login'); ?>
        </p>
        <!--<a href="https://clever.com/oauth/authorize?response_type=code&client_id=e5da6ddd7da309c332b6&redirect_uri=<?php echo $app_auth_url ?>" alt="Log in with Clever"><img src="https://s3.amazonaws.com/assets.clever.com/sign-in-with-clever/sign-in-with-clever-full.png" /></a>-->
      </div>
    </div>
    <div class="close"><span class="fa fa-close" onclick="jQuery('#signup-dialog').hide();"></span></div>
  </div>
  <?php
}

add_action('wp_ajax_curriki_login', 'fn_wp_ajax_curriki_login');
/*
if (isset($_GET['curriki_ajax_action']) and $_GET['curriki_ajax_action'] == 'login') {
  fn_wp_ajax_curriki_login();
}
*/
function fn_wp_ajax_curriki_login() {
  if (empty($_POST['log'])) {
    echo "User Login is required.";
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

    echo '1';
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

        echo '1';
        die;
      }
      $error = $user->get_error_message();
      if (strstr($error, 'The password you entered for the username', true)) {
        echo "Password is incorrect.";
      } else {
        echo "Username not found.";
      }
    } elseif (strstr($error, 'The password you entered for the username', true)) {
      echo "Password is incorrect.";
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

if (isset($_GET['curriki_ajax_action']) and $_GET['curriki_ajax_action'] == 'newsletter') {
  fn_wp_ajax_curriki_newsletter();
}

function fn_wp_ajax_curriki_newsletter() {
  if (empty($_POST['signup_newsletter']))
    die('Sorry!');

  global $wpdb;
  $dashboard_page = 6015;
  if (empty($_POST['nl_name']))
    $errors[] = "Name is required.";
  if (empty($_POST['nl_email']))
    $errors[] = "Email is required.";
  if (!empty($_POST['nl_email'])) {
    if (isset($_POST["nl_email"]) && strlen($_POST["nl_email"]) > 0 && !filter_var($_POST["nl_email"], FILTER_VALIDATE_EMAIL)) {
      $errors[] = "Please enter valid Email !";
    } else {
      $wpdb->prepare("SELECT * FROM newsletters WHERE email = %s ", $_POST['nl_email']);
      $q_newsletter_email = $wpdb->prepare("SELECT * FROM newsletters WHERE email =  %s", $_POST['nl_email']);
      $newsletter_email = $wpdb->get_row($q_newsletter_email);
      if (!empty($newsletter_email))
        $errors[] = "You have already been signed up for newsletter.";
    }
  }


  if (empty($errors)) {
    $wpdb->insert(
            'newsletters', array(
        'name' => $_POST['nl_name'],
        'email' => $_POST['nl_email']
            ), array(
        '%s',
        '%s'
            )
    );
    //curriki_redirect(get_permalink($dashboard_page));
    echo "Thanks for signing up!";
  } else {
    foreach ($errors as $error) {
      echo $error . '<br />';
    }
  }
  die;
  /*
    if(empty($_POST['log']))return;
    $creds = array();
    $creds['user_login'] = $_POST['log'];
    $creds['user_password'] = $_POST['pwd'];
    $creds['remember'] = $_POST['rememberme'];
    $user = wp_signon( $creds, false );
    if ( !is_wp_error($user) ){
    fn_curriki_wp_login();
    echo '1';
    die;
    }else{
    $error = $user->get_error_message();
    if(strstr($error, 'Invalid username', true)){
    echo "Invalid Username.";
    }elseif(strstr($error, 'The password you entered for the username', true)){
    echo "Password is incorrect.";
    }
    die;
    } */
}

//add_action('wp_login', 'fn_curriki_wp_login');
function fn_curriki_wp_login($user = null) {
  global $wpdb;  
  
  $user_id = isset($user) ? $user->ID : 0;
  $wpdb->insert(
          'logins', array(
                        'sitename' => 'curriki',
                        'userid' => $user_id,      
                        'logindate' => date("Y-m-d H:i:s")
          ), 
          array('%s', '%d', '%s')
  );   
}


if (isset($_GET['curriki_ajax_action']) && $_GET['curriki_ajax_action'] == 'signup' && isset($_POST["is_registration_invitation"]) &&  $_POST["is_registration_invitation"] == '1' ) {    
  fn_wp_ajax_curriki_signup();
}

function fn_wp_ajax_curriki_signup() {
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
      $wpdb->insert('users', array('userid' => $userid), array('%d'));
      //echo $wpdb->last_query;
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

function disable_email_filter($result = '') {

  $to = '';
  $subject = '';
  $message = '';
  $headers = '';
  $attachments = array();

  return compact($_POST['email'], 'subject', 'message');
}

//add_action( 'wp_ajax_curriki_update_profile', 'fn_wp_ajax_curriki_update_profile' );
if (isset($_GET['curriki_ajax_action']) and $_GET['curriki_ajax_action'] == 'update_profile') {
  fn_wp_ajax_curriki_update_profile();
}

function fn_wp_ajax_curriki_update_profile() {
  global $wpdb;
  $wpdb->update(
          'users', array(
      $_POST['field'] => $_POST['value'],
      'indexrequired' => 'T',
      'indexrequireddate' => date('Y-m-d H:i:s')
          ), array('userid' => get_current_user_id()), array('%s', '%s', '%s'), array('%d')
  );
  die;
}

add_filter('gform_author_dropdown_args', 'curriki_set_users');

function curriki_set_users($args) {
  $args['include'] = '1,8';
  return $args;
}

//genesis_register_layout( 'contact-us-sidebar', array('label'   => __( 'Contact Us Sidebar', 'genesis' ), 'img'     => $url . 'cs.gif', 'default' => is_rtl() ? false : true,) );
/* add_filter('genesis_initial_layouts', 'curriki_genesis_initial_layouts',10,2);
  function curriki_genesis_initial_layouts($languages, $url){
  $languages['contact-us-sidebar'] = array(
  'label'   => __( 'Contact Us Sidebar', 'genesis' ),
  'img'     => $url . 'cs.gif',
  'default' => is_rtl() ? false : true,
  );
  return $languages;
  } */
/* genesis_register_widget_area(
  array(
  'id'               => 'contactus-sidebar',
  'name'             => is_rtl() ? __( 'Contact Us Sidebar', 'genesis' ) : __( 'Contact Us Sidebar', 'genesis' ),
  'description'      => __( 'This is the header widget area. It typically appears the the right of the contact us page.', 'genesis' ),
  '_genesis_builtin' => true,
  )
  ); */


function curriki_widgets_init() {

  register_sidebar(array(
      'name' => 'Contact Us sidebar',
      'id' => 'contactus-sidebar',
      'before_widget' => '<div class="contactus-sidebar">',
      'after_widget' => '</div>',
      'before_title' => '<h2 class="rounded">',
      'after_title' => '</h2>',
      'description' => __('This is the contact us widget area.', 'genesis'),
  ));
  register_sidebar(
          array(
              'id' => 'header-right-loggedin',
              'name' => is_rtl() ? __('Header Reight Loggedin', 'genesis') : __('Header Right Loggedin', 'genesis'),
              'description' => __('This is the header widget area. It typically appears the the right of the site title or logo if user loggedin. This widget area is not equipped to display any widget, and works best with a custom menu, a search form, or possibly a text widget.', 'genesis'),
              '_genesis_builtin' => true,
          )
  );
}
add_action('widgets_init', 'curriki_widgets_init');


function curr_genesis_do_header() {  
        
    	global $wp_registered_sidebars;
        /*
	genesis_markup( array(
		'html5'   => '<div %s>',
		'xhtml'   => '<div id="title-area">',
		'context' => 'title-area',
	) );
	do_action( 'genesis_site_title' );
	do_action( 'genesis_site_description' );
	echo '</div>';
        */
	if ( ( isset( $wp_registered_sidebars['header-right'] ) && is_active_sidebar( 'header-right' ) ) || has_action( 'genesis_header_right' ) ) {
		genesis_markup( array(
			'html5'   => '<aside %s>',
			'xhtml'   => '<div class="widget-area header-widget-area">',
			'context' => 'header-widget-area',
		) );

			do_action( 'genesis_header_right' );
			add_filter( 'wp_nav_menu_args', 'genesis_header_menu_args' );
			add_filter( 'wp_nav_menu', 'genesis_header_menu_wrap' );
                        if(is_user_logged_in()){
                            unregister_sidebar("header-right");
                            dynamic_sidebar( 'header-right-loggedin' );
                        }else{
                            unregister_sidebar("header-right-loggedin");                            
                            //dynamic_sidebar( 'header-right' );
                        }
			remove_filter( 'wp_nav_menu_args', 'genesis_header_menu_args' );
			remove_filter( 'wp_nav_menu', 'genesis_header_menu_wrap' );

		genesis_markup( array(
			'html5' => '</aside>',
			'xhtml' => '</div>',
		) );
	}       
}
add_action( 'genesis_header', 'curr_genesis_do_header' );



if (isset($_GET['curriki_ajax_action']) and $_GET['curriki_ajax_action'] == 'resource_rating') {
  $q = "SELECT resourceid FROM comments WHERE resourceid = '" . mysql_real_escape_string($_POST['resource_id']) . "' AND userid = '" . get_current_user_id() . "'";
  $rid = $wpdb->get_var($q);
  if ($rid > 0) {
    echo "You have already posted review for this.";
  } else {
    $wpdb->insert(
            'comments', array(
        'resourceid' => $_POST['resource_id'],
        'userid' => get_current_user_id(),
        'comment' => $_POST['comments'],
        'rating' => $_POST['rating'],
        'commentdate' => date("Y-m-d H:i:s"),
            ), array(
        '%d',
        '%d',
        '%s',
        '%d',
        '%s'
            )
    );
    $q_avg_rating = "SELECT avg(rating) FROM comments WHERE resourceid = '" . mysql_real_escape_string($_POST['resource_id']) . "'";
    $avg_rating = $wpdb->get_var($q_avg_rating);
    $wpdb->update(
            'resources', array(
        'memberrating' => $avg_rating
            ), array('resourceid' => $_POST['resource_id']), array(
        '%d'
            ), array('%d')
    );
    echo "1";
  }
  die;
}

function library_pagination($old_url, $current_page, $total_pages) {
  if ($total_pages < 2)
    return;
  $user_library = "";
  $user_library .= '<div class="pagination">';
  if ($current_page < 2)
    $current_page = 1;
  if ($current_page > 1)
    $user_library .= '<a class="pagination-first" href="' . $old_url . '&page_no=1"><span class="fa fa-angle-double-left"></span></a>';
  if ($current_page > 1)
    $user_library .= '<a class="pagination-previous" href="' . $old_url . '&page_no=' . ($current_page - 1) . '"><span class="fa fa-angle-left"></span> '.__('Previous','curriki').'</a>';

  $first_page = 1;
  $j = 0;
  if ($current_page > 4)
    $first_page = $current_page - ($current_page % 5);
  for ($i = $first_page; $i <= $total_pages; $i++) {
    $current = "";
    if ($current_page == $i)
      $current = " current";
    $user_library .= '<a class="pagination-num' . $current . '" href="' . $old_url . '&page_no=' . $i . '">' . $i . '</a>';
    if ($j++ > 8)
      break;
  }
  if ($current_page < $total_pages)
    $user_library .= '<a class="pagination-next" href="' . $old_url . '&page_no=' . ($current_page + 1) . '">'.__('Next','curriki').' <span class="fa fa-angle-right"></span></a>';
  if ($current_page < $total_pages)
    $user_library .= '<a class="pagination-last" href="' . $old_url . '&page_no=' . $total_pages . '"><span class="fa fa-angle-double-right"></span></a>';
  $user_library .= '</div>';
  return $user_library;
}

function curriki_member_rating($rating = 0) {
  $library = "";
  for ($star_count = 1; $star_count <= round($rating); $star_count++) {
    $library .= '<span class="fa fa-star"></span>';
  }for ($star_count = $star_count; $star_count < 6; $star_count++) {
    $library .= '<span class="fa fa-star-o"></span>';
  }
  return $library;
}

function curriki_library_scripts() {
  ?>
  <div id="rate_resource-dialog" class="review-content-box rounded-borders-full border-grey join-oauth-modal modal border-grey rounded-borders-full grid_8" style="display: none;">
    <h3 class="modal-title curriki-review-title">Rate This</h3>
    <div class="review review-form">
      <div class="dialog_result_div"><div id="resource-rating-form_result" class="dialog_result"></div></div>
      <div class="review-content" style="width: 100% !important;">
        <div class="review-rating rating">

          <span onclick="resourceRating(1);" id="resource-rating-1" class="fa fa-star-o"></span>
          <span onclick="resourceRating(2);" id="resource-rating-2" class="fa fa-star-o"></span>
          <span onclick="resourceRating(3);" id="resource-rating-3" class="fa fa-star-o"></span>
          <span onclick="resourceRating(4);" id="resource-rating-4" class="fa fa-star-o"></span>
          <span onclick="resourceRating(5);" id="resource-rating-5" class="fa fa-star-o"></span></span>
        </div>
        <form method="post" action="" id="resource-rating-form">
          <input type="hidden" name="review-resource-id" id="review-resource-id" value="" />
          <input type="hidden" name="resource-rating" id="resource-rating" value="0">
          <textarea name="resource-comments"></textarea>
          <button class="green-button"><?php echo __('Submit Review','curriki'); ?></button>
        </form>
      </div>
    </div>
    <div class="close"><span class="fa fa-close" onclick="jQuery('#rate_resource-dialog').hide();"></span></div>
  </div>
  <script type="text/javascript">
    function resourceRating(star) {
      jQuery("#resource-rating-" + star).siblings().addClass('fa-star-o').removeClass('fa-star');

      for (i = 1; i <= star; i++)
      {
        jQuery("#resource-rating-" + i).addClass('fa-star');
        jQuery("#resource-rating-" + i).removeClass('fa-star-o');
      }
      jQuery("#resource-rating").val(star);
    }
    jQuery("#resource-rating-form").submit(function (event) {
      jQuery("#resource-rating-form_result").empty().append(jQuery("#please-wait-text").val());
      jQuery(".dialog_result_div").css('background-color', '#031770');
      // Stop form from submitting normally
      event.preventDefault();
      // Get some values from elements on the page:
      var $form = jQuery(this),
              resource_rating = $form.find("input[name='resource-rating']").val(),
              resource_comments = $form.find("textarea[name='resource-comments']").val(),
              resource_id = $form.find("input[name='review-resource-id']").val(),
              url = '?curriki_ajax_action=resource_rating';

      // Send the data using post
      var posting = jQuery.post(url, {rating: resource_rating, comments: resource_comments, resource_id: resource_id, action: 'curriki_resource_rating'});
      // Put the results in a div
      posting.done(function (data) {
        if (data.trim() != '1') {
          jQuery("#resource-rating-form_result").empty().append(data);
          jQuery(".dialog_result_div").css('background-color', '#031770');
        } else {
          jQuery("#resource-rating-form_result").empty().append('Review Posted!');
          jQuery(".dialog_result_div").css('background-color', '#031770');
        }
      });

      return false;

    });
    function curriki_sharethis(rid, title) {
      //"https://www.addthis.com/bookmark.php?source=tbx32nj-1.0&v=300&url='.urlencode(get_bloginfo('url').'/oer/?rid='.$rid).'";
      var url_to_share = '<?php echo get_bloginfo('url') . '/oer/?rid='; ?>' + rid;
      jQuery("#share-" + rid).html(url_to_share);
      alert(url_to_share);
    }
  </script>
  <?php
}

function curriki_library_sorting($page, $position, $selected = "", $userid = '') {
  if ($selected == 'displayseqno')
    $selected_displayseqno = ' selected="selected"';
  else
    $selected_displayseqno = '';
  if ($selected == 'oldest')
    $selected_oldest = ' selected="selected"';
  else
    $selected_oldest = '';
  if ($selected == 'newest')
    $selected_newest = ' selected="selected"';
  else
    $selected_newest = '';
  if ($selected == 'rtc')
    $selected_rtc = ' selected="selected"';
  else
    $selected_rtc = '';
  if ($selected == 'ctr')
    $selected_ctr = ' selected="selected"';
  else
    $selected_ctr = '';
  if ($selected == 'mcf')
    $selected_mcf = ' selected="selected"';
  else
    $selected_mcf = '';
  if ($selected == 'mff')
    $selected_mff = ' selected="selected"';
  else
    $selected_mff = '';
  if ($selected == 'aza')
    $selected_aza = ' selected="selected"';
  else
    $selected_aza = '';
  if ($selected == 'azd')
    $selected_azd = ' selected="selected"';
  else
    $selected_azd = '';
  if ($selected == 'ru')
    $selected_ru = ' selected="selected"';
  else
    $selected_ru = '';

  $library_sorting = '<form method="GET" action="" id="library_sorting_form-' . $position . '">';
  if (!empty($userid))
    $library_sorting .= '<input type="hidden" name="userid" value="' . $userid . '" />';
  $library_sorting .= '<strong>'.__('Sort by','curriki').': </strong><select name="library_sorting" onchange="document.getElementById(\'library_sorting_form-' . $position . '\').submit();">';  
  if ($page == 'my') {
    $library_sorting .= '<option value="mcf"' . $selected_mcf . '>'.__('My Contributions First','curriki').'</option>';
    $library_sorting .= '<option value="mff"' . $selected_mff . '>'.__('My Favorites First','curriki').'</option>';
  }
  //. '<option value="displayseqno"'.$selected_displayseqno.'>Sequence No</option>'
  $library_sorting .= '<option value="aza"' . $selected_aza . '>'.__('Title [A-Z]','curriki').'</option>';
  $library_sorting .= '<option value="azd"' . $selected_azd . '>'.__('Title [Z-A]','curriki').'</option>';

  $library_sorting .= '<option value="newest"' . $selected_newest . '>'.__('Newest First','curriki').'</option>';
  $library_sorting .= '<option value="oldest"' . $selected_oldest . '>'.__('Oldest First','curriki').'</option>'
          . '<option value="rtc"' . $selected_rtc . '>'.__('Resources then Collections','curriki').'</option>'
          . '<option value="ctr"' . $selected_ctr . '>'.__('Collections then Resources','curriki').'</option>'
          . '<option value="ru"' . $selected_ru . '>'.__('Recently Updated','curriki').'</option>';

  $library_sorting .= '</select></form>';

  return $library_sorting;
}

function curriki_sharethis($rid, $title = '') {
  return '<a title="Share this resource with a friend" '
          . 'onclick="return addthis_sendto()" '
          . 'onmouseout="addthis_close()" '
          . 'onmouseover="return addthis_open(this, \'\', \'' . get_bloginfo('url') . '/oer/?rid=' . $rid . '\', \'' . $title . '\')" '
          . 'href="javascript:;"><span class="fa fa-share-alt-square"></span> <span>'.__('Share','curriki').'</span></a>';
//    return '<a href="javascript:;" onclick="curriki_sharethis(\''.$rid.'\', \''.$title.'\');"><span class="fa fa-share-alt-square"></span> <span>Share</span></a><span id="share-'.$rid.'"></span>';
}

add_action('genesis_after', 'curriki_footer_scripts');

function curriki_footer_scripts() {
  if (!is_user_logged_in()) {
    ?>
    <style type="text/css">
      #menu-item-6034 { display: none; }
    </style>
    <?php
  }
}

function curriki_addthis_scripts() {
  echo '<!-- Go to www.addthis.com/dashboard to customize your tools -->
        <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-554cdcac2ebf96b6" async="async"></script>';
}

if (!empty($_GET['curriki_ajax_action']) and $_GET['curriki_ajax_action'] == 'curriki_organize_collection') {
  if (empty($_POST['s']))
    echo 'Nothing changed.';else {
    global $wpdb;
    $resources_posted = '';
    $cid_post = mysql_real_escape_string($_POST['cid']);
    $q_collectionid = "select resourceid from resources where resourceid = '" . $cid_post . "' and contributorid = '" . get_current_user_id() . "'";
    $cid = $wpdb->get_var($q_collectionid);
    if ($cid_post != $cid)
      echo "Bad attempt logged.";else {
      $seqs = explode(',', mysql_real_escape_string($_POST['s']));
      foreach ($seqs as $s) {
        $s = explode('=', $s);
        $wpdb->update(
                'collectionelements', array(
            'displayseqno' => $s[1] // string
                ), array('collectionid' => $cid, 'resourceid' => $s[0]), array(
            '%d' // value2
                ), array('%d', '%d')
        );
        $resources_posted .= ',' . $s[0];
      }
      $q_delete_elements = "delete from collectionelements where collectionid = '" . $cid . "' and resourceid not in (0" . $resources_posted . ")";
      $wpdb->query($q_delete_elements);
      echo '1';
    }
  }
  die;
}
add_action('oa_social_login', 'curriki_oa_social_login', 1);

function curriki_oa_social_login() {
  ?>
  <script type="text/javascript">
    var your_callback_script = '<?php echo get_bloginfo('url') ?>/dashboard/';
  </script>
  <?php
}

add_filter('wp_get_nav_menu_items', 'curriki_wp_get_nav_menu_items', 10, 3);

function curriki_wp_get_nav_menu_items($items, $menu, $args) {               
    
  if ( ($menu->slug == 'primary-nav' && !is_user_logged_in()) || (!is_user_logged_in() && $menu->slug == 'primary-nav-'.strtolower(ICL_LANGUAGE_NAME_EN) ) ) {        
    unset($items[3]);
    unset($items[2]);
    unset($items[1]);
    unset($items[0]);
  }
  return $items;
}

add_filter('pre_get_posts', 'switch_search_to_posts');

function switch_search_to_posts($query) {

  if ($query->is_admin)
    return $query;

  if (!$query->is_search)
    return $query;

  $query->set('post_type', 'post');

  return $query;
}

//======= Setteubg resource slug in oer post's slug for WPML flangs =============
//add_filter( 'icl_ls_languages', 'cur_ls_languages',110,1);
function cur_ls_languages( $languages ) {
  
    //global $post;      
    $pageurl_text = $_SESSION["pageurl_text"];     
    $pagename = get_query_var('pagename'); 
    if( $pagename === "oer")
    {                                        
      $request_uri = explode("/", preg_replace('{/$}', '', $_SERVER["REQUEST_URI"]));      
      $resource_slug = $request_uri[count($request_uri)-1];
      
      //$resource_slug = implode( "W" , $_GET ) ;
      
      //$pageurl_text = isset($pageurl_text) ? "yes":"no";
      
      foreach ($languages as $k=>$l)
      {
          $l["url"] = $l["url"].$pageurl_text;  
          //$l["url"] = $l["url"];  
          $languages[$k] = $l;
      }
    }      
    /*
    global $resourceUserGlobal;    
    $pagename = get_query_var('pagename'); 
    if( $pagename === "oer")
    {                                        
        //$resource_slug = $resourceUserGlobal->pageurl;
        $resource_slug = $resourceUserGlobal["pageurl"];
        foreach ($languages as $k=>$l)
        {
            $l["url"] = $l["url"].$resource_slug;  
            $languages[$k] = $l;
        }
    }
    */
    return $languages;
}


//add_action('wp_loaded', 'curr_bp_actions',99,3);
function curr_bp_actions($a,$b,$c)
{
    
    /*
    global $wpdb;    
    $a = $wpdb->get_row("select * from cur_options where option_name = 'rewrite_rules'");
    echo "<pre>";
        var_dump(
                    unserialize($a->option_value)
                );
    die;
    */
    echo "<pre>-------------";
    var_dump($a);
    echo "<br />=============================<br />";
    var_dump($b);
    echo "<br />=============================<br />";
    var_dump($c);    
    echo "<br />**************<br />";
    
    //var_dump( function_exists("get_taxonomies") );
    
    $taxonomies = get_taxonomies();
    
    //var_dump(count($taxonomies) );    
    var_dump($taxonomies);
    
    //global $bp;
    //echo "<pre>wwwww0900*111***111*9900wwwwwwwwwww11111  ";
    //var_dump($bp);    
    //register_taxonomy($taxonomy, $object_type);
    die;
}



/*
add_action( 'shutdown', function(){
        foreach( $GLOBALS['wp_actions'] as $action => $count )
        {
            var_dump($count );
            echo "  ***********   ";
            var_dump($action );
            echo " <br /> =====================================================  <br /> ";
        }
        wp_die();
    });
*/

