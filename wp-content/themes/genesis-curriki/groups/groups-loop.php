<?php
do_action( 'bp_before_groups_loop' );

$bp_has_groups = bp_has_groups( bp_ajax_querystring( 'groups' ));

global $bp;
global $group_loop_source,$group_user;
curr_set_global_vars();

if( $group_loop_source == "groups" )
{
    $q_options = "";
    $object_req = $_POST['object'];
    $filter_type = $_POST['filter'];
    $page = $_REQUEST['page'];
    if( isset($object_req) && isset($filter_type) )
    {
       $q_options.= "&type=$filter_type";
    }

    if(isset($page))
    {
       $q_options.="&page=$page";
    }
    
    
    if( get_current_user_id() > 0 && $_POST["search_terms"] == 'false')
    {        
        $q_options.="&user_id=".get_current_user_id();     
    }

    if(strlen($q_options) == 0)
    {
       $bp_has_groups = bp_has_groups( bp_ajax_querystring( 'groups' ));
    }  else {
        $bp_has_groups = bp_has_groups( bp_ajax_querystring( 'groups' ) . $q_options );
    }
}elseif( $group_loop_source == "members" && isset($group_user))
{
    $q_options = "";
    $object_req = $_POST['object'];
    $filter_type = $_POST['filter'];
    $page = $_REQUEST['page'];
    if( isset($object_req) && isset($filter_type) )
    {
       $q_options.= "&type=$filter_type";
    }

    if(isset($page))
    {
       $q_options.="&page=$page";
    }

    if( get_current_user_id() > 0)
    {
        $q_options.="&user_id=".$group_user->ID;
    }

    if(strlen($q_options) == 0)
    {
       $bp_has_groups = bp_has_groups( bp_ajax_querystring( 'groups' ));
    }  else {
        $bp_has_groups = bp_has_groups( bp_ajax_querystring( 'groups' ) . $q_options );
    }
}

if ( $bp_has_groups ) :
//if ( bp_has_groups( bp_ajax_querystring( 'members/jpinto/groups' ) ) ) :
?>

	<div id="pag-top" class="pagination">
		<div class="pag-count" id="group-dir-count-top">
			<?php bp_groups_pagination_count(); ?>
		</div>
		<div class="pagination-links" id="group-dir-pag-top">
			<?php bp_groups_pagination_links(); ?>
		</div>
	</div>

	<?php do_action( 'bp_before_directory_groups_list' ); ?>

<!--	<ul id="groups-list" class="item-list" role="main">-->

        <div class="groups clearfix">

	<?php while ( bp_groups() ) : bp_the_group(); ?>

	<?php
                $group = groups_get_group( array( 'group_id' => bp_get_group_id() ) );
                
		$group_members = bp_get_group_total_members();
		$resources_for_group = cur_get_resource_total_from_group ( bp_get_group_id() );
		//$forum_id = groups_get_groupmeta( bp_get_group_id(), 'forum_id', true );
		//$topic_count       = bbp_get_forum_topic_count( $forum_id, false, true );
		//$total_topic_count = bbp_get_forum_topic_count( $forum_id, true,  true );
                
                $forum_ids = groups_get_groupmeta( bp_get_group_id(), 'forum_id', true );
                if( !(is_array($forum_ids) && count( $forum_ids ) > 0) )
                {
                    $forum_ids = array();
                }
                //$forum_count = count($forum_ids);
                $forum_id = count($forum_ids) > 0 ? $forum_ids[0] : 0;
                $forum_count = $wpdb->get_var("SELECT count(ID) FROM {$wpdb->prefix}posts where post_type = 'topic' AND post_status = 'publish' AND post_parent = $forum_id");

	?>
            
		<div class="group-card card rounded-borders-full border-grey fixed_grid_3 join-group">
			<div class="card-header">
				<div><a href="<?php bp_group_permalink(); ?>"><?php bp_group_avatar( 'type=thumb&width=100&height=100' ); ?></a></div>
				<span class="group-name name">                                     
                                    <a href="<?php bp_group_permalink(); ?>"><?php bp_group_name(); ?></a>
                                </span>
                                <br />
			</div>
			<div class="card-stats">
				<span class="stat"><span class="fa fa-users"></span><?php echo $group_members; ?></span>
                                <?php
                                    $forum_ids = groups_get_groupmeta( bp_get_group_id(), 'forum_id', true );
                                    if( !(is_array($forum_ids) && count( $forum_ids ) > 0) )
                                    {
                                        $forum_ids = array();
                                    }                                        
                                    $forum_id = count($forum_ids) > 0 ? $forum_ids[0] : 0;                                        
                                    if($forum_id > 0)
                                    {
                                ?>
                                        <span class="stat"><span class="fa fa-comments"></span><?php echo $forum_count; ?></span>
                                <?php 
                                    }
                                ?>                                
				<span class="stat"><span class="fa fa-book"></span><?php echo $resources_for_group; ?></span>
			</div>
			<div class="card-description">
                            <?php                                 
                                //bp_group_description_excerpt(); 
                            if( strlen($group->description) > 45 )
                            {
                                echo strip_tags(substr($group->description ,0,45))."....";
                            }else{
                                echo strip_tags($group->description);
                            }
                            ?>
                        </div>
			
			<div class="card-button action">				
				<?php do_action( 'bp_directory_groups_actions' ) ?> 		                                
                                &nbsp;
			</div>
		</div>
            

		<?php /* <li>
			<div class="item-avatar">
				<a href="<?php bp_group_permalink(); ?>"><?php bp_group_avatar( 'type=thumb&width=50&height=50' ); ?></a>
			</div>

			<div class="item">
				<div class="item-title"><a href="<?php bp_group_permalink(); ?>"><?php bp_group_name(); ?></a></div>
				<div class="item-meta"><span class="activity"><?php printf( __( 'active %s', 'buddypress' ), bp_get_group_last_active() ); ?></span></div>

				<div class="item-desc"><?php bp_group_description_excerpt(); ?></div>

				<?php do_action( 'bp_directory_groups_item' ); ?>

			</div>

			<div class="action">
				<?php do_action( 'bp_directory_groups_actions' ); ?>
				<div class="meta">
					<?php bp_group_type(); ?> / <?php bp_group_member_count(); ?>
				</div>
			</div>
			<div class="clear"></div>
		</li> */ ?>

	<?php endwhile; ?>

<!--	</ul>-->
        </div>

	<?php do_action( 'bp_after_directory_groups_list' ); ?>

	<div id="pag-bottom" class="pagination">
		<div class="pag-count" id="group-dir-count-bottom">
			<?php //bp_groups_pagination_count(); ?>
		</div>
		<div class="pagination-links" id="group-dir-pag-bottom">
			<?php bp_groups_pagination_links(); ?>
		</div>
	</div>

<?php else: ?>
	<div id="message" class="info">
		<p><?php _e( 'There were no groups found.', 'buddypress' ); ?></p>
	</div>
<?php
endif;

do_action( 'bp_after_groups_loop' );
