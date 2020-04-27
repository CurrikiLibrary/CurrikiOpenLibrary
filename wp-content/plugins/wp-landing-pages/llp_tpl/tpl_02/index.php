<?php
// Landing Page Name: Mobile App
// Preview Image: tmp_2.jpg
// Template File: tpl_02
$tpl_id = 'tpl_02';
$tpl_dir_url = llp_URL . '/llp_tpl/' . $tpl_id . '/';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php wp_title(); ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1" />
        <link rel="stylesheet" href="<?php echo llp_URL; ?>/llp_tpl/<?php echo $llp_tpl_id; ?>/css/style.css" />
        <?php
        include llp_PATH . '/llp_tpl/llp_imp_js_css.php';
        ?>
        <?php #wp_head(); ?>
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
        // background image
        $bg_img1 = wplp_get_bg_image($tpl_id, $tpl_id . '_bg_img1', 'bg-image.jpg');
        ?>
        <style>
            .header-image { background: url(<?php echo $bg_img1; ?>) no-repeat center center fixed; -webkit-background-size: cover;
                            -moz-background-size: cover;
                            -o-background-size: cover;
                            background-size: cover;
                            height:615px;}
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
            <div class="container">
                <?php
// logo
                echo wplp_gen_image($tpl_id, 2, $tpl_id . '_logo', 'logo.png', 'logo');
                ?>
            </div>
            <div class="header-image">
                <div class="container">
                    <?php
// headline
                    echo wplp_gen_text($tpl_id, 3, '_headline', '<h1 class="h1main">GET IN<br />
TOUCH OUR<br />
NEWLY LAUNCHED<br />
MOBILE <br />
<span class="tplcolor">APPLICATION</span>
</h1>');
                    ?>

                </div>
            </div>
        </header>
        <div class="wplp-tpl2">
            <div class="container">
                <?php echo wplp_gen_text($tpl_id, 4, '_para2', "<h2>APP Features</h2>
                    <ul>
                        <li><img src='" . llp_URL . '/llp_tpl/' . $llp_tpl_id . "/images/01.jpg' />
                            <h2>Intractive</h2>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's </p></li>
                        <li><img src='" . llp_URL . '/llp_tpl/' . $llp_tpl_id . "/images/02.jpg' />
                            <h2>Intractive</h2>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's </p></li>
                        <li><img src='" . llp_URL . '/llp_tpl/' . $llp_tpl_id . "/images/03.jpg' />
                            <h2>Intractive</h2>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's </p></li>
                    </ul>", 'cntentboxwhite'); ?>
                <?php
                echo wplp_gen_text($tpl_id, 10, '_para3', '<h2>Sign up and Get Instant Access</h2>
                <h3>Mauris nunc ultrices sit amet felis vitae metus dolor.</h3>');
                echo wplp_gen_form($tpl_id, 11, $tpl_id . '_form', '');
                echo wplp_gen_text($tpl_id, 12, '_disclaimer', '<p class="diclaimer">Your information will *never* be shared or sold to 3rd party</p>');
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
