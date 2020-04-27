<?php
/*
  Created on : Mar 21, 2016, 8:58:41 PM
  Author     : waqarmuneer
  Purpose    : to manage LTI module functionality
 */

require_once __DIR__."/../../core/Curriki_controller.php";

class Lti extends Curriki_controller{

    //Misc Variables
    public $wpdb;    
    public $partnerid;
    public $current_language = "eng";    
    
    //Constructor
    public function __construct()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
                                                
        $this->loadHelper("tp_misc_helper");                
        $this->loadclass("lti-core/ToolConsumer");
        $this->loadclass("lti_lms_modal");
        
        if(!isset($_GET["action"]))
        {
            $_GET["action"] = "select_lms";
        }
        parent::__construct();
        
        global $wpdb;
        $this->wpdb = $wpdb;
        
        $this->partnerid = (isset($this->request['partnerid']) AND ! empty($this->request['partnerid'])) ? intval($this->request['partnerid']) : 0;        
        $this->current_language = "eng";
        if( defined('ICL_LANGUAGE_CODE') )
            $this->current_language = cur_get_current_language(ICL_LANGUAGE_CODE);         
               
        $this->saveConsumer();                        
    }
        
    public function saveConsumer()
    {        
        if( isset($_POST["savelti_action"]) && $_POST["savelti_action"] === "Save")
        {                  
            $lti_res_message =  isset($_POST["key"]) && strlen($_POST["key"]) === 0 ? "<br />Missing \"Key\".":"";
            $lti_res_message .= isset($_POST["secret"]) && strlen($_POST["secret"]) === 0 ? "<br />Missing \"Secret\".":"";
            $lti_res_message .= isset($_POST["key"]) && strlen($_POST["key"]) > 20 ? "<br />\"Key\" should have up to 20 characters":"";
            $lti_res_message .= isset($_POST["secret"]) && strlen($_POST["secret"]) > 32 ? "<br />\"Secret\" should have up to 32 characters":"";
        
            if( strlen($lti_res_message) === 0 )
            {                
                global $wpdb;                
                $q_me = "SELECT * FROM users where userid = '" . get_current_user_id() . "'";                                
                $me = $wpdb->get_row($q_me);
                
                $user_credentials = null; 
                $cm = new ToolConsumer();
                
                if( isset($_POST["cpk"]) && intval($_POST["cpk"]) > 0 )
                {
                    $cm->setRecordId($_POST["cpk"]);                
                    $cm->lms = $_POST["lms"];
                    $user_credentials = $cm->getByIdAndCurrentUserAndLMS();                
                }
                
                $consumer = new ToolConsumer();                
                
                if($user_credentials)
                {
                    $consumer->setRecordId($user_credentials->consumer_pk);                
                }            
                $consumer->name = "{$me->firstname} {$me->lastname}";
                $consumer->setKey( TPMiscHelper::cleanSpecialCharacters( trim($_POST["key"]) ) );
                $consumer->secret = TPMiscHelper::cleanSpecialCharacters( trim($_POST["secret"]) );
                $consumer->ltiVersion = "LTI-1p0";
                $consumer->enabled = 1;            
                $consumer->lms = $_POST["lms"];
                               
                $consumer->save();
                //wp_redirect(site_url()."/manage-lti/?action=lti_form&lms={$_POST["lms"]}&t=".time());
                //wp_die();
                
                $lti_lms_modal = new Lti_lms_modal();                
                $lti_lms_modal_links = $lti_lms_modal->getLtiIntegrationLinks();
                
                $rtn = new stdClass();
                $rtn->done = 1; 
                $rtn->message = "Credential Saved!";
                $rtn->lms_modal_links = $lti_lms_modal_links;
                echo json_encode($rtn);
                die();
            }else{
                //$_SESSION['lti_res_message'] = $lti_res_message;
                $rtn = new stdClass();
                $rtn->done = 0;                
                $rtn->message = $lti_res_message;
                echo json_encode($rtn);
                die();
            }
        }
        
    }
    
        public function loadHelper($filename) {
        $this->loadclass($filename);         
    }
    
    public function loadclass($filename) {
        return get_template_part('modules/lti-front/classes/'.$filename);
    }
    
    public function loginRedirect() {
        if(!is_user_logged_in() && function_exists('curriki_redirect_login')){curriki_redirect_login();die;}
    }
    
    //Search Page Functions
    public function curriki_module_page_init() {
        
        //$this->request = array_merge(array('educationlevel' => array(), 'subject' => array(), 'subsubjectarea' => array(), 'instructiontype' => array()), $this->request);
        $this->subdomain = SUBDOMAIN;
        $this->branding = SUBDOMAIN ? SUBDOMAIN : (isset($this->request['branding']) ? $this->request['branding'] : 'common');
        $this->page_name = get_query_var('name');
        $this->page_name = get_the_ID();
        $this->page_type = 'search';
        $this->search_page_url = get_permalink();
        $this->OER_page_url = get_bloginfo('url') . '/';
        $this->current_user = wp_get_current_user();
        $this->partnerid = $this->partnerid ? $this->partnerid : 1;

    }

    public function curriki_module_page_layout() {
        //* Force full-width-content layout setting
        add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');

        remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');
        remove_action('genesis_loop', 'genesis_do_loop');

        // OR

        if (($this->partnerid != 1 OR $this->branding != 'common') AND $this->branding != 'curriki') {
            //remove header
//            if($this->branding != 'students' && $this->branding != 'studentsearch' && $this->branding != 'search'){
                remove_action('genesis_header', 'genesis_header_markup_open', 5);
                remove_action('genesis_header', 'genesis_do_header');
                remove_action('genesis_header', 'genesis_header_markup_close', 15);
//            }

            //remove navigation
            remove_action('genesis_after_header', 'genesis_do_nav');
            remove_action('genesis_after_header', 'genesis_do_subnav');

            //Remove footer
            remove_action('genesis_footer', 'genesis_footer_markup_open', 5);
            remove_action('genesis_footer', 'genesis_do_footer');
            remove_action('genesis_footer', 'genesis_footer_markup_close', 15);

            //* Remove the entry footer markup (requires HTML5 theme support)
            remove_action('genesis_before_footer', 'genesis_footer_widget_areas');
            remove_action('genesis_entry_footer', 'genesis_entry_footer_markup_open', 5);
            remove_action('genesis_entry_footer', 'genesis_entry_footer_markup_close', 15);
            
            
        }
    }

    public function curriki_module_page_body_class($classes) {
        
        $classes[] = 'page-module';        
        return $classes;
    }

    public function curriki_module_page_scripts() {

        wp_enqueue_style('qtip-css', get_stylesheet_directory_uri() . '/js/qtip2_v2.2.1/jquery.qtip.min.css', null, false, 'all'); // Add the styles first, in the <head> (last parameter false, true = bottom of page!)
        wp_enqueue_script('qtip-js', get_stylesheet_directory_uri() . '/js/qtip2_v2.2.1/jquery.qtip.min.js', array('jquery'), false, true); // Not using imagesLoaded? :( Okay... then this.

        wp_enqueue_style('lti-front-module-css', get_stylesheet_directory_uri() . '/modules/lti-front/css/style.css', null, false, 'all');
        
        
        wp_register_script('lti-front-module-script',get_stylesheet_directory_uri() . '/modules/lti-front/js/script.js', array('jquery'), false, true);
        $translation_array = array( 'tootip_heading' => __('Search Tips & Advance Features', 'curriki') );
        wp_localize_script('lti-front-module-script', 'tootip_ml_obj', $translation_array);
        wp_enqueue_script("lti-front-module-script");  
        //wp_enqueue_script('lti-front-module-script', get_stylesheet_directory_uri() . '/modules/lti-front/js/script.js', array('jquery'), false, true);

        echo "<script>";
        echo "var ajaxurl = '" . admin_url('admin-ajax.php') . "';";
        echo "var baseurl = '" . get_bloginfo('url') . "/';";
        echo "</script>";
    }

    public function curriki_module_page_header() {
        get_template_part('modules/lti-front/brandings/' . $this->branding . '/header');
    }

    public function curriki_module_page_body() {
        ?>        
                <?php                                                                                
                    if( isset($this->action) && $this->action !== null && file_exists(get_stylesheet_directory()."/modules/lti-front/views/{$this->action}.php" ) )
                    {
                        get_template_part('modules/lti-front/views/'.$this->action);
                    }else{
                ?>                        
                        <?php get_template_part('modules/lti-front/views/credential-form'); ?>                                      
                <?php
                    }
                ?>        
        <?php
    }

    public function curriki_module_page_footer() {
        get_template_part('modules/lti-front/brandings/' . $this->branding . '/footer');
    }

    //Targeted Search Landing Page Functions
    public function curriki_module_targeted_init() {
        $this->curriki_module_page_init();
        $this->page_type = 'targeted';
        $this->meta = get_post_meta(get_the_ID());
        foreach ($this->meta as $key => $val)
            $this->meta[$key] = $val[0];

//        echo "<pre>";
//        print_r($this->meta);
//        die();
    }

    public function have_operators($string, $operators, $type) {
        $have = false;
        foreach ($operators as $op) {

            if ($type == 'AND') {
                if (stripos($string, $op)) {
                    $have = true;
                } else {
                    return false;
                }
            } else {
                if (stripos($string, $op)) {
                    return true;
                } else {
                    $have = false;
                }
            }
        }
        return $have;
    }

}
