<?php
/*
* Template Name: Organize Collections
*
* Child Theme Name: Curriki Child Theme for Genesis 2.1
* Author: Orange Blossom Media
* Url: http://orangeblossommedia.com/
*/

if(!is_user_logged_in() and function_exists('curriki_redirect_login')){curriki_redirect_login();die;}

// Add custom body class to the head
add_filter( 'body_class', 'curriki_user_my_library_add_body_class' );
function curriki_user_my_library_add_body_class( $classes ) {
   $classes[] = 'backend user-dashboard';
   return $classes;

}

// Execute custom style guide page
add_action( 'genesis_meta', 'curriki_custom_user_my_library_loop' );
function curriki_custom_user_my_library_loop() {
	//* Force full-width-content layout setting
	add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

	remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
	remove_action( 'genesis_loop', 'genesis_do_loop' );

	add_action( 'genesis_loop', 'curriki_user_my_library_body', 15 );
}

/*function curriki_sharethis($url, $title = ''){
    return '<a href="https://www.addthis.com/bookmark.php?source=tbx32nj-1.0&v=300&url='.$url.'" target="_blank"><span class="fa fa-share-alt-square"></span> <span>Share</span></a>';
}*/

function curriki_user_my_library_body() {
    global $wpdb;
    if($_GET['page_no'] < 1) $_GET['page_no'] = 1;
    $start = (10 * $_GET['page_no']) - 10;
    $groupid = addslashes($_GET['groupid']);
    $pagination_url = get_bloginfo('url').'/organize-collections/?oc=1';
    if(isset($groupid) and $groupid > 0){
        $q_collections = "select distinct ce.collectionid, r.title, r.resourceid, r.resourceid, r.memberrating, r.contributiondate
        from group_resources gr
        inner join resources r on gr.resourceid = r.resourceid
        inner join collectionelements ce on ce.collectionid = r.resourceid
        where type = 'collection'
        and groupid = '$groupid'";
        $collections = $wpdb->get_results($q_collections.' limit '.$start. ', 10;');
        $total_collections_q = "select count(distinct ce.collectionid)
        from group_resources gr
        inner join resources r on gr.resourceid = r.resourceid
        inner join collectionelements ce on ce.collectionid = r.resourceid
        where type = 'collection'
        and groupid = '$groupid'";
        $total_collections = $wpdb->get_var($total_collections_q);
        $pagination_url .= "&groupid=".$groupid;
    }else{
        $myid = get_current_user_id();
        $q_user = "SELECT * FROM users WHERE userid = '".$myid."'";
        $user = $wpdb->get_row($q_user);

        $q_collections = "select * from resources where type = 'collection' and contributorid = '".$myid."' limit ".$start.", 10;";
        $collections = $wpdb->get_results($q_collections);
        $total_collections_q = "select count(*) from resources "
                . "where type = 'collection' and contributorid = '".$myid."' ";
        $total_collections = $wpdb->get_var($total_collections_q);
    }
	echo '<div class="user-library-content clearfix"><div class="wrap container_12">';

	// Access
	$user_library = '';

		$user_library .= '<div class="user-library-breadcrumbs breadcrumbs grid_12">'.__('Organize Collections','curriki').'</div>';
                if($total_collections > 0){
                
		$user_library .= '<div class="grid_12">'.__('Select the title of a collection to organize your resources.','curriki').'</div>';

		//$user_library .= '<div class="actions-row grid_12 clearfix">';
			//$user_library .= '<div class="grid_8 alpha"><button class="small-button green-button"><span class="fa fa-search"></span> New Collection</button><button class="small-button green-button" onclick="window.location=\''.get_bloginfo('url').'/create-resource\';"><span class="fa fa-plus-circle"></span> Upload Resource</button><button class="small-button green-button" onclick="window.location=\''.get_bloginfo('url').'/organize-collections\'"><span class="fa fa-list"></span> Organize Collections</button></div>';
			//$user_library .= '<div class="search-dropdown grid_4 omega">'.curriki_library_sorting('user', 'top', $_GET['library_sorting'], $userid).'</div>';
		//$user_library .= '</div>';

		$user_library .= '<div class="clearfix grid_12">';

			$library = '';
                            if($total_collections > 0)
                            foreach($collections as $collection){
                                // Collection - First Level
                                $library .= '<div class="library-asset rounded-borders-full border-grey library-collection">';
                                        $library .= '<div class="library-icon"><span class="fa fa-folder"></span></div>';
                                        $library .= '<div class="library-title vertical-align"><a href="javascript:;" class="organize-collections-title" id="organize-collections-title-'.$collection->resourceid.'">'. stripslashes($collection->title) .'</a></div>';
                                        $library .= '<div class="library-author vertical-align">';
                                                //$library .= '<img src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
                                                //$library .= '<div class="library-author-info">';
                                                //	$library .= '<span class="member-name name">Member Name</span><span class="location">Toronto, Ontario, Canada</span>';
                                                //$library .= '</div>';
                                                //$library .= '<div class="member-more"><a href="'.get_bloginfo('url').'/user-library">More from this member</a></div>';
                                        $library .= '</div>';
                                        $library .= '<div class="library-rating rating vertical-align"><span class="member-rating-title">'.__('Member Rating','curriki').'</span>';

                                                $library .= curriki_member_rating($collection->memberrating);
                                                //$library .= '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>';

                                                $library .= '<a href="javascript:;" onclick="jQuery(\'#rate_resource-dialog\').show(); jQuery(\'#review-resource-id\').val('.$collection->resourceid.'); jQuery(\'.curriki-review-title\').html(\''.$collection->title.'\'); setInterval(function () {jQuery( \'#rate_resource-dialog\' ).center()}, 1);">'.__('Rate this resource','curriki').'</a>';
                                        $library .= '</div>';
                                        $reviewrating = round($collection->reviewrating);
                                        if($reviewrating == 0)
                                            $library .= '<div class="library-curriki-rating curriki-rating vertical-align"><span class="curriki-rating-title">'.__('Curriki Rating','curriki').'</span><span class="rating-badge-nr">NR</span></div>';
                                        else
                                            $library .= '<div class="library-curriki-rating curriki-rating vertical-align"><span class="curriki-rating-title">'.__('Curriki Rating','curriki').'</span><span class="rating-badge">'.$reviewrating.'</span></div>';
                                        $library .= '<div class="library-date vertical-align">'.date('M d, Y', strtotime($collection->contributiondate)).'</div>';
                                        $library .= '<div class="library-actions vertical-align">';
                                                //$library .= '<a href="#"><span class="fa fa-edit"></span> <span>Edit</span></a>';
                                                //$library .= '<a href="#"><span class="fa fa-trash"></span> <span>Delete</span></a>';
                                                // $library .= '<a href="#"><span class="fa fa-copy"></span> <span>'.__('Duplicate','curriki').'</span></a>';
                                                $library .= curriki_sharethis($collection->resourceid, $collection->title);
                                        $library .= '</div>';
                                $library .= '</div>';
                                //$library .= '<div class="resources-of-collection" id="resources-of-'.$collection->resourceid.'"></div>';
                            }
			$user_library .= $library;

		$user_library .= '</div>';
                }else{
                    $user_library .= '<div class="grid_12">You do not currently have any collections to organize.</div>';
                }

		
                
                $user_library .= library_pagination($pagination_url, $_GET['page_no'], ceil($total_collections/10));

		//$user_library .= '<div class="actions-row grid_12 clearfix">';
		//	$user_library .= '<div class="grid_8 alpha"><button class="small-button green-button"><span class="fa fa-search"></span> New Collection</button><button class="small-button green-button" onclick="window.location=\''.get_bloginfo('url').'/create-resource\';"><span class="fa fa-plus-circle"></span> Upload Resource</button><button class="small-button green-button" onclick="window.location=\''.get_bloginfo('url').'/organize-collections\'"><span class="fa fa-list"></span> Organize Collections</button></div>';
		//	$user_library .= '<div class="search-dropdown grid_4 omega">'.curriki_library_sorting('user', 'bottom', $_GET['library_sorting'], $userid).'</div>';
		//$user_library .= '</div>';

		echo $user_library;

	echo '</div></div>';

}
add_action('genesis_after', 'curriki_library_scripts');
add_action('genesis_after', 'curriki_addthis_scripts');
add_action('genesis_after', 'curriki_organize_collections_scripts');
function curriki_organize_collections_scripts(){

//    wp_enqueue_script( 'jquery-js', 'https://code.jquery.com/jquery-1.10.2.js', array(), '1.10.2', true );
   wp_enqueue_script( 'jquery-ui', 'https://code.jquery.com/ui/1.11.4/jquery-ui.js', array(), '1.11.4', true );
   wp_enqueue_style( 'organize-collections-css', get_stylesheet_directory_uri() . '/css/organize-collections.css' );
   wp_enqueue_script( 'organize-collections-js', get_stylesheet_directory_uri() . '/js/organize-collections.js', array(), false, true );
   wp_localize_script('organize-collections-js', 'organize_collections_js_vars', array(
           'url' => get_bloginfo('url')
       )
   );
?>

            <div class="modal modal-secondary fade" id="modal-collections" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-wrap">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">×</button>
                                <h4 class="modal-title">Organize Collections</h4>
                            </div>
                            <div class="modal-body organize-collection-resources">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <?php
}

genesis();