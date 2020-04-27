<?php
if ( bp_group_has_members( 'exclude_admins_mods=0' ) ) :

	do_action( 'bp_before_group_members_content' );
?>

<!--	<div class="item-list-tabs" id="bpsubnav" role="navigation">
		<ul>

			<?php do_action( 'bp_members_directory_member_sub_types' ); ?>

			<li id="members-order-select" class="last filter">

				<label for="members-order-by"><?php _e( 'Order By:', 'buddypress' ); ?></label>
				<select id="members-order-by">
					<option value="active"><?php _e( 'Last Active', 'buddypress' ); ?></option>
					<option value="newest"><?php _e( 'Newest Registered', 'buddypress' ); ?></option>

					<?php if ( bp_is_active( 'xprofile' ) ) : ?>
						<option value="alphabetical"><?php _e( 'Alphabetical', 'buddypress' ); ?></option>
					<?php endif; ?>

					<?php do_action( 'bp_members_directory_order_options' ); ?>
				</select>
			</li>
		</ul>
		<div class="clear"></div>
	</div>-->

	<div id="pag-top" class="pagination no-ajax">
		<div class="pag-count" id="member-count-top">
			<?php bp_members_pagination_count(); ?>
		</div>
		<div class="pagination-links" id="member-pag-top">
			<?php bp_members_pagination_links(); ?>
		</div>
	</div>

	<?php do_action( 'bp_before_group_members_list' ); ?>
    <div id="members-dir-list" class="dir-list members follow <?php echo bp_current_action(); ?>">
	<ul id="member-list" class="item-list" role="main">
		<?php while ( bp_group_members() ) : bp_group_the_member(); ?>

		<?php

		$resources_for_user = cur_get_resource_total_from_member ( bp_get_member_user_id() );
		//$occupation = cur_get_user_nonwp_data ( bp_get_member_user_id(), 'occupation' );
		$occupation = null;
		//$location = cur_get_user_nonwp_data ( bp_get_member_user_id(), 'location' );
		$location = null;
		$friend_total = friends_get_total_friend_count ( bp_get_member_user_id() );
		$groups_total = groups_total_groups_for_user ( bp_get_member_user_id() );
		$topic_count = 0; // bp_forums_total_topic_count_for_user ( bp_get_member_user_id() );

		?>
		<li>
			<div class="member-card card rounded-borders-full border-grey fixed_grid_2 follow">
				<div class="card-header">
					<?php 

						$args = array(
							'type'   => 'thumb',
							'width'  => 100,
							'height' => 100,
							'class'  => 'border-grey',
							'id'     => false
						);

					?>
                                        
                                        <?php                                             
                                            $actvity_user = $wpdb->get_row("SELECT * FROM users WHERE userid=".bp_get_member_user_id() , OBJECT);
                                            $file_img = "https://" . DBI_AWS_S3_CDN_URL . "//avatars/".$actvity_user->uniqueavatarfile;
                                            if(strlen( $actvity_user->uniqueavatarfile ) == 0)
                                            {
                                                $file_img = get_stylesheet_directory_uri()."/images/user-icon-sample.png";
                                            }
                                        ?>
                                        <script type="text/javascript">
                                            jQuery(document).ready(function(){
                                                  jQuery("img.activity-img").error(function(){
                                                        jQuery(this).attr("src","<?php echo get_stylesheet_directory_uri(); ?>/images/user-icon-sample.png");
                                                  });
                                            });
                                        </script>
                                        <a href="<?php bp_member_permalink(); ?>">                    
                                            <?php //bp_activity_avatar( $avatar_defaults ); ?>                
                                            <img src="<?php echo $file_img; ?>" class="activity-img border-grey user-<?php echo $actvity_user->userid; ?>-avatar avatar-<?php echo $actvity_user->uniqueavatarfile; ?> photo" width="100" height="100" alt="" style="width: 100px !important; height: 100px !important;" />
                                        </a>
                                        
                                        <!--<a href="<?php //bp_member_permalink(); ?>"><?php //bp_member_avatar( $args ); ?></a>-->
                                        
                                        <span class="member-name name">
                                            <?php bp_member_name(); ?>                                             
                                        
                                        
                                            <?php  
                                                    //var_dump(groups_get_group_admins(bp_get_group_id()) );
                                                    if( cur_is_admin_found( groups_get_group_admins(bp_get_group_id()) , bp_get_member_user_id() )  )
                                                    {
                                            ?>
                                                        <span class="group-leader-lable">Group Leader</span>
                                              <?php } ?> 
                                        </span>
                                        
					<?php if ( $occupation ) { ?>
					<span class="occupation"><?php echo $occupation; ?></span>
					<?php } ?>
					<?php if ( $location ) { ?>
					<span class="location"><?php echo $location; ?></span>
					<?php } ?>
				</div>
				<div class="card-stats">
					<span class="stat"><span class="fa fa-users"></span><?php echo $groups_total; ?></span>
					<span class="stat"><span class="fa fa-user"></span><?php echo $friend_total; ?></span>
					<span class="stat"><span class="fa fa-comments"></span><?php echo $topic_count; ?></span>
					<span class="stat"><span class="fa fa-book"></span><?php echo $resources_for_user; ?></span>
				</div>
				<button class="card-button followcard">                                    
                                    <?php do_action( 'bp_directory_members_actions' ); ?>
                                </button>
			</div>
		</li>

			<?php /* <li>
				<a href="<?php bp_group_member_domain(); ?>">
					<?php bp_group_member_avatar_thumb(); ?>
				</a>

				<h5><?php bp_group_member_link(); ?></h5>
				<span class="activity"><?php bp_group_member_joined_since(); ?></span>

				<?php do_action( 'bp_group_members_list_item' ); ?>

				<?php if ( bp_is_active( 'friends' ) ) : ?>

					<div class="action">

						<?php bp_add_friend_button( bp_get_group_member_id(), bp_get_group_member_is_friend() ); ?>

						<?php do_action( 'bp_group_members_list_item_action' ); ?>

					</div>

				<?php endif; ?>
			</li> */ ?>

		<?php endwhile; ?>
	</ul>
    </div>
    <style type="text/css">
        .card-button {            
            height: 50px !important;
        }
    </style>
    <script type="text/javascript">
        jQuery(document).ready(function(){
            
            
            
            jQuery(".member-card .followcard").click(function(){
                var a_link = jQuery(this).find("a").get();                
                if( a_link.length > 0 )
                {           
                    bp_follow_button_action( jQuery(a_link[0]) );
                }
            });
           
        });
        
        
        function bp_follow_button_action( scope, context ) {
		var link   = scope;
                
		var uid    = link.attr('id');
		var nonce  = link.attr('href');
		var action = '';
 
		uid    = uid.split('-');
		action = uid[0];
		uid    = uid[1];

		nonce = nonce.split('?_wpnonce=');
		nonce = nonce[1].split('&');
		nonce = nonce[0];

		jq.post( ajaxurl, {
			action: 'bp_' + action,
			'uid': uid,
			'_wpnonce': nonce
		},
		function(response) {
			jq( link.parent()).fadeOut(200, function() {
				// toggle classes
				if ( action == 'unfollow' ) {
					link.parent().removeClass( 'following' ).addClass( 'not-following' );
				} else {
					link.parent().removeClass( 'not-following' ).addClass( 'following' );
				}

				// add ajax response
				link.parent().html( response );

				// increase / decrease counts
				var count_wrapper = false;
				if ( context == 'profile' ) {
					count_wrapper = jq("#user-members-followers span");

				} else if ( context == 'member-loop' ) {
					// a user is on their own profile
					if ( ! jq.trim( profileHeader.text() ) ) {
						count_wrapper = jq("#user-members-following span");

					// this means we're on the member directory
					} else {
						count_wrapper = jq("#members-following span");
					}
				}

				if ( count_wrapper.length ) {
					if ( action == 'unfollow' ) {
						count_wrapper.text( ( count_wrapper.text() >> 0 ) - 1 );
					} else if ( action == 'follow' ) {
						count_wrapper.text( ( count_wrapper.text() >> 0 ) + 1 );
					}
				}

				jq(this).fadeIn(200);
			});
		});
	}
        
    </script>

	<?php do_action( 'bp_after_group_members_list' ); ?>

	<div id="pag-bottom" class="pagination">
		<div class="pag-count" id="member-count-bottom">
			<?php bp_members_pagination_count(); ?>
		</div>
		<div class="pagination-links" id="member-pag-bottom">
			<?php bp_members_pagination_links(); ?>
		</div>
	</div>
	<?php do_action( 'bp_after_group_members_content' ); ?>

<?php else: ?>
	<div id="message" class="info">
		<p><?php _e( 'This group has no members.', 'buddypress' ); ?></p>
	</div>
<?php
endif;
