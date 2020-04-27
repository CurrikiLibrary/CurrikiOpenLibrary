<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
gconnect_get_header();


do_action( 'bp_before_member_home_content' );

$user_id = bp_displayed_user_id();
$current_user = wp_get_current_user();
//if($_GET['testx123']){
//    echo $user_id;
//    die('test12');
//}
$friend_count = friends_get_total_friend_count ( $user_id );
$group_count = groups_get_total_member_count ( $user_id );

$city = cur_get_user_nonwp_data ( $user_id, 'city' );
$state = cur_get_user_nonwp_data ( $user_id, 'state' );
$country = cur_get_user_nonwp_data ( $user_id, 'country' );
if ( $city || $state || $country ) {
	$location = $city . ', ' . $state . ' ' . $country;
}
$bio = cur_get_user_nonwp_data ( $user_id, 'bio' );
$profession = false;

global $bp , $wpdb;
$user = $wpdb->get_row("SELECT * FROM cur_users WHERE ID = {$user_id}");

if(!isset($group_url)) $group_url = "";

    /*
    $q_resources = "select count(r.resourceid) as count
    from resources r
    inner join collectionelements ce on ce.collectionid = r.resourceid
    inner join resources r2 on ce.resourceid = r2.resourceid
    left join users u on u.userid = r2.contributorid
    where r.type = 'collection'
    and r.title = 'Favorites'
    and r.contributorid = '".$user_id."'
    Union
    select count(r.resourceid) as count
    from resources r left join users u on u.userid = r.contributorid
    where contributorid = '".$user_id."'
    and not (r.type = 'collection' and r.title = 'Favorites')";
     * 
     */
    $q_resources = "select count(resourceid) as count from resources
         WHERE contributorid = $user_id and not (type = 'collection' and title = 'Favorites') and active = 'T'";
    $resources = $wpdb->get_results($q_resources);    
    $total_resources = $resources[0]->count;
?>


<div id="item-header" role="complementary">
        <?php gconnect_locate_template( array( 'members/single/member-header.php' ), true ); ?>
</div><!-- #item-header -->

<?php

	$member_url = bp_displayed_user_domain();
	//$friend_count = friends_get_total_friend_count ( bp_displayed_user_id() );
	$friend_count = 0;        
        if($bp->displayed_user->id == $bp->loggedin_user->id)
        {        
            $friend_count = $bp->loggedin_user->total_follow_counts['following'];        
        }else{
            $friend_count = $bp->displayed_user->total_follow_counts['following'];        
        }
        
	$group_count = groups_total_groups_for_user ( bp_displayed_user_id() );
	$library_count =  cur_get_resource_total_from_member( bp_displayed_user_id() );

	// echo bp_current_component();

        $forum_count = 0; 
        $forum_ids = groups_get_groupmeta( bp_get_group_id(), 'forum_id', true );
        if( !(is_array($forum_ids) && count( $forum_ids ) > 0) )
        {
            $forum_ids = array();
        }
        $forum_count = count($forum_ids);
        
?>
<style type="text/css">        
    .member-tabs ul li{
        float: none !important;
    }
</style>


<div class="member-tabs page-tabs"><div class="wrap container_12">
<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist">
<!--	<li class="ui-state-default ui-corner-top <?php if ( bp_current_component() != "activity" ) { ?><?php } else { ?>ui-tabs-active ui-state-active<?php } ?>" role="tab" tabindex="0" aria-controls="activity" aria-labelledby="ui-id-1" aria-selected="true" aria-expanded="true">
		<a href="<?php echo $member_url; ?>"><span class="tab-icon fa fa-home"></span> <span class="tab-text">Activity</span></a>
	</li>
	<li class="ui-state-default ui-corner-top <?php if ( bp_current_component() != "following" ) { ?><?php } else { ?>ui-tabs-active ui-state-active<?php } ?>" role="tab" tabindex="0" aria-controls="activity" aria-labelledby="ui-id-1" aria-selected="true" aria-expanded="true">
		<a href="<?php echo $member_url; ?>following"><span class="tab-icon fa fa-user"></span> <span class="tab-text">Following<span class="member-number">(<?php echo $friend_count; ?>)</span></span></a>
	</li>
	<li class="ui-state-default ui-corner-top <?php if ( bp_current_component() != "groups" ) { ?><?php } else { ?>ui-tabs-active ui-state-active<?php } ?>" role="tab" tabindex="0" aria-controls="activity" aria-labelledby="ui-id-1" aria-selected="true" aria-expanded="true">
		<a href="<?php echo $member_url; ?>groups"><span class="tab-icon fa fa-users"></span> <span class="tab-text">Groups <span class="member-number">(<?php echo $group_count; ?>)</span></span></a>
	</li>	-->
    <!--
    <li class="ui-state-default ui-corner-top <?php if ( bp_current_action() == "home" ) { ?>ui-tabs-active ui-state-active<?php } ?> " role="tab" tabindex="0" aria-controls="activity" aria-labelledby="ui-id-1" aria-selected="true" aria-expanded="true"><a href="<?php echo $group_url; ?>" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-1"><span class="tab-icon fa fa-home"></span> <span class="tab-text">Activity</span></a></li><li class="ui-state-default ui-corner-top <?php if ( bp_current_action() == "members" ) { ?>ui-tabs-active ui-state-active<?php } ?>" role="tab" tabindex="-1" aria-controls="members" aria-labelledby="ui-id-2" aria-selected="false" aria-expanded="false"><a href="<?php echo $group_url; ?>members" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-2"><span class="tab-icon fa fa-user"></span> <span class="tab-text">Members <span class="group-number">(<?php echo $friend_count; ?>)</span></span></a></li><li class="ui-state-default ui-corner-top <?php if ( bp_current_action() == "resources" ) { ?>ui-tabs-active ui-state-active<?php } ?>" role="tab" tabindex="-1" aria-controls="resources" aria-labelledby="ui-id-3" aria-selected="false" aria-expanded="false"><a href="<?php echo $group_url; ?>library" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-3"><span class="tab-icon fa fa-book"></span> <span class="tab-text">Resources <span class="group-number">(<?php echo $library_count; ?>)</span></span></a></li><?php if( count( $forum_ids ) > 0 ) { ?><li class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="forums" aria-labelledby="ui-id-4" aria-selected="false" aria-expanded="false"><a href="<?php echo $group_url; ?>forum" class="ui-tabs-anchor <?php if ( bp_current_action() == "forum" ) { ?>ui-tabs-active ui-state-active<?php } ?>" role="presentation" tabindex="-1" id="ui-id-4"><span class="tab-icon fa fa-comments"></span> <span class="tab-text">Forums <span class="group-number">(<?php echo $forum_count; ?>)</span></span></a></li><?php } ?>
    -->    
    <?php
        $lang_in_slug = "";
        if( defined('ICL_LANGUAGE_CODE') )
        {
            $lang_in_slug = '/'.ICL_LANGUAGE_CODE;
        }
    ?>    
    <li class="ui-state-default ui-corner-top <?php if ( bp_current_component() != "activity" ) { ?><?php } else { ?>ui-tabs-active ui-state-active<?php } ?>" role="tab" tabindex="0" aria-controls="activity" aria-labelledby="ui-id-1" aria-selected="true" aria-expanded="true"><a href="<?php echo $member_url; ?>"><span class="tab-icon fa fa-home"></span> <span class="tab-text"><?php echo __('Activity','curriki'); ?></span></a></li><li class="ui-state-default ui-corner-top <?php if ( bp_current_component() != "following" ) { ?><?php } else { ?>ui-tabs-active ui-state-active<?php } ?>" role="tab" tabindex="0" aria-controls="activity" aria-labelledby="ui-id-1" aria-selected="true" aria-expanded="true"><a href="<?php echo $member_url; ?>following"><span class="tab-icon fa fa-user"></span> <span class="tab-text"><?php echo __('Following','curriki'); ?><span class="member-number">(<?php echo $friend_count; ?>)</span></span></a></li><li class="ui-state-default ui-corner-top <?php if ( bp_current_component() != "groups" ) { ?><?php } else { ?>ui-tabs-active ui-state-active<?php } ?>" role="tab" tabindex="0" aria-controls="activity" aria-labelledby="ui-id-1" aria-selected="true" aria-expanded="true"><a href="<?php echo $member_url; ?>groups"><span class="tab-icon fa fa-users"></span> <span class="tab-text"><?php echo __('Groups','curriki'); ?> <span class="member-number">(<?php echo $group_count; ?>)</span></span></a></li><li class="ui-state-default ui-corner-top <?php if ( bp_current_component() != "resources" ) { ?><?php } else { ?>ui-tabs-active ui-state-active<?php } ?>" role="tab" tabindex="0" aria-controls="activity" aria-labelledby="ui-id-1" aria-selected="true" aria-expanded="true"><a href="<?php echo site_url().$lang_in_slug ; ?>/members/<?php echo $user->user_nicename; ?>/resources"><span class="tab-icon fa fa-book"></span> <span class="tab-text"><?php echo __('Resources','curriki'); ?> <span class="member-number">(<?php echo $total_resources; ?>)</span></span></a></li>
</ul>
</div></div>

<div class="member-content dashboard-tabs-content"><div class="wrap container_12">

			<div id="activity" class="tab-contents">
				<div class="activity-sidebar page-sidebar grid_2">
					<?php

					if ( $friend_count == 0 ) { ?>

					<h4 class="sidebar-title"><?php echo __('Friends','curriki'); ?></h4>
					<p><?php echo __('This user is not currently following anyone','curriki'); ?></p>

					<?php } else { 

						$friends = friends_get_friend_user_ids ( bp_displayed_user_id() );
						$friends_to_display = array_slice($friends, 0, 3);

					?>

					<?php } ?>
					<?php

					if ( $group_count == 0 ) { ?>

					<h4 class="sidebar-title"><?php echo __('Groups','curriki'); ?></h4>
					<p><?php echo __('This user currently has no groups','curriki'); ?>.</p>

					<?php } else { 

						$groups = groups_get_user_groups ( bp_displayed_user_id() );
						$groups_to_display = array_slice($groups['groups'], 0, 3);

						// print_r ($groups_to_display);

					?>
					<h4 class="sidebar-title"><?php echo __('Groups','curriki'); ?></h4>
					<div class="groupsx card rounded-borders-full border-grey">
						<ul class="discussion">
							<?php

								foreach ( $groups_to_display as $the_group_id) { 

								$avatar_options = array ( 'class' => 'border-grey', 'item_id' => $the_group_id, 'object' => 'group', 'type' => 'thumbnail' );

								$result = bp_core_fetch_avatar($avatar_options);

								$group = groups_get_group( array( 'group_id' => $the_group_id) );

							?>
							<li class="group">
								<?php echo $result; ?>
								<!-- <img class="border-grey" src="placehold.it/100x100" alt="member-name" /> -->
								<div class="group-info"><a href="<?php echo bp_get_group_permalink( $group ); ?>"><span class="group-name name"><?php echo $group->name; ?></a></div>
							</li>
							<?php } ?>
						</ul>
						<a href="<?php echo $member_url; ?>groups"><div class="card-button"><?php echo __('See All Groups','curriki'); ?></div></a>
					</div>
					<?php } ?>
				</div>
				<div class="activity-content grid_10">                                            
						<div id="item-body">

<?php //do_action( 'bp_before_member_' . bp_current_action() . '_content' ); ?>

<?php // this is important! do not remove the classes in this DIV as AJAX relies on it! ?>
                                                    
<div id="members-dir-list" class="dir-list members follow <?php echo bp_current_action(); ?>">
        <?php 
            
        
  global $wpdb;
  
  $userid = addslashes( bp_displayed_user_id() );
  //var_dump($userid);die;
  if (empty($userid) and ! empty($_GET['user'])) {
    $userid = $wpdb->get_var("select ID from cur_users where user_nicename = '" . addslashes($_GET['user']) . "'");
  }
  $q_user = "SELECT users.*,cur_users.user_nicename FROM users 
             LEFT JOIN cur_users on cur_users.ID = users.userid
          WHERE userid = '" . $userid . "'";
  $user = $wpdb->get_row($q_user);
  
  $order_by = "";
  if (empty($_GET['library_sorting']))
    $order_by = "order by title ASC";
  //elseif($_GET['library_sorting'] == 'displayseqno')  $order_by = "";
  elseif ($_GET['library_sorting'] == 'oldest')
    $order_by = "order by contributiondate ASC";
  elseif ($_GET['library_sorting'] == 'newest')
    $order_by = "order by contributiondate DESC";
  elseif ($_GET['library_sorting'] == 'rtc')
    $order_by = "order by type DESC";
  elseif ($_GET['library_sorting'] == 'ctr')
    $order_by = "order by type ASC";
  elseif ($_GET['library_sorting'] == 'aza')
    $order_by = "order by title ASC";
  elseif ($_GET['library_sorting'] == 'azd')
    $order_by = "order by title DESC";
  elseif ($_GET['library_sorting'] == 'ru')
    $order_by = "order by lasteditdate DESC";

  
  $total_resources_q = "select count(*) from resources where contributorid = '" . $userid . "' and not (type = 'collection' and title = 'Favorites') and active = 'T'";
  $total_resources = $wpdb->get_var($total_resources_q);

  $q_resources = "select * from resources where contributorid = '" . $userid . "' and not (type = 'collection' and title = 'Favorites') and active = 'T' $order_by";
  
  if(empty($_GET['page_no']) or $_GET['page_no'] < 1) $_GET['page_no'] = 1;
    $start = (10 * $_GET['page_no']) - 10;
    
    $q_resources .= " limit $start, 10";
    $resources = $wpdb->get_results($q_resources);
  
  echo '<div class="user-library-content clearfix"><div class="wrap container_12 library-wrapper">';

  // Access
  $user_library = '';

  //$user_library .= '<div class="user-library-breadcrumbs breadcrumbs grid_12">Resource Library > ' . $user->firstname . ' ' . $user->lastname . '</div>';

  /*
  $user_library .= '<div class="actions-row grid_12 clearfix">';
  $user_library .= '<div class="grid_8 alpha"><button class="small-button green-button"><span class="fa fa-search"></span> New Collection</button><button class="small-button green-button" onclick="window.location=\'' . get_bloginfo('url') . '/create-resource\';"><span class="fa fa-plus-circle"></span> Upload Resource</button><button class="small-button green-button" onclick="window.location=\'' . get_bloginfo('url') . '/organize-collections\'"><span class="fa fa-list"></span> Organize Collections</button></div>';
  $user_library .= '<div class="search-dropdown grid_4 omega">' . curriki_library_sorting('user', 'top', $_GET['library_sorting'], $userid) . '</div>';
  $user_library .= '</div>';
  */
  $user_library .= '<div class="actions-row grid_12 clearfix">';
    $user_library .= '<div class="search-dropdown grid_4 omega">' . curriki_library_sorting('user', 'top', (isset($_GET['library_sorting']) ? $_GET['library_sorting'] : ""), $userid) . '</div>';
  $user_library .= '</div>';
  
  $user_library .= '<div class="clearfix grid_12">';

  $library = '';
  foreach ($resources as $resource) {
    // Collection - First Level
    $library .= '<div class="library-asset rounded-borders-full border-grey library-collection">';
    if ($resource->type == 'collection')
      $type_class = "fa-folder";
    elseif ($resource->type == 'resource')
      $type_class = "fa-image";
    else
      $type_class = "fa-folder-open";
    $library .= '<div class="library-icon"><span class="fa ' . $type_class . '"></span></div>';
    
    $title = $resource->title ? $resource->title:'Go to Resource';
    $title = strlen( $title ) > 50 ? (substr($title,0,50)." ...") : ($title);
    $title = stripslashes($title);
            
    $library .= '<div class="library-title vertical-align"><a href="' . get_bloginfo('url') . '/oer/?rid=' . $resource->resourceid . '">' . $title . '</a></div>';
    //$library .= '<div class="library-author vertical-align">';
    //$library .= '<img src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
    //$library .= '<div class="library-author-info">';
    //	$library .= '<span class="member-name name">Member Name</span><span class="location">Toronto, Ontario, Canada</span>';
    //$library .= '</div>';
    //$library .= '<div class="member-more"><a href="'.get_bloginfo('url').'/user-library">More from this member</a></div>';
    //$library .= '</div>';
    $library .= '<div class="library-rating rating vertical-align" style="width:160px;"><span class="member-rating-title">'.__('Member Rating','curriki').'</span>';

    $library .= curriki_member_rating($resource->memberrating);
    //$library .= '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>';

    $library .= '<a href="javascript:;" onclick="jQuery(\'#rate_resource-dialog\').show(); jQuery(\'#review-resource-id\').val(' . $resource->resourceid . '); jQuery(\'.curriki-review-title\').html(\'' . "" . '\'); setInterval(function () {jQuery( \'#rate_resource-dialog\' ).center()}, 1);">'.__('Rate this resource','curriki').'</a>';
    $library .= '</div>';
    
    $reviewrating = isset($resource->reviewrating) ? round((float) $resource->reviewrating, 1) : null;                
    $qtip_text = "";
    if (isset($resource->reviewstatus) && $resource->reviewstatus == 'reviewed' && $reviewrating != null && $reviewrating >= 0) {                        
        $library .= '<div class="library-curriki-rating curriki-rating vertical-align"><span class="curriki-rating-title">'.__('Curriki Rating','curriki').'</span><span class="rating-badge">' . $reviewrating . '</span></div>';           
    } elseif (isset($resource->reviewstatus) && $resource->reviewstatus == 'reviewed' && $reviewrating != null && $reviewrating < 0) {            
        $library .= '<div class="library-curriki-rating curriki-rating vertical-align"><span class="curriki-rating-title">'.__('Curriki Rating','curriki').'</span><span class="rating-badge">' . $reviewrating . '</span></div>';
    } elseif (isset($resource->partner) && $resource->partner == 'T') {                        
        $library .= '<div class="library-curriki-rating curriki-rating vertical-align"><span class="curriki-rating-title">'.__('Curriki Rating','curriki').'</span><span class="rating-badge">' . 'P' . '</span></div>';
    } else {                        
        $library .= '<div class="library-curriki-rating curriki-rating vertical-align"><span class="curriki-rating-title">'.__('Curriki Rating','curriki').'</span><span class="rating-badge-nr">' . 'NR' . '</span></div>';        
    }                                
    /*    
    $reviewrating = round($resource->reviewrating);
    if ($reviewrating == 0)
      $library .= '<div class="library-curriki-rating curriki-rating vertical-align"><span class="curriki-rating-title">'.__('Curriki Rating','curriki').'</span><span class="rating-badge-nr">NR</span></div>';
    else
      $library .= '<div class="library-curriki-rating curriki-rating vertical-align"><span class="curriki-rating-title">Curriki Rating</span><span class="rating-badge">' . $reviewrating . '</span></div>';    
    */
    
    $library .= '<div class="library-date vertical-align">' . date('M d, Y', strtotime($resource->contributiondate)) . '</div>';
    $library .= '<div class="library-actions vertical-align">';
    //$library .= '<a href="#"><span class="fa fa-edit"></span> <span>Edit</span></a>';
    //$library .= '<a href="#"><span class="fa fa-trash"></span> <span>Delete</span></a>';
    if (isset($current_user->caps['administrator']) && $resource->approvalStatus == 'pending') {
        $library .= '<a onclick="return approveResource('.$resource->resourceid.');" href="#"><span class="fa fa-check"></span> <span>'.__('Approve','curriki').'</span></a>';
        $library .= '<a onclick="return rejectResource('.$resource->resourceid.');" href="#"><span class="fa fa-ban"></span> <span>'.__('Reject','curriki').'</span></a>';
    }
    $library .= '<a href="' . get_bloginfo('url') . '/create-resource/?resourceid=' . $resource->resourceid . '&copy=1"><span class="fa fa-copy"></span> <span>'.__('Duplicate','curriki').'</span></a>';
    $library .= curriki_sharethis($resource->resourceid, ($resource->title?$resource->title:'Go to Resource'));
    $library .= '</div>';
    $library .= '</div>';
  }
  $user_library .= $library;

  $user_library .= '</div>';



  $user_library .= library_pagination(get_bloginfo('url') . '/members/'.$user->user_nicename.'/resources/?library_sorting=' . (isset($_GET['library_sorting']) ? $_GET['library_sorting']: ""), $_GET['page_no'], ceil($total_resources / 10));
  /*
  $user_library .= '<div class="actions-row grid_12 clearfix">';
  $user_library .= '<div class="grid_8 alpha"><button class="small-button green-button"><span class="fa fa-search"></span> New Collection</button><button class="small-button green-button" onclick="window.location=\'' . get_bloginfo('url') . '/create-resource\';"><span class="fa fa-plus-circle"></span> Upload Resource</button><button class="small-button green-button" onclick="window.location=\'' . get_bloginfo('url') . '/organize-collections\'"><span class="fa fa-list"></span> Organize Collections</button></div>';
  $user_library .= '<div class="search-dropdown grid_4 omega">' . curriki_library_sorting('user', 'bottom', $_GET['library_sorting'], $userid) . '</div>';
  $user_library .= '</div>';
  */
  
  echo $user_library;

  echo '</div></div>';

  
        ?>
</div>

<?php do_action( 'bp_after_member_' . bp_current_action() . '_content' ); ?>
</div><!-- #item-body -->
                                        

				</div>
			</div>
		</div>
	</div>

<style type="text/css">
    #bpsubnav
    {
        display: none !important;
    }
    .pagination
    {
        display: flex !important;
    }
    
    .actions-row {
        padding: 0px !important;
    }
    .search-dropdown
    {
        float: left !important;
        text-align: left !important;
    }
    .container_12 .grid_12 {
        width: auto !important;
    }
    .library-asset .library-title
    {                
        width: calc(100% - 470px) !important;
    }
    .user-library-content .container_12 .grid_12 {
        width: 100% !important;
    }
</style>
<script>
    function approveResource(resourceid){
        jQuery.ajax({
            url: ajaxurl, // or example_ajax_obj.ajaxurl if using on frontend
            method: "POST", 
            dataType: "json",
            data: {
                'resourceids':[resourceid],
                action:'resource_bulkaction',
                bulkaction:'Approve'
            } ,
            success:function(data) {
                console.log(data);
                alert(data.msg);
                window.location.reload();
                
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });  
        return false;
    }
    function rejectResource(resourceid){
        jQuery.ajax({
            url: ajaxurl, // or example_ajax_obj.ajaxurl if using on frontend
            method: "POST", 
            dataType: "json",
            data: {
                'resourceids':[resourceid],
                action:'resource_bulkaction',
                bulkaction:'Reject'
            } ,
            success:function(data) {
                console.log(data);
                alert(data.msg);
                window.location.reload();
                
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });  
        return false;
    }
</script>

<?php

add_action('genesis_after', 'curriki_library_scripts');
add_action('genesis_after', 'curriki_addthis_scripts');
gconnect_get_footer();

?>