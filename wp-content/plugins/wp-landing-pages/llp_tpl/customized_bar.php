<?php
$nonce = wp_create_nonce("llp_nonce");
?>
<link rel="stylesheet" href="<?php echo llp_URL; ?>/llp_tpl/customized_bar.css" type="text/css" media="screen">
<style>
    html body #customized_bar h1.cstmz {
        <?php if ($do == 'edit') {
            ?>  display:block;
            <?php
        } else {
            ?>  display:none;
            <?php
        }
        ?>  margin-top:10px;
    }
</style>
<?php include_once llp_PATH . '/llp_tpl/customized_bar_js.php'; ?>

<div class="llp_response"> <span class="llp_response_text"></span> <span class="llp_response_close">×</span> </div>
<div id="customized_bar">
    <?php if ($do != 'edit') { ?>
        <h1 class="crp">
            <?php _e('Create page', 'wp-landing-pages'); ?>
        </h1>
        <input class="textfield crp" id="llp_create_page" />
        <a class="crp llp_btn" id="llp_save_create_page" href="#">Create</a>
    <?php } ?>
    <input type="hidden" id="pgid" value="<?php
    if ($do == 'edit') {

        echo $post->ID;
    }
    ?>" />
           <?php if ($do == 'edit') { ?>
        <h1 class="cstmz"><a href="<?php echo get_permalink($post->ID); ?>" target="_blank"><?php _e('View Live Page', 'wp-landing-pages'); ?></a></h1>
    <?php } ?>
    <h2 id="customized_bar_heading"></h2>
    <input type="hidden" id="dataIdc" />
    <input type="hidden" id="dataCSF" />
    <input class="textfield edtr" id="llp_text_editor" />
    <input class="textfield edtr" id="llp_link_editor" />
    <div class="data_type_link additional_options">
        <div class="link_optin_form">
            <input checked="checked" type="radio" name="use_as" id="use_as_link" />
            <label for="use_as_link" id="use_as_link_label">
                <?php _e('Link', 'wp-landing-pages'); ?>
            </label>
            <input type="radio" name="use_as" id="use_as_optin_form" />
            <label for="use_as_optin_form" id="use_as_optin_form_label">
                <?php _e('Optin Form', 'wp-landing-pages'); ?>
            </label>
        </div>
        <div class="use_as_link_options"> <strong class="data_link">
                <?php _e('link', 'wp-landing-pages'); ?>
            </strong>
            <input class="textfield" id="llp_link_a_editor" />
            <strong class="data_link">
                <?php _e('Open in new window', 'wp-landing-pages'); ?>
            </strong>
            <select id="llp_link_target_editor">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
        </div>
        <br />
    </div>
    <div class="data_type_image additional_options"> <img src="" class="img_c" /> <strong class="data_link">
            <?php _e('Image URL', 'wp-landing-pages'); ?>
        </strong>
        <input class="textfield edtr" id="llp_image_editor" />
        <strong class="data_link">
            <?php _e('Upload Image', 'wp-landing-pages'); ?>
        </strong>
        <form id="uploadImage" action="" method="post" accept="image/*" enctype="multipart/form-data">
            <div class="upload_image_c"> <span class="upload_image_t llp_btn">
                    <?php _e('Click here to upload image', 'wp-landing-pages'); ?>
                </span>
                <input type="file" id="upload_image" name="upload_image">
            </div>
            <input class="llp_btn" type="submit" value="Upload" />
        </form>
        <br />
        <strong class="data_link">
            <?php _e('Image Link', 'wp-landing-pages'); ?>
        </strong>
        <input class="textfield" id="llp_image_link_a_editor" />
    </div>
    <div class="data_type_bgimage additional_options"> <img src="" class="bgimg_c" /> <strong class="data_link">
            <?php _e('Image URL', 'wp-landing-pages'); ?>
        </strong>
        <input class="textfield edtr" id="llp_bgimage_editor" />
        <strong class="data_link">
            <?php _e('Upload Image', 'wp-landing-pages'); ?>
        </strong>
        <form id="uploadbgImage" action="" method="post" accept="image/*" enctype="multipart/form-data">
            <div class="upload_image_c"> <span class="upload_image_t llp_btn">
                    <?php _e('Click here to upload image', 'wp-landing-pages'); ?>
                </span>
                <input type="file" id="upload_image" name="upload_image">
            </div>
            <input class="llp_btn" type="submit" value="Upload" />
        </form>
        <br />
    </div>
    <div class="data_type_menu additional_options"> <strong class="data_menu">
            <?php _e('Select Menu', 'wp-landing-pages'); ?>
        </strong>
        <div style="clear:both;"></div>
        <?php
        $menus = get_terms('nav_menu');

        if (!empty($menus)) {
            ?>
            <select id="llp_data_menu" class="selectfield">
                <?php
                foreach ($menus as $menu) {
                    ?>
                    <option><?php echo $menu->name; ?></option>
                <?php } ?>
            </select>
            <div class="llp_menu_c">
                <?php
                foreach ($menus as $menu) {
                    ?>
                    <div data-menu="<?php echo $menu->name; ?>"> <?php echo wp_nav_menu(array('menu' => $menu->name)); ?> </div>
                <?php } ?>
            </div>
        <?php } ?>
        <br/>
        <br/>
    </div>
    <div class="data_type_custom additional_options">
        <textarea class="cstm_editor txteditor edtr textarea" id="llp_custom_editor"></textarea>
    </div>
    <textarea class="cstm_editor edtr textarea" id="llp_iframe_editor"></textarea>
    <div class="data_type_form additional_options">
        <div class="sf_c">
            <div class="tab_content" id="tab_optinautoresponder">
                <?php
                $atform = get_post_meta($post->ID, 'llp_tpl4_form', true);
                ?>
                <form autocomplete='off' id="atform_c">
                    <p>
                    <div class="form-field form-required">
                        <label for="_optthemes_webformcodehtml">
                            <strong class="data_link">
                                <?php _e('Autoresponder Code', 'wp-landing-pages'); ?>
                            </strong>
                            <p class="ptext">
                                <?php _e('Copy your form code from your autoresponder provider and paste it in the box below. Ensure you are using the non-javascript code, and paste ALL HTML code your autoresponder company gives you into this box', 'wp-landing-pages'); ?>
                            </p>
                        </label>
                        <textarea class="textarea" rows="3" columns="30" id="_optthemes_webformcodehtml" name="_optthemes_webformcodehtml"></textarea>
                        <a style="margin:3%;" id="codehtmlbtn" class="llp_btn" href="javascript:void(0);">Submit</a> </div>
                    </p>
                    <p>
                    <div class="sffields_c">
                        <?php
                        if ($atform) {

                            if ($atform['optintext']) {

                                foreach ($atform['optintext'] as $atform_fields => $key) {
                                    ?>
                                    <br />
                                    <div class="form-field form-required optinbelow"><strong class="data_link">
                                            <?php _e('Field Label', 'wp-landing-pages'); ?>
                                        </strong>
                                        <input type="text" value="<?php echo $key; ?>" name="optintext[<?php echo $atform_fields; ?>]" class="optinbelow textfield">
                                    </div>
                                    <div class="form-field form-required optinbelow">
                                        <input <?php if ($atform['optincheck'][$atform_fields] == 'yes') { ?>checked="checked"<?php } ?> type="checkbox" style="margin-left:3%;margin-right:3%;width: auto; height: 21px; vertical-align: middle;" value="yes" name="optincheck[<?php echo $atform_fields; ?>]">
                                        Show?</div>
                                    <input type="hidden" name="optintype[<?php echo $atform_fields; ?>]" value="<?php echo $atform['optintype'][$atform_fields]; ?>"/>
                                    <?php
                                }
                            }
                        }
                        ?>
                    </div>
                    </p>
                    <br />
                    <div class="other_fields_c">
                        <?php if ($atform) { ?>
                            <p>
                            <div class="form-field form-required formurl optinbelow"> <strong class="data_link">
                                    <?php _e('Form URL', 'wp-landing-pages'); ?>
                                </strong>
                                <input type="text" value="<?php echo $atform['_optthemes_optinformurl']; ?>" id="_optthemes_optinformurl" name="_optthemes_optinformurl" class="formurl optinbelow textfield">
                            </div>
                            </p>
                            <p>
                            <div class="form-field form-required formurl optinbelow"> <strong class="data_link">
                                    <?php _e('Hidden Fields', 'wp-landing-pages'); ?>
                                </strong>
                                <textarea class="textarea" rows="3" columns="30" id="_optthemes_webformhiddenhtml" name="_optthemes_webformhiddenhtml"><?php echo $atform['_optthemes_webformhiddenhtml']; ?></textarea>
                            </div>
                            </p>
                            <p>
                            <div class="form-field form-required formurl optinbelow"> <strong class="data_link">
                                    <?php _e('Submit Button Label', 'wp-landing-pages'); ?>
                                </strong>
                                <input type="text" value="<?php echo $atform['_optthemes_optinformsubmit']; ?>" id="_optthemes_optinformsubmit" name="_optthemes_optinformsubmit" class="formurl optinbelow textfield">
                            </div>
                            </p>
                        <?php } ?>
                    </div>
                </form>
                <p>
                <div class="form-field form-required">
                    <div id="popup_domination_hdn_div" style="display:none"></div>
                    <div id="popup_domination_hdn_div2" style="display:none"></div>
                </div>
                </p>
                <div style="clear:both;"></div>
            </div>
        </div>
    </div>
    <a class="llp_btn" id="llp_prv" href="#">
        <?php _e('Preview', 'wp-landing-pages'); ?>
    </a> <a class="llp_btn" id="llp_reset" href="#">
        <?php _e('Reset', 'wp-landing-pages'); ?>
    </a> <a class="llp_btn" id="llp_save" href="#">
        <?php _e('Save', 'wp-landing-pages'); ?>
    </a> </div>
<div class="m_c_customized">
