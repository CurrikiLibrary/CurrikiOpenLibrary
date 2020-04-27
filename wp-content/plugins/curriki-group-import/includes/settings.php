<?php

// this file contains all settings pages and options

function bpaokgg_settings_page() {
	global $bpaokgg_options;
	?>
	<div class="wrap">
		<div id="upb-wrap" class="upb-help">
			<h2><?php _e('Aok Group Generator Settings', 'aok_groupgenerator'); ?></h2>
			<?php
			if ( ! isset( $_REQUEST['updated'] ) )
				$_REQUEST['updated'] = false;
			?>
			<?php if ( false !== $_REQUEST['updated'] ) : ?>
			<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
			<?php endif; ?>
			<form method="post" action="options.php">

				<?php settings_fields( 'bpaokgg_settings_group' ); ?>

				<table class="form-table">
					<tbody>
						<tr>
							<p>Note: You will need to upload a CSV file with just 1 column of manual-codes. No column headers in the file. Limit to 500 per file. You should get a file BACK with additional information about the created BuddyPress groups. <strong>PLEASE BACKUP YOUR DATABASE BEFORE DOING THIS AND BE READY TO RESTORE JUST IN CASE.</strong></p>
						</tr>
						<tr valign="top">
							<th scope="row">
								<?php _e('CSV File Path', 'aok_groupgenerator'); ?>
							</th>
							<td>
								<fieldset>
									<legend class="screen-reader-text">
										<span><?php _e('CSV File Path', 'aok_groupgenerator'); ?></span>
									</legend>
									<label for="bpaokgg_settings[bpaokgg_csv_file_path]">
										<input id="bpaokgg_settings[bpaokgg_csv_file_path]" name="bpaokgg_settings[bpaokgg_csv_file_path]" type="text" value="<?php echo $bpaokgg_options['bpaokgg_csv_file_path'];?>" />
										<?php // _e( 'Description Of Setting Here', 'aok_groupgenerator' ); ?>
									</label>
								</fieldset>
							</td>
						</tr>

					</tbody>
				</table>

				<!-- save the options -->
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e( 'Process File', 'aok_groupgenerator' ); ?>" />
				</p>

			</form>
		</div><!--end sf-wrap-->
	</div><!--end wrap-->
	<?php
}

// register the plugin settings
function bpaokgg_register_settings() {

	// create whitelist of options
	register_setting( 'bpaokgg_settings_group', 'bpaokgg_settings' );

}
//call register settings function
add_action( 'admin_init', 'bpaokgg_register_settings' );


function bpaokgg_settings_menu() {
	global $aok_groupgenerator_page;
	// add settings page
	$aok_groupgenerator_page = add_submenu_page('options-general.php', __('Aok Group Generator', 'aok_groupgenerator'), __('Aok Group Generator', 'aok_groupgenerator'),'manage_options', 'aok-groupgenerator-settings', 'bpaokgg_settings_page');
	// load each of the help tabs
	// add_action("load-$aok_groupgenerator_page", "bpaokgg_contextual_help");
}
add_action('admin_menu', 'bpaokgg_settings_menu');


function bpaokgg_contextual_help($hook) {
	global $aok_groupgenerator_page;
	$screen = get_current_screen();
	if(!is_object($screen))
		return;

	switch($screen->id) :

		case $aok_groupgenerator_page :
			$screen->add_help_tab(
				array(
					'id' => 'general',
					'title' => __('General', 'aok_groupgenerator'),
					'content' => bpaokgg_render_help_tab('general')
				)
			);
			$screen->add_help_tab(
				array(
					'id' => 'template_tags',
					'title' => __('Template Tags', 'aok_groupgenerator'),
					'content' => bpaokgg_render_help_tab('template_tags')
				)
			);
			$screen->add_help_tab(
				array(
					'id' => 'custom_css',
					'title' => __('Custom CSS', 'aok_groupgenerator'),
					'content' => bpaokgg_render_help_tab('custom_css')
				)
			);
		break;
	endswitch;
}
add_action('admin_menu', 'bpaokgg_contextual_help', 100);

function bpaokgg_render_help_tab($tab_id) {

	switch($tab_id) :

		case 'general' :
			ob_start(); ?>
			<p>Love It Pro allows you to add "Love It" links to your posts, pages, and custom post types. The Love It links function must like Facebook's Like button: they allow your users to show their appreciation.</p>
			<p>When a user clicks "Love It", the love count for the item is increased by one. The total number of loves on a psot or page can then be used to display your "most loved items".</p>
			<p>Love It Pro is great way to give your users a simple, but great way of interacting with your site a little more, and it provides very valuble feedback to you as a site administrator.</p>
			<?php
			break;
		case 'template_tags' :
			ob_start(); ?>
			<p>There are three template tags you can use with this plugin if you wish to integrate it more fully into your site.</p>
			<p><strong>bpaokgg_aok_groupgenerator_link()</strong> - this is the function that can be used to display the Love It link / Already Love This text. It also outputs the Love count.</p>
			<p>The function has four parameters: <em>bpaokgg_aok_groupgenerator_link($post_id = null, $link_text, $already_loved_text, $echo = true)</em>:</p>
			<ul>
				<li><em>$post_id</em> - the ID of the item to love (for a post, page, or CPT)</li>
				<li><em>$link_text</em> - the text to show for the "Love It" link</li>
				<li><em>$already_loved_text</em> - the text to show for the "Already Loved This" message</li>
				<li><em>$echo</em> - whether to echo or return the final link HTML</li>
			</ul>
			<p><strong>li_get_love_count($post_id)</strong> - this will retrieve the total love count of the specified item ID. The value is returned, so you must echo it to display the count.</p>
			<p><strong>li_user_has_loved_post($user_id, $post_id)</strong> - this can be used to determine if a user has loved an item or not. It should be used as a conditional, like this: <em>if(li_user_has_loved_post($user_id, $post_id)) { // show something if the user has loved the item }</em></p>
			<?php
			break;
		case 'custom_css' :
			ob_start(); ?>
			<p>If you wish to modify the appearance of the plugin, you may do so by adding custom CSS to the box below. Here is a list of HTML elements and class names:</p>
			<ul>
				<li><em>div.love-it-wrapper</em> - the DIV taht wraps the Love it link</li>
				<li><em>a.love-it</em> - the anchor tag for the Love It link</li>
				<li><em>span.love-count</em> - the span tag that contains the total love count</li>
				<li><em>span.loved</em> - the span tag that contains the text for an item that has been loved</li>
			</ul>
			<?php
			break;
		default;
			break;

	endswitch;

	return ob_get_clean();
}