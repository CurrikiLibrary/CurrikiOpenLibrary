<?php
/*
Plugin Name: SSH SFTP Updater Support
Plugin URI: https://wordpress.org/plugins/ssh-sftp-updater-support/
Description: Update your WordPress blog / plugins via SFTP without libssh2
Version: 0.8.1
Author: TerraFrost, David Anderson + Team Updraft
Author URI: https://updraftplus.com/
*/

if (!defined('ABSPATH')) die('No direct access allowed');

define('SSH_SFTP_UPDATER_SUPPORT_MAIN_PATH', plugin_dir_path(__FILE__));
define('SSH_SFTP_UPDATER_SUPPORT_BASENAME', plugin_basename(__FILE__));
define('SSH_SFTP_UPDATER_SUPPORT_VERSION', '0.8.1');
define('SSH_SFTP_UPDATER_SUPPORT_URL', plugin_dir_url(__FILE__));
// see http://adambrown.info/p/wp_hooks/hook/<filter name>
add_filter('filesystem_method', 'phpseclib_filesystem_method', 10, 2); // since 2.6 - WordPress will ignore the ssh option if the php ssh extension is not loaded
add_filter('request_filesystem_credentials', 'phpseclib_request_filesystem_credentials', 10, 1024); // since 2.5 - Alter some strings and don't ask for the public key
add_filter('fs_ftp_connection_types', 'phpseclib_fs_ftp_connection_types'); // since 2.9 - Add the SSH2 option to the connection options
add_filter('filesystem_method_file', 'phpseclib_filesystem_method_file', 10, 2); // since 2.6 - Direct WordPress to use our ssh2 class

// disable the modal dialog on WordPress >= 4.2
if (version_compare(get_bloginfo('version'), '4.2.0', '>=')) add_action('admin_head-plugins.php', 'phpseclib_disable_update_link_onclick');

function phpseclib_disable_update_link_onclick() {
?>
<script>
	jQuery(function($) {
		$(".plugin-update-tr").off("click", ".update-link");
	});
</script>
<?php
}

function phpseclib_filesystem_method_file($path, $method) {
	return $method == 'ssh2' ?
		dirname(__FILE__) . '/class-wp-filesystem-ssh2.php' :
		$path;
}

/**
 * Used by the WP filter phpseclib_filesystem_method
 */
function phpseclib_filesystem_method($method, $args) {
	return (isset($args['connection_type']) && 'ssh' == $args['connection_type']) ? 'ssh2' : $method;
}

function phpseclib_fs_ftp_connection_types($types) {
	$types['ssh'] = __('SSH2');
	return $types;
}

// this has been pretty much copy / pasted from wp-admin/includes/file.php
function phpseclib_request_filesystem_credentials($value, $form_post, $type = '', $error = false, $context = false, $extra_fields = null, $allow_relaxed_file_ownership = false) {
	if ( empty($type) )
		$type = get_filesystem_method(array(), $context, $allow_relaxed_file_ownership);

	if ( 'direct' == $type )
		return $value;

	if ( is_null( $extra_fields ) )
		$extra_fields = array( 'version', 'locale' );

	$credentials = get_option('ftp_credentials', array( 'hostname' => '', 'username' => ''));

	// If defined, set it to that, Else, If POST'd, set it to that, If not, Set it to whatever it previously was(saved details in option)
	$credentials['hostname'] = defined('FTP_HOST') ? FTP_HOST : (!empty($_POST['hostname']) ? stripslashes($_POST['hostname']) : $credentials['hostname']);
	$credentials['username'] = defined('FTP_USER') ? FTP_USER : (!empty($_POST['username']) ? stripslashes($_POST['username']) : $credentials['username']);
	$credentials['password'] = defined('FTP_PASS') ? FTP_PASS : (!empty($_POST['password']) ? stripslashes($_POST['password']) : '');

	// Check to see if we are setting the private key for ssh
	if (defined('FTP_PRIKEY') && file_exists(FTP_PRIKEY)) {
		$credentials['private_key'] = file_get_contents(FTP_PRIKEY);
	} else {
		$credentials['private_key'] = (!empty($_POST['private_key'])) ? stripslashes($_POST['private_key']) : '';
		if (isset($_FILES['private_key_file']) && file_exists($_FILES['private_key_file']['tmp_name'])) {
			$credentials['private_key'] = file_get_contents($_FILES['private_key_file']['tmp_name']);
		}
	}

	//sanitize the hostname, Some people might pass in odd-data:
	$credentials['hostname'] = preg_replace('|\w+://|', '', $credentials['hostname']); //Strip any schemes off

	if ( strpos($credentials['hostname'], ':') ) {
		list( $credentials['hostname'], $credentials['port'] ) = explode(':', $credentials['hostname'], 2);
		if ( ! is_numeric($credentials['port']) )
			unset($credentials['port']);
	} else {
		unset($credentials['port']);
	}

	if ( (defined('FTP_SSH') && FTP_SSH) || (defined('FS_METHOD') && 'ssh' == FS_METHOD) )
		$credentials['connection_type'] = 'ssh';
	else if ( (defined('FTP_SSL') && FTP_SSL) && 'ftpext' == $type ) //Only the FTP Extension understands SSL
		$credentials['connection_type'] = 'ftps';
	else if ( !empty($_POST['connection_type']) )
		$credentials['connection_type'] = stripslashes($_POST['connection_type']);
	else if ( !isset($credentials['connection_type']) ) //All else fails (And its not defaulted to something else saved), Default to FTP
		$credentials['connection_type'] = 'ftp';

	if ( ! $error &&
			(
				( !empty($credentials['password']) && !empty($credentials['username']) && !empty($credentials['hostname']) ) ||
				( 'ssh' == $credentials['connection_type'] && !empty($credentials['private_key']) )
			) ) {
		$stored_credentials = $credentials;
		if ( !empty($stored_credentials['port']) ) //save port as part of hostname to simplify above code.
			$stored_credentials['hostname'] .= ':' . $stored_credentials['port'];

		unset($stored_credentials['password'], $stored_credentials['port'], $stored_credentials['private_key'], $stored_credentials['public_key']);
		if ( ! defined( 'WP_INSTALLING' ) ) {
			update_option( 'ftp_credentials', $stored_credentials );
		}
		return $credentials;
	}
	$hostname = isset( $credentials['hostname'] ) ? $credentials['hostname'] : '';
	$username = isset( $credentials['username'] ) ? $credentials['username'] : '';
	$public_key = isset( $credentials['public_key'] ) ? $credentials['public_key'] : '';
	$private_key = isset( $credentials['private_key'] ) ? $credentials['private_key'] : '';
	$port = isset( $credentials['port'] ) ? $credentials['port'] : '';
	$connection_type = isset( $credentials['connection_type'] ) ? $credentials['connection_type'] : '';

	if ( $error ) {
		$error_string = __('<strong>ERROR:</strong> There was an error connecting to the server, Please verify the settings are correct.');
		if ( is_wp_error($error) ) {
			$error_strings = $error->get_error_messages();
			//foreach ( $error_strings as &$error_string )
			//	$error_string = esc_html( $error_string );
			$error_string = implode('<br />', $error_strings);
		}
		echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
	}

	$types = array();
	if ( extension_loaded('ftp') || extension_loaded('sockets') || function_exists('fsockopen') )
		$types[ 'ftp' ] = __('FTP');
	if ( extension_loaded('ftp') ) //Only this supports FTPS
		$types[ 'ftps' ] = __('FTPS (SSL)');

	$types = apply_filters('fs_ftp_connection_types', $types, $credentials, $type, $error, $context);

?>
<script type="text/javascript">
<!--
jQuery(function($){
	jQuery("#ssh").click(function () {
		jQuery(".ssh_keys").show();
	});
	jQuery("#ftp, #ftps").click(function () {
		jQuery(".ssh_keys").hide();
	});
	jQuery("#private_key_file").change(function (event) {
		if (window.File && window.FileReader) {
			var reader = new FileReader();
			reader.onload = function(file) {
				jQuery("#private_key").val(file.target.result);
			};
			reader.readAsBinaryString(event.target.files[0]);
		}
	});
	jQuery("form").submit(function () {
		if(typeof(Storage)!=="undefined") {
			localStorage.privateKeyFile = jQuery("#private_key").val();
		}
		jQuery("#private_key_file").attr("disabled", "disabled");
	});
	if(typeof(Storage)!=="undefined" && localStorage.privateKeyFile) {
		jQuery("#private_key").val(localStorage.privateKeyFile);
	}
	jQuery('form input[value=""]:first').focus();
});
-->
</script>
<form action="<?php echo $form_post ?>" method="post" enctype="multipart/form-data">
<div class="wrap">
<h2><?php _e('Connection Information') ?></h2>
<p><?php
	$label_user = __('Username');
	$label_pass = __('Password');
	_e('To perform the requested action, WordPress needs to access your web server.');
	echo ' ';
	if ( ( isset( $types['ftp'] ) || isset( $types['ftps'] ) ) ) {
		if ( isset( $types['ssh'] ) ) {
			_e('Please enter your FTP or SSH credentials to proceed.');
			$label_user = __('FTP/SSH Username');
			$label_pass = __('FTP/SSH Password');
		} else {
			_e('Please enter your FTP credentials to proceed.');
			$label_user = __('FTP Username');
			$label_pass = __('FTP Password');
		}
		echo ' ';
	}
	_e('If you do not remember your credentials, you should contact your web host.');
?></p>
<table class="form-table">
<tr valign="top">
<th scope="row"><label for="hostname"><?php _e('Hostname') ?></label></th>
<td><input name="hostname" type="text" id="hostname" value="<?php echo esc_attr($hostname); if ( !empty($port) ) echo ":$port"; ?>"<?php disabled( defined('FTP_HOST') ); ?> size="40" /></td>
</tr>

<tr valign="top">
<th scope="row"><label for="username"><?php echo $label_user; ?></label></th>
<td><input name="username" type="text" id="username" value="<?php echo esc_attr($username) ?>"<?php disabled( defined('FTP_USER') ); ?> size="40" /></td>
</tr>

<tr valign="top">
<th scope="row"><label for="password"><?php echo $label_pass; ?></label></th>
<td><input name="password" type="password" id="password" value="<?php if ( defined('FTP_PASS') ) echo '*****'; ?>"<?php disabled( defined('FTP_PASS') ); ?> size="40" /></td>
</tr>

<?php if ( isset($types['ssh']) ) : ?>
<tr class="ssh_keys" valign="top" style="<?php if ( 'ssh' != $connection_type ) echo 'display:none' ?>">
<th scope="row" colspan="2"><?php _e('SSH Authentication Keys') ?>
<div><?php _e('If a passphrase is needed, enter that in the password field above.') ?></div></th></tr>
<tr class="ssh_keys" valign="top" style="<?php if ( 'ssh' != $connection_type ) echo 'display:none' ?>">
<th scope="row">
<div class="key-labels textright">
<label for="private_key"><?php _e('Copy / Paste Private Key:') ?></label>
</div>
</th>
<td><textarea name="private_key" id="private_key" cols="58" rows="10" value="<?php echo esc_attr($private_key) ?>"<?php disabled( defined('FTP_PRIKEY') ); ?>></textarea>
</td>
</tr>
<tr class="ssh_keys" valign="top" style="<?php if ( 'ssh' != $connection_type ) echo 'display:none' ?>">
<th scope="row">
<div class="key-labels textright">
<label for="private_key_file"><?php _e('Upload Private Key:') ?></label>
</div>
</th>
<td><input name="private_key_file" id="private_key_file" type="file" <?php disabled( defined('FTP_PRIKEY') ); ?>/>
</td>
</tr>
<?php endif; ?>

<tr valign="top">
<th scope="row"><?php _e('Connection Type') ?></th>
<td>
<fieldset><legend class="screen-reader-text"><span><?php _e('Connection Type') ?></span></legend>
<?php
	$disabled = disabled( (defined('FTP_SSL') && FTP_SSL) || (defined('FTP_SSH') && FTP_SSH), true, false );
	foreach ( $types as $name => $text ) : ?>
	<label for="<?php echo esc_attr($name) ?>">
		<input type="radio" name="connection_type" id="<?php echo esc_attr($name) ?>" value="<?php echo esc_attr($name) ?>"<?php checked($name, $connection_type); echo $disabled; ?> />
		<?php echo $text ?>
	</label>
	<?php endforeach; ?>
</fieldset>
</td>
</tr>
</table>

<?php
foreach ( (array) $extra_fields as $field ) {
	if ( isset( $_POST[ $field ] ) )
		echo '<input type="hidden" name="' . esc_attr( $field ) . '" value="' . esc_attr( stripslashes( $_POST[ $field ] ) ) . '" />';
}
?>
<p class="submit">
	<input type="submit" name="upgrade" id="upgrade" class="button" value="<?php esc_attr_e( 'Proceed' ); ?>"  />
</p>
</div>
</form>
<?php
	return false;
}

// Check to make sure if SSH_SFTP_Updater_Support is already call and returns.
if (!class_exists('SSH_SFTP_Updater_Support')) :

class SSH_SFTP_Updater_Support {

	private $template_directories;

	protected static $_instance = null;
	
	protected static $_options_instance = null;

	protected static $_notices_instance = null;

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action('plugins_loaded', array($this, 'plugins_loaded'), 1);
		add_action('admin_init', array($this, 'admin_init'));
		add_action('wp_ajax_ssh_sftp_updater_support_ajax', array($this, 'ssh_sftp_updater_support_ajax_handler'));
		add_filter('plugin_row_meta',  array($this, 'plugin_row_meta'), 10, 2);
	}

	public static function instance() {
		if (empty(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public static function get_notices() {
		if (empty(self::$_notices_instance)) {
			if (!class_exists('SSH_SFTP_Updater_Support_Notices')) include_once(SSH_SFTP_UPDATER_SUPPORT_MAIN_PATH.'/includes/ssh-sftp-updater-support-notices.php');
			self::$_notices_instance = new SSH_SFTP_Updater_Support_Notices();
		}
		return self::$_notices_instance;
	}
	
	/**
	 * Checks if this is the premium version and loads it. It also ensures that if the free version is installed then it is disabled with an appropriate error message.
	 */
	public function plugins_loaded() {
		// Loads the language file.
		load_plugin_textdomain('ssh-sftp-updater-support', false, dirname(plugin_basename(__FILE__)) . '/languages');
	}
	
	/**
	 * Gets an array of plugins active on either the current site, or site-wide
	 *
	 * @return Array - a list of plugin paths (relative to the plugin directory)
	 */
	private function get_active_plugins() {
	
		// Gets all active plugins on the current site
		$active_plugins = get_option('active_plugins');

		if (is_multisite()) {
			$network_active_plugins = get_site_option('active_sitewide_plugins');
			if (!empty($network_active_plugins)) {
				$network_active_plugins = array_keys($network_active_plugins);
				$active_plugins = array_merge($active_plugins, $network_active_plugins);
			}
		}
		
		return $active_plugins;
	}
	
	/**
	 * This function checks whether a specific plugin is installed, and returns information about it
	 *
	 * @param  string $name Specify "Plugin Name" to return details about it.
	 * @return array        Returns an array of details such as if installed, the name of the plugin and if it is active.
	 */
	public function is_installed($name) {

		// Needed to have the 'get_plugins()' function
		include_once(ABSPATH.'wp-admin/includes/plugin.php');

		// Gets all plugins available
		$get_plugins = get_plugins();
	
		$active_plugins = $this->get_active_plugins();
	
		$plugin_info['installed'] = false;
		$plugin_info['active'] = false;

		// Loops around each plugin available.
		foreach ($get_plugins as $key => $value) {
			// If the plugin name matches that of the specified name, it will gather details.
			if ($value['Name'] != $name) continue;
			$plugin_info['installed'] = true;
			$plugin_info['name'] = $key;
			$plugin_info['version'] = $value['Version'];
			if (in_array($key, $active_plugins)) {
				$plugin_info['active'] = true;
			}
			break;
		}
		return $plugin_info;
	}

	public function admin_init() {
		$pagenow = $GLOBALS['pagenow'];

		$this->register_template_directories();

		if (('index.php' == $pagenow && current_user_can('update_plugins')) || ('index.php' == $pagenow && defined('SSH_SFTP_UPDATER_SUPPORT_FORCE_DASHNOTICE') && SSH_SFTP_UPDATER_SUPPORT_FORCE_DASHNOTICE)) {
			
			$dismissed_until = get_site_option('ssh_sftp_updater_support_dismiss_dash_notice_until', 0);

			if (file_exists(SSH_SFTP_UPDATER_SUPPORT_MAIN_PATH . '/index.html')) {
				$installed = filemtime(SSH_SFTP_UPDATER_SUPPORT_MAIN_PATH . '/index.html');
				$installed_for = (time() - $installed);
			}
			if (($installed && time() > $dismissed_until && $installed_for < (14 * 86400) && !defined('SSH_SFTP_UPDATER_SUPPORT_NOADS_B')) || (defined('SSH_SFTP_UPDATER_SUPPORT_FORCE_DASHNOTICE') && SSH_SFTP_UPDATER_SUPPORT_FORCE_DASHNOTICE)) {
				add_action('all_admin_notices', array($this, 'show_admin_notice_upgradead'));
			} else {
				// These are not desired.
				// add_action('all_admin_notices', array($this->get_notices(), 'do_notice'));
			}
		}
	}

	public function show_admin_notice_upgradead() {
		$this->include_template('notices/thanks-for-using-main-dash.php');
	}
	
	public function capability_required() {
		return apply_filters('ssh_sftp_updater_support_capability_required', 'manage_options');
	}
	
	public function ssh_sftp_updater_support_ajax_handler() {
		$nonce = empty($_POST['nonce']) ? '' : $_POST['nonce'];

		if (!wp_verify_nonce($nonce, 'ssh-sftp-updater-support-ajax-nonce') || empty($_POST['subaction'])) die('Security check');

		$subaction = $_POST['subaction'];
		$data = isset($_POST['data']) ? $_POST['data'] : null;

		if (!current_user_can($this->capability_required())) die('Security check');

		$results = array();

		// Some commands that are available via AJAX only.
		if ('dismiss_dash_notice_until' == $subaction) {
			update_site_option('ssh_sftp_updater_support_dismiss_dash_notice_until', (time() + 366 * 86400));
		} elseif ('dismiss_page_notice_until' == $subaction) {
			update_site_option('ssh_sftp_updater_support_dismiss_page_notice_until', (time() + 84 * 86400));
		}

		echo json_encode($results);

		die;
	}
	
	private function wp_normalize_path($path) {
		// Wp_normalize_path is not present before WP 3.9.
		if (function_exists('wp_normalize_path')) return wp_normalize_path($path);
		// Taken from WP 4.6.
		$path = str_replace('\\', '/', $path);
		$path = preg_replace('|(?<=.)/+|', '/', $path);
		if (':' === substr($path, 1, 1)) {
			$path = ucfirst($path);
		}
		return $path;
	}
	
	public function get_templates_dir() {
		return apply_filters('ssh_sftp_updater_support_templates_dir', $this->wp_normalize_path(SSH_SFTP_UPDATER_SUPPORT_MAIN_PATH.'/templates'));
	}

	public function get_templates_url() {
		return apply_filters('ssh_sftp_updater_support_templates_url', SSH_SFTP_UPDATER_SUPPORT_URL.'/templates');
	}

	public function include_template($path, $return_instead_of_echo = false, $extract_these = array()) {
		if ($return_instead_of_echo) ob_start();

		if (preg_match('#^([^/]+)/(.*)$#', $path, $matches)) {
			$prefix = $matches[1];
			$suffix = $matches[2];
			if (isset($this->template_directories[$prefix])) {
				$template_file = $this->template_directories[$prefix].'/'.$suffix;
			}
		}

		if (!isset($template_file)) {
			$template_file = SSH_SFTP_UPDATER_SUPPORT_MAIN_PATH.'/templates/'.$path;
		}

		$template_file = apply_filters('ssh_sftp_updater_support_template', $template_file, $path);

		do_action('ssh_sftp_updater_support_before_template', $path, $template_file, $return_instead_of_echo, $extract_these);

		if (!file_exists($template_file)) {
			error_log("SSH SFTP Updater Support: template not found: ".$template_file);
			echo __('Error:', 'ssh-sftp-updater-support').' '.__('template not found', 'ssh-sftp-updater-support')." (".$path.")";
		} else {
			extract($extract_these);
			$wpdb = $GLOBALS['wpdb'];
			$ssh_sftp_updater_support = $this;
			$ssh_sftp_updater_support_notices = $this->get_notices();
			include $template_file;
		}

		do_action('ssh_sftp_updater_support_after_template', $path, $template_file, $return_instead_of_echo, $extract_these);

		if ($return_instead_of_echo) return ob_get_clean();
	}

	/**
	 * Build a list of template directories (stored in self::$template_directories)
	 */
	private function register_template_directories() {

		$template_directories = array();

		$templates_dir = $this->get_templates_dir();

		if ($dh = opendir($templates_dir)) {
			while (($file = readdir($dh)) !== false) {
				if ('.' == $file || '..' == $file) continue;
				if (is_dir($templates_dir.'/'.$file)) {
					$template_directories[$file] = $templates_dir.'/'.$file;
				}
			}
			closedir($dh);
		}

		// Optimal hook for most extensions to hook into.
		$this->template_directories = apply_filters('ssh_sftp_updater_support_template_directories', $template_directories);

	}
	
	/**
	 * This will customize a URL with a correct Affiliate link
	 * This function can be update to suit any URL as longs as the URL is passed
	 *
	 * @param String  $url					  - URL to be check to see if it an updraftplus match.
	 * @param String  $text					  - Text to be entered within the href a tags.
	 * @param String  $html					  - Any specific HTML to be added.
	 * @param String  $class				  - Specify a class for the href (including the attribute label)
	 * @param Boolean $return_instead_of_echo - if set, then the result will be returned, not echo-ed.
	 *
	 * @return String|void
	 */
	public function ssh_sftp_updater_support_url($url, $text, $html = '', $class = '', $return_instead_of_echo = false) {
		// Check if the URL is UpdraftPlus.
		if (false !== strpos($url, '//updraftplus.com')) {
			// Set URL with Affiliate ID.
			$url = $url.'?ref='.$this->get_notices()->get_affiliate_id().'&source=sshsmtp';

			// Apply filters.
			$url = apply_filters('ssh_sftp_updater_support_updraftplus_com_link', $url);
		}
		// Return URL - check if there is HTML such as images.
		if ('' != $html) {
			$result = '<a '.$class.' href="'.esc_attr($url).'">'.$html.'</a>';
		} else {
			$result = '<a '.$class.' href="'.esc_attr($url).'">'.htmlspecialchars($text).'</a>';
		}
		if ($return_instead_of_echo) return $result;
		echo $result;
	}
	
	/**
    * Add a "Other useful plugins" link in the links in the line for the plugin in the 'Plugin' page
    *
    * @param array  $plugin_meta An array of the plugin's metadata
	* @param string $plugin_file Path to the plugin file, relative to the plugins directory
    * @return array $links An array of plugin action links
    */
	public function plugin_row_meta($plugin_meta_links, $plugin_file) {
		if (SSH_SFTP_UPDATER_SUPPORT_BASENAME ==  $plugin_file) {
			$plugin_meta_links[] = '<a href="https://profiles.wordpress.org/davidanderson#content-plugins">'.__('Other useful plugins', 'ssh-sftp-updater-support').'</a>';
		}
		return $plugin_meta_links;
	}
}

function SSH_SFTP_Updater_Support() {
	return SSH_SFTP_Updater_Support::instance();
}

endif;

$GLOBALS['ssh_sftp_updater_support'] = SSH_SFTP_Updater_Support();
