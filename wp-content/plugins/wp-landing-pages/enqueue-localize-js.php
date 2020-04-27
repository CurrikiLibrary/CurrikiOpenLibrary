<?php

// embed the javascript file that makes the AJAX request
wp_enqueue_script('my-ajax-request', plugin_dir_url(__FILE__) . 'js/ajax.js', array('jquery'));

// declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
wp_localize_script('my-ajax-request', 'MyAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
