<form action="<?php bp_activity_post_form_action(); ?>" method="post" id="whats-new-form" name="whats-new-form" role="complementary">

	<?php do_action( 'bp_before_activity_post_form' ); ?>

	<?php
	$avatar_defaults = array(
                                    'alt'     => 'member-name',
                                    'height'  => bp_core_avatar_thumb_width(),
                                    'width'	  => bp_core_avatar_thumb_height()
                            );
        
        $userid = bp_displayed_user_id();        
        if(bp_current_component() == "groups")
        {            
            $userid = get_current_user_id();
        }
//        $actvity_user = $wpdb->get_row("SELECT * FROM users WHERE userid=". $userid , OBJECT);  //prepared statement added
        
        $actvity_user = $wpdb->get_row( $wpdb->prepare( 
                            "
                                    SELECT * FROM users 
                                    WHERE userid = %d
                            ", 
                            $userid
                    ), OBJECT );
        
        $file_img = "https://" . DBI_AWS_S3_CDN_URL . "//avatars/".$actvity_user->uniqueavatarfile;
        if(strlen( $actvity_user->uniqueavatarfile ) == 0)
        {
            $profile = get_user_meta($actvity_user->userid,"profile",true);    
            $profile = isset($profile) ? json_decode($profile) : null; 
            $gender_img = isset($profile) ? "-".$profile->gender : "";
            $file_img = get_stylesheet_directory_uri()."/images/user-icon-sample{$gender_img}.png";
        }
        
	?>
        
	<div id="whats-new-avatar">
            <img src="<?php echo $file_img; ?>" class="activity-img border-grey user-<?php echo $actvity_user->userid; ?>-avatar avatar-<?php echo $actvity_user->uniqueavatarfile; ?> photo circle" width="100" height="100" alt="" style="width: 50px !important; height: 50px !important;" />
            <!--
		<a href="<?php //echo bp_loggedin_user_domain(); ?>">
			<?php //bp_loggedin_user_avatar( $avatar_defaults ); ?>
		</a>
            -->
	</div>

	<h5><?php if ( bp_is_group() )
			printf( __( "What's new in %s, %s?", 'buddypress' ), bp_get_group_name(), bp_get_user_firstname() );
		else
			printf( __( "What's new, %s?", 'buddypress' ), bp_get_user_firstname() );
	?></h5>

	<div id="whats-new-content">
		<div id="whats-new-textarea">
			<textarea name="whats-new" id="whats-new" cols="50" rows="10"><?php if ( isset( $_GET['r'] ) ) : ?>@<?php echo esc_attr( $_GET['r'] ); ?> <?php endif; ?></textarea>
		</div>

		<div id="whats-new-options">
			<div id="whats-new-submit">
				<input type="submit" name="aw-whats-new-submit" id="aw-whats-new-submit" value="<?php _e( 'Post Update', 'buddypress' ); ?>" />
			</div>

			<?php if ( bp_is_active( 'groups' ) && !bp_is_my_profile() && !bp_is_group() ) : ?>

				<div id="whats-new-post-in-box">

					<?php _e( 'Post in', 'buddypress' ); ?>:

					<select id="whats-new-post-in" name="whats-new-post-in">
						<option selected="selected" value="0"><?php _e( 'My Profile', 'buddypress' ); ?></option>

						<?php if ( bp_has_groups( 'user_id=' . bp_loggedin_user_id() . '&type=alphabetical&max=100&per_page=100&populate_extras=0' ) ) :
							while ( bp_groups() ) : bp_the_group(); ?>

								<option value="<?php bp_group_id(); ?>"><?php bp_group_name(); ?></option>

							<?php endwhile;
						endif; ?>

					</select>
				</div>
				<input type="hidden" id="whats-new-post-object" name="whats-new-post-object" value="groups" />

			<?php elseif ( bp_is_group_home() ) : ?>

				<input type="hidden" id="whats-new-post-object" name="whats-new-post-object" value="groups" />
				<input type="hidden" id="whats-new-post-in" name="whats-new-post-in" value="<?php bp_group_id(); ?>" />

			<?php endif; ?>

			<?php do_action( 'bp_activity_post_form_options' ); ?>

		</div><!-- #whats-new-options -->
	</div><!-- #whats-new-content -->

	<?php wp_nonce_field( 'post_update', '_wpnonce_post_update' ); do_action( 'bp_after_activity_post_form' ); ?>

</form><!-- #whats-new-form -->
