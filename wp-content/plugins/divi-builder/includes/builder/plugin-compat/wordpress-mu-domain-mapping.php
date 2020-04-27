<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Plugin compatibility for WordPress MU Domain Mapping
 */
class ET_Builder_Plugin_Compat_Mu_Domain_Mapping extends ET_Builder_Plugin_Compat_Base {
	/**
	 * Constructor
	 */
	function __construct() {
		$this->plugin_id = 'wordpress-mu-domain-mapping/domain_mapping.php';

		$this->init_hooks();
	}

	/**
	 * Hook methods to WordPress
	 *
	 * @return void
	 */
	function init_hooks() {
		// Bail if there's no version found
		if ( ! $this->get_plugin_version() ) {
			return;
		}

		$bfb_enabled = et_()->array_get( $_GET, 'et_bfb' );
		if ( $bfb_enabled ) {
			remove_action( 'template_redirect', 'redirect_to_mapped_domain' );
		}
	}
}

new ET_Builder_Plugin_Compat_Mu_Domain_Mapping();
