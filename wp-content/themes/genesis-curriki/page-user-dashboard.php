<?php
/*
* Template Name: User Dashboard Template
*
* Child Theme Name: Curriki Child Theme for Genesis 2.1
* Author: Orange Blossom Media
* Url: http://orangeblossommedia.com/
*/

if(!is_user_logged_in() and function_exists('curriki_redirect_login')){curriki_redirect_login();die;}

get_template_part('modules/lti-front/classes/lti_lms_modal');
$lti_lms_modal = new Lti_lms_modal();
$lti_lms_modal->curriki_module_targeted_init();
$lti_lms_modal_links = $lti_lms_modal->getLtiIntegrationLinks();    

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
   $classes[] = 'backend user-dashboard';
   return $classes;
}

// Execute custom style guide page
add_action( 'genesis_meta', 'curriki_custom_user_dashboard_loop' );
function curriki_custom_user_dashboard_loop() {
	//* Force full-width-content layout setting
	add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

	remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
	remove_action( 'genesis_loop', 'genesis_do_loop' );

    add_action( 'genesis_loop', 'curriki_user_dashboard_body', 15 );
    add_action('genesis_before', 'curriki_user_dashboard_scripts');
}

function curriki_user_dashboard_scripts() {
    // Enqueue JQuery Tab and Accordion scripts
    wp_enqueue_script('owl-carousel-js', get_stylesheet_directory_uri() . '/js/user-dashboard/owl.carousel.min.js', array('jquery'), false, true);
    wp_enqueue_script('user-dashboard-js', get_stylesheet_directory_uri() . '/js/user-dashboard/user-dashboard.js', array('jquery'), false, true);
    wp_enqueue_style('owl-carousel-css', get_stylesheet_directory_uri() . '/css/user-dashboard/owl.carousel.min.css');
    wp_enqueue_style('dashboard-css', get_stylesheet_directory_uri() . '/css/user-dashboard/dashboard.css');
}

function curriki_user_dashboard_body() {
        global $wpdb;

        $current_user = wp_get_current_user();

        $current_language = "eng";
        $current_language_slug = "";
        if( defined('ICL_LANGUAGE_CODE') )
        {
            $current_language = cur_get_current_language(ICL_LANGUAGE_CODE); 
            $current_language_slug = ( ICL_LANGUAGE_CODE == "en" ? "":"/".ICL_LANGUAGE_CODE );
        }
        
        $q_user = "SELECT * FROM users WHERE userid='".get_current_user_id()."'";
        $user = $wpdb->get_row($q_user);
        $q_user_nicename = "SELECT user_nicename FROM cur_users WHERE ID='".get_current_user_id()."'";
        $user_nicename = $wpdb->get_var($q_user_nicename);
		// Access
		$dashboard = '<div class="resource-content clearfix">';
                        
                        if($user->firstname == '')$user->firstname = "First Name";
                        if($user->lastname == '')$user->lastname = "Last Name";
                        if($user->city == '')$user->city = "City";
                        if($user->state == '')$user->state = "State";else $user->state = $user->state;
                        if($user->country == '')$user->country = "Country";else $user->country = strtoupper($user->country);
                        if($user->membertype == '')$user->membertype = "Member Type";
                        if($user->bio == '')$user->bio = "This is a description of your BIO.";
                        
                        $dashboard .= '<div class="welcome-bar">';
                            $dashboard .= '<div class="wrap container_12">';
                                $dashboard .= '<div class="welcome-text">';
                                    $dashboard .= '<p>Welcome, '.$user->firstname.' '.$user->lastname.'!</p>';
                                $dashboard .= '</div>';
                            $dashboard .= '</div>';
                        $dashboard .= '</div>';
                        
                        $dashboard .= '<div class="wrap container_12">';
                            $dashboard .= '<div class="grid_3 grid_mx">';
                                $dashboard .= '<div class="info-column">';
                                    $dashboard .= '<div class="user-profile-info info-card">';
                                        $dashboard .= '<div class="user-profile-image">';
                                    if(empty($user->uniqueavatarfile)){
                                        $profile = get_user_meta(get_current_user_id(),"profile",true);
                                        $profile = isset($profile) ? json_decode($profile) : null; 
                                        $gender_img = isset($profile) ? "-".$profile->gender : "";
                                        $dashboard .= '<img src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample'.$gender_img.'.png" width="48" height="48" alt="pic">';
                                    }else{
                                        $dashboard .= '<img src="https://' . DBI_AWS_S3_CDN_URL . '//avatars/'.$user->uniqueavatarfile.'" width="48" height="48" alt="pic">';
                                    }

                                    // $dashboard .= '<div class="user-online-indicator is-online"></div>';

                                $dashboard .= '</div>';

                                        // $dashboard .= '<div class="profile-complete"><a href="'.  site_url() . $current_language_slug .'/edit-profile">'.__('Edit','curriki').'</a></div>';
                                $dashboard .= '<div class="user-profile-label">';
                                        
                                        $q_usa_ml = cur_countries_query($current_language,$user->country);
                                        $usa_ml_obj = $wpdb->get_row($q_usa_ml);                                        
                                        
                                        $country_profile = cur_convert_to_utf_to_html($usa_ml_obj->displayname);
                                        
                                        $q_states = cur_states_query($current_language,$user->state);
                                        $state_obj = $wpdb->get_row($q_states);                                        
                                        $state_profile = cur_convert_to_utf_to_html($state_obj->state_name);
                                        
                                        $membertype = "";
                                        if($user->membertype === "professional")
                                            $membertype = "Professional";
                                        if($user->membertype === "student")
                                            $membertype = "Studentl";
                                        if($user->membertype === "teacher")
                                            $membertype = "Teacher";
                                        if($user->membertype === "administration")
                                            $membertype = "School/District Administrator";
                                        if($user->membertype === "nonprofit")
                                            $membertype = "Non-profit Organization";
                                        
                                        $dashboard .= '<div class="username-line">';
                                        $dashboard .= $user->firstname.' '.$user->lastname;
                                        $dashboard .= '</div>';

                                        $dashboard .= '<div class="user-oneliner">';
                                        $dashboard .= $membertype.' · '.$user->city.', <br>'.$state_profile.', '.$country_profile;
                                        $dashboard .= '</div>';
                                        // $dashboard .= '<div class="user-oneliner">';
                                        // $dashboard .= $user->bio;
                                        // $dashboard .= '</div>';
                                    
                                    $dashboard .= '</div>';

                                    $dashboard .= '<a href="' . site_url() . $current_language_slug . '/edit-profile" class="user-profile-edit"><i class="fa fa-edit"></i></a>';
                                $dashboard .= '</div>';


                                $dashboard .= '<nav class="info-card">';
                                    $dashboard .= '<ul class="info-list">';
                                        $dashboard .= '<li>';
                                            $dashboard .= '<a class="active"><i class="fa fa-angle-right"></i> Dashboard</a>';

                                            if (in_array("content_creator", $current_user->roles) || (isset($current_user->caps['administrator']))) {
                                                $dashboard .= '<ul class="info-sublist">';
                                                    $dashboard .= '<li><a href="' . get_bloginfo('url') . '/my-library">'.__('My Resource Library','curriki').' <i class="fa fa-angle-right"></i></a></li>';
                                                $dashboard .= '</ul>';
                                                $dashboard .= '<ul class="info-sublist">';
                                                    $dashboard .= '<li><a href="' . get_bloginfo('url') . '/create-resource">'.__('Contribute a Resource','curriki').' <i class="fa fa-angle-right"></i></a></li>';
                                                    $dashboard .= '<li><a href="' . get_bloginfo('url') . '/create-resource/?type=collection">'.__('Build a collection','curriki').' <i class="fa fa-angle-right"></i></a></li>';
                                                $dashboard .= '</ul>';
                                            }

                                            $dashboard .= '<ul class="info-sublist">';
                                                $dashboard .= '<li><a href="' . site_url() . $current_language_slug . '/edit-profile">My Profile <i class="fa fa-angle-right"></i></a></li>';
                                                $dashboard .= '<li><a class="text-danger class-header-menu-logout" href="#">Logout</a></li>';
                                            $dashboard .= '</ul>';
                                        $dashboard .= '</li>';
                                    $dashboard .= '</ul>';
                                $dashboard .= '</nav>';

                            $dashboard .= '</div>';


                            $dashboard .= '<div class="side-banner">';
                                $dashboard .= '<a href="#"><img src="' . get_stylesheet_directory_uri() . '/images/search-page/birdies_for_education.jpg" width="373" height="284" alt="Birdies For Education"></a>';
                            $dashboard .= '</div>';

                        $dashboard .= '</div>';

                        $dashboard .= '<div class="grid_9 grid_mx">';
                            $dashboard .= '<div class="widecolumn">';
                            
                                    $dashboard .= '<div class="section-row">';
                                        $dashboard .= '<h2 class="section-title">'.__("What's New in Curriki",'curriki').'</h2>';
                                        if(function_exists('curriki_show_featured_item'))
                                        {
                                            $dashboard .= curriki_show_featured_item('dashboardresource');
                                        }
                                    $dashboard .= '</div>';


                                    $dashboard .= '<div class="section-row">';
                                        $dashboard .= '<h2 class="section-title">'.__('Recent Articles','curriki').'</h2>';
                                        $dashboard .= '<div class="owl-carousel owl-theme2">';

                                        // Adding three articles
                                        $the_query = new WP_Query( array(
                                            'posts_per_page' => 4,
                                        ));

                                        if ( $the_query->have_posts() ) {
                                            while ( $the_query->have_posts() ) {
                                                $the_query->the_post(); 
                                                $title = get_the_title();
                                                $content = wp_trim_words(strip_shortcodes(get_the_content()),30 ); ;
                                                $link = get_the_permalink();
                                                $thumbnail_id = get_post_thumbnail_id( $the_query->post->ID );
                                                $image = wp_get_attachment_image_src( $thumbnail_id, 'single-post-thumbnail' );
                                                $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
                                                $date = get_the_date();
                                                $author = get_the_author();
                                                $image_link='';
                                                if($image){
                                                    $image_link = $image[0];
                                                } else {
                                                    $image_link = get_site_url().'/wp-content/themes/genesis-curriki/images/CurrikiLogo_2x.jpg';
                                                }

                                                $dashboard.=<<<EOD
                                                    <div class="item">
                                                        <article class="recent-article">
                                                            <a class="article-thumbnail-link" href="{$link}">
                                                                <img class="article-thumbnail"src="{$image_link}" width="241" height="308" alt="{$alt}">
                                                            </a>
                                                            <div class="article-body">
                                                                <div class="article-meta">{$date} by {$author}</div>
                                                                <h4 class="article-title"><a href="{$link}">{$title}</a></h4>
                                                                <p>{$content} <a href="{$link}">Read More</a> </p>
                                                            </div>
                                                        </article>
                                                    </div>
EOD;
                                            }

                                            wp_reset_postdata(); 
                                        } else {
                                            $dashboard .= __('No News'); 
                                        }

                                        $dashboard .= '</div>';
                                    $dashboard .= '</div>';


                                    $dashboard .= '<div class="section-row">';
                                        $dashboard .= '<h2 class="section-title">'.__('Our Featured Collections','curriki').'</h2>';
                                        if(function_exists('curriki_show_featured_item'))
                                        {
                                            $dashboard .= curriki_show_featured_item('homepagecollection');
                                        }
                                    $dashboard .= '</div>';


                                    $dashboard .= '<div class="section-row">';
                                        $dashboard .= '<h2 class="section-title">'.__('Lessons by Education Level','curriki').'</h2>';

                                        $theme_url = get_stylesheet_directory_uri();
	                                    $home_url = get_bloginfo('url');
                                        $search_url = $home_url.'/search?size=10&type=Resource&phrase=&language=&start=0&partnerid=1&searchall=&viewer=&branding=common&sort=rank1+desc&size=10';
                                        $dashboard.=<<<EOD
                                        <div class="owl-carousel owl-theme">
                                            <a class="item level-box" href="{$search_url}&educationlevel%5B0%5D=K%7CPre-K">
                                                <img src="{$theme_url}/images/home-page/pre-k.jpg" width="303" height="219" alt="Preschool">
                                                <div class="overlay-content">
                                                    <h4 class="overlay-title">Preschool</h4>
                                                </div>
                                            </a>
                                            <a class="item level-box" href="{$search_url}&educationlevel%5B1%5D=1%7C2">
                                                <img src="{$theme_url}/images/home-page/early-elementary.jpg" width="303" height="219" alt="Grades K-2">
                                                <div class="overlay-content">
                                                    <h4 class="overlay-title">Grades K-2</h4>
                                                </div>
                                            </a>
                                            <a class="item level-box" href="{$search_url}&educationlevel%5B2%5D=3%7C4%7C5">
                                                <img src="{$theme_url}/images/home-page/late-elementary.jpg" width="303" height="219" alt="Grades 3-5">
                                                <div class="overlay-content">
                                                    <h4 class="overlay-title">Grades 3-5</h4>
                                                </div>
                                            </a>
                                            <a class="item level-box" href="{$search_url}&educationlevel%5B3%5D=6%7C7%7C8">
                                                <img src="{$theme_url}/images/home-page/middle-school.jpg" width="303" height="219" alt="Middle School">
                                                <div class="overlay-content">
                                                    <h4 class="overlay-title">Middle School</h4>
                                                </div>
                                            </a>
                                            <a class="item level-box" href="{$search_url}&educationlevel%5B4%5D=9%7C10&educationlevel%5B5%5D=11%7C12">
                                                <img src="{$theme_url}/images/home-page/high-school.jpg" width="303" height="219" alt="High School">
                                                <div class="overlay-content">
                                                    <h4 class="overlay-title">High School</h4>
                                                </div>
                                            </a>
                                            <a class="item level-box" href="{$search_url}&subject%5B2%5D=CareerTechnicalEducation">
                                                <img src="{$theme_url}/images/home-page/professional-development.jpg" width="303" height="219" alt="Career/Technical Education">
                                                <div class="overlay-content">
                                                    <h4 class="overlay-title">Career/Technical Education</h4>
                                                </div>
                                            </a>
                                        </div>
EOD;
                                    $dashboard .= '</div>';


                                    $dashboard .= '<div class="section-row">';
                                        $dashboard .= '<h2 class="section-title">'.__('Lessons from our Content Partners','curriki').'</h2>';
                                        if(function_exists('curriki_show_featured_item'))
                                        {
                                            $dashboard .= curriki_show_featured_item('homepagepartner');
                                        }
                                    $dashboard .= '</div>';



                                $dashboard .= '</div>'; // widecolumn
                            $dashboard .= '</div>'; // grid_9 grid_mx

                        $dashboard .= '</div>'; // wrap container_12

                    $dashboard .= '</div>'; // resource-content clearfix




                if(isset($_GET["cn"]) && $_GET["cn"] == 1)
                {
                    
                       $dashboard .= '                    
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
                        ';
                }

                
		echo $dashboard;
}

add_action('genesis_before', array(&$lti_lms_modal, 'curriki_module_page_scripts'));
add_action('genesis_loop', array(&$lti_lms_modal, 'curriki_module_page_body'), 15); 

genesis();