<?php
// Landing Page Name: Presentation / Video
// Preview Image: tmp_5.jpg
// Template File: template5
$tpl_id = 'template5';
$tpl_dir_url = llp_URL . '/llp_tpl/' . $tpl_id . '/';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>
            <?php wp_title(); ?>
        </title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <meta charset="utf-8">
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">

        <!-- Style.css  -->

        <link rel="stylesheet" href="<?php echo llp_URL; ?>/llp_tpl/<?php echo $llp_tpl_id; ?>/style.css" type="text/css" media="screen">

        <!-- Style.css  -->

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
            }
        }

        $llp_bg_1 = get_post_meta($post->ID, 'llp_bg_1', true);
        if (empty($llp_bg_1)) {
            $llp_bg_1 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/bg5.png';
        }
        $llp_tpl4_form = get_post_meta($post->ID, 'llp_tpl4_form', true);

        $atform = $llp_tpl4_form;

        $llp_logo_1 = get_post_meta($post->ID, 'llp_logo_1', true);

        if (empty($llp_logo_1)) {

            $llp_logo_1 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/logo.png';
        }

        $llp_logo_1_image_link = get_post_meta($post->ID, 'llp_logo_1_image_link', true);

        if (empty($llp_logo_1_image_link)) {

            $llp_logo_1_image_link = 'http://google.com';
        }

        $llp_image_1 = get_post_meta($post->ID, 'llp_image_1', true);
        if (empty($llp_image_1)) {
            $llp_image_1 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/red-circle.png';
        }
        if (empty($llp_image_1_link)) {
            $llp_image_1_link = 'http://google.com';
        }
        $llp_image_2 = get_post_meta($post->ID, 'llp_image_2', true);
        if (empty($llp_image_2)) {
            $llp_image_2 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/red-circle.png';
        }
        if (empty($llp_image_2_link)) {
            $llp_image_2_link = 'http://google.com';
        }

        $llp_image_3 = get_post_meta($post->ID, 'llp_image_3', true);
        if (empty($llp_image_3)) {
            $llp_image_3 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/red-arrowL.png';
        }
        if (empty($llp_image_3_link)) {
            $llp_image_3_link = 'http://google.com';
        }

        $llp_image_4 = get_post_meta($post->ID, 'llp_image_4', true);
        if (empty($llp_image_4)) {
            $llp_image_4 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/red-arrowR.png';
        }
        if (empty($llp_image_4_link)) {
            $llp_image_4_link = 'http://google.com';
        }

        $llp_tpl5_5P_text1_default = 'Turn Up Your Speakers and WATCH';
        $llp_tpl5_5P_text1 = get_post_meta($post->ID, 'llp_tpl5_5P_text1', true);
        if (empty($llp_tpl5_5P_text1)) {
            $llp_tpl5_5P_text1 = $llp_tpl5_5P_text1_default;
        }


        $llp_tpl5_5P_text2_default = 'This FREE PRESENTATION Below';
        $llp_tpl5_5P_text2 = get_post_meta($post->ID, 'llp_tpl5_5P_text2', true);
        if (empty($llp_tpl5_5P_text2)) {
            $llp_tpl5_5P_text2 = $llp_tpl5_5P_text2_default;
        }

        $llp_iframe_1 = get_post_meta($post->ID, 'llp_iframe_1', true);

        if (empty($llp_iframe_1)) {

            $llp_iframe_1 = '<iframe width="750" height="414" src="http://www.youtube.com/embed/moSFlvxnbgk?wmode=opaque" frameborder="0" allowfullscreen></iframe>';
        }

        $llp_button_1 = get_post_meta($post->ID, 'llp_button_1', true);

        if (empty($llp_button_1)) {

            $llp_button_1 = 'Click Here To Place Your Order';
        }

        $llp_button_1_link_a = get_post_meta($post->ID, 'llp_button_1_link_a', true);

        if (empty($llp_button_1_link_a)) {

            $llp_button_1_link_a = 'http://google.com';
        }

        $llp_button_1_link_a_open = get_post_meta($post->ID, 'llp_button_1_link_a_open', true);

        if (empty($llp_button_1_link_a_open)) {

            if ($llp_button_1_link_a_open == '1') {

                $llp_button_1_link_a_open = '1';
            } else {

                $llp_button_1_link_a_open = '0';
            }
        }

        if ($llp_button_1_link_a_open == 1) {

            $llp_button_1_link_a_open = '_blank';
        } else {

            $llp_button_1_link_a_open = '';
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
        <header>
            <div class="headmain">
                <div id="logo"> <a id="llp_logo_1" data-heading="Logo" data-image-link="<?php echo $llp_logo_1_image_link; ?>" data-id="2" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_logo_1; ?>" border="0" alt="logo">' data-wp_custom_field='llp_logo_1' href="<?php echo $llp_logo_1_image_link; ?>"><img src="<?php echo $llp_logo_1; ?>" border="0" alt="logo"></a> </div>
            </div>
        </header>
        <div id="wrap">
            <div id="content">
                <div class="video-course">
                    <h1> <a id="llp_image_1" data-heading="image"     style="display:inline-block;" data-image-link="#" data-id="3" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_1; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_1' href="#"> <img alt="image" src="<?php echo $llp_image_1; ?>"  style="display:block;" class="circleleft"> </a>
                        <div id="llp_tpl5_5P_text1" data-heading="Content" data-id="4"  style="display:inline-block;" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl5_5P_text1)); ?>' data-wp_custom_field='llp_tpl5_5P_text1'><?php echo stripslashes($llp_tpl5_5P_text1); ?> </div>
                        <a id="llp_image_2" data-heading="image"     style="display:inline-block;" data-image-link="#" data-id="5" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_2; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_2' href="#"> <img alt="image" src="<?php echo $llp_image_2; ?>"  style="display:block;" class="circleright"> </a> </h1>
                    <h1> <a id="llp_image_3" data-heading="image"     style="display:inline-block;" data-image-link="#" data-id="6" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_3; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_3' href="#"> <img alt="image" src="<?php echo $llp_image_3; ?>"  style="display:block;" class="circleleft"> </a>
                        <div id="llp_tpl5_5P_text2" data-heading="Content" data-id="7"  style="display:inline-block;" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl5_5P_text2)); ?>' data-wp_custom_field='llp_tpl5_5P_text2'><?php echo stripslashes($llp_tpl5_5P_text2); ?> </div>
                        &nbsp; <a id="llp_image_4" data-heading="image"     style="display:inline-block;" data-image-link="#" data-id="8" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_4; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_4' href="#"> <img alt="image" src="<?php echo $llp_image_4; ?>"  style="display:block;" class="circleleft"> </a> </h1>
                    <section id="vid-frame">
                        <div id="llp_iframe_1" class="video-container" data-heading="Intro Video" data-id="9" data-edit="true" data-type='iframe' data-default='<?php echo $llp_iframe_1; ?>' data-wp_custom_field='llp_iframe_1'><?php echo $llp_iframe_1; ?></div>
                    </section>
                    <div id="lb-fade-box" style="display: block; opacity: 1;"><a id="llp_button_1" data-heading="Button" data-id="10" data-edit="true" data-type='link' data-default='<?php echo $llp_button_1; ?>' data-wp_custom_field='llp_button_1' data-link='<?php echo $llp_button_1_link_a; ?>' data-target='1' href="<?php echo $llp_button_1_link_a; ?>" class="yellow-btn llp_box" <?php if ($llp_button_1_link_a_open != "") { ?>target="_blank"<?php } ?>><?php echo $llp_button_1; ?></a></div>
                    <div id="inline1" style="width:400px;display: none;">
                        <div class="form" id="llp_tpl4_form" data-heading="Optin Form" data-id="11" data-edit="true" data-type='form' data-wp_custom_field='llp_tpl4_form'>
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
                                    <button type="submit" class="btn yellow-btn"><?php echo $atform['_optthemes_optinformsubmit']; ?></button>
                                </form>
                            <?php } else { ?>
                                <form action="#" method="post" onsubmit="" target="_top">
                                    <input placeholder="Enter a Valid Email Here..." onfocus="this.placeholder = 'Enter a Valid Email Here...'" title="Enter a Valid Email Here..." value="" type="email" id="email" data-lb-inputemail="true">
                                    <button type="submit" class="btn yellow-btn">Get Instant Access</button>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer> </footer>
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
    </body>
</html>