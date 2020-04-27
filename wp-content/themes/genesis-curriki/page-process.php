<?php
/*
* Template Name: Process Page Template
*
* Child Theme Name: Curriki Child Theme for Genesis 2.1
* Author: Orange Blossom Media
* Url: http://orangeblossommedia.com/
*/

//if(!is_user_logged_in() and function_exists('curriki_redirect_login')){curriki_redirect_login();die;}
if($_SERVER['HTTP_REFERER'] != site_url()."/"){curriki_redirect(site_url());die;}

add_filter( 'genesis_header', 'curriki_genesis_header_class_card' );
function curriki_genesis_header_class_card( $classes ) {
    ?>
    <style type="text/css">
        .card{
            min-width: 0px !important;
        }
    </style>
    <?php
}

// Add custom body class to the head
add_filter( 'body_class', 'curriki_user_dashboard_add_body_class' );
function curriki_user_dashboard_add_body_class( $classes ) {
   $classes[] = 'backend user-dashboard process-page';
   return $classes;
}

// Execute custom style guide page
add_action( 'genesis_meta', 'curriki_custom_user_dashboard_loop' );
function curriki_custom_user_dashboard_loop() {
	//* Force full-width-content layout setting
	add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

	remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
	remove_action( 'genesis_loop', 'genesis_do_loop' );

	add_action( 'genesis_loop', 'curriki_process_body', 15 );
        add_action('genesis_before', 'curriki_process_scripts');
}

function curriki_process_scripts() {

  // Enqueue JQuery Tab and Accordion scripts
  wp_enqueue_script('jquery-ui-tabs');
  wp_enqueue_style('resource-font-awesome-css', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
  wp_enqueue_style('resource-legacy-css', get_stylesheet_directory_uri() . '/css/legacy.css');

  wp_enqueue_style('jquery-fancybox-css', get_stylesheet_directory_uri() . '/js/fancybox_v2.1.5/jquery.fancybox.css?v=2.1.5');
  wp_enqueue_script('jquery-fancybox-js', get_stylesheet_directory_uri() . '/js/fancybox_v2.1.5/jquery.fancybox.pack.js?v=2.1.5', 'jquery.fancybox', '2.1.5');
  wp_enqueue_script('jquery-mousewheel-js', get_stylesheet_directory_uri() . '/js/fancybox_v2.1.5/jquery.mousewheel-3.0.6.pack.js', 'jquery.mousewheel', '3.0.6');
  ?>
    <style type="text/css">
        .message_para
        {
            border: 0px !important;
            width: 100% !important;
            text-align: center !important;
        }
        .main-wrapper
        {
            min-height: 500px !important;
        }
    </style>
    
    <script type="text/javascript">
        (function (jQuery) {
            jQuery(document).ready(function(){
                    var cntr = 5;
                    var stop = false;
                    setInterval(function(){                                                 
                        if( stop == false)
                        {
                            var btn = '<button onclick="gotodashboard();" class="green-button" id="addtolibrary">Go to Dashboard</button>';
                            jQuery(".message_para").html(" You will be redirected to dashboard after "+cntr+" seconds. <br /> "+btn);
                            if(cntr == 1)
                            {
                                window.location = "<?php echo site_url() ?>/dashboard";
                                stop = true;
                            }else                                                
                            {
                                cntr--;
                            }
                        }
                    }, 1000);
                    
            });
        }(jQuery));
        
        function gotodashboard()
        {
            window.location = "<?php echo site_url() ?>/dashboard";
        }
    </script>        
  <?php
}


function curriki_process_body() {
    
    ?> 
    <!-- Google Code for Registered User Conversion Page -->
    <script type="text/javascript">
    /* <![CDATA[ */
    var google_conversion_id = 1066533164;
    var google_conversion_language = "en";
    var google_conversion_format = "3";
    var google_conversion_color = "ffffff";
    var google_conversion_label = "ot5lCPW072AQrILI_AM";
    var google_remarketing_only = false;
    /* ]]> */
    </script>
    <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>
    <noscript>
        <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/1066533164/?label=ot5lCPW072AQrILI_AM&guid=ON&script=0"/>
        </div>
    </noscript>


    <?php
                $dashboard = '<div class="resource-content clearfix">';
                        $dashboard .= '<div class="wrap main-wrapper container_12 ">';
                            $dashboard .= "<div class='message_para'></div>";
                        $dashboard .= '</div>';
		$dashboard .= '</div>';		                
                
		echo $dashboard;	                
}


genesis();