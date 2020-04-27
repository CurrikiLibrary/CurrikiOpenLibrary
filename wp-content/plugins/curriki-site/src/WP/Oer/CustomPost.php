<?php
namespace CurrikiSite\WP\Oer;
use CurrikiSite\Core\Singleton;

/**
 * Description of CustomPost
 *
 * @author waqarmuneer
 */

class CustomPost {
    
    use Singleton;
    
    function __construct(){          
        add_action( 'init', array($this,'register') );
        add_filter( 'query_vars', array($this,'queryVars') );
        add_filter( 'template_include', array($this,'templateInclude') );        
    }
    
    public function register() {
        $labels = array(
            'name'               => __( "Oer", 'post type general name' ),
            'singular_name'      => __( 'Oer', 'post type singular name' )
        );


        $args = array(
          'labels'        => $labels,
          'description'   => "Curriki's Open Educational Resource",
          'public'        => true,
          'menu_position' => 5,
          'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
          'has_archive'   => true,
          'query_var'          => true,
          'capability_type' => 'post',
          'rewrite' => array('slug' => OER_MODULE_SLUG),
        );  

        register_post_type( OER_MODULE_SLUG , $args ); 
    }
    
    public function queryVars($vars) {        
        $vars[] = 'pageurl';
        $vars[] = 'resourceid';          
        return $vars;
    }
    
    public function templateInclude($template) {        
        global $wp_query;
        $query_vars = is_array($wp_query->query_vars) ? $wp_query->query_vars : [];    
        if( isset($query_vars['post_type']) && $query_vars['post_type'] === OER_MODULE_SLUG){                
            return $this->curriki_site_oer_get_template_hierarchy( 'oer' );
        } else {    
            return $template;
        }
    }
    
    private function curriki_site_oer_get_template_hierarchy( $template ) {
 
        // Get the template slug
        $template_slug = rtrim( $template, '.php' );
        $template = $template_slug . '.php';        
        $file = "single";
        
        // Check if a custom template exists in the theme folder, if not, load the plugin template file
        if ( $theme_file = locate_template( array( CURRIKI_SITE_NAME.'/' . $template ) ) ) {
            $file = $theme_file;
        }
        else {                         
            $plugins_url = plugin_dir_path( realpath( dirname(__FILE__ ) . '/../../' ) );            
            $path_to_templates = 'resources/templates/';
            $file = $plugins_url . $path_to_templates . $template;
        }    
        return apply_filters( 'curriki_site_oer_template_' . $template, $file );        
    }
}
