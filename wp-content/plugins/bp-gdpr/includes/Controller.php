<?php

namespace Buddyboss\BpGdpr;

use Buddyboss\BpGdpr\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

if ( ! class_exists( '\BuddyBoss\BpGdpr\Controller' ) ) :

	final class Controller {

		/**
		 * Default options for the plugin.
		 * After the user saves options the first time they are loaded from the DB.
		 *
		 * @var array
		 */
		protected $_default_options = array(
			'export_page_title' => "Download an export of your data",
			'export_page_content' => "You can export all data you have shared on this platform.
Click the button below to request an export. An email will be sent to you for verification. Once verified, an export file will be generated and emailed to you.",
			"export_page_btn_txt" => "Submit a Data export request"
		);


		/**
		 * This options array is setup during class instantiation, holds
		 * default and saved options for the plugin.
		 *
		 * @var array
		 */
		protected $_options             = array();
		protected $_network_activated = false;
		protected $_actions = false,
			$_template = false;

		/**
		 * Holds the namespace information of current plugin scope.
		 *
		 * @var bool|string
		 */
		protected $namespace   = false;

		/**
		 * Holds Plugin Loader File Path
		 *
		 * @var bool|string
		 */
		protected $plugin_main = false;

		/**
		 * Holds Plugin Directory Path Info
		 *
		 * @var bool|string
		 */
		public $plugin_path    = false;

		/**
		 * Holds Plugin URL location
		 *
		 * @var bool|string
		 */
		public $plugin_url     = false;
		/**
		 * Version of Plugin
		 *
		 * @var string
		 */
		public $version     = '1.0.0';
		/**
		 * Text Domain of Plugin Scope
		 *
		 * @var string
		 */
		public $lang_domain    = 'buddyboss-bp-gdpr';

		/**
		 * Plugin Slug Used in Places like register style or script etc.
		 *
		 * @var string
		 */
		public $plugin_slug    = 'buddyboss-bp-gdpr'; // slug, will be used in option name & other related places

		private function __construct() {
			// ... leave empty, see Singleton below
		}

		/**
		 * Get the instance of this class.
		 *
		 * @param $loader_file <string> of main plugin file used for detecting plugin directory and url.
		 *
		 * @return Controller|null
		 */
		public static function instance( $loader_file ) {
			static $instance = null;

			if ( null === $instance ) {
				$instance = new Controller();
				$instance->_register_autoload();
				$instance->_setup_globals( $loader_file );
				$instance->_setup_actions();
				$instance->_setup_textdomain();
			}

			return $instance;
		}

		function _register_autoload() {

			spl_autoload_register(
				function ( $class_name ) {

					$class_name = str_replace( '\\', '/', $class_name );


					// Identify if it's valid namespace for this loader
					if ( 'Buddyboss/' !== substr( $class_name, 0, 10 ) ) {
						return false;
					}

					$namespace = explode( '/', $class_name );
					// Identify if it's correct namespace to do auto load.
					if ( $namespace[1] !== $this->namespace ) {
						return false;
					}

					$load = $namespace;

					// remove first two
					unset( $load[0], $load[1] );
					$load = implode( '/', $load );

					// Identify ignored namespace
					if ( in_array(
						$load, array(
							'Controller',
							'functions',
						), true
					) ) {
						return false;
					}

					$load = $this->plugin_path . 'includes/' . $load . '.php';

					if ( file_exists( $load ) ) {
						require_once( $load );

						return true;
					}

					return false;

				}
			);

		}

		protected function _setup_globals( $loader_file ) {

			/**
			 * Plugin Namespace
			 */
			$namespace       = str_replace( '\\', '/', __NAMESPACE__ );
			$namespace       = explode( '/', $namespace );
			$namespace       = $namespace[1];
			$this->namespace = $namespace;

			/**
			 * Set Plugin Path and URL
			 */
			$this->plugin_path = trailingslashit( plugin_dir_path( $loader_file ) );

			$plugin_url = plugin_dir_url( $loader_file );
			if ( is_ssl() ) {
				$plugin_url = str_replace( 'http://', 'https://', $plugin_url );
			}
			$this->plugin_url = $plugin_url;

			/**
			 * Set Plugin Main
			 */
			$plugin_main       = explode( '/', $loader_file );
			$plugin_main       = end( $plugin_main );
			$plugin_main       = str_replace( '.php', '', $plugin_main );
			$plugin_main       = $plugin_main . '/' . $plugin_main . '.php';
			$this->plugin_main = $plugin_main;

			/**
			 * Default Configuration Options
			 */

			$default_options = $this->_default_options;

			$saved_options = $this->is_network_activated() ? get_site_option( $this->plugin_slug ) : get_option( $this->plugin_slug );
			$saved_options = maybe_unserialize( $saved_options );

			$this->_options = wp_parse_args( $saved_options, $default_options );

		}

		/**
		 * Check if the plugin is activated network wide(in multisite)
		 *
		 * @return boolean
		 */
		public function is_network_activated() {
			if ( ! $this->_network_activated ) {
				$this->_network_activated = 'no';

				if ( is_multisite() ) {
					if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
						require_once ABSPATH . '/wp-admin/includes/plugin.php';
					}

					if ( is_plugin_active_for_network( $this->plugin_main ) ) {
						$this->_network_activated = 'yes';
					}
				}
			}

			return 'yes' === $this->_network_activated ? true : false;
		}

		protected function _setup_actions() {

			// Admin
			if ( ( is_admin() || is_network_admin() ) && current_user_can( 'manage_options' ) ) {
				$this->load_admin();
			}

			add_action("bp_init", array($this,"load_on_bp_dependency"),1);
		}

		function load_on_bp_dependency(){

			if(bp_is_active("xprofile")) {
				XprofileExport::instance();
			}
			if(bp_is_active("activity")) {
				ActivityExport::instance();
			}
			if(bp_is_active("notifications")) {
				NotificationExport::instance();
			}
			if(bp_is_active("messages")) {
				MessageExport::instance();
			}
			if(bp_is_active("groups")) {
				GroupExport::instance();
				GroupMembershipExport::instance();
			}
			if(bp_is_active( 'friends' ) ) {
				FriendshipExport::instance();
			}
			if(bp_is_active("settings")) {
				SettingsExport::instance();
				UserSettings::instance();
			}
		}

		public function _setup_textdomain() {

			$locale = apply_filters( 'plugin_locale', get_locale(), $this->lang_domain );

			// first try to load from wp-content/languages/plugins/ directory
			load_textdomain( $this->lang_domain, WP_LANG_DIR . '/plugins/' . $this->lang_domain . '-' . $locale . '.mo' );

			// if not found, then load from plugin_folder/languages directory
			load_plugin_textdomain( $this->lang_domain, false, $this->plugin_slug.'/languages' );

		}

		private function load_admin() {
			$this->_admin = Admin::instance();
		}

		public function option( $key ) {

			$key    = strtolower( $key );
			$option = isset( $this->_options[ $key ] ) ? $this->_options[ $key ] : null;
			$option = apply_filters( 'buddyboss_bp_gdpr_' . $key, $option );
			return $option;

		}

		public function admin() {
			return $this->_admin;
		}

	}



endif;
