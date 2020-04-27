<?php
/*
  Plugin Name: WP Landing Pages
  Plugin URI: http://www.intensewp.com/wp-landing-pages/
  Description: Create Unlimited Beautiful Landing Pages in WordPress. Opt-in Forms, Customize Text, Upload photos, Embed videos.
  Author: 99robots
  Version: 3.7
  Author URI: https://99robots.com/
  Text Domain: wp-landing-pages
  Domain Path: /languages
 */
$llp_PluginName = 'WPLP';
$llp_PluginPrefix = 'llp_';
define($llp_PluginPrefix . 'Currency', '$');
$llp_upload_dir = wp_upload_dir();
define('llp_PATH', plugin_dir_path(__FILE__));
define('llp_URL', plugins_url('/wp-landing-pages'));

function wplp_update_custom_meta($postID, $newvalue, $field_name) {
    global $PluginDirName, $PluginName, $FullPluginDirURL;
// To create new meta
    if (!get_post_meta($postID, $field_name)) {
        add_post_meta($postID, $field_name, $newvalue);
    } else {
// or to update existing meta
        update_post_meta($postID, $field_name, $newvalue);
    }
}

function llp_Activation_register() {
    global $wpdb;

    // flush permalinks
    wplp_reg_posttype_landing_page();
    flush_rewrite_rules();
}

register_activation_hook(__FILE__, 'wplp_activate_plugin');

function wplp_activate_plugin($networkwide) {
    global $wpdb;

    if (function_exists('is_multisite') && is_multisite()) {
        // check if it is a network activation - if so, run the activation function for each blog id
        if ($networkwide) {
            $old_blog = $wpdb->blogid;
            // Get all blog ids
            $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach ($blogids as $blog_id) {
                switch_to_blog($blog_id);
                llp_Activation_register();
            }
            switch_to_blog($old_blog);
            return;
        }
    }
    llp_Activation_register();
}

add_action('wpmu_new_blog', 'wplp_new_blog', 10, 6);

add_action('init', 'wplp_load_textdomain');

function wplp_load_textdomain() {
    load_plugin_textdomain('wp-landing-pages', false, dirname(plugin_basename(__FILE__)) . '/languages');
}

// remove the templates table. as its not neded anymore since version 4.1
function llp_remove_tbl__error() {
    global $wpdb;
    $table_name = $wpdb->prefix . "llp_templates";
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
        ?>
        <div class="notice notice-error is-dismissible">
            <p><?php _e('The WP Landing Pages Pro plugin installed a templates table in the previous versions. That table is no longer required. Do you want me to go ahead and remove it?', 'wp-landing-pages'); ?>
                <a href="<?php echo admin_url('admin.php?page=llp_templates&tblremove=1'); ?>"><?php _e('Yes', 'wp-landing-pages'); ?></a></p>
        </div>
        <?php
    }
}

#add_action('admin_notices', 'llp_remove_tbl__error');

function llp_remove_tbl__success() {
    global $wpdb;
    ?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e('The table has been removed successfully', 'wp-landing-pages'); ?></p>
    </div>
    <?php
}

function llp_remove_tbl_process() {
    global $wpdb;
    if (isset($_REQUEST['tblremove']) && 1 == $_REQUEST['tblremove']) {
        $table_name = $wpdb->prefix . "llp_templates";
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
            $wpdb->get_var("DROP TABLE " . $table_name);
            add_action('admin_notices', 'llp_remove_tbl__success');
        }
    }
}

// Run plugin activation on new blog creation in wp multisite.
function wplp_new_blog($blog_id, $user_id, $domain, $path, $site_id, $meta) {
    global $wpdb;

    if (is_plugin_active_for_network('wp-landing-pages/wp_landing_pages.php')) {
        $old_blog = $wpdb->blogid;
        switch_to_blog($blog_id);
        llp_Activation_register();
        switch_to_blog($old_blog);
    }
}

function wplp_AdminMenu() {

    global $llp_PluginPrefix, $llp_PluginName;
    $llp_home = add_menu_page($llp_PluginName . __(' Configuration', 'wp-landing-pages'), $llp_PluginName, 'manage_options', $llp_PluginName . __('Home', 'wp-landing-pages'), 'wplp_home');
    $llp_templates = add_submenu_page($llp_PluginName . "Home", __('Templates', 'wp-landing-pages'), __('Create Landing Page', 'wp-landing-pages'), 'manage_options', $llp_PluginPrefix . "templates", 'wplp_get_templates');
    $llp_templates = add_submenu_page($llp_PluginName . "Home", __('Premium Templates', 'wp-landing-pages'), __('Premium Designs', 'wp-landing-pages'), 'manage_options', $llp_PluginPrefix . "templates-pro", 'wplp_get_templates_pro');
}

add_action('admin_menu', 'wplp_AdminMenu');
add_filter('template_redirect', 'wplp_check_for_template');

function wplp_check_for_template() {
    global $wp_query, $post, $wpdb;
    @$do = $_REQUEST['do'];
    @$llp_tpl_id = sanitize_text_field($_REQUEST['tpl_id']);
    $chk_llp_temp = get_post_meta($post->ID, 'llp_tpl_id', true);
    if (is_user_logged_in()) {
        if ($do == 'create') {
            include llp_PATH . 'llp_tpl/' . $llp_tpl_id . '/index.php';
            exit();
        } elseif ($do == 'edit') {
            $llp_tpl_id = get_post_meta($post->ID, 'llp_tpl_id', true);
            include llp_PATH . 'llp_tpl/' . $llp_tpl_id . '/index.php';
            exit();
        } elseif ($chk_llp_temp != "") {
            $llp_tpl_id = get_post_meta($post->ID, 'llp_tpl_id', true);
            include llp_PATH . '/llp_tpl/' . $llp_tpl_id . '/index.php';
            exit();
        }
    } elseif ($chk_llp_temp != "") {
        $llp_tpl_id = get_post_meta($post->ID, 'llp_tpl_id', true);
        include llp_PATH . '/llp_tpl/' . $llp_tpl_id . '/index.php';
        exit();
    }
}

function wplp_home() {
    global $wpdb;
    include 'llp_home.php';
}

function wplp_get_templates() {
    @$do = $_REQUEST['do'];
    include 'llp_get_templates.php';
}

function wplp_load_scripts($hook) {
    wp_enqueue_script('llp_ajax', plugin_dir_url(__FILE__) . 'js/llp_ajax.js', array('jquery'));
    wp_localize_script('llp_ajax', 'llp_vars', array(
        'llp_nonce' => wp_create_nonce('llp_nonce')
    ));
    wp_enqueue_script('wplp_js_1', plugins_url('llp_tpl/js/source/llp_box.js', __FILE__), array('jquery'), '20170506', true);
}

add_action('admin_enqueue_scripts', 'wplp_load_scripts');
add_action('wp_enqueue_scripts', 'wplp_load_scripts');

function wplp_ajax_process() {
    if (!isset($_REQUEST['llp_nonce']) || !wp_verify_nonce(($_REQUEST['llp_nonce']), 'llp_nonce')) {
        die('Permission denied...');
    }
    $do = isset($_REQUEST['do']) ? sanitize_text_field($_REQUEST['do']) : null;
    $llp_create_page = $_POST['llp_create_page'];
    if ($do == 'llp_create_page') {
        $llp_tpl_id = $_POST['llp_tpl_id'];
        $llp_post = array(
            'post_title' => $llp_create_page,
            'post_type' => 'landing-page',
            'post_content' => 'This page is created by WP Landing Pages.',
            'post_status' => 'publish'
        );
// Insert the post into the database
        $insert_post = wp_insert_post($llp_post);
        update_post_meta($insert_post, 'llp_tpl_id', $llp_tpl_id);
        echo $insert_post;
    }
    if ($do == 'llp_save_meta') {
        $llp_id = $_POST['llp_id'];
        $llp_metafield = $_POST['llp_metafield'];
        $llp_metavalue = $_POST['llp_metavalue'];
        $llp_image_link = $_POST['llp_image_link'];
        $llp_link_a = $_POST['llp_link_a'];
        $llp_link_a_open = $_POST['llp_link_a_open'];
        $llp_data_type = $_POST['llp_data_type'];
        if ($llp_data_type == 'form') {
            update_post_meta($llp_id, $llp_metafield, $_POST);
        } else {
            update_post_meta($llp_id, $llp_metafield, $llp_metavalue);
        }
        if ($llp_image_link != "") {
            if ($llp_image_link == "null") {
                update_post_meta($llp_id, $llp_metafield . '_image_link', '');
            } else {
                update_post_meta($llp_id, $llp_metafield . '_image_link', $llp_image_link);
            }
        }
        if ($llp_link_a != "") {
            if ($llp_link_a == "null") {
                update_post_meta($llp_id, $llp_metafield . '_link_a', '');
            } else {
                update_post_meta($llp_id, $llp_metafield . '_link_a', $llp_link_a);
            }
        }
        if ($llp_link_a_open != "") {
            update_post_meta($llp_id, $llp_metafield . '_link_a_open', $llp_link_a_open);
        }
        if ($llp_data_type == 'form') {
            echo get_permalink($llp_id) . '?do=edit';
        } else {
            echo 1;
        }
    }
    if ($do == 'llp_upload_image') {
        $result = array(
            'pass' => '',
            'error' => ''
        );
// Set the allowed file extensions
        $fileTypes = array('png', 'jpg', 'jpeg', 'gif', 'bmp'); // Allowed file extensions
        if (!empty($_FILES)) {
            $fileParts = pathinfo($_FILES['upload_image']['name']);
// Validate the filetype
            if (in_array(strtolower($fileParts['extension']), $fileTypes)) {
// Save the file
                $uploadedfile = $_FILES['upload_image'];
                $upload_overrides = array('test_form' => false);
                $movefile = wp_handle_upload($uploadedfile, $upload_overrides);
                if ($movefile) {
                    $result['pass'] = $movefile['url'];
                } else {
                    $result['error'] = "Some error occured please try again.";
                }
            } else {
// The file type wasn't allowed
                $result['error'] = 'Invalid image type.';
            }
        }
        echo json_encode($result);
    }
    die();
}

add_action('wp_ajax_update_llp_options', 'wplp_ajax_process');
add_action('media_buttons_context', 'wplp_editor_btn');

function wplp_editor_btn($context) {
    global $post, $wp_rewrite;
    $chk_llp_temp = get_post_meta($post->ID, 'llp_tpl_id', true);
    if ($chk_llp_temp != "") {
        if ($wp_rewrite->permalink_structure == '') {
            $output = '<a target="_blank" title="Edit Template" class="button" href="' . get_permalink($post->ID) . '&do=edit">' . __('Edit in Design View', 'wp-landing-pages') . '</a>';
        } else {
            $output = '<a target="_blank" title="Edit Template" class="button" href="' . get_permalink($post->ID) . '?do=edit">' . __('Edit in Design View', 'wp-landing-pages') . '</a>';
        }
    }
    return $output;
}

add_action('init', 'wplp_reg_posttype_landing_page');

function wplp_reg_posttype_landing_page() {
    $labels = array(
        'name' => __('Landing Pages', 'wp-landing-pages'),
        'singular_name' => 'Landing Page', __('Landing Pages', 'wp-landing-pages'),
        //'add_new' => __('Add New', 'wp-landing-pages'),
        //'add_new_item' => __('Add New Landing Page', 'wp-landing-pages'),
        'edit_item' => __('Edit Landing Page', 'wp-landing-pages'),
        'new_item' => __('New Landing Page', 'wp-landing-pages'),
        'all_items' => __('All Landing Pages', 'wp-landing-pages'),
        'view_item' => __('View Landing Page', 'wp-multi'),
        'search_items' => __('Search Landing Pages', 'wp-landing-pages'),
        'not_found' => __('No landing pages found', 'wp-landing-pages'),
        'not_found_in_trash' => __('No landing pages found in Trash', 'wp-landing-pages'),
        'menu_name' => __('Landing Pages', 'wp-landing-pages')
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'landing-page'),
        'capability_type' => 'post',
        'capabilities' => array(
            'create_posts' => 'do_not_allow', // false < WP 4.5, credit @Ewout
          ),
          'map_meta_cap' => true, // Set to `false`, if users are not allowed to edit/delete existing posts
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
        'rewrite' => array('slug' => '', 'with_front' => TRUE),
    );
    register_post_type('landing-page', $args);
}

/**
 * Remove the slug from published post permalinks. Only affect our custom post type, though.
 */
function wplp_remove_cpt_slug($post_link, $post, $leavename) {

    if ('landing-page' != $post->post_type || 'publish' != $post->post_status) {
        return $post_link;
    }

    $post_link = str_replace('/' . $post->post_type . '/', '/', $post_link);

    return $post_link;
}

#add_filter('post_type_link', 'wplp_remove_cpt_slug', 10, 3);

/**
 * Have WordPress match postname to any of our public post types (post, page, race)
 * All of our public post types can have /post-name/ as the slug, so they better be unique across all posts
 * By default, core only accounts for posts and pages where the slug is /post-name/
 */
function wplp_parse_request_trick($query) {

// Only noop the main query
    if (!$query->is_main_query())
        return;

// Only noop our very specific rewrite rule match
    if (2 != count($query->query) || !isset($query->query['page'])) {
        return;
    }

// 'name' will be set if post permalinks are just post_name, otherwise the page rule will match
    if (!empty($query->query['name'])) {
        $query->set('post_type', array('post', 'page', 'landing-page'));
    }
}

#add_action('pre_get_posts', 'wplp_parse_request_trick');

function getPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if ($count == '') {
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0 View";
    }
    return $count . ' Views';
}

function wplp_setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if ($count == '') {
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    } else {
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

// Remove issues with prefetching adding extra views
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

// Manage custom column in landing page admin screen

function wplp_set_custom_edit_lp_columns($columns) {
    return array(
        'cb' => '<input type="checkbox" />',
        'screen_shot' => __('Screenshot'),
        'title' => __('Title'),
        'date' => __('Date'),
        'wplp_page_views' => __('Total Visits')
    );
}

add_filter('manage_book_posts_columns', 'set_book_columns');

add_filter('manage_landing-page_posts_columns', 'wplp_set_custom_edit_lp_columns'); //landing-page
add_action('manage_landing-page_posts_custom_column', 'wplp_custom_lp_column', 10, 2);

function wplp_set_custom_edit_lp_columns44($columns) {
    $columns['wplp_page_views'] = __('Total Visits', 'wp-landing-pages');
    $columns['screen_shot'] = __('Screenshot', 'wp-landing-pages');

    return $columns;
}

function wplp_custom_lp_column($column, $post_id) {
    global $wpdb;
    switch ($column) {

        case 'wplp_page_views' :
            $lp_count = get_post_meta($post_id, 'post_views_count', true);
            if (is_string($lp_count))
                echo $lp_count;
            else
                _e('Unable to get visits', 'wp-landing-pages');
            break;

        case 'screen_shot' :
            $id_llp_temp = get_post_meta($post_id, 'llp_tpl_id', true);
            $img_llp_tpl = llp_get_lp_data(llp_PATH . '/llp_tpl/' . $id_llp_temp . '/index.php');
            ?>
            <img src="<?php echo llp_URL . '/llp_tpl/' . $id_llp_temp . '/images/' . $img_llp_tpl['Preview Image']; ?>" width="150" /> <?php
            break;
    }
}

function wplp_hide_addnew() {
// Hide sidebar link
    global

    $submenu;
    unset($submenu['edit.php?post_type=landing-page'][10]);

// Hide link on listing page
    if (isset($_GET['post_type']) && $_GET['post_type'] == 'landing-page') {
        echo '<style type = "text/css">
.wrap h1 a { display:none;
}
</style>';
    }
}

add_action('admin_menu', 'wplp_hide_addnew');

/*
 * Function creates post duplicate as a draft and redirects then to the edit post screen
 */

function wplp_duplicate_landing_page() {
    global



    $wpdb;
    if (!( isset($_GET['post']) || isset($_POST['post']) || ( isset($_REQUEST['action']) && 'wplp_duplicate_landing_page' == $_REQUEST['action'] ) )) {
        wp_die('No landing page to duplicate has been supplied!');
    }

    /*
     * get the original post id
     */
    $post_id = (isset($_GET['post']) ? $_GET['post'] : $_POST['post']);
    /*
     * and all the original post data then
     */
    $post = get_post($post_id);

    /*
     * if you don't want current user to be the new post author,
     * then change next couple of lines to this: $new_post_author = $post->post_author;
     */
    $current_user = wp_get_current_user();
    $new_post_author = $current_user->ID;

    /*
     * if post data exists, create the post duplicate
     */
    if (isset($post) && $post != null) {

        /*
         * new post data array
         */
        $args = array(
            'comment_status' => $post->comment_status,
            'ping_status' => $post->ping_status,
            'post_author' => $new_post_author,
            'post_content' => $post->post_content,
            'post_excerpt' => $post->post_excerpt,
            'post_name' => $post->post_name,
            'post_parent' => $post->post_parent,
            'post_password' => $post->post_password,
            'post_status' => 'publish',
            'post_title' => $post->post_title,
            'post_type' => $post->post_type,
            'to_ping' => $post->to_ping,
            'menu_order' => $post->menu_order
        );

        /*
         * insert the post by wp_insert_post() function
         */
        $new_post_id = wp_insert_post($args);

        /*
         * get all current post terms ad set them to the new post draft
         */
        $taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
        foreach ($taxonomies as $taxonomy) {
            $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
            wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
        }

        /*
         * duplicate all post meta just in two SQL queries
         */
        $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id = $post_id");
        if (count($post_meta_infos) != 0) {
            $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
            foreach ($post_meta_infos as $meta_info) {
                $meta_key = $meta_info->meta_key;
                $meta_value = addslashes($meta_info->meta_value);
                $sql_query_sel[] = "SELECT $new_post_id, '$meta_key', '$meta_value'";
            }
            $sql_query.= implode(" UNION ALL ", $sql_query_sel);
            $wpdb->query($sql_query);
        }


        /*
         * finally, redirect to the edit post screen for the new draft
         */
        wp_redirect(admin_url('post.php?action=edit&post=' . $new_post_id));
        exit;
    } else {
        wp_die('Post creation failed, could not find original post: ' . $post_id);
    }
}

add_action('admin_action_rd_duplicate_post_as_draft', 'wplp_duplicate_landing_page');

/*
 * Add the duplicate link to action list for post_row_actions
 */

function wplp_duplicate_landing_page_link($actions, $post) {
    if (current_user_can('edit_posts')) {
        $actions['duplicate'] = '<a href="admin.php?action=rd_duplicate_post_as_draft&post=' . $post->ID . '" title="Duplicate" rel="permalink">' . __('Duplicate', 'wp-landing-pages') . '</a>';
    }
    return $actions;
}

// Screen shot colum width
add_filter('post_row_actions', 'wplp_duplicate_landing_page_link', 10, 2);

add_action('admin_head', 'wplp_scr_col_width');

function wplp_scr_col_width() {
    echo '<style type="text/css">';
    echo '.column-screen_shot { width:200px !important }';
    echo '</style>';
}

// Custom JS Meta box

add_action('add_meta_boxes', 'wplp_meta_custom_code');
/* Adds a box to the main column on the landing page edit screens */

function wplp_meta_custom_code() {
    add_meta_box(
            'wplp_section1', __('Custom Code', 'wp-landing-pages'), 'wplp_meta_box_html', 'landing-page', 'normal', 'high'
    );
}

/* Prints the box content */

function wplp_meta_box_html($post) {
    $mypostid = $post->ID;
    $wplp_custom_code_box = stripslashes(get_post_meta($mypostid, 'wplp_custom_code_box', true));
    ?><div class="inside"><textarea name="wplp_custom_code_box" id="wplp_custom_code_box" style="width:100%; height: 150px;"><?php echo $wplp_custom_code_box; ?></textarea></div>
    <?php
}

add_action('save_post', 'wplp_save_custom_code');

function wplp_save_custom_code($postID) {
    global $wpdb;
// called after a post or page is saved
    if ($parent_id = wp_is_post_revision($postID)) {
        $postID = $parent_id;
    }

    wplp_update_custom_meta($postID, addslashes($_POST['wplp_custom_code_box']), 'wplp_custom_code_box');
}

// custom edit link in post row actions
function wplp_edit_design_post_link($actions, $post) {
    global $wp_rewrite;
    if ($post->post_type == 'landing-page') {

        if ($wp_rewrite->permalink_structure == '') {
            $actions['edti_tpl'] = '<a href=' . get_permalink($post->ID) . '&do=edit title="" rel = "permalink" target="_blank">Edit in Design View</a>';
        } else {
            $actions['edti_tpl'] = '<a href=' . get_permalink($post->ID) . '?do=edit title="" rel = "permalink" target="_blank">Edit in Design View</a>';
        }
    }
    return $actions;
}

add_filter('post_row_actions', 'wplp_edit_design_post_link', 10, 2);

add_filter('get_pages', 'wplp_add_lp_dropdown_pages');

function wplp_add_lp_dropdown_pages($pages) {
    $mlist_wplp_pages = new WP_Query(array('post_type' => 'landing-page'));
    if ($mlist_wplp_pages->post_count > 0) {
        $pages = array_merge($pages, $mlist_wplp_pages->posts);
    }
    return $pages;
}

function llp_get_lp_data($lp_file) {
    global $wpdb;

    if (file_exists($lp_file)) {
        $filedata = get_file_data($lp_file, array(
            'Landing Page Name' => 'Landing Page Name',
            'Preview Image' => 'Preview Image',
            'Template File' => 'Template File',
        ));
        return ($filedata);
    }
}

function llp_read_tpl_dir() {
    $src_dir = llp_PATH . 'llp_tpl/*/';
    #$llp_folders = glob(llp_PATH . 'llp_tpl/template*');
    $llp_folders = glob(llp_PATH . 'llp_tpl/*');
    #$llp_folders = glob($src_dir . 'index.php');
    return $llp_folders;
}

// Generate logo function
function wplp_gen_logo($tpl_id, $data_id, $data_field, $default_logo = 'logo.png', $class = '') {
    global $wpdb, $post;
    $wplp_output_logo = '';

    // logo
    $llp_logo = get_post_meta($post->ID, $data_field, true);
    #${'$tpl_id' . _logo_image_link} = get_post_meta($post->ID, $tpl_id . '_logo_image_link', true);
    $llp_logo_image_link = get_post_meta($post->ID, $data_field . '_image_link', true);
    if (empty($llp_logo)) {
        $llp_logo = llp_URL . '/llp_tpl/' . $tpl_id . '/images/' . $default_logo;
    }
    if (empty($llp_logo_image_link)) {
        $llp_logo_image_link = home_url();
    }


    $wplp_output_logo = "<a id='" . $data_field . "' data-heading='Update Image' data-image-link='" . $llp_logo_image_link . "' data-id='" . $data_id . "' data-edit='true' data-type='image' "
            . "data-wp_custom_field='" . $data_field . "' href='" . $llp_logo_image_link . "' class='" . sanitize_html_class($class) . "'>"
            . "<img src='" . $llp_logo . "' />"
            . "</a>";

    return $wplp_output_logo;
}

// Generate disclaimer
function wplp_gen_disclaimer($tpl_id, $data_id) {
    global $wpdb, $post;

    // disclaimer content
    $llp_disclaimer_default = '<p class="diclaimer">' . __('Your information will *never* be shared or sold to 3rd party', 'wp-landing-pages') . '.</p>';
    $llp_disclaimer = get_post_meta($post->ID, $tpl_id . '_disclaimer', true);

    if (empty($llp_disclaimer)) {
        $llp_disclaimer = $llp_disclaimer_default;
    }
    $disclaimer_html = '<div id="' . $tpl_id . '_disclaimer" data-heading="Content" data-id="' . $data_id . '" data-edit="true" data-type="custom" data-default="' . htmlspecialchars(stripslashes($llp_disclaimer)) . '" data-wp_custom_field="' . $tpl_id . '_disclaimer">' . stripslashes($llp_disclaimer) . '</div>';

    return $disclaimer_html;
}

// Generate text editor
function wplp_gen_text($tpl_id, $data_id, $data_field, $default_cnt, $class = '') {
    global $post;

    $llp_cnt = get_post_meta($post->ID, $data_field, true);
    if (empty($llp_cnt)) {
        $llp_cnt = $default_cnt;
    }
    $llp_output_cnt = '<div id="' . $data_field . '" data-heading="Content" data-id="' . $data_id . '" data-edit="true" data-type="custom" data-default="" data-wp_custom_field="' . $data_field . '" class=' . sanitize_html_class($class) . '>' . stripslashes($llp_cnt) . '</div>';
    return $llp_output_cnt;
}

// Generate video frame
function wplp_gen_video($tpl_id, $data_id, $data_field, $default_cnt, $width, $height) {
    global $post;
    $llp_output_video = '';

    $llp_video = get_post_meta($post->ID, $data_field, true);
    if (empty($llp_video)) {
        $llp_video = $default_cnt;
    }

    $llp_output_video = '<div id="vid-frame" class="video-container" data-heading="Video" data-id="' . $data_id . '" data-edit="true" data-type="iframe" data-default="' . htmlspecialchars(stripslashes($llp_video)) . '" data-wp_custom_field="' . $data_field . '">';
    $llp_output_video .= $llp_video;
    $llp_output_video .= '</div>';

    return $llp_output_video;
}

// Generate form
function wplp_gen_form($tpl_id, $data_id, $data_field, $default_cnt) {
    global $post;
    $llp_output_form = '';

    // Form
    $llp_form = get_post_meta($post->ID, $data_field, true);
    $atform = $llp_form;

    $llp_output_form .= '<div class="form" id="' . $tpl_id . '_form" data-heading="Form" data-id="' . $data_id . '" data-edit="true" data-type="form" data-wp_custom_field="' . $data_field . '">';
    if ($llp_form) {
        $llp_output_form .= '<form action = "' . $atform['_optthemes_optinformurl'] . '" method = "post" class = "tpl-frm">';

        if ($atform['optintext']) {
            foreach ($atform['optintext'] as $atform_fields => $key) {
                if ($atform['optincheck'][$atform_fields] == 'yes') {

                    $llp_output_form .= '<input placeholder="' . $key . '" onfocus="this.placeholder = ' . $key . '" title="' . $key . '" value="" name="' . $atform_fields . '" type="' . $atform['optintype'][$atform_fields] . '">';
                }
            }
        }

        $llp_output_form .= $atform['_optthemes_webformhiddenhtml'];
        $llp_output_form .= '<button type="submit" class="btn">' . $atform['_optthemes_optinformsubmit'] . '</button>';
        $llp_output_form .= '</form>';
    } else {

        $llp_output_form .= '<form action="#" method="post" onsubmit="" target="_top" class="tpl-frm">';
        $llp_output_form .= '<input type="text" id="" placeholder="Your Name" />';
        $llp_output_form .= '<input placeholder="Enter a Valid Email Here..." onfocus="this.placeholder = \'Enter a Valid Email Here...\'" title="Enter a Valid Email Here..." value="" type="email" id="email" data-lb-inputemail="true" />';
        $llp_output_form .= '<button type="submit" class="btn">';
        $llp_output_form .= __('Signup Now!', 'wp-landing-pages');
        $llp_output_form .= '</button>
</form>';
    }
    $llp_output_form .= '</div>';
    return $llp_output_form;
}

// Get existing background image
function wplp_get_bg_image($tpl_id, $data_field, $default_img) {
    global $post;
    // background images
    $llp_bg_image = get_post_meta($post->ID, $data_field, true);
    if (empty($llp_bg_image)) {
        $llp_bg_image = llp_URL . '/llp_tpl/' . $tpl_id . '/images/' . $default_img;
    }

    return $llp_bg_image;
}

// Generate change background image button
function wplp_gen_bg_img($tpl_id, $data_id, $data_field, $llp_bg_image, $btn_text = 'Change Background Image', $class = '') {
    global $post;
    $llp_output_bg_image = '';

    $llp_output_bg_image .= '<a id="' . $tpl_id . '_bg_image" href="javascript:void(0);" data-heading="Update Background"  data-id="' . $data_id . '" data-edit="true" data-type="bgimage" data-default=\'<img src = "' . $llp_bg_image . '" >\' data-wp_custom_field="' . $data_field . '" class="llp_btn ' . sanitize_html_class($class) . '">';
    $llp_output_bg_image .= $btn_text; #__('Change Image', 'wp-landing-pages');
    $llp_output_bg_image .= '</a>';

    return $llp_output_bg_image;
}

function wplp_gen_button($tpl_id, $data_id, $data_field) {
    global $post;


    $llp_button = get_post_meta($post->ID, $data_field, true);

    if (!isset($llp_button)) {
        $llp_button = __('Click Here for Instant Access', 'wp-landing-pages');
    }

    #$llp_button = isset(get_post_meta($post->ID, $data_field, true)) ? get_post_meta($post->ID, $data_field, true) : __('Click Here for Instant Access', 'wp-landing-pages');

    $llp_button_link_a = get_post_meta($post->ID, $data_field . '_link_a', true);
    if (empty($llp_button_link_a)) {
        $llp_button_link_a = home_url();
    }
    $llp_button_link_a_open = get_post_meta($post->ID, $data_field . '_link_a_open', true);
    if (empty($llp_button_link_a_open)) {
        if ($llp_button_link_a_open == '1') {
            $llp_button_link_a_open = '_blank';
        } else {
            $llp_button_link_a_open = '';
        }
    }

    $llp_output_button = '<a id="' . $data_field . '" data-heading="Button" data-id="' . $data_id . '" data-edit="true" data-type="link" data-default="' . $llp_button . '" '
            . 'data-wp_custom_field="' . $data_field . '" data-link="' . $llp_button_link_a . '" data-target="1" href="' . $llp_button_link_a_open . '" class="btn2 llp_box" ';
    if ($llp_button_link_a_open != "") {
        $llp_output_button .= 'target="_blank"';
    }
    $llp_output_button .= '>' . $llp_button . '</a>';

    return $llp_output_button;
}

// Generate image or logo function
function wplp_gen_image($tpl_id, $data_id, $data_field, $default_logo = 'logo.png', $class = '') {
    global $wpdb, $post;
    $wplp_output_logo = '';

    // logo
    $llp_logo = get_post_meta($post->ID, $data_field, true);
    #${'$tpl_id' . _logo_image_link} = get_post_meta($post->ID, $tpl_id . '_logo_image_link', true);
    $llp_logo_image_link = get_post_meta($post->ID, $data_field . '_image_link', true);
    if (empty($llp_logo)) {
        $llp_logo = llp_URL . '/llp_tpl/' . $tpl_id . '/images/' . $default_logo;
    }
    if (empty($llp_logo_image_link)) {
        #$llp_logo_image_link = home_url();
        $wplp_output_logo = "<div id='" . $data_field . "' data-heading='Update Image' data-id='" . $data_id . "' data-edit='true' data-type='image' "
                . "data-wp_custom_field='" . $data_field . "' class='" . sanitize_html_class($class) . "'>"
                . "<img src='" . $llp_logo . "' />"
                . "</div>";
    } else {
        $wplp_output_logo = "<a id='" . $data_field . "' data-heading='Update Image' data-image-link='" . $llp_logo_image_link . "' data-id='" . $data_id . "' data-edit='true' data-type='image' "
                . "data-wp_custom_field='" . $data_field . "' href='" . $llp_logo_image_link . "' class='" . sanitize_html_class($class) . "'>"
                . "<img src='" . $llp_logo . "' />"
                . "</a>";
    }
    return $wplp_output_logo;
}

function llp_get_lp_data_pro($lp_file) {
    global $wpdb;

    if (file_exists($lp_file)) {
        $filedata = get_file_data($lp_file, array(
            'Landing Page Name' => 'Landing Page Name',
            'Preview Image' => 'Preview Image',
            'Template File' => 'Template File',
        ));
        return ($filedata);
    }
}

function wplp_get_templates_pro() {
    include 'llp_premium_templates.php';
}
