<?php

namespace Buddyboss\BpGdpr;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

if ( ! class_exists( '\Buddyboss\BpGdpr\Admin' ) ) :

	/**
	 * Admin Class
	 */
	final class Admin {

		/**
		 * Holds information for network activated or not
		 *
		 * @access private
		 * @var bool
		 */
		private $_network_activated = false;

		/**
		 * Holds Slug which will be used in actions and admin page uri.
		 *
		 * @access private
		 * @var string
		 */
		private $_plugin_slug = 'buddyboss-bp-gdpr';

		/**
		 * Holds the default admin menu hook
		 *
		 * @access private
		 * @var string
		 */
		private $_menu_hook = 'admin_menu';

		/**
		 * Parent of admin page.
		 *
		 * @access private
		 * @var string
		 */
		private $_settings_page = 'options-general.php';

		/**
		 * Holds Capability of Admin Page.
		 *
		 * @access private
		 * @var string
		 */
		private $_capability = 'manage_options';

		/**
		 * Holds Action Page
		 *
		 * @access private
		 * @var string
		 */
		private $_form_action = 'options.php';

		/**
		 * Holds the setting page URL
		 *
		 * @access private
		 * @var string
		 */
		private $_plugin_settings_url = '';

		/**
		 * Holds option name where settings will save.
		 *
		 * @access private
		 * @var bool
		 */
		private $_settings_name = false;

		/**
		 * Empty constructor function to ensure a single instance
		 */
		public function __construct() {
			// ... leave empty, see Singleton below
		}

		/**
		 * Returns the Singleton of The Class.
		 *
		 * @return Admin|null
		 */
		public static function instance() {
			static $instance = null;

			if ( null === $instance ) {
				$instance = new Admin();
				$instance->setup();
			}

			return $instance;
		}

		/**
		 * Returns the value by index key from options.
		 *
		 * @param string $key Key Name.
		 *
		 * @return mixed|null|void
		 */
		public function option( $key ) {
			$value = buddyboss_bp_gdpr()->option( $key );
			return $value;
		}

		/**
		 * Setup hooks and networks logic's.
		 */
		public function setup() {
			if ( ( ! is_admin() && ! is_network_admin() ) || ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$this->_plugin_settings_url = admin_url( 'options-general.php?page=' . $this->_plugin_slug );

			$this->_network_activated = buddyboss_bp_gdpr()->is_network_activated();

			// if the plugin is activated network wide in multisite, we need to override few variables.
			if ( $this->_network_activated ) {
				// Main settings page - menu hook.
				$this->_menu_hook = 'network_admin_menu';

				// Main settings page - parent page.
				$this->_settings_page = 'settings.php';

				// Main settings page - Capability.
				$this->_capability = 'manage_network_options';

				// Settins page - form's action attribute.
				$this->_form_action = 'edit.php?action=' . $this->_plugin_slug;

				// Plugin settings page url.
				$this->_plugin_settings_url = network_admin_url( 'settings.php?page=' . $this->_plugin_slug );
			}

			// if the plugin is activated network wide in multisite, we need to process settings form submit ourselves.
			if ( $this->_network_activated ) {
				add_action( 'network_admin_edit_' . $this->_plugin_slug, array( $this, 'save_network_settings_page' ) );
			}

			$this->_settings_name = buddyboss_bp_gdpr()->plugin_slug;

			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action( $this->_menu_hook, array( $this, 'admin_menu' ) );

		}

		/**
		 * Add Admin Menu
		 */
		public function admin_menu() {
			add_submenu_page(
				$this->_settings_page, __( 'BuddyPress GDPR', 'buddyboss-bp-gdpr' ),
				__( 'BuddyPress GDPR', 'buddyboss-bp-gdpr' ), $this->_capability, $this->_plugin_slug,
				array( $this, 'options_page' )
			);
		}

		/**
		 * Function output admin page
		 */
		public function options_page() {
			?>
			<div class="wrap">
				<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
				<?php

				if ( $this->_network_activated && isset( $_GET['updated'] ) ) { // WPCS: CSRF ok. input var ok.
					echo "<div class='updated'><p>" . esc_html__( 'Settings updated.', 'buddyboss-bp-gdpr' ) . '</p></div>';
				}

				?>
				<form method="post" action="<?php echo esc_attr( $this->_form_action ); ?>">
					<?php settings_fields( $this->_settings_name ); ?>
					<?php do_settings_sections( __FILE__ ); ?>
					<p class="submit">
						<input name="bp_msgat_submit" type="submit" class="button-primary"
								value="<?php esc_attr_e( 'Save Changes', 'buddyboss-bp-gdpr' ); ?>"/>
					</p>
				</form>
			</div>
			<?php
		}

		/**
		 * Register option settings.
		 */
		public function admin_init() {
			register_setting( $this->_settings_name, $this->_settings_name, array( $this, 'plugin_options_validate' ) );

			add_settings_section(
				'general_section', __( 'General Settings', 'buddyboss-bp-gdpr' ),
				array( $this, 'general_section' ), __FILE__
			);

			add_settings_field(
				'export_page_title', __( 'Export Page Title', 'buddyboss-bp-gdpr'), array( $this, 'export_page_title' ),
				__FILE__, 'general_section'
			);
			add_settings_field(
				'export_page_content', __( 'Export Page Content', 'buddyboss-bp-gdpr'), array( $this, 'export_page_content' ),
				__FILE__, 'general_section'
			);
			add_settings_field(
				'export_page_btn_txt', __( 'Export Page Button Text', 'buddyboss-bp-gdpr'), array( $this, 'export_page_btn_txt' ),
				__FILE__, 'general_section'
			);
		}

		/**
		 * Declares General Sections.
		 */
		public function general_section() {
			// nothing..
		}

		/**
		 * Validate Options Values before saving.
		 *
		 * @param $input
		 *
		 * @return mixed
		 */
		public function plugin_options_validate( $input ) {
			return $input; // no validations for now
		}

		public function export_page_title() {
			$value = $this->option( 'export_page_title' );

			?>
            <input name="<?php echo $this->_settings_name; ?>[export_page_title]" type="text" value="<?php echo esc_attr($value); ?>" class="regular-text">
            <?php
		}

		public function export_page_btn_txt() {
			$value = $this->option( 'export_page_btn_txt' );

			?>
            <input name="<?php echo $this->_settings_name; ?>[export_page_btn_txt]" type="text" value="<?php echo esc_attr($value); ?>" class="regular-text">
            <?php
		}

		public function export_page_content() {
			$value = $this->option( 'export_page_content' );

			?>
            <textarea name="<?php echo $this->_settings_name; ?>[export_page_content]" rows="10" cols="50" class="large-text"><?php echo esc_textarea($value); ?></textarea>
            <?php
		}

		/**
		 * Save options settings
		 */
		public function save_network_settings_page() {

			if ( ! check_admin_referer( $this->_settings_name . '-options' ) ) {
				return;
			}

			if ( ! current_user_can( $this->_capability ) ) {
				die( 'Access denied!' );
			}

			if ( isset( $_POST['bp_msgat_submit'] ) ) {

				if ( isset( $_POST[ $this->_settings_name ] ) ) {
					$_POST[ $this->_settings_name ] = wp_unslash( $_POST[ $this->_settings_name ] ); // WPCS: sanitization ok.
				}

				$submitted = array_map( 'sanitize_text_field', $_POST[ $this->_settings_name ] ); // WPCS: sanitization ok.

				$submitted = $this->plugin_options_validate( $submitted );

				update_site_option( $this->_settings_name, $submitted );

			}

			// Where are we redirecting to?
			$base_url     = trailingslashit( network_admin_url() ) . 'settings.php';
			$redirect_url = add_query_arg(
				array(
					'page'    => $this->_plugin_slug,
					'updated' => '1',
				), $base_url
			);

			// Redirect
			wp_safe_redirect( $redirect_url );
			exit();

		}


	}

	// End class
endif;
