<?php
function wpssi_menu_item()
{
    add_menu_page("Social Share Icons", "Social Share Icons", "manage_options", "wpssi-options", "wpssi_settings_page", 'dashicons-share', "20");
    add_submenu_page(
        'wpssi-options',
        'Upgrade To Pro',
        'Upgrade To Pro',
        'manage_options',
        'social_upgrade_to_pro',
        'social_upgrade_to_pro');
}

function social_upgrade_to_pro()
{
    include_once 'upgrade-to-pro.php';
}

add_action("admin_menu", "wpssi_menu_item");
function wpssi_register_settings()
{
    if (isset($_POST['option_page']) && $_POST['option_page'] == 'wpssi-settings-group') {
        if(isset($_POST['nonce']) && !empty($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], "social_share_button_popup")) {
            register_setting('wpssi-settings-group', 'wpsocialarrow-enable-plugin', 'wpssi_sanitize_options_int');
            register_setting('wpssi-settings-group', 'wpsocialarrow-enable-post', 'wpssi_sanitize_options_int');
            register_setting('wpssi-settings-group', 'wpsocialarrow-enable-page', 'wpssi_sanitize_options_int');
            register_setting('wpssi-settings-group', 'wpsocialarrow-enable-home', 'wpssi_sanitize_options_int');
            register_setting('wpssi-settings-group', 'wpsocialarrow-message-selection', 'wpssi_sanitize_options');
            register_setting('wpssi-settings-group', 'wpsocialarrow-custom-message', 'wpssi_sanitize_options');
            register_setting('wpssi-settings-group', 'wpsocialarrow-positioning', 'wpssi_sanitize_options');
            register_setting('wpssi-settings-group', 'wpsocialarrow-alignment', 'wpssi_sanitize_options');
            register_setting('wpssi-settings-group', 'wpsocialarrow-facebook', 'wpssi_sanitize_options');
            register_setting('wpssi-settings-group', 'wpsocialarrow-twitter', 'wpssi_sanitize_options');
            register_setting('wpssi-settings-group', 'wpsocialarrow-linkedin', 'wpssi_sanitize_options');
            register_setting('wpssi-settings-group', 'wpsocialarrow-pinterest', 'wpssi_sanitize_options');
            register_setting('wpssi-settings-group', 'wpsocialarrow-whatsapp', 'wpssi_sanitize_options');
            register_setting('wpssi-settings-group', 'wpsocialarrow-reddit', 'wpssi_sanitize_options');
            register_setting('wpssi-settings-group', 'wpsocialarrow-telegram', 'wpssi_sanitize_options');
            register_setting('wpssi-settings-group', 'wpsocialarrow-tumbler', 'wpssi_sanitize_options');
            register_setting('wpssi-settings-group', 'wpsocialarrow-vkontakte', 'wpssi_sanitize_options');
            register_setting('wpssi-settings-group', 'wpsocialarrow-wechat', 'wpssi_sanitize_options');
            register_setting('wpssi-settings-group', 'wpsocialarrow-email', 'wpssi_sanitize_options');
            register_setting('wpssi-settings-group', 'wpsocialarrow-skins', 'wpssi_sanitize_options');
            register_setting('wpssi-settings-group', 'wpsocialarrow-line', 'wpssi_sanitize_options');
            register_setting('wpssi-settings-group', 'wpsocialarrow-gfonts', 'wpssi_sanitize_options');
            register_setting('wpssi-settings-group', 'wpsocialarrow-custom-order', 'wpssi_sanitize_options');

            register_setting('wpssi-settings-group', 'wpsocialarrow-beforepost', 'wpssi_sanitize_options');
            register_setting('wpssi-settings-group', 'wpsocialarrow-floatingleft', 'wpssi_sanitize_options');
            register_setting('wpssi-settings-group', 'wpsocialarrow-floatingright', 'wpssi_sanitize_options');
        }
    }
}

add_filter( 'whitelist_options', 'wpssi_settings_page_update_option' );
function wpssi_settings_page_update_option() {		
	if ( isset($_POST['option_page']) && $_POST['option_page'] == 'wpssi-settings-group' && isset($_POST['nonce']) && !empty($_POST['nonce'])) {
		$wpssi_settings_update = 'Your social share buttons settings were saved successfully';
		set_transient( 'settings_wpssi_update', $wpssi_settings_update, 30 );
	}
}
add_action('admin_init', 'wpssi_register_settings');
function wpssi_settings_page()
{
    $url = "";

    $social_widgets = array(
        array(
            'title' => "Facebook",
            'slug' => "facebook"
        ),
        array(
            'title' => "Twitter",
            'slug' => "twitter"
        ),        
        array(
            'title' => "LinkedIn",
            'slug' => "linkedin"
        ),
        array(
            'title' => "Pinterest",
            'slug' => "pinterest"
        )
    );

    $social_pattern = array(
        "patent-1" => array(
            'id' => "1",
            'title' => "Default",
            'type' => 'default-skin',
            'reg' => '<a href="javascript:;" class="premio-_social_slug_" title="_social_title_"></a>',
            'prefix' => '<div class="social1">',
            'postfix' => '</div>',
            'is_locked' => 0
        ),
        "patent-16" => array(
            'id' => "16",
            'title' => "Round Rectangle",
            'type' => 'round-rectangle',
            'reg' => '<a href="javascript:;" class="theme16-_social_slug_" title="_social_title_"></a>',
            'prefix' => '<div class="social16">',
            'postfix' => '</div>',
            'is_locked' => 1
        ),
        "patent-18" => array(
            'id' => "18",
            'title' => "Round Triangle",
            'type' => 'round-triangle',
            'reg' => '<a href="javascript:;" class="theme18-_social_slug_" title="_social_title_"></a>',
            'prefix' => '<div class="social18">',
            'postfix' => '</div>',
            'is_locked' => 1
        ),
        "patent-19" => array(
            'id' => "19",
            'title' => "Lion Icon",
            'type' => 'lion-icon',
            'reg' => '<a href="javascript:;" class="theme19-_social_slug_" title="_social_title_"></a>',
            'prefix' => '<div class="social19">',
            'postfix' => '</div>',
            'is_locked' => 1
        ),
        "patent-2" => array(
            'id' => "2",
            'title' => "Social Wide",
            'type' => 'social-wide',
            'reg' => '<a href="javascript:;" class="premio-_social_slug_ _social_slug_" title="_social_title_"></a>',
            'prefix' => '<div class="social1">',
            'postfix' => '</div>',
            'is_locked' => 1
        ),
        "patent-3" => array(
            'id' => "3",
            'title' => "Flip Cards (With Animation)",
            'type' => 'bounce-up',
            'reg' => '<a href="#" target="_blank"><div class="social--_social_slug_"></div></a>',
            'prefix' => '<div class="social3">',
            'postfix' => '</div>',
            'is_locked' => 1
        ),
        "patent-4" => array(
            'id' => "4",
            'title' => "Roller (With Animation)",
            'type' => 'grind-in',
            'reg' => '<a class="btn__social_slug_"><i class="fa fa-_social_slug_"></i><i class="fa fa-_social_slug_"></i></a>',
            'prefix' => '<div class="social_icons">',
            'postfix' => '</div>',
            'is_locked' => 1
        ),
        "patent-5" => array(
            'id' => "5",
            'title' => "Paper Fold (With Animation)",
            'type' => 'paper-fold',
            'reg' => '<li id="_social_slug_theme5"><div><a href="#"><span class="wpsocialarrow-theme5-_social_slug_ hvr-wobble-vertical"></span></a></div></li>',
            'prefix' => '<div id="wpsocialarrow-theme5" class=""><nav><ul>',
            'postfix' => '</ul></div>',
            'is_locked' => 1
        ),
        "patent-6" => array(
            'id' => "6",
            'title' => "Branded (With Animation)",
            'type' => 'branded',
            'reg' => '<li id="_social_slug_theme6" class="dr-margin"><div><a href="#"><span class="wpsocialarrow-theme6-_social_slug_ hvr-outline-in_social_slug_"></span></a></div></li>',
            'prefix' => '<div id="wpsocialarrow-theme6" class=""><nav><ul>',
            'postfix' => '</ul></div>',
            'is_locked' => 1
        ),
        "patent-7" => array(
            'id' => "7",
            'title' => "Radiused (With Animation)",
            'type' => 'radiused',
            'reg' => '<li id="_social_slug_theme7" class="dr-margin"><div><a href="#"><span class="wpsocialarrow-theme7-_social_slug_ rotate_social_slug_"></span></a></div></li>',
            'prefix' => '<div id="wpsocialarrow-theme7" class=""><nav><ul>',
            'postfix' => '</ul></div>',
            'is_locked' => 1
        ),
        "patent-8" => array(
            'id' => "8",
            'title' => "Octagon (With Animation)",
            'type' => 'octagon',
            'reg' => '<li id="_social_slug_theme8" class="dr-margin"><div><a href="#"><span class="wpsocialarrow-theme8-_social_slug_ hvr-float-shadow"></span></a></div></li>',
            'prefix' => '<div id="wpsocialarrow-theme8" class=""><nav><ul>',
            'postfix' => '</ul></div>',
            'is_locked' => 1
        ),
        "patent-9" => array(
            'id' => "9",
            'title' => "Hanging (With Animation)",
            'type' => 'hanging',
            'reg' => '<li id="_social_slug_theme9" class="dr-margin"><div><a href="#"><span class="wpsocialarrow-theme9-_social_slug_ hvr-wobble-bottom"></span></a></div></li>',
            'prefix' => '<div id="wpsocialarrow-theme9" class=""><nav><ul>',
            'postfix' => '</ul></div>',
            'is_locked' => 1
        ),
        "patent-10" => array(
            'id' => "10",
            'title' => "Tricon (With Animation)",
            'type' => 'tricon',
            'reg' => '<li id="_social_slug_theme10" class="dr-margin"><div><a href="#"><span class="wpsocialarrow-theme10-_social_slug_ hvr-buzz-out"></span></a></div></li>',
            'prefix' => '<div id="wpsocialarrow-theme10" class=""><nav><ul>',
            'postfix' => '</ul></div>',
            'is_locked' => 1
        ),
        "patent-11" => array(
            'id' => "11",
            'title' => "Hollow (With Animation)",
            'type' => 'hollow',
            'reg' => '<li id="_social_slug_theme11" class="dr-margin"><div><a href="#"><span class="wpsocialarrow-theme11-_social_slug_ rotate_social_slug_"></span></a></div></li>',
            'prefix' => '<div id="wpsocialarrow-theme11" class=""><nav><ul>',
            'postfix' => '</ul></div>',
            'is_locked' => 1
        ),
        "patent-12" => array(
            'id' => "12",
            'title' => "Social Lambs (With Animation)",
            'type' => 'sociallambs',
            'reg' => '<li id="_social_slug_theme12" class="dr-margin"><div><a href="#"><span class="wpsocialarrow-theme12-_social_slug_ hvr-pop"></span></a></div></li>',
            'prefix' => '<div id="wpsocialarrow-theme12" class=""><nav><ul>',
            'postfix' => '</ul></div>',
            'is_locked' => 1
        ),
        "patent-13" => array(
            'id' => "13",
            'title' => "3D Icons (With Animation)",
            'type' => '3dicons',
            'reg' => '<li id="_social_slug_theme17" class="dr-margin"><div><a href="#"><span class="wpsocialarrow-theme17-_social_slug_ hvr-grow-rotate"></span></a></div></li>',
            'prefix' => '<div id="wpsocialarrow-theme17" class=""><nav><ul>',
            'postfix' => '</ul></div>',
            'is_locked' => 1
        ),
        "patent-14" => array(
            'id' => "14",
            'title' => "White Stitched Border (With Animation)",
            'type' => 'whitestitchedborder',
            'reg' => '<li id="_social_slug_theme14" class="dr-margin"><div><a href="#"><span class="wpsocialarrow-theme14-_social_slug_ hvr-wobble-skew"></span></a></div></li>',
            'prefix' => '<div id="wpsocialarrow-theme14" class=""><nav><ul>',
            'postfix' => '</ul></div>',
            'is_locked' => 1
        ),
        "patent-15" => array(
            'id' => "15",
            'title' => "White Rounded (With Animation)",
            'type' => 'whiterounded',
            'reg' => '<li id="_social_slug_theme15" class="dr-margin"><div><a href="#"><span class="wpsocialarrow-theme15-_social_slug_ hvr-bounce-out"></span></a></div></li>',
            'prefix' => '<div id="wpsocialarrow-theme15" class=""><nav><ul>',
            'postfix' => '</ul></div>',
            'is_locked' => 1
        )
    );    

    $social_buttons = array(
        array(
            'title' => "Facebook",
            'name' => 'facebook',
            'class' => 'facebook'
        ),
        array(
            'title' => "Twitter",
            'name' => 'twitter',
            'class' => 'twitter'
        ),
		array(
            'title' => "WhatsApp",
            'name' => 'whatsapp',
            'class' => 'whatsapp'
        ),
		array(
            'title' => "Email",
            'name' => 'email',
            'class' => 'email'
        ),
        array(
            'title' => "LinkedIn",
            'name' => 'linkedin',
            'class' => 'linkedin'
        ),
		array(
            'title' => "Telegram",
            'name' => 'telegram',
            'class' => 'telegram'
        ),
		array(
            'title' => "Tumblr",
            'name' => 'tumbler',
            'class' => 'tumbler'
        ),
		array(
            'title' => "Line",
            'name' => 'line',
            'class' => 'line'
        ),
		array(
            'title' => "Pinterest",
            'name' => 'pinterest',
            'class' => 'pinterest'
        ),
		array(
            'title' => "Reddit",
            'name' => 'reddit',
            'class' => 'reddit'
        ),array(
            'title' => "VKontakte",
            'name' => 'vkontakte',
            'class' => 'vkontakte'
        ),
		array(
            'title' => "WeChat",
            'name' => 'wechat',
            'class' => 'wechat'
        )
    );
    ?>
    <!-- Top Header Brand Bra -->
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <div class="social-button-box">
        <form method="post" action="options.php">
        <?php settings_fields('wpssi-settings-group'); 	?>

        <div class="wrap wrap-general social-page">
            <h2>Social Share Icons</h2>
			<?php 
			$wpssi_settings_update = get_transient('settings_wpssi_update');
			if ( $wpssi_settings_update != '') {			
				echo '<div id="message" class="updated inline"><p><strong>' . esc_html( $wpssi_settings_update ) . '</strong></p></div>';
				set_transient( 'settings_wpssi_update', '', 30 );
			}		
			?>
            <div class="social-navigation-tab">
                <span>
                    <a href="#social-theme" class="nav-tab active">Design</a>
                    <a href="#social-settings" class="nav-tab">Settings</a>
                </span>
            </div>

            <div class="tab-content">
                <div id="social-theme" class="social-tab active">
                    <div class="social-buttons abs-position">
                        <a href="javascript:;" class="next-step">NEXT</a>
                        <div class="clear"></div>
                    </div>
                    <div class="tab-pane active" id="a">
                        <div class="sb-box">
                            <div class="sb-row">
                                <div class="sb-row-box-2">
                                    <div class="sb-title">Select Social Networks</div>
                                    <div class="sb-inner-box">
                                        <div class="social-items">
                                        <div id="wpsocialarrow-social-network-selection">
                                            <ul class="social-channel-list" id="social-channel-list">
                                                <?php foreach($social_buttons as $button) { ?>
                                                    <li class="soc-<?php echo $button['class'] ?> <?php echo get_option('wpsocialarrow-'.$button['name'])=="wpsocialarrow-".$button['name']?"active ui-draggable-disabled":"" ?>" data-social="soc-<?php echo $button['class'] ?>">
                                                        <div id="wpsocialarrow-selection">
                                                            <label for="soc-<?php echo $button['class'] ?>"><span class="premio-<?php echo $button['class'] ?>" title="<?php echo $button['title'] ?>"></span></label>
                                                            <input id="<?php echo $button['name'] ?>" class="<?php echo $button['name'] ?>cb social-selection" type="checkbox" name="wpsocialarrow-<?php echo $button['name'] ?>" value="wpsocialarrow-<?php echo $button['name'] ?>" <?php checked('wpsocialarrow-'.$button['name'], get_option('wpsocialarrow-'.$button['name'])); ?> >
                                                            <span></span>
                                                        </div>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
										<p class="social-network-upgrade-to-pro sb-inner-box sb-pro-bg" style="display:none;">Get unlimited social networks in the Pro plan <a href="<?php echo PR_SOCIAL_UPGRADE_LINK?>"  target="_blank" class="locked-button"><i class="fa fa-lock"></i> Upgrade Now</a></p>
                                    </div>
                                    </div>
                                </div>
                                <div class="sb-row-box-2">
                                    <div class="sb-title">Preview</div>
                                        <div class="sb-inner-box">
                                            <div class="social-items" id="social-selected-list" >
                                                <ul class="social-channel-list" id="social-channel-selected-list">

                                                </ul>
												<div class="preview-get-widget sb-inner-box" style="display:none">
													<div class="sb-row-box-1 text-left">
														Get Widget &nbsp;
														(<a class="upgrade-link" target="_blank" href="<?php echo PR_SOCIAL_UPGRADE_LINK ?>">Upgrade now</a> to remove the credit)
													</div>													
													<div class="sb-row-box-2 text-center">
														
													</div>
													<div class="clear"></div>													
												</div>													
                                            </div>											
                                        </div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </div>
                    </div>
                    <div class="sb-space"></div>
                    <div class="sb-box">
                        <div class="sb-row">
                            <div class="sb-row-box-12">
                                <div class="sb-title">Select Icon Style</div>
                            </div>
                            <?php foreach($social_pattern as $social) {
                                echo '<div class="sb-row-box-2">';
                                echo '<div class="sb-inner-box sb-social-icons" id="includedskin'.$social['id'].'">';
                                echo '<div class="social-top">';
                                echo '<div class="social-input-box">';
                                if( $social['is_locked'] == 0) {
                                    $checked = checked($social['type'], get_option('wpsocialarrow-skins'), false);
                                    echo '<input class="custom-radio" id="' . $social['type'] . '" type="radio" name="wpsocialarrow-skins" value="' . $social['type'] . '"  ' . $checked . ' />';
                                    echo '<label class="wpsocialarrow-skins-label" for="' . $social['type'] . '"> '.$social['title'].' </label>';
                                } else {
                                    echo '<input class="custom-radio" id="' . $social['type'] . '" type="radio" disabled />';
                                    echo '<label class="wpsocialarrow-skins-label" for="' . $social['type'] . '"> '.$social['title'].' </label>';
                                    echo '<a class="locked-button" target="_blank"  href="'.PR_SOCIAL_UPGRADE_LINK.'"><i class="fa fa-lock"></i> Upgrade</a>';
                                }
                                echo '</div>';
                                echo '</div>';
                                echo $social['prefix'];
                                $reg = $social['reg'];
                                foreach($social_widgets as $widget) {                                    
                                    $link = str_replace("_social_slug_", $widget['slug'], $reg);
                                    $link = str_replace("_social_title_", $widget['title'], $link);                                    
                                    echo $link;
                                }
                                echo $social['postfix'];
                                echo '<div class="clear"></div>';
                                echo '</div>';
                                echo '<div class="sb-widget"></div>';
                                echo '</div>';
                            } ?>
                            <div class="clear"></div>
                            <div class="social-buttons">
                                <a href="javascript:;" class="next-step">NEXT</a>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="social-settings" class="social-tab">
                    <div class="social-buttons abs-position">
                        <a href="javascript:;" class="back-step">Back</a>
                        <input type="submit" class="button-primary" value="<?php _e('Save Changes'); ?>"/>
                    </div>
                    <div class="tab-pane active">
                        <div class="sb-box">
                            <div class="sb-row">
                                <div class="sb-row-box-12">
                                    <div class="sb-title">General Settings</div>
                                </div>
                                <div class="sb-row-box-2">
                                    <div class="social-input">
                                        <div class="social-input-left">
                                            <label>Enable Social Share Plugin:</label>
                                        </div>
                                        <div class="social-input-right">
                                            <div class="switch">
                                                <input id="wpsocialarrow-enable-plugin" name="wpsocialarrow-enable-plugin"
                                                       class="cmn-toggle cmn-toggle-round" type="checkbox"
                                                       value='1'<?php checked(1, get_option('wpsocialarrow-enable-plugin')); ?> >
                                                <label for="wpsocialarrow-enable-plugin"></label>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="social-input">
                                        <div class="social-input-left">
                                            <label>Display On:</label>
                                        </div>
                                        <div class="social-input-right">
                                            <div class="switch text-right">
                                                Posts
                                                <input id="wpsocialarrow-enable-post" name="wpsocialarrow-enable-post" class="cmn-toggle cmn-toggle-round" type="checkbox" value='1'<?php checked(1, get_option('wpsocialarrow-enable-post')); ?>>
                                                <label for="wpsocialarrow-enable-post"></label>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="social-input no-padding">
                                        <div class="sb-inner-box1">
                                            <div class="sb-row">                                                
                                                <div class="sb-row-box-12">
                                                    <div class="social-input-chkbx">
                                                        <div class="social-input-left">
                                                            <label>Display On:</label>
                                                        </div>
                                                        <div class="social-input-right text-right">
                                                            <div class="switch text-right">
																Pages
																<input id="wpsocialarrow-enable-page" value="1" name="wpsocialarrow-enable-page" class="cmn-toggle cmn-toggle-round" type="checkbox" <?php checked(1, get_option('wpsocialarrow-enable-page')); ?> >
																<label for="wpsocialarrow-enable-page"></label>
															</div>
                                                        </div>
                                                        <div class="clear"></div>
                                                    </div>
                                                    <div class="social-input-chkbx">
                                                        <div class="social-input-left">
                                                            <label>Display On:</label>
                                                        </div>
                                                        <div class="social-input-right text-right">
                                                            <div class="switch text-right">
																Homepage
																<input  id="wpsocialarrow-enable-home" value="1" name="wpsocialarrow-enable-home" class="cmn-toggle cmn-toggle-round" type="checkbox" <?php checked(1, get_option('wpsocialarrow-enable-home')); ?> >
																<label for="wpsocialarrow-enable-home"></label>
															</div>
                                                        </div>
                                                    </div>
                                                    <div class="clear"></div>
                                                </div>
                                                <div class="clear"></div>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <div class="sb-row-box-2">
                                    <div class="social-input">
                                        <div class="social-input-left">
                                            <label>Default Call-To-Action:</label>
                                        </div>
                                        <div class="social-input-right">
                                            <select id="wpsocialarrow-message-selection" name="wpsocialarrow-message-selection">
                                                <option value="None" <?php selected(get_option('wpsocialarrow-message-selection'), 'None'); ?>>
                                                    None
                                                </option>
                                                <option value="Share this post"<?php selected(get_option('wpsocialarrow-message-selection'), 'Share this post'); ?>   >
                                                    Share this post
                                                </option>
                                                <option value="Show Some Love for us!"<?php selected(get_option('wpsocialarrow-message-selection'), 'Show Some Love for us!'); ?>   >
                                                    Show Some Love for us!
                                                </option>
                                                <option value="Sharing is Caring"<?php selected(get_option('wpsocialarrow-message-selection'), 'Sharing is Caring'); ?>   >
                                                    Sharing is Caring
                                                </option>
                                                <option value="Hey check this out" <?php selected(get_option('wpsocialarrow-message-selection'), 'Hey check this out'); ?>>
                                                    Hey check this out
                                                </option>
                                                <option value="Share this!"<?php selected(get_option('wpsocialarrow-message-selection'), 'Share this!'); ?>   >
                                                    Share this!
                                                </option>
                                                <option value="Share the knowledge"<?php selected(get_option('wpsocialarrow-message-selection'), 'Share the knowledge'); ?>   >
                                                    Share the knowledge
                                                </option>
                                                <option value="Wanna share this?"<?php selected(get_option('wpsocialarrow-message-selection'), 'Wanna share this?'); ?>   >
                                                    Wanna share this?
                                                </option>
                                                <option value="Custom Message"<?php selected(get_option('wpsocialarrow-message-selection'), 'Custom Message'); ?>   >
                                                    Custom Message
                                                </option>
                                            </select>
                                            <div class="clear"></div>                                            
                                        </div>
                                        <div class="clear"></div>
										<div class="custom-message sb-inner-box sb-pro-bg"">
											<input id="wpsocialarrow-custom-default-message" name="" class="input-message" type="text" disabled id="wpsocialarrow-custom-size-span">
											<a class="locked-button" target="_blank" href="<?php echo PR_SOCIAL_UPGRADE_LINK ?>"><i class="fa fa-lock"></i> Upgrade</a><br/><p class="description">(Add your custom message if don't use default ones)</p>
										</div>
                                    </div>

                                    <div class="social-input">
                                        <div class="social-input-left">
                                            <label>Call-To-Action Font:</label>
                                        </div>
                                        <div class="social-input-right">
                                            <input name="wpsocialarrow-gfonts" id="wpsocialarrow_gfonts" class="wpsocialarrow_msgfont" type="text" value="<?php echo get_option("wpsocialarrow-gfonts", true) ?>"/>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
									
									<div class="social-input sb-inner-box sb-pro-bg">
										<div class="sb-row-box-3 text-center">
											&nbsp;
										</div>
										<div class="sb-row-box-3 text-center">
										</div>
										<div class="sb-row-box-3 text-center">
											<a class="locked-button" target="_blank" href="<?php echo PR_SOCIAL_UPGRADE_LINK ?>"><i class="fa fa-lock"></i> Upgrade</a>
										</div>
										<div class="clear"></div>
                                        <div class="social-input-left">
                                            <label>Remove Credit:</label>
                                        </div>
                                        <div class="social-input-right">
                                            <input  disabled id="wpsocialarrow-disable-credit" value="1" name="wpsocialarrow-disable-credit" class="cmn-toggle cmn-toggle-round" type="checkbox" >
											<label for="wpsocialarrow-disable-credit"></label>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="sb-space"></div>
                        <div class="sb-box">
                            <div class="sb-position-box">
                                <div class="sb-row">
                                    <div class="sb-row-box-4">
                                        <div class="sb-title text-left">Position</div>										
                                        <label class="input-label" for="afterpost">
                                            <img src='<?php echo plugins_url('admin/images/after-post.png', __FILE__); ?>'/>
                                        </label>
                                         <input id="afterpost" type="checkbox" class="custom-checkbox" name="wpsocialarrow-positioning" value="afterpost" <?php checked('afterpost', get_option('wpsocialarrow-positioning')); ?> />
                                        <label for="afterpost"></label>
                                    </div>
                                    <div class="sb-row-box-4">
                                        <div class="sb-title">&nbsp;</div>
                                        <label class="input-label" for="beforepost">
                                            <img src='<?php echo plugins_url('admin/images/before-post.png', __FILE__); ?>'/>
                                        </label>
                                        <input id="beforepost" type="checkbox" class="custom-checkbox" name="wpsocialarrow-beforepost" value="beforepost" <?php checked('beforepost', get_option('wpsocialarrow-beforepost')); ?>  />
                                        <label for="beforepost"></label>
                                    </div>
                                    <div class="sb-row-box-4">
                                        <div class="sb-title"></div>
                                        <label class="input-label" for="flaotingleft">
                                            <img src='<?php echo plugins_url('admin/images/floating-left.png', __FILE__); ?>'/>
                                        </label>
                                        <input id="flaotingleft"  type="checkbox" class="custom-checkbox" name="wpsocialarrow-floatingleft" value="floatingleft" <?php checked('floatingleft', get_option('wpsocialarrow-floatingleft')); ?>  />
                                        <label for="flaotingleft"></label>
                                    </div>
                                    <div class="sb-row-box-4">
                                        <div class="sb-title">
                                        <div class="clear"></div>
                                        </div>
                                        <label class="input-label" for="floatingright">
                                            <img src='<?php echo plugins_url('admin/images/floating-right.png', __FILE__); ?>'/>
                                        </label>
                                         <input id="floatingright"  type="checkbox" class="custom-checkbox" name="wpsocialarrow-floatingright" value="floatingright" <?php checked('floatingright', get_option('wpsocialarrow-floatingright')); ?>  />
                                        <label for="floatingright"></label>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                        <div class="sb-space"></div>
                        <div class="sb-box">
                            <div class="sb-alignment-box">
                                <div class="sb-row">
                                    <div class="sb-row-box-12">
                                        <div class="sb-title">Alignment</div>
                                    </div>
                                    <div class="clear"></div>
                                    <div class="sb-row-box-3 text-center">
                                        <label class="input-label" for="alignleft">
                                            <img src='<?php echo plugins_url('admin/images/align-left.png', __FILE__); ?>'/>
                                        </label><br/>
                                        <input class="custom-radio" id="alignleft" type="radio" name="wpsocialarrow-alignment" value="alignleft"<?php checked('alignleft', get_option('wpsocialarrow-alignment')); ?>/>
                                        <label for="alignleft"></label>
                                    </div>
                                    <div class="sb-row-box-3 text-center">
                                        <label class="input-label" for="aligncenter">
                                            <img src='<?php echo plugins_url('admin/images/align-center.png', __FILE__); ?>'/>
                                        </label><br/>
                                        <input class="custom-radio" id="aligncenter" type="radio" name="wpsocialarrow-alignment" value="aligncenter" <?php checked('aligncenter', get_option('wpsocialarrow-alignment')); ?>/>
                                        <label for="aligncenter"></label>
                                    </div>
                                    <div class="sb-row-box-3 text-center">
                                        <label class="input-label" for="alignright">
                                            <img src='<?php echo plugins_url('admin/images/align-right.png', __FILE__); ?>'/>
                                        </label><br/>
                                        <input class="custom-radio" id="alignright" type="radio" name="wpsocialarrow-alignment" value="alignright" <?php checked('alignright', get_option('wpsocialarrow-alignment')); ?>/>
                                        <label for="alignright"></label>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                        <div class="sb-space"></div>
                        <div class="social-buttons">
                            <a href="javascript:;" class="back-step">Back</a>
                            <input type="submit" class="button-primary" value="<?php _e('Save Changes'); ?>"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input id="wpsocialarrow-selected-skin" type="hidden" value="<?php echo get_option("wpsocialarrow-skins"); ?>"/>
        <input id="wpsocialarrow-selected-social-network1" name="wpsocialarrow-selcetion-network" type="hidden" value="<?php echo get_option("wpsocialarrow-facebook"); ?>">
        <input id="wpsocialarrow-selected-social-network2" name="wpsocialarrow-selcetion-network" type="hidden" value="<?php echo get_option("wpsocialarrow-twitter"); ?>">
        <input id="wpsocialarrow-selected-social-network3" name="wpsocialarrow-selcetion-network" type="hidden" value="<?php echo get_option("wpsocialarrow-google"); ?>">
        <input id="wpsocialarrow-selected-social-network4" name="wpsocialarrow-selcetion-network" type="hidden" value="<?php echo get_option("wpsocialarrow-linkedin"); ?>">
        <input id="wpsocialarrow-selected-social-network5" name="wpsocialarrow-selcetion-network" type="hidden" value="<?php echo get_option("wpsocialarrow-pinterest"); ?>">
        <input id="wpsocialarrow-selected-social-network6" name="wpsocialarrow-selcetion-network" type="hidden" value="<?php echo get_option("wpsocialarrow-whatsapp"); ?>">
        <input id="wpsocialarrow-selected-social-network7" name="wpsocialarrow-selcetion-network" type="hidden" value="<?php echo get_option("wpsocialarrow-reddit"); ?>">
        <input id="wpsocialarrow-selected-social-network8" name="wpsocialarrow-selcetion-network" type="hidden" value="<?php echo get_option("wpsocialarrow-telegram"); ?>">
        <input id="wpsocialarrow-selected-social-network9" name="wpsocialarrow-selcetion-network" type="hidden" value="<?php echo get_option("wpsocialarrow-tumbler"); ?>">
        <input id="wpsocialarrow-selected-social-network10" name="wpsocialarrow-selcetion-network" type="hidden" value="<?php echo get_option("wpsocialarrow-vkontakte"); ?>">
        <input id="wpsocialarrow-selected-social-network11" name="wpsocialarrow-selcetion-network" type="hidden" value="<?php echo get_option("wpsocialarrow-wechat"); ?>">
        <input id="wpsocialarrow-selected-social-network12" name="wpsocialarrow-selcetion-network" type="hidden" value="<?php echo get_option("wpsocialarrow-email"); ?>">
        <input id="wpsocialarrow-selected-social-network12" name="wpsocialarrow-selcetion-network" type="hidden" value="<?php echo get_option("wpsocialarrow-line"); ?>">
        <input id="wpsocialarrow-facebook-url" type="hidden" value="http://www.facebook.com/sharer.php?u=".<?php echo $url; ?> >
        <input id="wpsocialarrow-twitter-url" type="hidden" value="https://twitter.com/share?url=".<?php echo $url; ?>>
        <input id="wpsocialarrow-linkedin-url" type="hidden" value="http://www.linkedin.com/shareArticle?url=".<?php echo $url; ?>>
        <input id="wpsocialarrow-google-url" type="hidden" value="https://plus.google.com/share?url".<?php echo $url; ?>>
        <input id="wpsocialarrow-pinterest-url" type="hidden" value="https://pinterest.com/pin/create/button/?url".<?php echo $url; ?>>
        <input id="wpsocialarrow-custom-order" type="hidden" name="wpsocialarrow-custom-order" value="<?php echo get_option("wpsocialarrow-custom-order"); ?>">
        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('social_share_button_popup') ?>">
    </form>
    </div>
<?php
    include_once 'help.php';
}

if(!function_exists('wpssi_sanitize_options')) {
    function wpssi_sanitize_options($value)
    {
        $value = stripslashes($value);
        $value = sanitize_text_field($value);
        return $value;
    }
}

if(!function_exists('wpssi_sanitize_options_int')) {
    function wpssi_sanitize_options_int($value) {
        $value = stripslashes($value);
        $value = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
        return $value;
    }
}
?>