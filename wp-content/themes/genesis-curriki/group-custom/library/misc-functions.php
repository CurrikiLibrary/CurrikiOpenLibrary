<?php


function initialize_modals()
{
    $allowed_pages = array("oer");
    $pagename = get_query_var('pagename');        
    if(isset($pagename) && $pagename!=null && in_array($pagename, $allowed_pages))
    { 
        wp_enqueue_style('jquery-progress-css', get_stylesheet_directory_uri() . '/js/oer-custom-script/progress.css');
        wp_enqueue_script('jquery-progress-js', includes_url('js/jquery/ui/progressbar.min.js'), array('jquery'), false, true);
    }
    
    //======= For logged-in users ======
    if(is_user_logged_in())
    {
        initialize_complete_profile_modal();
    }else{
        //======= For non-logged-in users ======
        
    }
}

function initialize_complete_profile_modal()
{
    $pagename = get_query_var('pagename');
    $allowed_page_dashboard = array("dashboard");
    
    //echo "wqwqwq ==> ";
    //$_SESSION["complete_porfile_displayed"] = true;
    //var_dump();
        
    /*
    if( isset($pagename) && $pagename!=null && in_array($pagename, $allowed_page_dashboard) && !isset($_SESSION["complete_porfile_displayed"]) )
    {
        require_once( realpath(__DIR__ . "/../complete-profile-modal.php") );
    } 
     * 
     */   
        
    
    if( isset($pagename) && $pagename!=null && in_array($pagename, $allowed_page_dashboard) )
    {
        require_once( realpath(__DIR__ . "/../complete-profile-modal.php") );
    } 
     
}


add_action( 'wp_ajax_nopriv_cur_set_profile_complete_profile_modal_display', 'ajax_cur_set_profile_complete_profile_modal_display' ); 
add_action( 'wp_ajax_cur_set_profile_complete_profile_modal_display', 'ajax_cur_set_profile_complete_profile_modal_display'); 

function ajax_cur_set_profile_complete_profile_modal_display() 
{
    $complete_profile_modal_display = get_user_meta(get_current_user_id(),"complete_profile_modal_display",true);    
    
    if(isset($complete_profile_modal_display))
    {        
        update_user_meta( get_current_user_id(), "complete_profile_modal_display", current_time("mysql") );
    }else{        
        add_user_meta(get_current_user_id(), "complete_profile_modal_display", current_time("mysql") );
    }    
    $d = get_user_meta(get_current_user_id(),"complete_profile_modal_display",true);    
    echo $d;
}