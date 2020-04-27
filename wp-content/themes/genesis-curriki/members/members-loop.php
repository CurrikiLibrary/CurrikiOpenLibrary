<?php do_action( 'bp_before_members_loop' ); ?>

<?php if ( bp_has_members( bp_ajax_querystring( 'members' ) ) ) : ?>
	<div id="pag-top" class="pagination">
		<div class="pag-count" id="member-dir-count-top">
			<?php bp_members_pagination_count(); ?>
		</div>
		<div class="pagination-links" id="member-dir-pag-top">
			<?php bp_members_pagination_links(); ?>
		</div>
	</div>

	<?php do_action( 'bp_before_directory_members_list' ); ?>

	<ul id="members-list" class="item-list" role="main">

	<?php while ( bp_members() ) : bp_the_member(); ?>

		<?php

		$resources_for_user = cur_get_resource_total_from_member ( bp_get_member_user_id() );		
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
                                                $profile = get_user_meta($actvity_user->userid,"profile",true);    
                                                $profile = isset($profile) ? json_decode($profile) : null; 
                                                $gender_img = isset($profile) ? "-".$profile->gender : "";
                                                $file_img = get_stylesheet_directory_uri()."/images/user-icon-sample{$gender_img}.png";
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
                                    
                                        <!--<a href="<?php bp_member_permalink(); ?>"><?php bp_member_avatar( $args ); ?></a>-->
                                    
                                    
					<span class="member-name name"><?php bp_member_name(); ?></span>										
				</div>
				<div class="card-stats">
					<span class="stat"><span class="fa fa-users"></span><?php echo $groups_total; ?></span>
					<span class="stat"><span class="fa fa-user"></span><?php echo $friend_total; ?></span>
					<span class="stat"><span class="fa fa-comments"></span><?php echo $topic_count; ?></span>
					<span class="stat"><span class="fa fa-book"></span><?php echo $resources_for_user; ?></span>
				</div>
				<div class="card-button action">
					<?php do_action( 'bp_directory_members_actions' ); ?>
				</div>
			</div>
		</li>

		<?php /* <li>
			<div class="item-avatar">
				<a href="<?php bp_member_permalink(); ?>"><?php bp_member_avatar(); ?></a>
			</div>
			<div class="item">
				<div class="item-title">
					<a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a>

					<?php if ( bp_get_member_latest_update() ) : ?>

						<span class="update"> <?php bp_member_latest_update(); ?></span>

					<?php endif; ?>

				</div>

				<div class="item-meta"><span class="activity"><?php bp_member_last_active(); ?></span></div>

				<?php do_action( 'bp_directory_members_item' ); ?>
			</div>
			<div class="action">
				<?php do_action( 'bp_directory_members_actions' ); ?>
			</div>
			<div class="clear"></div>
		</li> */ ?>
	<?php endwhile; ?>

	</ul>

	<?php do_action( 'bp_after_directory_members_list' ); bp_member_hidden_fields(); ?>

	<div id="pag-bottom" class="pagination">
		<div class="pag-count" id="member-dir-count-bottom">
			<?php bp_members_pagination_count(); ?>
		</div>
		<div class="pagination-links" id="member-dir-pag-bottom">
			<?php bp_members_pagination_links(); ?>
		</div>
	</div>
<?php else: ?>
	<div id="message" class="info">
            <?php
            global $bp;            
            if($bp->current_component == 'following')
            { ?>
                    <p><?php echo __('This user is not currently following anyone','curriki'); ?></p>
            <?php }else{ ?>
		<p><?php _e( "Sorry, no members were found.", 'buddypress' ); ?></p>
            <?php } ?>
	</div>
<?php endif; ?>

<?php do_action( 'bp_after_members_loop' ); ?>
