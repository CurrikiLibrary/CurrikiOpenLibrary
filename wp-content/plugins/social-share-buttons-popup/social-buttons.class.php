<?php
class social_share_button_subscribe {
    private static $instance;

    private function __construct() {
        add_filter('plugin_action_links_' . PR_SOCIAL_PLUGIN_BASE, [$this, 'plugin_action_links']);
        add_action('admin_enqueue_scripts', array($this, 'admin_styles'));
        add_action('admin_init', array($this, 'admin_init'));

        /* load language files */
        add_action( 'plugins_loaded', array( $this, 'plugin_text' ) );
    }

    public function plugin_text() {
        load_plugin_textdomain("social-share-buttons-popup", FALSE, dirname(plugin_basename(__FILE__)).'/languages/');
    }

    public function plugin_action_links($links)
    {
        $links['pro'] = '<a class="upgrade-button" href="'.admin_url("admin.php?page=social_upgrade_to_pro").'" >'.__( 'Upgrade', 'subscribe-form').'</a>';
        return $links;
    }

    public static function get_instance() {
        if (empty(self::$instance)) {
            self::$instance = new social_share_button_subscribe();
        }
        return self::$instance;
    }

    function admin_styles()
    {
        wp_register_style('wcp-css-handle', false);
        wp_enqueue_style('wcp-css-handle');
        $css = "
				.upgrade-button {color: #FF5983; font-weight: bold;}
			";
        wp_add_inline_style('wcp-css-handle', $css);
    }

    function admin_init() {
        $option = get_option("social_share_button_subscribe_redirect_status", true);
        if ($option == "on") {
            update_option("social_share_button_subscribe_redirect_status", "off");
            wp_redirect(admin_url("admin.php?page=wpssi-options"));
            exit;
        }
    }

    public static function activate() {
        update_option("social_share_button_subscribe_redirect_status", "on");
        update_option("wpsocialarrow-enable-post", "1");
        update_option("wpsocialarrow-enable-plugin", "1");
        update_option("wpsocialarrow-positioning", "afterpost");
        update_option("wpsocialarrow-skins", "default-skin");
    }
}
social_share_button_subscribe::get_instance();

if (!function_exists('get_social_button_list')) {
	function get_social_button_list() {
		return array(
			'facebook' => array(
				'title' => "Facebook",
				'name' => 'facebook',
				'class' => 'facebook',
				'share_url' => "http://www.facebook.com/sharer/sharer.php?u=__url__"
			),
			'twitter' => array(
				'title' => "Twitter",
				'name' => 'twitter',
				'class' => 'twitter',
				'share_url' => "https://twitter.com/share?url=__url__"
			),  
			'whatsapp' => array(
				'title' => "WhatsApp",
				'name' => 'whatsapp',
				'class' => 'whatsapp',
				'share_url' => "https://api.whatsapp.com/send/?text=__url__"
			),
			'email' => array(
				'title' => "Email",
				'name' => 'email',
				'class' => 'email',
				'share_url' => "mailto:?body=__url__"
			),		
			'linkedin' => array(
				'title' => "LinkedIn",
				'name' => 'linkedin',
				'class' => 'linkedin',
				'share_url' => "http://www.linkedin.com/shareArticle?url=__url__"
			),
			'telegram' => array(
				'title' => "Telegram",
				'name' => 'telegram',
				'class' => 'telegram',
				'share_url' => "https://telegram.me/share/url?url==__url__"
			),
			'tumbler' => array(
				'title' => "Tumblr",
				'name' => 'tumbler',
				'class' => 'tumbler',
				'share_url' => "http://www.tumblr.com/share/link?url=__url__"
			),
			'line' => array(
				'title' => "Line",
				'name' => 'line',
				'class' => 'line',
				'share_url' => "https://lineit.line.me/share/ui?url=__url__&text=__title__"
			),
			'pinterest' => array(
				'title' => "Pinterest",
				'name' => 'pinterest',
				'class' => 'pinterest',
				'share_url' => "http://pinterest.com/pin/create/button/?url=__url__"
			),        
			'reddit' => array(
				'title' => "Reddit",
				'name' => 'reddit',
				'class' => 'reddit',
				'share_url' => "http://reddit.com/submit?url=__url__"
			),
			'vkontakte' => array(
				'title' => "VKontakte",
				'name' => 'vkontakte',
				'class' => 'vkontakte',
				'share_url' => "http://vk.com/share.php?url=__url__"
			),
			'wechat' => array(
				'title' => "WeChat",
				'name' => 'wechat',
				'class' => 'wechat',
				'share_url' => "https://www.addtoany.com/add_to/wechat?linkurl=__url__"
			),
			
		);
	}
}
if (!function_exists('get_social_button_pattern')) {
	function get_social_button_pattern() {
		return array(
			"default-skin" => array(
				'id' => "1",
				'title' => "Default",
				'type' => 'default-skin',
				'reg' => '<a href="javascript:;" class="premio-_social_slug_" title="_social_title_"></a>',
				'prefix' => '<div class="social1">',
				'postfix' => '</div>',
				'is_locked' => 0
			),
			"round-rectangle" => array(
				'id' => "16",
				'title' => "Round Rectangle",
				'type' => 'round-rectangle',
				'reg' => '<a href="javascript:;" class="theme16-_social_slug_ button-_social_slug_" title="_social_title_"></a>',
				'prefix' => '<div class="social16 wp-social-arrow-theme16">',
				'postfix' => '</div>',
				'is_locked' => 1
			),
			"round-triangle" => array(
				'id' => "18",
				'title' => "Round Triangle",
				'type' => 'round-triangle',
				'reg' => '<a href="javascript:;" class="theme18-_social_slug_ button-_social_slug_" title="_social_title_"></a>',
				'prefix' => '<div class="social18 wp-social-arrow-theme18">',
				'postfix' => '</div>',
				'is_locked' => 1
			),
			"lion-icon" => array(
				'id' => "19",
				'title' => "Lion Icon",
				'type' => 'lion-icon',
				'reg' => '<a href="javascript:;" class="theme19-_social_slug_ button-_social_slug_" title="_social_title_"></a>',
				'prefix' => '<div class="social19 wp-social-arrow-theme19">',
				'postfix' => '</div>',
				'is_locked' => 1
			),
			"social-wide" => array(
				'id' => "2",
				'title' => "Social Wide",
				'type' => 'social-wide',
				'reg' => '<a href="javascript:;" class="premio-_social_slug_ _social_slug_" title="_social_title_"></a>',
				'prefix' => '<div class="social1">',
				'postfix' => '</div>',
				'is_locked' => 1
			),
			"bounce-up" => array(
				'id' => "3",
				'title' => "Flip Cards (With Animation)",
				'type' => 'bounce-up',
				'reg' => '<a title="_social_title_" href="javascript:;" target="_blank"><div class="social--_social_slug_ social-buttons button-_social_slug_ social-btn-_social_slug_"></div></a>',
				'prefix' => '<div class="social3 wp-social-arrow-theme3">',
				'postfix' => '</div>',
				'is_locked' => 1
			),
			"grind-in" => array(
				'id' => "4",
				'title' => "Roller (With Animation)",
				'type' => 'grind-in',
				'reg' => '<a href="javascript:;"  title="_social_title_" class="button-_social_slug_ btn__social_slug_"><i class="social-btn-_social_slug_"></i><i class="social-btn-_social_slug_"></i></a>',
				'prefix' => '<div class="social_icons wp-social-arrow-theme4">',
				'postfix' => '</div>',
				'is_locked' => 1
			),
			"paper-fold" => array(
				'id' => "5",
				'title' => "Paper Fold (With Animation)",
				'type' => 'paper-fold',
				'reg' => '<li id="_social_slug_theme5"><div><a title="_social_title_" href="javascript:;"><span class="wpsocialarrow-theme5-_social_slug_ button-_social_slug_ hvr-wobble-vertical"></span></a></div></li>',
				'prefix' => '<div id="wpsocialarrow-theme5" class="wp-social-arrow-theme5 wp-social-arrow-theme"><nav><ul>',
				'postfix' => '</ul></div>',
				'is_locked' => 1
			),
			"branded" => array(
				'id' => "6",
				'title' => "Branded (With Animation)",
				'type' => 'branded',
				'reg' => '<li id="_social_slug_theme6" class="dr-margin"><div><a title="_social_title_" href="javascript:;"><span class="wpsocialarrow-theme6-_social_slug_ button-_social_slug_ hvr-outline-in_social_slug_"></span></a></div></li>',
				'prefix' => '<div id="wpsocialarrow-theme6" class="wp-social-arrow-theme6 wp-social-arrow-theme"><nav><ul>',
				'postfix' => '</ul></div>',
				'is_locked' => 1
			),
			"radiused" => array(
				'id' => "7",
				'title' => "Radiused (With Animation)",
				'type' => 'radiused',
				'reg' => '<li id="_social_slug_theme7" class="dr-margin"><div><a title="_social_title_" href="javascript:;"><span class="wpsocialarrow-theme7-_social_slug_ button-_social_slug_ rotate_social_slug_"></span></a></div></li>',
				'prefix' => '<div id="wpsocialarrow-theme7" class="wp-social-arrow-theme7 wp-social-arrow-theme"><nav><ul>',
				'postfix' => '</ul></div>',
				'is_locked' => 1
			),
			"octagon" => array(
				'id' => "8",
				'title' => "Octagon (With Animation)",
				'type' => 'octagon',
				'reg' => '<li id="_social_slug_theme8" class="dr-margin"><div><a title="_social_title_" href="javascript:;"><span class="wpsocialarrow-theme8-_social_slug_ button-_social_slug_ hvr-float-shadow"></span></a></div></li>',
				'prefix' => '<div id="wpsocialarrow-theme8" class="wp-social-arrow-theme8 wp-social-arrow-theme"><nav><ul>',
				'postfix' => '</ul></div>',
				'is_locked' => 1
			),
			"hanging" => array(
				'id' => "9",
				'title' => "Hanging (With Animation)",
				'type' => 'hanging',
				'reg' => '<li id="_social_slug_theme9" class="dr-margin"><div><a title="_social_title_" href="javascript:;"><span class="wpsocialarrow-theme9-_social_slug_ button-_social_slug_ hvr-wobble-bottom"></span></a></div></li>',
				'prefix' => '<div id="wpsocialarrow-theme9" class="wp-social-arrow-theme9 wp-social-arrow-theme"><nav><ul>',
				'postfix' => '</ul></div>',
				'is_locked' => 1
			),
			"tricon" => array(
				'id' => "10",
				'title' => "Tricon (With Animation)",
				'type' => 'tricon',
				'reg' => '<li id="_social_slug_theme10" class="dr-margin"><div><a href="javascript:;"><span class="wpsocialarrow-theme10-_social_slug_ button-_social_slug_ hvr-buzz-out"></span></a></div></li>',
				'prefix' => '<div id="wpsocialarrow-theme10" class="wp-social-arrow-theme10 wp-social-arrow-theme"><nav><ul>',
				'postfix' => '</ul></div>',
				'is_locked' => 1
			),
			"hollow" => array(
				'id' => "11",
				'title' => "Hollow (With Animation)",
				'type' => 'hollow',
				'reg' => '<li id="_social_slug_theme11" class="dr-margin"><div><a title="_social_title_" href="javascript:;"><span class="wpsocialarrow-theme11-_social_slug_ button-_social_slug_ rotate_social_slug_"></span></a></div></li>',
				'prefix' => '<div id="wpsocialarrow-theme11" class="wp-social-arrow-theme11 wp-social-arrow-theme"><nav><ul>',
				'postfix' => '</ul></div>',
				'is_locked' => 1
			),
			"sociallambs" => array(
				'id' => "12",
				'title' => "Social Lambs (With Animation)",
				'type' => 'sociallambs',
				'reg' => '<li id="_social_slug_theme12" class="dr-margin"><div><a title="_social_title_" href="javascript:;"><span class="wpsocialarrow-theme12-_social_slug_ button-_social_slug_ hvr-pop"></span></a></div></li>',
				'prefix' => '<div id="wpsocialarrow-theme12" class="wp-social-arrow-theme12 wp-social-arrow-theme"><nav><ul>',
				'postfix' => '</ul></div>',
				'is_locked' => 1
			),
			"3dicons" => array(
				'id' => "13",
				'title' => "3D Icons (With Animation)",
				'type' => '3dicons',
				'reg' => '<li id="_social_slug_theme17" class="dr-margin"><div><a title="_social_title_" href="javascript:;"><span class="wpsocialarrow-theme17-_social_slug_ button-_social_slug_ hvr-grow-rotate"></span></a></div></li>',
				'prefix' => '<div id="wpsocialarrow-theme17" class="wp-social-arrow-theme17 wp-social-arrow-theme"><nav><ul>',
				'postfix' => '</ul></div>',
				'is_locked' => 1
			),
			"whitestitchedborder" => array(
				'id' => "14",
				'title' => "White Stitched Border (With Animation)",
				'type' => 'whitestitchedborder',
				'reg' => '<li id="_social_slug_theme14" class="dr-margin"><div><a title="_social_title_" href="javascript:;"><span class="wpsocialarrow-theme14-_social_slug_ button-_social_slug_ hvr-wobble-skew"></span></a></div></li>',
				'prefix' => '<div id="wpsocialarrow-theme14" class="wp-social-arrow-theme14 wp-social-arrow-theme"><nav><ul>',
				'postfix' => '</ul></div>',
				'is_locked' => 1
			),
			"whiterounded" => array(
				'id' => "15",
				'title' => "White Rounded (With Animation)",
				'type' => 'whiterounded',
				'reg' => '<li id="_social_slug_theme15" class="dr-margin"><div><a title="_social_title_" href="javascript:;"><span class="wpsocialarrow-theme15-_social_slug_ button-_social_slug_ hvr-bounce-out"></span></a></div></li>',
				'prefix' => '<div id="wpsocialarrow-theme15" class="wp-social-arrow-theme15 wp-social-arrow-theme"><nav><ul>',
				'postfix' => '</ul></div>',
				'is_locked' => 1
			)
		);
	}
}

$html_string_for_social_buttons = "";
if (!function_exists('get_html_string_for_social_buttons')) {
	function get_html_string_for_social_buttons($page_url, $page_title) {
		global $html_string_for_social_buttons;
		if(!empty($html_string_for_social_buttons)) {
			return $html_string_for_social_buttons;
		}

		$skin = get_option("wpsocialarrow-skins");
		$skin = empty($skin)?"default-skin":$skin;

		$skins = get_social_button_pattern();
		$skin_data = isset($skins[$skin])?$skins[$skin]:$skins["default-skin"];

		$custom_order = get_option("wpsocialarrow-custom-order");
		$custom_order = str_replace("soc-","",$custom_order);

		$social_buttons = get_social_button_list();

		$social_string = "";
		if(!empty($custom_order)) {
			$custom_order = explode(",", $custom_order);

			$alignment = get_option("wpsocialarrow-alignment");

			$class = "social-align-left";
			if($alignment == "aligncenter") {
				$class = "social-align-center";
			} else if($alignment == "alignright") {
				$class = "social-align-right";
			}

			$social_string = "<div class='poptin-social-buttons {$class} {$skin}-theme'>";

			$social_string .= $skin_data['prefix'];

			$reg = $skin_data['reg'];

			$reg = str_replace("javascript:;","__url__", $reg);
			$reg = str_replace("href","target='_blank' href", $reg);

			foreach($custom_order as $order) {
				if(isset($social_buttons[$order])) {
					$theme = $social_buttons[$order];
					$link = $theme['share_url'];
					$link = str_replace("__url__", urlencode($page_url), $link);
					$link = str_replace("__title__", urlencode($page_title), $link);

					$title = $theme['title'];
					$slug = $theme['class'];

					$share_link = $reg;
					$share_link = str_replace("_social_slug_", $slug, $share_link);
					$share_link = str_replace("_social_title_", $title, $share_link);
					$share_link = str_replace("__url__", $link, $share_link);

					$social_string .= $share_link;
				}
			}

			$social_string .= $skin_data['postfix'];

			$social_string .= "</div>";

			/* Custom message */
			$message = get_option("wpsocialarrow-message-selection");
			
			if(!empty($message) && $message != "None") {            
				$message = ( $message!='' ) ? $message : "Share this post" ;
				
				$font_family = get_option("wpsocialarrow-gfonts");
				$font = '';
				if(!empty($font_family)) {
					$social_string .= '<link href="http://fonts.googleapis.com/css?family=' . $font_family . '" rel="stylesheet" type="text/css">';
					$font = $font_family;
				}
				$social_string = '<div style="font-family: '.$font.'" class="social-button-message '.$class.'">'.$message.'</div>'.$social_string;
			}
			
			$social_string .='<div class="clear"></div><div class="social-button-credit '.$class.'"><a class="social-button-pro" target="_blank" href="https://premio.io/downloads/social-share-buttons/?utm_source=credit&amp;domain=' . site_url(). '">Get Widget</a></div>';
		}
		
		
		return $social_string;
	}
}