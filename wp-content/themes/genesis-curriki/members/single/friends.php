<div class="item-list-tabs no-ajax" id="bpsubnav" role="navigation">
	<ul>
		<?php if ( bp_is_my_profile() ) { bp_get_options_nav(); } ?>

		<li id="members-order-select" class="last filter">
			<?php _e( 'Order By:', 'buddypress' ) ?>
			<select id="members-all">
				<option value="active"><?php _e( 'Last Active', 'buddypress' ); ?></option>
				<option value="newest"><?php _e( 'Newest Registered', 'buddypress' ); ?></option>
				<option value="alphabetical"><?php _e( 'Alphabetical', 'buddypress' ); ?></option>

				<?php do_action( 'bp_member_blog_order_options' ); ?>
			</select>
		</li>
	</ul>
	<div class="clear"></div>
</div>
<?php

if ( bp_is_current_action( 'requests' ) ) :
	gconnect_locate_template( array( 'members/single/friends/requests.php' ), true );
else :
	do_action( 'bp_before_member_friends_content' );
?>
	<div class="members friends">
		<?php gconnect_locate_template( array( 'members/members-loop.php' ), true ); ?>
	</div><!-- .members.friends -->
<?php 
	do_action( 'bp_after_member_friends_content' );
endif;
