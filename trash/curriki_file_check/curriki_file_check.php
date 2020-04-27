<?php

/**
 * Plugin Name: Curriki File Check
 * Plugin URI: http://curriki.org
 * Description: Checking all resources to approve and disapprove
 * Version: The plugin's version number. 1.0.0
 * Author: Furqan Aziz
 * Author URI: https://plus.google.com/u/1/117186528930994951156/posts
 */



add_action('admin_menu', 'init_admin_menus');

function init_admin_menus() {

add_menu_page('File Check', 'File Check', 'file_check', 'curriki_file_check_list', 'curriki_file_check_list');
}

function curriki_file_check_list() {
    
    global $wpdb;
    $view = 'list';
    $data = array();
    global $wpdb;
    if (!empty($_REQUEST['file_submit_id'])) {
        $current_user = wp_get_current_user();

        $wpdb->query("UPDATE resources set "
                . "resourcechecked = '" . $_REQUEST['status'] . "', "
                . "resourcecheckdate = '" . date('Y-m-d H:i:s') . "', "
                . "resourcecheckid = " . $current_user->ID . ", "
                . "resourcechecknote = '" . $_REQUEST['notes'] . "' "
                . "where resourceid = '" . $_REQUEST['file_submit_id'] . "'");
    }
    if (!empty($_REQUEST['file_id'])) {
        $view = 'edit';
        $data = $wpdb->get_results("SELECT r.resourceid, r.title, r.description, r.resourcechecked, r.createdate, r.resourcecheckrequestnote, r.contributorid, u.display_name
                                    FROM resources r
                                    LEFT JOIN cur_users u ON u.ID = r.contributorid
                                    WHERE resourcechecked IN ('F',  'D')
                                    AND resourceid = '" . $_REQUEST['file_id'] . "'", OBJECT);
    } else {
        $data = $wpdb->get_results("SELECT r.resourceid, r.title, r.resourcechecked, r.createdate, r.resourcecheckrequestnote, r.contributorid, u.display_name
                                    FROM resources r
                                    LEFT JOIN cur_users u ON u.ID = r.contributorid
                                    WHERE resourcechecked IN ('F',  'D')
                                    ORDER BY resourcechecked, title ASC ", OBJECT);
    }
    curriki_load_views($view, $data);
}

function curriki_file_check_edit() {

    curriki_load_views('list');
}

function curriki_load_views($view = '', $data = array()) {
    $dir = __DIR__;
    @include_once($dir . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $view . '.php');
}

function debug($data = array(), $exit = false) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    if ($exit)
        exit;
}
