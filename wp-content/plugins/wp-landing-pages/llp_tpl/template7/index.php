<?php
// Landing Page Name: 2 in 1 Opt-in & Webinar Page
// Preview Image: tmp_7.jpg
// Template File: template7
$tpl_id = 'template7';
$tpl_dir_url = llp_URL . '/llp_tpl/' . $tpl_id . '/';
?>
<!DOCTYPE html>
<html>

    <meta http-equiv="content-type" content="text/html;charset=utf-8" />

    <head>

        <title><?php wp_title(); ?></title>

        <meta charset="utf-8">

        <meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">

        <link href="https://fonts.googleapis.com/css?family=Roboto:%20300,400,700,500%7CRaleway:400,300,500,600,700%7COxygen:300%7CSacramento" type="text/css" rel="stylesheet">

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
            $llp_button_1 = 'Get Started Now!';
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



        $llp_tpl7_h1_default = "John Smith";

        $llp_tpl7_h1 = get_post_meta($post->ID, 'llp_tpl7_h1', true);

        if (empty($llp_tpl7_h1)) {

            $llp_tpl7_h1 = $llp_tpl7_h1_default;
        }



        $llp_tpl7_h2_default = "Internet Marketer<br>&amp; Entrepreneur";

        $llp_tpl7_h2 = get_post_meta($post->ID, 'llp_tpl7_h2', true);

        if (empty($llp_tpl7_h2)) {

            $llp_tpl7_h2 = $llp_tpl7_h2_default;
        }



        $llp_tpl7_h3_default = "Lorem Ipsum Webinar";

        $llp_tpl7_h3 = get_post_meta($post->ID, 'llp_tpl7_h3', true);

        if (empty($llp_tpl7_h3)) {

            $llp_tpl7_h3 = $llp_tpl7_h3_default;
        }



        $llp_tpl7_h31_span_default = "Lorem Ipsum Amte";

        $llp_tpl7_h31_span = get_post_meta($post->ID, 'llp_tpl7_h31_span', true);

        if (empty($llp_tpl7_h31_span)) {

            $llp_tpl7_h31_span = $llp_tpl7_h31_span_default;
        }



        $llp_tpl7_days_default = "00";

        $llp_tpl7_days = get_post_meta($post->ID, 'llp_tpl7_days', true);

        if (empty($llp_tpl7_days)) {

            $llp_tpl7_days = $llp_tpl7_days_default;
        }



        $llp_tpl7_hours_default = "00";

        $llp_tpl7_hours = get_post_meta($post->ID, 'llp_tpl7_hours', true);

        if (empty($llp_tpl7_hours)) {

            $llp_tpl7_hours = $llp_tpl7_hours_default;
        }



        $llp_tpl7_mins_default = "00";

        $llp_tpl7_mins = get_post_meta($post->ID, 'llp_tpl7_mins', true);

        if (empty($llp_tpl7_mins)) {

            $llp_tpl7_mins = $llp_tpl7_mins_default;
        }



        $llp_tpl7_secs_default = "00";

        $llp_tpl7_secs = get_post_meta($post->ID, 'llp_tpl7_secs', true);

        if (empty($llp_tpl7_secs)) {

            $llp_tpl7_secs = $llp_tpl7_secs_default;
        }



        $llp_tpl7_cntnt_h1_default = "What is Lorem Ipsum";

        $llp_tpl7_cntnt_h1 = get_post_meta($post->ID, 'llp_tpl7_cntnt_h1', true);

        if (empty($llp_tpl7_cntnt_h1)) {

            $llp_tpl7_cntnt_h1 = $llp_tpl7_cntnt_h1_default;
        }



        $llp_tpl7_cntnt_default = "<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>

        <p><b>Lorem Ipsum is simply dummy text:</b></p>

        <ul>

          <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</li>

          <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</li>

          <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</li>

        </ul>

        <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. </p>

        <p>This offer ends  - January 12 @ midnight Pacific.</p>

        <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters.</p>";

        $llp_tpl7_cntnt = get_post_meta($post->ID, 'llp_tpl7_cntnt', true);

        if (empty($llp_tpl7_cntnt)) {

            $llp_tpl7_cntnt = $llp_tpl7_cntnt_default;
        }



        $llp_tpl7_textbox_default = "<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable.</p>";

        $llp_tpl7_textbox = get_post_meta($post->ID, 'llp_tpl7_textbox', true);

        if (empty($llp_tpl7_textbox)) {

            $llp_tpl7_textbox = $llp_tpl7_textbox_default;
        }



        $llp_tpl7_textbox_right_default = "There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour.";

        $llp_tpl7_textbox_right = get_post_meta($post->ID, 'llp_tpl7_textbox_right', true);

        if (empty($llp_tpl7_textbox_right)) {

            $llp_tpl7_textbox_right = $llp_tpl7_textbox_right_default;
        }



        $llp_tpl7_sign_default = "John Smith";

        $llp_tpl7_sign = get_post_meta($post->ID, 'llp_tpl7_sign', true);

        if (empty($llp_tpl7_sign)) {

            $llp_tpl7_sign = $llp_tpl7_sign_default;
        }



        $llp_tpl7_footer_default = 'Legal information';

        $llp_tpl7_footer = get_post_meta($post->ID, 'llp_tpl7_footer', true);

        if (empty($llp_tpl7_footer)) {

            $llp_tpl7_footer = $llp_tpl7_footer_default;
        }



        $llp_tpl7_profileimg_default = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/1.png';

        $llp_tpl7_profileimg = get_post_meta($post->ID, 'llp_tpl7_profileimg', true);

        if (empty($llp_tpl7_profileimg)) {

            $llp_tpl7_profileimg = $llp_tpl7_profileimg_default;
        }


        $llp_tpl7_getstart1_default = '  <div data-lb-id="optin-form" class="button" href="#" onclick="javascript:return window.triggerOptInForm()"><a href="#" onclick="javascript:return false"><span>Get Started Now!</span></a></div>
';

        $llp_tpl7_getstart1 = get_post_meta($post->ID, 'llp_tpl7_getstart1', true);

        if (empty($llp_tpl7_getstart1)) {

            $llp_tpl7_getstart1 = $llp_tpl7_getstart1_default;
        }

        $llp_tpl7_getstart2_default = '<div data-lb-id="optin-form" class="button second-btn" href="#" onclick="javascript:return window.triggerOptInForm()"><a href="#" onclick="javascript:return false"><span>Get Started Now!</span></a></div>
';

        $llp_tpl7_getstart2 = get_post_meta($post->ID, 'llp_tpl7_getstart2', true);

        if (empty($llp_tpl7_getstart2)) {

            $llp_tpl7_getstart2 = $llp_tpl7_getstart2_default;
        }
        ?>

        <div id="fb-root"></div>

        <div class="main-page">

            <div class="container">

                <div class="header-container">

                    <div class="collins">
                        <h1>   <div id="llp_tpl7_h1" data-heading="Content" data-id="1" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl7_h1)); ?>' data-wp_custom_field='llp_tpl7_h1'><?php echo stripslashes($llp_tpl7_h1); ?>
                            </div>
                        </h1>



                        <h1 class="bracket">}</h1>

                        <h2 id="llp_tpl7_h2" data-heading="Heading2" data-id="2" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl7_h2)); ?>' data-wp_custom_field='llp_tpl7_h2'><?php echo stripslashes($llp_tpl7_h2); ?></h2>

                    </div>

                    <div class="business-text">

                        <h3><div id="llp_tpl7_h3" style="display:inline-block;" data-heading="Heading3" data-id="3" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl7_h3)); ?>' data-wp_custom_field='llp_tpl7_h3'><?php echo stripslashes($llp_tpl7_h3); ?>
                            </div></h3>

                        <h3><div id="llp_tpl7_h31_span" style="display:inline-block;" data-heading="Heading3 Span" data-id="4" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl7_h31_span)); ?>' data-wp_custom_field='llp_tpl7_h31_span'><?php echo stripslashes($llp_tpl7_h31_span); ?>
                            </div> </h3>  </div>

                </div>

                <div class="content-bg">

                    <!----------  <div class="time">

                    <ul class="countdown" id="countdown">
                        <li>
                          <div class="time-wrap"> <span id="llp_tpl7_days" data-heading="Days" data-id="5" data-edit="true" data-type='text' data-default='<?php //echo htmlspecialchars(stripslashes($llp_tpl7_days));         ?>' data-wp_custom_field='llp_tpl7_days'><?php //echo stripslashes($llp_tpl7_days);         ?></span> </div>
                          <p class="timeRefDays">Days</p>
                        </li>
                        <li>
                          <div class="time-wrap"> <span id="llp_tpl7_hours" data-heading="Hours" data-id="6" data-edit="true" data-type='text' data-default='<?php //echo htmlspecialchars(stripslashes($llp_tpl7_hours));         ?>' data-wp_custom_field='llp_tpl7_hours'><?php //echo stripslashes($llp_tpl7_hours);         ?></span> </div>
                          <p class="timeRefHours">Hours</p>
                        </li>
                        <li>
                          <div class="time-wrap"> <span id="llp_tpl7_mins" data-heading="Mins" data-id="7" data-edit="true" data-type='text' data-default='<?php //echo htmlspecialchars(stripslashes($llp_tpl7_mins));         ?>' data-wp_custom_field='llp_tpl7_mins'><?php //echo stripslashes($llp_tpl7_mins);         ?></span> </div>
                          <p class="timeRefMinutes">Mins</p>
                        </li>
                        <li>
                          <div class="time-wrap"> <span id="llp_tpl7_secs" data-heading="Secs" data-id="8" data-edit="true" data-type='text' data-default='<?php //echo htmlspecialchars(stripslashes($llp_tpl7_secs));         ?>' data-wp_custom_field='llp_tpl7_secs'><?php //echo stripslashes($llp_tpl7_secs);         ?></span> </div>
                          <p class="timeRefSeconds">Secs</p>
                        </li>
                      </ul>

                    </div>------>

                    <div class="button">
                        <a id="llp_button_1" data-heading="Button"   data-id="100" data-edit="true" data-type='link' data-default='<?php echo $llp_button_1; ?>' data-wp_custom_field='llp_button_1' data-link='<?php echo $llp_button_1_link_a; ?>' data-target='1' href="<?php echo $llp_button_1_link_a; ?>" class="button llp_box" <?php if ($llp_button_1_link_a_open != "") { ?>target="_blank"<?php } ?>><?php echo $llp_button_1; ?></a>
                    </div>
                    <div id="inline1" style="max-width:400px;display: none;">
                        <div class="form" id="llp_tpl4_form" data-heading="Optin Form" data-id="101" data-edit="true" data-type='form' data-wp_custom_field='llp_tpl4_form'>
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
                                    <button type="submit" class="button blue-btn"><?php echo $atform['_optthemes_optinformsubmit']; ?></button>
                                </form>
                            <?php } else { ?>

                                <form action="#" method="post" onsubmit="" target="_top">
                                    <input placeholder="Enter a Valid Email Here..." onfocus="this.placeholder = 'Enter a Valid Email Here...'" title="Enter a Valid Email Here..." value="" type="email" id="email" data-lb-inputemail="true">
                                    <button type="submit" class="blue-btn">Get Started Now</button>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                    <div>

                        <h1>  <div id="llp_tpl7_cntnt_h1" style="display:inline-block;"data-heading="Content Heading" data-id="11" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl7_cntnt_h1)); ?>' data-wp_custom_field='llp_tpl7_cntnt_h1'><?php echo stripslashes($llp_tpl7_cntnt_h1); ?></div></h1>

                        <div id="llp_tpl7_cntnt" style="display:inline-block;" data-heading="Content" data-id="12" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl7_cntnt)); ?>' data-wp_custom_field='llp_tpl7_cntnt'><?php echo stripslashes($llp_tpl7_cntnt); ?></div>

                    </div>
                    <div class="button">
                        <a id="llp_button_1" data-heading="Button" data-id="100" data-edit="true" data-type='link' data-default='<?php echo $llp_button_1; ?>' data-wp_custom_field='llp_button_1' data-link='<?php echo $llp_button_1_link_a; ?>' data-target='1' href="<?php echo $llp_button_1_link_a; ?>" class="button second-btn llp_box" <?php if ($llp_button_1_link_a_open != "") { ?>target="_blank"<?php } ?>><?php echo $llp_button_1; ?></a>
                    </div>
                    <div id="inline1" style="max-width:400px;display: none;">
                        <div class="form" id="llp_tpl4_form" data-heading="Optin Form" data-id="101" data-edit="true" data-type='form' data-wp_custom_field='llp_tpl4_form'>
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
                                    <button type="submit" class="button second-btn"><?php echo $atform['_optthemes_optinformsubmit']; ?></button>
                                </form>
                            <?php } else { ?>

                                <form action="#" method="post" onsubmit="" target="_top">
                                    <input placeholder="Enter a Valid Email Here..." onfocus="this.placeholder = 'Enter a Valid Email Here...'" title="Enter a Valid Email Here..." value="" type="email" id="email" data-lb-inputemail="true">
                                    <button type="submit" class="blue-btn button second-btn">Get Started Now</button>
                                </form>
                            <?php } ?>
                        </div>
                    </div>

                </div>

                <div class="facebook-area">

                    <div class="text-box" id="llp_tpl7_textbox" style="display:inline-block;" data-heading="Text Box" data-id="15" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl7_textbox)); ?>' data-wp_custom_field='llp_tpl7_textbox'><?php echo stripslashes($llp_tpl7_textbox); ?></div>

                    <div class="text-box-bottom">

                        <div class="text-box-left">
                            <a id="llp_tpl7_profileimg" data-heading="image"    style="display:inline-block;" data-image-link="#" data-id="16" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_tpl7_profileimg; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_tpl7_profileimg' href="#">
                                <img alt="image" src="<?php echo $llp_tpl7_profileimg; ?>"  style="display:block;">
                            </a>

                            <div class="signechar" id="llp_tpl7_sign" data-heading="Sign" data-id="17" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl7_sign)); ?>' data-wp_custom_field='llp_tpl7_sign'><?php echo stripslashes($llp_tpl7_sign); ?></div>

                        </div>

                        <div id="llp_tpl7_textbox_right"  class="text-box-right"  data-heading="Text Box Right" data-id="18" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl7_textbox_right)); ?>' data-wp_custom_field='llp_tpl7_textbox_right'><?php echo stripslashes($llp_tpl7_textbox_right); ?>
                        </div>

                    </div>

                </div>

            </div>
            <!--   <a href="#">--->
            <div  id="llp_tpl7_footer" style="display:inline-block;width:150px;height:50px" class="footer" data-heading="Footer" data-id="19" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl7_footer)); ?>' data-wp_custom_field='llp_tpl7_footer'><?php echo stripslashes($llp_tpl7_footer); ?></div>

        </div>
        <!---</a>--->

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