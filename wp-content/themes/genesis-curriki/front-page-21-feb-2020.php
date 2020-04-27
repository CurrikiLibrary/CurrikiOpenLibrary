<?php
/*
* Custom Front Page
*
* Child Theme Name: Curriki Child Theme for Genesis 2.1
* Author: Orange Blossom Media
* Url: http://orangeblossommedia.com/
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
  wp_enqueue_style('bootstrap-css',  get_stylesheet_directory_uri() . '/css/bootstrap.min.css');
  wp_enqueue_script('bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', 'jquery', '2.1.5');
  wp_enqueue_script('isotope-pkgd-js', get_stylesheet_directory_uri() . '/js/isotope.pkgd.min.js');
  wp_enqueue_script('owl-carousel-js', get_stylesheet_directory_uri() . '/js/owl.carousel.min.js');
  wp_enqueue_script('jquery-matchHeight-js', get_stylesheet_directory_uri() . '/js/jquery.matchHeight-min.js');
  wp_enqueue_script('script-js', get_stylesheet_directory_uri() . '/js/script.js');
  wp_enqueue_style('owl-carousel-css', get_stylesheet_directory_uri() . '/css/owl.carousel.min.css');
  wp_enqueue_style('banner-css', get_stylesheet_directory_uri() . '/css/banner.css');
  ?>

  <script type="text/javascript">
      jQuery(document).ready(function() { 
        //   jQuery("#watch-the-video").click(function() {
		jQuery("#watch-the-video-link").click(function() {
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
  </script>
  
<script type="application/ld+json">
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
			echo '<div class="search-modal">';
				echo '<h2><span>FIND YOUR LESSON</span> <br> A Community for Teaching and Learning <br> Create, Share and
				Explore High Quality K-12 Content</h2>';
				curriki_home_search_bar();
				global $wpdb;
				$video_post = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}posts WHERE post_name='inspiring-learning-everywhere-video'", OBJECT);
				echo '<div class="text-center">';
					echo '<a href="'.strip_tags($video_post->post_content).'" class="btn btn-yellow btn-play-icon" id="watch-the-video-link" title="'.__('Inspiring Learning Everywhere','curriki').'"><i class="fa fa-play"></i>'.__('Watch the Video','curriki').'</a>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
		/*
		echo '<div class="home-row button-row">';
			echo '<div class="wrap container_12">';
				echo '<div class="text-center">';
					echo '<a class="btn btn-white" href="#">STUDENTS, Start Here <i class="fa fa-angle-right"></i></a>';
					echo '<a class="btn btn-white" href="#">TEACHERS, Start Here <i class="fa fa-angle-right"></i></a>';
					echo '<a class="btn btn-white" href="#">PARENTS, Start Here <i class="fa fa-angle-right"></i></a>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
		*/
	echo '</div>';

	$theme_url = get_stylesheet_directory_uri();
	$home_url = get_bloginfo('url');
	$home_testimonials = curriki_home_show_featured_item('homepagequote');
	$home_page_partners = curriki_home_show_featured_item('homepagepartner');
	$home_page_collections = curriki_home_show_featured_item('homepagecollection');
	$currikiStatsVisitors = getCurrikiStats('visitors');
	$currikiStatsMembers = getCurrikiStats('members');
	$currikiStatsResources = getCurrikiStats('resources');
	$currikiStatsGroups = getCurrikiStats('groups');
	$search_url = $home_url.'/search?size=10&type=Resource&phrase=&language=&start=0&partnerid=1&searchall=&viewer=&branding=common&sort=rank1+desc&size=10';

	ob_start();
    genesis_widget_area( 'home-newsletter-subscription', array(
		'before' => '<div class="home-newsletter-subscription widget-area">',
		'after'  => '</div>',
	) );
    $home_newsletter_subscription = ob_get_contents();
    ob_end_clean();

	/*
	$homeHtml =<<<EOD
	<div class="home-row row-info">
		<div class="wrap container_12">
			<h2 class="heading-v2 text-blue-alt">CONNECT WITH PEERS AROUND THE WORLD</h2>
			<div class="row-info-inner grid_md_flex">
				<div class="grid_4 grid_md_flex">
					<div class="service-block text-center">
						<i class="icon"><img src="{$theme_url}/images/home-page/join-group.png" width="110" height="80" alt="icon"></i>
						<h3 class="text-blue-alt">JOIN A GROUP</h3>
						<p>Join an existing group and collaborate, share ideas, and move forward!</p>
					</div>
				</div>
				<div class="grid_4 grid_md_flex">
					<div class="service-block text-center">
						<i class="icon"><img src="{$theme_url}/images/home-page/create-group.png" width="110" height="80" alt="icon"></i>
						<h3 class="text-blue-alt">CREATE A GROUP</h3>
						<p>You can create a group on Curriki and start your own professional learning community!</p>
					</div>
				</div>
				<div class="grid_4 grid_md_flex">
						<div class="service-block text-center">
							<i class="icon"><img src="{$theme_url}/images/home-page/follow-peer.png" width="73" height="80" alt="icon"></i>
							<h3 class="text-blue-alt">FOLLOW A PEER</h3>
							<p>Like a resource shared on Curriki? Follow that contributor!</p>
						</div>
				</div>
			</div>
		</div>
	</div>

	<div class="promo-box">
		<div class="row no-gutters">
			<div class="col-sm-8">
				<div class="promo-left">
					<h3 class="promo-title">Looking for more Art Resources?</h3>
					<div>Browse Over {$currikiStatsResources} resources in Subject</div>
				</div>
			</div>
			<a class="btn btn-promo" href="{$search_url}&subject%5B1%5D=Arts">SEE MORE <i class="fa fa-angle-right"></i></a>
		</div>
	</div>
	*/

	$homeHtml =<<<EOD
	<div class="home-row gray-row">
		<div class="wrap container_12">
			<h2 class="heading-v2 text-light-blue text-center">Browse Our Top Resources</h2>
			{$home_page_collections}
		</div>
	</div>

	<div class="home-row row-info">
		<div class="wrap container_12">
			<h2 class="heading-v2 heading-v2-medium text-blue-alt">Lessons by Education Level</h2>
			<div class="clearfix no-gutters">
				<div class="col-sm-4">
					<a class="level-box" href="{$search_url}&educationlevel%5B0%5D=K%7CPre-K">
						<img src="{$theme_url}/images/home-page/pre-k.jpg" width="566" height="333" alt="Preschool">
						<div class="level-overlay">
							<div class="img-overlay"></div>
							<div class="overlay-content">
								<h4 class="overlay-title">Preschool</h4>
								<span class="btn">Explore Lessons</span>
							</div>
						</div>
					</a>
				</div>
				<div class="col-sm-4">
					<a class="level-box" href="{$search_url}&educationlevel%5B1%5D=1%7C2">
						<img src="{$theme_url}/images/home-page/early-elementary.jpg" width="566" height="333" alt="Grades K-2">
						<div class="level-overlay">
							<div class="img-overlay"></div>
							<div class="overlay-content">
								<h4 class="overlay-title">Grades K-2</h4>
								<span class="btn">Explore Lessons</span>
							</div>
						</div>
					</a>
				</div>
				<div class="col-sm-4">
					<a class="level-box" href="{$search_url}&educationlevel%5B2%5D=3%7C4%7C5">
						<img src="{$theme_url}/images/home-page/late-elementary.jpg" width="566" height="333" alt="Grades 3-5">
						<div class="level-overlay">
							<div class="img-overlay"></div>
							<div class="overlay-content">
								<h4 class="overlay-title">Grades 3-5</h4>
								<span class="btn">Explore Lessons</span>
							</div>
						</div>
					</a>
				</div>
			</div>
			<div class="clearfix no-gutters">
				<div class="col-sm-4">
					<a class="level-box" href="{$search_url}&educationlevel%5B3%5D=6%7C7%7C8">
						<img src="{$theme_url}/images/home-page/middle-school.jpg" width="566" height="333" alt="Middle School">
						<div class="level-overlay">
							<div class="img-overlay"></div>
							<div class="overlay-content">
								<h4 class="overlay-title">Middle School</h4>
								<span class="btn">Explore Lessons</span>
							</div>
						</div>
					</a>
				</div>
				<div class="col-sm-4">
					<a class="level-box" href="{$search_url}&educationlevel%5B4%5D=9%7C10&educationlevel%5B5%5D=11%7C12">
						<img src="{$theme_url}/images/home-page/high-school.jpg" width="566" height="333" alt="High School">
						<div class="level-overlay">
							<div class="img-overlay"></div>
							<div class="overlay-content">
								<h4 class="overlay-title">High School</h4>
								<span class="btn">Explore Lessons</span>
							</div>
						</div>
					</a>
				</div>
				<div class="col-sm-4">
					<a class="level-box" href="{$search_url}&educationlevel%5B7%5D=ProfessionalEducation-Development%7CVocational+Training">
						<img src="{$theme_url}/images/home-page/professional-development.jpg" width="566" height="333" alt="Professional Development">
						<div class="level-overlay">
							<div class="img-overlay"></div>
							<div class="overlay-content">
								<h4 class="overlay-title">Professional Development</h4>
								<span class="btn">Explore Lessons</span>
							</div>
						</div>
					</a>
				</div>
			</div>
		</div>
	</div>

	<div class="home-row newsletter-row">
		<div class="wrap container_12">
			<div class="grid_7">
				<h2>SUBSCRIBE TO OUR NEWSLETTER</h2>
				<p>Don’t miss out on the latest updates and OER initiatives!</p>
			</div>
			<div class="grid_5">{$home_newsletter_subscription}</div>
		</div>
	</div>

	<div class="home-row row-info row-info-v2">
		<div class="wrap container_12">
			<h2 class="heading-v2 text-blue-alt">FIND | ORGANIZE | SHARE</h2>
			<div class="row-info-inner grid_md_flex">
				<div class="grid_4 grid_md_flex">
					<div class="service-block text-center">
					<i class="icon"><img src="{$theme_url}/images/home-page/find-lessons.png" width="75" height="80"
						alt="icon"></i>
					<h3 class="text-blue-alt">FIND LESSONS</h3>
					<p>Search our growing lesson library by title, subjects, grade levels,
						resource types, and standards.
					</p>
					</div>
				</div>
				<div class="grid_4 grid_md_flex">
					<div class="service-block text-center">
					<i class="icon"><img src="{$theme_url}/images/home-page/organize-lessons.png" width="82"
						height="82" alt="icon"></i>
					<h3 class="text-blue-alt">ORGANIZE LESSONS</h3>
					<p>Group and organize your resources whether they are your own, contributed
						by other educators, or by one of Curriki's content partners.
					</p>
					</div>
				</div>
				<div class="grid_4 grid_md_flex">
					<div class="service-block text-center">
					<i class="icon"><img src="{$theme_url}/images/home-page/share-lessons.png" width="75" height="82"
						alt="icon"></i>
					<h3 class="text-blue-alt">SHARE LESSONS</h3>
					<p>Upload and share your own content. Rate and review content so others
						can quickly find the best resources.
					</p>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="home-row gray-row">
		<div class="wrap container_12">
			<div class="partner-block">
				<h2 class="heading-v2 text-blue-alt text-center">Lessons by Select Content Partners
				</h2>
				{$home_page_partners}
			</div>
		</div>
	</div>

	<div class="home-row blue-row">
		<div class="wrap container_12">
			<h2 class="heading-v2 text-center">What Members are Saying about Curriki</h2>
			<div class="owl-carousel owl-testimonial">
				{$home_testimonials}
			</div>
		</div>
	</div>

	<div class="home-row contribute-row">
		<div class="wrap container_12">
			<div class="col-sm-6">
				<div class="text-top">
					<h2>Contribute Your Lessons</h2>
					<p>Join Curriki’s global community of sharing -Membership is free. Share
					resources
					with colleagues from around the world. Build collaborative working groups
					with your
					team.
					</p>
				</div>
				<div class="intrinsic intrinsic-portrait">
					<img class="intrinsic-portrait-base" src="{$theme_url}/images/home-page/contribute-image-a.jpg"
					width="440" height="471" alt="Contribute image">
					<img class="intrinsic-portrait-attachment" src="{$theme_url}/images/home-page/contribute-image-b.jpg"
					width="438" height="309" alt="Contribute image">
				</div>
				<a class="btn btn-dark-blue btn-with-icon btn-icon-right class-header-menu-signup" href="#">Contribute Now!
				<i class="fa fa-angle-right"></i></a>
			</div>
			<div class="col-sm-6">
				<div>
					<div class="feature-box feature-box-first text-center">
					<figure class="counter-figure">
						<img src="{$theme_url}/images/home-page/visitors-icon.png" width="138" height="108"
							alt="Unique Visitors">
					</figure>
					<span class="counter-data">{$currikiStatsVisitors}+</span>
					<h4 class="counter-desc">UNIQUE VISITORS</h4>
					</div>
					<div class="row">
					<div class="col-sm-4">
						<div class="feature-box text-center">
							<figure class="counter-figure">
								<img src="{$theme_url}/images/home-page/members-icon.png" width="108" height="102"
								alt="Members">
							</figure>
							<span class="counter-data">{$currikiStatsMembers}+</span>
							<h4 class="counter-desc">MEMBERS</h4>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="feature-box text-center">
							<figure class="counter-figure">
								<img src="{$theme_url}/images/home-page/groups-icon.png" width="101" height="101"
								alt="Groups">
							</figure>
							<span class="counter-data">{$currikiStatsGroups}+</span>
							<h4 class="counter-desc">GROUPS</h4>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="feature-box text-center">
							<figure class="counter-figure">
								<img src="{$theme_url}/images/home-page/resources-icon.png" width="102" height="102"
								alt="Resources">
							</figure>
							<span class="counter-data">{$currikiStatsResources}+</span>
							<h4 class="counter-desc">RESOURCES</h4>
						</div>
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="home-row support-wrap">
		<div class="wrap container_12">
			<div class="grid_7">
				<h2>Help Support Curriki</h2>
				<p>With the support of people like you, Curriki has touched the lives of countless
					students, educators and parents around the world. Please join us in supporting
					Curriki’s
					mission to eliminate the educational divide for all children.
				</p>
				<div class="button-group">
					<a class="btn btn-blue btn-with-icon btn-icon-right" href="{$home_url}/about-curriki/donate/">Make a Donation <i
					class="fa fa-angle-right"></i></a>
					<a class="btn btn-yellow btn-with-icon btn-icon-right" href="{$home_url}/about-curriki/partners-sponsors/">Partner with
					Curriki <i class="fa fa-angle-right"></i></a>
				</div>
			</div>
		</div>
	</div>
EOD;

echo $homeHtml;

/*
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
	        	// $four_icons .= '<div class="home-row features-row">';
				// 	$four_icons .= '<div class="wrap container_12">';
				// 		$four_icons .= '<div class="feature-icons">';

				// 		 	// loop through the rows of data
				// 		    while ( have_rows('icons') ) : the_row();

				// 				$icon = get_sub_field('icon');
                //                                                 if(is_object($icon))
                //                                                     $post_object = get_post( $icon->ID );

                //                                                     $four_icons .= '<div class="grid_3">';
                //                                                             $four_icons .= '<div class="feature-icon circle">' . wp_get_attachment_image( $icon, 'medium' ) . '</div>';
                //                                                             $four_icons .= '<h4>' . get_sub_field('headline') . '</h4>';
                //                                                             $four_icons .= '<p>' . get_sub_field('text') . '</p>';
                //                                                     $four_icons .= '</div>';
                                                                

				// 			endwhile;

				// 		$four_icons .= '</div>';
				// 		$four_icons .= '<h1>' . get_sub_field('four_icon_headline') . '</h1>';						
				// 		$four_icons .= '<h4 class="cur-description">' . get_sub_field('four_icon_text') . '</h4>';                                                
                //                                 global $wpdb;
                //                                 $video_post = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}posts WHERE post_name='inspiring-learning-everywhere-video'", OBJECT);
                //                                 $four_icons .= '<a href="'.strip_tags($video_post->post_content).'" class="button blue-button iframe" id="watch-the-video" title="'.__('Inspiring Learning Everywhere','curriki').'">'.__('Watch the Video','curriki').'</a>';
				// 	$four_icons .= '</div>';
				// $four_icons .= '</div>';
				echo $four_icons;

	        elseif( get_row_layout() == 'testimonials' ):

	    		$testimonials = '';

	        	$testimonials .= '<div class="home-row testimonials-row">';
					$testimonials .= '<div class="wrap container_12">';
                                        
                                        $testimonials .= curriki_show_featured_item('homepagequote');

					 	// loop through the rows of data

						// while ( have_rows('testimonial') ) : the_row();

						// 	$testimonial = get_sub_field('testimonial_image');
						// 	$post_object = get_post( $testimonial->ID );

						// 	$testimonial_continent = get_sub_field('testimonial_continent');
				        // 	if( $testimonial_continent == 'North America' ) {
				        // 		$tc = 'na';
				        // 	} elseif( $testimonial_continent == 'South America' ) {
				        // 		$tc = 'sa';
				        // 	} elseif( $testimonial_continent == 'Africa' ) {
				        // 		$tc = 'af';
				        // 	} elseif( $testimonial_continent == 'Asia' ) {
				        // 		$tc = 'as';
				        // 	} elseif( $testimonial_continent == 'Europe' ) {
				        // 		$tc = 'eu';
				        // 	} elseif( $testimonial_continent == 'Australia' ) {
				        // 		$tc = 'au';
				        // 	}

						// 	$testimonials .= '<div class="grid_6 testimonial">';

						// 		$testimonials .= '<div class="testimonial-person grid_4">';
						// 			$testimonials .= wp_get_attachment_image( $testimonial, 'medium', 0, array( 'class' => 'circle' ) );
						// 			$testimonials .= '<div class="testimonial-name">' . get_sub_field('testimonial_name') . '</div>';
						// 			$testimonials .= '<div class="testimonial-place ' . $tc . '">' . get_sub_field('testimonial_location') . '</div>';
						// 		$testimonials .= '</div>';
						// 		$testimonials .= '<div class="grid_8"><div class="testimonial-text rounded-borders-full">' . get_sub_field('testimonial_content') . '</div></div>';

						// 	$testimonials .= '</div>';

						// endwhile;

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
	*/
        $theme_url = get_stylesheet_directory_uri();
        $modal =<<<EOD
            <style>
                #pop-up-model{
                    max-width:582px; 
                    margin:0 auto;
                    background: #dadada url({$theme_url}/images/maverick.png) no-repeat 100% 100%;
                    height:409px;
                    color:#fff;
                    position:relative;
                    font-family: 'Lato', sans-serif;
                }
                .pop-content{
                    background: -moz-linear-gradient(left, rgba(0,0,0,1) 0%, rgba(0,0,0,0.21) 100%); /* FF3.6-15 */
                    background: -webkit-linear-gradient(left, rgba(0,0,0,1) 0%,rgba(0,0,0,0.21) 100%); /* Chrome10-25,Safari5.1-6 */
                    background: linear-gradient(to right, rgba(0,0,0,1) 0%,rgba(0,0,0,0.21) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
                    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#000000', endColorstr='#36000000',GradientType=1 ); /* IE6-9 */width:100%; height:409px;
                    font-weight:400;
                }

                .Birdies_for_Education{
                    font-size: 30px; letter-spacing: 8.0px;  color: rgb(255, 255, 255);  text-transform: uppercase;line-height: 1.133; text-align: left;  display:inline-block;  border-bottom:1px solid rgba(252, 190, 54, 0.540);  padding-left:45px; padding-bottom:25px; margin-bottom:25px;  margin-top:50px; font-weight:300;
                }
                #pop-up-model p{
                    padding: 0 250px 0 45px; line-height: 25px;
                }		
                #pop-up-model p a{
                    color: #546cb1; font-weight:bold; text-decoration:underline
                }	
                a.hashbe{
                    font-weight:bold; display:inline-block; margin-top:20px; color:#fff; font-size: 18px; margin-left: 45px;text-decoration:none;
                }
                .pop-footer{
                    position:absolute; bottom:0; left:0; width:100%; height: 36px; line-height: 36px; padding:0 0 0 45px; font-size:14px; background-color: rgba(253, 189, 54, 0.9); box-sizing:border-box; color:#000; font-weight:900;
                }
                .pop-footer a{
                    color:#000;
                    text-decoration:none;
                }
                .modal-content {
                    min-height:auto !important;
                }

                @media(max-width:400px){
                    #pop-up-model p{padding: 0 50px 0 45px; line-height: 25px;}	
                    .Birdies_for_Education {margin-top:30px;}
                }
                
            </style>

            <div class="modal fade " id="home-modal" tabindex="-1" role="dialog" aria-labelledby="home-modal-label" aria-hidden="true" style="max-width:582px;overflow: visible;margin-top: 30px;height:500px;margin-left:auto;margin-right:auto;">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <div>
                        <div id="pop-up-model">
                            <div class="pop-content">
                                <div class="Birdies_for_Education">Birdies for<br>Education</div>
                                <p>
                                        Pro-golfer Maverick McNealy invites you to join him in his quest to support <a href="#">Curriki</a> by making a pledge for every birdie he makes during the 2019 season. 
                                </p>
                                <a href="https://www.instagram.com/explore/tags/birdiesforeducation/" class="hashbe">
                                        #BirdiesforEducation
                                </a>
                            </div>
                                <div class="pop-footer">
                                    Learn more and pledge: <a href="https://birdiesforeducation.com/" target="_blank">https://birdiesforeducation.com/</a>
                                </div>
                            </div>

                        </div>
                    </div>
                  </div>
                </div>
            </div>
                <script>
                    jQuery(document).ready(function(){
                        var ts = Math.round((new Date()).getTime() / 1000);
                        var modal = document.cookie;
                        var n = modal.indexOf("ts=");
                        if(n == -1){
                            jQuery('#home-modal').modal('show');
                            document.cookie = "ts="+ts;
                        } else if(ts > parseInt(getCookie('ts'))+86400) {
                            jQuery('#home-modal').modal('show');
                            document.cookie = "ts="+ts;
                        }
                    });
                    function getCookie(name) {
                        var value = "; " + document.cookie;
                        var parts = value.split("; " + name + "=");
                        if (parts.length == 2) return parts.pop().split(";").shift();
                      }
                </script>
EOD;
//        echo $modal;
	/*
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
	*/
}

function cur_front_page_head()
{
    echo '<meta name="p:domain_verify" content="cbc262324a1577e3971f1785d2bde5f3"/>';
}

genesis();