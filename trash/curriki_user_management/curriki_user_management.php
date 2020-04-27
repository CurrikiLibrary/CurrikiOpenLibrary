<?php

/**
 * Plugin Name: Curriki User Management 
 * Plugin URI: http://curriki.org
 * Description: Managing Users to Activate and deactivate
 * Version: The plugin's version number. 1.0.0
 * Author: Furqan Aziz
 * Author URI: https://plus.google.com/u/1/117186528930994951156/posts
 */
add_action('admin_menu', 'init_user_management_admin_menus');

function init_user_management_admin_menus() {
    add_menu_page('User Management', 'User Management', 'manage_options', 'curriki_user_management_list', 'curriki_user_management_list');
}

function curriki_user_management_list() {
    
    global $wpdb;
    $view = 'list';
    $data = array();
    global $wpdb;
    if (!empty($_REQUEST['user_submit_id'])) {
        $current_user = wp_get_current_user();
        $wpdb->query("UPDATE users set "
                . "email = '" . $_REQUEST['email'] . "', "
                . "firstname = '" . $_REQUEST['firstname'] . "', "
                . "lastname = '" . $_REQUEST['lastname'] . "', "
                . "active = '" . ($_REQUEST['active']?'T':'F') . "' "
                . ($_REQUEST['active']?'':", inactivedate = '" . date('Y-m-d H:i:s') . "' ")
                . "where userid = '" . $_REQUEST['user_submit_id'] . "'");
    }
    if (!empty($_REQUEST['user_id'])) {
        $view = 'edit';
        $data = $wpdb->get_results("select * from users where userid = '" . $_REQUEST['user_id'] . "' ", OBJECT);
    } else {
        $data = $wpdb->get_results("select * from users order by firstname ", OBJECT);
    }

    curriki_load_views_2($view, $data);
}

function curriki_user_management_edit() {
    curriki_load_views_2('list');
}


function curriki_load_views_2($view = '', $data = array()) {
    $dir = __DIR__;
    @include_once($dir . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $view . '.php');
}
