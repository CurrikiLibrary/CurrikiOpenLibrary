<?php
    /**
    Plugin Name: Landing pages leads analytics SEO content
    Description: Create landing pages for your website to increase the sales number.
    Version: 1.0.3
    */
    //add_action('widgets_init', 'init_form_application');

    require_once 'plugin.php';
    require_once 'inc/functions.php';
   
    add_action( 'the_post', 'post_form_check' );
    add_action( 'the_post', array('landing_page_wb', 'show_landin_page_form_post') );

    add_shortcode( 'shotcode_form_application',  array('landing_page_wb', 'form_show') );

    add_action('admin_print_scripts', array('landing_page_wb', 'print_scripts'));
    
    register_activation_hook( __FILE__, 'activate_plugin_application');
    register_deactivation_hook( __FILE__, 'deactivate_plugin_application');
    
    add_action('init', array('landing_page_wb', 'send'));
    add_action('admin_menu', array('landing_page_wb', 'menu')); 
    add_action('admin_post_save_post_title', array('landing_page_wb', 'save_post_title') );
    add_action( 'save_post', array('landing_page_wb', 'save_post_title'), 10, 3 );
    
    add_filter( 'wp_footer', array("landing_page_wb", "clear_page_application"));
    add_filter( 'edit_form_top', array("landing_page_wb", "edit_form") );
    add_filter( 'manage_edit-post_columns', 'header_menu');
    add_action( 'manage_post_posts_custom_column', array("landing_page_wb", "filter_page") );
   
    

?>