<div class="wrap">
    <?php if (!empty($msg)) : ?>
        <div class="updated" style="padding: 20px; font-size:15px; line-height: 25px;">
            <?php echo $msg; ?>
        </div>
        <?php endif; ?>
    <?php if (!empty($error)) : ?>
        <div class="error" style="padding: 20px; font-size:15px;line-height: 25px;">
            <?php echo $error;?>
        </div>
        <?php endif; ?>
    <style>
        <?php echo file_get_contents( dirname(__FILE__) . '/css/jquery.arcticmodal-0.3.css');?>
    </style>
    <script type="text/javascript">
        <?php echo file_get_contents( dirname(__FILE__) . '/js/jquery.arcticmodal-0.3.min.js');?>

        function showSetting(t, class_obj)
        {
            dis = jQuery("." + class_obj).css('display');
            if (dis == 'none') {
                jQuery("." + class_obj).show('slow');

                jQuery(t).find( '.dashicons' ).removeClass('dashicons-arrow-down').addClass('dashicons-arrow-up');
                jQuery(t).find( '.button-setting' ).html('Hide');
            } else {
                jQuery("." + class_obj).hide('slow');
                jQuery(t).find( '.dashicons' ).removeClass('dashicons-arrow-up').addClass('dashicons-arrow-down');
                jQuery(t).find( '.button-setting' ).html('Show');
                //jQuery(t).html('Show');
            }
        }
        var show_form = "";
        function ShowInfo(id)
        {
            disp = jQuery("#" + id).css('display');
            if (disp == 'none') {
                jQuery("#" + id).show('slow');
                if (show_form != "" && show_form != id) {
                    jQuery("#" + show_form).hide('slow');
                    show_form = id;
                }
            } else{
                jQuery("#" + id).hide('slow');
            }
            if (show_form == "") {
                show_form = id;
            }
        }
    </script>
    <div>

        <div class="setting-landing-page">
            <div class="title-setting-smtp">
                Order form settings  
            </div>
            <div class="form-setting" style="display: none;">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="clear">
                        <div class="label">
                            <label for="id-title-name">Form field name</label>
                        </div>
                        <div class="input">
                            <input type="text" value="<?php echo isset($settings['title-name']) ? ($settings['title-name']) : self::$titles['title-name'];?>" name="title-name" id="id-title-name"> 
                        </div>
                    </div>     
                    <div class="clear">
                        <div class="label">
                            <label for="id-title-email">Form field email</label>
                        </div>
                        <div class="input">
                            <input type="text" value="<?php echo isset($settings['title-email']) ? ($settings['title-email']) : self::$titles['title-email'];?>" name="title-email" id="id-title-email"> 
                        </div>
                    </div>
                    <div class="clear">
                        <div class="label">
                            <label for="id-title-message">Form field message</label>
                        </div>
                        <div class="input">
                            <input type="text" value="<?php echo isset($settings['title-message']) ? ($settings['title-message']) : self::$titles['title-message'];?>" name="title-message" id="id-title-message"> 
                        </div>
                    </div>
                    <div class="clear">
                        <div class="label">
                            <label for="id-title-phone">Text about call</label>
                        </div>
                        <div class="input">
                            <input type="text" value="<?php echo isset($settings['title-phone']) ? ($settings['title-phone']) : self::$titles['title-phone'];?>" name="title-phone" id="id-title-phone"> 
                        </div>
                    </div>
                    <div class="clear">
                        <div class="label">
                            <label for="id-title-message">Button label</label>
                        </div>
                        <div class="input">
                            <input type="text" value="<?php echo isset($settings['title-button']) ? ($settings['title-button']) : self::$titles['title-button'];?>" name="title-button" id="id-title-button"> 
                        </div>
                    </div>
                    <div class="clear">
                        <div class="label">
                            <label for="id-text-description">Text on the right side</label>
                            <br />
                            <div style="font-size: 12px; float:left; margin-top:3px;"><i>Will be displayed near order form, for example to describe the order for user. Using of HTML tags is possible.</i></div>
                        </div>
                        <div class="input">
                            <textarea cols="35" rows="3" style="resize:none" name="text-description" id="id-text-description"><?php echo isset($settings['text-description']) ? base64_decode($settings['text-description']) : "";?></textarea>
                        </div>                                        
                    </div>
                    <div class="clear"></div>
                    <div>
                        <div class="label">
                        </div>
                        <div class="input">
                            <input type="submit" class="buttom-admin" value="Save"  />
                        </div>
                    </div>
                    <div class="clear margin-form"></div>
                </form>

            </div>
            <div class="clear"></div>
            <div class="button-show-setting-smpt" onclick="showSetting(this, 'form-setting');">
                <span class="dashicons dashicons-arrow-down"></span>
                <span class="button-setting">
                    Show
                </span>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="block-application">
        <h2>
            Order
        </h2>
        <div class="tablenav top">
            <div class="tablenav-pages">
                <span class="pagination-links">
                    <?php if ($pages == 1 || $pages == 0) {
                            echo 'pages: '. $pages;
                        } else {?>
                        <script>
                            $(document).ready(function() { 
                                document.getElementById('current-page-selector').onkeypress = function(e) {

                                    e = e || event;

                                    if (e.ctrlKey || e.altKey || e.metaKey) return;

                                    var chr = getChar(e);
                                    // alert(chr);
                                    //if (chr == null) return;

                                    if (chr) { // chr < '0' || chr > '9'
                                        return false;
                                    }
                                    if (getChar(e, true)) {
                                        document.page_form.submit();
                                    }
                                }

                            });
                        </script>
                        <form action="" method="post" id="page_form">
                            <a class="first-page" href="<?php echo admin_url( "admin.php?page=application_form_to_admin_menu" . ( isset($_REQUEST['s']) ? '&s=' . $_REQUEST['s'] : '' ) ) ?>" title="Go to first page">«</a>
                            <a class="prev-page" href="<?php echo  admin_url( "admin.php?page=application_form_to_admin_menu&paged=" . ( ($page - 1) > 0 ? $page - 1 : 1 ) . ( isset($_REQUEST['s']) ? '&s=' . $_REQUEST['s'] : '' ) ); ?>" title="Go to the previous page">‹</a>
                            <span class="paging-input">
                                <input id="current-page-selector" class="current-page" type="text" size="1" value="<?php echo $page;?>" name="paged" title="Current Page">
                                from
                                <span class="total-pages"><?php echo $pages;?></span>
                            </span>
                            <a class="next-page" href="<?php echo admin_url( "admin.php?page=application_form_to_admin_menu&paged=" . ( ($page + 1) <= $pages  ? $page + 1 : $pages ) . ( isset($_REQUEST['s']) ? '&s=' . $_REQUEST['s'] : '' ) ); ?>" title="Go to next page">›</a>
                            <a class="last-page" href="<?php echo admin_url( "admin.php?page=application_form_to_admin_menu&paged=$pages" . ( isset($_REQUEST['s']) ? '&s=' . $_REQUEST['s'] : '' ) ); ?>" title="Go to the last page">»</a>
                        </form>
                        <?php 
                    } ?>
                </span>
            </div>
        </div>
        <div>
            <table class="list-table widefat fixed pages">
                <tr>
                    <th width="25">ID</th>
                    <th >Name</th>
                    <th>Email</th>
                    <th>Time Create</th>
                    <th width="100">IP</th>
                    <th width="300">Landing Page</th>
                </tr>
                <?php 
                    if ($vars && ($n = count($vars)) > 0 ) {
                        for($i = 0; $i< $n ; $i++) {
                        ?>
                        <tr onclick="ShowInfo('<?php echo md5( print_r($vars[$i], true) );?>');" style="cursor: pointer;">
                            <td><?php echo $vars[$i]['id'];?></td>
                            <td><?php echo $vars[$i]['name'];?></td>
                            <td><?php echo $vars[$i]['email'];?></td>
                            <td><?php echo $vars[$i]['create'];?></td>
                            <td><?php echo $vars[$i]['ip'];?></td>
                            <td><?php echo $vars[$i]['landing_page'];?></td>
                        </tr>
                        <tr>
                            <td colspan="6" id="<?php echo md5( print_r($vars[$i], true) );?>" style="display: none;">
                                <div class="form-contact-view">
                                    <table class="form-table">
                                        <tr>
                                            <th>Name</th>
                                            <td><?php echo $vars[$i]['name'];?></td>
                                            <th rowspan="1">Message(desire)</th> 
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td><?php echo $vars[$i]['email'];?></td>
                                            <td rowspan="1"><?php echo $vars[$i]['message'];?></td>
                                        </tr>
                                        <tr>
                                            <th>Date create</th>
                                            <td><?php echo $vars[$i]['create'];?></td>
                                            <th rowspan="1">Phone</th>
                                        </tr>  
                                        <tr>
                                            <th>IP</th>
                                            <td><?php echo $vars[$i]['ip'];?></td>
                                            <td rowspan="4"><?php echo $vars[$i]['phone'];?></td>
                                        </tr>  
                                        <tr>
                                            <th>Landing Page</th>
                                            <td><?php echo $vars[$i]['landing_page'];?></td>
                                        </tr>  
                                        <tr>
                                            <th>User Agent</th>
                                            <td><?php echo $vars[$i]['user_agent'];?></td>
                                        </tr>  

                                    </table>

                                </div>
                            </td>
                        </tr>
                        <?php 
                        }
                    }
                ?>
            </table>
        </div>
    </div>

</div>