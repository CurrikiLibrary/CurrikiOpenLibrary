<?php
// Landing Page Name: Basic Squeeze Page (Right Aligned)
// Preview Image: tmp_11.jpg
// Template File: template11
$tpl_id = 'template11';
$tpl_dir_url = llp_URL . '/llp_tpl/' . $tpl_id . '/';
?>
<!DOCTYPE html>
<html>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <head>
        <title><?php wp_title(); ?></title>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">
        <link href="<?php echo llp_URL; ?>/llp_tpl/<?php echo $llp_tpl_id; ?>/css/style.css" rel="stylesheet">
        <?php include llp_PATH . '/llp_tpl/llp_imp_js_css.php'; ?>
    <body>
        <?php
        if (is_user_logged_in()) {
            if ($do == 'create' || $do == 'edit') {
                include llp_PATH . '/llp_tpl/customized_bar.php';
            }
        }
        $llp_logo_1 = get_post_meta($post->ID, 'llp_logo_1', true);
        if (empty($llp_logo_1)) {
            $llp_logo_1 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/logo.png';
        }
        $llp_logo_1_image_link = get_post_meta($post->ID, 'llp_logo_1_image_link', true);
        if (empty($llp_logo_1_image_link)) {
            $llp_logo_1_image_link = 'http://google.com';
        }
        $llp_bg_1 = get_post_meta($post->ID, 'llp_bg_1', true);
        $llp_tpl4_form = get_post_meta($post->ID, 'llp_tpl4_form', true);
        $atform = $llp_tpl4_form;
        if (empty($llp_bg_1)) {
            $llp_bg_1 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/bg.jpg';
        }
        $llp_button_1 = get_post_meta($post->ID, 'llp_button_1', true);
        if (empty($llp_button_1)) {
            $llp_button_1 = 'Download The Report';
        }
        $llp_button_1_link_a = get_post_meta($post->ID, 'llp_button_1_link_a', true);
        if (empty($llp_button_1_link_a)) {
            $llp_button_1_link_a = 'http://google.com';
        }
        $llp_button_1_link_a_open = get_post_meta($post->ID, 'llp_button_1_link_a_open', true);
        if (empty($llp_button_1_link_a_open)) {
            if ($llp_button_1_link_a_open == '1') {
                $llp_button_1_link_a_open = '_blank';
            } else {
                $llp_button_1_link_a_open = '';
            }
        }
        $llp_tpl11_5P_text1_default = '<h3>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec efficitur sollicitudin leo et pulvinar."</h3>';
        $llp_tpl11_5P_text1 = get_post_meta($post->ID, 'llp_tpl11_5P_text1', true);
        if (empty($llp_tpl11_5P_text1)) {
            $llp_tpl11_5P_text1 = $llp_tpl11_5P_text1_default;
        }
        $llp_tpl11_5P_text2_default = ' <p>Enter your email address to get this free report</p>';
        $llp_tpl11_5P_text2 = get_post_meta($post->ID, 'llp_tpl11_5P_text2', true);
        if (empty($llp_tpl11_5P_text2)) {
            $llp_tpl11_5P_text2 = $llp_tpl11_5P_text2_default;
        }

        $llp_tpl11_5P_text3_default = '  <p>Your information will *never* be shared<br> or sold to a 3rd party.</p>';
        $llp_tpl11_5P_text3 = get_post_meta($post->ID, 'llp_tpl11_5P_text3', true);
        if (empty($llp_tpl11_5P_text3)) {
            $llp_tpl11_5P_text3 = $llp_tpl11_5P_text3_default;
        }
        $llp_tpl11_5P_text4_default = 'Legal Information Copyright 2013 © Company / All Rights Reserved';
        $llp_tpl11_5P_text4 = get_post_meta($post->ID, 'llp_tpl11_5P_text4', true);
        if (empty($llp_tpl11_5P_text4)) {
            $llp_tpl11_5P_text4 = $llp_tpl11_5P_text4_default;
        }
        ?>
        <style>
            body {
                <?php if (!empty($llp_bg_1)) { ?>
                    background-image:url("<?php echo $llp_bg_1; ?>");
                    -webkit-background-size: cover;
                    -moz-background-size: cover;
                    background-size: cover;
                <?php } ?>
            }
            #llp_bg_1 img {
                height:100px;
                width:100%;
                display:none;
            }
        </style>
        <?php
        if (is_user_logged_in()) {
            if ($do == 'create' || $do == 'edit') {
                ?>
                <a id="llp_bg_1" href="javascript:void(0);" data-heading="Update Background" data-id="1" data-edit="true" data-type='bgimage' data-default='<img src="<?php echo $llp_bg_1; ?>" border="0" alt="">' data-wp_custom_field='llp_bg_1' class="dspblk llp_btn">Change Background Image<img alt="" src="<?php echo $llp_bg_1; ?>"></a>
                <?php
            }
        }
        ?>
<!---<img src="<?php //echo llp_URL;      ?>/llp_tpl/<?php //echo $llp_tpl_id;      ?>/images/bg.jpg" class="bg" alt="" id="bgimg">--->
        <section id="wrapper">
            <section class="content-box type2">
                <a id="llp_logo_1" data-heading="Logo"  style="display:block;" text-align="center" data-image-link="#" data-id="2" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_logo_1; ?>" border="0" alt="logo"  style="display:block;">' data-wp_custom_field='llp_logo_1' href="#">
                    <img alt="logo" src="<?php echo $llp_logo_1; ?>"  >
                </a>     <section class="blackbox">
                    <div id="llp_tpl11_5P_text1" data-heading="Content" data-id="3" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl11_5P_text1)); ?>' data-wp_custom_field='llp_tpl11_5P_text1'><?php echo stripslashes($llp_tpl11_5P_text1); ?>
                    </div>
                    <div id="llp_tpl11_5P_text2" data-heading="Content" data-id="4" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl11_5P_text2)); ?>' data-wp_custom_field='llp_tpl11_5P_text2'><?php echo stripslashes($llp_tpl11_5P_text2); ?>
                    </div>
                    <div class="submit" style="">
                        <a id="llp_button_1" data-heading="Button" data-id="5" data-edit="true" data-type='link' data-default='<?php echo $llp_button_1; ?>' data-wp_custom_field='llp_button_1' data-link='<?php echo $llp_button_1_link_a; ?>' data-target='1' href="<?php echo $llp_button_1_link_a; ?>" class="submit llp_box" <?php if ($llp_button_1_link_a_open != "") { ?>target="_blank"<?php } ?>><?php echo $llp_button_1; ?></a>
                    </div>
                    <div id="inline1" style="max-width:400px;display: none;">
                        <div class="form" id="llp_tpl4_form" data-heading="Optin Form" data-id="6" data-edit="true" data-type='form' data-wp_custom_field='llp_tpl4_form'>
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
                                    <button type="submit" class="submit blue-btn"><?php echo $atform['_optthemes_optinformsubmit']; ?></button>
                                </form>
                            <?php } else { ?>

                                <form action="#" method="post" onsubmit="" target="_top">
                                    <input placeholder="Enter a Valid Email Here..." onfocus="this.placeholder = 'Enter a Valid Email Here...'" title="Enter a Valid Email Here..." value="" type="email" id="email" data-lb-inputemail="true">
                                    <button type="submit" class="submit blue-btn">Download The Report</button>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                    <div id="llp_tpl11_5P_text3" data-heading="Content" data-id="7" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl11_5P_text3)); ?>' data-wp_custom_field='llp_tpl11_5P_text3'><?php echo stripslashes($llp_tpl11_5P_text3); ?>
                    </div>
                </section>
                <footer class="footer">
                    <!--- <a href="h#" target="_new"> --> <div id="llp_tpl11_5P_text4" data-heading="Content" data-id="8" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl11_5P_text4)); ?>' data-wp_custom_field='llp_tpl11_5P_text4'><?php echo stripslashes($llp_tpl11_5P_text4); ?>
                    </div> <!--</a>--->
                </footer>
            </section>
        </section>
        <?php
        if (is_user_logged_in()) {
            if ($do == 'create' || $do == 'edit') {
                ?>
            </div>
            <?php
        } else {
            wplp_setPostViews(get_the_ID());
            $wplp_custom_code_box = stripslashes(get_post_meta(get_the_ID(), 'wplp_custom_code_box', true));
            echo $wplp_custom_code_box;
        }
    }
    ?>
</body>
</html>