<?php
// Landing Page Name: Book Sales Page
// Preview Image: tmp_14.jpg
// Template File: template14
$tpl_id = 'template14';
$tpl_dir_url = llp_URL . '/llp_tpl/' . $tpl_id . '/';
?>
<!DOCTYPE html>
<html>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <head>
        <title>
            <?php wp_title(); ?>
        </title>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" type="text/css" rel="stylesheet">
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


        $llp_tpl15_5P_text1_default = '  <div class="header-title">Lorem . ipsum</div>
        <div class="title-small">Lorem ipsum dolor sit amet, consectetur </div>
        <p class="header-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit conse ctetur adipi scing elit</p>
        <p class="book-explains">This book explains how they did it, and <b>how you can too.</b> </p>';
        $llp_tpl15_5P_text1 = get_post_meta($post->ID, 'llp_tpl15_5P_text1', true);
        if (empty($llp_tpl15_5P_text1)) {
            $llp_tpl15_5P_text1 = $llp_tpl15_5P_text1_default;
        }


        $llp_tpl15_5P_text2_default = ' <div class="learn-box"><h2>Lorem ipsum:</h2>
          <ul>
            <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit conse ctetur adipi scing elit</li>
            <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit conse ctetur adipi scing elit</li>
            <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit conse ctetur adipi scing elit</li>
            <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit conse ctetur adipi scing elit</li>
          </ul>  </div>';
        $llp_tpl15_5P_text2 = get_post_meta($post->ID, 'llp_tpl15_5P_text2', true);
        if (empty($llp_tpl15_5P_text2)) {
            $llp_tpl15_5P_text2 = $llp_tpl15_5P_text2_default;
        }

        $llp_tpl15_5P_text3_default = ' Buy It Now';
        $llp_tpl15_5P_text3 = get_post_meta($post->ID, 'llp_tpl15_5P_text3', true);
        if (empty($llp_tpl15_5P_text3)) {
            $llp_tpl15_5P_text3 = $llp_tpl15_5P_text3_default;
        }

        $llp_tpl15_5P_text4_default = 'Fusce posuere leo porttitor velit rhoncus, in tristique ex vulputate?';
        $llp_tpl15_5P_text4 = get_post_meta($post->ID, 'llp_tpl15_5P_text4', true);
        if (empty($llp_tpl15_5P_text4)) {
            $llp_tpl15_5P_text4 = $llp_tpl15_5P_text4_default;
        }

        $llp_tpl15_5P_text5_default = '<p class="title-bottom">John Smith</p>    <div>Author of Wool</div>';
        $llp_tpl15_5P_text5 = get_post_meta($post->ID, 'llp_tpl15_5P_text5', true);
        if (empty($llp_tpl15_5P_text5)) {
            $llp_tpl15_5P_text5 = $llp_tpl15_5P_text5_default;
        }


        $llp_tpl15_5P_text6_default = 'Fusce posuere leo porttitor velit rhoncus, in tristique ex vulputate?';
        $llp_tpl15_5P_text6 = get_post_meta($post->ID, 'llp_tpl15_5P_text6', true);
        if (empty($llp_tpl15_5P_text6)) {
            $llp_tpl15_5P_text6 = $llp_tpl15_5P_text6_default;
        }


        $llp_tpl15_5P_text7_default = '  <p class="title-bottom">John Smith</p>
          <div>Former Editor, Writer’s Digest </div>';
        $llp_tpl15_5P_text7 = get_post_meta($post->ID, 'llp_tpl15_5P_text7', true);
        if (empty($llp_tpl15_5P_text7)) {
            $llp_tpl15_5P_text7 = $llp_tpl15_5P_text7_default;
        }

        $llp_tpl15_5P_text8_default = 'Fusce posuere leo porttitor velit rhoncus, in tristique ex vulputate';
        $llp_tpl15_5P_text8 = get_post_meta($post->ID, 'llp_tpl15_5P_text8', true);
        if (empty($llp_tpl15_5P_text8)) {
            $llp_tpl15_5P_text8 = $llp_tpl15_5P_text8_default;
        }

        $llp_tpl15_5P_text9_default = '  <p class="title-bottom">John Smith</p>
          <div>Industry Blogger </div>';
        $llp_tpl15_5P_text9 = get_post_meta($post->ID, 'llp_tpl15_5P_text9', true);
        if (empty($llp_tpl15_5P_text9)) {
            $llp_tpl15_5P_text9 = $llp_tpl15_5P_text9_default;
        }

        $llp_tpl15_5P_text10_default = 'Fusce posuere leo porttitor velit rhoncus, in tristique ex vulputate';
        $llp_tpl15_5P_text10 = get_post_meta($post->ID, 'llp_tpl15_5P_text10', true);
        if (empty($llp_tpl15_5P_text10)) {
            $llp_tpl15_5P_text10 = $llp_tpl15_5P_text10_default;
        }

        $llp_tpl15_5P_text11_default = '<p class="title-bottom">John Smith</p>
          <div>Founder, Author Marketing </div>';
        $llp_tpl15_5P_text11 = get_post_meta($post->ID, 'llp_tpl15_5P_text11', true);
        if (empty($llp_tpl15_5P_text11)) {
            $llp_tpl15_5P_text11 = $llp_tpl15_5P_text11_default;
        }


        $llp_tpl15_5P_text12_default = 'Fusce posuere leo porttitor velit rhoncus, in tristique ex vulputate';
        $llp_tpl15_5P_text12 = get_post_meta($post->ID, 'llp_tpl15_5P_text12', true);
        if (empty($llp_tpl15_5P_text12)) {
            $llp_tpl15_5P_text12 = $llp_tpl15_5P_text12_default;
        }

        $llp_tpl15_5P_text13_default = '  <p class="title-bottom">David Gaughran,</p>
          <div>Author “Let’s Get Digital? and “Let’s Get Visible</div>';
        $llp_tpl15_5P_text13 = get_post_meta($post->ID, 'llp_tpl15_5P_text13', true);
        if (empty($llp_tpl15_5P_text13)) {
            $llp_tpl15_5P_text13 = $llp_tpl15_5P_text13_default;
        }

        $llp_tpl15_5P_text14_default = ' <p> John Smith</p>';
        $llp_tpl15_5P_text14 = get_post_meta($post->ID, 'llp_tpl15_5P_text14', true);
        if (empty($llp_tpl15_5P_text14)) {
            $llp_tpl15_5P_text14 = $llp_tpl15_5P_text14_default;
        }


        $llp_tpl15_5P_text15_default = '    <p>Jennifer Carmelo</p>';
        $llp_tpl15_5P_text15 = get_post_meta($post->ID, 'llp_tpl15_5P_text15', true);
        if (empty($llp_tpl15_5P_text15)) {
            $llp_tpl15_5P_text15 = $llp_tpl15_5P_text15_default;
        }

        $llp_tpl15_5P_text16_default = '   <p class="authors-text">About the Authors</p>
        <p class="separately">Fusce posuere leo porttitor velit rhoncus, in tristique ex vulputate. Interdum et malesuada fames ac ante ipsum primis in faucibus. Praesent congue nec orci non dapibus. </p>
        <div class="small-text">
          <div class="small-box">
            <p>Fusce posuere leo porttitor velit rhoncus, in tristique ex vulputate. Interdum et malesuada fames ac ante ipsum primis in faucibus. Praesent congue nec orci non dapibus.<br>
              <br>
              Fusce posuere leo porttitor velit rhoncus, in tristique ex vulputate. Interdum et malesuada fames ac ante ipsum primis in faucibus. Praesent congue nec orci non dapibus. Sed id gravida nunc </p>
          </div>
          <div class="small-box right">
            <p>Fusce posuere leo porttitor velit rhoncus, in tristique ex vulputate. Interdum et malesuada fames ac ante ipsum primis in faucibus. Praesent congue nec orci non dapibus. Sed id gravida nunc<br>
              <br>
             Fusce posuere leo porttitor velit rhoncus, in tristique ex vulputate. Interdum et malesuada fames ac ante ipsum primis in faucibus. Praesent congue nec orci non dapibus. Sed id gravida nunc</p>
          </div>
     </div>';
        $llp_tpl15_5P_text16 = get_post_meta($post->ID, 'llp_tpl15_5P_text16', true);
        if (empty($llp_tpl15_5P_text16)) {
            $llp_tpl15_5P_text16 = $llp_tpl15_5P_text16_default;
        }


        $llp_tpl15_5P_text18_default = '<div>Copyright © 2014. Legal Information</div>';
        $llp_tpl15_5P_text18 = get_post_meta($post->ID, 'llp_tpl15_5P_text18', true);
        if (empty($llp_tpl15_5P_text18)) {
            $llp_tpl15_5P_text18 = $llp_tpl15_5P_text18_default;
        }

        $llp_tpl15_5P_text19_default = '<div>Buy<b> Write. Publish. Repeat.</b> on&nbsp;&nbsp;</div>'
                . '<img src="' . llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/amazonblack.png" alt=""><span> Now! </span>';
        $llp_tpl15_5P_text19 = get_post_meta($post->ID, 'llp_tpl15_5P_text19', true);
        if (empty($llp_tpl15_5P_text19)) {
            $llp_tpl15_5P_text19 = $llp_tpl15_5P_text19_default;
        }


        $llp_image_1 = get_post_meta($post->ID, 'llp_image_1', true);
        if (empty($llp_image_1)) {
            $llp_image_1 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/book.png';
        }
        $llp_image_1_link = get_post_meta($post->ID, 'llp_image_1_link', true);
        if (empty($llp_image_1_link)) {
            $llp_image_1_link = 'http://google.com';
        }

        $llp_image_2 = get_post_meta($post->ID, 'llp_image_2', true);
        if (empty($llp_image_2)) {
            $llp_image_2 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/number.png';
        }
        $llp_image_2_link = get_post_meta($post->ID, 'llp_image_2_link', true);
        if (empty($llp_image_2_link)) {
            $llp_image_2_link = 'http://google.com';
        }

        $llp_image_3 = get_post_meta($post->ID, 'llp_image_3', true);
        if (empty($llp_image_3)) {
            $llp_image_3 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/star.png';
        }
        $llp_image_3_link = get_post_meta($post->ID, 'llp_image_3_link', true);
        if (empty($llp_image_3_link)) {
            $llp_image_3_link = 'http://google.com';
        }

        $llp_image_4 = get_post_meta($post->ID, 'llp_image_4', true);
        if (empty($llp_image_4)) {
            $llp_image_4 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/amazon.png';
        }
        $llp_image_4_link = get_post_meta($post->ID, 'llp_image_4_link', true);
        if (empty($llp_image_4_link)) {
            $llp_image_4_link = 'http://google.com';
        }

        $llp_image_5 = get_post_meta($post->ID, 'llp_image_5', true);
        if (empty($llp_image_5)) {
            $llp_image_5 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/amazonuk.png';
        }
        $llp_image_5_link = get_post_meta($post->ID, 'llp_image_5_link', true);
        if (empty($llp_image_5_link)) {
            $llp_image_5_link = 'http://google.com';
        }

        $llp_image_6 = get_post_meta($post->ID, 'llp_image_6', true);
        if (empty($llp_image_6)) {
            $llp_image_6 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/kobo.png';
        }
        $llp_image_6_link = get_post_meta($post->ID, 'llp_image_6_link', true);
        if (empty($llp_image_6_link)) {
            $llp_image_6_link = 'http://google.com';
        }

        $llp_image_7 = get_post_meta($post->ID, 'llp_image_7', true);
        if (empty($llp_image_7)) {
            $llp_image_7 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/nook.png';
        }
        $llp_image_7_link = get_post_meta($post->ID, 'llp_image_7_link', true);
        if (empty($llp_image_7_link)) {
            $llp_image_7_link = 'http://google.com';
        }

        $llp_image_8 = get_post_meta($post->ID, 'llp_image_8', true);
        if (empty($llp_image_8)) {
            $llp_image_8 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/ibooks.png';
        }
        $llp_image_8_link = get_post_meta($post->ID, 'llp_image_8_link', true);
        if (empty($llp_image_8_link)) {
            $llp_image_8_link = 'http://google.com';
        }

        $llp_image_9 = get_post_meta($post->ID, 'llp_image_9', true);
        if (empty($llp_image_9)) {
            $llp_image_9 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/quote.png';
        }
        $llp_image_9_link = get_post_meta($post->ID, 'llp_image_9_link', true);
        if (empty($llp_image_9_link)) {
            $llp_image_9_link = 'http://google.com';
        }

        $llp_image_10 = get_post_meta($post->ID, 'llp_image_10', true);
        if (empty($llp_image_10)) {
            $llp_image_10 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/hugh.jpg';
        }
        $llp_image_10_link = get_post_meta($post->ID, 'llp_image_10_link', true);
        if (empty($llp_image_10_link)) {
            $llp_image_10_link = 'http://google.com';
        }

        $llp_image_11 = get_post_meta($post->ID, 'llp_image_11', true);
        if (empty($llp_image_11)) {
            $llp_image_11 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/quote.png';
        }
        $llp_image_11_link = get_post_meta($post->ID, 'llp_image_11_link', true);
        if (empty($llp_image_11_link)) {
            $llp_image_11_link = 'http://google.com';
        }

        $llp_image_12 = get_post_meta($post->ID, 'llp_image_12', true);
        if (empty($llp_image_12)) {
            $llp_image_12 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/jane.jpg';
        }
        $llp_image_12_link = get_post_meta($post->ID, 'llp_image_12_link', true);
        if (empty($llp_image_12_link)) {
            $llp_image_12_link = 'http://google.com';
        }

        $llp_image_13 = get_post_meta($post->ID, 'llp_image_13', true);
        if (empty($llp_image_13)) {
            $llp_image_13 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/quote.png';
        }
        $llp_image_13_link = get_post_meta($post->ID, 'llp_image_13_link', true);
        if (empty($llp_image_13_link)) {
            $llp_image_13_link = 'http://google.com';
        }

        $llp_image_14 = get_post_meta($post->ID, 'llp_image_14', true);
        if (empty($llp_image_14)) {
            $llp_image_14 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/carol.jpg';
        }
        $llp_image_14_link = get_post_meta($post->ID, 'llp_image_14_link', true);
        if (empty($llp_image_14_link)) {
            $llp_image_14_link = 'http://google.com';
        }

        $llp_image_15 = get_post_meta($post->ID, 'llp_image_15', true);
        if (empty($llp_image_15)) {
            $llp_image_15 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/quote.png';
        }
        $llp_image_15_link = get_post_meta($post->ID, 'llp_image_15_link', true);
        if (empty($llp_image_15_link)) {
            $llp_image_15_link = 'http://google.com';
        }

        $llp_image_16 = get_post_meta($post->ID, 'llp_image_16', true);
        if (empty($llp_image_16)) {
            $llp_image_16 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/jim.jpg';
        }
        $llp_image_16_link = get_post_meta($post->ID, 'llp_image_16_link', true);
        if (empty($llp_image_16_link)) {
            $llp_image_16_link = 'http://google.com';
        }

        $llp_image_17 = get_post_meta($post->ID, 'llp_image_17', true);
        if (empty($llp_image_17)) {
            $llp_image_17 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/quote.png';
        }
        $llp_image_17_link = get_post_meta($post->ID, 'llp_image_17_link', true);
        if (empty($llp_image_17_link)) {
            $llp_image_17_link = 'http://google.com';
        }

        $llp_image_18 = get_post_meta($post->ID, 'llp_image_18', true);
        if (empty($llp_image_18)) {
            $llp_image_18 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/david.jpg';
        }
        $llp_image_18_link = get_post_meta($post->ID, 'llp_image_18_link', true);
        if (empty($llp_image_18_link)) {
            $llp_image_18_link = 'http://google.com';
        }

        $llp_image_19 = get_post_meta($post->ID, 'llp_image_19', true);
        if (empty($llp_image_19)) {
            $llp_image_19 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/sean.jpg';
        }
        $llp_image_19_link = get_post_meta($post->ID, 'llp_image_19_link', true);
        if (empty($llp_image_19_link)) {
            $llp_image_19_link = 'http://google.com';
        }

        $llp_image_20 = get_post_meta($post->ID, 'llp_image_20', true);
        if (empty($llp_image_20)) {
            $llp_image_20 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/jhonny.jpg';
        }
        $llp_image_20_link = get_post_meta($post->ID, 'llp_image_20_link', true);
        if (empty($llp_image_20_link)) {
            $llp_image_20_link = 'http://google.com';
        }

        $llp_image_21 = get_post_meta($post->ID, 'llp_image_21', true);
        if (empty($llp_image_21)) {
            $llp_image_21 = llp_URL . '/llp_tpl/' . $llp_tpl_id . '/images/amazonblack.png';
        }
        $llp_image_21_link = get_post_meta($post->ID, 'llp_image_21_link', true);
        if (empty($llp_image_21_link)) {
            $llp_image_21_link = 'http://google.com';
        }
        ?>
        <div class="main-page">
            <div class="header-bg">
                <div class="header-center">
                    <div class="header-left">
                        <div id="llp_tpl15_5P_text1" data-heading="Content" data-id="1" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl15_5P_text1)); ?>' data-wp_custom_field='llp_tpl15_5P_text1'><?php echo stripslashes($llp_tpl15_5P_text1); ?> </div>
                        <div id="llp_tpl15_5P_text2" data-heading="Content" data-id="2" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl15_5P_text2)); ?>' data-wp_custom_field='llp_tpl15_5P_text2'><?php echo stripslashes($llp_tpl15_5P_text2); ?> </div>
                    </div>
                    <div class="header-right">
                        <div class="book"> <a id="llp_image_1" data-heading="image"    style="display:block;" data-image-link="#" data-id="3" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_1; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_1' href="#"> <img alt="image" src="<?php echo $llp_image_1; ?>"  style="display:block;"> </a> </div>
                        <div class="number"> <a id="llp_image_2" data-heading="image"    style="display:block;" data-image-link="#" data-id="4" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_2; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_2' href="#"> <img alt="image" src="<?php echo $llp_image_2; ?>"  style="display:block;"> </a> </div>
                        <div class="star"> <a id="llp_image_3" data-heading="image"    style="display:block;" data-image-link="#" data-id="5" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_3; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_3' href="#"> <img alt="image" src="<?php echo $llp_image_3; ?>"  style="display:block;"> </a> </div>
                    </div>
                </div>
            </div>
            <div class="orange-box">
                <div class="orange-box-center">
                    <div class="left-content">
                        <div id="llp_tpl15_5P_text3" data-heading="Content" data-id="6" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl15_5P_text3)); ?>' data-wp_custom_field='llp_tpl15_5P_text3'><?php echo stripslashes($llp_tpl15_5P_text3); ?> </div>
                    </div>
                    <div class="right-content"> <a id="llp_image_4" data-heading="image"    style="display:inline-block;" data-image-link="#" data-id="7" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_4; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_4' href="#"> <img alt="image" src="<?php echo $llp_image_4; ?>"  style="display:block;"> </a> <a id="llp_image_5" data-heading="image"    style="display:inline-block;" data-image-link="#" data-id="8" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_5; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_5' href="#"> <img alt="image" src="<?php echo $llp_image_5; ?>"  style="display:block;"> </a> <a id="llp_image_6" data-heading="image"    style="display:inline-block;" data-image-link="#" data-id="9" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_6; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_6' href="#"> <img alt="image" src="<?php echo $llp_image_6; ?>"  style="display:block;"> </a> <a id="llp_image_7" data-heading="image"   style="display:inline-block;" data-image-link="#" data-id="10" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_7; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_7' href="#"> <img alt="image" src="<?php echo $llp_image_7; ?>"  style="display:block;"> </a> <a id="llp_image_8" data-heading="image"   style="display:inline-block;" data-image-link="#" data-id="11" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_8; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_8' href="#"> <img alt="image" src="<?php echo $llp_image_8; ?>"  style="display:block;"> </a> </div>
                </div>
            </div>
            <div class="content-area">
                <div class="content-text">
                    <ul>
                        <li>
                            <div class="publish-report" > <a id="llp_image_9" data-heading="image" style="display:inline-block;" data-image-link="#" data-id="12" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_9; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_9' href="#"> <img alt="image" src="<?php echo $llp_image_9; ?>"  style="margin:0px 0px 0px 0px;"> </a>
                                <div style="clear:both"></div>
                                <div class="testimonial-text">
                                    <div id="llp_tpl15_5P_text4" data-heading="Content" data-id="13" style="margin:0px 0px 0px 0px;" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl15_5P_text4)); ?>' data-wp_custom_field='llp_tpl15_5P_text4'><?php echo stripslashes($llp_tpl15_5P_text4); ?> </div>
                                </div>
                            </div>
                            <div class="img"> <a id="llp_image_10" data-heading="image"    style="display:inline-block;" data-image-link="#" data-id="14" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_10; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_10' href="#"> <img alt="image" src="<?php echo $llp_image_10; ?>"  style="display:block;"> </a> </div>
                            <div id="llp_tpl15_5P_text5" data-heading="Content" data-id="15"  style="display:inline-block;" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl15_5P_text5)); ?>' data-wp_custom_field='llp_tpl15_5P_text5'><?php echo stripslashes($llp_tpl15_5P_text5); ?> </div>
                        </li>
                        <li>
                            <div class="publish-report"> <a id="llp_image_11" data-heading="image"    style="display:inline-block;" data-image-link="#" data-id="16" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_11; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_11' href="#"> <img alt="image" src="<?php echo $llp_image_11; ?>"  style="display:block;"> </a>
                                <p class="testimonial-text">

                                <div id="llp_tpl15_5P_text6" data-heading="Content"  style="display:inline-block;" data-id="17" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl15_5P_text6)); ?>' data-wp_custom_field='llp_tpl15_5P_text6'><?php echo stripslashes($llp_tpl15_5P_text6); ?> </div>
                                </p>
                            </div>
                            <div class="img"> <a id="llp_image_12" data-heading="image"    style="display:inline-block;" data-image-link="#" data-id="18" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_12; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_12' href="#"> <img alt="image" src="<?php echo $llp_image_12; ?>"  style="display:block;"> </a> </div>
                            <div id="llp_tpl15_5P_text7" data-heading="Content" data-id="19"  style="display:inline-block;" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl15_5P_text7)); ?>' data-wp_custom_field='llp_tpl15_5P_text7'><?php echo stripslashes($llp_tpl15_5P_text7); ?> </div>
                        </li>
                        <li>
                            <div class="publish-report"> <a id="llp_image_13" data-heading="image"    style="display:inline-block;" data-image-link="#" data-id="20" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_13; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_13' href="#"> <img alt="image" src="<?php echo $llp_image_13; ?>"  style="display:block;"> </a>
                                <p class="testimonial-text">

                                <div id="llp_tpl15_5P_text8" data-heading="Content" data-id="21" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl15_5P_text8)); ?>' data-wp_custom_field='llp_tpl15_5P_text8'><?php echo stripslashes($llp_tpl15_5P_text8); ?> </div>
                                </p>
                            </div>
                            <div class="img"> <a id="llp_image_14" data-heading="image"    style="display:inline-block;" data-image-link="#" data-id="22" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_14; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_14' href="#"> <img alt="image" src="<?php echo $llp_image_14; ?>"  style="display:block;"> </a> </div>
                            <div id="llp_tpl15_5P_text9" data-heading="Content" data-id="23"  style="display:inline-block;" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl15_5P_text9)); ?>' data-wp_custom_field='llp_tpl15_5P_text9'><?php echo stripslashes($llp_tpl15_5P_text9); ?> </div>
                        </li>
                        <li>
                            <div class="publish-report"> <a id="llp_image_15" data-heading="image"    style="display:inline-block;" data-image-link="#" data-id="24" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_15; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_15' href="#"> <img alt="image" src="<?php echo $llp_image_15; ?>"  style="display:block;"> </a>
                                <p class="testimonial-text">

                                <div id="llp_tpl15_5P_text10" data-heading="Content" data-id="25"  style="display:inline-block;" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl15_5P_text10)); ?>' data-wp_custom_field='llp_tpl15_5P_text10'><?php echo stripslashes($llp_tpl15_5P_text10); ?> </div>
                                </p>
                            </div>
                            <div class="img"> <a id="llp_image_16" data-heading="image"    style="display:inline-block;" data-image-link="#" data-id="26" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_16; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_16' href="#"> <img alt="image" src="<?php echo $llp_image_16; ?>"  style="display:block;"> </a> </div>
                            <div id="llp_tpl15_5P_text11" data-heading="Content" data-id="27"   style="display:inline-block;" style="display:inline-block;" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl15_5P_text11)); ?>' data-wp_custom_field='llp_tpl15_5P_text11'>
                                <?php echo stripslashes($llp_tpl15_5P_text11); ?>
                            </div>
                        </li>
                        <li class="last">
                            <div class="publish-report"> <a id="llp_image_17" data-heading="image"    style="display:inline-block;" data-image-link="#" data-id="28" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_17; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_17' href="#"> <img alt="image" src="<?php echo $llp_image_17; ?>"  style="display:block;"> </a>
                                <p class="testimonial-text">

                                <div id="llp_tpl15_5P_text12" data-heading="Content"  style="display:inline-block;" data-id="29" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl15_5P_text12)); ?>' data-wp_custom_field='llp_tpl15_5P_text12'><?php echo stripslashes($llp_tpl15_5P_text12); ?> </div>
                                </p>
                            </div>
                            <div class="img"> <a id="llp_image_18" data-heading="image"    style="display:inline-block;" data-image-link="#" data-id="30" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_18; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_18' href="#"> <img alt="image" src="<?php echo $llp_image_18; ?>"  style="display:block;"> </a> </div>
                            <div id="llp_tpl15_5P_text13" data-heading="Content"  style="display:inline-block;" data-id="31" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl15_5P_text13)); ?>' data-wp_custom_field='llp_tpl15_5P_text13'><?php echo stripslashes($llp_tpl15_5P_text13); ?> </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="authors">
                <div class="authors-center">
                    <div class="left-text">
                        <div class="left-img"> <a id="llp_image_19" data-heading="image"    style="display:inline-block;" data-image-link="#" data-id="32" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_19; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_19' href="#"> <img alt="image" src="<?php echo $llp_image_19; ?>"  style="display:block;"> </a>
                            <div id="llp_tpl15_5P_text14" data-heading="Content"  style="text-align:center;"  data-id="33" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl15_5P_text14)); ?>' data-wp_custom_field='llp_tpl15_5P_text14'><?php echo stripslashes($llp_tpl15_5P_text14); ?> </div>
                        </div>
                        <div class="right-img"> <a id="llp_image_20" data-heading="image"    style="display:inline-block;" data-image-link="#" data-id="34" data-edit="true" data-type='image' data-default='<img src="<?php echo $llp_image_20; ?>" border="0" alt="logo"  >' data-wp_custom_field='llp_image_20' href="#"> <img alt="image" src="<?php echo $llp_image_20; ?>"  style="display:block;"> </a>
                            <div id="llp_tpl15_5P_text15" data-heading="Content" data-id="35" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl15_5P_text15)); ?>' data-wp_custom_field='llp_tpl15_5P_text15'><?php echo stripslashes($llp_tpl15_5P_text15); ?> </div>
                        </div>
                    </div>
                    <div class="right-text">
                        <div id="llp_tpl15_5P_text16" data-heading="Content" style="display:inline-block;" data-id="36" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl15_5P_text16)); ?>' data-wp_custom_field='llp_tpl15_5P_text16'><?php echo stripslashes($llp_tpl15_5P_text16); ?> </div>
                    </div>
                </div>
            </div>
            <div class="buy">
                <div class="box"> <a href="#">
                        <p>
                        <div id="llp_tpl15_5P_text19" data-heading="Content" style="display:inline-block;" data-id="37" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl15_5P_text19)); ?>' data-wp_custom_field='llp_tpl15_5P_text19'><?php echo stripslashes($llp_tpl15_5P_text19); ?> </div>
                        </p>
                    </a> </div>
            </div>
            <div class="footer">
                <div id="llp_tpl15_5P_text18" data-heading="Content" style="display:inline-block;" data-id="38" data-edit="true" data-type='custom' data-default='<?php echo htmlspecialchars(stripslashes($llp_tpl15_5P_text18)); ?>' data-wp_custom_field='llp_tpl15_5P_text18'><?php echo stripslashes($llp_tpl15_5P_text18); ?> </div>
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
    </body>
</html>