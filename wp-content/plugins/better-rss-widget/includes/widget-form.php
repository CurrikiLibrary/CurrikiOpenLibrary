<?php
if ( preg_match( '#' . basename( __FILE__ ) . '#', $_SERVER['PHP_SELF'] ) ) {
	die( 'You are not allowed to call this page directly.' );
}
/**
 * widget-form.php - View for the Settings page.
 *
 * @package Better RSS Widget
 * @subpackage includes
 * @author GrandSlambert
 * @copyright 2009-2013
 * @access public
 * @since 0.1
 */
?>
<p>
     <label for="<?php print $this->get_field_id( 'title' ); ?>">
		<?php _e( 'Give the Feed a Title (optional):', 'better-rss-widget' ); ?>
     </label>
     <input id="<?php print $this->get_field_id( 'title' ); ?>" name="<?php print $this->get_field_name( 'title' ); ?>" type="text" value="<?php print $instance['title']; ?>" />
     <label>
          <input type="checkbox" id="<?php print $this->get_field_id( 'no_link_title' ); ?>" name="<?php print $this->get_field_name( 'no_link_title' ); ?>" value="1" <?php checked( $instance['no_link_title'], true ); ?> />
		<?php _e( 'Do not link', 'better-rss-widget' ); ?>
     </label>
</p>
<p>
     <label for="<?php print $this->get_field_id( 'title_url' ); ?>">
		<?php _e( 'Title URL (blank for RSS feed):', 'better-rss-widget' ); ?>
     </label>
     <input style="width:300px;" id="<?php print $this->get_field_id( 'title_url' ); ?>" name="<?php print $this->get_field_name( 'title_url' ); ?>" type="text" value="<?php print $instance['title_url']; ?>" />
</p>
<?php if ( true == $this->options->allow_intro ) : ?>
	<p>
		<label for="<?php print $this->get_field_id( 'intro_text' ); ?>">
			<?php _e( 'Text to display above the links:', 'better-rss-widget' ); ?>
		</label>
	</p>
	<p>
		<textarea style="width:485px;height: 75px;" name="<?php print $this->get_field_name( 'intro_text' ); ?>" id="<?php print $this->get_field_id( 'intro_text' ); ?>"><?php echo $instance['intro_text']; ?></textarea>
	</p>
<?php endif; ?>
<p>
     <label for="<?php print $this->get_field_id( 'items' ); ?>"><?php _e( 'Show', 'better-rss-widget' ); ?></label>
     <select name="<?php print $this->get_field_name( 'items' ); ?>" id="<?php print $this->get_field_id( 'items' ); ?>">
		<?php
		for ( $i = 1; $i <= 20; ++$i )
			print "<option value='$i' " . selected( $instance['items'], $i, true ) . ">$i</option>";
		?>
     </select>
     <label for="<?php print $this->get_field_id( 'rss_url' ); ?>">
		<?php _e( 'items from', 'better-rss-widget' ); ?>
     </label>
     <input style="width:330px;" id="<?php print $this->get_field_id( 'rss_url' ); ?>" name="<?php print $this->get_field_name( 'rss_url' ); ?>" type="text" value="<?php print $instance['rss_url']; ?>" />
</p>
<p>
     <input name="<?php print $this->get_field_name( 'show_icon' ); ?>" type="checkbox" id="<?php print $this->get_field_id( 'show_icon' ); ?>" value="1" <?php checked( $instance['show_icon'], 1 ); ?> />
     <label for="<?php print $this->get_field_id( 'show_icon' ); ?>">
		<?php _e( 'Show feed icon before title', 'better-rss-widget' ); ?>
     </label>
     <label><?php _e( 'and link icon to', 'better-rss-widget' ); ?></label>
     <label><input type="radio" name="<?php print $this->get_field_name( 'link_icon' ); ?>" value="rss_url" <?php checked( $instance['link_icon'], 'rss_url' ); ?> /> <?php _e( 'RSS Url', 'better-rss-widget' ); ?></label>
     <label><input type="radio" name="<?php print $this->get_field_name( 'link_icon' ); ?>" value="title_url" <?php checked( $instance['link_icon'], 'title_url' ); ?> /> <?php _e( 'Title Url', 'better-rss-widget' ); ?></label>
</p>
<p>
     <input name="<?php print $this->get_field_name( 'limit_title_length' ); ?>" type="checkbox" id="<?php print $this->get_field_id( 'limit_title_length' ); ?>" value="1" <?php checked( $instance['limit_title_length'], 1 ); ?> />
     <label for="<?php print $this->get_field_id( 'limit_title_length' ); ?>">
		<?php _e( 'Limit length of title to', 'better-rss-widget' ); ?>
     </label>
     <input id="<?php print $this->get_field_id( 'title_length' ); ?>" name="<?php print $this->get_field_name( 'title_length' ); ?>" type="text" value="<?php print $instance['title_length']; ?>" />

     <label for="<?php print $this->get_field_id( 'title_length' ); ?>">
		<?php _e( 'characters.', 'better-rss-widget' ); ?>
     </label>
</p>
<p>
     <input name="<?php print $this->get_field_name( 'show_summary' ); ?>" type="checkbox" id="<?php print $this->get_field_id( 'show_summary' ); ?>" value="1" <?php checked( $instance['show_summary'], 1 ); ?> />
     <label for="<?php print $this->get_field_id( 'show_summary' ); ?>">
		<?php _e( 'Display item summary limited to', 'better-rss-widget' ); ?>
     </label>
     <input id="<?php print $this->get_field_id( 'excerpt' ); ?>" name="<?php print $this->get_field_name( 'excerpt' ); ?>" type="text" value="<?php print $instance['excerpt']; ?>" />

     <label for="<?php print $this->get_field_id( 'excerpt' ); ?>">
		<?php _e( 'characters.', 'better-rss-widget' ); ?>
     </label>
</p>
<h3><?php _e( 'Additional Fields', 'better-rss-widget' ); ?></h3>
<p>
     <input type="checkbox" value="1" name="<?php print $this->get_field_name( 'show_author' ); ?>" id="<?php print $this->get_field_id( 'show_author' ); ?>" <?php checked( $instance['show_author'], 1 ); ?> />
     <label for="<?php print $this->get_field_id( 'show_author' ); ?>">
		<?php _e( 'Author', 'better-rss-widget' ); ?>
     </label>
     <input type="checkbox" value="1" name="<?php print $this->get_field_name( 'show_date' ); ?>" id="<?php print $this->get_field_id( 'show_date' ); ?>" <?php checked( $instance['show_date'], 1 ); ?> />
     <label for="<?php print $this->get_field_id( 'show_date' ); ?>">
		<?php _e( 'Date', 'better-rss-widget' ); ?>
     </label>
     <input type="checkbox" value="1" name="<?php print $this->get_field_name( 'show_time' ); ?>" id="<?php print $this->get_field_id( 'show_time' ); ?>" <?php checked( $instance['show_time'], 1 ); ?> />
     <label for="<?php print $this->get_field_id( 'show_time' ); ?>">
		<?php _e( 'Time', 'better-rss-widget' ); ?>
     </label>
     <input type="checkbox" value="1" name="<?php print $this->get_field_name( 'nofollow' ); ?>" id="<?php print $this->get_field_id( 'nofollow' ); ?>" <?php checked( $instance['nofollow'], 1 ); ?> />
     <label for="<?php print $this->get_field_id( 'nofollow' ); ?>">
		<?php _e( 'Add nofollow to links', 'better-rss-widget' ); ?>
     </label>
</p>
<p>
     <label for="<?php print $this->get_field_id( 'link_target' ); ?>">
		<?php _e( 'Link Target:', 'better-rss-widget' ); ?>
     </label>
     <select name="<?php print $this->get_field_name( 'link_target' ); ?>" id="<?php print $this->get_field_id( 'link_target' ); ?>">
          <option value="none" <?php selected( $instance['link_target'], 'none' ); ?>><?php _e( 'None', 'better-rss-widget' ); ?></option>
          <option value="_blank" <?php selected( $instance['link_target'], '_blank' ); ?>><?php _e( 'New Window', 'better-rss-widget' ); ?></option>
          <option value="_top" <?php selected( $instance['link_target'], '_top' ); ?>><?php _e( 'Top Window', 'better-rss-widget' ); ?></option>
     </select>
</p>

<h3><?php _e( 'Cache Settings', 'better-rss-widget' ); ?></h3>
<p>
     <input type="checkbox" value="1" name="<?php print $this->get_field_name( 'enable_cache' ); ?>" id="<?php print $this->get_field_id( 'enable_cache' ); ?>" <?php checked( $instance['enable_cache'], 1 ); ?> />
     <label for="<?php print $this->get_field_id( 'enable_cache' ); ?>">
		<?php _e( 'Enable Cache?', 'better-rss-widget' ); ?>
     </label>
</p>
<p>
     <label for="<?php print $this->get_field_id( 'cache_duration' ); ?>">
		<?php _e( 'Cache Duration (seconds)<br /><small>ex. 3600 seconds = 60 minutes</small>', 'better-rss-widget' ); ?>
     </label>
     <input  id="<?php print $this->get_field_id( 'cache_duration' ); ?>" name="<?php print $this->get_field_name( 'cache_duration' ); ?>" type="text" value="<?php print $instance['cache_duration']; ?>" />
	<?php _e( 'seconds', 'better-rss-widget' ); ?>.
</p>
