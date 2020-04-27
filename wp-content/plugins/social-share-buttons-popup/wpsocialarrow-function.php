<?php
/*
Plugin Name: Social Share buttons
Description: Simply add social share buttons to your WordPress site
Author: Premio
Author URI: https://premio.io/downloads/social-share-buttons/
Version: 1.3.9
License: GplV2
*/

/* PLUGIN VAR */
define('PR_SOCIAL_PLUGIN_FILE', __FILE__ );
define('PR_SOCIAL_PLUGIN_BASE', plugin_basename(PR_SOCIAL_PLUGIN_FILE));
define('PR_SOCIAL_SUBSCRIBE_URL',plugin_dir_url(__FILE__));
$upgrade_link = admin_url("admin.php?page=social_upgrade_to_pro");
define("PR_SOCIAL_UPGRADE_LINK", $upgrade_link);
define("PR_SOCIAL_UPGRADE_VERSION_TEXT","Upgrade to Pro");

include_once 'social-buttons.class.php';
register_activation_hook( __FILE__, array( 'social_share_button_subscribe', 'activate' ) );
$socialArray = array(
    'facebook', 'twitter', 'linkedin', 'pinterest', 'whatsapp', 'reddit', 'telegram', 'tumbler', 'vkontakte', 'wechat', 'email', 'line'
);
function premio_social_array_list() {
    return array(
        'facebook', 'twitter', 'linkedin', 'pinterest', 'whatsapp', 'reddit', 'telegram', 'tumbler', 'vkontakte', 'wechat', 'email', 'line'
    );
}
function wpssi_add_social_icons_post($content) {
    $social_content = '';
	if (get_option('wpsocialarrow-enable-post') == 1 && is_single()) {
		global $post;
		$url = get_permalink($post->ID);
		$url = esc_url($url);
		$title = get_the_title($post->ID);

		$social_content = get_html_string_for_social_buttons($url, $title);
	}
	if($social_content != "") {

		$skin = get_option("wpsocialarrow-skins");
		$skin = empty($skin)?"default-skin":$skin;

		if (get_option('wpsocialarrow-floatingleft') == 'floatingleft') {
			$extra_content = "<div class='social-pull-left social-floating {$skin}-theme'>{$social_content}</div>";
			$content = $extra_content . $content;
		}
		if (get_option('wpsocialarrow-beforepost') == 'beforepost') {
			$content = $social_content.$content;
		}
		if (get_option('wpsocialarrow-positioning') == 'afterpost') {
			$content = $content . $social_content;
		}
		if (get_option('wpsocialarrow-floatingright') == 'floatingright') {
			$extra_content = "<div class='social-pull-right social-floating {$skin}-theme'>{$social_content}</div>";
			$content = $content . $extra_content;
		}
	}
	return $content;
}

if (get_option('wpsocialarrow-enable-post') == 1) {
    add_filter('the_content', 'wpssi_add_social_icons_post');
}

if(is_admin()) {
    include_once "class-review-box.php";
    include_once "class-affiliate.php";
}

function wpssi_add_social_icons_page($content) {
    $social_content = '';
	if (get_option('wpsocialarrow-enable-page') == 1 && is_page() && ( !is_home() && !is_front_page())) {
		global $post;
		$url = get_permalink($post->ID);
		$url = esc_url($url);
		$title = get_the_title($post->ID);

		$social_content = get_html_string_for_social_buttons($url, $title);		
	}
	if($social_content != "") {

		$skin = get_option("wpsocialarrow-skins");
		$skin = empty($skin)?"default-skin":$skin;

		if (get_option('wpsocialarrow-floatingleft') == 'floatingleft') {
			$extra_content = "<div class='social-pull-left social-floating {$skin}-theme'>{$social_content}</div>";
			$content = $extra_content . $content;
		}
		if (get_option('wpsocialarrow-beforepost') == 'beforepost') {
			$content = $social_content.$content;
		}
		if (get_option('wpsocialarrow-positioning') == 'afterpost') {
			$content = $content . $social_content;
		}
		if (get_option('wpsocialarrow-floatingright') == 'floatingright') {
			$extra_content = "<div class='social-pull-right social-floating {$skin}-theme'>{$social_content}</div>";
			$content = $content . $extra_content;
		}
	}
    return $content;
    
}

if (get_option('wpsocialarrow-enable-page') == 1) {
    add_filter('the_content', 'wpssi_add_social_icons_page');
}


function wpssi_add_social_icons_home($content)
{
    $social_content = '';
	if (get_option('wpsocialarrow-enable-home') == 1 && is_home() || is_front_page()) {
		global $post;
		$url = site_url();
		$url = esc_url($url);
		$title = "";
		$social_content = get_html_string_for_social_buttons($url, $title);		
	}
	if($social_content != "") {

		$skin = get_option("wpsocialarrow-skins");
		$skin = empty($skin)?"default-skin":$skin;

		if (get_option('wpsocialarrow-floatingleft') == 'floatingleft') {
			$extra_content = "<div class='social-pull-left social-floating {$skin}-theme'>{$social_content}</div>";
			$content = $extra_content . $content;
		}
		if (get_option('wpsocialarrow-beforepost') == 'beforepost') {
			$content = $social_content.$content;
		}
		if (get_option('wpsocialarrow-positioning') == 'afterpost') {
			$content = $content . $social_content;
		}
		if (get_option('wpsocialarrow-floatingright') == 'floatingright') {
			$extra_content = "<div class='social-pull-right social-floating {$skin}-theme'>{$social_content}</div>";
			$content = $content . $extra_content;
		}
	}
	return $content;
}

if (get_option('wpsocialarrow-enable-home') == 1) {
    add_filter('the_excerpt', 'wpssi_add_social_icons_home');
    add_filter('the_content', 'wpssi_add_social_icons_home');
}

/* Send message to owner */
add_action( 'wp_ajax_social_buttons_send_message_to_owner', 'social_buttons_send_message_to_owner' );
//register_uninstall_hook(__FILE__, 'wpssi_delete_options');

function social_buttons_send_message_to_owner() {
    $response = array();
    $response['status'] = 0;
    $response['error'] = 0;
    $response['errors'] = array();
    $response['message'] = "";
    $errorArray = [];
    $errorMessage = __("%s is required", 'stars-testimonials');
    $postData = $_POST;
    if(!isset($postData['textarea_text']) || trim($postData['textarea_text']) == "") {
        $error = array(
            "key"   => "textarea_text",
            "message" => __("Please enter your message",'stars-testimonials')
        );
        $errorArray[] = $error;
    }
    if(!isset($postData['user_email']) || trim($postData['user_email']) == "") {
        $error = array(
            "key"   => "user_email",
            "message" => sprintf($errorMessage,__("Email",'stars-testimonials'))
        );
        $errorArray[] = $error;
    } else if(!filter_var($postData['user_email'], FILTER_VALIDATE_EMAIL)) {
        $error = array(
            'key' => "user_email",
            "message" => "Email is not valid"
        );
        $errorArray[] = $error;
    }
    if(empty($errorArray)) {
        if(!isset($postData['nonce']) || trim($postData['nonce']) == "") {
            $error = array(
                "key"   => "nonce",
                "message" => __("Your request is not valid", 'stars-testimonials')
            );
            $errorArray[] = $error;
        } else {
            if(!wp_verify_nonce($postData['nonce'], 'social_buttons_send_message_to_owner_nonce')) {
                $error = array(
                    "key"   => "nonce",
                    "message" => __("Your request is not valid", 'stars-testimonials')
                );
                $errorArray[] = $error;
            }
        }
    }
    if(empty($errorArray)) {
        global $current_user;
        $text_message = sanitize_textarea_field($postData['textarea_text']);
        $email = sanitize_email($postData['user_email']);
        $domain = site_url();
        $user_name = $current_user->first_name." ".$current_user->last_name;
        $subject = "Social Share buttons request: ".$domain;
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= 'From: '.$user_name.' <'.$email.'>'.PHP_EOL ;
        $headers .= 'Reply-To: '.$user_name.' <'.$email.'>'.PHP_EOL ;
        $headers .= 'X-Mailer: PHP/' . phpversion();
        ob_start();
        ?>
        <table border="0" cellspacing="0" cellpadding="5">
            <tr>
                <th>Domain</th>
                <td><?php echo $domain ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo $email ?></td>
            </tr>
            <tr>
                <th>Message</th>
                <td><?php echo nl2br($text_message) ?></td>
            </tr>
        </table>
        <?php
        $message = ob_get_clean();
        $to = "contact+fromwp@premio.io";
        $status = wp_mail($to, $subject, $message, $headers);
        if($status) {
            $response['status'] = 1;
        } else {
            $response['status'] = 0;
            $response['message'] = "Not able to send mail";
        }
    } else {
        $response['error'] = 1;
        $response['errors'] = $errorArray;
    }
    echo json_encode($response);
}

require('wpsocialarrow-enque-script.php');
require('wpsocialarrow-admin.php');
require('wpsocialarrow-del-option.php');

?>