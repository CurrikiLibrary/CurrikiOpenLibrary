<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      4.0
 *
 * @package    Email_Subscribers
 * @subpackage Email_Subscribers/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Email_Subscribers
 * @subpackage Email_Subscribers/public
 * @author     Your Name <email@example.com>
 */
class Email_Subscribers_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    4.0
	 * @access   private
	 * @var      string $email_subscribers The ID of this plugin.
	 */
	private $email_subscribers;

	/**
	 * The version of this plugin.
	 *
	 * @since    4.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $email_subscribers The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    4.0
	 *
	 */
	public function __construct( $email_subscribers, $version ) {

		$this->email_subscribers = $email_subscribers;
		$this->version           = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    4.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Email_Subscribers_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Email_Subscribers_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->email_subscribers, plugin_dir_url( __FILE__ ) . 'css/email-subscribers-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    4.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Email_Subscribers_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Email_Subscribers_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->email_subscribers, plugin_dir_url( __FILE__ ) . 'js/email-subscribers-public.js', array( 'jquery' ), $this->version, false );

		$es_data = array(

			'messages' => array(
				'es_empty_email_notice'           => __( 'Please enter email address', 'email-subscribers' ),
				'es_rate_limit_notice'            => __( 'You need to wait for sometime before subscribing again', 'email-subscribers' ),
				'es_single_optin_success_message' => __( 'Successfully Subscribed.', 'email-subscribers' ),
				'es_double_optin_success_message' => __( 'Your subscription was successful! Kindly check your mailbox and confirm your subscription. If you don\'t see the email within a few minutes, check the spam/junk folder.', 'email-subscribers' ),
				'es_email_exists_notice'          => __( 'Email Address already exists!', 'email-subscribers' ),
				'es_unexpected_error_notice'      => __( 'Oops.. Unexpected error occurred.', 'email-subscribers' ),
				'es_invalid_email_notice'         => __( 'Invalid email address', 'email-subscribers' ),
				'es_try_later_notice'             => __( 'Please try after some time', 'email-subscribers' )
			),

			'es_ajax_url' => admin_url( 'admin-ajax.php' ),

		);

		wp_localize_script( $this->email_subscribers, 'es_data', $es_data );


	}

	public function es_email_subscribe_init() {

		global $wpdb;

		//initialize 
		new ES_Cron();
		new ES_Handle_Subscription();
		new ES_Shortcode();

		$option = ! empty( $_REQUEST['es'] ) ? $_REQUEST['es'] : '';
		$db_id  = ! empty( $_REQUEST['db'] ) ? $_REQUEST['db'] : '';
		$email  = ! empty( $_REQUEST['email'] ) ? $_REQUEST['email'] : '';
		$guid   = ! empty( $_REQUEST['guid'] ) ? $_REQUEST['guid'] : '';

		$email = str_replace( ' ', '+', $email );

		if ( ! empty( $option ) ) {
			if ( ( 'optin' === $option || 'unsubscribe' === $option ) && ! empty( $db_id ) ) {
				//check if contact exist with id and email
				$check = ES_DB_Contacts::check_contact( $db_id, $email );
				if ( $check ) {
					$ids          = array( $db_id );
					$status       = $subject = $content = '';
					$unsubscribed = 0;
					if ( $option === 'optin' ) {
						$status  = 'subscribed';
						$message = get_option( 'ig_es_subscription_success_message' );
						//$message = get_option( 'ig_es_subscription_error_messsage' );
					} elseif ( $option === 'unsubscribe' ) {
						$status       = 'unsubscribed';
						$unsubscribed = 1;
						$message      = get_option( 'ig_es_unsubscribe_success_message' );
						//$message = get_option( 'ig_es_unsubscribe_error_message' );
					}

					ES_DB_Contacts::edit_subscriber_status( $ids, $status );
					ES_DB_Contacts::edit_subscriber_status_global( $ids, $unsubscribed );

					if ( 'optin' === $option ) {

						$contact = ES_DB_Contacts::get_subsribers_email_name_map( array( $email ) );
						$data    = array(
							'name'       => ! empty( $contact[ $email ] ) ? $contact[ $email ]['name'] : '',
							'first_name' => ! empty( $contact[ $email ] ) ? $contact[ $email ]['first_name'] : '',
							'last_name'  => ! empty( $contact[ $email ] ) ? $contact[ $email ]['last_name'] : '',
							'email'      => $email,
							'db_id'      => $db_id,
							'guid'       => $guid
						);

						$enable_welcome_email = get_option( 'ig_es_enable_welcome_email', 'no' );

						if ( $enable_welcome_email === 'yes' ) {
							$content = ES_Mailer::prepare_welcome_email( $data );
							$subject = ES_Mailer::prepare_welcome_email_subject( $data );
							ES_Mailer::send( $email, $subject, $content );
						}
					}

					do_action( 'es_redirect_to_optin_page', $option );
				} else {
					$message = __( 'Sorry, we couldn\'t find you. Please contact admin.', 'email-subscribers' );
				}

				include 'partials/subscription-successfull.php';

			} elseif ( 'viewstatus' === $option ) {
				if ( ! empty( $guid ) && ! empty( $email ) ) {
					ES_DB_Sending_Queue::update_viewed_status( $guid, $email );
				}
			}

		}

	}

	public function add_contact( $contact_data, $list_id ) {

		$email = $contact_data['email'];

		$default_data = array(
			'status'     => 'verified',
			'hash'       => ES_Common::generate_guid(),
			'created_at' => ig_get_current_date_time(),
			'wp_user_id' => 0
		);

		$contact_data = wp_parse_args( $contact_data, $default_data );

		$contact = ES_DB_Contacts::is_subscriber_exist_in_list( $email, $list_id );
		if ( empty( $contact['contact_id'] ) ) {
			$contact_id = ES_DB_Contacts::add_subscriber( $contact_data );
		} else {
			$contact_id = $contact['contact_id'];
		}

		if ( empty( $contact['list_id'] ) ) {

			$optin_type        = get_option( 'ig_es_optin_type', true );
			$optin_type        = ( $optin_type === 'double_opt_in' ) ? 2 : 1;
			$list_id           = ! empty( $list_id ) ? $list_id : 1;
			$list_contact_data = array(
				'list_id'       => array( $list_id ),
				'contact_id'    => $contact_id,
				'status'        => 'subscribed',
				'subscribed_at' => ig_get_current_date_time(),
				'optin_type'    => $optin_type,
				'subscribed_ip' => null
			);

			ES_DB_Lists_Contacts::delete_list_contacts( $contact_id, array( $list_id ) );

			$result = ES_DB_Lists_Contacts::add_lists_contacts( $list_contact_data );
		}

	}

}
