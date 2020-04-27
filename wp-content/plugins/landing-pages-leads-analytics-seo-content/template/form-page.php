<?php $setting = get_option(FORM_APPLICATION . "settings");?>
<style>
    <?php echo file_get_contents( dirname(__FILE__) . '/css/form-page.css'); ?>
</style>
<?php 
$title_order = isset($title_post['title']) ? $title_post['title'] : $title_post ;
?>

<div class="form-post-landing-page">
    <h2><?php echo $title_order ?></h2>
    <?php if (!empty($msg)) {
            echo "<div class='form-success-message'>$msg</div>";
    }?>
    <div class="form-application">

        <form action="" method="post" autocomplete="off" >
            <div class="row-form">
                <div class="label one-label">
                    <label for="name_id"><?php echo isset($setting['title-name']) ? $setting['title-name'] : self::$titles['title-name']; ?></label>
                </div>
                <div class="input one-input">
                    <div class='error-message'><?php echo isset($err['name']) ? $err['name'] : "" ?></div>  
                    <input type="text" id="name_id" name="name" value="<?php echo isset($params['name']) ? $params['name'] : '' ;?>" class="<?php echo isset($err['name']) ? "error" : '' ?>" />
                </div>
            </div>
            <div class="row-form">
                <div class="label one-label">
                    <label for="email_id"><?php echo isset($setting['title-email']) ? $setting['title-email'] : self::$titles['title-email']; ?></label>
                </div>
                <div class="input one-input">
                    <div class='error-message'><?php echo isset($err['email']) ?  $err['email'] : "" ?></div>  
                    <input type="text" id="email_id" name="email" value="<?php echo isset($params['email']) ? $params['email'] : '' ;?>" class="<?php echo isset($err['email']) ? "error" : '' ?>" />
                </div>
            </div>
            <div class="row-form">
                <div class="label one-label">
                    <label for="message_id"><?php echo isset($setting['title-message']) ? $setting['title-message'] : self::$titles['title-message']; ?></label>
                </div>
                <div class="input one-input">
                    <div class='error-message'><?php echo isset($err['message']) ? $err['message'] : "" ?></div>  
                    <textarea style="height: 68px" cols="29" rows="3" name="message" class="<?php echo isset($err['message']) ? "error" : '' ?>" id="message_id"><?php echo str_replace('%landing-page-title%', $title_order, isset($params['message']) ? $params['message'] : (isset($title_post['text']) ? $title_post['text'] : 'I want to order %landing-page-title% for...' ) );  ;?></textarea>
                </div>
            </div>
            <div class="row-form">
                <div class="label" style="padding-top: 0px;"><?php echo isset($setting['title-phone']) ? $setting['title-phone'] : self::$titles['title-phone']; ?></div>
                <div class="input" style="">
                    <?php $check = isset($params['phone_radio']) && $params['phone_radio'] == 'yes';?>
                    <script type="text/javascript">
                        var _phone = "";
                        function showPhone(show)
                        {
                            disp = jQuery("#phone_id").css('display');
                            if (disp == 'none' && show === true) {
                                jQuery("#phone_id").show('slow');
                                if (_phone != "") {
                                    jQuery("#phone_id").val(_phone);
                                }
                            } else if (show === false){
                                jQuery("#phone_id").hide('slow');
                                _phone = jQuery("#phone_id").val();
                                jQuery("#phone_id").val("");
                            }
                        }
                    </script>
                    <div class='error-message'><?php echo isset($err['phone_radio']) ? $err['phone_radio'] : "" ?></div>  
                    <div class='error-message'><?php echo isset($err['phone']) ? $err['phone'] : "" ?></div>  
                    <input type="radio" onclick="showPhone(true)" 
                        name="phone_radio" id="check_phone_yes" <?php echo $check ? 'checked="checked"' : ''?> 
                        value="yes" style="margin-bottom:7px; cursor: pointer;" />&nbsp;<label for="check_phone_yes" style="cursor: pointer;">Yes</label> 
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" onclick="showPhone(false)" 
                        name="phone_radio" id="check_phone_no" <?php echo isset($params['phone_radio']) && $params['phone_radio'] == 'no'? 'checked="checked"' : ''?> 
                        value="no" style="margin-bottom:7px; cursor: pointer;" />&nbsp;<label for="check_phone_no" style="cursor: pointer;">No</label> 

                    <input type="text" name="phone" id="phone_id" class="<?php echo $check ? "error" : '' ?>" value="<?php echo $check ? $params['phone'] : '' ;?>" style="display: <?php echo isset($params['phone_radio']) && $params['phone_radio'] == 'yes' ? 'block' : 'none';?>; ">
                </div>
            </div>
            <div class="row-form">
                <div class="label" style="padding-top: 0px;">
                </div>
                <div class="input auto-width" >
                    <input type="submit" class="buttom-form-app" value="<?php echo isset($setting['title-button']) ? $setting['title-button'] : self::$titles['title-button']; ?>">
                </div>
            </div>
            <?php wp_nonce_field('form-send', 'create-application' ); ?>
        </form>
    </div>
    <?php 
        $categories = get_the_category(self::$post->ID); 
        $check = checkCategory($categories, 'landing-page', true);
        if ($check) {
            if (!isset($title_post['img_id']) || (isset($title_post['img_id']) && $title_post['img_id'] == -1 ) ) {
                $n = count($check);
                $img = "";
                $imgFile = "";
                for($i = 0; $i < $n; $i++) {
                    $folder_check = self::translit( urldecode( $check[$i] ) );
                    $imgs = self::getDirs(plugin_dir_path( dirname(__FILE__) ) . "img/cat", $folder_check, array('.png', '.jpg', '.gif', 'jpeg') );
                    if ( ( $k = count($imgs) ) > 0) {
                        $rand = rand(0, $k - 1 );
                        $img = plugin_dir_url(dirname(__FILE__)) . "img/cat/" . $folder_check . "/" . $imgs[ $rand ];
                        $imgFile = plugin_dir_path (dirname(__FILE__) ). "img/cat/" . $folder_check . "/" . $imgs[ $rand ] ;
                    }
                    if (!empty($img)) {
                        break;
                    }
                }
            } elseif (isset($title_post['img_id'])) {
                global $wpdb;
                $data_img = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID = {$title_post['img_id']}", ARRAY_A );
                $img = isset($data_img['guid']) ? $data_img['guid'] : '' ;
                $imgFile = get_attached_file( $title_post['img_id'] );
            }


            if (!empty($img) && file_exists($imgFile)) {
                list($w, $h) = getimagesize($imgFile);
                if ($w > 0 && $h > 0) {
                    $koe = $w/380;  // reduction factor
                    $new_h = ceil($h / $koe);
                    $new_w = ceil($w / $koe);
                    echo '<div class="form-application-image">';
                    echo '<img src="' . $img. '" title="' . $title_post['title'] . '" style="height:' . $new_h . 'px; width: ' . $new_w . 'px ;">';

                    echo '</div>'; 
                }
            }

        }
        
        if(!empty($setting['text-description'])) {
            echo '<div class="text-description">';
            echo str_replace("\n", "<br />", base64_decode( $setting['text-description'] ) ) ;                                                                                 
            echo '</div>';
        }
    ?>
</div>
<div style="clear: both;"></div>