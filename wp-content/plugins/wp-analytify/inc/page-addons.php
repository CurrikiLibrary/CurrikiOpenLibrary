<?php

if ( ! class_exists( 'WP_Analytify_Addons' ) ) {

	class WP_Analytify_Addons {

		protected $plugins_list;

		/**
		 * Constructor
		 */
		public function __construct() {

			$this->plugins_list = get_plugins();

		}

		/**
		 * Returns a list of addons
		 *
		 * @return array
		 * @since 1.3
		 */
		 public function addons() {

			// For Testing
			// delete_transient( 'analytify_api_addons' );

			 // Get the transient where the addons are stored on-site.
			 $data = get_transient( 'analytify_api_addons' );

			 // If we already have data, return it.
			 if ( ! empty( $data ) )
			 return $data;

			 // Make sure this matches the exact URL from your site.
			 $url = 'https://analytify.io/wp-json/analytify/v1/plugins';

			 $wp_request_headers = array(
				//'Authorization' => 'Basic ' . base64_encode( 'dev:dev' )
			 );

			 // Get data from the remote URL.
			 $response = wp_remote_get( $url, array( 'timeout' => 20, 'headers' => $wp_request_headers ) );

			 if ( ! is_wp_error( $response ) ) {

				 // Decode the data that we got.
				 $data = json_decode( wp_remote_retrieve_body( $response ) );

				 if ( ! empty( $data ) && is_array( $data ) ) {

					 // Store the data for a week.
					 set_transient( 'analytify_api_addons', $data, 7 * DAY_IN_SECONDS );

					 return $data;
				 }
			 }

			return array();

		 }


		/**
		 * Check plugin status
		 *
		 * @return array
		 * @since 1.3
		 */
		public function check_plugin_status( $slug, $extension ) {

			$slug = $slug . '/'. $slug .'.php';
			if ( is_plugin_active( $slug ) ) {

				echo sprintf( esc_html__( '%1$s Already Installed %2$s', 'wp-analytify' ), '<button class="button-primary">', '</button>' );

			} else if ( array_key_exists( $slug, $this->plugins_list ) ) {

				$link = wp_nonce_url( add_query_arg( array( 'action' => 'activate', 'plugin' => $slug ), admin_url( 'plugins.php' ) ),  'activate-plugin_' . $slug ) ;
				echo sprintf( esc_html__( '%1$s Activate Plugin %2$s', 'wp-analytify' ), '<a href="' .  $link . '" class="button-primary">', '</a>' );

			} else if ( is_plugin_inactive( $slug ) ) {

				if ( isset( $extension->status ) &&	$extension->status != '' ) {
					echo sprintf( esc_html__( '%1$s Download %2$s', 'wp-analytify' ), '<a target="_blank" href="' . $extension->url . '" class="button-primary">', '</a>' ); } else {
					echo sprintf( esc_html__( '%1$s Get this add-on %2$s', 'wp-analytify' ), '<a target="_blank" href="' . $extension->url . '" class="button-primary">', '</a>' ); }
			}
		}
	}

}

$obj_wp_analytify_addons = new WP_Analytify_Addons;
$addons = $obj_wp_analytify_addons->addons();
?>

<div class="wrap">

	<h2 class='opt-title'><span id='icon-options-general' class='analytics-options'><img src="<?php echo plugins_url( '../assets/images/wp-analytics-logo.png', __FILE__ );?>" alt=""></span>
	<?php esc_html_e( 'Extend the functionality of Analytify with these awesome Add-ons', 'wp-analytify' ); ?>
	</h2>

	<div class="tabwrapper">
		<?php
		foreach ( $addons as $name => $extension ) :
			?>
			<div class="wp-extension <?php echo $name; ?>">
				<a target="_blank" href="<?php echo $extension->url; ?>">

					<h3 style="background-image: url(<?php echo $extension->media->icon->url ?>);"><?php echo $extension->title; ?></h3>
				</a>

				<p><?php echo wpautop( wp_strip_all_tags( $extension->excerpt ) ) ?></p>
				<p>
					<?php $obj_wp_analytify_addons->check_plugin_status( $extension->slug, $extension ); ?>
				</p>
			</div>
		<?php endforeach; ?>
	</div>

</div>
