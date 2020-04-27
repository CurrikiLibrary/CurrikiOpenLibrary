<?php
// Landing Page Name: Recipe Book
// Preview Image: template15.jpg
// Template File: template15
$tpl_id = 'template15';
$tpl_dir_url = llp_URL . '/llp_tpl/' . $tpl_id . '/';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>
            <?php wp_title(); ?>
        </title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1" />
        <link rel="stylesheet" href="<?php echo llp_URL; ?>/llp_tpl/<?php echo $llp_tpl_id; ?>/css/style.css" />
        <?php
        include llp_PATH . '/llp_tpl/llp_imp_js_css.php';
        ?>
    </head>
    <body class="bg-main">
        <?php
        if (is_user_logged_in()) {
            if ($do == 'create' || $do == 'edit') {
                include llp_PATH . '/llp_tpl/customized_bar.php';
            } else {
                wplp_setPostViews(get_the_ID());
            }
        }

        // background images
        $bg_img1 = wplp_get_bg_image($tpl_id, $tpl_id . '_bg_img1', 'bg-image.jpg');
        ?>
        <style>
            .bg-main {
                background: url(<?php echo $bg_img1; ?>) no-repeat center center fixed;
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
            }
        </style>
        <?php
// background image change button
        if (is_user_logged_in()) {
            if ($do == 'create' || $do == 'edit') {
                echo wplp_gen_bg_img($tpl_id, 18, $tpl_id . '_bg_img1', $bg_img1, __('Change Background Image', 'wp-landing-pages-pro'), 'bgimgbtnmrg');
            }
        }
        ?>
        <header id="Header">
            <?php
// logo
            echo wplp_gen_image($tpl_id, 2, $tpl_id . '_logo', 'logo.png', 'logo');
            ?>
            <div class="wplp-tpl1">
                <div class="container">
                    <?php
// headline
                    echo wplp_gen_text($tpl_id, 3, '_headline', '<h1 class="h1main">Invite a Friend to Get <br>Free Access of Recipes Book</h1>');
                    ?>
                    <?php echo wplp_gen_form($tpl_id, 11, $tpl_id . '_form', ''); ?>
                    <?php
                    echo wplp_gen_text($tpl_id, 12, '_disclaimer', '<p class="diclaimer">Your information will *never* be shared or sold to 3rd party</p>');
                    echo wplp_gen_text($tpl_id, 13, '_para1', '<p style="text-align: center;">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been <br />
      the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley <br />
      of type and scrambled it to make a type specimen book. It has survived not only five centuries, <br />
      but also the leap into electronic typesetting, remaining essentially unchanged.</p>');
                    ?>

                </div>
            </div>
            <footer>
                <div class="container">
                    <?php echo wplp_gen_text($tpl_id, 16, '_copyright', '<div class="copyright">© ' . date('Y') . '. WP Landing Pages. All Rights Reserved.</div>', 'ftrcnt1'); ?>
                    <?php echo wplp_gen_text($tpl_id, 17, '_footermenu', '<div class="fmenu"><a href="#">Privacy Policy</a> | <a href="#">Terms of Services</a></div>', 'ftrcnt2'); ?>
                    <div class="clear"></div>
                </div>
            </footer>
    </body>
</html>
