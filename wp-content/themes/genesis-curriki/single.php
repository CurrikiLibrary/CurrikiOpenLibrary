<?php 
/*
* Single Post Template
*
* Child Theme Name: Curriki Child Theme for Genesis 2.1
* Author: Orange Blossom Media
* Url: http://orangeblossommedia.com/
*/



function cur_jetpack_open_graph_tags_singlepage( $tags ) {
    
    if ( is_singular() )
    {
        global $post;
        
        $current_language = "eng";
        $current_language_slug = "";
        if( defined('ICL_LANGUAGE_CODE') )
        {
            $current_language = cur_get_current_language(ICL_LANGUAGE_CODE); 
            $current_language_slug = ( ICL_LANGUAGE_CODE == "en" ? "":"/".ICL_LANGUAGE_CODE );
        }

        //$resource_url = site_url(). $current_language_slug.'/oer/' . $resourceUser['pageurl'] ;
        $resource_url = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

        $tags['og:url'] = esc_url( $resource_url );
        $tags['og:type'] = "website";
        $tags['og:image'] = site_url()."/wp-content/themes/genesis-curriki/images/device-icons/ios/curriki-01_180.png";                                                        
        $description = stripcslashes(get_post_meta($post->ID, '_aioseop_description', true));
        $tags['og:description'] = $description ;            
    }
        
    return $tags;
}
add_filter( 'jetpack_open_graph_tags' , 'cur_jetpack_open_graph_tags_singlepage' );
 

/************* CUSTOMIZE THE POST INFO *************/

// Remove the post info function and/or reposition
//remove_action( 'genesis_entry_header', 'genesis_post_info' 12 );
//add_action( 'genesis_entry_header', 'genesis_post_info', 12 );

// Customize the post info function
// add_filter( 'genesis_post_info', 'post_info_filter' );
function post_info_filter($post_info) {
	$post_info = '[post_date] by [post_author_posts_link] | [post_comments] [post_edit]';
	return $post_info;
}

/************* CUSTOMIZE THE POST META *************/

// Remove the post meta function and/or reposition 
//remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
//add_action( 'genesis_entry_header', 'genesis_post_meta' );

// Customize the post meta function 
//add_filter( 'genesis_post_meta', 'post_meta_filter' );
function post_meta_filter($post_meta) {
	$post_meta = '[post_categories before="Filed Under: "] [post_tags before="Tagged: "]';
	return $post_meta;
}


/************* OTHER POST FUNCTIONS *************/

// Add post navigation *note HTML5 Support needs to be on in functions.php*
add_action( 'genesis_after_entry_content', 'genesis_prev_next_post_nav', 5 );


genesis();