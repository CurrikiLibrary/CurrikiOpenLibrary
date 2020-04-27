<?php
// Landing Page Name: Basic Centered Squeez Page
// Preview Image: tmp_9.jpg
// Template File: template9
$tpl_id = 'template9';
$tpl_dir_url = llp_URL . '/llp_tpl/' . $tpl_id . '/';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
        <meta content="width=device-width, initial-scale=1" name="viewport">
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700,400italic,600italic,700italic&amp;subset=latin,latin-ext" type="text/css" rel="stylesheet">
        <link href="<?php echo llp_URL; ?>/llp_tpl/<?php echo $llp_tpl_id; ?>/css/style.css" rel="stylesheet">
        <script type="text/javascript" src="<?php echo llp_URL; ?>/llp_tpl/<?php echo $llp_tpl_id; ?>/js/javascript.js"></script>
        <?php include llp_PATH . '/llp_tpl/llp_imp_js_css.php'; ?>
    </head>
    <body>
        <?php
        if (is_user_logged_in()) {
            if ($do == 'create' || $do == 'edit') {
                include llp_PATH . '/llp_tpl/customized_bar.php';
            }
        }
        $llp_tpl4_form = get_post_meta($post->ID, 'llp_tpl4_form', true);
        $atform = $llp_tpl4_form;
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
        $llp_tpl9_bg_1 = get_post_meta($post->ID, 'llp_tpl9_bg_1', true);
        if (empty($llp_tpl9_bg_1)) {
            $llp_tpl9_bg_1 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/bg.jpg';
        }
        $llp_tpl9_text1_default = 'Download the Report';
        $llp_tpl9_text1 = get_post_meta($post->ID, 'llp_tpl9_text1', true);
        if (empty($llp_tpl9_text1)) {
            $llp_tpl9_text1 = $llp_tpl9_text1_default;
        }
        $llp_tpl9_3P_text10_2_default = ' <p class="headline">Free Report Reveals...</p>';
        $llp_tpl9_3P_text10_2 = get_post_meta($post->ID, 'llp_tpl9_3P_text10_2', true);
        if (empty($llp_tpl9_3P_text10_2)) {
            $llp_tpl9_3P_text10_2 = $llp_tpl9_3P_text10_2_default;
        }
        $llp_tpl9_3P_text11_default = '   <p class="subheadline">"The 5 (Dirt Cheap) Tools I Use To Create All My Videos (Including My $80 HD Video Camera)"</p>';
        $llp_tpl9_3P_text11 = get_post_meta($post->ID, 'llp_tpl9_3P_text11', true);
        if (empty($llp_tpl9_3P_text11)) {
            $llp_tpl9_3P_text11 = $llp_tpl9_3P_text11_default;
        }
        $llp_tpl9_3P_text12_default = '   <p>Enter your email address below to get this free report</p>';
        $llp_tpl9_3P_text12 = get_post_meta($post->ID, 'llp_tpl9_3P_text12', true);
        if (empty($llp_tpl9_3P_text12)) {
            $llp_tpl9_3P_text12 = $llp_tpl9_3P_text12_default;
        }
        $llp_tpl9_3P_text13_default = '<span>Download the Report</span>';
        $llp_tpl9_3P_text13 = get_post_meta($post->ID, 'llp_tpl9_3P_text13', true);
        if (empty($llp_tpl9_3P_text13)) {
            $llp_tpl9_3P_text13 = $llp_tpl9_3P_text13_default;
        }
        $llp_tpl9_3P_text14_default = 'Legal Information';
        $llp_tpl9_3P_text14 = get_post_meta($post->ID, 'llp_tpl9_3P_text14', true);
        if (empty($llp_tpl9_3P_text14)) {
            $llp_tpl9_3P_text14 = $llp_tpl9_3P_text14_default;
        }
        $llp_tpl9_3P_text16_default = '
                                            Your Information is 100% Secure and Will Never Be Shared With Anyone.';
        $llp_tpl9_3P_text16 = get_post_meta($post->ID, 'llp_tpl9_3P_text16', true);
        if (empty($llp_tpl9_3P_text16)) {
            $llp_tpl9_3P_text16 = $llp_tpl9_3P_text16_default;
        }


        $llp_privacy_1 = get_post_meta($post->ID, 'llp_privacy_1', true);
        if (empty($llp_privacy_1)) {
            $llp_privacy_1 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/privacy.png';
        }


        $llp_tpl8_3P_text15_default = '<input placeholder="Enter a Valid Email Here..." onfocus="this.placeholder = "Enter a Valid Email Here..."" title="Enter a Valid Email Here..." value="" type="email" id="email" data-lb-inputemail="true">';
        $llp_tpl8_3P_text15 = get_post_meta($post->ID, 'llp_tpl8_3P_text15', true);
        if (empty($llp_tpl8_3P_text15)) {
            $llp_tpl8_3P_text15 = $llp_tpl8_3P_text15_default;
        }
        ?>
        <style>
            body {
                <?php if (!empty($llp_tpl9_bg_1)) { ?>
                    background-image:url("<?php echo $llp_tpl9_bg_1; ?>");
                    -webkit-background-size: cover;
                    -moz-background-size: cover;
                    background-size: cover;
                <?php } ?>
            }
            #llp_tpl9_bg_1 img {
                height:100px;
                width:100%;
                display:none;
            }
        </style>
        <?php
        if (is_user_logged_in()) {
            if ($do == 'create' || $do == 'edit') {
                ?>
                <a id="llp_tpl9_bg_1" href="javascript:void(0);" data-heading="Update Background" data-id="1" data-edit="true" data-type='bgimage' data-default='<img src="<?php echo $llp_tpl9_bg_1; ?>" border="0" alt="">' data-wp_custom_field='llp_tpl9_bg_1' class="dspblk llp_btn">Change Background Image<img alt="" src="<?php echo $llp_tpl9_bg_1; ?>"></a>
                <?php
            }
        }
        $llp_tpl8_1P_text1 = 'text1';
        $llp_tpl8_1P_text1 = get_post_meta($post->ID, 'llp_tpl8_1P_text1', true);
        if (empty($llp_tpl8_1P_text1)) {
            $llp_tpl8_1P_text1 = $llp_tpl8_1P_text1;
        }
        ?>
<!--<img src="<?php //echo llp_URL;      ?>/llp_tpl/<?php //echo $llp_tpl_id;      ?>/images/bg.jpg" class="bg" alt="" id="bgimg">-->
        <section id="wrapper">
            <section id="box">
                <div id="box-top">
                    <div id="llp_tpl9_3P_text10_2" data-heading="Content" data-id="2" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl9_3P_text10_2)); ?>' data-wp_custom_field='llp_tpl9_3P_text10_2'><?php echo stripslashes($llp_tpl9_3P_text10_2); ?>
                    </div>
                </div>
                <div id="box-bottom">
                    <div id="llp_tpl9_3P_text11" data-heading="Content" data-id="3" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl9_3P_text11)); ?>' data-wp_custom_field='llp_tpl9_3P_text11'><?php echo stripslashes($llp_tpl9_3P_text11); ?>
                    </div>
                    <div id="llp_tpl9_3P_text12" data-heading="Content" data-id="4" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl9_3P_text12)); ?>' data-wp_custom_field='llp_tpl9_3P_text12'><?php echo stripslashes($llp_tpl9_3P_text12); ?>
                    </div>
                    <div class="form" id="llp_tpl4_form" data-heading="Optin Form" data-id="5" data-edit="true" data-type='form' data-wp_custom_field='llp_tpl4_form'>
                        <div id="form">
                            <?php if ($llp_tpl4_form) { ?>
                                <form action="<?php echo $atform['_optthemes_optinformurl']; ?>" method="post" onsubmit="javascript: if (!CompanyNameIsValidEmail(this, 'email')) {
                                            return false;
                                        }
                                        return true;" target="_top">
                                          <?php
                                          if ($atform['optintext']) {
                                              foreach ($atform['optintext'] as $atform_fields => $key) {
                                                  if ($atform['optincheck'][$atform_fields] == 'yes') {
                                                      ?>
                                                <input onblur="if ($(this).val() == '') {
                                                            $(this).val($(this).attr('title'));
                                                        }" onfocus="if ($(this).val() == $(this).attr('title')) {
                                                                    $(this).val('')
                                                                }" id="email" placeholder="<?php echo $key; ?>" onfocus="this.placeholder = '<?php echo $key; ?>'"  title="<?php echo $key; ?>" value="" name="<?php echo $atform_fields; ?>" type="<?php echo $atform['optintype'][$atform_fields]; ?>">
                                                <div id="abc"></div>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                    <?php echo $atform['_optthemes_webformhiddenhtml']; ?>

                                    <button type="submit" id="submit-btn"><?php echo $atform['_optthemes_optinformsubmit']; ?></button>
                                </form>
                            <?php } else { ?>
                                <form action="#" method="post" onsubmit="" target="_top">
                                    <input placeholder="Enter a Valid Email Here..." onfocus="this.placeholder = 'Enter a Valid Email Here...'" title="Enter a Valid Email Here..." value="" type="email" id="email" data-lb-inputemail="true">
                                    <button type="submit" id="submit-btn">Download the Report</button>
                                </form>
                            <?php } ?>
                            <div id="box-bg"></div>
                        </div>
                    </div>
                </div>
                <div id="footer">
                    <div id="llp_tpl9_3P_text16" data-heading="Content" data-id="7" style="display:inline-block;color: #2A435D;margin-top: 18px;" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl9_3P_text16)); ?>' data-wp_custom_field='llp_tpl9_3P_text16'><?php echo stripslashes($llp_tpl9_3P_text16); ?>
                    </div>
                    <div class="legal">
                        <div id="llp_tpl9_3P_text14" style="display:inline-block;" data-heading="Content" data-id="6" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl9_3P_text14)); ?>' data-wp_custom_field='llp_tpl9_3P_text14'><?php echo stripslashes($llp_tpl9_3P_text14); ?>
                        </div>
                    </div>

                </div>
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