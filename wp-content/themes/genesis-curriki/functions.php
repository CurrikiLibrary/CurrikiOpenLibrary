<?php

if (!session_id()) {
  session_start();
}

add_action( 'init', 'load_scripts_styles',1);

function load_scripts_styles(){
    wp_enqueue_style('bootstrap-css',  get_stylesheet_directory_uri() . '/css/bootstrap.min.css');
    wp_enqueue_script('bootstrap-js', get_stylesheet_directory_uri() . '/js/home-page/bootstrap.min.js', 'jquery', '2.1.5', true);
}
add_action( 'init', 'login_check',1);

function login_check(){

    /*
     * WP Security for users
     */
//    if ( $_SERVER['REQUEST_URI'] != '/' && substr( $_SERVER['REQUEST_URI'], 0, 3 ) != "/wp") {
//        if(get_current_user_id() > 0){
//            $user_meta=get_userdata(get_current_user_id());
//
//            $user_roles=$user_meta->roles;
//            if ( !in_array( 'administrator', (array) $user_roles ) ) {
//                wp_logout();
//                header(get_site_url());
//                exit();
//            }
//        } else {
//            wp_redirect(get_site_url());
////            header("Location:".get_site_url());
//            exit();
//        }
//
//    }
}


/*
 * Child Theme Name: Curriki Child Theme for Genesis 2.1
 * Author: Orange Blossom Media
 * Url: http://orangeblossommedia.com/
 */

//UTF-8 Meta Tag
add_action('genesis_meta', 'cur_charset_meta_tag');

function cur_charset_meta_tag() {
  echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
}

/* * *********** REGISTER CHILD THEME ************ */
include("curriki-customized/curriki-customized.php");

include("group-custom/custom-functions.php");

define('CHILD_THEME_NAME', 'curriki');
define('CHILD_THEME_URL', 'https://www.curriki.org/');

// add_action( 'admin_bar_menu', 'show_template' );
function show_template() {
  global $template;
  print_r($template);
}

/* * *********** THEME SETUP TIME ************ */


// Activate the child theme
add_action('genesis_setup', 'curriki_theme_setup', 15);

function curriki_scripts() {

  wp_enqueue_style('misc', get_stylesheet_directory_uri() . '/css/misc.css', array());
}

add_action('wp_enqueue_scripts', 'curriki_scripts');

// we're putting all our core stuff in this function.
function curriki_theme_setup() {

  // Clean up wordpress and genesis and add things like our stylesheets and javascript libraries.
  include_once( CHILD_DIR . '/lib/child_setup.php'); // Required Do Not Delete.
  // Holds all of our base child functions
  include_once( CHILD_DIR . '/lib/child_theme_functions.php');

  // Holds all of our admin functions
  include_once( CHILD_DIR . '/lib/admin_functions.php');

  // Holds all of our custom post type functions
  include_once( CHILD_DIR . '/lib/cpt_functions.php');

  // Widget to display Contributors
  include_once( CHILD_DIR . '/lib/curriki_contributors_widget.php');

  // include ( get_stylesheet_directory() . '/page-member.php' );
  // include ( get_stylesheet_directory() . '/page-user-dashboard.php' );
  // include ( get_stylesheet_directory() . '/page-user-dashboard.php' );

  require_once( CHILD_DIR . '/functions/functions-bp.php');
  require_once( CHILD_DIR . '/functions/other-functions.php');
  // require_once( CHILD_DIR . '/functions/functions-bp-display.php');

  add_filter('show_admin_bar', '__return_false');

  // Don't update theme (it's custom right? so you don't need updates)
  add_filter('http_request_args', 'curriki_dont_update', 5, 2);

  /*   * *********** THEME SUPPORT ************ */

  // Turn On HTML5 Markup @since GENESIS 2.0 final
  add_theme_support('html5');

  // Add structural support
  add_theme_support('genesis-structural-wraps', array('header', 'menu-primary', 'menu-secondary', 'site-inner', 'footer-widgets', 'footer'));

  // Filter the site-inner context of the genesis_structural_wrap to add a containter div
  add_filter('genesis_structural_wrap-site-inner', 'curriki_filter_site_inner_structural_wrap', 15, 2);

  /**
   * @param string $output The markup to be returned
   * @param string $original_output Set to either 'open' or 'close'
   */
  function curriki_filter_site_inner_structural_wrap($output, $original_output) {
    global $bp;
    if (!is_front_page()) {
      if ($bp->current_component == "groups") {
        return;
      }
      if ('open' == $original_output) {
        $output = '<div class="container_12">';
      } elseif ('close' == $original_output) {
        $output = '</div>';
      }
    }

    return $output;
  }

  // Menus
  add_theme_support('genesis-menus', array(
      'primary' => 'Primary Navigation Menu',
          // 'secondary' => 'Footer Menu',
  ));
  add_filter('wp_nav_menu_items', 'curriki_theme_menu_extras', 10, 2);

  // Reposition the navigation
  // remove_action( 'genesis_after_header', 'genesis_do_nav' );
  remove_action('genesis_after_header', 'genesis_do_subnav');
  // add_action( 'genesis_header', 'genesis_do_nav', 5 );
  // add_action( 'genesis_before_footer', 'genesis_do_subnav' );
  // Remove Header Title and Tagline
  // remove_action( 'genesis_site_title', 'genesis_seo_site_title' );
  remove_action('genesis_site_description', 'genesis_seo_site_description');

  // Posts
  remove_action('genesis_before_post_content', 'genesis_post_info', 99);
  remove_action('genesis_before_entry_content', 'genesis_post_info', 99);
  // add_action( 'genesis_entry_header', 'genesis_post_info', 5 );

  remove_action('genesis_post_content', 'genesis_do_post_image');
  add_action('genesis_before_post', 'genesis_do_post_image');

  /** Add custom post image above post title */
  add_action('genesis_before_post_content', 'generate_post_image', 5);

  function generate_post_image() {

    if (!genesis_get_option('content_archive_thumbnail'))
      return;

    if ($image = genesis_get_image(array('format' => 'url', 'size' => genesis_get_option('image_size')))) {
      printf('<a href="%s" rel="bookmark"><img class="post-image" src="%s" alt="%s" /></a>', get_permalink(), $image, the_title_attribute('echo=0'));
    }
  }

  /*   * *********** UNREGISTER LAYOUTS AND WIDGETS ************ */

  genesis_unregister_layout('content-sidebar-sidebar');
  genesis_unregister_layout('sidebar-sidebar-content');
  genesis_unregister_layout('sidebar-content-sidebar');
  // genesis_unregister_layout( 'content-sidebar' );
  genesis_unregister_layout('sidebar-content');

  // Remove Genesis Widgets
  add_action('widgets_init', 'curriki_remove_genesis_widgets', 20);

  function curriki_remove_genesis_widgets() {
    unregister_widget('Genesis_eNews_Updates');
    unregister_widget('Genesis_Featured_Page');
    // unregister_widget( 'Genesis_Featured_Post' );
    unregister_widget('Genesis_Latest_Tweets_Widget');
    unregister_widget('Genesis_User_Profile_Widget');
  }

  /*   * *********** <HEAD> ELEMENTS ************ */

  // remove default stylesheet
  remove_action('genesis_meta', 'genesis_load_stylesheet');

  // enqueue base scripts and styles
  add_action('wp_enqueue_scripts', 'curriki_scripts_and_styles', 999); // See "/lib/child_theme_setup.php"
  // Add viewport meta tag for mobile browsers @since GENESIS 2.0
  add_theme_support('genesis-responsive-viewport');

  // Change favicon location
  add_filter('genesis_pre_load_favicon', 'curriki_custom_favicon_location');

  // Typekit Fonts
  add_action('wp_head', 'curriki_typekit_load');


  /*   * *********** CLEANING <HEAD> ************ */

  // Remove rsd link
  remove_action('wp_head', 'rsd_link');
  // Remove Windows Live Writer
  remove_action('wp_head', 'wlwmanifest_link');
  // Remove index link
  remove_action('wp_head', 'index_rel_link');
  // Remove previous link
  remove_action('wp_head', 'parent_post_rel_link', 10, 0);
  // Remove start link
  remove_action('wp_head', 'start_post_rel_link', 10, 0);
  // Remove links for adjacent posts
  remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
  // Remove WP version
  remove_action('wp_head', 'wp_generator');
  // }


  /*   * *********** CHILD THEME IMAGE SIZES ************ */
  /*
   * To add more sizes, simply copy a line from below and change the dimensions & name.
    As long as you upload a "featured image" as large as the biggest set width or height,
    all the other sizes will be auto-cropped.

   * To call a different size, simply change the text inside the thumbnail function.

   * For example, to call the 225 x 225 sized image, we would use the function:
    <?php the_post_thumbnail( 'curriki_medium_img' ); ?>

   * You can change the names and dimensions to whatever you like.
   */

  //add_image_size( 'curriki_medium_img', 225, 225, TRUE );
  //add_image_size( 'curriki_small_img', 45, 45, TRUE );

  update_option('image_default_link_type', 'none');


  /*   * *********** SIDEBARS AND WIDGETS ************ */

  // Remove Sidebars
  //unregister_sidebar( 'header-right' );
  // unregister_sidebar( 'sidebar' );
  unregister_sidebar('sidebar-alt');

  // Home page widgets
  genesis_register_sidebar(array(
      'id' => 'home-footer-1',
      'name' => __('Home Footer One', 'curriki_theme'),
  ));
  genesis_register_sidebar(array(
      'id' => 'home-footer-2',
      'name' => __('Home Footer Two', 'curriki_theme'),
  ));
  genesis_register_sidebar(array(
      'id' => 'home-footer-3',
      'name' => __('Home Footer Three', 'curriki_theme'),
  ));
  // genesis_register_sidebar(array(
  //     'id' => 'logo-footer',
  //     'name' => __('Logo Footer', 'curriki_theme'),
  // ));
  genesis_register_sidebar(array(
    'id' => 'home-newsletter-subscription',
    'name' => __('Home Newsletter Subscription', 'curriki_theme'),
  ));
  /*   * *********** FOOTER AREA ************ */

  // footer credit & attribution text
  add_filter('genesis_footer_creds_text', 'curriki_footer_cred');

  /*
    if you want to add widgets to your footer, you can use this function
   */
  add_theme_support('genesis-footer-widgets', 5);
}

/* DO NOT DELETE (YOUR CHILD THEME WILL IMPLODE!) */



// bailiwik load scripts
if (!function_exists('cur_load_scripts')) :

  function cur_load_scripts() {

    // wp_enqueue_style( 'normalise',  get_template_directory_uri() . '/css/normalise.css', array());

    wp_enqueue_script('jquery-ui-tabs');
    wp_enqueue_script('jquery-ui-accordion');
    wp_enqueue_script('jquery-ui-button');
  }

endif;
add_action('wp_enqueue_scripts', 'cur_load_scripts');

add_action('add_meta_boxes', 'adding_new_metaabox');

function adding_new_metaabox() {
  $types = array('page');

  foreach ($types as $type) {
    //add_meta_box('featured-meta', 'Feature Me?', 'custom_meta_featured', $type, 'side');
    add_meta_box('page_right_section', 'Right Content', 'my_output_function', $type, 'side');
  }
}

function my_output_function($post) {
  //so, dont ned to use esc_attr in front of get_post_meta
  $valueeee2 = get_post_meta($_GET['post'], 'rightcontent_value', true);
  wp_editor(htmlspecialchars_decode($valueeee2), 'mettaabox_ID_stylee', $settings = array('textarea_name' => 'rightcontent'));
}

function save_my_postdata($post_id) {
  if (!empty($_POST['rightcontent'])) {
    $datta = htmlspecialchars($_POST['rightcontent']);
    update_post_meta($post_id, 'rightcontent_value', $datta);
  }
}

add_action('save_post', 'save_my_postdata');

add_action('get_header', 'child_sidebar_logic');

/**
 * Swap in a different sidebar instead of the default sidebar.
 * 
 * @author Jennifer Baumann
 * @link http://dreamwhisperdesigns.com/?p=1034
 */
function child_sidebar_logic() {
  if (is_page_template('page-right-column.php')) {
    remove_action('genesis_after_content', 'genesis_get_sidebar');
    add_action('genesis_after_content', 'child_get_blog_sidebar');
    add_filter('body_class', 'my_class_names');
  }
  return;
  if (is_page_template('page-right-column.php') || is_page_template('page_blog.php')) {
    remove_action('genesis_after_content', 'genesis_get_sidebar');
    add_action('genesis_after_content', 'child_get_blog_sidebar');
    add_filter('body_class', 'my_class_names');
  }
}

function my_class_names($classes) {
  if (($key = array_search('page-template', $classes)) !== false) {
    unset($classes[$key]);
  }
  $classes[] = 'page-template-default';
  return $classes;
}

function child_get_blog_sidebar() {


  $my_post_meta = get_post_meta(get_the_id(), 'rightcontent_value', true);
  if (!empty($my_post_meta)) {
    $input = get_post_meta(get_the_id(), 'rightcontent_value', true);
    $str = html_entity_decode($input);
  }
  echo '<aside class="sidebar sidebar-primary widget-area" role="complementary" itemscope="itemscope" itemtype="http://schema.org/WPSideBar">
	<section id="text-12" class="widget widget_text">' . $str . '</section>
	</aside>';
}

define('ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true);
add_action( 'wp_enqueue_scripts', 'remove_default_stylesheet', 20 );
function remove_default_stylesheet() {
    wp_dequeue_style( 'wpml-legacy-vertical-list-0-css' );
    wp_deregister_style( 'wpml-legacy-vertical-list-0-css' );
}
add_action( 'init', 'allow_origin' );
function allow_origin() {
    header("Access-Control-Allow-Origin: *");
}


add_action( 'init', 'registration_check',5);

function registration_check() {
    global $wpdb;
    
    if ( is_user_logged_in() && !current_user_can('administrator') ) {
        $userData = $wpdb->get_row(
                $wpdb->prepare("SELECT * FROM users where userid = %d", get_current_user_id())
                );

//        if($_SERVER['REQUEST_URI'] != '/edit-profile' && strpos($_SERVER['REQUEST_URI'], 'wp-login.php')!== true){
//            if((empty($userData->country) || empty($userData->membertype) || empty($userData->school))){
//                $url = get_site_url().'/edit-profile';
//                wp_redirect($url);
//                exit();
//            }
//        }
        
    }
}


add_action( 'init', 'login_to_partner_by_key',5);

function login_to_partner_by_key(){
    if(!is_user_logged_in()){
        if(isset($_REQUEST['partnerkey']) && ($_REQUEST['partnerkey'] == 'p5MTY49cIbtbMyGmKVwYY3KERRfN3chNbaLNLUfp')){ //georgia partner
            $user = get_user_by('login', 'georgeapartner' );
            if ( !is_wp_error( $user ) )
            {
                wp_clear_auth_cookie();
                wp_set_current_user ( $user->ID );
                wp_set_auth_cookie  ( $user->ID );
                header("Location:".$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$_SERVER['REDIRECT_URL']);
            }
        }
        if(isset($_REQUEST['partnerkey']) && ($_REQUEST['partnerkey'] == '9STm9P9vQ96WwhPM2RJo0y3eOR40Yp5KzxIxFydj')){ //LRN partner
            $user = get_user_by('login', 'currikilrn' );
            if ( !is_wp_error( $user ) )
            {
                wp_clear_auth_cookie();
                wp_set_current_user ( $user->ID );
                wp_set_auth_cookie  ( $user->ID );
                header("Location:".$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$_SERVER['REDIRECT_URL']);
            }
        }
    }
    
}



// Add Google Tag Manager code in <head>
add_action( 'wp_head', 'sk_google_tag_manager1', -1000 ); // at the top of <head>
function sk_google_tag_manager1() { ?>
	<!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-PKB8B5J');</script>
        <!-- End Google Tag Manager -->
<?php }
// Add Google Tag Manager code immediately below opening <body> tag
add_action( 'genesis_before', 'sk_google_tag_manager2' );
function sk_google_tag_manager2() { ?>
	<!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PKB8B5J"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
<?php }




require_once( 'functions/newsletters-emails.php');


// Add Google Tag Manager code in <head>
add_action( 'wp_head', 'fb_pages_meta_tag', -900 ); // at the top of <head>
function fb_pages_meta_tag() { ?>
	<meta property="fb:pages" content="134427817464" />
<?php }


function jwt_auth_function($data, $user)
{
  $data['id'] = $user->id;
  return $data;
}
add_filter('jwt_auth_token_before_dispatch', 'jwt_auth_function', 10, 2);

add_action('rest_api_init', function () {
  require_once(__DIR__.'/../../libs/rest-api/ResourceController.php');
  require_once(__DIR__.'/../../libs/rest-api/UserController.php');

  $resource_controller = new Resource_Controller();
  $resource_controller->register_routes();

  $user_controller = new User_Controller();
  $user_controller->register_routes();
});

add_action('rest_api_init', function () {
  require_once('lib/rest-webhooks/WebhookController.php');

  $webhook_controller = new Webhook_Controller();
  $webhook_controller->register_routes();
});

function curriki_style_last() {
  wp_enqueue_style('responsive-css', get_stylesheet_directory_uri() . '/css/responsive.css');
}

add_action('wp_enqueue_scripts', 'curriki_style_last', 999999999999);

function add_content_creator_role() {
  if ( get_option( 'content_creator_role_added' ) < 1 ) {
      add_role( 'content_creator', 'Content Creator', array( 'read' => true, 'level_0' => true ) );
      update_option( 'content_creator_role_added', 1 );
  }
}

add_action( 'init', 'add_content_creator_role' );