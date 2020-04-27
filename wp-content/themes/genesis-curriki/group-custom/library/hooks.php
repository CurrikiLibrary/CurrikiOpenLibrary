<?php

function cur_bp_core_render_message_content($msg,$type)
{   
    
    global $bp;    
    $msg_replace_target = "<p>You have selected an image that is smaller than recommended. For best results, upload a picture larger than 150 x 150 pixels.</p>";
    if($type === "error" && trim($msg) === $msg_replace_target)
    {
        $msg = "<p>";
        $msg .= "Upload an image to use as an avatar for this group. The image will be shown on the main group page, and in search results. The image must be larger than 150 x 150.  If larger, you will have a chance to crop it after uploading.";
        
        if( bp_current_component() === "groups" && in_array("admin", $bp->unfiltered_uri) )
        {
            $msg .= ' <strong><a href="'.  site_url().'/'. join("/", $bp->unfiltered_uri).'/?t='.  time() .'">Upload New</a></strong>';
        }
        
        $msg .= "</p>";
    }   
    return $msg;
}
add_action('bp_core_render_message_content', 'cur_bp_core_render_message_content',20,2);