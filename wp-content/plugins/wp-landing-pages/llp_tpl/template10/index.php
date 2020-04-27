<?php
// Landing Page Name: Basic Squeeze Page
// Preview Image: tmp_10.jpg
// Template File: template10
$tpl_id = 'template10';
$tpl_dir_url = llp_URL . '/llp_tpl/' . $tpl_id . '/';
?>
<!DOCTYPE html>
<html>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <head>
        <meta charset="utf-8">
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
        <meta content="width=device-width, initial-scale=1" name="viewport">
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:200italic,400,600,400italic,700&amp;subset=latin,latin-ext" type="text/css" rel="stylesheet">
        <script type="text/javascript" src="<?php echo llp_URL; ?>/llp_tpl/<?php echo $llp_tpl_id; ?>/js/script.js"></script>
        <link rel="stylesheet" href="<?php echo llp_URL; ?>/llp_tpl/<?php echo $llp_tpl_id; ?>/css/style.css" />
        <?php include llp_PATH . '/llp_tpl/llp_imp_js_css.php'; ?>
    </head>
    <?php
    if (is_user_logged_in()) {
        if ($do == 'create' || $do == 'edit') {
            include llp_PATH . '/llp_tpl/customized_bar.php';
        }
    }
    $llp_tpl11_bg_1 = get_post_meta($post->ID, 'llp_tpl11_bg_1', true);
    if (empty($llp_tpl11_bg_1)) {
        $llp_tpl11_bg_1 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/bg.jpg';
    }
    $llp_tpl4_form = get_post_meta($post->ID, 'llp_tpl4_form', true);
    $atform = $llp_tpl4_form;
    $llp_button_1 = get_post_meta($post->ID, 'llp_button_1', true);
    if (empty($llp_button_1)) {
        $llp_button_1 = '<span>Click Here For Free Instant Access</span>';
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
    $llp_tpl9_text1_default = ' <p class="read-this">Lorem ipsum dolor report</p>';
    $llp_tpl9_text1 = get_post_meta($post->ID, 'llp_tpl9_text1', true);
    if (empty($llp_tpl9_text1)) {
        $llp_tpl9_text1 = $llp_tpl9_text1_default;
    }

    $llp_image_1 = get_post_meta($post->ID, 'llp_image_1', true);
    if (empty($llp_image_1)) {
        $llp_image_1 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/book.png';
    }
    $llp_image_1_link = get_post_meta($post->ID, 'llp_image_1_link', true);
    if (empty($llp_image_1_link)) {
        $llp_image_1_link = 'http://google.com';
    }
    $llp_tpl9_text3_default = ' <p class="headline">"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec efficitur sollicitudin leo et pulvinar."</p>';
    $llp_tpl9_text3 = get_post_meta($post->ID, 'llp_tpl9_text3', true);
    if (empty($llp_tpl9_text3)) {
        $llp_tpl9_text3 = $llp_tpl9_text3_default;
    }
    $llp_tpl9_text4_default = 'Click Here For Free Instant Access';
    $llp_tpl9_text4 = get_post_meta($post->ID, 'llp_tpl9_text4', true);
    if (empty($llp_tpl9_text4)) {
        $llp_tpl9_text4 = $llp_tpl9_text4_default;
    }
    $llp_tpl9_text5_default = '  <span>Click Here For Free Instant Access</span>';
    $llp_tpl9_text5 = get_post_meta($post->ID, 'llp_tpl9_text5', true);
    if (empty($llp_tpl9_text5)) {
        $llp_tpl9_text5 = $llp_tpl9_text5_default;
    }

    $llp_tpl9_text7_default = 'Your Information is 100% Secure And Will Never Be Shared With Anyone.';
    $llp_tpl9_text7 = get_post_meta($post->ID, 'llp_tpl9_text7', true);
    if (empty($llp_tpl9_text7)) {
        $llp_tpl9_text7 = $llp_tpl9_text7_default;
    }
    $llp_tpl9_text8_default = 'Legal Information';
    $llp_tpl9_text8 = get_post_meta($post->ID, 'llp_tpl9_text8', true);
    if (empty($llp_tpl9_text8)) {
        $llp_tpl9_text8 = $llp_tpl9_text8_default;
    }
    $llp_image_2 = get_post_meta($post->ID, 'llp_image_2', true);
    if (empty($llp_image_2)) {
        $llp_image_2 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/secure.png';
    }
    ?>
    <style>
        body {
            <?php if (!empty($llp_tpl11_bg_1)) { ?>
                background-image:url("<?php echo $llp_tpl11_bg_1; ?>");
                -webkit-background-size: cover;
                -moz-background-size: cover;
                background-size: cover;
            <?php } ?>
        }
        #llp_tpl11_bg_1 img {
            height:100px;
            width:100%;
            display:none;
        }
    </style>

    <?php
    if (is_user_logged_in()) {
        if ($do == 'create' || $do == 'edit') {
            ?>
            <a id="llp_tpl11_bg_1" href="javascript:void(0);" data-heading="Update Background" data-id="1" data-edit="true" data-type='bgimage' data-default='<img src="<?php echo $llp_tpl11_bg_1; ?>" border="0" alt="">' data-wp_custom_field='llp_tpl11_bg_1' class="dspblk llp_btn">Change Background Image<img alt="" src="<?php echo $llp_tpl11_bg_1; ?>"></a>
            <?php
        }
    }
    ?>
    <body>
        <div id="main">
            <div class="row-main">
                <div class="col col-book"> <a href="#">
                    </a> </div><br /><br />

                <a id="llp_image_1" data-heading="image"  style="display:inline-block;" data-image-link="#" data-id="2" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_1; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_1' href="#">
                    <img alt="image" src="<?php echo $llp_image_1; ?>"  style="display:block;">
                </a>


                <div class="col col-content">
                    <div class="in">
                        <div id="llp_tpl9_text1" data-heading="Content" data-id="3" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl9_text1)); ?>' data-wp_custom_field='llp_tpl9_text1'><?php echo stripslashes($llp_tpl9_text1); ?>
                        </div>
                        <div id="llp_tpl9_text3" data-heading="Content" data-id="4" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl9_text3)); ?>' data-wp_custom_field='llp_tpl9_text3'><?php echo stripslashes($llp_tpl9_text3); ?>
                        </div> <div class="row">
                            <div class="buttons">
                                <!----   <button data-lb-id="optin-form" class="btn" type="submit" href="#" onclick="javascript:return window.triggerOptInForm()"> ----->
                                <a id="llp_button_1" data-heading="Button" data-id="5" data-edit="true" data-type='link' data-default='<?php echo $llp_button_1; ?>' data-wp_custom_field='llp_button_1' data-link='<?php echo $llp_button_1_link_a; ?>' data-target='1' href="<?php echo $llp_button_1_link_a; ?>" class="llp_box btns" <?php if ($llp_button_1_link_a_open != "") { ?>target="_blank"<?php } ?>><?php echo $llp_button_1; ?></a>
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
                                                <button type="submit" class="btn blue-btn"><?php echo $atform['_optthemes_optinformsubmit']; ?></button>
                                            </form>
                                        <?php } else { ?>

                                            <form action="#" method="post" onsubmit="" target="_top">
                                                <input placeholder="Enter a Valid Email Here..." onfocus="this.placeholder = 'Enter a Valid Email Here...'" title="Enter a Valid Email Here..." value="" type="email"  data-lb-inputemail="true">
                                                <button type="submit" class="blue-btn">Get Instant Access</button>
                                            </form>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div data-lb-id="optin-form" href="#" onclick="javascript:return window.triggerOptInForm()"><a type="submit" class="link">
                                        <div id="llp_tpl9_text4" data-heading="Content" data-id="7" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl9_text4)); ?>' data-wp_custom_field='llp_tpl9_text4'><?php echo stripslashes($llp_tpl9_text4); ?>
                                        </div></a>
                                </div>
                            </div>
                        </div>


                        <div class="secure">
                            <div id="llp_tpl9_text7" data-heading="Content" style="margin:-50px 0px 0px 0px;" data-id="61" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl9_text7)); ?>' data-wp_custom_field='llp_tpl9_text7'><?php echo stripslashes($llp_tpl9_text7); ?>
                            </div>
                        </div>
                        <div class="legal">
                            <!--<a href="#">---->
                            <div id="llp_tpl9_text8" data-heading="Content" style="" data-id="62" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl9_text8)); ?>' data-wp_custom_field='llp_tpl9_text8'><?php echo stripslashes($llp_tpl9_text8); ?>
                            </div>
                            <!--</a>---->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        if (is_user_logged_in()) {
            if ($do == 'create' || $do == 'edit') {
                ?>
                <?php
            } else {
                wplp_setPostViews(get_the_ID());
                $wplp_custom_code_box = stripslashes(get_post_meta(get_the_ID(), 'wplp_custom_code_box', true));
                echo $wplp_custom_code_box;
            }
        }
        ?>
        <div style="clear:both"></div>
    </body>
</html>