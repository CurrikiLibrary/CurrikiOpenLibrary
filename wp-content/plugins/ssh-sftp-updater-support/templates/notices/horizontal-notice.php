<?php if (!defined('SSH_SFTP_UPDATER_SUPPORT_MAIN_PATH')) die('No direct access allowed'); ?>

<div class="updraft-ad-container updated">
	<div class="updraft_notice_container">
		<div class="updraft_advert_content_left">
			<img src="<?php echo SSH_SFTP_UPDATER_SUPPORT_URL.'/images/'.$image; ?>" width="60" height="60" alt="<?php _e('notice image', 'ssh-sftp-updater-support'); ?>" />
		</div>
		<div class="updraft_advert_content_right">
			<h3 class="updraft_advert_heading">
				<?php
				if (!empty($prefix)) echo $prefix.' ';
					echo $title;
				?>
				<div class="updraft-advert-dismiss">
				<?php if (!empty($dismiss_time)) { ?>
					<a href="#" onclick="jQuery('.updraft-ad-container').slideUp(); jQuery.post(ajaxurl, {action: 'ssh_sftp_updater_support_ajax', subaction: '<?php echo $dismiss_time; ?>', nonce: '<?php echo wp_create_nonce('ssh-sftp-updater-support-ajax-nonce'); ?>' });"><?php _e('Dismiss', 'ssh-sftp-updater-support'); ?></a>
				<?php } else { ?>
					<a href="#" onclick="jQuery('.updraft-ad-container').slideUp();"><?php _e('Dismiss', 'ssh-sftp-updater-support'); ?></a>
				<?php } ?>
				</div>
			</h3>
			<p>
				<?php
				echo $text;
					$button_text = '';
					if (isset($discount_code)) echo ' <b>' . $discount_code . '</b>';

					if (!empty($button_link) && !empty($button_meta)) {
					// Check which Message is going to be used.
					if ('updraftcentral' == $button_meta) {
						$button_text = __('Get UpdraftCentral', 'ssh-sftp-updater-support');
					} elseif ('review' == $button_meta) {
						$button_text = __('Review "SSH SFTP Updater Support"', 'ssh-sftp-updater-support');
					} elseif ('updraftplus' == $button_meta) {
						$button_text = __('Get UpdraftPlus', 'ssh-sftp-updater-support');
					} elseif ('signup' == $button_meta) {
						$button_text = __('Sign up', 'ssh-sftp-updater-support');
					} elseif ('go_there' == $button_meta) {
						$button_text = __('Go there', 'ssh-sftp-updater-support');
					} elseif ('wpo-premium' == $button_meta) {
						$button_text = __('Find out more.', 'ssh-sftp-updater-support');
					} elseif ('ssh-sftp-updater-support' == $button_meta) {
						$button_text = __('Find out more.', 'ssh-sftp-updater-support');
					} elseif ('keyy' == $button_meta) {
						$button_text = __('Get Keyy', 'ssh-sftp-updater-support');
					} elseif ('wp-optimize' == $button_meta) {
						$button_text = __('Get WP-Optimize', 'ssh-sftp-updater-support');
					} elseif ('metaslider' == $button_meta) {
						$button_text = __('Get MetaSlider', 'ssh-sftp-updater-support');
					} 
					$ssh_sftp_updater_support->ssh_sftp_updater_support_url($button_link, $button_text, null, 'class="updraft_notice_link"');
					}
				?>
			</p>
		</div>
	</div>
	<div class="clear"></div>
</div>
