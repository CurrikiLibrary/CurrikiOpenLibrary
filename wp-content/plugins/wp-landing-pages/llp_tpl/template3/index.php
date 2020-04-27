<?php
// Landing Page Name: Free Access
// Preview Image: tmp_3.jpg
// Template File: template3
$tpl_id = 'template3';
$tpl_dir_url = llp_URL . '/llp_tpl/' . $tpl_id . '/';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <title><?php wp_title(); ?></title>
        <!-- Style.css  -->
        <link rel="stylesheet" href="<?php echo llp_URL; ?>/llp_tpl/<?php echo $llp_tpl_id; ?>/style.css" type="text/css" media="screen">
        <!-- Style.css  -->
        <meta charset="utf-8">
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
        <meta content="width=device-width" name="viewport">
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,400italic,600italic%7COswald:400,300,700&amp;subset=latin,latin-ext" type="text/css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Lora" rel="stylesheet" type="text/css">
        <?php
        //wp_head();
        include llp_PATH . '/llp_tpl/llp_imp_js_css.php';
        ?>
    </head>
    <body>
        <?php
        if (is_user_logged_in()) {
            if ($do == 'create' || $do == 'edit') {
                include llp_PATH . '/llp_tpl/customized_bar.php';
            } else {
                wplp_setPostViews(get_the_ID());
            }
        }
        $llp_bg_1 = get_post_meta($post->ID, 'llp_bg_1', true);

        if (empty($llp_bg_1)) {
            $llp_bg_1 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/bg.png';
        }
        $llp_logo_1 = get_post_meta($post->ID, 'llp_logo_1', true);
        $llp_logo_1_image_link = get_post_meta($post->ID, 'llp_logo_1_image_link', true);
        $llp_tpl3_headline = get_post_meta($post->ID, 'llp_tpl3_headline', true);
        $llp_tpl3_disclaimer = get_post_meta($post->ID, 'llp_tpl3_disclaimer', true);

        $llp_tpl3_button_lnk = get_post_meta($post->ID, 'llp_tpl3_button_lnk', true);
        // $llp_tpl3_footer_menu = get_post_meta($post->ID, 'llp_tpl3_footer_menu', true);
        $llp_tpl4_form = get_post_meta($post->ID, 'llp_tpl4_form', true);
        $atform = $llp_tpl4_form;
        /* $llp_lnk_3 = get_post_meta($post->ID,'llp_lnk_3',true); */
        if (empty($llp_logo_1)) {
            $llp_logo_1 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/logo.png';
        }
        if (empty($llp_logo_1_image_link)) {
            $llp_logo_1_image_link = 'http://google.com';
        }

        $llp_tpl3_headline_default = '"The 7 Dead Simple Proven Steps That Led To This 27 Year Old College Dropout Profiting More Than $1,412 A Day <u>WITHOUT Any Affiliates</u>"';
        $llp_tpl3_headline = get_post_meta($post->ID, 'llp_tpl3_headline', true);
        if (empty($llp_tpl3_headline)) {
            $llp_tpl3_headline = $llp_tpl3_headline_default;
        }
        $llp_tpl3_text1_default = 'Enter Just Your Email Below And Then Click The Button For Instant Access';
        $llp_tpl3_text1 = get_post_meta($post->ID, 'llp_tpl3_text1', true);
        if (empty($llp_tpl3_text1)) {
            $llp_tpl3_text1 = $llp_tpl3_text1_default;
        }
        if (empty($llp_tpl3_disclaimer)) {
            $llp_tpl3_disclaimer = 'Copyright 2013, 7Steps-42k.com All Rights Reserved.';
        }
        $llp_btn_1 = get_post_meta($post->ID, 'llp_btn_1', true);
        if (empty($llp_btn_1)) {
            $llp_btn_1 = '>> Click Here For Instant Access <<';
        }
        $llp_btn_1_link_a = get_post_meta($post->ID, 'llp_btn_1_link_a', true);
        if (empty($llp_btn_1_link_a)) {
            $llp_btn_1_link_a = 'http://google.com';
        }
        $llp_btn_1_link_a_open = get_post_meta($post->ID, 'llp_btn_1_link_a_open', true);
        if (empty($llp_btn_1_link_a_open)) {
            if ($llp_btn_1_link_a_open == '1') {
                $llp_btn_1_link_a_open = '_blank';
            } else {
                $llp_btn_1_link_a_open = '';
            }
        }
        ?>

        <header>
            <div class="shell">
                <div id="logo">
                    <a id="llp_logo_1" data-heading="Update Logo" data-image-link="<?php echo $llp_logo_1_image_link; ?>" data-id="2" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_logo_1; ?>" border="0"
                       alt="logo">' data-wp_custom_field='llp_logo_1' href="<?php echo $llp_logo_1_image_link; ?>"class="dspblk">
                        <img alt="" src="<?php echo $llp_logo_1; ?>"></a>
                </div>
                <div class="clear"></div>
            </div>
        </header>
        <section id="yellow-bar" >
            <div class="shell">
                <div id="llp_tpl3_headline"  data-heading="Content" data-id="3" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl3_headline)); ?>' data-wp_custom_field='llp_tpl3_headline'><?php echo stripslashes($llp_tpl3_headline); ?>
                </div>
            </div>
            <div id="arrow-down"></div>
            <div id="arrow-bg"></div>
        </section>
        <section id="purchase"><div class="shell">
                <div class="letter">
                </div>
                <div class="clear"></div>
                <div class="button-holder">
                    <a id="llp_btn_1" data-heading="Edit button" data-id="4" data-edit="true" data-type='link' data-default='<?php echo $llp_btn_1; ?>' data-wp_custom_field='llp_btn_1' data-link='<?php echo $llp_btn_1_link_a; ?>' data-target='1' href="<?php echo $llp_btn_1_link_a; ?>" class="btn button-holder llp_box" <?php if ($llp_btn_1_link_a_open != "") { ?>target="_blank"<?php } ?>><?php echo $llp_btn_1; ?></a>
                </div>
                <div class="no-thanks"></div>
            </div>
            <div id="inline1" style="max-width:400px;display: none;">
                <div class="form" id="llp_tpl4_form" data-heading="Optin Form" data-id="5" data-edit="true" data-type='form' data-wp_custom_field='llp_tpl4_form'>
                    <?php if ($llp_tpl4_form) { ?>
                        <form action="<?php echo $atform['_optthemes_optinformurl']; ?>" method="post">
                            <?php
                            if ($atform['optintext']) {
                                foreach ($atform['optintext'] as $atform_fields => $key) {
                                    if ($atform['optincheck'][$atform_fields] == 'yes') {
                                        ?>
                                        <input placeholder="<?php echo $key; ?>" onfocus="this.placeholder = '<?php echo $key; ?>'" title="<?php echo $key; ?>" value="" name="<?php echo $atform_fields; ?>" type="<?php echo $atform['optintype'][$atform_fields]; ?>">
                                        <?php
                                    }
                                }
                            }
                            ?>
                            <?php echo $atform['_optthemes_webformhiddenhtml']; ?>
                            <button type="submit" class="btn blue-btn"><?php echo $atform['_optthemes_optinformsubmit']; ?></button>
                        </form>
                    <?php } else { ?>
                        <form></form>
                        <form action="#" method="post" onsubmit="" target="_top">
                            <input placeholder="Enter a Valid Email Here..." onfocus="this.placeholder = 'Enter a Valid Email Here...'" title="Enter a Valid Email Here..." value="" type="email" id="email" data-lb-inputemail="true"><button type="submit" class="btn blue-btn">Get Instant Access</button>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </section>
        <footer>
            <div class="shell">
                <br>
                <span id="llp_tpl3_disclaimer" data-heading="Disclaimer" data-id="6" data-edit="true" data-type='text' data-default='<?php echo $llp_tpl3_disclaimer; ?>' data-wp_custom_field='llp_tpl3_disclaimer' class="dspblk"><?php echo $llp_tpl3_disclaimer; ?></span>
            </div>
        </footer>
        <div id="myModal" class="popup-container reveal-modal">
            <div id="register_popup" class="popup">
                <div id="pop-head">
                    <div class="pop-headline">
                        <div id="llp_tpl3_text1" data-heading="Content" data-id="70" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl3_text1)); ?>' data-wp_custom_field='llp_tpl3_text1'><?php echo stripslashes($llp_tpl3_text1); ?>
                        </div>
                    </div>
                </div>
                <div class="arrow-down"></div>
                <form action="#" method="POST" onsubmit="#" target="_top">
                    <div class="row">
                        <p>STEP 1</p><p class="small">Enter your email below</p>
                        <input onblur="if ($(this).val() == '') {
                                    $(this).val($(this).attr('title'));
                                }" title="Enter a valid email here" value="Enter a valid email here" onfocus="if ($(this).val() == $(this).attr('title')) {
                                            $(this).val('')
                                        }" type="email" id="email" data-lb-inputemail="true" name="email"><div class="clear"></div>
                    </div>
                    <div class="row">
                        <p>STEP 2</p><p class="small">Click the button below</p>
                        <div class="clear"></div>
                        <div class="button-holder-pop">
                            <button type="submit" class="btn-pop"><span>Click Here For Instant Access</span> <img width="10" alt="" height="8" src=""></button>
                        </div>
                        <div class="clear"></div>
                    </div>
                </form>
                <div id="pop-footer">
                    <p><b>Privacy Policy:</b> We hate SPAM and promise to keep your<br> email address safe.</p>
                </div>
            </div>
        </div>
        <?php
        if (is_user_logged_in()) {
            if ($do == 'create' || $do == 'edit') {
                ?>
                <?php
            } else {
                $wplp_custom_code_box = stripslashes(get_post_meta(get_the_ID(), 'wplp_custom_code_box', true));
                echo $wplp_custom_code_box;
            }
        }
        ?>
    </body>
</html>