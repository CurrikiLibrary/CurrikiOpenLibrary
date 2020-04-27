<?php 
global $wpdb;

if ( bp_has_groups() ) : 
	while ( bp_groups() ) : 
		bp_the_group();
		do_action( 'bp_before_group_home_content' ); 

                
$group_url = bp_get_group_permalink();
$member_count = groups_get_total_member_count ( bp_get_group_id() );
$forum_count = 0; 
$library_count =  cur_get_resource_total_from_group( bp_get_group_id() );

	// echo "----";
	// echo bp_current_action();
	// echo bp_current_component(); 
	// echo "----";

$forum_ids = groups_get_groupmeta( bp_get_group_id(), 'forum_id', true );
if( !(is_array($forum_ids) && count( $forum_ids ) > 0) )
{
    $forum_ids = array();
}
//$forum_count = count($forum_ids);
$forum_id = count($forum_ids) > 0 ? $forum_ids[0] : 0;
$forum_count = $wpdb->get_var("SELECT count(ID) FROM {$wpdb->prefix}posts where post_type = 'topic' AND post_status = 'publish' AND post_parent = $forum_id");

// ======== [start] Manage Add to resource/collection buttons ==========
    if( get_current_user_id() > 0)
    {
        $is_user_member_of_group = false;
        
        
        
        if(bp_group_is_member())
        {
            $is_user_member_of_group = true;
        }
        
        
        $user_id = get_current_user_id();
        $group_id = bp_get_group_id();
        
        $sql_btn = "           
                    select * from cur_bp_groups cbg
                        left join cur_bp_groups_members cbgm on cbgm.group_id = cbg.id                    
                    where
                        cbg.id = $group_id                    
                        and cbgm.user_id = $user_id
                        and is_banned = 0";
        $group_member_record = $wpdb->get_results($sql_btn);        
    }
// ======== [end] Manage Add to resource/collection buttons ==========    
?>


<style type="text/css">
    .recently-active
    {
        min-width: 100% !important;
    }
    #whats-new-form
    {
        margin-left: 0px !important;
    }
    .internal-page #content ul li{
        float: none !important;
    }
</style>

<div class="member-tabs page-tabs"><div class="wrap container_12">

<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist">
<!--    
	<li class="ui-state-default ui-corner-top <?php if ( bp_current_action() == "home" ) { ?>ui-tabs-active ui-state-active<?php } ?> " role="tab" tabindex="0" aria-controls="activity" aria-labelledby="ui-id-1" aria-selected="true" aria-expanded="true">
		<a href="<?php echo $group_url; ?>" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-1"><span class="tab-icon fa fa-home"></span> <span class="tab-text">Activity</span></a></li>
	<li class="ui-state-default ui-corner-top <?php if ( bp_current_action() == "members" ) { ?>ui-tabs-active ui-state-active<?php } ?>" role="tab" tabindex="-1" aria-controls="members" aria-labelledby="ui-id-2" aria-selected="false" aria-expanded="false">
		<a href="<?php echo $group_url; ?>members" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-2"><span class="tab-icon fa fa-user"></span> <span class="tab-text">Members <span class="group-number">(<?php echo $member_count; ?>)</span></span></a></li>
	<li class="ui-state-default ui-corner-top <?php if ( bp_current_action() == "library" ) { ?>ui-tabs-active ui-state-active<?php } ?>" role="tab" tabindex="-1" aria-controls="resources" aria-labelledby="ui-id-3" aria-selected="false" aria-expanded="false">
		<a href="<?php echo $group_url; ?>resources" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-3"><span class="tab-icon fa fa-book"></span> <span class="tab-text">Resources <span class="group-number">(<?php echo $library_count; ?>)</span></span></a></li>
	<li class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="forums" aria-labelledby="ui-id-4" aria-selected="false" aria-expanded="false">
		<a href="<?php echo $group_url; ?>forums" class="ui-tabs-anchor <?php if ( bp_current_action() == "forum" ) { ?>ui-tabs-active ui-state-active<?php } ?>" role="presentation" tabindex="-1" id="ui-id-4"><span class="tab-icon fa fa-comments"></span> <span class="tab-text">Forums <span class="group-number">(<?php echo $forum_count; ?>)</span></span></a></li>-->
        <li class="ui-state-default ui-corner-top <?php if ( bp_current_action() == "home" ) { ?>ui-tabs-active ui-state-active<?php } ?> " role="tab" tabindex="0" aria-controls="activity" aria-labelledby="ui-id-1" aria-selected="true" aria-expanded="true"><a href="<?php echo $group_url; ?>" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-1"><span class="tab-icon fa fa-home"></span> <span class="tab-text"><?php echo __('Activity','curriki'); ?></span></a></li><li class="ui-state-default ui-corner-top <?php if ( bp_current_action() == "members" ) { ?>ui-tabs-active ui-state-active<?php } ?>" role="tab" tabindex="-1" aria-controls="members" aria-labelledby="ui-id-2" aria-selected="false" aria-expanded="false"><a href="<?php echo $group_url; ?>members" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-2"><span class="tab-icon fa fa-user"></span> <span class="tab-text"><?php echo __('Members','curriki'); ?> <span class="group-number">(<?php echo $member_count; ?>)</span></span></a></li><li class="ui-state-default ui-corner-top <?php if ( bp_current_action() == "library" ) { ?>ui-tabs-active ui-state-active<?php } ?>" role="tab" tabindex="-1" aria-controls="resources" aria-labelledby="ui-id-3" aria-selected="false" aria-expanded="false"><a href="<?php echo $group_url; ?>library" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-3"><span class="tab-icon fa fa-book"></span> <span class="tab-text"><?php echo __('Resources','curriki'); ?> <span class="group-number">(<?php echo $library_count; ?>)</span></span></a></li><?php if( count( $forum_ids ) > 0 ) { ?><li class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="forums" aria-labelledby="ui-id-4" aria-selected="false" aria-expanded="false"><a href="<?php echo $group_url; ?>forum" class="ui-tabs-anchor <?php if ( bp_current_action() == "forum" ) { ?>ui-tabs-active ui-state-active<?php } ?>" role="presentation" tabindex="-1" id="ui-id-4"><span class="tab-icon fa fa-comments"></span> <span class="tab-text"><?php echo __('Forum','curriki'); ?> <span class="group-number">(<?php echo $forum_count; ?>)</span></span></a></li><?php } ?>
</ul>

</div></div>

<div class="wrap container_12">
                                <div class="tab-contents ui-tabs-panel ui-widget-content ui-corner-bottom" id="activity" aria-labelledby="ui-id-1" role="tabpanel" aria-hidden="false">
                                    <div class="activity-sidebar page-sidebar grid_2">
                                        
                                        <?php if ( $member_count > 0 ) { 

                                            $args = array(
                                                'group_id'            => bp_get_current_group_id(),
                                                'per_page'            => 3,
                                                'page'                => 1,
                                                'exclude_admins_mods' => true,
                                                'exclude_banned'      => true,
                                                'exclude'             => false,
                                                'group_role'          => array(),
                                                'search_terms'        => false,
                                                'type'                => 'last_joined', // last_active?
                                            );

                                            $members = groups_get_group_members( $args );

                                            //print_r ($members);

                                        ?>
                                        <h4 class="sidebar-title"><?php echo __("Recently Active","curriki"); ?></h4>
                                        <div class="recently-active member-card card rounded-borders-full border-grey">
                                            <ul>
                                            <?php

                                                foreach ( $members as $member ) {

                                                    // print_r ($member);

                                                    $the_member_id = $member[0]->ID;

                                                    $city = cur_get_user_nonwp_data ( $the_member_id, 'city' );
                                                    $state = cur_get_user_nonwp_data ( $the_member_id, 'state' );
                                                    $country = cur_get_user_nonwp_data ( $the_member_id, 'country' );
                                                    if ( $city || $state || $country ) {
                                                        $location = $city . ', ' . $state . ' ' . $country;
                                                    }
                                                    $bio = cur_get_user_nonwp_data ( $the_member_id, 'bio' );
                                                    $profession = false;


                                            ?>
                                            <li class="member">
                                                <!-- <img class="border-grey" src="placehold.it/100x100" alt="member-name" /> -->
                                                <?php //echo bp_core_fetch_avatar( 'item_id='.$the_member_id ); ?>
                                                <?php                                                        
                                                        $userid = $the_member_id;
                                                        
                                                        $q_userinfo = "select * from users where userid = '".$userid."'";        
                                                        $userinfo = $wpdb->get_row($q_userinfo);                                                        
                                                        if(empty($userinfo->uniqueavatarfile)){
                                                            echo '<img width="150" height="150" alt="Profile picture of '.$full_name.'" class="avatar user-'.$userinfo->uniqueavatarfile.'-avatar avatar-'.$userinfo->uniqueavatarfile.' photo" src="'.get_stylesheet_directory_uri().'/images/user-icon-sample.png">';
                                                        }else{
                                                            echo '<img width="150" height="150" alt="Profile picture of '.$full_name.'" class="avatar user-'.$userinfo->uniqueavatarfile.'-avatar avatar-'.$userinfo->uniqueavatarfile.' photo" src="https://' . DBI_AWS_S3_CDN_URL . '//avatars/'.$userinfo->uniqueavatarfile.'">';
                                                        }                                                        
                                                ?>
                                                <div class="member-info">
                                                    <span class="member-name name"><?php echo bp_core_get_userlink( $the_member_id ); ?></span>
                                                    <span class="occupation"><?php if ( $profession ) { echo $profession; } ?></span><span class="location"><?php if ( $location ) { echo $location; } ?></span>
                                                </div>
                                            </li>
                                            <?php } ?>
                                            </ul>
                                            <a href="<?php echo $group_url; ?>members">
                                                <div class="card-button"><?php echo __('Browse All Members','curriki'); ?></div>
                                            </a>
                                        </div>
                                        <?php } ?>
                                        <!--<h4 class="sidebar-title">Recent Discussions</h4>
                                        <div class="recent-discussion card rounded-borders-full border-grey">
                                            <ul class="discussion">
                                                <li><a href="#">Discussion Topic Can be Very Long at Times, so We Need Space</a></li>
                                                <li><a href="#">Discussion Topic Goes Here</a></li>
                                                <li><a href="#">Discussion Topic Can be Very Long at Times, so We Need Space</a></li>
                                            </ul>
                                            <a href="<?php echo $group_url; ?>forums">
                                                <div class="card-button">Browse All Conversations</div>
                                            </a>
                                        </div>-->
                                    </div>
                                    <div class="activity-content grid_10">
                                        
                                        <div class="group-search page-search">
                                            <!--<div class="search-input grid_6 alpha">
                                                <div class="search-field">
                                                    <input type="text" placeholder="Search" class="rounded-borders-left">
                                                </div>
                                                <div class="search-button">
                                                    <button class="rounded-borders-right" type="submit"><span class="search-button-icon fa fa-search"></span></button>
                                                </div>
                                            </div>
                                            <div class="search-dropdown grid_4 omega">
                                                <select>
                                                    <option>English</option>
                                                </select>
                                            </div>-->
                                        </div>
                                        
                                        <div class="group-activity-container page-container rounded-borders-full border-grey">
                                            
											<div id="item-body">
<?php

global $wpdb;
$res = new CurrikiResources();

    $myid = get_current_user_id();
    
    $q_me = "SELECT * FROM users WHERE userid = '".$myid."'";
    $me = $wpdb->get_row($q_me);
    $myname = $me->firstname.' '.$me->lastname;
    $mylocation = $me->city;
    if(!empty($mylocation))$mylocation .= ', '.$me->state;
    if(!empty($mylocation))$mylocation .= ', '.$me->country;
    if(!empty($me->uniqueavatarfile)) $myphoto = "https://" . DBI_AWS_S3_CDN_URL . "//avatars/".$me->uniqueavatarfile; else $myphoto = get_stylesheet_directory_uri() . '/images/user-icon-sample.png';
    
    
    $order_by = "order by 1 desc, 5";
    if(empty($_GET['library_sorting']))                 $order_by = "order by 1 desc, 5";
    
    elseif($_GET['library_sorting'] == 'oldest')        $order_by = "order by contributiondate ASC";
    elseif($_GET['library_sorting'] == 'newest')        $order_by = "order by contributiondate DESC";
    elseif($_GET['library_sorting'] == 'rtc')           $order_by = "order by type DESC";
    elseif($_GET['library_sorting'] == 'ctr')           $order_by = "order by type ASC";
    elseif($_GET['library_sorting'] == 'mcf')           $order_by = "order by 1 desc, 5";
    elseif($_GET['library_sorting'] == 'mff')           $order_by = "order by 1 asc, 5";

    $groupid = bp_get_group_id();
    if($_GET['test_groupid']) $groupid = 1886;
    
    /*$q_resources = "select 'Favorite', ce.resourceid, title, type, displayseqno, r.memberrating, r.reviewrating, r.createdate, r.contributorid, r.contributiondate
    from resources r
    inner join collectionelements ce on ce.collectionid = r.resourceid
    inner join group_resources gr on gr.resourceid = r.resourceid and gr.groupid = '$groupid'
    where r.type = 'collection'
    and r.title = 'Favorites'
    and r.contributorid = '".$myid."'
    Union
    select 'Contributions', r.resourceid, title, type, NULL, r.memberrating, r.reviewrating, r.createdate, r.contributorid, r.contributiondate
    from resources r inner join group_resources gr on gr.resourceid = r.resourceid and gr.groupid = '$groupid'
    where contributorid = '".$myid."'
    and not (r.type = 'collection' and r.title = 'Favorites')
    ".$order_by;*/
    /*
    $q_resources = "select 'Contributions', r.resourceid, title, type, NULL, r.memberrating, r.reviewrating, r.createdate, r.contributorid, r.contributiondate, firstname, lastname, state, country, uniqueavatarfile
    from resources r
    inner join group_resources gr on gr.resourceid = r.resourceid
    left outer join users u on r.contributorid = u.userid
    where gr.groupid = '$groupid' ".$order_by;
    */
    $q_resources = "
                select 'Contributions', r.resourceid, title, type, NULL, r.memberrating, r.reviewrating, r.createdate,
                r.contributorid, r.contributiondate, firstname, lastname, state, country, uniqueavatarfile, if(ifnull(cgm.user_id, 'F') = 'F', 'F', 'T') as editable
                from resources r
                inner join group_resources gr on gr.resourceid = r.resourceid
                left outer join users u on r.contributorid = u.userid
                left outer join cur_bp_groups_members cgm on cgm.group_id = gr.groupid and r.contributorid = cgm.user_id
                where gr.groupid = '$groupid' ".$order_by;
    
    $resources = $wpdb->get_results($q_resources);
    $total_resources = count($resources);
    unset($resources);
    if(empty($_GET['page_no'])) $_GET['page_no'] = 0;
    if($_GET['page_no'] < 1) $_GET['page_no'] = 1;
    $start = (10 * $_GET['page_no']) - 10;
    
    $q_resources .= " limit $start, 10";
    $resources = $wpdb->get_results($q_resources);
    
	echo '<div class="user-library-content clearfix"><div class="wrap container_12">';

	// Access
	$user_library = '';

		//$user_library .= '<div class="user-library-breadcrumbs breadcrumbs grid_12">Resource Library > My Library</div>';

		$user_library .= '<div class="actions-row grid_12 clearfix">';
                
                $user_library .= '<div class="grid_8 alpha">';
                if(count($group_member_record) > 0)
                {
			
                    // $user_library.= '<button class="small-button green-button" onclick="window.location=\''.get_bloginfo('url').'/create-resource?type=collection&groupid='.$groupid.'\';"><span class="fa fa-search"></span> '.__('New Collection','curriki').'</button>';
                    // $user_library.= '<button class="small-button green-button" onclick="window.location=\''.get_bloginfo('url').'/create-resource?groupid='.$groupid.'\';"><span class="fa fa-plus-circle"></span> '.__('Upload Resource','curriki').'</button>';
                                            
                }
                $user_library.= '<button class="small-button green-button" onclick="window.location=\''.get_bloginfo('url').'/organize-collections/?groupid='.$groupid.'\'"><span class="fa fa-list"></span> '.__('Organize Collections','curriki').'</button>';
                $user_library.='</div>';
                        
			$user_library .= '<div class="search-dropdown grid_4 omega">'.curriki_library_sorting('my', 'top', $_GET['library_sorting']).'</div>';
		$user_library .= '</div>';

		$user_library .= '<div class="clearfix grid_12">';

			$library = '';
    foreach($resources as $collection){
        $myname = $collection->firstname.' '.$collection->lastname;
        $mylocation = $collection->city;
        if(!empty($mylocation))$mylocation .= ', '.$collection->state;else $mylocation = $collection->state;
        if(!empty($mylocation))$mylocation .= ', '.$collection->country;else $mylocation = $collection->country;
        
        $resourceUser = $res->getResourceUserById((int)$collection->resourceid, "");
        if (trim($resourceUser['uniqueavatarfile']) != '')
        {
            $myphoto = "https://" . DBI_AWS_S3_CDN_URL . "//avatars/".$resourceUser['uniqueavatarfile'];
        }else{
            $myphoto = get_stylesheet_directory_uri() . '/images/user-icon-sample.png';
        }
        
                        
        
        		// Collection - First Level
			$library .= '<div class="library-asset rounded-borders-full border-grey library-collection">';
                            if($collection->type == 'collection')$type_class = "fa-folder";
                            elseif($collection->type == 'resource')$type_class = "fa-image";
                            else $type_class = "fa-folder-open";
				$library .= '<div class="library-icon"><span class="fa '.$type_class.'"></span></div>';
                                
                                $collection_title = ($collection->title?$collection->title:'Go to Collection');
                                $collection_title = strip_tags($collection_title);
                                $collection_title = strlen( $collection_title ) > 30 ? (substr($collection_title,0,30)." ...") : ($collection_title);                                
                                
                                
                                $myname = strip_tags($myname);
                                $myname = strlen( $myname ) > 11 ? (substr($myname,0,11)." ...") : ($myname);
                                
                                
				$library .= '<div class="library-title vertical-align"><a href="'.get_bloginfo('url').'/oer/?rid='.$collection->resourceid.'&back_url='.  base64_encode($group_url.'library').'">'.$collection_title.'</a></div>';
                                
				$library .= '<div class="library-author vertical-align">';
					$library .= '<img src="' .$myphoto. '" alt="member-name" />';
					$library .= '<div class="library-author-info">';
						$library .= '<span class="member-name name">'.$myname.'</span><span class="location">'.$mylocation.'</span>';
					$library .= '</div>';
					$library .= '<div class="member-more"><a href="'.get_bloginfo('url').'/user-library?userid='.$collection->contributorid.'">'.__('More from this member','curriki').'</a></div>';
				$library .= '</div>';
				$library .= '<div class="library-rating rating vertical-align"><span class="member-rating-title">'.__('Member Rating','curriki').'</span>';
					$library .= curriki_member_rating($collection->memberrating);
					if(get_current_user_id() > 0)
                                        {
                                            $library .= '<a href="javascript:;" onclick="jQuery(\'#rate_resource-dialog\').show(); jQuery(\'#review-resource-id\').val('.$collection->resourceid.'); jQuery(\'.curriki-review-title\').html(\''.($collection->title?$collection->title:'Go to Collection').'\'); setInterval(function () {jQuery( \'#rate_resource-dialog\' ).center()}, 1);">'.__('Rate this resource','curriki').'</a>';
                                        }                                        
				$library .= '</div>';
                                $reviewrating = round($collection->reviewrating);
                                if($reviewrating == 0)
                                    $library .= '<div class="library-curriki-rating curriki-rating vertical-align"><span class="curriki-rating-title">'.__('Curriki Rating','curriki').'</span><span class="rating-badge-nr">NR</span></div>';
                                else
                                    $library .= '<div class="library-curriki-rating curriki-rating vertical-align"><span class="curriki-rating-title">'.__('Curriki Rating','curriki').'</span><span class="rating-badge">'.$reviewrating.'</span></div>';
				$library .= '<div class="library-date vertical-align">'.date('M d, Y', strtotime($collection->contributiondate)).'</div>';
				$library .= '<div class="library-actions vertical-align">';
					
                                        $show_edit_option = property_exists($collection, "editable") && isset($collection->editable) && $collection->editable === 'T' ? true : false;
                                        if($show_edit_option)
                                        {
                                            // $library .= '<a href="'.get_bloginfo('url').'/create-resource/?resourceid='.$collection->resourceid.'"><span class="fa fa-edit"></span> <span>'.__('Edit','curriki').'</span></a>';
                                        }
					//if($collection->Favorite != 'Contributions')
                                        //    $library .= '<a href="#"><span class="fa fa-trash"></span> <span>Remove</span></a>';
					//$library .= '<a href="#"><span class="fa fa-copy"></span> <span>Duplicate</span></a>';
					$library .= curriki_sharethis($collection->resourceid, ($collection->title?$collection->title:'Go to Collection'));
				$library .= '</div>';
			$library .= '</div>';
    }


			$user_library .= $library;

		$user_library .= '</div>';

                $user_library .= library_pagination(get_bloginfo('url').'/groups/'.bp_get_current_group_slug().'/library?library_sorting='.$_GET['library_sorting'], $_GET['page_no'], ceil($total_resources/10));

		$user_library .= '<div class="actions-row grid_12 clearfix">';
                
                /*
                if(count($group_member_record) > 0)
                {
			$user_library .= '<div class="grid_8 alpha"><button class="small-button green-button" onclick="window.location=\''.get_bloginfo('url').'/create-resource?type=collection&groupid='.$groupid.'\';"><span class="fa fa-search"></span> New Collection</button><button class="small-button green-button" onclick="window.location=\''.get_bloginfo('url').'/create-resource?groupid='.$groupid.'\';"><span class="fa fa-plus-circle"></span> Upload Resource</button><button class="small-button green-button" onclick="window.location=\''.get_bloginfo('url').'/organize-collections?groupid='.$groupid.'\'"><span class="fa fa-list"></span> Organize Collections</button></div>';
                }
                 * 
                 */
                
                $user_library .= '<div class="grid_8 alpha">';
                if(count($group_member_record) > 0)
                {			
                    // $user_library.= '<button class="small-button green-button" onclick="window.location=\''.get_bloginfo('url').'/create-resource?type=collection&groupid='.$groupid.'\';"><span class="fa fa-search"></span> '.__('New Collection','curriki').'</button>';
                    // $user_library.= '<button class="small-button green-button" onclick="window.location=\''.get_bloginfo('url').'/create-resource?groupid='.$groupid.'\';"><span class="fa fa-plus-circle"></span> '.__('Upload Resource','curriki').'</button>';
                                            
                }
                $user_library.= '<button class="small-button green-button" onclick="window.location=\''.get_bloginfo('url').'/organize-collections/?groupid='.$groupid.'\'"><span class="fa fa-list"></span> '.__('Organize Collections','curriki').'</button>';
                $user_library.='</div>';
                
                
			$user_library .= '<div class="search-dropdown grid_4 omega">'.curriki_library_sorting('my', 'bottom', $_GET['library_sorting']).'</div>';
		$user_library .= '</div>';

		echo $user_library;

	echo '</div></div>';
?>

											</div>

                                        </div>
                                    </div>
                                </div>

                                
                            </div>
                            

						


                        </div>

<?php /* 
	<div id="item-nav">
		<div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
			<ul>
				<?php bp_get_options_nav(); do_action( 'bp_group_options_nav' ); ?>
			</ul>
			<div class="clear"></div>
		</div>
	</div> */ ?>


<?php 
		do_action( 'bp_after_group_home_content' );
	endwhile; 
endif;    

add_action('genesis_after', 'curriki_library_scripts');
add_action('genesis_after', 'curriki_addthis_scripts');
gconnect_get_footer();
?>