<?php

/**
 * Topics Loop
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<?php do_action( 'bbp_template_before_topics_loop' ); ?>

<script type="text/javascript">
    jQuery(document).ready(function(){
        
        jQuery("ul.bbp-topics li.bbp-body ul.sticky").hide();
        
        var stickies = jQuery("ul.bbp-topics li.bbp-body ul.sticky").get();
        
        jQuery("ul.bbp-topics li.bbp-body ul.sticky").remove();
        
        jQuery(stickies).each(function (i,obj){
            jQuery("ul.bbp-topics li.bbp-body").prepend(obj);
        });
        jQuery("ul.bbp-topics li.bbp-body ul.sticky").show();
        
        
    });
</script>
<ul id="bbp-forum-<?php bbp_forum_id(); ?>" class="bbp-topics">

	<li class="bbp-header">

		<ul class="forum-titles">
			<li class="bbp-topic-title"><?php _e( 'Topic', 'bbpress' ); ?></li>
			<li class="bbp-topic-voice-count"><?php _e( 'Voices', 'bbpress' ); ?></li>
			<li class="bbp-topic-reply-count"><?php bbp_show_lead_topic() ? _e( 'Replies', 'bbpress' ) : _e( 'Posts', 'bbpress' ); ?></li>
			<li class="bbp-topic-freshness"><?php _e( 'Freshness', 'bbpress' ); ?></li>
		</ul>

	</li>

	<li class="bbp-body">      
            
            <?php
                /*$have_posts = bbpress()->topic_query->request;
                echo "<pre>";
                echo $have_posts;
                echo "</pre>";*/
            ?>
		<?php while ( bbp_topics($default_args) ) : bbp_the_topic(); ?>

			<?php bbp_get_template_part( 'loop', 'single-topic' ); ?>

		<?php endwhile; ?>
	</li>

	<li class="bbp-footer">

		<div class="tr">
			<p>
				<span class="td colspan<?php echo ( bbp_is_user_home() && ( bbp_is_favorites() || bbp_is_subscriptions() ) ) ? '5' : '4'; ?>">&nbsp;</span>
			</p>
		</div><!-- .tr -->

	</li>

</ul><!-- #bbp-forum-<?php bbp_forum_id(); ?> -->

<?php do_action( 'bbp_template_after_topics_loop' ); ?>
