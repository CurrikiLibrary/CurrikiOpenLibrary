<?php
// Landing Page Name: Basic Video Squeeze Page
// Preview Image: tmp_12.jpg
// Template File: template12
$tpl_id = 'template12';
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
    </head>
    <body>
        <?php
        if (is_user_logged_in()) {
            if ($do == 'create' || $do == 'edit') {
                include llp_PATH . '/llp_tpl/customized_bar.php';
            }
        }


        $llp_down_1 = get_post_meta($post->ID, 'llp_down_1', true);
        if (empty($llp_down_1)) {
            $llp_down_1 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/down.png';
        }
        $llp_down_1_image_link = get_post_meta($post->ID, 'llp_down_1_image_link', true);
        if (empty($llp_down_1_image_link)) {
            $llp_down_1_image_link = 'http://google.com';
        }


        $llp_bg_1 = get_post_meta($post->ID, 'llp_bg_1', true);
        $llp_tpl4_form = get_post_meta($post->ID, 'llp_tpl4_form', true);
        $atform = $llp_tpl4_form;


        if (empty($llp_bg_1)) {
            $llp_bg_1 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/bg.jpg';
        }

        $llp_button_1 = get_post_meta($post->ID, 'llp_button_1', true);
        if (empty($llp_button_1)) {
            $llp_button_1 = '<span>Download the Report</span><span class="arrow"></span>';
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


        $llp_tpl12_5P_text1_default = ' <p>Free Report Reveals... "The 5 (Dirt Cheap) Tools I Use To Create All My Videos (Including My $80 HD Video Camera)"</p>';
        $llp_tpl12_5P_text1 = get_post_meta($post->ID, 'llp_tpl12_5P_text1', true);
        if (empty($llp_tpl12_5P_text1)) {
            $llp_tpl12_5P_text1 = $llp_tpl12_5P_text1_default;
        }



        $llp_tpl12_5P_text2_default = '<p>Enter your email address to get this free report</p>';
        $llp_tpl12_5P_text2 = get_post_meta($post->ID, 'llp_tpl12_5P_text2', true);
        if (empty($llp_tpl12_5P_text2)) {
            $llp_tpl12_5P_text2 = $llp_tpl12_5P_text2_default;
        }

        $llp_tpl12_5P_text3_default = 'Legal Information';
        $llp_tpl12_5P_text3 = get_post_meta($post->ID, 'llp_tpl12_5P_text3', true);
        if (empty($llp_tpl12_5P_text3)) {
            $llp_tpl12_5P_text3 = $llp_tpl12_5P_text3_default;
        }
        $llp_tpl12_5P_text4_default = 'Your Information is 100% Secure and Will Never Be Shared With Anyone.';
        $llp_tpl12_5P_text4 = get_post_meta($post->ID, 'llp_tpl12_5P_text4', true);
        if (empty($llp_tpl12_5P_text4)) {
            $llp_tpl12_5P_text4 = $llp_tpl12_5P_text4_default;
        }

        $llp_image_1 = get_post_meta($post->ID, 'llp_image_1', true);
        if (empty($llp_image_1)) {
            $llp_image_1 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/secure.png';
        }
        $llp_yt_video = get_post_meta($post->ID, 'llp_yt_video', true);
        if (empty($llp_yt_video)) {
            $llp_yt_video = '<iframe width="400" height="225" src="http://www.youtube.com/embed/ZmPt2Zvz_o0" frameborder="0" allowfullscreen></iframe>';
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
            <section id="box-main">
                <section id="box1">
                    <div class="content">
                        <div id="llp_tpl12_5P_text1" data-heading="Content" data-id="2" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl12_5P_text1)); ?>' data-wp_custom_field='llp_tpl12_5P_text1'><?php echo stripslashes($llp_tpl12_5P_text1); ?>
                        </div>
                        <div id="video">
                            <div id="llp_yt_video" class="video-container" data-heading="video" data-id="3" data-edit="true" data-type='iframe' data-default='<?php echo $llp_yt_video; ?>' data-wp_custom_field='llp_yt_video'>
                                <?php echo $llp_yt_video; ?>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="box2">
                    <div class="content">
                        <div id="llp_tpl12_5P_text2" data-heading="Content" data-id="50" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl12_5P_text2)); ?>' data-wp_custom_field='llp_tpl12_5P_text2'><?php echo stripslashes($llp_tpl12_5P_text2); ?>
                        </div>
                        <div id="arrow-down">
                            <a id="llp_down_1" data-heading="image"    style="display:block;" data-image-link="#" data-id="51" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_down_1; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_down_1' href="#">
                                <img alt="image" src="<?php echo $llp_down_1; ?>"  style="display:block;">
                            </a>
                            <div id="form">
                                <div class="abc">
                                    <div class="form" id="llp_tpl4_form" data-heading="Optin Form" data-id="6" data-edit="true" data-type='form' data-wp_custom_field='llp_tpl4_form'>
                                        <?php if ($llp_tpl4_form) { ?>
                                            <form action="<?php echo $atform['_optthemes_optinformurl']; ?>" method="post">
                                                <?php
                                                if ($atform['optintext']) {
                                                    foreach ($atform['optintext'] as $atform_fields => $key) {
                                                        if ($atform['optincheck'][$atform_fields] == 'yes') {
                                                            ?>
                                                            <input id="email" placeholder="<?php echo $key; ?>" onfocus="this.placeholder = '<?php echo $key; ?>'" title="<?php echo $key; ?>" value="" name="<?php echo $atform_fields; ?>" type="<?php echo $atform['optintype'][$atform_fields]; ?>">
                                                            <div class="br"></div>
                                                            <?php
                                                        }
                                                    }
                                                }
                                                ?>
                                                <?php echo $atform['_optthemes_webformhiddenhtml']; ?>

                                                <button type="submit" class="submit"><?php echo $atform['_optthemes_optinformsubmit']; ?></button>
                                            </form>
                                        <?php } else { ?>
                                            <form action="#" method="post" onsubmit="" target="_top">
                                                <input placeholder="Enter a Valid Email Here..." onfocus="this.placeholder = 'Enter a Valid Email Here...'" title="Enter a Valid Email Here..." value="" type="email" id="email" data-lb-inputemail="true">
                                                <button type="submit" name="Submit"  class="submit">Download The Report</button>
                                            </form>
                                        <?php } ?> </div>
                                </div></div>
                        </div>
                </section>
                <div class="clear"></div>
            </section>
            <div id="footer">

                </br>
                <div class="legal">
                    <!---<a href="#" target="_blank">--->
                    <div id="llp_tpl12_5P_text3" data-heading="Content" data-id="7" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl12_5P_text3)); ?>' data-wp_custom_field='llp_tpl12_5P_text3'><?php echo stripslashes($llp_tpl12_5P_text3); ?>
                    </div>
                    <!---</a>--->
                </div>
                <div class="note">
                    <a id="llp_image_1" data-heading="image"    style="display:inline-block;margin:20px 0px 0px -450px;" data-image-link="#" data-id="55" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_1; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_1' href="#">
                        <img alt="image" src="<?php echo $llp_image_1; ?>"  style="display:block;">
                    </a>
                    <div id="llp_tpl12_5P_text4" data-heading="Content" style="display:block;margin:-35px 0px 0px 50px;font-size:13px;" data-id="56" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl12_5P_text4)); ?>' data-wp_custom_field='llp_tpl12_5P_text4'><?php echo stripslashes($llp_tpl12_5P_text4); ?>
                    </div> </div>





            </div>
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