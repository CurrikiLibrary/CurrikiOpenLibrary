<?php 
function wpssi_load_custom_wp_admin_style() {

  wp_register_style( 'custom_wp_admin_css', plugin_dir_url( __FILE__ )  . 'admin/css/style.css', false, '1.0.0' );
  wp_register_style( 'font_css', plugin_dir_url( __FILE__ )  . 'admin/fonts/font.css', false, '1.0.0' );
  wp_register_style( 'font2_css', plugin_dir_url( __FILE__ )  . 'admin/fonts/font2.css', false, '1.0.0' );
  wp_register_style( 'font3_css', plugin_dir_url( __FILE__ )  . 'admin/fonts/font3.css', false, '1.0.0' );
  wp_register_style( 'hover_css', plugin_dir_url( __FILE__ )  . 'admin/css/hover.css', false, '1.0.0' );
  wp_register_style( 'fo_css', plugin_dir_url( __FILE__ )  . 'admin/css/font-awesome.min.css', false, '1.0.0' );
  wp_register_style( 'fontend_styling', plugin_dir_url( __FILE__ )  . 'public/css/style.css', false, '1.0.0' );

  wp_register_script( 'default', plugin_dir_url(__FILE__) . 'admin/js/default.js', array('jquery', 'jquery-ui-resizable', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-sortable', 'backbone'), '1.0', true );
//  wp_register_script( 'live_templates', plugin_dir_url(__FILE__) . 'admin/js/livetemplates.js', array('jquery'), '1.0', true );
  wp_register_script( 'ssi_script', plugin_dir_url(__FILE__) . 'public/js/ssi_script.js', array('jquery'), '1.0', true );

  wp_register_style( 'font_select', plugin_dir_url( __FILE__ )  . 'admin/css/fontselect.css', false, '1.0.0' );
  wp_register_script( 'gfonts', plugin_dir_url(__FILE__) . 'admin/js/jquery.fontselect.js', array('jquery'), '1.0', true );

  wp_enqueue_style('font_select' );
  wp_enqueue_script('gfonts' );
  
  wp_enqueue_style( 'custom_wp_admin_css' );
  wp_enqueue_style( 'fo_css' );
  wp_enqueue_style('font_css' );
  wp_enqueue_style('font2_css' );
  wp_enqueue_style('font3_css' );
  wp_enqueue_style('hover_css' );
  wp_enqueue_style('fontend_styling' );
  
  
  wp_enqueue_script('default' );
  wp_enqueue_script('live_templates' );
  wp_enqueue_script('ssi_script' );
  
}

add_action( 'admin_enqueue_scripts', 'wpssi_load_custom_wp_admin_style' );
add_action('wp_enqueue_scripts', 'wpssi_load_custom_wp_admin_style');

?>