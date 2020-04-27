<?php
// Landing Page Name: Free Report
// Preview Image: tmp_4.jpg
// Template File: template4
$tpl_id = 'template4';
$tpl_dir_url = llp_URL . '/llp_tpl/' . $tpl_id . '/';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <title><?php wp_title(); ?></title>
        <meta charset="utf-8">
        <meta content="width=device-width" name="viewport">
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,200,600,700,900,200italic,300italic,400italic,600italic,700italic,900italic&amp;subset=latin,latin-ext" type="text/css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">
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
            } else {
                wplp_setPostViews(get_the_ID());
            }
        }
        $llp_bg_1 = get_post_meta($post->ID, 'llp_bg_1', true);
        if (empty($llp_bg_1)) {
            $llp_bg_1 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/bg.png';
        }
        //$llp_tpl4_topline = get_post_meta($post->ID, 'llp_tpl4_topline', true);
        $llp_tpl4_headline = get_post_meta($post->ID, 'llp_tpl4_headline', true);
        $llp_tpl4_disclaimer = get_post_meta($post->ID, 'llp_tpl4_disclaimer', true);
        $llp_tpl4_form = get_post_meta($post->ID, 'llp_tpl4_form', true);
        $atform = $llp_tpl4_form;

        $llp_tpl4_text1_1_default = 'Click Here For Free Instant Access';
        $llp_tpl4_text1_1 = get_post_meta($post->ID, 'llp_tpl4_text1_1', true);
        if (empty($llp_tpl4_text1_1)) {
            $llp_tpl4_text1_1 = $llp_tpl4_text1_1_default;
        }

        $llp_tpl4_text2_1_default = '"Free Report Reveals... The 5 Dead Simple Ways To Quickly XYZ Guaranteed"';
        $llp_tpl4_text2_1 = get_post_meta($post->ID, 'llp_tpl4_text2_1', true);
        if (empty($llp_tpl4_text2_1)) {
            $llp_tpl4_text2_1 = $llp_tpl4_text2_1_default;
        }

        $llp_image_1 = get_post_meta($post->ID, 'llp_image_1', true);
        if (empty($llp_image_1)) {
            $llp_image_1 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/lock.png';
        }
        $llp_image_1_link = get_post_meta($post->ID, 'llp_image_1_link', true);
        if (empty($llp_image_1_link)) {
            $llp_image_1_link = 'http://google.com';
        }

        $llp_tpl4_text3_default = 'Your Information is 100% Secure And Will Never Be Shared With Anyone.';
        $llp_tpl4_text3 = get_post_meta($post->ID, 'llp_tpl4_text3', true);
        if (empty($llp_tpl4_text3)) {
            $llp_tpl4_text3 = $llp_tpl4_text3_default;
        }
        ?>

        <div class="main-container">
            <div class="main wrapper clearfix"><h4>
                    <div id="llp_tpl4_text1_1" data-heading="Content"  data-id="2" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl4_text1_1)); ?>' data-wp_custom_field='llp_tpl4_text1_1' style="font-size:18px;"><?php echo stripslashes($llp_tpl4_text1_1); ?>
                    </div></h4>
                <h5><strong>
                        <div id="llp_tpl4_text2_1" data-heading="Content" data-id="3"  data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl4_text2_1)); ?>' data-wp_custom_field='llp_tpl4_text2_1' style="font-size:30px;font-weight:bold;"><?php echo stripslashes($llp_tpl4_text2_1); ?>
                        </div></strong></h5>
                <div class="form" id="llp_tpl4_form" data-heading="Optin Form" data-id="4" data-edit="true" data-type='form' data-wp_custom_field='llp_tpl4_form'>
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
                            <button type="submit" class="btn"><?php echo $atform['_optthemes_optinformsubmit']; ?></button>
                        </form>
                    <?php } else { ?>
                        <form action="#" method="post" onsubmit="" target="_top">
                            <input placeholder="Enter a Valid Email Here..." onfocus="this.placeholder = 'Enter a Valid Email Here...'" title="Enter a Valid Email Here..." value="" type="email" id="email" data-lb-inputemail="true"><button type="submit" class="btn">Get Instant Access</button>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </div>
        <footer><div class="wrapper clearfix">
                <a id="llp_image_1" data-heading="image"    class="#" style="display:inline-block;" data-image-link="#" data-id="5" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_1; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_1' href="#">
                    <img alt="image" src="<?php echo $llp_image_1; ?>"  style="display:block;">
                </a>
                <div id="abc"> </div>
                <div id="llp_tpl4_text3" class="#" data-heading="Disclaimer" style="display:inline-block;margin:0px 0px 0px 25px;" data-id="6" data-edit="true" data-type='custom' data-default='<?php echo $llp_tpl4_text3; ?>' data-wp_custom_field='llp_tpl4_text3' class="dspblk"><?php echo $llp_tpl4_text3; ?>
                </div>
            </div>
        </footer>
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