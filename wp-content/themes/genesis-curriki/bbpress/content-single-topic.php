<?php

/**
 * Single Topic Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<?php

gconnect_get_header();
global $wpdb;
$group_url = bp_get_group_permalink();
$member_count = groups_get_total_member_count ( bp_get_group_id() );
$library_count =  cur_get_resource_total_from_group( bp_get_group_id() );
$forum_ids = groups_get_groupmeta( bp_get_group_id(), 'forum_id', true );
if( !(is_array($forum_ids) && count( $forum_ids ) > 0) )
{
    $forum_ids = array();
}
//$forum_count = count($forum_ids);
$forum_id = count($forum_ids) > 0 ? $forum_ids[0] : 0;
//$forum_count = $wpdb->get_var("SELECT count(ID) FROM {$wpdb->prefix}posts where post_type = 'topic' AND post_status = 'publish' AND post_parent = $forum_id");    //prepared statement added
$forum_count = $wpdb->get_var( $wpdb->prepare(      
        "
                SELECT count(ID)
                FROM {$wpdb->prefix}posts
                where post_type = 'topic'
                AND post_status = 'publish'
                AND post_parent = %d
        ", 
        $forum_id
) );

?>


<div class="member-tabs page-tabs"><div class="wrap container_12">                
<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist">
<li class="ui-state-default ui-corner-top <?php if ( bp_current_action() == "home" ) { ?>ui-tabs-active ui-state-active<?php } ?> " role="tab" tabindex="0" aria-controls="activity" aria-labelledby="ui-id-1" aria-selected="true" aria-expanded="true"><a href="<?php echo $group_url; ?>" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-1"><span class="tab-icon fa fa-home"></span> <span class="tab-text"><?php echo __('Activity','curriki'); ?></span></a></li><li class="ui-state-default ui-corner-top <?php if ( bp_current_action() == "members" ) { ?>ui-tabs-active ui-state-active<?php } ?>" role="tab" tabindex="-1" aria-controls="members" aria-labelledby="ui-id-2" aria-selected="false" aria-expanded="false"><a href="<?php echo $group_url; ?>members" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-2"><span class="tab-icon fa fa-user"></span> <span class="tab-text"><?php echo __('Members','curriki'); ?> <span class="group-number">(<?php echo $member_count; ?>)</span></span></a></li><li class="ui-state-default ui-corner-top <?php if ( bp_current_action() == "library" ) { ?>ui-tabs-active ui-state-active<?php } ?>" role="tab" tabindex="-1" aria-controls="resources" aria-labelledby="ui-id-3" aria-selected="false" aria-expanded="false"><a href="<?php echo $group_url; ?>library" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-3"><span class="tab-icon fa fa-book"></span> <span class="tab-text"><?php echo __('Resources','curriki'); ?> <span class="group-number">(<?php echo $library_count; ?>)</span></span></a></li><?php if( count( $forum_ids ) > 0 ) { ?><li class="ui-state-default ui-corner-top <?php if ( bp_current_action() == "forum" ) { ?>ui-tabs-active ui-state-active<?php } ?>" role="tab" tabindex="-1" aria-controls="forums" aria-labelledby="ui-id-4" aria-selected="false" aria-expanded="false"><a href="<?php echo $group_url; ?>forum" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-4"><span class="tab-icon fa fa-comments"></span> <span class="tab-text"><?php echo __('Forum','curriki'); ?> <span class="group-number">(<?php echo $forum_count; ?>)</span></span></a></li><?php } ?>
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
                                            $members = $members["members"];
                                            //print_r ($members);

                                        ?>
                                        <h4 class="sidebar-title"><?php echo __('Recently Active','curriki'); ?></h4>
                                        <div class="recently-active member-card card rounded-borders-full border-grey">
                                            <ul>
                                            <?php

                                                  foreach ( $members as $member ) {

                                                    // print_r ($member);

                                                    $the_member_id = $member->ID;

                                                    $location_arr = array();
                                                    
                                                    $city = cur_get_user_nonwp_data ( $the_member_id, 'city' );                                                                                                        
                                                    if(strlen($city) > 0)
                                                        $location_arr[] = $city;
                                                    
                                                    
                                                    $state = cur_get_user_nonwp_data ( $the_member_id, 'state' );                                                    
                                                    if(strlen($state) > 0)
                                                        $location_arr[] = $state;
                                                    
                                                    $country = cur_get_user_nonwp_data ( $the_member_id, 'country' );                                                    
                                                    if(strlen($country) > 0)
                                                        $location_arr[] = $country;
                                                    
                                                    /*
                                                    if ( $city || $state || $country ) {
                                                        $location = $city . ', ' . $state . ' ' . $country;
                                                    }
                                                    */
                                                    $location = implode(',', $location_arr);
                                                    $bio = cur_get_user_nonwp_data ( $the_member_id, 'bio' );
                                                    $profession = false;


                                            ?>
                                            <li class="member">
                                                <!-- <img class="border-grey" src="placehold.it/100x100" alt="member-name" /> -->
                                                <?php //echo bp_core_fetch_avatar( 'item_id='.$the_member_id ); ?>
                                                <?php                                                        
                                                        $userid = $the_member_id;
                                                        
//                                                        $q_userinfo = "select * from users where userid = '".$userid."'";        //prepared statement added
//                                                        $userinfo = $wpdb->get_row($q_userinfo); 
                                                        
                                                        $userinfo = $wpdb->get_row( $wpdb->prepare(      
                                                                "
                                                                    select * from users where userid = %d
                                                                ", 
                                                                $userid
                                                        ) );
                                                        
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
                                        <!--  <h4 class="sidebar-title">Recent Discussions</h4>
                                        <div class="recent-discussion card rounded-borders-full border-grey">
                                            <ul class="discussion">
                                                <li><a href="#">Discussion Topic Can be Very Long at Times, so We Need Space</a></li>
                                                <li><a href="#">Discussion Topic Goes Here</a></li>
                                                <li><a href="#">Discussion Topic Can be Very Long at Times, so We Need Space</a></li>
                                            </ul>
                                            <a href="<?php //echo $group_url; ?>forums">
                                                <div class="card-button">Browse All Conversations</div>
                                            </a>
                                        </div>-->
                                    </div>
                                    <div class="activity-content grid_10">
<!--                                        
                                        <div class="group-search page-search">
                                            <div class="search-input grid_6 alpha">
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
                                            </div>
                                        </div>
                                        -->
                                        <div class="group-activity-container page-container rounded-borders-full border-grey">

                                            
<div id="bbpress-forums">

    
	<?php bbp_breadcrumb(); ?>

	<?php do_action( 'bbp_template_before_single_topic' ); ?>

	<?php if ( post_password_required() ) : ?>

		<?php bbp_get_template_part( 'form', 'protected' ); ?>

	<?php else : ?>                
		<?php bbp_topic_tag_list(); ?>

		<?php bbp_single_topic_description(); ?>

		<?php if ( bbp_show_lead_topic(true) ) : ?>

                        <a class="generic-green-btn back-to-topic" href="<?php echo $group_url; ?>forum">Back to topics</a>
                        <?php
                            if( is_user_logged_in() )
                            {
                        ?>
                                <a class="generic-green-btn reply-to-topic" href="#">Reply</a>
                        <?php
                            }
                        ?>
                        
                        <?php
                            echo "<p>";
                            echo "<strong>Topic: ". bbp_get_topic_title()."</strong>";
                            echo "</p>";                            
                        ?>
                        
			<?php bbp_get_template_part( 'content', 'single-topic-lead' ); ?>

		<?php endif; ?>

		<?php if ( bbp_has_replies() ) : ?>

                        <strong>Replies:</strong>
                     
			<?php bbp_get_template_part( 'pagination', 'replies' ); ?>
                        
			<?php bbp_get_template_part( 'loop',       'replies' ); ?>

			<?php bbp_get_template_part( 'pagination', 'replies' ); ?>

		<?php endif; ?>

		<?php bbp_get_template_part( 'form', 'reply' ); ?>

	<?php endif; ?>

	<?php do_action( 'bbp_template_after_single_topic' ); ?>

</div>
                                        </div>
                                    </div>
                                </div>
</div>

<script type="text/javascript">
    jq(document).ready(function(){
      
      jq(document).on("click","a.reply-to-topic",function(e){                    
           jq('html, body').animate({
                scrollTop: jq("#new-post").offset().top - 100
            }, 1000);    
          e.preventDefault();
      });
      
        //bbp-topic-reply-link
        
      jq(document).on("click",".bbp-admin-links a",function(e){
          var go_return = true;
          
          if( jq(this).hasClass("bbp-reply-to-link") || jq(this).hasClass("bbp-topic-reply-link"))
          {                
                var reply_tags = jq('.bbp-admin-links').get();      
                var last_reply_tag = reply_tags[reply_tags.length-1];      
                var catTopPosition = jq(last_reply_tag).offset().top + 240;  
                jq('html, body').animate({scrollTop:catTopPosition}, 'slow');
                // Stop the link from acting like a normal anchor link
                go_return = false;
          }
          
          return go_return;
      });
      
    });
</script>