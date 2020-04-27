<?php
/*
  Created on : Mar 21, 2016, 8:58:41 PM
  Author     : furqanaziz
  Purpose    : to manage search module functionality
 */

require_once __DIR__."/../../core/Curriki_controller.php";

class Page404 extends Curriki_controller{

    //Misc Variables
    public $wpdb;    
    public $partnerid;
    public $current_language = "eng";    
    
    //Constructor
    public function __construct()
    {        
        
        if(!isset($_GET["action"]))
        {
            $_GET["action"] = "404";
        }
        
        parent::__construct();
        
        global $wpdb;
        $this->wpdb = $wpdb;
        
        $this->partnerid = (isset($this->request['partnerid']) AND ! empty($this->request['partnerid'])) ? intval($this->request['partnerid']) : 0;
        $this->current_language = "eng";                
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

        wp_enqueue_style('404-module-css', get_stylesheet_directory_uri() . '/modules/404/css/style.css', null, false, 'all');
        
        
        wp_register_script('404-module-script',get_stylesheet_directory_uri() . '/modules/404/js/script.js', array('jquery'), false, true);
        $translation_array = array( 'tootip_heading' => __('Search Tips & Advance Features', 'curriki') );
        wp_localize_script('404-module-script', 'tootip_ml_obj', $translation_array);
        wp_enqueue_script("404-module-script");  
        //wp_enqueue_script('404-module-script', get_stylesheet_directory_uri() . '/modules/404/js/script.js', array('jquery'), false, true);

        echo "<script>";
        echo "var ajaxurl = '" . admin_url('admin-ajax.php') . "';";
        echo "var baseurl = '" . get_bloginfo('url') . "/';";
        echo "</script>";
    }

    public function curriki_module_page_header() {
        get_template_part('modules/404/brandings/' . $this->branding . '/header');
    }

    public function curriki_module_page_body() {
        ?>

        <div class="lti-content" >            
            <div class="wrap container_12" >
                
                <?php                                                                                
                    if( isset($this->action) && $this->action !== null && file_exists(get_stylesheet_directory()."/modules/404/views/{$this->action}.php" ) )
                    {
                        get_template_part('modules/404/views/'.$this->action);
                    }else{
                ?>                        
                        <?php get_template_part('modules/404/views/index'); ?>
                <?php
                    }
                ?>
            </div>
        </div>

        <?php
    }

    public function curriki_module_page_footer() {
        get_template_part('modules/404/brandings/' . $this->branding . '/footer');
    }

    //Targeted Search Landing Page Functions
    public function curriki_module_targeted_init() {
        $this->curriki_module_page_init();
        $this->page_type = 'targeted';
        $this->meta = get_post_meta(get_the_ID());
        
        if(is_array($this->meta))
        {
            foreach ($this->meta as $key => $val)
            {
                $this->meta[$key] = $val[0];
            }
        }        
        
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
