<?php if (!defined('SSH_SFTP_UPDATER_SUPPORT_MAIN_PATH')) die('No direct access allowed'); ?>

<div id="ssh-sftp-updater-support-dashnotice" class="updated">

	<div style="float:right;"><a href="#" onclick="jQuery('#ssh-sftp-updater-support-dashnotice').slideUp(); jQuery.post(ajaxurl, {action: 'ssh_sftp_updater_support_ajax', subaction: 'dismiss_dash_notice_until', nonce: '<?php echo wp_create_nonce('ssh-sftp-updater-support-ajax-nonce'); ?>' });"><?php printf(__('Dismiss (for %s months)', 'ssh-sftp-updater-support'), 12); ?></a></div>

	<h3><?php _e("Thank you for installing 'SSH SFTP Updater Support' !", 'ssh-sftp-updater-support'); ?></h3>
	<div id="ssh-sftp-updater-support-dashnotice-wrapper">
		<p>
			<?php echo __("This plugin is offered and maintained as a free service to the WP community. You might also be interested in enhancing your WordPress site with our other top plugins, below. If not, then sorry to trouble you; please use the 'Dismiss' link above and right.", 'ssh-sftp-updater-support'); ?>
		</p>
		<p>
			<?php printf(__('%s simplifies backups and restoration. It is the #1 most-used backup/restore plugin, with over a million currently-active installs.', 'ssh-sftp-updater-support'), '<strong>'.$ssh_sftp_updater_support->ssh_sftp_updater_support_url('https://updraftplus.com/', 'UpdraftPlus').': </strong>'); ?>
		</p>

		<p>
			<?php printf(__('%s a highly efficient way to manage, optimize, update and backup multiple websites from one place.', 'ssh-sftp-updater-support'), '<strong>'.$ssh_sftp_updater_support->ssh_sftp_updater_support_url('https://updraftplus.com/updraftcentral/', 'UpdraftCentral').': </strong>'); ?>
		</p>
		
		<p>
			<?php printf(__('%s helps you to optimize and clean your WordPress database so that it runs at maximum efficiency.', 'ssh-sftp-updater-support'), '<strong>'.$ssh_sftp_updater_support->ssh_sftp_updater_support_url('https://getwpo.com/', 'WP-Optimize').': </strong>'); ?>
		</p>
		
		<p>
			<?php printf(__('%s Simple and secure logins with a wave of your phone.', 'ssh-sftp-updater-support'), '<strong>'.$ssh_sftp_updater_support->ssh_sftp_updater_support_url('https://getkeyy.com/', 'Keyy').': </strong>'); ?>
		</p>

		<p>
			<?php printf(__('%s Easily create beautifully-designed sliders with the #1-most installed WP slider plugin.', 'ssh-sftp-updater-support'), '<strong>'.$ssh_sftp_updater_support->ssh_sftp_updater_support_url('https://www.metaslider.com', 'MetaSlider').': </strong>'); ?>
		</p>
		
		<p>
			<?php '<strong>'.__('More quality plugins', 'ssh-sftp-updater-support').': </strong>';?>
			<strong><?php $ssh_sftp_updater_support->ssh_sftp_updater_support_url('https://www.simbahosting.co.uk/s3/shop/', __('Premium WooCommerce extensions', 'ssh-sftp-updater-support')); ?></strong> | <strong><?php $ssh_sftp_updater_support->ssh_sftp_updater_support_url('https://profiles.wordpress.org/davidanderson#content-plugins', __('Other useful plugins', 'ssh-sftp-updater-support')); ?></a></strong>		
		</p>

	</div>
</div>
