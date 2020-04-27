<?php do_action( 'bp_before_activity_entry' ); ?>

<?php

global $bp;
$avatar_defaults = array(
			'alt'     => 'member-name',
			'class'   => 'border-grey circle',
			'height'  => 83,
			'width'	  => 83
		);
//echo count($bp->unfiltered_uri) . "  ****";
if( is_array($bp->unfiltered_uri) && count($bp->unfiltered_uri) === 4 && in_array("members", $bp->unfiltered_uri) && in_array("activity", $bp->unfiltered_uri) )
{
    ?>
    <style type="text/css">
        div.activity
        {
            margin: 0 auto !important;
            min-height: 345px !important;
            padding-top: 35px !important;
            width: 60%;
        }
    </style>
<?php
}
?>

<li class="group-activity-card page-activity-card <?php bp_activity_css_class(); ?>" id="activity-<?php bp_activity_id(); ?>">
    <div class="act_wrapper">
	<div class="group-activity-member page-activity-member">
                <?php 
//                    $actvity_user = $wpdb->get_row("SELECT * FROM users WHERE userid=".bp_get_activity_user_id() , OBJECT);                    //prepared statement added
                    $actvity_user = $wpdb->get_row( $wpdb->prepare( 
                            "
                                    SELECT * FROM users 
                                    WHERE userid = %d
                            ", 
                            bp_get_activity_user_id()
                    ), OBJECT );
                    
                    $file_img = "";
                    if(is_object($actvity_user) && strlen( $actvity_user->uniqueavatarfile ) == 0)
                    {
                        
                        $profile = get_user_meta($actvity_user->userid,"profile",true);
                        $profile = isset($profile) ? json_decode($profile) : null;
                        $gender_img = isset($profile) ? "-".$profile->gender : "";
                        $file_img = get_stylesheet_directory_uri()."/images/user-icon-sample{$gender_img}.png";
                    }elseif(is_object($actvity_user))
{                        $file_img = "https://" . DBI_AWS_S3_CDN_URL . "//avatars/".$actvity_user->uniqueavatarfile;
                    }elseif(!is_object($actvity_user))
{                        $file_img = get_stylesheet_directory_uri()."/images/user-icon-sample.png";
                    }
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function(){
                          jQuery("img.activity-img").error(function(){
                                jQuery(this).attr("src","<?php echo get_stylesheet_directory_uri(); ?>/images/user-icon-sample.png");
                          });
                    });
                </script>
		<a href="<?php bp_activity_user_link(); ?>">                    
                    <?php //bp_activity_avatar( $avatar_defaults ); ?>                
                    <img src="<?php echo $file_img; ?>" class="activity-img border-grey circle user-<?php echo is_object($actvity_user) ? $actvity_user->userid : ""; ?>-avatar avatar-<?php echo is_object($actvity_user) ? $actvity_user->uniqueavatarfile : ""; ?> photo" width="83" height="83" alt="member-name" />
                </a>
	</div>
	<div class="group-activity page-activity">
		<div class="group-activity-header page-activity-header">
			<div class="group-activity-info page-activity-info">
				<?php bp_activity_action( array ('no_timestamp' => true ) ); ?>
			</div>
			<div class="group-activity-time page-activity-time"><?php echo date('M d, Y H:m:s', strtotime(bp_get_activity_feed_item_date())); ?> <!--August 14, 2014  5:15 PM EST--> </div>
		</div> 
            
                <div class="act_body activity-inner"> 
                    <?php bp_activity_content_body(); ?>                    
                </div>
            
		<?php /*
		
			<div class="group-activity-body-content page-activity-body-content">
				<a href="#" class="resource-name">This Resource Name</a>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less...<div class="rate-align"><a href="#">Rate Resource</a><a href="#">Align to Standards</a>
			</div>
		
		*/ ?>
	</div>
	<?php if ( is_user_logged_in() ) : ?>

		<div class="activity-meta">

			<?php if ( bp_activity_can_comment() ) : ?>
				<a href="<?php bp_get_activity_comment_link(); ?>" class="button acomment-reply bp-primary-action" id="acomment-comment-<?php bp_activity_id(); ?>"><?php printf( __( 'Comment <span>%s</span>', 'buddypress' ), bp_activity_get_comment_count() ); ?></a>
			<?php endif; ?>

			<?php if ( bp_activity_can_favorite() ) : ?>
				<?php if ( !bp_get_activity_is_favorite() ) : ?>
					<a href="<?php bp_activity_favorite_link(); ?>" class="button fav bp-secondary-action" title="<?php esc_attr_e( 'Mark as Favorite', 'buddypress' ); ?>"><?php _e( 'Favorite', 'buddypress' ); ?></a>
				<?php else : ?>
					<a href="<?php bp_activity_unfavorite_link(); ?>" class="button unfav bp-secondary-action" title="<?php esc_attr_e( 'Remove Favorite', 'buddypress' ); ?>"><?php _e( 'Remove Favorite', 'buddypress' ); ?></a>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ( bp_activity_user_can_delete() ) bp_activity_delete_link(); ?>

			<?php do_action( 'bp_activity_entry_meta' ); ?>

		</div>

	<?php endif; ?>
    </div>
</li>

<?php /* 

<li class="<?php bp_activity_css_class(); ?>" id="activity-<?php bp_activity_id(); ?>">
	<div class="activity-avatar">
		<a href="<?php bp_activity_user_link(); ?>">
			<?php bp_activity_avatar(); ?>
		</a>
	</div>

	<div class="activity-content">

		<div class="activity-header">
			<?php bp_activity_action(); ?>
		</div>

		<?php if ( 'activity_comment' == bp_get_activity_type() ) : ?>

			<div class="activity-inreplyto">
				<strong><?php _e( 'In reply to: ', 'buddypress' ); ?></strong><?php bp_activity_parent_content(); ?> <a href="<?php bp_activity_thread_permalink(); ?>" class="view" title="<?php _e( 'View Thread / Permalink', 'buddypress' ); ?>"><?php _e( 'View', 'buddypress' ); ?></a>
			</div>

		<?php endif; ?>

		<?php if ( bp_activity_has_content() ) : ?>

			<div class="activity-inner">
				<?php bp_activity_content_body(); ?>
			</div>

		<?php endif; ?>

		<?php do_action( 'bp_activity_entry_content' ); ?>

		<?php if ( is_user_logged_in() ) : ?>

			<div class="activity-meta">

				<?php if ( bp_activity_can_comment() ) : ?>
					<a href="<?php bp_get_activity_comment_link(); ?>" class="button acomment-reply bp-primary-action" id="acomment-comment-<?php bp_activity_id(); ?>"><?php printf( __( 'Comment <span>%s</span>', 'buddypress' ), bp_activity_get_comment_count() ); ?></a>
				<?php endif; ?>

				<?php if ( bp_activity_can_favorite() ) : ?>
					<?php if ( !bp_get_activity_is_favorite() ) : ?>
						<a href="<?php bp_activity_favorite_link(); ?>" class="button fav bp-secondary-action" title="<?php esc_attr_e( 'Mark as Favorite', 'buddypress' ); ?>"><?php _e( 'Favorite', 'buddypress' ); ?></a>
					<?php else : ?>
						<a href="<?php bp_activity_unfavorite_link(); ?>" class="button unfav bp-secondary-action" title="<?php esc_attr_e( 'Remove Favorite', 'buddypress' ); ?>"><?php _e( 'Remove Favorite', 'buddypress' ); ?></a>
					<?php endif; ?>
				<?php endif; ?>

				<?php if ( bp_activity_user_can_delete() ) bp_activity_delete_link(); ?>

				<?php do_action( 'bp_activity_entry_meta' ); ?>

			</div>

		<?php endif; ?>

	</div>

	<?php do_action( 'bp_before_activity_entry_comments' ); ?>

	<?php if ( ( is_user_logged_in() && bp_activity_can_comment() ) || bp_activity_get_comment_count() ) : ?>

		<div class="activity-comments">
			
			<?php bp_activity_comments(); ?>

			<?php if ( is_user_logged_in() ) : ?>

				<form action="<?php bp_activity_comment_form_action(); ?>" method="post" id="ac-form-<?php bp_activity_id(); ?>" class="ac-form"<?php bp_activity_comment_form_nojs_display(); ?>>
					<div class="ac-reply-avatar"><?php bp_loggedin_user_avatar( 'width=' . BP_AVATAR_THUMB_WIDTH . '&height=' . BP_AVATAR_THUMB_HEIGHT ); ?></div>
					<div class="ac-reply-content">
						<div class="ac-textarea">
							<textarea id="ac-input-<?php bp_activity_id(); ?>" class="ac-input" name="ac_input_<?php bp_activity_id(); ?>"></textarea>
						</div>
						<input type="submit" name="ac_form_submit" value="<?php _e( 'Post', 'buddypress' ); ?>" /> &nbsp; <?php _e( 'or press esc to cancel.', 'buddypress' ); ?>
						<input type="hidden" name="comment_form_id" value="<?php bp_activity_id(); ?>" />
					</div>

					<?php do_action( 'bp_activity_entry_comments' ); wp_nonce_field( 'new_activity_comment', '_wpnonce_new_activity_comment' ); ?>

				</form>

			<?php endif; ?>

		</div>

	<?php endif; ?>

	<?php do_action( 'bp_after_activity_entry_comments' ); ?>
	<div class="clear"></div>
</li> */ ?>

<?php
do_action( 'bp_after_activity_entry' );
