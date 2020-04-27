<?php

/*
 * Author: Ali Mehdi
 */
function unload_plugins_dequeue_script() {
//    print_r(get_post());
//    die('test');
    /*
     * Pages on which there are no need for plugin css / js
     */
    if (
            is_front_page() ||
            is_page('resources-curricula') ||
            is_page('search') ||
            is_page('browse-resource-library') ||
            is_page('featured-curriki-curated-collections') ||
            is_page('introduction-to-computational-thinking-pd') ||
            is_page('community')  ||
            is_page('about-curriki') ||
            is_page('impact') ||
            is_page('about-curriki/team') ||
            is_page('about-curriki/partners-sponsors') ||
            is_page('search-api')  ||
            is_page('about-curriki/media-center') ||
            is_page('about-curriki/awards') ||
            is_page('about-curriki/contact-us') ||
            is_page('about-curriki/careers') ||
            is_page('curriki-newsletter-sign-up-2') ||
            is_page('features') ||
            is_page('services') ||
            is_page('custom-curation') ||
            is_page('search-widgets') ||
            is_page('lti-compliance') ||
            is_page('blog') ||
            is_page('about-curriki/donate')  ||
            is_page('publish-resources') ||
            is_page('oer') 
        ) {
        wp_dequeue_style('bbpress_css');
        wp_dequeue_style('tablepress-default');
        wp_dequeue_style('jetpack_css');
        wp_dequeue_style('genericons');
        wp_dequeue_style('gconnect-bp');
        wp_dequeue_style('jetpack_css');
        wp_dequeue_style('bp-legacy-js');


        wp_deregister_style('tablepress-default');
        wp_deregister_style('jetpack_css');
        wp_deregister_style('genericons');
        wp_deregister_style('gconnect-bp');
        wp_deregister_style('jetpack_css');
        wp_deregister_style('bp-legacy-js');

        wp_dequeue_script('jquery-fancybox-js');
        wp_deregister_script('jquery-fancybox-js');

        wp_dequeue_script('bbpress-editor');
        wp_deregister_script('bbpress-editor');
        
        /*
         * If checking from google insights;
         */
        if (!isset($_SERVER['HTTP_USER_AGENT']) || stripos($_SERVER['HTTP_USER_AGENT'], 'Speed Insights') === false):
            add_filter( 'option_active_plugins', 'lg_disable_oa_plugin' );
            
        endif;

        /*
         * Putting defer attribute to javascript
         */

//        add_filter('script_loader_tag', function ( $tag, $handle ) {
//            if($handle == 'jquery-core' || $handle == 'jquery-migrate') {
//                return $tag;
//            }
//            return str_replace(' src', ' defer="defer" src', $tag);
//        }, 10, 2);
    }
}
function lg_disable_oa_plugin($plugins){
    
    $key = array_search( 'oa-social-login/oa-social-login.php' , $plugins );
    
    if ( false !== $key ) {
        unset( $plugins[$key] );
    }
    

    return $plugins;
}
add_action('wp_enqueue_scripts', 'unload_plugins_dequeue_script', 9999999999999999);
add_action('wp_footer', 'unload_plugins_dequeue_script', 9999999999999999);
add_action('bp_enqueue_scripts', 'unload_plugins_dequeue_script', 9999999999999999);


