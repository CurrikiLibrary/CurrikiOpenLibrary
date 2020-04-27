<?php
// Landing Page Name: About Me
// Preview Image: tpl_13.jpg
// Template File: template13
$tpl_id = 'template13';
$tpl_dir_url = llp_URL . '/llp_tpl/' . $tpl_id . '/';
?>
<!DOCTYPE html>
<html class="no-js">
    <head>
        <?php
        $tpl_full_url = llp_URL . '/llp_tpl/' . $llp_tpl_id;
//wp_head();
        include llp_PATH . '/llp_tpl/llp_imp_js_css.php';
        ?>
        <title><?php wp_title(); ?></title>
        <meta charset="utf-8">
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
        <meta content="width=device-width" name="viewport">
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,400italic,600italic&amp;subset=latin,latin-ext" type="text/css" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo $tpl_full_url; ?>/style.css" type="text/css" media="screen">
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
            $llp_button_1 = "'Click Here To Go To My Startup's Website!'";
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
        $llp_bg_1 = get_post_meta($post->ID, 'llp_bg_1', true);
        if (empty($llp_bg_1)) {
            $llp_bg_1 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/main.jpg';
        }
        $llp_image_1 = get_post_meta($post->ID, 'llp_image_1', true);
        if (empty($llp_image_1)) {
            $llp_image_1 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/loc.png';
        }
        $llp_image_1_link = get_post_meta($post->ID, 'llp_image_1_link', true);
        if (empty($llp_image_1_link)) {
            $llp_image_1_link = 'http://google.com';
        }
        $llp_image_2 = get_post_meta($post->ID, 'llp_image_2', true);
        if (empty($llp_image_2)) {
            $llp_image_2 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/briefcase.png';
        }
        $llp_image_2_link = get_post_meta($post->ID, 'llp_image_2_link', true);
        if (empty($llp_image_2_link)) {
            $llp_image_2_link = 'http://google.com';
        }
        $llp_image_3 = get_post_meta($post->ID, 'llp_image_3', true);
        if (empty($llp_image_3)) {
            $llp_image_3 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/fb.png';
        }
        $llp_image_3_link = get_post_meta($post->ID, 'llp_image_3_link', true);
        if (empty($llp_image_3_link)) {
            $llp_image_3_link = 'http://google.com';
        }
        $llp_image_4 = get_post_meta($post->ID, 'llp_image_4', true);
        if (empty($llp_image_4)) {
            $llp_image_4 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/twitter.png';
        }
        $llp_image_4_link = get_post_meta($post->ID, 'llp_image_4_link', true);
        if (empty($llp_image_4_link)) {
            $llp_image_4_link = 'http://google.com';
        }
        $llp_image_5 = get_post_meta($post->ID, 'llp_image_5', true);
        if (empty($llp_image_5)) {
            $llp_image_5 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/google.png';
        }
        $llp_image_5_link = get_post_meta($post->ID, 'llp_image_5_link', true);
        if (empty($llp_image_5_link)) {
            $llp_image_5_link = 'http://google.com';
        }
        $llp_image_6 = get_post_meta($post->ID, 'llp_image_6', true);
        if (empty($llp_image_6)) {
            $llp_image_6 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/lnkdin.png';
        }
        $llp_image_6_link = get_post_meta($post->ID, 'llp_image_6_link', true);
        if (empty($llp_image_6_link)) {
            $llp_image_6_link = 'http://google.com';
        }
        $llp_image_7 = get_post_meta($post->ID, 'llp_image_7', true);
        if (empty($llp_image_7)) {
            $llp_image_7 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/wiki.png';
        }
        $llp_image_7_link = get_post_meta($post->ID, 'llp_image_7_link', true);
        if (empty($llp_image_7_link)) {
            $llp_image_7_link = 'http://google.com';
        }
        $llp_image_8 = get_post_meta($post->ID, 'llp_image_8', true);
        if (empty($llp_image_8)) {
            $llp_image_8 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/field.png';
        }
        $llp_image_8_link = get_post_meta($post->ID, 'llp_image_8_link', true);
        if (empty($llp_image_8_link)) {
            $llp_image_8_link = 'http://google.com';
        }
        $pro_tpl_aboutme_name = get_post_meta($post->ID, 'pro_tpl_aboutme_name', true);
        $pro_tpl_aboutme_name_default = 'Merry Jonathan Sky';
        $pro_tpl_aboutme_name = get_post_meta($post->ID, 'pro_tpl_aboutme_name', true);
        if (empty($pro_tpl_aboutme_name)) {
            $pro_tpl_aboutme_name = $pro_tpl_aboutme_name_default;
        }
        $llp_tpl_abtme_5P_text1_default = ' Forest, Pacific Northwest';
        $llp_tpl_abtme_5P_text1 = get_post_meta($post->ID, 'llp_tpl_abtme_5P_text1', true);
        if (empty($llp_tpl_abtme_5P_text1)) {
            $llp_tpl_abtme_5P_text1 = $llp_tpl_abtme_5P_text1_default;
        }
        $llp_tpl_abtme_5P_text2_default = 'Unemployed';
        $llp_tpl_abtme_5P_text2 = get_post_meta($post->ID, 'llp_tpl_abtme_5P_text2', true);
        if (empty($llp_tpl_abtme_5P_text2)) {
            $llp_tpl_abtme_5P_text2 = $llp_tpl_abtme_5P_text2_default;
        }
        $llp_tpl_abtme_5P_text3_default = '  Wikipedia';
        $llp_tpl_abtme_5P_text3 = get_post_meta($post->ID, 'llp_tpl_abtme_5P_text3', true);
        if (empty($llp_tpl_abtme_5P_text3)) {
            $llp_tpl_abtme_5P_text3 = $llp_tpl_abtme_5P_text3_default;
        }
        $llp_tpl_abtme_5P_text4_default = '  Field Research';
        $llp_tpl_abtme_5P_text4 = get_post_meta($post->ID, 'llp_tpl_abtme_5P_text4', true);
        if (empty($llp_tpl_abtme_5P_text4)) {
            $llp_tpl_abtme_5P_text4 = $llp_tpl_abtme_5P_text4_default;
        }
        $pro_tpl_aboutme_1_default = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. '
                . 'Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.';
        $pro_tpl_aboutme_1 = get_post_meta($post->ID, 'pro_tpl_aboutme_1', true);
        if (empty($pro_tpl_aboutme_1)) {
            $pro_tpl_aboutme_1 = $pro_tpl_aboutme_1_default;
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
        <section id="wrapper">
            <header>
                <div class="shell">
                    <div id="pro_tpl_aboutme_name"  style="display:inline-block;" data-heading="Name" data-id="2" data-edit="true" data-type='custom' data-default="<?php echo htmlspecialchars(stripslashes($pro_tpl_aboutme_name)); ?>" data-wp_custom_field='pro_tpl_aboutme_name'>
                        <?php echo stripslashes($pro_tpl_aboutme_name); ?>
                    </div>
                </div>
            </header>
            <section id="location">
                <div class="shell">
                    <div class="holder">
                        <ul>
                            <li class="loc">
                                <a id="llp_image_1" data-heading="image"    style="display:inline-block;" data-image-link="#" data-id="3" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_1; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_1' href="#">
                                    <img alt="image" src="<?php echo $llp_image_1; ?>"  style="display:block;">
                                </a>
                                <div id="llp_tpl_abtme_5P_text1" style="display:inline-block;" data-heading="Content" data-id="4" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl_abtme_5P_text1)); ?>' data-wp_custom_field='llp_tpl_abtme_5P_text1'><?php echo stripslashes($llp_tpl_abtme_5P_text1); ?>
                                </div>
                            </li>
                            <li class="briefcase">
                                <a id="llp_image_2" data-heading="image"    style="display:inline-block;" data-image-link="#" data-id="5" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_2; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_2' href="#">
                                    <img alt="image" src="<?php echo $llp_image_2; ?>"  style="display:block;">
                                </a>
                                <div id="llp_tpl_abtme_5P_text2" style="display:inline-block;" data-heading="Content" data-id="6" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl_abtme_5P_text2)); ?>' data-wp_custom_field='llp_tpl_abtme_5P_text2'><?php echo stripslashes($llp_tpl_abtme_5P_text2); ?>
                                </div>
                            </li>
                        </ul>
                        <div class="clear"></div>
                    </div>
                </div>
            </section>
            <section id="social-links">
                <div class="shell">
                    <div id="social-icons">
                        <a id="llp_image_3" data-heading="image"    style="display:inline-block;" data-image-link="#" data-id="7" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_3; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_3' href="#">
                            <img alt="image" src="<?php echo $llp_image_3; ?>"  style="display:block;">
                        </a>
                        <a id="llp_image_4" data-heading="image"    style="display:inline-block;" data-image-link="#" data-id="8" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_4; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_4' href="#">
                            <img alt="image" src="<?php echo $llp_image_4; ?>"  style="display:block;">
                        </a>
                        <a id="llp_image_5" data-heading="image"    style="display:inline-block;" data-image-link="#" data-id="9" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_5; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_5' href="#">
                            <img alt="image" src="<?php echo $llp_image_5; ?>"  style="display:block;">
                        </a>
                        <a id="llp_image_6" data-heading="image"    style="display:inline-block;" data-image-link="#" data-id="10" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_6; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_6' href="#">
                            <img alt="image" src="<?php echo $llp_image_6; ?>"  style="display:block;">
                        </a>
                    </div>
                    <ul>
                        <li>
                            <a id="llp_image_7" data-heading="image"    style="display:inline-block;" data-image-link="#" data-id="11" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_7; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_7' href="#">
                                <img alt="image" src="<?php echo $llp_image_7; ?>"  style="display:block;">
                            </a>
                            <!---     <a href="#">-->
                            <div id="llp_tpl_abtme_5P_text3" style="display:inline-block;" data-heading="Content" data-id="12" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl_abtme_5P_text3)); ?>' data-wp_custom_field='llp_tpl_abtme_5P_text3'><?php echo stripslashes($llp_tpl_abtme_5P_text3); ?>
                            </div>
                            <!--
                                 </a>--->
                        </li>
                        <li>
                            <a id="llp_image_8" data-heading="image"    style="display:inline-block;" data-image-link="#" data-id="13" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_8; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_8' href="#">
                                <img alt="image" src="<?php echo $llp_image_8; ?>"  style="display:block;">
                            </a>
                            <!---     <a href="#">-->
                            <div id="llp_tpl_abtme_5P_text4" style="display:inline-block;" data-heading="Content" data-id="14" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl_abtme_5P_text4)); ?>' data-wp_custom_field='llp_tpl_abtme_5P_text4'><?php echo stripslashes($llp_tpl_abtme_5P_text4); ?>
                            </div>
                            <!--     </a>--->
                        </li>
                    </ul>
                    <div class="clear"></div>
                </div>
            </section>
            <section id="cont">
                <div class="shell">
                    <div id="pro_tpl_aboutme_1" data-heading="Main Content" style="display:inline-block;" data-id="15" data-edit="true" data-type='custom' data-default="<?php echo htmlspecialchars(stripslashes($pro_tpl_aboutme_1)); ?>" data-wp_custom_field='pro_tpl_aboutme_1'>
                        <?php echo stripslashes($pro_tpl_aboutme_1); ?>
                    </div>
                    <a id="llp_button_1" data-heading="Button" data-id="16" data-edit="true" data-type='link' data-default='<?php echo $llp_button_1; ?>' data-wp_custom_field='llp_button_1' data-link='<?php echo $llp_button_1_link_a; ?>' data-target='1' href="<?php echo $llp_button_1_link_a; ?>" class="button llp_box" <?php if ($llp_button_1_link_a_open != "") { ?>target="_blank"<?php } ?>><?php echo $llp_button_1; ?></a>
                    <div id="inline1" style="max-width:400px;display: none;">
                        <div class="form" id="llp_tpl4_form" data-heading="Optin Form" data-id="16" data-edit="true" data-type='form' data-wp_custom_field='llp_tpl4_form'>
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
                                    <button type="submit" class="button3"><?php echo $atform['_optthemes_optinformsubmit']; ?></button>
                                </form>
                            <?php } else { ?>

                                <form action="#" method="post" onsubmit="" target="_top">
                                    <input placeholder="Enter a Valid Email Here..." onfocus="this.placeholder = 'Enter a Valid Email Here...'" title="Enter a Valid Email Here..." value="" type="email" id="email" data-lb-inputemail="true">
                                    <button type="submit" class="blue-btn">Click Here To Go To My Startup's Website!</button>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                    <!--- <div class="btn-handler"> <a href="#" class="button">Click Here To Go To My Startup's Website!</a> </div>--->
                </div>
            </section>
            <div class="clear"></div>
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