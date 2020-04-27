<?php
gconnect_get_header();

do_action( 'bp_before_member_home_content' );

$user_id = bp_displayed_user_id();

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
        <?php //gconnect_locate_template( array( 'members/single/member-header.php' ), true ); ?>
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
    <li class="ui-state-default ui-corner-top <?php if ( bp_current_action() == "home" ) { ?>ui-tabs-active ui-state-active<?php } ?> " role="tab" tabindex="0" aria-controls="activity" aria-labelledby="ui-id-1" aria-selected="true" aria-expanded="true"><a href="<?php echo $group_url; ?>" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-1"><span class="tab-icon fa fa-home"></span> <span class="tab-text">Activity</span></a></li><li class="ui-state-default ui-corner-top <?php if ( bp_current_action() == "members" ) { ?>ui-tabs-active ui-state-active<?php } ?>" role="tab" tabindex="-1" aria-controls="members" aria-labelledby="ui-id-2" aria-selected="false" aria-expanded="false"><a href="<?php echo $group_url; ?>members" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-2"><span class="tab-icon fa fa-user"></span> <span class="tab-text">Members <span class="group-number">(<?php echo $friend_count; ?>)</span></span></a></li><li class="ui-state-default ui-corner-top <?php if ( bp_current_action() == "library" ) { ?>ui-tabs-active ui-state-active<?php } ?>" role="tab" tabindex="-1" aria-controls="resources" aria-labelledby="ui-id-3" aria-selected="false" aria-expanded="false"><a href="<?php echo $group_url; ?>library" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-3"><span class="tab-icon fa fa-book"></span> <span class="tab-text">Resources <span class="group-number">(<?php echo $library_count; ?>)</span></span></a></li><?php if( count( $forum_ids ) > 0 ) { ?><li class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="forums" aria-labelledby="ui-id-4" aria-selected="false" aria-expanded="false"><a href="<?php echo $group_url; ?>forum" class="ui-tabs-anchor <?php if ( bp_current_action() == "forum" ) { ?>ui-tabs-active ui-state-active<?php } ?>" role="presentation" tabindex="-1" id="ui-id-4"><span class="tab-icon fa fa-comments"></span> <span class="tab-text">Forums <span class="group-number">(<?php echo $forum_count; ?>)</span></span></a></li><?php } ?>
    -->    
    
    <?php
        $lang_in_slug = "";
        if( defined('ICL_LANGUAGE_CODE') )
        {
            $lang_in_slug = '/'.ICL_LANGUAGE_CODE;
        }
    ?>    
    <li class="ui-state-default ui-corner-top <?php if ( bp_current_component() != "activity" ) { ?><?php } else { ?>ui-tabs-active ui-state-active<?php } ?>" role="tab" tabindex="0" aria-controls="activity" aria-labelledby="ui-id-1" aria-selected="true" aria-expanded="true"><a href="<?php echo $member_url; ?>"><span class="tab-icon fa fa-home"></span> <span class="tab-text"><?php echo __('Activity','curriki'); ?></span></a></li><li class="ui-state-default ui-corner-top <?php if ( bp_current_component() != "following" ) { ?><?php } else { ?>ui-tabs-active ui-state-active<?php } ?>" role="tab" tabindex="0" aria-controls="activity" aria-labelledby="ui-id-1" aria-selected="true" aria-expanded="true"><a href="<?php echo $member_url; ?>following"><span class="tab-icon fa fa-user"></span> <span class="tab-text"><?php echo __('Following','curriki'); ?><span class="member-number">(<?php echo $friend_count; ?>)</span></span></a></li><li class="ui-state-default ui-corner-top <?php if ( bp_current_component() != "groups" ) { ?><?php } else { ?>ui-tabs-active ui-state-active<?php } ?>" role="tab" tabindex="0" aria-controls="activity" aria-labelledby="ui-id-1" aria-selected="true" aria-expanded="true"><a href="<?php echo $member_url; ?>groups"><span class="tab-icon fa fa-users"></span> <span class="tab-text"><?php echo __('Groups','curriki'); ?><span class="member-number">(<?php echo $group_count; ?>)</span></span></a></li><li class="ui-state-default ui-corner-top <?php if ( bp_current_component() != "library" ) { ?><?php } else { ?>ui-tabs-active ui-state-active<?php } ?>" role="tab" tabindex="0" aria-controls="activity" aria-labelledby="ui-id-1" aria-selected="true" aria-expanded="true"><a href="<?php echo site_url().$lang_in_slug ; ?>/members/<?php echo $user->user_nicename; ?>/resources"><span class="tab-icon fa fa-book"></span> <span class="tab-text"><?php echo __('Resources','curriki'); ?> <span class="member-number">(<?php echo $total_resources; ?>)</span></span></a></li>
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
					<p>This user currently has no groups.</p>

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
        <?php bp_get_template_part( 'members/members-loop' ) ?>
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
</style>