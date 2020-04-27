<?php
/*
 * Template Name: Preview Resource Page Template
 *
 * Child Theme Name: Curriki Child Theme for Genesis 2.1
 * Author: Orange Blossom Media
 * Url: http://orangeblossommedia.com/
 */


// Add custom body class to the head
if (isset($_GET['screenaction']) and $_GET['screenaction'] == 'print') {
    include('page-resource-print.php');
    exit;
}
global $bp;

// Execute custom style guide page
add_filter('body_class', 'curriki_resource_page_add_body_class');
add_action('genesis_meta', 'curriki_custom_resource_page_loop');

function curriki_resource_page_add_body_class($classes) {
    $classes[] = 'backend resource-page';
    return $classes;
}

function curriki_custom_resource_page_loop() {

    //* Force full-width-content layout setting
    add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');

    if (isset($_GET['viewer']) && trim($_GET['viewer']) == 'embed') {
        remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');
        remove_action('genesis_loop', 'genesis_do_loop');

        //remove header
        remove_action('genesis_header', 'genesis_header_markup_open', 5);
        remove_action('genesis_header', 'genesis_do_header');
        remove_action('genesis_header', 'genesis_header_markup_close', 15);

        //remove navigation
        remove_action('genesis_after_header', 'genesis_do_nav');
        remove_action('genesis_after_header', 'genesis_do_subnav');

        //Remove footer
        remove_action('genesis_footer', 'genesis_footer_markup_open', 5);
        remove_action('genesis_footer', 'genesis_do_footer');
        remove_action('genesis_footer', 'genesis_footer_markup_close', 15);

        //* Remove the entry footer markup (requires HTML5 theme support)
        remove_action('genesis_before_footer', 'genesis_footer_widget_areas');
        remove_action('genesis_entry_footer', 'genesis_entry_footer_markup_open', 5);
        remove_action('genesis_entry_footer', 'genesis_entry_footer_markup_close', 15);
    } else {

        remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');
        remove_action('genesis_loop', 'genesis_do_loop');

        remove_action('wp_head', 'genesis_canonical');
        remove_action('wp_head', 'rel_canonical');
    }

    add_action('genesis_meta', 'curriki_AddShareMeta');
    add_action('genesis_before', 'curriki_resource_page_scripts');
    add_action('genesis_after_header', 'curriki_resource_header', 10);
    add_action('genesis_after_header', 'curriki_resource_page_body', 15);
}

function get_client_ip_address() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';

    return $ipaddress;
}

function curriki_AddShareMeta() {
    if (isset($_GET['rid']) || isset($_GET['pageurl'])) {
        $res = new CurrikiResources();
        $resourceUser = $res->getResourceUserById((int) $_GET['rid'], rtrim($_GET['pageurl'], '/'));

        if (trim($resourceUser['uniqueavatarfile']) != '')
            $imageUrl = 'https://' . DBI_AWS_S3_CDN_URL . '//avatars/' . $resourceUser['uniqueavatarfile'];
        else
            $imageUrl = get_stylesheet_directory_uri() . '/images/user-icon-sample.png';

        /*
          if ($resourceUser['type'] == 'collection' && isset($resourceUser['collection']))
          $resource_content = $resourceUser['description'];
          else
          $resource_content = $resourceUser['content'];

         */
        $resource_content = stripslashes(trim(strip_tags($resourceUser['description'])));

        echo '<meta property="og:title" content="' . stripslashes($resourceUser['title']) . '"/>
        <meta property="og:url" content="' . get_bloginfo('url') . '/oer/' . $resourceUser['pageurl'] . '" />
        <meta property="og:image" content="' . $imageUrl . '"/>
        <meta property="og:site_name" content="Curriki"/>
        <meta property="og:description" content="' . strip_tags($resource_content) . '" />';
    }
}

function curriki_resource_page_scripts() {

    // Enqueue JQuery Tab and Accordion scripts
    wp_enqueue_script('jquery-ui-tabs');
    wp_enqueue_style('resource-font-awesome-css', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
    wp_enqueue_style('resource-legacy-css', get_stylesheet_directory_uri() . '/css/legacy.css');

    wp_enqueue_style('jquery-fancybox-css', get_stylesheet_directory_uri() . '/js/fancybox_v2.1.5/jquery.fancybox.css?v=2.1.5');
    wp_enqueue_script('jquery-fancybox-js', get_stylesheet_directory_uri() . '/js/fancybox_v2.1.5/jquery.fancybox.pack.js?v=2.1.5', 'jquery.fancybox', '2.1.5');
    wp_enqueue_script('jquery-mousewheel-js', get_stylesheet_directory_uri() . '/js/fancybox_v2.1.5/jquery.mousewheel-3.0.6.pack.js', 'jquery.mousewheel', '3.0.6');

    //wp_enqueue_style( 'jquery-mobile-css', 'https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.3/jquery.mobile.min.css', 'jquery', '1.4.3' );
    //wp_enqueue_script( 'jquery-mobile', 'https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.3/jquery.mobile.min.js', 'jquery', '1.4.3' );

    wp_enqueue_style('qtip', get_stylesheet_directory_uri() . '/js/qtip2_v2.2.1/jquery.qtip.min.css', null, false, false);
    wp_enqueue_script('qtip', get_stylesheet_directory_uri() . '/js/qtip2_v2.2.1/jquery.qtip.min.js', array('jquery'), false, true);
    
    wp_enqueue_script('page-preview-resource-js', get_stylesheet_directory_uri() . '/js/page-preview-resource.js', array('jquery'), false, true);
    ?>
    <style type="text/css">      
    <?php
    if (isset($_GET['viewer']) && trim($_GET['viewer']) == 'embed') {
        echo '.page-header {background: none;padding: 10px 0 20px 0;}';
        echo 'body.backend {background: #BCBEC0; }';
        echo '.site-container {border: 10px solid #031770; padding: 0; max-width: 1200px; padding: 10px;}';
        echo '.resource-content-content:nth(1) { border: 5px solid #99C736; }';
        echo '.resource-header-info{width: 100%;}';
        echo 'a#powered-by-curriki {background: url(https://www.curriki.org/wp-content/uploads/2016/04/CurrikiPoweredBy_120x52.png) top left no-repeat; display: inline; float: right;height: 52px;text-indent: -9999px;width: 120px;}';
        echo '.resource-content>.resource-content-content{border: 5px solid #99c736;}';
    }
    ?>
        .directory .item-list-tabs ul li, .internal-page #content ul li{display: block;}
        .bp-template-notice{width: 95% !important;}
        .qtipCustomClass{border-color: #0E9236 !important;background-color: #99c736 !important;}
        .qtipCustomClass .qtip-content{font-size: 12px !important;color: #FFF !important;}
        .tooltip:hover{cursor: help !important;}
        .href-cls{color: #53830c !important;        text-decoration: none !important;font-size: 14px !important;}
        .display_none {
            display:none !important;
        }
    </style>
    <script>
        window.___gcfg = {
            parsetags: 'explicit'
        };
    </script>
    <script src="https://apis.google.com/js/platform.js"></script>
    <script>

        var fancyContentWindow = null;
        var fancyWindow = null;
        var un_matched_elements = [];

        (function (jQuery) {
            "use strict";
            jQuery(function () {
                jQuery("#resource-tabs").tabs();
                //jQuery( "#alignments" ).listview();
                
//                jQuery("div.review-members *").attr("disabled", "disabled").off('click');
            });


            jQuery(document).ready(function () {

                jQuery.fn.centergc = function () {
                    var h = jQuery(this).height();
                    var w = jQuery(this).width();
                    var wh = jQuery(window).height();
                    var ww = jQuery(window).width();
                    var wst = jQuery(window).scrollTop();
                    var wsl = jQuery(window).scrollLeft();
                    this.css("position", "absolute");
                    var $top = Math.round((wh - h) / 2 + wst);
                    var $left = Math.round((ww - w) / 2 + wsl);
                    this.css("top", $top + "px");
                    this.css("left", ($left - 30) + "px");
                    return this;
                }
                

//                jQuery('#google-classroom-btn').qtip({
//                    content: {
//                        text: '<div style="text-align:center;">Share with Google Classroom</div> <div style="margin-top: 5px;margin-left: 50px;"> <div id="gc-widget-div-hld">  </div> </div>'
//                    },
//                    hide: 'unfocus',
//                    events: {
//                        render: function (event, api) {
//                            // Grab the tip element
//                            var elem = api.elements.tip;
//                            /*var rs_url = "<?php //echo urlencode(get_bloginfo('url') . '/oer/' . $resourceUser['pageurl'] . '">' . get_bloginfo('url') . '/oer/' . $resourceUser['pageurl']);           ?>";                        
//                             var params = {"data-url":encodeURI(rs_url) , "data-title": jQuery(".resource-title").text()};
//                             console.log("params = " , params);
//                             gapi.sharetoclassroom.render("gc-widget-div" , params);*/
//                            jQuery("#gc-widget-div-hld").html(jQuery("#gc-widget-div").clone());
//                        }
//                    }
//                });

                /*
                jQuery("#google-classroom-btn").click(function () {

                    jq("#google-class-room-dialog").show();
                    jq("#google-class-room-dialog").centergc();
                });

                jQuery("#google-classroom-btn").mouseenter(function () {
                    var gc_img = "<?php echo site_url() . "/wp-content/themes/genesis-curriki/images/GoogleClassroomIcon_goldhover.png"; ?>"
                    jQuery(this).find("img").attr("src", gc_img);
                });
                jQuery("#google-classroom-btn").mouseleave(function () {
                    var gc_img = "<?php echo site_url() . "/wp-content/themes/genesis-curriki/images/GoogleClassroomIcon_gray.png"; ?>"
                    jQuery(this).find("img").attr("src", gc_img);
                });
                */
            });





            jQuery(".close-add-to-lib-alert").on("click", function () {
                jQuery("#add-to-lib-alert").hide();
                jQuery("#resourceReviewed").hide();
            });

            jQuery.fn.center_align = function () {
                
                var h = jQuery(this).height();
                var w = jQuery(this).width();
                var wh = jQuery(window).height();
                var ww = jQuery(window).width();
                var wst = jQuery(window).scrollTop();
                var wsl = jQuery(window).scrollLeft();
                this.css("position", "absolute");
                var $top = Math.round((wh - h) / 2 + wst);
                var $left = Math.round((ww - w) / 2 + wsl);

                this.css("top", $top + "px");
                this.css("left", ($left - 30) + "px");
                return this;
            }


            jQuery(".close-add-to-lib-alert").on("click", function () {
                jQuery("#add-to-lib-alert").hide();
                jQuery("#resourceReviewed").hide();
            });

        }(jQuery));

        function resourceRating(star) {
            jQuery("#resource-rating-" + star).siblings().addClass('fa-star-o').removeClass('fa-star');

            for (i = 1; i <= star; i++)
            {
                jQuery("#resource-rating-" + i).addClass('fa-star');
                jQuery("#resource-rating-" + i).removeClass('fa-star-o');
            }
            jQuery("#resource-rating").val(star);
        }

        function resourceRating2(star) {
            jQuery("#resource-rating2-" + star).siblings().addClass('fa-star-o').removeClass('fa-star');

            for (i = 1; i <= star; i++)
            {
                jQuery("#resource-rating2-" + i).addClass('fa-star');
                jQuery("#resource-rating2-" + i).removeClass('fa-star-o');
            }
            jQuery("#resource-rating2").val(star);
        }

        function resourceFileDownload(id, url) {
            jQuery(document).ready(function () {
                jQuery.ajax({
                    method: "POST",
                    url: "<?php echo get_bloginfo('url'); ?>/oer/?resource_file_download=file",
                    data: {id: id}
                })
                        .done(function (msg) {
                            window.location.assign(url);
                        });
            });
        }

        function resourceInappropriate(id) {
            jQuery(document).ready(function () {
                jQuery.ajax({
                    method: "POST",
                    url: "<?php echo get_bloginfo('url'); ?>/oer/?inappropriate=true",
                    data: {id: id}
                })
                        .done(function (msg) {
                            //jQuery("#resourceInappropriate").hide();             
                            //alert("Resource Flagged as inappropriate!");                    
                            jQuery("#add-to-lib-alert #msg_title").html("<?php echo __('Resource Flagged as inappropriate!','curriki'); ?>");
                            jQuery("#add-to-lib-alert #msg_para").html("");
                            jQuery("#add-to-lib-alert").show();
                            jQuery("#add-to-lib-alert").center_align();
                            jQuery("#add-to-lib-alert").css("z-index", "5");
                            jQuery("#add-to-lib-alert").addClass("inappropriate-cls");

                        });
            });
        }


        function resourceReviewed(id) {
            jQuery(document).ready(function () {
                jQuery.ajax({
                    method: "POST",
                    url: "<?php echo get_bloginfo('url'); ?>/oer/?reviewed=true",
                    data: {id: id}
                })
                        .done(function (msg) {
                            //jQuery("#resourceReviewed").hide(); 
                            //alert("Resource Nominated For Review!");
                            jQuery("#add-to-lib-alert #msg_title").html("<?php echo __('Resource Nominated For Review!','curriki'); ?>");
                            jQuery("#add-to-lib-alert #msg_para").html("");
                            jQuery("#add-to-lib-alert").show();
                            jQuery("#add-to-lib-alert").center_align();
                            jQuery("#add-to-lib-alert").css("z-index", "5");
                            jQuery("#add-to-lib-alert").addClass("resourcereviewed-cls");


                            jQuery(".close-add-to-lib-alert").on("click", function () {
                                jQuery("#add-to-lib-alert").hide();
                                jQuery("#resourceReviewed").hide();
                            });
                        });
            });
        }


        function addToMyLibrary(id) {
            jQuery(document).ready(function () {
                jQuery.ajax({
                    method: "POST",
                    url: "<?php echo get_bloginfo('url'); ?>/oer/?add_to=my_library&id=" + id,
                    data: {id: id}
                })
                        .done(function (msg) { /*jQuery("#addtolibrary").hide();*/
                        });
            });
        }



        function addfolder() {
            var url_rd = "<?php echo get_bloginfo('url'); ?>/create-resource/?type=collection&prid=" + jQuery("input[name='resourceid']").val();
            window.location = url_rd;
        }

        function addresource() {

            var url_rd = "<?php echo get_bloginfo('url'); ?>/create-resource/?prid=" + jQuery("input[name='resourceid']").val();
            window.location = url_rd;
        }

        jQuery(document).ready(function () {




            var tb = "<?php (isset($_GET['tb']) && $_GET['tb'] == "standard") ? "standard" : "" ?>";

            jQuery(".fancybox").fancybox({
                afterShow: function () {
                    jQuery(".fancybox-close").unbind();
                },
                afterLoad: function () {
                    var wframe = jQuery('.fancybox-iframe');
                    var fancyContentWindowVar = wframe[0].contentWindow;
                    var fancyWindowVar = wframe[0];
                    window.fancyContentWindow = fancyContentWindowVar;
                    window.fancyWindow = fancyWindowVar;
                    /*var states_gobal_arr_initial = jQuery(wframe).find("#states_gobal_arr_initial").get();
                     console.log("states_gobal_arr_initial = " , states_gobal_arr_initial.length);*/

                    jQuery(window.fancyContentWindow.document).find(".no-close-confirm-alert").click(function () {
                        jQuery("#close-confirm-alert").hide();
                        jQuery.fancybox.close();
                    });

                },
                afterClose: function () {
    <?php
    $protocol = 'http://';
    if (is_ssl()) {
        $protocol = 'https://';
    }
    ?>
                    var crnt_url = "<?php echo $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>";
                    //alert( "Going to = " + crnt_url );                        
                    var ajaxurl = "<?php echo admin_url('admin-ajax.php', 'relative'); ?>";
                    jQuery.ajax({
                        url: ajaxurl,
                        method: "POST",
                        data: {action: 'load_statements', rid: '<?php echo (int) $_GET['rid'] ?>', pageurl: '<?php echo rtrim($_GET['pageurl'], '/'); ?>'}
                    }).done(function (data) {
                        var dt = JSON.parse(data)

                        if (dt.standards != undefined && dt.standards.length > 0)
                        {
                            jQuery(".standards-wrapper").html("");
                            jQuery(dt.standards).each(function (i, obj) {
                                var html_var = '<div class="alignment-standard-section information-section">';
                                html_var = html_var + '<h4 class="resource-subheadline">' + obj.notation + ': ' + obj.title + '</h4>';
                                html_var = html_var + '<div class="alignment-standard-section">' + obj.description + '</div>';
                                html_var = html_var + '</div>';
                                jQuery(".standards-wrapper").append(html_var);
                            });

                        }
                    });
                }
            });

            jQuery(document).on("click", ".fancybox-close", function () {
                console.log("fancy-states_gobal_arr = ", window.fancyContentWindow.states_gobal_arr);
                console.log("states_gobal_arr_initial = ", jQuery(window.fancyContentWindow.document).find("#states_gobal_arr_initial").val());

                var states_gobal_arr_initial = JSON.parse(jQuery(window.fancyContentWindow.document).find("#states_gobal_arr_initial").val());
                /*console.log(" states_gobal_arr_initial ===> " , array1);*/

                var array1 = window.fancyContentWindow.states_gobal_arr;
                var array2 = states_gobal_arr_initial;
                /*                    
                 console.log(" array1 " , array1);
                 console.log(" array2 " , array2);
                 */
                //var window.un_matched_elements = [];                   

                //window.un_matched_elements = window.un_matched_elements.concat( window.fancyContentWindow.states_removed_existing_arr ); 


                /*
                 jQuery(window.fancyContentWindow.states_gobal_arr).each(function(i,obj){
                     
                 console.log(i , obj );
                 console.log(jQuery.inArray(obj,states_gobal_arr_initial) );
                 if(jQuery.inArray("----> "obj,states_gobal_arr_initial) == -1)
                 {
                 window.un_matched_elements.push(obj);
                 }
                 });
                 */

                /*window.fancyContentWindow.window.un_matched_elements_inner_arr = window.un_matched_elements;*/

                /*
                 if(window.un_matched_elements.length > 0)
                 {
                     
                     
                 jQuery(window.fancyContentWindow.document).find("#close-confirm-alert").show();
                 }else{
                 jQuery.fancybox.close();
                 }
                 */
                var do_stop = false;
                jQuery(window.fancyContentWindow.states_gobal_arr).each(function (i, obj) {

                    if (jQuery.inArray(obj, states_gobal_arr_initial) == -1)
                    {
                        do_stop = true;
                    }

                });

                console.log(" do_stop ", do_stop);
                console.log(" states_removed_existing_arr ---> ", window.fancyContentWindow.states_removed_existing_arr);
                if (do_stop == true || window.fancyContentWindow.states_removed_existing_arr.length > 0)
                {
                    jQuery(window.fancyContentWindow.document).find("#close-confirm-alert").show();
                } else {
                    jQuery.fancybox.close();
                }

                console.log("window.un_matched_elements = " + window.un_matched_elements.length, window.un_matched_elements);
                console.log("removed_elements = ", window.fancyContentWindow.states_removed_existing_arr);

            });

            jQuery(".close-add-to-lib-alert").on("click", function () {

                //console.log( "inappropriate-cls = " , jQuery("#add-to-lib-alert").hasClass("inappropriate-cls") );

                jQuery("#add-to-lib-alert").hide();

                if (jQuery("#add-to-lib-alert").hasClass("resourcereviewed-cls"))
                {
                    jQuery("#resourceReviewed").hide();
                }

            });

        });
    </script>
    <?php
}

function curriki_resource_header() {

    $current_user = wp_get_current_user();

    if (function_exists('check_resource_rating'))
        check_resource_rating();

    if (isset($_GET['rid']) || isset($_GET['pageurl'])) {
        $res = new CurrikiResources();
//        die();
        $resourceUser = $res->getPreviewResourceUserById((int) $_GET['rid'], rtrim($_GET['pageurl'], '/'));
        $res->setResourceViews((int) $resourceUser['resourceid']);
        //print_r($resourceUser);
    }

    // ======== [start] Manage Add to resource/collection buttons ==========
    if (get_current_user_id() > 0) {
        global $wpdb;
        $c_id = $resourceUser["resourceid"];

        $user_id = get_current_user_id();
        $sql_btn = "
            select c.title as Collection
                from preview_resources c                                        
                    where c.type = 'collection'
                    and c.contributorid = $user_id
                    and c.active = 'T'
                    and c.resourceid in ($c_id)
                union
                select c.title as Collection
                from cur_bp_groups cbg
                    inner join cur_bp_groups_members cbgm on cbgm.group_id = cbg.id
                    inner join group_resources gr on gr.groupid = cbg.id
                    inner join preview_resources c on gr.resourceid = c.resourceid                    
                where c.type = 'collection'
                    and c.active = 'T'
                    and cbgm.user_id = $user_id   
                    and c.resourceid in ($c_id)
              ";


        if ($resourceUser['type'] == "collection" && count($wpdb->get_results($sql_btn)) > 0) {/* to display add resource/collection buttons */
        }
    }
    // ======== [end] Manage Add to resource/collection buttons ==========


    $location = '';
    if (trim($resourceUser['city']) != '')
        $location .= $resourceUser['city'] . ', ';
    if (trim($resourceUser['state']) != '')
        $location .= $resourceUser['state'] . ', ';
    if (trim($resourceUser['country']) != '')
        $location .= $resourceUser['country'] . ', ';

    if (!isset($_GET['viewer'])) {
        if ((int) $resourceUser['memberrating'] == 0)
            $stars = '<span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span>';
        elseif ((int) $resourceUser['memberrating'] == 1)
            $stars = '<span class="fa fa-star"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span>';
        elseif ((int) $resourceUser['memberrating'] == 2)
            $stars = '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span>';
        elseif ((int) $resourceUser['memberrating'] == 3)
            $stars = '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span>';
        elseif ((int) $resourceUser['memberrating'] == 4)
            $stars = '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>';
        elseif ((int) $resourceUser['memberrating'] == 5)
            $stars = '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span>';
    }

    $resource_header = '<div class="resource-header page-header">';
    $resource_header .= '<div class="wrap container_12">';
    if (!isset($_GET['viewer']) && is_user_logged_in()) {
        $resource_header .= '<div class="resource-join page-join grid_2">';
        //$resource_header .= '<img class="aligncenter" src="http://placehold.it/176x146" alt="resource-name" />';
        $resource_header .= '<button id="addtolibrary" class="green-button" onclick="addToMyLibrary(' . $resourceUser['resourceid'] . ');"><span class="fa fa-plus-circle"></span> '.__('Add to My Library','curriki').'</button>';

        if ($resourceUser['type'] == "collection" && count($wpdb->get_results($sql_btn)) > 0) {
            // $resource_header .= '<button id="addfolder" class="green-button" onclick="addfolder(' . $resourceUser['resourceid'] . ');"><span class="fa fa-plus-circle"></span> '.__('Add Folder','curriki').'</button>';
            // $resource_header .= '<button id="addresource" class="green-button" onclick="addresource(' . $resourceUser['resourceid'] . ');"><span class="fa fa-plus-circle"></span> '.__('Add Resource','curriki').'</button>'; 
        }

        $resource_header .= '</div>';
    }

    $resource_header .= '<div class="resource-info page-info ' . ((!isset($_GET['viewer'])) ? 'grid_10' : 'grid_12') . '">';
    $resource_header .= '<h1 class="resource-title page-title resource-title-heading">' . stripslashes($resourceUser['title']) . '</h1>';

    if (!isset($_GET['viewer'])) {
        $resource_header .= '<div class="resource-link page-link"><strong>'.__('Website Address','curriki').':</strong> <a class="resource-url-link" href="' . get_bloginfo('url') . '/oer/' . $resourceUser['pageurl'] . '">' . get_bloginfo('url') . '/oer/' . $resourceUser['pageurl'] . '</a></div>';
        $resource_header .= '<div class="resource-tools clearfix">';
        $resource_header .= '<div class="resource-share page-share share-icons" style="width:310px;">';
        $resource_header .= '<a target="_blank" href="#" onclick="return false;" class="share-print"><span class="fa fa-print"></span></a>';
    } else {
        $resource_header .= '<div class="resource-tools clearfix">';
    }

    if (!isset($_GET['viewer'])) {
        if (trim($resourceUser['uniquename']) != '')
            $resource_header .= '<a href="#" class="share-download" onclick="resourceFileDownload(' . $resourceUser['fileid'] . ', \'https://' . DBI_AWS_S3_CDN_URL . '//resourcefiles/' . $resourceUser['uniquename'] . '\')"><span class="fa fa-download"></span></a>';
        $resource_header .= '<input type="hidden" name="fileid" id="fileid" value="' . $resourceUser['fileid'] . '" />';

        $resource_url = urlencode(get_bloginfo('url') . '/oer/?rid=');
        $facebook = 'https://www.facebook.com/sharer/sharer.php?u=' . get_bloginfo('url') . '/oer/' . $resourceUser['pageurl'] . '%23.VVSq-YDZN0Y.facebook';
        $twitter = 'https://twitter.com/intent/tweet?text=Check out this great resource I found on Curriki! ' . stripslashes($resourceUser['title']) . '-OER via @Curriki' . '&url=' . get_bloginfo('url') . '/oer/' . $resourceUser['pageurl'] . '%23.VVSralG2Opo.twitter&related=';

        $resource_header .= '<span class="separator">|</span>';
        $resource_header .= '<a href="' . $facebook . '" class="share-facebook" onclick="return false;" target="_blank"><span class="fa fa-facebook"></span></a>';
        $resource_header .= '<a href="' . $twitter . '" class="share-twitter" onclick="return false;" target="_blank"><span class="fa fa-twitter"></span></a>';
        //$resource_header .= '<a href="#" class="share-pinterest"><span class="fa fa-pinterest"></span></a>';
        $resource_header .= '<a onclick="return addthis_sendto(\'pinterest_share\');" 
           onblur="if(_ate.maf.key==9){_ate.maf.key=null;}else{_ate.maf.key=null;addthis_close();}" 
           onkeydown="if(!e){var e = window.event||event;}if(e.keyCode){_ate.maf.key=e.keyCode;}else{if(e.which){_ate.maf.key=e.which;}}" 
               onkeypress="if(!e){var e = window.event||event;}if(e.keyCode){_ate.maf.key=e.keyCode;}else{if(e.which){_ate.maf.key=e.which;}}" 
                   href="' . $resource_url . $resourceUser['resourceid'] . '" id="atic_pinterest_share" class="share-pinterest"><span class="fa fa-pinterest"></span></a>';

        $resource_header .= '<a href="#" onclick="return false;" class="share-email"><span class="fa fa-envelope-o"></span></a>';
        $google_classroom_img = site_url() . "/wp-content/themes/genesis-curriki/images/GoogleClassroomIcon_gray.png";
        $resource_header .= '<a href="#" id="google-classroom-btn" onclick="return false;" class="google-classroom-btn-cls"> <img src="' . $google_classroom_img . '" alt="" /> </a>';

        if (isset($current_user->caps['administrator'])) {
            $resource_header .= '<span class="separator">|</span>';
            // $resource_header .= '<a href="' . get_bloginfo('url') . "/create-resource/?resourceid=" . $resourceUser['resourceid'] . '" class="edit-resource" target="_blank"><span class="fa fa-pencil"></span></a>';
            $resource_header .= '<a href="javascript:void(0);" onclick="open_review_dialog()" class="edit-resource" title="Open Review Pop Up"><span class="fa fa-eye"></span></a>';
        }
        $resource_header .= '</div>';
    }

    $resource_header .= '<div class="resource-header-info page-header-info">';
    $resource_header .= '<div class="resource-header-author page-header-author">';
    if (trim($resourceUser['uniqueavatarfile']) != '')
        $resource_header .= '<img class="border-grey circle" src="https://' . DBI_AWS_S3_CDN_URL . '//avatars/' . $resourceUser['uniqueavatarfile'] . '" alt="member-name" />';
    else
        $resource_header .= '<img class="border-grey circle" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';

    $user_info = get_userdata($resourceUser['userid']);
    $user_page_url = '';
    if (is_object($user_info))
        $user_page_url .= site_url() . "/members/" . $user_info->user_nicename;

    $resource_header .= '<div class="author">';
    $resource_header .= '<span class="member-name name">' . '<a href="javascript:void(0);">' . $resourceUser['display_name'] . '</a>' . '</span><span class="occupation">' . $resourceUser['organization'] . '</span><span class="location">' . $location . '</span>';
    if (isset($_GET['viewer'])  && trim($_GET['viewer']) == 'embed')
        $resource_header .= '<img src="' . get_bloginfo('url') . '/wp-content/themes/genesis-curriki/images/licenses/' . str_replace(" ", "-", $resourceUser['license']) . '.png' . '" style="float: left;margin-top: 5px;width: 88px;height: 31px;" />';
    $resource_header .= '</div>';
    $resource_header .= '</div>'; // Closing .resource-header-author page-header-author

    if (!isset($_GET['viewer'])) {
        $cnt_date = isset($resourceUser['contributiondate']) ? date("F j, Y", strtotime($resourceUser['contributiondate'])) : "";
        $resource_header .= '<div class="resource-header-date page-date vertical-align">' . $cnt_date . '</div>';
        $resource_header .= '<div class="resource-header-rating rating vertical-align"><span class="member-rating-title">'.__('Member Rating','curriki').'</span>';
        $resource_header .= $stars;

        if (is_user_logged_in())
            $resource_header .= '<a href="#" class="link_cls" onclick="return false;">'.__('Rate this resource','curriki').'</a>';
    }

    if (isset($_GET['viewer']) && trim($_GET['viewer']) == 'embed') {
        $resource_header .= '<a id="powered-by-curriki" href="https://www.curriki.org/" target="_self">Powered By Curriki</a>';
    }
    $resource_header .= '</div>'; // Closing .esource-header-info page-header-info

    if (!isset($_GET['viewer'])) {
        $do_nominate = false;
        $resourceUser['reviewrating'] = isset($resourceUser['reviewrating']) ? round((float) $resourceUser['reviewrating'], 1) : null;

        $qtip_text = "";
        if (isset($resourceUser['reviewstatus']) && $resourceUser['reviewstatus'] == 'reviewed' && $resourceUser['reviewrating'] != null && $resourceUser['reviewrating'] >= 0) {

            /*
            if (is_resource_rating_new_scale($resourceUser)) {
                $qtip_text = '<div class="hidden-qtip">'.__('On a scale of 0 to 5','curriki').'</div>';
            } else {
                $qtip_text = '<div class="hidden-qtip">'.__('On a scale of 0 to 3','curriki').'</div>';
            }*/
            $qtip_text = '<div class="hidden-qtip">'.__('On a scale of 0 to 3','curriki').'</div>';
            
            $resource_header .= '<div class="resource-header-curriki-rating curriki-rating vertical-align"><span class="curriki-rating-title"> <span class="curriki-rating-title-text tooltip-rating">'.__('Curriki Rating','curriki').'</span>' . $qtip_text . ' </span><span class="rating-badge">' . $resourceUser['reviewrating'] . '</span>' . $qtip_text . '</div>';
        } elseif (isset($resourceUser['reviewstatus']) && $resourceUser['reviewstatus'] == 'reviewed' && $resourceUser['reviewrating'] != null && $resourceUser['reviewrating'] < 0) {
            $qtip_text = '<div class="hidden-qtip">'.__('Commented','curriki').'</div>';
            $resource_header .= '<div class="resource-header-curriki-rating curriki-rating vertical-align"><span class="curriki-rating-title"> <span class="curriki-rating-title-text tooltip-rating">'.__('Curriki Rating','curriki').'</span>' . $qtip_text . ' </span><span class="rating-badge">-</span>' . $qtip_text . '</div>';
        } elseif (isset($resourceUser['partner']) && $resourceUser['partner'] == 'T') {
            $qtip_text = '<div class="hidden-qtip"><strong>\'P\'</strong> - '.__('This is a trusted Partner resource','curriki').'</div>';
            $resource_header .= '<div class="resource-header-curriki-rating curriki-rating vertical-align"><span class="curriki-rating-title"> <span class="curriki-rating-title-text tooltip-rating">'.__('Curriki Rating','curriki').'</span>' . $qtip_text . ' </span><span class="rating-badge">P</span>' . $qtip_text . '</div>';
        } else {
            $qtip_text = '<div class="hidden-qtip"><strong>\'NR\'</strong> - '.__('This resource has not been rated','curriki').'</div>';
            $resource_header .= '<div class="resource-header-curriki-rating curriki-rating vertical-align"><span class="curriki-rating-title"> <span class="curriki-rating-title-text tooltip-rating">'.__('Curriki Rating','curriki').'</span>' . $qtip_text . ' </span><span class="rating-badge">NR</span>' . $qtip_text . '</div>';
            $do_nominate = true;
        }
    }

    if (!isset($_GET['viewer']))
        $resource_header .= '</div>'; //Closing .resource-share page-share share-icons
    $resource_header .= '</div>'; //Closing .resource-tools clearfix

    if (!isset($_GET['viewer'])) {
        $resource_header .= '<div class="flag_wrapper">';
        $resource_header .= '<div class="flag" id="resourceInappropriate"><a href="#" onclick="return false;">'.__('Flag as inappropriate','curriki').'</a></div>';
        if ($do_nominate) {
            $resource_header .= '<div class="flag" id="resourceReviewed"><a href="#" onclick="return false;">'.__('Nominate for Review','curriki').'</a></div>';
        }
        $resource_header .= '</div>';
    }

    if (isset($_GET['back_url']))
        $resource_header .= '<div class="flag" id="returnToGroup"><a href="' . base64_decode($_GET['back_url']) . '">'.__('Return to Group','curriki').'</a></div>';

    $resource_header .= '</div>'; // Closing .resource-info page-info grid_10
    $resource_header .= '</div>'; // Closing .wrap container_12
    $resource_header .= '</div>'; // Closing .resource-header page-header

    if (!isset($_GET['viewer'])) {
        $resource_header .=
                '<div id="add-to-lib-alert" class="my-library-modal modal border-grey rounded-borders-full grid_6" style="display: none;">
        <h3 class="modal-title" id="msg_title">Resource Added!</h3>
        <div class="grid_8 center">
            <div style="margin: 0 auto;"> <p id="msg_para"> '.__('The resource has been added to your collection','curriki').' </p> </div>
            <div class="my-library-actions" style="margin: 0 auto;">                                    
              <button class="button-cancel close-add-to-lib-alert">'.__('Close','curriki').'</button>
            </div>
        </div>
        <div class="close close-add-to-lib-alert"><span class="fa fa-close"></span></div>
      </div>';
    }

    echo $resource_header;
}

function curriki_resource_page_body() {
    $resource = array();
    if (isset($_GET['rid']) || isset($_GET['pageurl'])) {
        $res = new CurrikiResources();
        $resource = $res->getPreviewResourceById((int) $_GET['rid'], rtrim($_GET['pageurl'], '/'), true);
        if ($resource['access'] == 'public')
            $resource['access'] = 'Public - Available to anyone';
        elseif ($resource['access'] == 'private')
            $resource['access'] = 'Private';
        elseif ($resource['access'] == 'members')
            $resource['access'] = 'Members';

        $componentRatings = $reviewerComments = '';
        $newRatings = false;

        if ((int) $resource['standardsalignment'] && (int) $resource['standardsalignment'] >= 0) {
            //$componentRatings .= '<br />' . $resource['standardsalignmentcomment'] . ': ' . $resource['standardsalignment'];
            $componentRatings .= '<br />Standards Alignment: ' . $resource['standardsalignment'];
            $newRatings = true;
        }
        if (isset($resource['standardsalignmentcomment']) && strlen($resource['standardsalignmentcomment']) > 0) {
            $reviewerComments .= '<br />Standards Alignment: ' . $resource['standardsalignmentcomment'];
            $newRatings = true;
        }

        if ((int) $resource['subjectmatter'] && (int) $resource['subjectmatter'] >= 0) {
            //$componentRatings .= '<br />' . $resource['subjectmattercomment'] . ': ' . $resource['subjectmatter'];
            $componentRatings .= '<br />Subject Matter: ' . $resource['subjectmatter'];
            $newRatings = true;
        }

        if (isset($resource['subjectmattercomment']) && strlen($resource['subjectmattercomment']) > 0) {
            $reviewerComments .= '<br />Subject Matter: ' . $resource['subjectmattercomment'];
            $newRatings = true;
        }

        if ((int) $resource['supportsteaching'] && (int) $resource['supportsteaching'] >= 0) {
            //$componentRatings .= '<br />' . $resource['supportsteachingcomment'] . ': ' . $resource['supportsteaching'];
            $componentRatings .= '<br />Support Steaching: ' . $resource['supportsteaching'];
            $newRatings = true;
        }

        if (isset($resource['supportsteachingcomment']) && strlen($resource['supportsteachingcomment']) > 0) {
            $reviewerComments .= '<br />Support Steaching: ' . $resource['supportsteachingcomment'];
            $newRatings = true;
        }

        if ((int) $resource['assessmentsquality'] && (int) $resource['assessmentsquality'] >= 0) {
            //$componentRatings .= '<br />' . $resource['assessmentsqualitycomment'] . ': ' . $resource['assessmentsquality'];
            $componentRatings .= '<br />Assessments Quality: ' . $resource['assessmentsquality'];
            $newRatings = true;
        }

        if (isset($resource['assessmentsqualitycomment']) && strlen($resource['assessmentsqualitycomment']) > 0) {
            $reviewerComments .= '<br />Assessments Quality: ' . $resource['assessmentsqualitycomment'];
            $newRatings = true;
        }

        if ((int) $resource['interactivityquality'] && (int) $resource['interactivityquality'] >= 0) {
            //$componentRatings .= '<br />' . $resource['interactivityqualitycomment'] . ': ' . $resource['interactivityquality'];
            $componentRatings .= '<br />Interactivity Quality: ' . $resource['interactivityquality'];
            $newRatings = true;
        }

        if (isset($resource['interactivityqualitycomment']) && strlen($resource['interactivityqualitycomment']) > 0) {
            $reviewerComments .= '<br />Interactivity Quality: ' . $resource['interactivityqualitycomment'];
            $newRatings = true;
        }

        if ((int) $resource['instructionalquality'] && (int) $resource['instructionalquality'] >= 0) {
            //$componentRatings .= '<br />' . $resource['instructionalqualitycomment'] . ': ' . $resource['instructionalquality'];
            $componentRatings .= '<br />Instructional Quality: ' . $resource['instructionalquality'];
            $newRatings = true;
        }

        if (isset($resource['instructionalqualitycomment']) && strlen($resource['instructionalqualitycomment']) > 0) {
            $reviewerComments .= '<br />Instructional Quality: ' . $resource['instructionalqualitycomment'];
            $newRatings = true;
        }

        if ((int) $resource['deeperlearning'] && (int) $resource['deeperlearning'] >= 0) {
            $componentRatings .= '<br />Deeper Learning: ' . $resource['deeperlearning'];
            $newRatings = true;
        }
        if (isset($resource['deeperlearningcomment']) && strlen($resource['deeperlearningcomment']) > 0) {
            $reviewerComments .= '<br />Deeper Learning: ' . $resource['deeperlearningcomment'];
            $newRatings = true;
        }

        if (!$newRatings && ((int) $resource['technicalcompleteness'] || (int) $resource['contentaccuracy'] || (int) $resource['pedagogy'])) {
            $componentRatings = 'Technical Completeness: ' . $resource['technicalcompleteness'] . '<br />Content Accuracy: ' . $resource['contentaccuracy'] . '<br />Appropriate Pedagogy: ' . $resource['pedagogy'] . '';
        }

        if (isset($resource['ratingcomment']) && strlen($resource['ratingcomment']) > 0) {
            $reviewerComments = trim(str_replace('<strong>Reviewer Comments: </strong>', '', $resource['ratingcomment']));
        }
    }

    if (empty($resource['resourceid']))
        header(sprintf('Location: %s/resources-curricula', site_url()));

    if (!isset($_GET['viewer'])) {
        $resource_tabs = '<div id="resource-tabs">';
        $resource_tabs .= '<div class="resource-tabs page-tabs"><div class="wrap container_12">';
        $resource_tabs .= '<ul>';
        $resource_tabs .= '<li><a href="#content"><span class="tab-icon fa fa-file-text-o"></span> <span class="tab-text">'.__('Content','curriki').'</span></a></li>';
        $resource_tabs .= '<li><a href="#information"><span class="tab-icon fa fa-info-circle"></span> <span class="tab-text">'.__('Information','curriki').'</span></a></li>';
        $resource_tabs .= '<li><a href="#standards"><span class="tab-icon fa fa-graduation-cap"></span> <span class="tab-text">'.__('Standards','curriki').'</span></a></li>';
        $resource_tabs .= '<li><a href="#reviews"><span class="tab-icon fa fa-star"></span> <span class="tab-text">'.__('Reviews','curriki').'</span></a></li>';
        $resource_tabs .= '</ul>';
        $resource_tabs .= '</div></div>';

        echo $resource_tabs;

        echo '<div class="resource-content dashboard-tabs-content"><div class="wrap container_12">';

        // Content
        echo $content_tab = '<div id="content" class="tab-contents"></div>';

        // Information
        $information_tab = '';
        $information_tab .= '<div id="information" class="tab-contents">';
        $information_tab .= '<div class="grid_9">';
        $information_tab .= '<div class="information-section">';
        $information_tab .= '<h4 class="resource-subheadline">'.__('Type','curriki').':</h4>';
        $typeName = '';
        if (isset($resource['typeName']))
            foreach ($resource['typeName'] as $type)
                $typeName .= $type['typeName'] . ', ';
        $information_tab .= substr($typeName, 0, -2);
        $information_tab .= '</div>';
        $information_tab .= '<div class="information-section">';
        $information_tab .= '<h4 class="resource-subheadline">'.__('Description','curriki').':</h4>';
        $information_tab .= '<p>' . stripslashes($resource['description']) . '</p>';
        $information_tab .= '</div>';
        $information_tab .= '<div class="information-section">';
        $information_tab .= '<div class="grid_6">';
        $information_tab .= '<h4 class="resource-subheadline">'.__('Subjects','curriki').':</h4>';
        $information_tab .= '<ul>';
        if (isset($resource['subjects']))
            foreach ($resource['subjects'] as $subject)
                $information_tab .= '<li>' . $subject . '</li>';
        $information_tab .= '</ul>';
        $information_tab .= '</div>';
        $information_tab .= '<div class="grid_6">';
        $information_tab .= '<h4 class="resource-subheadline">'.__('Education Levels','curriki').':</h4>';
        $information_tab .= '<ul>';
        if (isset($resource['educationlevels']))
            foreach ($resource['educationlevels'] as $educationlevel)
                $information_tab .= '<li>' . $educationlevel . '</li>';
        $information_tab .= '</ul>';
        $information_tab .= '</div>';
        $information_tab .= '</div>';
        $information_tab .= '<div class="information-section">';
        $information_tab .= '<div class="grid_6">';
        $information_tab .= '<h4 class="resource-subheadline">'.__('Keywords','curriki').':</h4>';
        $information_tab .= $resource['keywords'];
        $information_tab .= '</div>';
        $information_tab .= '<div class="grid_6">';
        $information_tab .= '<h4 class="resource-subheadline">'.__('Language','curriki').':</h4>';
        $information_tab .= $resource['languageName'];
        $information_tab .= '</div>';
        $information_tab .= '</div>';
        $information_tab .= '</div>';
        $information_tab .= '<div class="grid_2 push_1">';

        $information_tab .= '<div class="information-section">';
        $information_tab .= '<h4 class="resource-subheadline">'.__('Access Privileges','curriki').':</h4>';
        $information_tab .= $resource['access'];
        $information_tab .= '</div>';


        //$information_tab .= '<div class="information-section">';
        //$information_tab .= '<h4 class="resource-subheadline">Hidden From Search:</h4>';
        //$information_tab .= 'No';
        //$information_tab .= '</div>';
        //$information_tab .= '<div class="information-section">';
        //$information_tab .= '<h4 class="resource-subheadline">Rights Holder:</h4>';
        //$information_tab .= 'Curriculum: Lesson Plan';
        //$information_tab .= '</div>';
        $information_tab .= '<div class="information-section">';
        $information_tab .= '<h4 class="resource-subheadline">'.__('License Deed','curriki').':</h4>';
        $information_tab .= $resource['licenseName'];
        $information_tab .= '</div>';


        $information_tab .= '<div class="information-section">';
        $information_tab .= '<h4 class="resource-subheadline">'.__('Collections','curriki').':</h4>';
        if (count($resource['collections_resource_blogngs_to']) == 0) {
            $information_tab .= "None";
        }

        if (isset($resource['collections_resource_blogngs_to'])) {
            foreach ($resource['collections_resource_blogngs_to'] as $collection_of_resource) {
                $information_tab .= '<a class="href-cls" href="' . site_url() . "/oer/" . $collection_of_resource->pageurl . '" target="_blank">' . stripslashes($collection_of_resource->title) . '</a>' . "<br />";
            }
        }
        $information_tab .= '</div>';


        $information_tab .= '</div>';
        $information_tab .= '</div>';

        echo $information_tab;


        // Standards
        $standards_tab = '';
        $standards_tab .= '<div id="standards" class="tab-contents">';
        $standards_tab .= '<div class="grid_2">';
        $standards_tab .= '</div>';
        $standards_tab .= '<div class="grid_10">';
        //$standards_tab .= '<div class="alignment-standard-breadcrumbs">';
        //$standards_tab .= '<a href="#">Aligned Standards</a> > <a href="#">Core Standards</a> > <a href="#">English</a> > <a href="#">Grade 11</a>';
        //$standards_tab .= '</div>';

        if (isset($resource['standards'])) {
            $standards_tab .= '<div class="alignment-standard-section information-section">';
            //$standards_tab .= 'Update Standards? <button class="modal-button green-button">Align Now</button>';
//            $standards_tab .= __('Update Standards?','curriki').' <a href="#" onclick="return false;"><button class="modal-button green-button">'.__('Align Now','curriki').'</button></a>';
            $standards_tab .= '</div>';
        } else {
            $standards_tab .= '<div class="alignment-standard-section information-section">';
//            $standards_tab .= __('This resource has not yet been aligned.','curriki').' <a href="' . get_bloginfo('url') . '/alignment/?rid=' . $resource['resourceid'] . '" class="fancybox fancybox.iframe"><button class="modal-button white-button">'.__('Align Now','curriki').'</button></a>';
            $standards_tab .= '</div>';
        }

        $standards_tab .= '<div class="standards-wrapper">';
        if (isset($resource['standards']))
            foreach ($resource['standards'] AS $standard) {
                $standards_tab .= '<div class="alignment-standard-section information-section">';
                $standards_tab .= '<h4 class="resource-subheadline">' . $standard['notation'] . ': ' . $standard['title'] . '</h4>';
                $standards_tab .= '<div class="alignment-standard-section">';
                //$standards_tab .= '<h4 class="resource-subheadline">Core Standard:</h4>';
                $standards_tab .= $standard['description'];
                $standards_tab .= '</div>';
                $standards_tab .= '</div>';
            }
        $standards_tab .= '</div>';
        /*
          $standards_tab .= '<div class="alignment-standard-section information-section">';
          $standards_tab .= '<h4 class="resource-subheadline">LA.10.2 Informational Text: Structure, Comprehension and Analysis</h4>';
          $standards_tab .= '<div class="alignment-standard-section">';
          $standards_tab .= '<h4 class="resource-subheadline">Core Standard:</h4>';
          $standards_tab .= '<span class="standard">LA.10.2.1</span> Analyze arguments or defenses of claims, judge tone, features and arguments of each. Summarize and synthesize content from reliable sources for writing and speaking.';
          $standards_tab .= '</div>';
          $standards_tab .= '</div>';
         */

        $standards_tab .= '</div>';
        $standards_tab .= '</div>';

        echo $standards_tab;

        if ((int) $resource['memberrating'] == 0)
            $stars = '<span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span>';
        elseif ((int) $resource['memberrating'] == 1)
            $stars = '<span class="fa fa-star"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span>';
        elseif ((int) $resource['memberrating'] == 2)
            $stars = '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span>';
        elseif ((int) $resource['memberrating'] == 3)
            $stars = '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span>';
        elseif ((int) $resource['memberrating'] == 4)
            $stars = '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>';
        elseif ((int) $resource['memberrating'] == 5)
            $stars = '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span>';

        // Reviews
        $reviews_tab = '';
        $reviews_tab .= '<div id="reviews" class="tab-contents reviews-tab">';
        $reviews_tab .= '<div class="grid_6 review-curriki">';

        $resource['reviewrating'] = isset($resource['reviewrating']) ? round((float) $resource['reviewrating'], 1) : null;
        $qtip_text = "";
        if (isset($resource['reviewstatus']) && $resource['reviewstatus'] == 'reviewed' && $resource['reviewrating'] != null && $resource['reviewrating'] >= 0) {

            /*
            if (is_resource_rating_new_scale($resource)) {
                $qtip_text = '<div class="hidden-qtip">'.__('On a scale of 0 to 5','curriki').'</div>';
            } else {
                $qtip_text = '<div class="hidden-qtip">'.__('On a scale of 0 to 3','curriki').'</div>';
            }*/
            $qtip_text = '<div class="hidden-qtip">'.__('On a scale of 0 to 3','curriki').'</div>';
            
            $reviews_tab .= '<div class="review-aggregate curriki-rating"><span class="curriki-rating"> <span class="curriki-rating-title-text tooltip-rating">'.__('Curriki Rating','curriki').'</span>' . $qtip_text . ' </span><span class="rating-badge">' . $resource['reviewrating'] . '</span>' . $qtip_text . '</div>';
        } elseif (isset($resource['reviewstatus']) && $resource['reviewstatus'] == 'reviewed' && $resource['reviewrating'] != null && $resource['reviewrating'] < 0) {
            $qtip_text = '<div class="hidden-qtip">Commented</div>';
            $reviews_tab .= '<div class="review-aggregate curriki-rating"><span class="curriki-rating"> <span class="curriki-rating-title-text tooltip-rating">'.__('Curriki Rating','curriki').'</span>' . $qtip_text . ' </span><span class="rating-badge"> - </span>' . $qtip_text . '</div>';
        } elseif (isset($resource['partner']) && $resource['partner'] == 'T') {
            $qtip_text = '<div class="hidden-qtip"><strong>\'P\'</strong> - '.__('This is a trusted Partner resource','curriki').'</div>';
            $reviews_tab .= '<div class="review-aggregate curriki-rating"><span class="curriki-rating"> <span class="curriki-rating-title-text tooltip-rating">'.__('Curriki Rating','curriki').'</span>' . $qtip_text . ' </span><span class="rating-badge"> P </span>' . $qtip_text . '</div>';
        } else {
            $qtip_text = '<div class="hidden-qtip"><strong>\'NR\'</strong> - '.__('This resource has not been rated','curriki').'</div>';
            $reviews_tab .= '<div class="review-aggregate curriki-rating"><span class="curriki-rating"> <span class="curriki-rating-title-text tooltip-rating">'.__('Curriki Rating','curriki').'</span>' . $qtip_text . ' </span><span class="rating-badge"> NR </span>' . $qtip_text . '</div>';
        }

        $reviews_tab .= '<div class="review-content-box scrollbar rounded-borders-full border-grey">';

        if (isset($resource['reviewstatus']) && $resource['reviewstatus'] == 'reviewed' && $resource['reviewrating'] != null && $resource['reviewrating'] >= 0) {
            $reviews_tab .= '<p>This resource was reviewed using the Curriki Review rubric and received an overall Curriki Review System rating of ' . $resource['reviewrating'] . ', as of ' . date("Y-m-d", strtotime($resource['lastreviewdate'])) . '.</p>';
        } elseif (isset($resource['reviewstatus']) && $resource['reviewstatus'] == 'reviewed' && $resource['reviewrating'] != null && $resource['reviewrating'] < 0) {
            $reviews_tab .= '<p>This resource was reviewed using the Curriki Review rubric and received an overall Curriki Review System rating of ' . '(-)' . ' as of ' . date("Y-m-d", strtotime($resource['lastreviewdate'])) . '.</p>';
        } elseif (isset($resource['partner']) && $resource['partner'] == 'T') {
            //$reviews_tab .= '<p>This resource was reviewed using the Curriki Review rubric and received an overall Curriki Review System rating of ' . '(-)' . ' as of ' . date("Y-m-d", strtotime($resource['lastreviewdate'])) . '.</p>';
        } else {
            $reviews_tab .= '<p>'.__('This resource has not yet been reviewed.','curriki').'</p>';
        }


        if ($componentRatings) {
            $reviews_tab .= '<h4 class="resource-subheadline">'.__('Component Ratings','curriki').':</h4>';
            //$reviews_tab .= 'Technical Completeness:'.$resource['technicalcompleteness'].' Content Accuracy:'.$resource['contentaccuracy'].' Appropriate Pedagogy:'.$resource['pedagogy'].'';
            $reviews_tab .= $componentRatings;
        }
        if ($reviewerComments) {
            $reviews_tab .= '<br/><h4 class="resource-subheadline">'.__('Reviewer Comments','curriki').':</h4>';
            $reviews_tab .= $reviewerComments;
        }
        $reviews_tab .= '</div>';
        $reviews_tab .= '</div>';
        $reviews_tab .= '<div class="grid_6 review-members">';

        $member_rating_display = (is_user_logged_in()) ? (__('Member Rating','curriki').' ' . $stars) : ("");
        $reviews_tab .= '<div class="review-aggregate member-rating rating">' . $member_rating_display . '</div>';

        $reviews_tab .= '<div class="review-content-box scrollbar rounded-borders-full border-grey">';

        if (is_user_logged_in()) {

            $reviews_tab .= '<div class="review review-form">';

            if (isset($resource['currentUser']['uniqueavatarfile']))
                $reviews_tab .= '<img class="border-grey circle" src="https://' . DBI_AWS_S3_CDN_URL . '//avatars/' . $resource['currentUser']['uniqueavatarfile'] . '" alt="member-name" />';
            else
                $reviews_tab .= '<img class="border-grey circle" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
            $reviews_tab .= '<div class="review-content">';

/*
            if (isset($resource['currentUser']['display_name']))
                $display_name = $resource['currentUser']['display_name'];
            else
                $display_name = __('Member Name','curriki');

            $reviews_tab .= '
        <div class="review-rating rating">
          <span class="member-name name">' . $display_name . '</span> <span>
          <span class="fa fa-star-o" id="resource-rating-1" onclick="resourceRating(1);"></span>
          <span class="fa fa-star-o" id="resource-rating-2" onclick="resourceRating(2);"></span>
          <span class="fa fa-star-o" id="resource-rating-3" onclick="resourceRating(3);"></span>
          <span class="fa fa-star-o" id="resource-rating-4" onclick="resourceRating(4);"></span>
          <span class="fa fa-star-o" id="resource-rating-5" onclick="resourceRating(5);"></span></span>
        </div>';

            //$reviews_tab .= '<div class="review-rating rating"><span class="member-name name">Member Name</span> <span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span></div>';
            $reviews_tab .= '<form action="" method="post"><input type="hidden" name="resourceid" value="' . $resource['resourceid'] . '" /><input type="hidden" id="resource-rating" name="resource-rating" /><textarea name="resource-comments"></textarea>';
            $reviews_tab .= '<button class="green-button">'.__('Submit Review','curriki').'</button></form>';
 * 
 */
            $reviews_tab .= '</div>';
            $reviews_tab .= '</div>';
        }

        if (isset($resource['comments']))
            foreach ($resource['comments'] AS $comment) {
                if ($comment['rating'] == 0)
                    $stars = '<span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span>';
                elseif ($comment['rating'] == 1)
                    $stars = '<span class="fa fa-star"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span>';
                elseif ($comment['rating'] == 2)
                    $stars = '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span>';
                elseif ($comment['rating'] == 3)
                    $stars = '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span>';
                elseif ($comment['rating'] == 4)
                    $stars = '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>';
                elseif ($comment['rating'] == 5)
                    $stars = '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span>';

                $reviews_tab .= '<div class="review">';
                if (trim($comment['uniqueavatarfile']) != '')
                    $reviews_tab .= '<img class="border-grey circle" src="https://' . DBI_AWS_S3_CDN_URL . '//avatars/' . $comment['uniqueavatarfile'] . '" alt="member-name" />';
                else
                    $reviews_tab .= '<img class="border-grey circle" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
                $reviews_tab .= '<div class="review-content">';
                $reviews_tab .= '<div class="review-rating rating"><span class="member-name name">' . $comment['display_name'] . '</span> ' . $stars . '</div>';
                if ($comment['date'] != '0000-00-00 00:00:00')
                    $reviews_tab .= '<div class="review-date">' . date("F j, Y", strtotime($comment['date'])) . '</div>';
                $reviews_tab .= $comment['comment'];
                $reviews_tab .= '</div>';
                $reviews_tab .= '</div>';
            }

        $reviews_tab .= isset($resource['comments']) && count($resource['comments']) == 0 && !is_user_logged_in() ? "<p>".__('Not Rated Yet.','curriki')."</p>" : "";

        $reviews_tab .= '</div>';
        $reviews_tab .= '</div>';
        $reviews_tab .= '</div>';

        echo $reviews_tab;
    }

    // Resource Content (always visible below tabs)
    $resource_content = '<div class="resource-content-sidebar">';

    //**************** Start Side Bar ****************//  
    
    $content_class = '';
    if (!isset($_GET['viewer']) && (isset($resource["toc_persist"]) && count($resource["toc_persist"]) > 0 || (isset($resource['collection']) && count($resource['collection']) > 0))) {
        $toc_persist_rids = $resource["toc_persist_rids"];
        $resource_content .= '<div class="resource-sidebar page-sidebar grid_2">';
        //if ($resource['type'] == 'collection' && isset($resource['collection'])) {
        $resource_content .= '<div class="toc toc-card card rounded-borders-full border-grey no-min-width">';
        $resource_content .= '<div class="toc-header">'.__('Table of Contents','curriki').'</div>';
        $resource_content .= '<div class="toc-body">';
        //$resource_content .= '<h4 class="toc-collection-folder"><span class="fa fa-folder-open"></span> ' . stripslashes($resource['title']) . '</h4>';
        //$resource_content .= '<ul class="fa fa-ul toc-collection toc-folder">';
        $mrid = 0;
        if (isset($_GET["mrid"])) {
            $mrid = $_GET["mrid"];
        } else {
            $mrid = $resource["resourceid"];
        }
        

        foreach ($resource["toc_persist"] as $toc_persist) {
            //$table_of_content = $resource["resources_table_of_content"];
            $persist_rids = $toc_persist_rids;
            $table_of_content = $toc_persist;

            $rid = $toc_persist->main_resource_resources["resource"]->resourceid;
            //unset($persist_rids[$rid]);
            $persist_rids[] = $rid;
            $persist_rids = array_unique($persist_rids);
            $mrid = implode("-", $persist_rids);
            $content_class = ' class="resource-content"';
            if ((isset($resource['collection']) && count($resource['collection']) > 0) || $table_of_content->main_resource_resources["collections"] > 0) {

                $content_class = ' class="resource-content grid_10"';
                if ($table_of_content->main_resource_resources["collections"] > 0) {
                    $resource_content .= '<h4 class="toc-collection-folder toc-col-hd"><span class="fa fa-folder toc-col-folder-persist" id="tocf-' . $rid . '"></span> ' . stripslashes($table_of_content->main_resource_resources["resource"]->title) . '</h4>';
                    $resource_content .= '<ul class="fa fa-ul toc-collection toc-folder toc-col-ul toc-hide" id="toc-col-ul-' . $rid . '">';
                    foreach ($table_of_content->main_resource_resources["collections"] as $collection) {
                        $url_toc = get_bloginfo('url') . '/oer/' . $collection['pageurl'] . '/?mrid=' . $mrid;
                        $resource_content .= '<li class="toc-file toc-image"><span class="fa fa-li fa-file-o"></span> <a href="#" onclick="return false;" title="disabled in preview">' . stripslashes($collection['title']) . '</a></li>';
                    }
                    $resource_content .= '</ul>';
                    $resource_content .= '<div class="toc-spacer"></div>';
                }
            }
        }

        if (isset($resource['collection']) && count($resource['collection']) > 0) {

            $persist_rids = $toc_persist_rids;
            $rid = $resource['resourceid'];
            $persist_rids[] = $rid;
            $persist_rids = array_unique($persist_rids);
            $mrid = implode("-", $persist_rids);
            //$mrid = $resource['resourceid'];
            $resource_content .= '<h4 class="toc-collection-folder"><span class="fa fa-folder-open"></span> ' . stripslashes($resource['title']) . '</h4>';
            $resource_content .= '<ul class="fa fa-ul toc-collection toc-folder">';
            foreach ($resource['collection'] as $collection) {
                $url_toc = get_bloginfo('url') . '/oer/' . $collection['pageurl'] . "/?mrid=" . $mrid;
                $resource_content .= '<li class="toc-file toc-image"><span class="fa fa-li fa-file-o"></span> <a href="#" onclick="return false;" title="disabled in preview">' . stripslashes($collection['title']) . '</a></li>';
            }
            $resource_content .= '</ul>';
        }

        $resource_content .= '</div>';
        $resource_content .= '</div>';
        $resource_content .= '</div>';
    }
    //**************** End Side Bar *****************//

    $resource_content .= '<div' . $content_class . '>';
    $resource_content .= '<div class="resource-content-content rounded-borders-full border-grey">';

    $resource_desc = isset($resource['description']) ? $resource['description'] : "";
    $content = stripslashes((empty($resource['content'])) ? $resource_desc : $resource['content']);

    // ====== In content, correcting the path of scripts and styles ===============
    if (isset($_GET["pageurl"]) && strlen($_GET["pageurl"]) > 0) {
        $str_find = "../wp-content/themes";
        $str_replace = site_url() . "/wp-content/themes";
        $content = str_replace($str_find, $str_replace, $content);
    }

    $resource_content .= $content;
    $resource_content .= '</div>';

    if (isset($resource['type']) && $resource['type'] == 'collection' && isset($resource['collection'])) {
        $resource_content .= '<br /><div class="resource-content-content rounded-borders-full border-grey">';

        $rid = $resource['resourceid'];
        $persist_rids[] = $rid;
        $persist_rids = array_unique($persist_rids);
        $mrid = implode("-", $persist_rids);

        foreach ($resource['collection'] AS $collection) {
            $url = get_bloginfo('url') . '/oer/' . $collection['pageurl'];

            $content = stripslashes((!empty($collection['description'])) ? $collection['description'] : $collection['content']);

            if ((int) $collection['memberrating'] == 0)
                $m_stars = '<span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span>';
            elseif ((int) $collection['memberrating'] == 1)
                $m_stars = '<span class="fa fa-star"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span>';
            elseif ((int) $collection['memberrating'] == 2)
                $m_stars = '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span>';
            elseif ((int) $collection['memberrating'] == 3)
                $m_stars = '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span><span class="fa fa-star-o"></span>';
            elseif ((int) $collection['memberrating'] == 4)
                $m_stars = '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star-o"></span>';
            elseif ((int) $collection['memberrating'] == 5)
                $m_stars = '<span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span>';

            $resource_content .= '<div class="resource-content-content rounded-borders-full border-grey"><div class="collection-body-title"><div class="collection-title"><h3><a href="' . $url . "/?mrid=" . $mrid . '">' . stripslashes($collection['title']) . '</a></h3> by <span class="member-name name">' . $collection['contributorid_Name'] . '</span></div></div><div class="collection-body-content"><div class="collection-description">' . stripslashes($content) . '</div><div class="collection-rating rating"><span class="member-rating-title">'.__('Member Rating','curriki').'</span>' . $m_stars;
            if (get_current_user_id() > 0) {
                $resource_content .= '<a href="#" onclick="return false;">'.__('Rate this collection','curriki').'</a>';
            }
            $resource_content .= '</div><div class="collection-curriki-rating curriki-rating">';

            $collection['reviewrating'] = isset($collection['reviewrating']) ? round((float) $collection['reviewrating'], 1) : null;

            $qtip_text = "";
            if ($collection['reviewstatus'] == 'reviewed' && $collection['reviewrating'] != null && $collection['reviewrating'] >= 0) {
                //$qtip_text = '<div class="hidden-qtip">On a scale rating</div>';
                /*if (is_resource_rating_new_scale($collection)) {
                    $qtip_text = '<div class="hidden-qtip">'.__('On a scale of 0 to 5','curriki').'</div>';
                } else {
                    $qtip_text = '<div class="hidden-qtip">'.__('On a scale of 0 to 3','curriki').'</div>';
                }*/
                $qtip_text = '<div class="hidden-qtip">'.__('On a scale of 0 to 3','curriki').'</div>';
                
                $resource_content .= '<span class="curriki-rating-title"> <span class="curriki-rating-title-text tooltip-rating">'.__('Curriki Rating','curriki').'</span>' . $qtip_text . ' </span> <span class="rating-badge">' . $collection['reviewrating'] . '</span>' . $qtip_text . '';
            } elseif ($collection['reviewstatus'] == 'reviewed' && $collection['reviewrating'] != null && $collection['reviewrating'] < 0) {
                $qtip_text = '<div class="hidden-qtip">'.__('Commented','curriki').'</div>';
                $resource_content .= '<span class="curriki-rating-title">  <span class="curriki-rating-title-text tooltip-rating">'.__('Curriki Rating','curriki').'</span </span> </span><span class="rating-badge">-</span>' . $qtip_text . '';
            } elseif ($collection['partner'] == 'T') {
                $qtip_text = '<div class="hidden-qtip"><strong>\'P\'</strong> - This is a trusted Partner resource</div>';
                $resource_content .= '<span class="curriki-rating-title">  <span class="curriki-rating-title-text tooltip-rating">'.__('Curriki Rating','curriki').'</span>' . $qtip_text . ' </span> </span><span class="rating-badge">P</span>' . $qtip_text . '';
            } else {
                $qtip_text = '<div class="hidden-qtip"><strong>\'NR\'</strong> - '.__('This resource has not been rated','curriki').'</div>';
                $resource_content .= '<span class="curriki-rating-title">  <span class="curriki-rating-title-text tooltip-rating">'.__('Curriki Rating','curriki').'</span>' . $qtip_text . ' </span> </span><span class="rating-badge">NR</span>' . $qtip_text . '';
            }

            $resource_content .= '</div></div></div><br />'; // Closing Tags
        }

        $resource_content .= '</div>';
    }
    $pageurl_val = isset($resource['pageurl']) ? $resource['pageurl'] : "";
    $rs_url = get_bloginfo('url') . '/oer/' . $pageurl_val;

    $resource_content .= '
    <div style="display:none;"> 
      <div id="gc-widget-div"> 
        <div class="g-sharetoclassroom" data-url="' . $rs_url . '" data-title="' . ( isset($resource['title']) ? stripslashes($resource['title']) : "" ) . '" ></div> 
      </div> 
    </div>
    <script>gapi.sharetoclassroom.go("gc-widget-div");</script>
  ';

    $resource_content .= '</div></div>';
    if (isset($_GET['viewer']) && trim($_GET['viewer']) == 'embed')
        $resource_content .= '<p id="content-licensed" style="width:100%;text-align:center"><a href="https://www.curriki.org/copyright-open-licenses/" target="_blank">Copyright &amp; Open Licenses</a></p>';
    echo $resource_content .= '</div></div></div>';


    /*     * ********************************************************************************************
     *  educationalUse – (assignment, group work, curriculum) – name from resource_instructiontypes
     *  o   learningResourceType – name from resource_instructiontypes
     *  o   author - contributor name
     *  o   typicalAgeRange – convert educationallevel to age by adding 5.  Example:  educationallevel = 1 then age = 6.
     *  o   about – subject->subjectarea
     *  o   license & useRightsURL – www.creativecommons.org/licenses - licenses.url
     * ******************************************************************************************** */
    if (!empty($resource) and is_array($resource)) {
        echo '
      <div style="display:none;">
        <meta itemprop="educationalUse" value="' . substr($typeName, 0, -2) . '"/>
        <meta itemprop="learningResourceType" value="' . substr($typeName, 0, -2) . '"/>
        <meta itemprop="interactivityType" value="mixed"/>
        <meta itemprop="intendedEndUserRole" value="' . (isset($resource['studentfacing']) && ($resource['studentfacing'] == 'T' ? 'student' : 'teacher')) . '"/>
        <meta itemprop="author" value="' . (isset($resource['contributorid_Name']) ? $resource['contributorid_Name'] : "") . '"/>
        <meta itemprop="typicalAgeRange" value="1-12"/>
        <meta itemprop="name" value="' . (isset($resource['title']) ? stripslashes($resource['title']) : "") . '"/>
        <meta itemprop="description" value="' . (isset($resource['description']) ? addslashes($resource['description']) : "") . '"/>
        <meta itemprop="mediaType" value="' . (isset($resource['mediatype']) ? $resource['mediatype'] : "") . '"/>
        <meta itemprop="about" value="' . str_replace(' > ', ' ', implode(', ', (isset($resource['subjects']) && is_array($resource['subjects']) ? $resource['subjects'] : array()))) . '"/>
        <meta itemprop="dateCreated" value="' . (isset($resource['createdate']) ? $resource['createdate'] : "") . '"/>
        <meta itemprop="publisher" value="‘Curriki’"/>
        <meta itemprop="inlanguage" value="language"/>
        <meta itemprop="license" value="' . (isset($resource['licenseName']) ? $resource['licenseName'] : "") . '"/>
        <meta itemprop="useRightsURL" value="www.creativecommons.org/licenses"/>
      </div>';
    }

    if (isset($resource['currentUser']['uniqueavatarfile']) && strlen($resource['currentUser']['uniqueavatarfile']) > 0)
        $display_image = '<img class="border-grey circle" src="https://' . DBI_AWS_S3_CDN_URL . '//avatars/' . $resource['currentUser']['uniqueavatarfile'] . '" alt="member-name" />';
    else
        $display_image = '<img class="border-grey circle" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';

    $display_name = (isset($resource['currentUser']['display_name'])) ? $resource['currentUser']['display_name'] : 'Member Name';

    $reviews_popup = '<div class="review-content">
    <div class="review-content-box scrollbar rounded-borders-full border-grey review-popup-box">
            <div class="review review-form">
            ' . $display_image . '
                <div class="review-content">
                            <div class="review-rating rating">
                                <span class="member-name name">' . $display_name . '</span> 
                                <span>
                                    <span class="fa fa-star-o" id="resource-rating2-1" onclick="resourceRating2(1);"></span>
                                    <span class="fa fa-star-o" id="resource-rating2-2" onclick="resourceRating2(2);"></span>
                                    <span class="fa fa-star-o" id="resource-rating2-3" onclick="resourceRating2(3);"></span>
                                    <span class="fa fa-star-o" id="resource-rating2-4" onclick="resourceRating2(4);"></span>
                                    <span class="fa fa-star-o" id="resource-rating2-5" onclick="resourceRating2(5);"></span>
                                </span>
                              </div>
                                    <form action="" method="post">
                                      <input type="hidden" name="resourceid" value="' . (isset($resource['resourceid']) ? $resource['resourceid'] : "") . '" />
                                      <input type="hidden" id="resource-rating2" name="resource-rating" />
                                      <textarea name="resource-comments"></textarea>
                                    <button class="green-button">'.__('Submit Review','curriki').'</button>
                                    </form>
                 </div>
            </div>
    </div>
</div>    
  ';

    if (get_current_user_id() > 0) {
        echo '
        <div id="resource-member-review" class="join-oauth-modal modal border-grey rounded-borders-full grid_8" style="display: none; width: 50%;">
          <h3 class="modal-title">'.__('Member Review','curriki').'</h3>
          <div><span id="login_result" class="dialog_result"></span></div>
          ' . $reviews_popup . '
          <div class="close"><span class="fa fa-close" onclick="jQuery(\'#resource-member-review\').hide();"></span></div>
        </div>
      ';
    }
}

add_action('genesis_after', 'curriki_addthis_scripts');

genesis();

function is_resource_rating_new_scale($resource) {
    $componentRatings = $reviewerComments = '';
    $newRatings = false;

    if ((int) $resource['standardsalignment'] && (int) $resource['standardsalignment'] >= 0) {
        //$componentRatings .= '<br />' . $resource['standardsalignmentcomment'] . ': ' . $resource['standardsalignment'];
        $componentRatings .= '<br />Standards Alignment: ' . $resource['standardsalignment'];
        $newRatings = true;
    }
    if (isset($resource['standardsalignmentcomment']) && strlen($resource['standardsalignmentcomment']) > 0) {
        $reviewerComments .= '<br />Standards Alignment: ' . $resource['standardsalignmentcomment'];
        $newRatings = true;
    }

    if ((int) $resource['subjectmatter'] && (int) $resource['subjectmatter'] >= 0) {
        //$componentRatings .= '<br />' . $resource['subjectmattercomment'] . ': ' . $resource['subjectmatter'];
        $componentRatings .= '<br />Subject Matter: ' . $resource['subjectmatter'];
        $newRatings = true;
    }

    if (isset($resource['subjectmattercomment']) && strlen($resource['subjectmattercomment']) > 0) {
        $reviewerComments .= '<br />Subject Matter: ' . $resource['subjectmattercomment'];
        $newRatings = true;
    }

    if ((int) $resource['supportsteaching'] && (int) $resource['supportsteaching'] >= 0) {
        //$componentRatings .= '<br />' . $resource['supportsteachingcomment'] . ': ' . $resource['supportsteaching'];
        $componentRatings .= '<br />Support Steaching: ' . $resource['supportsteaching'];
        $newRatings = true;
    }

    if (isset($resource['supportsteachingcomment']) && strlen($resource['supportsteachingcomment']) > 0) {
        $reviewerComments .= '<br />Support Steaching: ' . $resource['supportsteachingcomment'];
        $newRatings = true;
    }

    if ((int) $resource['assessmentsquality'] && (int) $resource['assessmentsquality'] >= 0) {
        //$componentRatings .= '<br />' . $resource['assessmentsqualitycomment'] . ': ' . $resource['assessmentsquality'];
        $componentRatings .= '<br />Assessments Quality: ' . $resource['assessmentsquality'];
        $newRatings = true;
    }

    if (isset($resource['assessmentsqualitycomment']) && strlen($resource['assessmentsqualitycomment']) > 0) {
        $reviewerComments .= '<br />Assessments Quality: ' . $resource['assessmentsqualitycomment'];
        $newRatings = true;
    }

    if ((int) $resource['interactivityquality'] && (int) $resource['interactivityquality'] >= 0) {
        //$componentRatings .= '<br />' . $resource['interactivityqualitycomment'] . ': ' . $resource['interactivityquality'];
        $componentRatings .= '<br />Interactivity Quality: ' . $resource['interactivityquality'];
        $newRatings = true;
    }

    if (isset($resource['interactivityqualitycomment']) && strlen($resource['interactivityqualitycomment']) > 0) {
        $reviewerComments .= '<br />Interactivity Quality: ' . $resource['interactivityqualitycomment'];
        $newRatings = true;
    }

    if ((int) $resource['instructionalquality'] && (int) $resource['instructionalquality'] >= 0) {
        //$componentRatings .= '<br />' . $resource['instructionalqualitycomment'] . ': ' . $resource['instructionalquality'];
        $componentRatings .= '<br />Instructional Quality: ' . $resource['instructionalquality'];
        $newRatings = true;
    }

    if (isset($resource['instructionalqualitycomment']) && strlen($resource['instructionalqualitycomment']) > 0) {
        $reviewerComments .= '<br />Instructional Quality: ' . $resource['instructionalqualitycomment'];
        $newRatings = true;
    }

    if ((int) $resource['deeperlearning'] && (int) $resource['deeperlearning'] >= 0) {
        $componentRatings .= '<br />Deeper Learning: ' . $resource['deeperlearning'];
        $newRatings = true;
    }
    if (isset($resource['deeperlearningcomment']) && strlen($resource['deeperlearningcomment']) > 0) {
        $reviewerComments .= '<br />Deeper Learning: ' . $resource['deeperlearningcomment'];
        $newRatings = true;
    }
    /*
      if (!$newRatings && ((int) $resource['technicalcompleteness'] || (int) $resource['contentaccuracy'] || (int) $resource['pedagogy'])) {
      //$componentRatings = 'Technical Completeness: ' . $resource['technicalcompleteness'] . '<br />Content Accuracy: ' . $resource['contentaccuracy'] . '<br />Appropriate Pedagogy: ' . $resource['pedagogy'] . '';
      } */

    /*
      if (isset($resource['ratingcomment']) && strlen($resource['ratingcomment']) > 0) {
      $reviewerComments = trim(str_replace('<strong>Reviewer Comments: </strong>', '', $resource['ratingcomment']));
      } */

    return $newRatings;
}
