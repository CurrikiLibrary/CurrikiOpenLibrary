<?php

add_filter( 'ig_es_registered_settings', 'ig_es_add_upsale', 10, 2 );

// Add additional tab "Comments" in Audience > Sync
add_filter( 'ig_es_sync_users_tabs', 'ig_es_add_sync_users_tabs', 11, 1 );

add_action( 'ig_es_sync_users_tabs_comments', 'ig_es_add_comments_tab_settings' );
add_action( 'ig_es_sync_users_tabs_woocommerce', 'ig_es_add_woocommerce_tab_settings' );
add_action( 'ig_es_sync_users_tabs_cf7', 'ig_es_add_cf7_tab_settings' );

function ig_es_add_upsale( $fields ) {
	global $ig_es_tracker;

	$es_premium  = 'email-subscribers-premium/email-subscribers-premium.php';
	$all_plugins = $ig_es_tracker::get_plugins();

	if ( ! in_array( $es_premium, $all_plugins ) ) {

		// Security settings
		$field_security['es_upsale_security'] = array(
			'id'   => 'ig_es_blocked_domains',
			'type' => 'html',
			'name' => '',
			'html' => '<div class="es-upsale-image" style=""><a target="_blank" href="https://www.icegram.com/email-subscribers-starter/?utm_source=in_app&utm_medium=es_security_settings&utm_campaign=es_upsale#blockspam"><img src="' . EMAIL_SUBSCRIBERS_URL . '/admin/images/es-captcha-2.png' . '"/></a></div>'
		);
		$fields['security_settings']          = array_merge( $fields['security_settings'], $field_security );

		// SMTP settings
		$field_smtp['es_upsale_smtp'] = array(
			'id'   => 'ig_es_blocked_domains',
			'type' => 'html',
			'name' => '<div class="es-smtp-label" style=""><a target="_blank" href="https://www.icegram.com/email-subscribers-starter/?utm_source=in_app&utm_medium=es_smtp&utm_campaign=es_upsale#delivery"><img src="' . EMAIL_SUBSCRIBERS_URL . '/admin/images/es-smtp-label.png' . '"/></a></div>',
			'html' => '<div class="es-upsale-image es-smtp-image" style=""><a target="_blank" href="https://www.icegram.com/email-subscribers-starter/?utm_source=in_app&utm_medium=es_smtp&utm_campaign=es_upsale"><img src="' . EMAIL_SUBSCRIBERS_URL . '/admin/images/es-smtp.png' . '"/></a></div>'
		);
		$fields['email_sending']      = array_merge( $fields['email_sending'], $field_smtp );

	}

	return $fields;
}

function ig_es_add_sync_users_tabs( $tabs ) {
	global $ig_es_tracker;

	$es_premium  = 'email-subscribers-premium/email-subscribers-premium.php';
	$all_plugins = $ig_es_tracker::get_plugins();

	// Show integrations only if ES Premium is not installed.
	if ( ! in_array( $es_premium, $all_plugins ) ) {
		$tabs['comments'] = array(
			'name'             => __( 'Comments', 'email-subscribers' ),
			'indicator_option' => 'ig_es_show_sync_comment_users_indicator',
			'indicator_label'  => 'Starter'
		);

		$woocommerce_plugin = 'woocommerce/woocommerce.php';

		// Is WooCommmerce active? Show WooCommerce integration
		$active_plugins = $ig_es_tracker::get_active_plugins();
		if ( in_array( $woocommerce_plugin, $active_plugins ) ) {
			$tabs['woocommerce'] = array(
				'name'             => __( 'WooCommerce', 'email-subscribers' ),
				'indicator_option' => 'ig_es_show_sync_woocommerce_users_indicator',
				'indicator_label'  => 'Starter'
			);
		}

		// Is Contact Form 7 active? Show CF7 integration.
		$contact_form_7 = 'contact-form-7/wp-contact-form-7.php';
		if ( in_array( $contact_form_7, $active_plugins ) ) {
			$tabs['cf7'] = array(
				'name'             => __( 'Contact Form 7', 'email-subscribers' ),
				'indicator_option' => 'ig_es_show_sync_cf7_users_indicator',
				'indicator_label'  => 'Starter'
			);
		}
	}

	return $tabs;
}

function ig_es_add_comments_tab_settings( $tab_options ) {

	// If you want to hide once shown. Set it to 'no'
	// If you don't want to hide. do not use following code or set value as 'yes'
	/*
	if ( ! empty( $tab_options['indicator_option'] ) ) {
		update_option( $tab_options['indicator_option'], 'yes' ); // yes/no
	}
	*/

	$info = array(
		'type' => 'info'
	);

	ob_start();
	?>
    <div class="">
        <h2><?php _e('Sync Comment Users', 'email-subscribers') ?></h2>
        <p><?php _e('Quickly add to your mailing list when someone post a comment on your website.', 'email-subscribers') ?></p>
        <h2><?php _e('How to setup?', 'email-subscribers') ?></h2>
        <p><?php _e('Once you upgrade to ', 'email-subscribers') ?><a href="https://www.icegram.com/email-subscribers-starter/?utm_source=in_app&utm_medium=comment_sync&utm_campaign=es_upsale#sync_comment_users"><?php _e('Email Subscribers Starter', 'email-subscribers') ?></a>, <?php _e('you will have settings panel where you need to enable Comment user sync and select the list in which you want to add people whenever someone post a
            comment.', 'email-subscribers') ?></p>
        <hr>
        <p class="help"><?php _e('Checkout ', 'email-subscribers') ?><a href="https://www.icegram.com/email-subscribers-starter/?utm_source=in_app&utm_medium=comment_sync&utm_campaign=es_upsale#sync_comment_users"><?php _e('Email Subscribers Starter', 'email-subscribers') ?></a> <?php _e('now', 'email-subscribers') ?></p>
    </div>
	<?php

	$content = ob_get_clean();

	?>
    <a target="_blank" href="https://www.icegram.com/email-subscribers-starter/?utm_source=in_app&utm_medium=es_comment_upsale&utm_campaign=es_upsale#sync_comment_users">
        <img src=" <?php echo EMAIL_SUBSCRIBERS_URL . '/admin/images/es-comments.png' ?> "/>
    </a>
	<?php
	ES_Common::prepare_information_box( $info, $content );
}

function ig_es_add_woocommerce_tab_settings( $tab_options ) {

	$info = array(
		'type' => 'info',
	);

	ob_start();
	?>
    <div class="">
        <h2><?php _e('Sync WooCommerce Customers', 'email-subscribers') ?></h2>
        <p><?php _e('Are you using WooCommerce for your online business? You can use this integration to add to a specific list whenever someone make a purchase from you', 'email-subscribers') ?></p>
        <h2><?php _e('How to setup?', 'email-subscribers') ?></h2>
        <p><?php _e('Once you upgrade to ', 'email-subscribers') ?><a href="https://www.icegram.com/email-subscribers-starter/?utm_source=in_app&utm_medium=woocommerce_sync&utm_campaign=es_upsale#sync_woocommerce_customers"><?php _e('Email Subscribers Starter', 'email-subscribers') ?></a>, <?php _e('you will have settings panel where you need to enable WooCommerce sync and select the list in which you want to add people whenever they
            purchase something
            from you.', 'email-subscribers') ?></p>
        <hr>
        <p class="help"><?php _e('Checkout ', 'email-subscribers') ?><a href="https://www.icegram.com/email-subscribers-starter/?utm_source=in_app&utm_medium=woocommerce_sync&utm_campaign=es_upsale#sync_woocommerce_customers">Email Subscribers Starter</a> Now</p>
    </div>
	<?php $content = ob_get_clean(); ?>

    <a target="_blank" href="https://www.icegram.com/email-subscribers-starter/?utm_source=in_app&utm_medium=woocommerce_sync&utm_campaign=es_upsale#sync_woocommerce_customers">
        <img src=" <?php echo EMAIL_SUBSCRIBERS_URL . '/admin/images/woocommerce-sync.png' ?> "/>
    </a>

	<?php

	ES_Common::prepare_information_box( $info, $content );

	?>

	<?php
}

function ig_es_add_cf7_tab_settings( $tab_options ) {

	$info = array(
		'type' => 'info',
	);

	ob_start();
	?>
    <div class="">
        <h2><?php _e('Sync Contact Form 7 users', 'email-subscribers') ?></h2>
        <p><?php _e('Are you using Contact Form 7 for your list building? You can use this integration to add to a specific list whenever new subscribers added from Contact Form 7', 'email-subscribers') ?></p>
        <h2><?php _e('How to setup?', 'email-subscribers') ?></h2>
        <p><?php _e('Once you upgrade to ', 'email-subscribers') ?><a href="https://www.icegram.com/email-subscribers-starter/?utm_source=in_app&utm_medium=cf7_sync&utm_campaign=es_upsale#sync_cf7_subscribers"><?php _e('Email Subscribers Starter', 'email-subscribers') ?></a>, <?php _e('you will have settings panel where you need to enable Contact form 7 sync and select the list in which you want to add people whenever they fill any of the Contact Form.', 'email-subscribers') ?></p>
        <hr>
        <p class="help"><?php _e('Checkout ', 'email-subscribers') ?><a href="https://www.icegram.com/email-subscribers-starter/?utm_source=in_app&utm_medium=cf7_sync&utm_campaign=es_upsale#sync_cf7_subscribers">Email Subscribers Starter</a> Now</p>
    </div>
	<?php $content = ob_get_clean(); ?>

    <a target="_blank" href="https://www.icegram.com/email-subscribers-starter/?utm_source=in_app&utm_medium=cf7_sync&utm_campaign=es_upsale#sync_cf7_subscribers">
        <img src=" <?php echo EMAIL_SUBSCRIBERS_URL . '/admin/images/cf7-sync.png' ?> "/>
    </a>

	<?php

	ES_Common::prepare_information_box( $info, $content );

	?>

	<?php
}



