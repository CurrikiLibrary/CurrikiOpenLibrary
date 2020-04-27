<?php
/*
* Performance Test Page
*
* Child Theme Name: Curriki Child Theme for Genesis 2.1
* Author: Orange Blossom Media
* Url: http://orangeblossommedia.com/
* Template Name: Performance Test Page
*/


// Execute custom home page. If no widgets active, then loop
add_action( 'genesis_meta', 'curriki_custom_home_loop' );

function curriki_custom_home_loop() {
	add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

	remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
	remove_action( 'genesis_loop', 'genesis_do_loop' );

        add_action('genesis_before', 'curriki_front_page_scripts');
        
	add_action( 'genesis_loop', 'curriki_home_body_blocks' );
	// add_action( 'genesis_loop', 'curriki_home_body' );
        add_action('wp_head', 'cur_front_page_head');
}


function curriki_front_page_scripts() {
  
  wp_enqueue_style('jquery-fancybox-css', get_stylesheet_directory_uri() . '/js/fancybox_v2.1.5/jquery.fancybox.css?v=2.1.5');
  wp_enqueue_script('jquery-fancybox-js', get_stylesheet_directory_uri() . '/js/fancybox_v2.1.5/jquery.fancybox.pack.js?v=2.1.5', 'jquery.fancybox', '2.1.5' );
  wp_enqueue_script('jquery-mousewheel-js', get_stylesheet_directory_uri() . '/js/fancybox_v2.1.5/jquery.mousewheel-3.0.6.pack.js', 'jquery.mousewheel', '3.0.6' );
  ?>

<!--  <script type="text/javascript" defer='defer'>
      jQuery(document).ready(function() { 
          jQuery("#watch-the-video").click(function() { 
              jQuery.fancybox({ 
                  'padding' : 0, 
                  'autoScale' : false, 
                  'transitionIn' : 'none', 
                  'transitionOut' : 'none',                   
                  'width' : 840, 
                  'height' : 585, 
                  'href' : this.href.replace(new RegExp("watch\\?v=", "i"), 'v/'), 
                  'type' : 'iframe' 
              }); 
              return false; 
          }); 
      });            
  </script>-->
  <style>
      .site-inner>.container_12{
          max-width: none;
          width:100% !important;
      }
  </style>
  <script type="application/ld+json" defer='defer'>
    {
      "@context": "http://schema.org",
      "@type": "WebSite",
      "url": "https://www.curriki.org/",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "https://www.curriki.org/resources-curricula/?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
</script>

  
  <?php
}

function curriki_home_body_blocks() {

	echo '<div class="home-row search-row">';
		echo '<div class="search-row-overlay"></div>';
		echo '<div class="wrap container_12">';
			echo '<div class="search-modal rounded-borders-full grid_8 push_2">';                                
				echo '<h2>' . __(get_field('search_headline'), 'curriki') . '</h2>';
				curriki_search_bar();
			echo '</div>';
		echo '</div>';
	echo '</div>';


	// check if the flexible content field has rows of data
	if( have_rows('content_section') ):

	     // loop through the rows of data
	    while ( have_rows('content_section') ) : the_row();

	        if( get_row_layout() == 'image_content' ):

	        	$ic = '';

				$ic .= '<div class="home-row community-row"><div class="community-row-overlay"></div>';
					$ic .= '<div class="wrap container_12">';
						$ic .= '<div class="grid_6 push_6">';
							$ic .= '<h1>'.__('A Global Community' , 'curriki').'</h1>';
							$ic .= '<p>'.__('Teachers, parents, and students from around the world lorem ipsum dolor sit amet, consectetur adipiscing elit. Consectetur adipiscing elit. In vel convallis nisi, vel tristique tortor','curriki').'</p>';
							$ic .= '<a href="'.get_bloginfo('url').'/search/?type=Group" class="button blue-button">' . __( 'Browse Groups', 'curriki' ) . '</a>';
						$ic .= '</div>';
						$ic .= '<div class="side-tab rounded-borders-full right">';
							$ic .= '<img class="circle border-white" src="https://en.gravatar.com/userimage/12274633/a95d53311d524f540fd00453a5335d86.jpg?size=80" />';
							$ic .= '<div class="side-tab-title">' . __( 'Featured Member', 'curriki' ) . '</div>';
							$ic .= '<p>'.__('For more than five years, Shmoop has been a valued Curriki Content Partner. Don’t miss their featured resource, a study guide for Mary Shelly’s Frankenstein!','curriki').'</p>';
						$ic .= '</div>';
					$ic .= '</div>';
				$ic .= '</div>';

				echo $ic;

			elseif( get_row_layout() == 'community_row' ):

				$ic_image = get_sub_field('ic_image');
				$ic_sidebar_image = get_sub_field('ic_sidebar_image');
				$button_color = get_sub_field_object('ic_cta_button_color');
				$bc_value = get_sub_field('ic_cta_button_color');
	        	$ic = '';

				$ic .= '<div class="home-row community-row"><div class="community-row-overlay" style="background-image: url(' . get_sub_field('ic_image') . ');"></div>';
					$ic .= '<div class="wrap container_12">';
						$ic .= '<div class="grid_6 push_6">';
							$ic .= '<h1>' . get_sub_field('ic_headline') . '</h1>';
							$ic .= get_sub_field('ic_text');
							if( get_sub_field('link_call_to_action') == TRUE ) {
								$ic .= '<a href="' . get_sub_field('ic_cta_button_link') . '" class="button ' . $bc_value . '-button">' . get_sub_field('ic_cta_button') . '</a>';
							}
						$ic .= '</div>';

						if( get_sub_field('display_sidebar') == TRUE ) {
							$ic .= '<div class="side-tab rounded-borders-full right">'; 
                                                            if(function_exists('curriki_show_featured_item')){
                                                                $ic .= $homepagealigned = curriki_show_featured_item('homepagemember');
                                                            }else{
                                                                $ic .= wp_get_attachment_image( $ic_sidebar_image, 'medium', 0, array( 'class' => 'circle border-white' ) );
								$ic .= '<div class="side-tab-title">' . get_sub_field('ic_sidebar_title') . '</div>';
								$ic .= get_sub_field('ic_sidebar_content');
                                                            }
							$ic .= '</div>';
						}
					$ic .= '</div>';
				$ic .= '</div>';

				echo $ic;

			elseif( get_row_layout() == 'resources_row' ):

				$ic_image = get_sub_field('ic_image');
				$ic_sidebar_image = get_sub_field('ic_sidebar_image');
				$button_color = get_sub_field_object('ic_cta_button_color');
				$bc_value = get_sub_field('ic_cta_button_color');
	        	$ic = '';

				$ic .= '<div class="home-row resources-row"><div class="resources-row-overlay" style="background-image: url(' . get_sub_field('ic_image') . ');"></div>';
					$ic .= '<div class="wrap container_12">';
						$ic .= '<div class="grid_6">';
							$ic .= '<h1>' . get_sub_field('ic_headline') . '</h1>';
							$ic .= get_sub_field('ic_text');
							if( get_sub_field('link_call_to_action') == TRUE ) {
								$ic .= '<a href="' . get_sub_field('ic_cta_button_link') . '" class="button ' . $bc_value . '-button">' . get_sub_field('ic_cta_button') . '</a>';
							}
						$ic .= '</div>';

						if( get_sub_field('display_sidebar') == TRUE ) {
							$ic .= '<div class="side-tab rounded-borders-full left">';
                                                            if(function_exists('curriki_show_featured_item')){
                                                                $ic .= $homepagealigned = curriki_show_featured_item('homepageresource');
                                                            }else{
                                                                $ic .= wp_get_attachment_image( $ic_sidebar_image, 'medium', 0, array( 'class' => 'circle border-white' ) );
								$ic .= '<div class="side-tab-title">' . get_sub_field('ic_sidebar_title') . '</div>';
								$ic .= get_sub_field('ic_sidebar_content');
                                                            }
							$ic .= '</div>';
						}
					$ic .= '</div>';
				$ic .= '</div>';

				echo $ic;

			elseif( get_row_layout() == 'standards_row' ):

				$ic_image = get_sub_field('ic_image');
				$ic_sidebar_image = get_sub_field('ic_sidebar_image');
				$button_color = get_sub_field_object('ic_cta_button_color');
				$bc_value = get_sub_field('ic_cta_button_color');
	        	$ic = '';

				$ic .= '<div class="home-row standards-row"><div class="standards-row-overlay" style="background-image: url(' . get_sub_field('ic_image') . ');"></div>';
					$ic .= '<div class="wrap container_12">';
						$ic .= '<div class="grid_6 push_6">';
							$ic .= '<h1>' . get_sub_field('ic_headline') . '</h1>';
                                                        $ic .= get_sub_field('ic_text');
							if( get_sub_field('link_call_to_action') == TRUE ) {
								$ic .= '<a href="' . get_sub_field('ic_cta_button_link') . '" class="button ' . $bc_value . '-button">' . get_sub_field('ic_cta_button') . '</a>';
							}
						$ic .= '</div>';

						if( get_sub_field('display_sidebar') == TRUE ) {
							$ic .= '<div class="side-tab rounded-borders-full right">';
								if(function_exists('curriki_show_featured_item')){
                                                                    $ic .= $homepagealigned = curriki_show_featured_item('homepagealigned');
                                                                }else{
                                                                    $ic .= wp_get_attachment_image( $ic_sidebar_image, 'medium', 0, array( 'class' => 'circle border-white' ) );
                                                                    $ic .= '<div class="side-tab-title">' . get_sub_field('ic_sidebar_title') . '</div>';
                                                                    $ic .= get_sub_field('ic_sidebar_content');
                                                                }
							$ic .= '</div>';
						}
					$ic .= '</div>';
				$ic .= '</div>';

				echo $ic;

			elseif( get_row_layout() == 'support_row' ):

				$ic_image = get_sub_field('ic_image');
				$ic_sidebar_image = get_sub_field('ic_sidebar_image');
				$button_color = get_sub_field_object('ic_cta_button_color');
				$bc_value = get_sub_field('ic_cta_button_color');
	        	$ic = '';

				$ic .= '<div class="home-row support-row"><div class="support-row-overlay" style="background-image: url(' . get_sub_field('ic_image') . ');"></div>';
					$ic .= '<div class="wrap container_12">';
						$ic .= '<div class="grid_6">';
							$ic .= '<h1>' . get_sub_field('ic_headline') . '</h1>';
							$ic .= get_sub_field('ic_text');
							if( get_sub_field('link_call_to_action') == TRUE ) {
								$ic .= '<a href="' . get_sub_field('ic_cta_button_link') . '" class="button ' . $bc_value . '-button">' . get_sub_field('ic_cta_button') . '</a>';
							}
						$ic .= '</div>';

						if( get_sub_field('display_sidebar') == TRUE ) {
							$ic .= '<div class="side-tab rounded-borders-full right">';
								$ic .= wp_get_attachment_image( $ic_sidebar_image, 'medium', 0, array( 'class' => 'circle border-white' ) );
								$ic .= '<div class="side-tab-title">' . get_sub_field('ic_sidebar_title') . '</div>';
								$ic .= get_sub_field('ic_sidebar_content');
							$ic .= '</div>';
						}
					$ic .= '</div>';
				$ic .= '</div>';

				echo $ic;

			elseif( get_row_layout() == 'support_row' ):

				$ic_image = get_sub_field('ic_image');
				$ic_sidebar_image = get_sub_field('ic_sidebar_image');
				$button_color = get_sub_field_object('ic_cta_button_color');
				$bc_value = get_sub_field('ic_cta_button_color');
	        	$ic = '';

				$ic .= '<div class="home-row support-row"><div class="support-row-overlay" style="background-image: url(' . get_sub_field('ic_image') . ');"></div>';
					$ic .= '<div class="wrap container_12">';
						$ic .= '<div class="grid_6">';
							$ic .= '<h1>' . get_sub_field('ic_headline') . '</h1>';
							$ic .= get_sub_field('ic_text');
							if( get_sub_field('link_call_to_action') == TRUE ) {
								$ic .= '<a href="' . get_sub_field('ic_cta_button_link') . '" class="button ' . $bc_value . '-button">' . get_sub_field('ic_cta_button') . '</a>';
							}
						$ic .= '</div>';

						if( get_sub_field('display_sidebar') == TRUE ) {
							$ic .= '<div class="side-tab rounded-borders-full right">';
								$ic .= wp_get_attachment_image( $ic_sidebar_image, 'medium', 0, array( 'class' => 'circle border-white' ) );
								$ic .= '<div class="side-tab-title">' . get_sub_field('ic_sidebar_title') . '</div>';
								$ic .= get_sub_field('ic_sidebar_content');
							$ic .= '</div>';
						}
					$ic .= '</div>';
				$ic .= '</div>';

				echo $ic;

	        elseif( get_row_layout() == 'full_width_content' ):

				$fwc_image = get_sub_field('fwc_image');
                        if(is_object($fwc_image)):
				$post_object = get_post( $fwc_image->ID );

                                $fwc = '';

				$fwc .= '<div class="home-row fwc-row">';
					$fwc .= '<div class="wrap container_12">';
						$fwc .= '<h1>' . get_sub_field('fwc_headline') . '</h1>';
						$fwc .= '<h4>' . get_sub_field('fwc_content') . '</h4>';
						$fwc .= wp_get_attachment_image( $fwc_image, 'full' );
					$fwc .= '</div>';
				$fwc .= '</div>';

				echo $fwc;
                        endif;
	        elseif( get_row_layout() == 'four_icons' ):

	    		$four_icons = '';

	        	$four_icons .= '<div class="home-row features-row">';
					$four_icons .= '<div class="wrap container_12">';
						$four_icons .= '<div class="feature-icons">';

						 	// loop through the rows of data
						    while ( have_rows('icons') ) : the_row();

								$icon = get_sub_field('icon');
                                                                if(is_object($icon))
                                                                    $post_object = get_post( $icon->ID );

                                                                    $four_icons .= '<div class="grid_3">';
                                                                            $four_icons .= '<div class="feature-icon circle">' . wp_get_attachment_image( $icon, 'medium' ) . '</div>';
                                                                            $four_icons .= '<h4>' . get_sub_field('headline') . '</h4>';
                                                                            $four_icons .= '<p>' . get_sub_field('text') . '</p>';
                                                                    $four_icons .= '</div>';
                                                                

							endwhile;

						$four_icons .= '</div>';
						$four_icons .= '<h1>' . get_sub_field('four_icon_headline') . '</h1>';						
						$four_icons .= '<h4 class="cur-description">' . get_sub_field('four_icon_text') . '</h4>';                                                
                                                global $wpdb;
                                                $video_post = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}posts WHERE post_name='inspiring-learning-everywhere-video'", OBJECT);
                                                $four_icons .= '<a href="'.strip_tags($video_post->post_content).'" class="button blue-button iframe" id="watch-the-video" title="'.__('Inspiring Learning Everywhere','curriki').'">'.__('Watch the Video','curriki').'</a>';
					$four_icons .= '</div>';
				$four_icons .= '</div>';

				echo $four_icons;

	        elseif( get_row_layout() == 'testimonials' ):

	    		$testimonials = '';

	        	$testimonials .= '<div class="home-row testimonials-row">';
					$testimonials .= '<div class="wrap container_12">';
                                        
                                        $testimonials .= curriki_show_featured_item('homepagequote');

					 	// loop through the rows of data
					    /*while ( have_rows('testimonial') ) : the_row();

							$testimonial = get_sub_field('testimonial_image');
							$post_object = get_post( $testimonial->ID );

							$testimonial_continent = get_sub_field('testimonial_continent');
				        	if( $testimonial_continent == 'North America' ) {
				        		$tc = 'na';
				        	} elseif( $testimonial_continent == 'South America' ) {
				        		$tc = 'sa';
				        	} elseif( $testimonial_continent == 'Africa' ) {
				        		$tc = 'af';
				        	} elseif( $testimonial_continent == 'Asia' ) {
				        		$tc = 'as';
				        	} elseif( $testimonial_continent == 'Europe' ) {
				        		$tc = 'eu';
				        	} elseif( $testimonial_continent == 'Australia' ) {
				        		$tc = 'au';
				        	}

							$testimonials .= '<div class="grid_6 testimonial">';

								$testimonials .= '<div class="testimonial-person grid_4">';
									$testimonials .= wp_get_attachment_image( $testimonial, 'medium', 0, array( 'class' => 'circle' ) );
									$testimonials .= '<div class="testimonial-name">' . get_sub_field('testimonial_name') . '</div>';
									$testimonials .= '<div class="testimonial-place ' . $tc . '">' . get_sub_field('testimonial_location') . '</div>';
								$testimonials .= '</div>';
								$testimonials .= '<div class="grid_8"><div class="testimonial-text rounded-borders-full">' . get_sub_field('testimonial_content') . '</div></div>';

							$testimonials .= '</div>';

						endwhile;*/

					$testimonials .= '</div>';
				$testimonials .= '</div>';

				echo $testimonials;

	        elseif( get_row_layout() == 'stats' ):

	        	$stats = '';

				$stats .= '<div class="home-row stats-row">';
					$stats .= '<div class="wrap container_12">';
						$stats .= '<div class="stat grid_3">';
							$stats .= '<div class="stat-number">'.getCurrikiStats('visitors').'</div>';
							$stats .= '<div class="stat-title">' . get_sub_field('visitors') . '</div>';
						$stats .= '</div>';
						$stats .= '<div class="stat grid_3">';
							$stats .= '<div class="stat-number">'.getCurrikiStats('members').'</div>';
							$stats .= '<div class="stat-title">' . get_sub_field('members') . '</div>';
						$stats .= '</div>';
						$stats .= '<div class="stat grid_3">';
							$stats .= '<div class="stat-number">'.getCurrikiStats('resources').'</div>';
							$stats .= '<div class="stat-title">' . get_sub_field('resources') . '</div>';
						$stats .= '</div>';
						$stats .= '<div class="stat grid_3">';
							$stats .= '<div class="stat-number">'.getCurrikiStats('groups').'</div>';
							$stats .= '<div class="stat-title">' . get_sub_field('groups') . '</div>';
						$stats .= '</div>';
					$stats .= '</div>';
				$stats .= '</div>';

				echo $stats;

			endif;


	    endwhile;

	else :

	    // no layouts found

	endif;


	echo '<div class="home-row footer-row"><div class="footer-row-overlay"></div>';
		echo '<div class="wrap container_12">';
			echo '<div class="grid_4">';

				genesis_widget_area( 'home-footer-1', array(
					'before' => '<div class="home-footer-1 widget-area">',
					'after'  => '</div>',
				) );

			echo '</div>';
			echo '<div class="grid_4">';

				genesis_widget_area( 'home-footer-2', array(
					'before' => '<div class="home-footer-2 widget-area">',
					'after'  => '</div>',
				) );

			echo '</div>';
			echo '<div class="grid_4">';

				genesis_widget_area( 'home-footer-3', array(
					'before' => '<div class="home-footer-3 widget-area">',
					'after'  => '</div>',
				) );

			echo '</div>';
		echo '</div>';
	echo '</div>';

}

function cur_front_page_head()
{
    echo '<meta name="p:domain_verify" content="cbc262324a1577e3971f1785d2bde5f3"/>';
}

genesis();