<?php

if (!defined('SSH_SFTP_UPDATER_SUPPORT_MAIN_PATH')) die('No direct access allowed');

if (!class_exists('Updraft_Notices_1_0')) require_once(SSH_SFTP_UPDATER_SUPPORT_MAIN_PATH.'/includes/updraft-notices.php');

class SSH_SFTP_Updater_Support_Notices extends Updraft_Notices_1_0 {

	protected static $_instance = null;

	private $initialized = false;

	protected $self_affiliate_id = 212;

	protected $notices_content = array();

	/**
	 * Creates and returns the only notice instance
	 *
	 * @return a SSH_SFTP_Updater_Support_Notices instance
	 */
	public static function instance() {
		if (empty(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * This method gets any parent notices and adds its own notices to the notice array
	 *
	 * @return Array returns an array of notices
	 */
	protected function populate_notices_content() {
		global $ssh_sftp_updater_support;
		$parent_notice_content = parent::populate_notices_content();
		$child_notice_content = array(
			'updraftplus' => array(
				'prefix' => '',
				'title' => __('Make sure you backup before you optimize your database', 'ssh-sftp-updater-support'),
				'text' => __("UpdraftPlus is the world's most trusted backup plugin from the owners of SSH SFTP Updater Support", 'ssh-sftp-updater-support'),
				'image' => 'notices/updraft_logo.png',
				'button_link' => 'https://updraftplus.com/',
				'button_meta' => 'updraftplus',
				'dismiss_time' => 'dismiss_page_notice_until',
				'supported_positions' => $this->anywhere,
				'validity_function' => 'is_updraftplus_installed',
			),
			'updraftcentral' => array(
				'prefix' => '',
				'title' => __('Save Time and Money. Manage multiple WordPress sites from one location.', 'ssh-sftp-updater-support'),
				'text' => __('UpdraftCentral is a highly efficient way to take backup, update and manage multiple WP sites from one location.', 'ssh-sftp-updater-support'),
				'image' => 'notices/updraft_logo.png',
				'button_link' => 'https://updraftplus.com/updraftcentral/',
				'button_meta' => 'updraftcentral',
				'dismiss_time' => 'dismiss_page_notice_until',
				'supported_positions' => $this->anywhere,
				'validity_function' => 'is_updraftcentral_installed',
			),
			'wp-optimize' => array(
				'prefix' => '',
				'title' => 'WP-Optimize',
				'text' => __("After you've backed up your database, we recommend you install our WP-Optimize plugin to streamline it for better website performance.", 'ssh-sftp-updater-support'),
				'image' => 'notices/wp_optimize_logo.png',
				'button_link' => 'https://getwpo.com',
				'button_meta' => 'wp-optimize',
				'dismiss_time' => 'dismiss_page_notice_until',
				'supported_positions' => $this->anywhere,
				'validity_function' => 'is_wpo_installed',
			),
			'keyy' => array(
				'prefix' => '',
				'title' => 'Keyy: Instant and secure logon with a wave of your phone',
				'text' => __("Find out more about our revolutionary new WordPress plugin.", "ssh-sftp-updater-support"),
				'button_link' => 'https://getkeyy.com/',
				'button_meta' => 'keyy',
				'image' => 'notices/keyy_logo.png',
				'dismiss_time' => 'dismiss_page_notice_until',
				'supported_positions' => $this->anywhere,
				'validity_function' => 'is_keyy_installed',
			),			
			'meta-slider' => array(
				'prefix' => '',
				'title' => __("MetaSlider: the world's #1 slider plugin from the makers of SSH SFTP Updater Support", "ssh-sftp-updater-support"),
				'text' => __("With MetaSlider, you can easily add style and flare with beautifully-designed sliders.", "ssh-sftp-updater-support"),
				'button_link' => 'https://metaslider.com',
				'button_meta' => 'metaslider',
				'image' => 'notices/metaslider_logo.png',
				'dismiss_time' => 'dismiss_page_notice_until',
				'supported_positions' => $this->anywhere,
				'validity_function' => 'is_metaslider_installed',
			),
			
		);

		return array_merge($parent_notice_content, $child_notice_content);
	}
	
	/**
	 * Call this method to setup the notices
	 */
	public function notices_init() {
		if ($this->initialized) return;
		$this->initialized = true;
		$this->notices_content = (defined('SSH_SFTP_UPDATER_SUPPORT_NOADS_B') && SSH_SFTP_UPDATER_SUPPORT_NOADS_B) ? array() : $this->populate_notices_content();
		$our_version = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? SSH_SFTP_UPDATER_SUPPORT_VERSION.'.'.time() : SSH_SFTP_UPDATER_SUPPORT_VERSION;
		$min_or_not = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';
		wp_enqueue_style('ssh-sftp-updater-support-notices-css',  SSH_SFTP_UPDATER_SUPPORT_URL.'/css/ssh-sftp-updater-support-notices'.$min_or_not.'.css', array(), $our_version);
	}

	/**
	 * This method will call the parent is_plugin_installed and pass in the product updraftplus to check if that plugin is installed if it is then we shouldn't display the notice
	 *
	 * @param  string  $product             the plugin slug
	 * @param  boolean $also_require_active a bool to indicate if the plugin should also be active
	 * @return boolean                      a bool to indicate if the notice should be displayed or not
	 */
	protected function is_updraftplus_installed($product = 'updraftplus', $also_require_active = false) {
		return parent::is_plugin_installed($product, $also_require_active);
	}

	/**
	 * This method will call the parent is_plugin_installed and pass in the product updraftcentral to check if that plugin is installed if it is then we shouldn't display the notice
	 *
	 * @param  string  $product             the plugin slug
	 * @param  boolean $also_require_active a bool to indicate if the plugin should also be active
	 * @return boolean                      a bool to indicate if the notice should be displayed or not
	 */
	protected function is_updraftcentral_installed($product = 'updraftcentral', $also_require_active = false) {
		return parent::is_plugin_installed($product, $also_require_active);
	}

	/**
	 * This method will call the parent is_plugin_installed and pass in the product keyy to check if that plugin is installed if it is then we shouldn't display the notice
	 *
	 * @param  string  $product             the plugin slug
	 * @param  boolean $also_require_active a bool to indicate if the plugin should also be active
	 * @return boolean                      a bool to indicate if the notice should be displayed or not
	 */
	protected function is_keyy_installed($product = 'keyy', $also_require_active = false) {
		return parent::is_plugin_installed($product, $also_require_active);
	}

	/**
	 * This method will call the is premium function in the SSH_SFTP_Updater_Support_Notices object to check if this install is premium and if it is we won't display the notice
	 *
	 * @return boolean a bool to indicate if we should display the notice or not
	 */
	protected function is_wpo_installed($product = 'wp-optimize', $also_require_active = false) {
		return parent::is_plugin_installed($product, $also_require_active);
	}

	/**
	 * This method will check to see if Meta Slider plugin is installed.
	 *
	 * @param  String  $product			    the plugin slug
	 * @param  boolean $also_require_active a bool to indicate if the plugin should be active or not
	 * @return boolean					    a bool to indicate if the notice should be displayed or not
	 */
	protected function is_metaslider_installed($product = 'ml-slider', $also_require_active = false) {
		return parent::is_plugin_installed($product, $also_require_active);
	}


	/**
	 * This method calls the parent verson and will work out if the user is using a non english language and if so returns true so that they can see the translation advert.
	 *
	 * @param  String $plugin_base_dir the plugin base directory
	 * @param  String $product_name    the name of the plugin
	 * @return Boolean                 returns true if the user is using a non english language and could translate otherwise false
	 */
	protected function translation_needed($plugin_base_dir = null, $product_name = null) {
		return parent::translation_needed(SSH_SFTP_UPDATER_SUPPORT_MAIN_PATH, 'ssh-sftp-updater-support');
	}
	
	/**
	 * This method checks to see if the notices dismiss_time parameter has been dismissed
	 *
	 * @param  String $dismiss_time a string containing the dimiss time ID
	 * @return Boolaen              returns true if the notice has been dismissed and shouldn't be shown otherwise display it
	 */
	protected function check_notice_dismissed($dismiss_time) {

		$time_now = (defined('SSH_SFTP_UPDATER_SUPPORT_NOTICES_FORCE_TIME') ? SSH_SFTP_UPDATER_SUPPORT_NOTICES_FORCE_TIME : time());

		$notice_dismiss = ($time_now < 	get_site_option('ssh_sftp_updater_support_dismiss_page_notice_until', 0));

		$dismiss = false;

		if ('dismiss_page_notice_until' == $dismiss_time) $dismiss = $notice_dismiss;

		return $dismiss;
	}

	/**
	 * Check notice data for seasonal info and return true if we should display this notice.
	 *
	 * @param array $notice_data
	 * @return bool
	 */
	protected function skip_seasonal_notices($notice_data) {
		$time_now = defined('SSH_SFTP_UPDATER_SUPPORT_FORCE_TIME_NOTICES_FORCE_TIME') ? SSH_SFTP_UPDATER_SUPPORT_FORCE_TIME_NOTICES_FORCE_TIME : time();
		$valid_from = strtotime($notice_data['valid_from']);
		$valid_to = strtotime($notice_data['valid_to']);
		$dismiss = $this->check_notice_dismissed($notice_data['dismiss_time']);
		if (($time_now >= $valid_from && $time_now <= $valid_to) && !$dismiss) {
			// return true so that we return this notice to be displayed
			return true;
		}
		
		return false;
	}

	/**
	 * This method will create the chosen notice and the template to use and depending on the parameters either echo it to the page or return it
	 *
	 * @param  Array   $advert_information     an array with the notice information in
	 * @param  Boolean $return_instead_of_echo a bool value to indicate if the notice should be printed to page or returned
	 * @param  String  $position               a string to indicate what template should be used
	 * @return String                          a notice to display
	 */
	protected function render_specified_notice($advert_information, $return_instead_of_echo = false, $position = 'top') {
	
		if ('bottom' == $position) {
			$template_file = 'bottom-notice.php';
		} elseif ('report' == $position) {
			$template_file = 'report.php';
		} elseif ('report-plain' == $position) {
			$template_file = 'report-plain.php';
		} else {
			$template_file = 'horizontal-notice.php';
		}
		
		$extract_variables = array_merge($advert_information, array('ssh_sftp_updater_support_notices' => $this));

		return SSH_SFTP_Updater_Support()->include_template('notices/'.$template_file, $return_instead_of_echo, $extract_variables);
	}
}
