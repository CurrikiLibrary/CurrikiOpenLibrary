<?php
/*
  Created on : Mar 21, 2016, 8:58:41 PM
  Author     : waqarmuneer
  Purpose    : to manage LTI module functionality
 */

require_once __DIR__."/../../core/Curriki_controller.php";

class CommunityPages extends Curriki_controller{

    //Misc Variables
    public $wpdb;    
    public $partnerid;
    public $current_language = "eng";  
    public $view_data = array();


    //Constructor
    public function __construct()
    {
        /*
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
          */     
        $this->loadclass("Repository/CommunitiesRepository");
        $this->loadclass("helpers/misc");
        
        if(!isset($_GET["action"]))
        {
            $_GET["action"] = "community_pages_index";
        }
        global $wpdb;
        $this->wpdb = $wpdb;
               
        $this->partnerid = (isset($this->request['partnerid']) AND ! empty($this->request['partnerid'])) ? intval($this->request['partnerid']) : 0;        
        $this->current_language = "eng";
        if( defined('ICL_LANGUAGE_CODE') )
            $this->current_language = cur_get_current_language(ICL_LANGUAGE_CODE);         
        
        parent::__construct();
    }
    
    public function community_pages_index() 
    {        
        $communitiesRepository = new CommunitiesRepository();
        $communitiesRepository->wpdb = $this->wpdb;        
        $community = $communitiesRepository->getCommunityPageByUrl($_GET["comm_url"]);
        if(!$community)
        {            
            wp_redirect( site_url() );
        }
        
        $community_anchors = $communitiesRepository->getCommunityAnchors($community->communityid);
                
        $sql_collections = "SELECT communityid,cc.resourceid,c.title as name,cc.displayseqno,pageurl as url,cc.image,cc.title,
                            (SELECT count(collectionelements.resourceid) FROM collectionelements  where collectionelements.collectionid = cc.resourceid) as no_of_resources
                            FROM community_collections cc
                            join resources c on cc.resourceid = c.resourceid where c.type = 'collection' and communityid=%d
                            order by cc.displayseqno asc
                            ";                            
        $community_collections = $this->wpdb->get_results( $this->wpdb->prepare( $sql_collections , $community->communityid ) );                  
        
        $sql_groups = "
                        SELECT communityid,groupid,g.name as name,displayseqno,slug as url FROM community_groups cg
                        join cur_bp_groups g on cg.groupid = g.id where communityid=%d ORDER BY displayseqno ASC
                      ";
        
        $community_groups = $this->wpdb->get_results( $this->wpdb->prepare( $sql_groups , $community->communityid ) );
        
        $this->view_data["community"] = $community;
        $this->view_data["community_anchors"] = $community_anchors;
        $this->view_data["community_collections"] = $community_collections;
        $this->view_data["community_groups"] = $community_groups;
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

        if (($this->partnerid != 1 || $this->branding != 'common') && $this->branding != 'curriki') {
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

        wp_enqueue_style('community-pages-module-css-google-font', 'https://fonts.googleapis.com/css?family=Open+Sans:400,400i,600,600i,700,700i', null, false, 'all');
        wp_enqueue_style('community-pages-module-css-bootstrap', get_stylesheet_directory_uri() . '/modules/community-pages/assets/css/bootstrap.css', null, false, 'all');
        wp_enqueue_style('community-pages-module-css-font-awesome', get_stylesheet_directory_uri() . '/modules/community-pages/assets/css/font-awesome.min.css', null, false, 'all');
        wp_enqueue_style('community-pages-module-css-style', get_stylesheet_directory_uri() . '/modules/community-pages/assets/css/style.css', null, false, 'all');
        wp_enqueue_style('community-pages-module-css', get_stylesheet_directory_uri() . '/modules/community-pages/css/style.css', null, false, 'all');
        wp_enqueue_style('community-pages-module-css-jquery-ui', '//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css', null, false, 'all');
        
        wp_enqueue_script('community-pages-module-script', get_stylesheet_directory_uri() . '/modules/community-pages/js/script.js', array('jquery'), false, true);
        wp_enqueue_script('community-pages-module-script-bootstrap', get_stylesheet_directory_uri() . '/modules/community-pages/assets/js/bootstrap.js', array('jquery'), false, true);
        wp_enqueue_script('community-pages-module-script-custom', get_stylesheet_directory_uri() . '/modules/community-pages/assets/js/custom.js', array('jquery'), false, true);
        wp_enqueue_script('community-pages-module-jquery-ui', '//code.jquery.com/ui/1.11.2/jquery-ui.js', array('jquery'), false, true);
        
        echo "<script>";
        echo "var ajaxurl = '" . admin_url('admin-ajax.php') . "';";
        echo "var baseurl = '" . get_bloginfo('url') . "/';";
        echo "</script>";
        
    }

    public function curriki_module_page_header() {
        get_template_part('modules/community-pages/brandings/' . $this->branding . '/header');
    }

    public function curriki_module_page_body() {
        ?>        
                <?php                                                          
                    if( isset($this->action) && $this->action !== null && file_exists(get_stylesheet_directory()."/modules/community-pages/views/{$this->action}.php" ) )
                    {
                        global $view_data;
                        $view_data = $this->view_data;
                        get_template_part('modules/community-pages/views/'.$this->action);
                    }
                ?>                                              
        <?php
    }

    public function curriki_module_page_footer() {
        get_template_part('modules/community-pages/brandings/' . $this->branding . '/footer');
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
