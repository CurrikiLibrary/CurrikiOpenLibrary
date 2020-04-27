<?php

namespace Buddyboss\BpGdpr;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

if ( ! class_exists( '\BuddyBoss\BpGdpr\UserSettings' ) ) :

	final class UserSettings {

		protected $messages = array();

		/**
		 * Get the instance of this class.
		 *
		 * @return Controller|null
		 */
		public static function instance() {
			static $instance = null;

			if ( null === $instance ) {
				$instance = new UserSettings();
				$instance->hooks();
			}

			return $instance;
		}

		function hooks() {
			// Setup navigation.
			add_action( 'bp_setup_nav', array( $this, 'setup_nav' ), 99 );
			add_action( 'init', array( $this, 'submit' ), 99 );
		}

		function setup_nav() {
			// Determine user to use.
			if ( bp_displayed_user_domain() ) {
				$user_domain = bp_displayed_user_domain();
			} elseif ( bp_loggedin_user_domain() ) {
				$user_domain = bp_loggedin_user_domain();
			} else {
				return;
			}

			$slug          = bp_get_settings_slug();
			$settings_link = trailingslashit( $user_domain . $slug );

			$access        = bp_core_can_edit_settings();

			$sub_nav[] = array(
				'name'            => __( 'Export Data', 'buddyboss-bp-gdpr' ),
				'slug'            => 'export',
				'parent_url'      => $settings_link,
				'parent_slug'     => $slug,
				'screen_function' => array($this,"export_data_screen"),
				'position'        => 99,
				'user_has_access' => $access
			);

			foreach($sub_nav as $nav) {
				bp_core_new_subnav_item( $nav, 'members' );
			}

		}

		function export_data_screen() {
			add_action( 'bp_template_title', function(){
				return __("Export Data", 'buddyboss-bp-gdpr');
			} );
			add_action( 'bp_template_content', array($this,"export_data_page_render") );
			bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
		}

		function submit() {

			if(isset($_POST["buddyboss_data_export_request"])) {

				if(! wp_verify_nonce( $_POST['buddyboss_data_export_request'], 'buddyboss_data_export_request' ) ) {
					wp_die(__("Sorry something went wrong please try again.",'buddyboss-bp-gdpr'));
				}

				if(bp_core_can_edit_settings()) {

					$user_id = bp_loggedin_user_id();

					$user = get_userdata($user_id);
					$request_id = wp_create_user_request( $user->data->user_email, "export_personal_data" );

					if ( is_wp_error( $request_id ) ) {
						$this->messages["error"] = $request_id->get_error_message();
						return false;
					} elseif ( ! $request_id ) {
						$this->messages["error"] = __( 'Unable to initiate confirmation request.' );
						return false;
					}

					wp_send_user_request( $request_id );

					$this->messages["success"] = __( 'Confirmation request initiated successfully.' );


				}

			}

		}

		function export_data_page_render() {

			?>

			<div class="buddyboss-data-export">

				<?php

				foreach($this->messages as $errtype => $err) {
					?>
					<div id="message" class="bp-template-notice <?php echo esc_attr($errtype); ?>">
						<p><?php echo esc_html($err); ?></p>
					</div>
					<?php
				}

				?>

				<h3><?php echo esc_html(buddyboss_bp_gdpr()->option( 'export_page_title' )); ?></h3>
				<p><?php echo esc_html(buddyboss_bp_gdpr()->option( 'export_page_content' )); ?></p>

				<form method="post">
					<?php wp_nonce_field( 'buddyboss_data_export_request', 'buddyboss_data_export_request' ); ?>
					<div class="submit">
						<input id="submit" type="submit" name="request-submit" value="<?php echo esc_attr(buddyboss_bp_gdpr()->option( 'export_page_btn_txt' )); ?>" class="auto">
					</div>
				</form>

			</div>

			<?php

		}


	}

endif;
