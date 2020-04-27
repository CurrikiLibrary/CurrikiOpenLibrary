<?php
namespace CurrikiLti\WP;
use CurrikiLti\Core\Services\Lti\LtiLauncher;

class Bootstrap
{
    private static $instance = null;
    private $lti_launcher = null;

    public function __construct()
    {
        if(!session_id()) {
            session_start();            
        }          
    }

    public function initContainer()
    {
        $user = new \CurrikiLti\Core\Entity\User();        
        $user->id = 0;
        $user->name = '';
        if(is_user_logged_in()){
            $current_user = wp_get_current_user();
            $user->id = $current_user->data->ID;
            $user->name = $current_user->data->user_nicename;           
            $user->firstname = "First";
            $user->lastname = "Last";
            $user->email = $current_user->user_email;
            $user->username = $current_user->user_login;            
        }
                
        global $curriki_lti_instance;
        $lti_launcher = $curriki_lti_instance->get('CurrikiLti\Core\Services\Lti\LtiLauncher');
        $lti_launcher->setUser($user);
        $this->lti_launcher = $lti_launcher;                        
    }

    public static function getInstance()
    {
        if(self::$instance === null){
            self::$instance = new Bootstrap();
        }
        return self::$instance;
    }

    public static function pluginSetup()
    {                
        add_action('plugins_loaded', array(self::getInstance(),'init'));        
    }

    public function init()
    {           
        add_action('admin_menu', array(self::getInstance(),'wp_lti'));         
        add_shortcode( 'cur-lti-tool', array(self::getInstance(),'lti_tool_shortcode') );
        add_shortcode( 'cur-lti-launch', array(self::getInstance(),'lti_launch') );
        add_filter( 'page_template', array(self::getInstance(),'wp_lti_launch_template') );       
    }

    function wp_lti_launch_template( $template ) {
        global $post;        
        if( 'lti-launch' == $post->post_name ){
            $template = WP_PLUGIN_DIR . '/curriki-lti/src/views/page-lti-launch.php';            
        }            
        return $template;
    }

    public function wp_lti(){        
        add_menu_page( 'WP LTI (Learning Tools Interoperability)', 'WP LTI', 'manage_options', 'curriki-wp-lti', array(self::getInstance(),'wp_lti_page') );
    }

    public function wp_lti_page(){ 
        $this->initContainer();

        if( isset($_GET['action']) && isset($_GET['controller'])){
            $class = "CurrikiLti\\Core\\Controllers\\".ucwords($_GET['controller']);
            $controller = new $class();
            if(method_exists($controller,$_GET['action'])){
                call_user_func(array($controller,$_GET['action']));
            }            
        }else{
            require_once __DIR__.'/../views/tools_list.php';
        }             
    }

    public function lti_tool_shortcode($atts)
    { 
        if(!is_admin()){
            $id = 0;
            if(is_array($atts) && isset($atts['id'])){
                $id = $atts['id'];
            }                        
            $this->initContainer();
            return $this->lti_launcher->launch($id);                
        }                 
    }

    public function lti_launch()
    {  
        if(!is_admin()){
            $this->initContainer();
            return $this->lti_launcher->launchContent();
        }              
    }
}
