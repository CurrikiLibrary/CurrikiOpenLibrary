<?php
/**
* Plugin Name: Fix Another Update In Progress
* Plugin URI:  http://wordpress.org/plugins/fix-another-update-in-progress
* Description: A quick fix to WordPress another update is already in progress
* Version: 1.0.1
* Author: P. Roy
* Author URI: https://www.proy.info
* License: GPL v3
* Text Domain: fix-another-update-in-progress
**/

/**
 * Fix Another Update In Progress
 * Copyright (C) 2016, P. Roy - contact@proy.info
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if (!class_exists('FixAnotherUpdate')) {
	class FixAnotherUpdate {

		var $nonce = 'fix-another-update-in-progress-options';

		function __construct() {
			//Actions
			add_action('admin_menu', array(&$this, 'admin_menu_link'));
		}

		function admin_menu_link() {
			add_options_page('Fix another update in progress', 'Fix Another Update In Progress', 'manage_options', basename(__FILE__), array(&$this, 'admin_options_page'));
			add_filter('plugin_action_links_' . plugin_basename(__FILE__), array(&$this, 'filter_plugin_actions'), 10, 2 );
		}

		function filter_plugin_actions($links, $file) {
			$settings_link = '<a href="options-general.php?page=' . basename(__FILE__) . '">' . __('Settings', 'fix-another-update-inprogress') . '</a>';
			array_unshift($links, $settings_link); // before other links

			return $links;
		}

		function admin_options_page() {

			if (isset($_POST['d2l_fix_another_update_save'])) {
				check_admin_referer($this->nonce);

				if(version_compare(get_bloginfo('version'),'4.5', '>=') ){
					delete_option( 'core_updater.lock' );
				} else{
					delete_option( 'core_updater' );
				}

				echo '<div class="updated"><p>' . __('Success! You\'ve  successfully fix "another update in progress!"', 'fix-another-update-inprogress') . '</p></div>';
			}

		?>

			<div class='wrap'>
				<h2>Fix Another Update In Progress</h2>
				<span class='description'><?php _e('A quick fix to WordPress another update is already in progress.', 'fix-another-update-in-progress'); ?></span>
				<form method='post' id='d2l_fix_another_update_inprogress_options'>
				<?php wp_nonce_field($this->nonce); ?>
					<?php
						if(version_compare(get_bloginfo('version'),'4.5', '>=') )
						   	$checkcoreupdate = get_option('core_updater.lock', null);
						else
						    $checkcoreupdate = get_option('core_updater', null);

						if( $checkcoreupdate != null ) {
							echo '<h3 class="fix-status" style="color: red">WordPress Update is locked. Click the button below to fix it.</h3>';
						}else{
							echo '<h3 class="fix-status" style="color: green">There is no Update lock issue. Continue with your <a href="update-core.php">WordPress Update</a></h3>';
						}
					?>
					<?php if( $checkcoreupdate != null ) { ?>
					<p class='submit'><input type='submit' value='Fix WordPress Update Lock' name='d2l_fix_another_update_save' class='button-primary' /></p>
					<?php } ?>
				</form>
			</div>

<?php
		}

	}
}

// instantiate the class
if (class_exists('FixAnotherUpdate')) {
	$d2lfix_update = new FixAnotherUpdate();

}
