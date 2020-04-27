<?php

/**
 * No Topics Feedback Part
 *
 * @package bbPress
 * @subpackage Theme
 */
$group = groups_get_group( array('group_id'=>  bp_get_group_id() ) );
?>

<div class="bbp-template-notice">
    <?php if( !is_user_logged_in() ) { ?>
	<p><?php _e( 'Please Login and be the first to contribute to this forum!', 'bbpress' ); ?></p>    
    <?php }elseif( groups_is_user_member( get_current_user_id() , bp_get_group_id() ) ) { ?>
	<p><?php _e( 'Be the first to contribute to this forum!', 'bbpress' ); ?></p>
    <?php }else{ ?>       
        <p>            
            <a id="forum-join-group-link" class="forum-join-group-link" href="<?php echo wp_nonce_url( bp_get_group_permalink( $group ) . 'join', 'groups_join_group' ); ?>"><strong>Join this group</strong></a> and be the first to contribute to this forum!
        </p>
    <?php } ?>
</div>
