<?php
/*
  Created on : Mar 21, 2016, 8:58:41 PM
  Author     : waqar-muneer
  Purpose    : to manage all advance analytics
 */

add_filter('body_class', 'curr_remove_body_class', 20, 2);
function curr_remove_body_class($wp_classes)
{
    if(bp_current_component())
    {               
        foreach($wp_classes as $key => $value)
        {
            if ($value == 'home') 
            {
                unset($wp_classes[$key]); 
                $wp_classes = array_values($wp_classes);
            }
        } 
    }    
    return $wp_classes;
}
 
function cur_banners()
{    
    if(is_front_page() && bp_current_component() == false)
    {        
        $handel = 'banners-style';
        $src = get_stylesheet_directory_uri() . '/modules/banners/css/style.css';   
        $deps = array(); $ver = false; $media = 'screen';
        
        wp_register_style($handel,$src,$deps,$ver,$media);        
        wp_enqueue_style($handel, $src,$deps,$ver,$media);        
        
        require_once 'views/banner.php';        
    }
}
//add_filter('genesis_before_header', 'cur_banners',20);
