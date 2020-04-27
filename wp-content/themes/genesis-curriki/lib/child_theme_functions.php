<?php

/*
 * CHILD THEME FUNCTIONS (SITEWIDE)
 *
 * Child Theme Name: Curriki Child Theme for Genesis 2.1
 * Author: Orange Blossom Media
 * Url: http://orangeblossommedia.com/
 *
 * Add any functions here that you wish to use sitewide.
 */

// Change favicon location
function curriki_custom_favicon_location($favicon_url) {
    return get_stylesheet_directory_uri() . '/images/favicon.ico';
}

// Remove Edit Links
add_filter('edit_post_link', '__return_false');

// Add Features to Menu
function curriki_theme_menu_extras($menu, $args) {

    //* Change 'primary' to 'secondary' to add extras to the secondary navigation menu
    if ('primary' !== $args->theme_location)
        return $menu;

    //* Uncomment this block to add a search form to the navigation menu
    ob_start();
    curriki_search_bar();
    $search = ob_get_clean();
    $theme_url = get_stylesheet_directory_uri();
    $home_url = get_bloginfo('url');
    $search_url = $home_url.'/search?size=10&type=Resource&phrase=&language=&start=0&partnerid=1&searchall=&viewer=&branding=common&sort=rank1+desc&size=10';
    $dashboard_menu = "";
    if(is_user_logged_in()){
        $dashboard_menu = '<li  class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-13487"><a href="/dashboard" itemprop="url"><span itemprop="name">Dashboard</span></a></li>';
    }
    $menu =<<<EOD
                $dashboard_menu
                    <li  class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-13487 mega-menu"><a href="/resources-curricula" itemprop="url"><span itemprop="name">Resource Library</span></a>
                        <div class="sub-menu">
							<div class="wrap container_12">
								<div class="grid_12">
									<h4 class="dp-title">Lessons By Grade</h4>
								</div>
								<div class="clearfix">
									<div class="grid_2">
										<a class="section-link-thumbnail" href="{$search_url}&educationlevel%5B0%5D=K%7CPre-K">
											<img class="img-fluid" src="{$theme_url}/images/home-page/pre-k.jpg" width="566" height="333" alt="Preschool">
											<h4 class="overlay-title">PRE-K</h4>
										</a>
										<ul class="list-unstyled">
											<li><a href="{$search_url}&educationlevel%5B0%5D=K%7CPre-K&subject%5B1%5D=Arts"><i class="fa fa-circle"></i> Arts</a></li>
											<li><a href="{$search_url}&educationlevel%5B0%5D=K%7CPre-K&subject%5B2%5D=CareerTechnicalEducation"><i class="fa fa-circle"></i> Career/Technical Education</a></li>
											<li><a href="{$search_url}&educationlevel%5B0%5D=K%7CPre-K&subject%5B13%5D=ComputerScience"><i class="fa fa-circle"></i> Computer Science</a></li>
											<li><a href="{$search_url}&educationlevel%5B0%5D=K%7CPre-K&subject%5B7%5D=LanguageArts"><i class="fa fa-circle"></i> Language Arts</a></li>
											<li><a href="{$search_url}&educationlevel%5B0%5D=K%7CPre-K&subject%5B9%5D=Mathematics"><i class="fa fa-circle"></i> Mathematics</a></li>
											<li><a href="{$search_url}&educationlevel%5B0%5D=K%7CPre-K&subject%5B10%5D=Science"><i class="fa fa-circle"></i> Science</a></li>
											<li><a href="{$search_url}&educationlevel%5B0%5D=K%7CPre-K&subject%5B11%5D=SocialStudies"><i class="fa fa-circle"></i> Social Studies</a></li>
										</ul>
									</div>
									<div class="grid_2">
										<a class="section-link-thumbnail" href="{$search_url}&educationlevel%5B1%5D=1%7C2">
											<img class="img-fluid" src="{$theme_url}/images/home-page/early-elementary.jpg" width="566" height="333" alt="Grades K-2">
											<h4 class="overlay-title">Early Elementary</h4>
										</a>
										<ul class="list-unstyled">
                                            <li><a href="{$search_url}&educationlevel%5B1%5D=1%7C2&subject%5B1%5D=Arts"><i class="fa fa-circle"></i> Arts</a></li>
                                            <li><a href="{$search_url}&educationlevel%5B1%5D=1%7C2&subject%5B2%5D=CareerTechnicalEducation"><i class="fa fa-circle"></i> Career/Technical Education</a></li>
                                            <li><a href="{$search_url}&educationlevel%5B1%5D=1%7C2&subject%5B13%5D=ComputerScience"><i class="fa fa-circle"></i> Computer Science</a></li>
                                            <li><a href="{$search_url}&educationlevel%5B1%5D=1%7C2&subject%5B7%5D=LanguageArts"><i class="fa fa-circle"></i> Language Arts</a></li>
                                            <li><a href="{$search_url}&educationlevel%5B1%5D=1%7C2&subject%5B9%5D=Mathematics"><i class="fa fa-circle"></i> Mathematics</a></li>
                                            <li><a href="{$search_url}&educationlevel%5B1%5D=1%7C2&subject%5B10%5D=Science"><i class="fa fa-circle"></i> Science</a></li>
                                            <li><a href="{$search_url}&educationlevel%5B1%5D=1%7C2&subject%5B11%5D=SocialStudies"><i class="fa fa-circle"></i> Social Studies</a></li>
										</ul>
									</div>
									<div class="grid_2">
										<a class="section-link-thumbnail" href="{$search_url}&educationlevel%5B2%5D=3%7C4%7C5">
											<img class="img-fluid" src="{$theme_url}/images/home-page/late-elementary.jpg" width="566" height="333" alt="Grades 3-5">
											<h4 class="overlay-title">Late Elementary</h4>
										</a>
										<ul class="list-unstyled">
                                            <li><a href="{$search_url}&educationlevel%5B2%5D=3%7C4%7C5&subject%5B1%5D=Arts"><i class="fa fa-circle"></i> Arts</a></li>
                                            <li><a href="{$search_url}&educationlevel%5B2%5D=3%7C4%7C5&subject%5B2%5D=CareerTechnicalEducation"><i class="fa fa-circle"></i> Career/Technical Education</a></li>
                                            <li><a href="{$search_url}&educationlevel%5B2%5D=3%7C4%7C5&subject%5B13%5D=ComputerScience"><i class="fa fa-circle"></i> Computer Science</a></li>
                                            <li><a href="{$search_url}&educationlevel%5B2%5D=3%7C4%7C5&subject%5B7%5D=LanguageArts"><i class="fa fa-circle"></i> Language Arts</a></li>
                                            <li><a href="{$search_url}&educationlevel%5B2%5D=3%7C4%7C5&subject%5B9%5D=Mathematics"><i class="fa fa-circle"></i> Mathematics</a></li>
                                            <li><a href="{$search_url}&educationlevel%5B2%5D=3%7C4%7C5&subject%5B10%5D=Science"><i class="fa fa-circle"></i> Science</a></li>
                                            <li><a href="{$search_url}&educationlevel%5B2%5D=3%7C4%7C5&subject%5B11%5D=SocialStudies"><i class="fa fa-circle"></i> Social Studies</a></li>
										</ul>
									</div>
									<div class="grid_2">
										<a class="section-link-thumbnail" href="{$search_url}&educationlevel%5B3%5D=6%7C7%7C8">
											<img class="img-fluid" src="{$theme_url}/images/home-page/middle-school.jpg" width="566" height="333" alt="Middle School">
											<h4 class="overlay-title">Middle School</h4>
										</a>
										<ul class="list-unstyled">
                                            <li><a href="{$search_url}&educationlevel%5B3%5D=6%7C7%7C8&subject%5B1%5D=Arts"><i class="fa fa-circle"></i> Arts</a></li>
                                            <li><a href="{$search_url}&educationlevel%5B3%5D=6%7C7%7C8&subject%5B2%5D=CareerTechnicalEducation"><i class="fa fa-circle"></i> Career/Technical Education</a></li>
                                            <li><a href="{$search_url}&educationlevel%5B3%5D=6%7C7%7C8&subject%5B13%5D=ComputerScience"><i class="fa fa-circle"></i> Computer Science</a></li>
                                            <li><a href="{$search_url}&educationlevel%5B3%5D=6%7C7%7C8&subject%5B7%5D=LanguageArts"><i class="fa fa-circle"></i> Language Arts</a></li>
                                            <li><a href="{$search_url}&educationlevel%5B3%5D=6%7C7%7C8&subject%5B9%5D=Mathematics"><i class="fa fa-circle"></i> Mathematics</a></li>
                                            <li><a href="{$search_url}&educationlevel%5B3%5D=6%7C7%7C8&subject%5B10%5D=Science"><i class="fa fa-circle"></i> Science</a></li>
                                            <li><a href="{$search_url}&educationlevel%5B3%5D=6%7C7%7C8&subject%5B11%5D=SocialStudies"><i class="fa fa-circle"></i> Social Studies</a></li>
										</ul>
									</div>
									<div class="grid_2">
										<a class="section-link-thumbnail" href="{$search_url}&educationlevel%5B4%5D=9%7C10&educationlevel%5B5%5D=11%7C12">
											<img class="img-fluid" src="{$theme_url}/images/home-page/high-school.jpg" width="566" height="333" alt="High School">
											<h4 class="overlay-title">High School</h4>
										</a>
										<ul class="list-unstyled">
                                            <li><a href="{$search_url}&educationlevel%5B4%5D=9%7C10&educationlevel%5B5%5D=11%7C12&subject%5B1%5D=Arts"><i class="fa fa-circle"></i> Arts</a></li>
                                            <li><a href="{$search_url}&educationlevel%5B4%5D=9%7C10&educationlevel%5B5%5D=11%7C12&subject%5B2%5D=CareerTechnicalEducation"><i class="fa fa-circle"></i> Career/Technical Education</a></li>
                                            <li><a href="{$search_url}&educationlevel%5B4%5D=9%7C10&educationlevel%5B5%5D=11%7C12&subject%5B13%5D=ComputerScience"><i class="fa fa-circle"></i> Computer Science</a></li>
                                            <li><a href="{$search_url}&educationlevel%5B4%5D=9%7C10&educationlevel%5B5%5D=11%7C12&subject%5B7%5D=LanguageArts"><i class="fa fa-circle"></i> Language Arts</a></li>
                                            <li><a href="{$search_url}&educationlevel%5B4%5D=9%7C10&educationlevel%5B5%5D=11%7C12&subject%5B9%5D=Mathematics"><i class="fa fa-circle"></i> Mathematics</a></li>
                                            <li><a href="{$search_url}&educationlevel%5B4%5D=9%7C10&educationlevel%5B5%5D=11%7C12&subject%5B10%5D=Science"><i class="fa fa-circle"></i> Science</a></li>
                                            <li><a href="{$search_url}&educationlevel%5B4%5D=9%7C10&educationlevel%5B5%5D=11%7C12&subject%5B11%5D=SocialStudies"><i class="fa fa-circle"></i> Social Studies</a></li>
										</ul>
									</div>
								</div>
							</div>
							<hr>
							<div class="wrap container_12 padding-y-2">
								<ul class="list-inline text-center">
									<li class="list-inline-item"><a href="{$home_url}/help">Help</a></li>
									<li class="list-inline-item"><a href="{$home_url}/privacy-policy">Privacy Policy</a></li>
									<li class="list-inline-item"><a href="{$home_url}/terms-of-service">Terms of Service</a></li>
									<li class="list-inline-item"><a href="{$home_url}/search-api">Search API</a></li>
								</ul>
							</div>
							<div class="bg-white">
								<div class="wrap container_12 padding-y-2">
									<ul class="list-inline list-inline-buttons text-center">
                                        <li class="list-inline-item">
                                            <a class="btn btn-dark-blue btn-with-icon btn-icon-right" href="{$home_url}/about-curriki/donate/">Make a Donation <i class="fa fa-angle-right"></i></a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a class="btn btn-dark-blue btn-with-icon btn-icon-right" href="{$home_url}/about-curriki/partners-sponsors/">Partner with Curriki <i class="fa fa-angle-right"></i></a>
                                        </li>
									</ul>
								</div>
							</div>
						</div>
                    </li>
                    <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-7173"><a href="/about-curriki/" itemprop="url"><span itemprop="name">About Curriki</span></a>
                    <ul class="sub-menu">
                            <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-5981"><a href="/about-curriki/team/" itemprop="url"><span itemprop="name">Team</span></a></li>
                            <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-5981"><a href="/press-releases/" itemprop="url"><span itemprop="name">Press Releases</span></a></li>
                            <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-7489"><a href="/about-curriki/awards/" itemprop="url"><span itemprop="name">Awards</span></a></li>
                            <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-5982"><a href="/about-curriki/partners-sponsors/" itemprop="url"><span itemprop="name">Partners &#038; Sponsors</span></a></li>
                            <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-5990"><a href="/about-curriki/contact-us/" itemprop="url"><span itemprop="name">Contact Us</span></a></li>
                            <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-7930"><a href="/curriki-newsletter-sign-up-2/" itemprop="url"><span itemprop="name">Curriki Newsletter Sign-up</span></a></li>
                            <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-13386"><a href="/global-community/" itemprop="url"><span itemprop="name">Global Community</span></a></li>
                    </ul>
                    </li>
                    <li  class="menu-item menu-item-type-post_type menu-item-object-page menu-item-7169"><a href="https://birdiesforeducation.com" target="_blank" itemprop="url"><span itemprop="name">Birdies for Education</span></a></li>
                    <li  class="menu-item menu-item-type-post_type menu-item-object-page menu-item-7490"><a href="/blog/" itemprop="url"><span itemprop="name">Blog</span></a></li>
                
EOD;
//        return $menu;
    $menu .= '<li class="right search">' . $search . '</li>';

    //* Uncomment this block to add the date to the navigation menu
    /*
      $menu .= '<li class="right date">' . date_i18n( get_option( 'date_format' ) ) . '</li>';
     */

    return $menu;
}

// Change the footer credits
function curriki_footer_cred($curriki_ft) {
    // $curriki_copy = '<p>&copy; ' . date("Y") .' | Curriki</p>';
    // return '<div class="copy">' . $curriki_copy . '</div>';
    genesis_widget_area('logo-footer', array(
        'before' => '<div class="logo-footer widget-area"><div class="wrap">',
        'after' => '</div></div>',
    ));
}

// Gravity Forms Placeholder Text
add_action('wp_print_scripts', 'curriki_gf_placeholder_enqueue_scripts');

function curriki_gf_placeholder_enqueue_scripts() {
    wp_enqueue_script('curriki_gf_placeholders', get_stylesheet_directory_uri() . '/js/gf-placeholder.js', array('jquery'), '1.0');
}

//* Customize the post meta function
add_filter('genesis_post_meta', 'sp_post_meta_filter');

function sp_post_meta_filter($post_meta) {
    if (!is_page()) {
        $post_meta = '[post_categories before="Posted in: "] [post_tags before="Tagged: "]';
        return $post_meta;
    }
}

// Edit the read more link text
add_filter('excerpt_more', 'curriki_read_more_link');
add_filter('get_the_content_more_link', 'curriki_read_more_link');
add_filter('the_content_more_link', 'curriki_read_more_link');

function curriki_read_more_link() {
    return '<a class="more-link" href="' . get_permalink() . '" rel="nofollow">Read More</a>';
}

// Force full width layout on all archive pages
add_filter('genesis_pre_get_option_site_layout', 'curriki_full_width_layout_archives');

function curriki_full_width_layout_archives($layout) {
    if (is_archive() || is_search()) {
        $layout = 'content-sidebar';
        return $layout;
    }
}

// Force Content/Sidebar layout on archives
add_action('genesis_meta', 'curriki_force_page_layout');

function curriki_force_page_layout() {

    if (is_archive() || is_search()) {
        add_filter('genesis_pre_get_option_site_layout', '__genesis_return_content_sidebar');
    }
}

// Customize search form input box text
add_filter('genesis_search_text', 'curriki_search_text');

function curriki_search_text($text) {
    return esc_attr( __('Search Blog Posts','curriki') );
}

//* Customize search form input button text
add_filter('genesis_search_button_text', 'curriki_search_button_text');

function curriki_search_button_text($text) {
    return esc_attr('');
}

// Curriki Search Bar
function curriki_search_bar() {
    
    $current_language = "eng";    
    if( defined('ICL_LANGUAGE_CODE') )
    {
        $current_language = cur_get_current_language(ICL_LANGUAGE_CODE);         
    }
  
    // get_search_form();
    $search_bar = '';
    $search_bar .= '<div class="search-resources rounded-borders-full"><form action="' . get_bloginfo('url') . '/search/" method="GET">';
    // $search_bar .= '<div class="search-dropdown rounded-borders-left">'
    //         . '<select class="search-dropdown-icon fa-caret-down top-search-bar-dropdown" name="type">'
    //         . '<option value="Resource">' . __('Resources', 'curriki') . '</option>'
    //         // . '<option value="Member">' . __('Members', 'curriki') . '</option>'
    //         // . '<option value="Group">' . __('Groups', 'curriki') . '</option>'
    //         . '</select></div>';
    $search_bar .= '<div class="search-input"><input name="phrase" type="text" placeholder="' . __('Start Searching', 'curriki') . '" /></div>';
    
    $language = "";
    if($current_language !== "eng")
    {
        $language = $current_language;
    }
    
    $search_bar .= '<input type="hidden" name="type" value="Resource" />';
    $search_bar .= '<input type="hidden" name="language" value="'.$language.'" />';
    $search_bar .= '<input type="hidden" name="start" value="0" />';
    $search_bar .= '<input type="hidden" name="partnerid" id="partnerid" value="1" />';
    $search_bar .= '<input type="hidden" name="searchall" value="" />';
    $search_bar .= '<input type="hidden" name="viewer" value="" />';
    $search_bar .= '<div class="search-button rounded-borders-right"><button type="submit"><span class="search-button-icon fa fa-search"></span></button></div>';
    $search_bar .= '<input type="hidden" name="branding" id="branding" value="common"/>';
    $search_bar .= '<input type="hidden" name="sort" value="rank1 desc" />';
    $search_bar .= '</form></div>';

    echo $search_bar;
}

// Curriki Home Search Bar
function curriki_home_search_bar() {

    $current_language = "eng";
    if( defined('ICL_LANGUAGE_CODE') )
    {
        $current_language = cur_get_current_language(ICL_LANGUAGE_CODE);
    }

    $search_bar = '';
    $search_bar .= '<form class="bs-search" action="' . get_bloginfo('url') . '/search/" method="GET">';
    $search_bar .= '<div class="form-group">';
    // $search_bar .= '<div class="search-dropdown">';
    // $search_bar .= '<select class="search-dropdown-icon fa-caret-down" name="type">';
    // $search_bar .= '<option value="Resource">' . __('Resources', 'curriki') . '</option>';
    // // $search_bar .= '<option value="Member">' . __('Members', 'curriki') . '</option>';
    // // $search_bar .= '<option value="Group">' . __('Groups', 'curriki') . '</option>';
    // $search_bar .= '</select>';
    // $search_bar .= '</div>';
    $search_bar .= '<input class="form-control" name="phrase" type="text" placeholder="' . __('What do you want to learn?', 'curriki') . '" aria-label="search-main" aria-describedby="search-main">
                    <button type="submit" class="btn btn-dark-blue" id="search-main"><i class="fa fa-search"></i></button>
                    </div>';

    $language = "";
    if($current_language !== "eng")
    {
        $language = $current_language;
    }
    
    $search_bar .= '<input type="hidden" name="type" value="Resource" />';
    $search_bar .= '<input type="hidden" name="language" value="'.$language.'" />';
    $search_bar .= '<input type="hidden" name="start" value="0" />';
    $search_bar .= '<input type="hidden" name="partnerid" id="partnerid" value="1" />';
    $search_bar .= '<input type="hidden" name="searchall" value="" />';
    $search_bar .= '<input type="hidden" name="viewer" value="" />';
    $search_bar .= '<input type="hidden" name="branding" id="branding" value="common"/>';
    $search_bar .= '<input type="hidden" name="sort" value="rank1 desc" />';

    $search_bar .= '</form>';

    echo $search_bar;
}

/**
 * Register Sidebar
 */
function textdomain_register_sidebars() {

    /* Register the primary sidebar. */
    register_sidebar(
            array(
                'id' => 'primary-sidebar-one-column',
                'name' => __('One Column Sidebar', 'genesis'),
                'description' => __('A short description of the sidebar.', 'genesis'),
                'before_widget' => '<section class="widget"><div class="widget-wrap">',
                'after_widget' => '</div></section>',
                'before_title' => '<h4 class="widget-title widgettitle">',
                'after_title' => '</h4>'
            )
    );

    /* Repeat register_sidebar() code for additional sidebars. */
}

add_action('widgets_init', 'textdomain_register_sidebars');
