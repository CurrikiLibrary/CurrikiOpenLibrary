<?php
/*
  Created on : Mar 21, 2016, 8:58:41 PM
  Author     : waqarmuneer
  Purpose    : to manage LTI module functionality
 */

require_once __DIR__."/../../core/Curriki_controller.php";
require_once realpath(__DIR__.'/../..') . '/common.php';

class Lti_lms_modal extends Curriki_controller{

    //Misc Variables
    public $wpdb;    
    public $partnerid;
    public $current_language = "eng";    
    
    //Constructor
    public function __construct()
    { 
        if(!class_exists("TPMiscHelper"))
        {
            $this->loadHelper("tp_misc_helper");                
        }
        if(!class_exists("ToolConsumer"))
        {
            $this->loadclass("lti-core/ToolConsumer");
        }
        
        if(!isset($_GET["action"]))
        {
            $_GET["action"] = "lti_lms_modal";
        }
        parent::__construct();
        
        global $wpdb;
        $this->wpdb = $wpdb;
               
    }     
    
     public function loadHelper($filename) {
        $this->loadclass($filename);         
    }    
    public function loadclass($filename) {
        return get_template_part('modules/lti-front/classes/'.$filename);
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
    
    public function getLtiIntegrationLinks()
    {
        $lms_links = "";
        $consumer = new ToolConsumer();
        $lms_records = $consumer->getAllCredentialsByUserId(get_current_user_id());

        $canvas = new stdClass();
        $canvas->src = CDN_UPLOAD_DIR."/2017/03/10161922/canvas-photo-300x300.jpg";
        $canvas->width = 130;

        $moodle = new stdClass();
        $moodle->src = CDN_UPLOAD_DIR."/2017/03/10161954/moodle-logo-300x77.png";
        $moodle->width = 200;

        $blackboard = new stdClass();
        $blackboard->src = CDN_UPLOAD_DIR."/2017/03/10162032/blackboard-logo.jpg";
        $blackboard->width = 195;

        $sakai = new stdClass();
        $sakai->src = CDN_UPLOAD_DIR."/2017/03/22161705/sakai-300x182.png";
        $sakai->width = 195;

        $desire2learn = new stdClass();
        $desire2learn->src = CDN_UPLOAD_DIR."/2017/03/22161659/d2l-300x158.jpg";
        $desire2learn->width = 195;

        $logos = array("canvas"=>$canvas,"moodle"=>$moodle,"blackboard"=>$blackboard,"sakai"=>$sakai,"desire2learn"=>$desire2learn);

        foreach ( $lms_records as $k=>$lmr )
        {
            if( array_key_exists($lmr->lms, $logos) )
            {
                $lms_links .= '
                <a class="lms-select-link '.$lmr->lms.'-link '.$lmr->lms.'-link-widget lms-select-link-widget" href="'. site_url(). '/manage-lti/?action=list_keys&lms='.$lmr->lms.'&t='.time().'">
                        <div class="crop-icn-'.$lmr->lms.'">
                            <img width="'.$logos[$lmr->lms]->width.'" alt="'.$lmr->lms.'-logo" src="'.$logos[$lmr->lms]->src.'" />
                        </div>        
                    </a>
                ';
            }                                                    
        }
        
        if(count($lms_records) === 0)
        {
            $lms_links = '<p class="no_lms_msg">No LMS added</p>';
        }
        
        return $lms_links;
    }
    
    public function curriki_module_page_body() {    
                if( isset($this->action) && $this->action !== null && file_exists(get_stylesheet_directory()."/modules/lti-front/views/{$this->action}.php" ) )
                {
                    get_template_part('modules/lti-front/views/'.$this->action);
                }else{
                    get_template_part('modules/lti-front/views/index');                
                }                              
    }

    public function curriki_module_page_scripts() {
        
        wp_enqueue_style('lti-front-module-style', get_stylesheet_directory_uri() . '/modules/lti-front/css/style.css', null, false, 'all');        
        wp_register_script('lti-front-module-script',get_stylesheet_directory_uri() . '/modules/lti-front/js/lti_lms_modal.js', array('jquery'), false, true);
        wp_enqueue_script("lti-front-module-script");         
    }
    
    //Targeted Search Landing Page Functions
    public function curriki_module_targeted_init() {
        $this->curriki_module_page_init();
        $this->page_type = 'targeted';
        $this->meta = get_post_meta(get_the_ID());
        foreach ($this->meta as $key => $val)
            $this->meta[$key] = $val[0];

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
