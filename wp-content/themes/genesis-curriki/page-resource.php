<?php
require_once 'modules/resource/views/index.php';

/*
 * Template Name: Resource Page Template
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


        
global $resourceUserGlobal; 
$resourceUser = $resourceUserGlobal;

$owner_of_this_post = false;
if(get_current_user_id() > 0){
    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;
    $sql = "select resourceid FROM resources where resourceid = ".$resourceUser["resourceid"]. " and contributorid=".$user_id;

    if (count($wpdb->get_results($sql)) > 0) {
        $owner_of_this_post = true;
    }
}
if  (
        ($resourceUser['approvalStatus'] == 'approved')
        ||
        ($resourceUser['approvalStatus'] == 'pending'&& ($owner_of_this_post || isset($current_user->caps['administrator'])))
        ||
        ($resourceUser['approvalStatus'] == 'rejected' && isset($current_user->caps['administrator']))
        
    ){
    // Execute custom style guide page
    add_filter('body_class', 'curriki_resource_page_add_body_class');
    add_action('genesis_meta', 'curriki_custom_resource_page_loop');
}
//echo "<pre>";
//var_dump($resourceUser);
//die();


function cur_jetpack_open_graph_tags( $tags ) {
    if ( is_singular() && isset($_GET['rid']) || isset($_GET['pageurl']) ) {        
        global $resourceUserGlobal;                                            
        /*
        $res = new CurrikiResources();
        $resourceUser = $res->getResourceUserById((int) $_GET['rid'], rtrim($_GET['pageurl'], '/'));
        */                            
        $resourceUser = $resourceUserGlobal;         
        $current_language = "eng";
        $current_language_slug = "";
        if( defined('ICL_LANGUAGE_CODE') )
        {
            $current_language = cur_get_current_language(ICL_LANGUAGE_CODE); 
            $current_language_slug = ( ICL_LANGUAGE_CODE == "en" ? "":"/".ICL_LANGUAGE_CODE );
        }
        $resource_url = site_url(). $current_language_slug.'/oer/' . htmlentities($resourceUser['pageurl']) ;
        
        $tags['og:url'] = esc_url( $resource_url );
        $tags['og:type'] = "website";
        $tags['og:image'] = site_url()."/wp-content/themes/genesis-curriki/images/device-icons/ios/curriki-01_180.png";        
        if( isset($resourceUser["description"]) && strlen($resourceUser["description"]) > 0 )
        {            
            $og_desc = htmlentities(trim(strip_tags($resourceUser["description"])));            
            $tags['og:description'] = $og_desc." | {$tags['og:url']}";
        }        
    }
    return $tags;
}
add_filter( 'jetpack_open_graph_tags' , 'cur_jetpack_open_graph_tags' );

function curriki_resource_page_add_body_class($classes) {
    $classes[] = 'backend resource-page';
    return $classes;
}

function curriki_custom_resource_page_loop() {

    //* Force full-width-content layout setting
    add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');
    if(SUBDOMAIN == 'studentsearch' || SUBDOMAIN == 'students'){
        remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');
        remove_action('genesis_loop', 'genesis_do_loop');

        //remove header
//        remove_action('genesis_header', 'genesis_header_markup_open', 5);
//        remove_action('genesis_header', 'genesis_do_header');
//        remove_action('genesis_header', 'genesis_header_markup_close', 15);

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
    }

    if ( (isset($_GET['viewer']) && trim($_GET['viewer']) == 'embed') || (isset($_GET['oer-only']) && trim($_GET['oer-only']) == 'true') ) {
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
        //$res = new CurrikiResources();
        //$resourceUser = $res->getResourceUserById((int) $_GET['rid'], rtrim($_GET['pageurl'], '/'));
        global $resourceUserGlobal; 
        $resourceUser = $resourceUserGlobal;
        
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
        $resource_content = htmlentities(trim(strip_tags($resourceUser['description'])));

        echo '<meta property="og:title" content="' . htmlentities($resourceUser['title']) . '"/>
        <meta property="og:url" content="' . get_bloginfo('url') . '/oer/' . htmlentities($resourceUser['pageurl']) . '" />
        <meta property="og:image" content="' . $imageUrl . '"/>
        <meta property="og:site_name" content="Curriki"/>
        <meta property="og:description" content="' . strip_tags($resource_content) . '" />';
    }
}

function curriki_resource_page_scripts() {

    global $resourceUserGlobal;
    $resourceUser = $resourceUserGlobal;

    // Enqueue JQuery Tab and Accordion scripts
    wp_enqueue_script('jquery-ui-tabs');
    wp_enqueue_style('resource-font-awesome-css', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
    wp_enqueue_style('resource-legacy-css', get_stylesheet_directory_uri() . '/css/legacy.css');

    wp_enqueue_style('jquery-fancybox-css', get_stylesheet_directory_uri() . '/js/fancybox_v2.1.5/jquery.fancybox.css?v=2.1.5');
    wp_enqueue_script('jquery-fancybox-js', get_stylesheet_directory_uri() . '/js/fancybox_v2.1.5/jquery.fancybox.pack.js?v=2.1.5', 'jquery.fancybox', '2.1.5');
    wp_enqueue_script('jquery-mousewheel-js', get_stylesheet_directory_uri() . '/js/fancybox_v2.1.5/jquery.mousewheel-3.0.6.pack.js', 'jquery.mousewheel', '3.0.6');

    wp_enqueue_script('bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', 'jquery', '2.1.5');

    //wp_enqueue_style( 'jquery-mobile-css', 'https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.3/jquery.mobile.min.css', 'jquery', '1.4.3' );
    //wp_enqueue_script( 'jquery-mobile', 'https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.3/jquery.mobile.min.js', 'jquery', '1.4.3' );

    wp_enqueue_style('qtip', get_stylesheet_directory_uri() . '/js/qtip2_v2.2.1/jquery.qtip.min.css', null, false, false);
    wp_enqueue_style('questions-css', get_stylesheet_directory_uri() . '/css/questions_tinymce.css');
    wp_enqueue_script('qtip', get_stylesheet_directory_uri() . '/js/qtip2_v2.2.1/jquery.qtip.min.js', array('jquery'), false, true);
    wp_enqueue_script('page-resource', get_stylesheet_directory_uri() . '/js/page-resource.js', array('jquery'), false, true);
    wp_enqueue_style('collection-css', get_stylesheet_directory_uri() . '/css/collection-page/collection.css');
    ?>
    <style type="text/css">      
    <?php
    if ((SUBDOMAIN == 'studentsearch' || SUBDOMAIN == 'students')) {
        echo '.page-header {background: none;padding: 10px 0 20px 0;}';
        echo '.widget-area.header-widget-area {display:none;}';
        echo '.site-header {position:relative;padding-left: 15px;}';
        echo 'body.backend {background: #BCBEC0; }';
        echo '.site-container {border: 10px solid #031770; padding: 0; max-width: 1200px; padding: 10px;}';
        echo '.resource-content-content:nth(1) { border: 5px solid #99C736; }';
        echo '.resource-header-info{width: 100%;}';
        echo 'a#powered-by-curriki {background: url(https://www.curriki.org/wp-content/uploads/2016/04/CurrikiPoweredBy_120x52.png) top left no-repeat; display: inline; float: right;height: 52px;text-indent: -9999px;width: 120px;}';
        echo '.resource-content>.resource-content-content{border: 5px solid #99c736;}';
    }

    if ((isset($_GET['viewer']) && trim($_GET['viewer']) == 'embed')) {
        echo '.page-header {background: none;padding: 10px 0 20px 0;}';
    }

    if ( (isset($_GET['viewer']) && trim($_GET['viewer']) == 'embed') || (isset($_GET['oer-only']) && trim($_GET['oer-only']) == 'true') ) {
        echo '.page-header {padding-top:20px !important;}'; 
        echo '#resource-sidebar,#container_you_may_like,#container_premium_resources,#container_in_collections {display:none !important;}'; 
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

        @media (max-width: 640px) {
            .resource-content-sidebar {
                display: -webkit-flex;
                display: flex;
                -webkit-flex-direction: column-reverse;
                flex-direction: column-reverse;
            }
            .toc-card {
                margin-top: 30px;
            }
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
            });

            jQuery(document).ready(function () {

                jQuery( '.resource-content-content a' ).on( 'click', function() {
                    var href = jQuery( this ).attr( "href" );

                    if (!href.includes("curriki") && !href.startsWith("/")) {
                        var ajax_options = {
                            action: 'resource_content_link_log',
                            nonce: '<?php echo wp_create_nonce( 'resource_content_link_log_' . $resourceUser["resourceid"] ); ?>',
                            ajaxurl: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                            resource_id: '<?php echo $resourceUser["resourceid"]; ?>',
                            url: href
                        };

                        var redirectWindow = window.open(href, '_blank');
                        jQuery.post( ajax_options.ajaxurl, ajax_options, function() {
                            redirectWindow.location;
                        });

                        return false;
                    }
                });

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


                jQuery('#google-classroom-btn').qtip({
                    content: {
                        text: '<div style="text-align:center;">Share with Google Classroom</div> <div style="margin-top: 5px;margin-left: 50px;"> <div id="gc-widget-div-hld">  </div> </div>'
                    },
                    hide: 'unfocus',
                    events: {
                        render: function (event, api) {
                            // Grab the tip element
                            var elem = api.elements.tip;
                            /*var rs_url = "<?php //echo urlencode(get_bloginfo('url') . '/oer/' . $resourceUser['pageurl'] . '">' . get_bloginfo('url') . '/oer/' . $resourceUser['pageurl']);           ?>";                        
                             var params = {"data-url":encodeURI(rs_url) , "data-title": jQuery(".resource-title").text()};
                             console.log("params = " , params);
                             gapi.sharetoclassroom.render("gc-widget-div" , params);*/
                            jQuery("#gc-widget-div-hld").html(jQuery("#gc-widget-div").clone());
                        }
                    }
                });


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
                jQuery('.document-download').click(function(){
//                    var href = jQuery(this).attr("href");
//                    jQuery.ajax({
//                        type: "GET",
//                        url: href,
//                        data: {},
//                        success: function(data){
//                            console.log(data);
//                        },
//                        error: function(){
//                            console.log("Error");
//                        }
////                        dataType: dataType
//                      });
////                    alert();
//                    return false;
                });
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
            <?php
            if (isset($_GET['oer-only']) && trim($_GET['oer-only']) == 'true') {
                echo "window.location.assign(url);return true;";
            }
            ?>
                var t = new Date().getTime();
                jQuery.ajax({
                    method: "POST",
                    url: "<?php echo get_bloginfo('url'); ?>/oer/?resource_file_download=file&t="+t,
                    data: {id: id, rid:jQuery("#rid_current").val()}
                }).done(function (msg) {
                        var response = JSON.parse(msg);                        
                        if(response.action === "redirect")
                        {
                            window.location.assign(response.redirect_url+"&fwdreq="+ encodeURI(response.forward_url) );
                        }else if(response.action === "done")
                        {
                            window.location.assign(url);
                        }
                        //window.location.assign(url);
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
                            jQuery("#modal-resource-added #msg_title").html("<?php echo __('Resource Flagged as inappropriate!','curriki'); ?>");
                            jQuery("#modal-resource-added #msg_para").html("");
                            jQuery('#modal-resource-added').modal('show');
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
                            jQuery("#modal-resource-added #msg_title").html("<?php echo __('Resource Nominated For Review!','curriki'); ?>");
                            jQuery("#modal-resource-added #msg_para").html("");
                            jQuery('#modal-resource-added').modal('show');

                            jQuery('#modal-resource-added').on('hidden.bs.modal', function (e) {
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
                        .done(function (msg) { load_add_to_lib_modal(id);
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

                     if( typeof window.fancyContentWindow === 'object' && window.fancyContentWindow !== null){
                        jQuery(window.fancyContentWindow.document).find(".no-close-confirm-alert").click(function () {
                            jQuery("#close-confirm-alert").hide();
                            jQuery.fancybox.close();
                        });
                     }                    

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
                //console.log("fancy-states_gobal_arr = ", window.fancyContentWindow.states_gobal_arr);
                //console.log("states_gobal_arr_initial = ", jQuery(window.fancyContentWindow.document).find("#states_gobal_arr_initial").val());

                var states_gobal_arr_initial = (typeof window.fancyContentWindow === 'object' && window.fancyContentWindow !== null) ? JSON.parse(jQuery(window.fancyContentWindow.document).find("#states_gobal_arr_initial").val()) : [];
                /*console.log(" states_gobal_arr_initial ===> " , array1);*/

                var array1 = window.fancyContentWindow !== null && typeof window.fancyContentWindow.states_gobal_arr === 'object' ? window.fancyContentWindow.states_gobal_arr:[];
                var array2 = states_gobal_arr_initial;
                
                var do_stop = false;
                if(window.fancyContentWindow !== null && typeof window.fancyContentWindow.states_gobal_arr === 'object'){
                    jQuery(window.fancyContentWindow.states_gobal_arr).each(function (i, obj) {
                        if (jQuery.inArray(obj, states_gobal_arr_initial) == -1)
                        {
                            do_stop = true;
                        }
                    });
                }                

                console.log(" do_stop ", do_stop);
                //console.log(" states_removed_existing_arr ---> ", window.fancyContentWindow.states_removed_existing_arr);
                var s_r_e = window.fancyContentWindow !== null && typeof window.fancyContentWindow.states_removed_existing_arr === 'object' ? window.fancyContentWindow.states_removed_existing_arr:[];
                if ((typeof window.fancyContentWindow === 'object' && window.fancyContentWindow !== null) && do_stop == true || s_r_e.length > 0)
                {
                    jQuery(window.fancyContentWindow.document).find("#close-confirm-alert").show();
                } else {
                    jQuery.fancybox.close();
                }

                if(jQuery('#fancyBoxInlineAfterRegister').length > 0){
                    window.location = window.location.origin+"/oer/"+jQuery("#pageurl_param").val();
                }

                //console.log("window.un_matched_elements = " + window.un_matched_elements.length, window.un_matched_elements);
                //console.log("removed_elements = ", window.fancyContentWindow.states_removed_existing_arr);

            });

            jQuery(".close-add-to-lib-alert").on("click", function () {

                //console.log( "inappropriate-cls = " , jQuery("#add-to-lib-alert").hasClass("inappropriate-cls") );

                jQuery("#add-to-lib-alert").hide();

                if (jQuery("#add-to-lib-alert").hasClass("resourcereviewed-cls"))
                {
                    jQuery("#resourceReviewed").hide();
                }

            });
            var timeStamp = Math.floor(Date.now() / 1000);             
            //var oer_url = ajaxurl+"&="+timeStamp;        
            var oer_url = ajaxurl;
            var set_resource_views_data = {'rid': jQuery("#rid_param").val() ,'pageurl':jQuery("#pageurl_param").val() , 'lvid':jQuery("#lvid").val()};
            jQuery.ajax({
                method: "POST",
                url: ajaxurl ,
                data : {'action':'cur_oer_page_count' , 'set_resource_views_data' : set_resource_views_data}
            })
            .done(function( data ) {
                var data = JSON.parse(data);        
                if( data.is_redirect == 1 )
                {
                    window.location = data.redirect_url;
                }
            });
        });
    </script>
    <?php
}

function curriki_resource_header() {
    
    $current_language = "eng";
    $current_language_slug = "";
    if( defined('ICL_LANGUAGE_CODE') )
    {
        $current_language = cur_get_current_language(ICL_LANGUAGE_CODE); 
        $current_language_slug = ( ICL_LANGUAGE_CODE == "en" ? "":"/".ICL_LANGUAGE_CODE );
    }

    $current_user = wp_get_current_user();

    if (function_exists('check_resource_rating'))
        check_resource_rating();

    
    if (isset($_GET['rid']) || isset($_GET['pageurl'])) {
        
        global $resourceUserGlobal; 
        $resourceUser = $resourceUserGlobal;        
//        /*
//        $res = new CurrikiResources();
//        $resourceUser = $res->getResourceUserById((int) $_GET['rid'], rtrim($_GET['pageurl'], '/'));        
//         * 
//         */
    }

    // ======== [start] Manage Add to resource/collection buttons ==========
    if (get_current_user_id() > 0) {
        global $wpdb;
        $c_id = $resourceUser["resourceid"];

        $user_id = get_current_user_id();
        $sql_btn = "
            select c.title as Collection
                from resources c                                        
                    where c.type = 'collection'
                    and c.contributorid = $user_id
                    and c.active = 'T'
                    and c.resourceid in ($c_id)
                union
                select c.title as Collection
                from cur_bp_groups cbg
                    inner join cur_bp_groups_members cbgm on cbgm.group_id = cbg.id
                    inner join group_resources gr on gr.groupid = cbg.id
                    inner join resources c on gr.resourceid = c.resourceid                    
                where c.type = 'collection'
                    and c.active = 'T'
                    and cbgm.user_id = $user_id   
                    and c.resourceid in ($c_id)
              ";


        if ($resourceUser['type'] == "collection" && count($wpdb->get_results($sql_btn)) > 0) {/* to display add resource/collection buttons */
        }
        
    }
    // ======== [end] Manage Add to resource/collection buttons ==========
    $owner_of_this_post = false;
    if(get_current_user_id() > 0){
        $user_id = get_current_user_id();
        $sql = "select resourceid FROM resources where resourceid = ".$resourceUser["resourceid"]. " and contributorid=".$user_id;
        
        if (count($wpdb->get_results($sql)) > 0) {
            $owner_of_this_post = true;
        }
    }

    $location = '';
    if (trim($resourceUser['city']) != '')
        $location .= $resourceUser['city'] . ', ';
    if (trim($resourceUser['state']) != '')
        $location .= $resourceUser['state'] . ', ';
    if (trim($resourceUser['country']) != '')
        $location .= $resourceUser['country'] . ', ';

    if (!isset($_GET['viewer'])) {
        if ((int) $resourceUser['memberrating'] == 0)
            $stars = '<i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>';
        elseif ((int) $resourceUser['memberrating'] == 1)
            $stars = '<i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>';
        elseif ((int) $resourceUser['memberrating'] == 2)
            $stars = '<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>';
        elseif ((int) $resourceUser['memberrating'] == 3)
            $stars = '<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>';
        elseif ((int) $resourceUser['memberrating'] == 4)
            $stars = '<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i>';
        elseif ((int) $resourceUser['memberrating'] == 5)
            $stars = '<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>';
    }

    $resource_header = '<div class="resource-header page-header">';
    $resource_header .= '<div class="wrap container_12 page-gutter">';
    $resource_header .= '<div class="resource-info page-info">';
    $resource_header .= '<div class="resource-head">';

    $theme_url = get_stylesheet_directory_uri();
    $resourceThumbImage = $theme_url . '/images/subjects/Arts/General.jpg';

    if (isset($_GET['rid']) || isset($_GET['pageurl'])) {
        $res = new CurrikiResources();
        $resource = $res->getResourceById((int) $_GET['rid'], rtrim($_GET['pageurl'], '/'), true);

        if (isset($resource['type']) && $resource['type'] == 'collection' && isset($resource['collection'])) {

            // $rid = $resource['resourceid'];
            // $persist_rids[] = $rid;
            // $persist_rids = array_unique($persist_rids);
            // $mrid = implode("-", $persist_rids);

            foreach ($resource['collection'] as $collection) {

                // $url = get_bloginfo('url') . '/oer/' . $collection['pageurl'];
                // $url .= "/?mrid=" . $mrid;
                // $url .= isset($_GET['oer-only']) && $_GET['oer-only'] == 'true' ? '&oer-only=true' : '';

                if ($collection['thumb_image']) {
                    $resourceThumbImage = $collection['thumb_image'];
                } else {
                    $resourceSubject = '';
                    $resourceSubjectArea = '';
                    $resourceSubjectAreaExt = 'png';
                    if (isset($collection['subsubjectarea'])) {
                        $resourceSubjectAreaArray = explode(' > ', $collection['subsubjectarea'][array_rand($collection['subsubjectarea'])]);
                        $resourceSubject = preg_replace('/\PL/u', '', $resourceSubjectAreaArray[0]);
                        $resourceSubjectArea = preg_replace('/\PL/u', '', $resourceSubjectAreaArray[1]);

                        if ($resourceSubject == 'Arts' || $resourceSubject == 'CareerTechnicalEducation') {
                            $resourceSubjectAreaExt = 'jpg';
                        }

                        $resourceThumbImage = $theme_url . '/images/subjects/' . $resourceSubject . '/' . $resourceSubjectArea . '.' . $resourceSubjectAreaExt;
                    }
                }
            }
        }
    }

//    $resource_header .= '<img class="resource-thumbnail" src="' . $resourceThumbImage . '" width="175" height="121" alt="thumbnail">';

    $user_info = get_userdata($resourceUser['userid']);
    $user_page_url = '';
    if (is_object($user_info))
        $user_page_url .= site_url() . "/members/" . $user_info->user_nicename;

    $resource_header .= '<div class="resource-middle">';
    $resource_header .= '<h1 class="resource-title page-title resource-title-heading">' . htmlentities($resourceUser['title']) . '</h1>';
    $resource_header .= '<div class="resource-link page-link">';
    $resource_header .= '<span class="resource-author">by ' . $resourceUser['display_name'] . ', ' . $resourceUser['organization'] . ' ' . $location . '.</span>';

    if (!isset($_GET['viewer'])) {
        $cnt_date = isset($resourceUser['contributiondate']) ? date("F j, Y", strtotime($resourceUser['contributiondate'])) : "";
        $resource_header .= '<span class="resource-date">Created on: ' . $cnt_date . '</span>';
        $resource_header .= '</div>';
        $resource_header .= '<p class="adr">' . __('Website Address','curriki') . ': <a href="' . get_bloginfo('url') . '/oer/' . htmlentities($resourceUser['pageurl']) . '">' . get_bloginfo('url') . '/oer/' . htmlentities($resourceUser['pageurl']) . '</a></p>';

        $resource_header .= '<div class="resource-header-meta">';
        $resource_header .= '<div class="resource-header-rating">';
        $resource_header .= '<span class="rating-title">'.__('Member Rating','curriki').'</span>';
        $resource_header .= '<span class="rating-stars">';
        $resource_header .= $stars;
        $resource_header .= '</span>';

        if(SUBDOMAIN != 'studentsearch' && SUBDOMAIN != 'students'){
            if (is_user_logged_in())
                $resource_header .= '<a class="rate-link" data-toggle="modal" href="#modal-member-review">'.__('Rate this resource','curriki').'</a>';
        }
        $resource_header .= '</div>';

        $resource_header .= '<div class="resource-header-rating">';
        $resource_header .= '<span class="rating-title">'.__('Curriki Rating','curriki').'</span>';

        $do_nominate = false;
        $resourceUser['reviewrating'] = isset($resourceUser['reviewrating']) ? round((float) $resourceUser['reviewrating'], 1) : null;

        $qtip_text = "";
        if (isset($resourceUser['reviewstatus']) && $resourceUser['reviewstatus'] == 'reviewed' && $resourceUser['reviewrating'] != null && $resourceUser['reviewrating'] >= 0) {
            $qtip_text = '<div class="hidden-qtip">'.__('On a scale of 0 to 3','curriki').'</div>';
            $resource_header .= '<span class="rating-points curriki-rating-title-text tooltip-rating">' . $resourceUser['reviewrating'] . '/3.0</span>' . $qtip_text;
        } elseif (isset($resourceUser['reviewstatus']) && $resourceUser['reviewstatus'] == 'reviewed' && $resourceUser['reviewrating'] != null && $resourceUser['reviewrating'] < 0) {
            $qtip_text = '<div class="hidden-qtip">'.__('Commented','curriki').'</div>';
            $resource_header .= '<span class="rating-points curriki-rating-title-text tooltip-rating">-</span>' . $qtip_text;
        } elseif (isset($resourceUser['partner']) && $resourceUser['partner'] == 'T') {
            $qtip_text = '<div class="hidden-qtip"><strong>\'P\'</strong> - '.__('This is a trusted Partner resource','curriki').'</div>';
            $resource_header .= '<span class="rating-points curriki-rating-title-text tooltip-rating">P</span>' . $qtip_text;
        } elseif (isset($resourceUser['partner']) && $resourceUser['partner'] == 'C') {
            $qtip_text = '<div class="hidden-qtip"><strong>\'C\'</strong> - '.__('Curriki rating','curriki').'</div>';
            $resource_header .= '<span class="rating-points curriki-rating-title-text tooltip-rating">C</span>' . $qtip_text;
        } else {
            $qtip_text = '<div class="hidden-qtip"><strong>\'NR\'</strong> - '.__('This resource has not been rated','curriki').'</div>';
            $resource_header .= '<span class="rating-points curriki-rating-title-text tooltip-rating">NR</span>' . $qtip_text;
            $do_nominate = true;
        }

        $resource_header .= '</div>';
    }

    $resource_header .= '</div>';
    $resource_header .= '</div>';

    if (!isset($_GET['viewer'])) {

        $resource_header .= '<div class="resource-dropdown dropdown">';
        $resource_header .= '<a class="btn" href="#" data-toggle="dropdown">'.__('More Actions','curriki').' <i class="fa fa-angle-down"></i></a>';
        $resource_header .= '<ul class="dropdown-menu">';

        // if (!isset($_GET['viewer']) && is_user_logged_in()) {
        if (!isset($_GET['viewer']) && (in_array("content_creator", $current_user->roles) || (isset($current_user->caps['administrator'])))) {
            if(SUBDOMAIN != 'studentsearch' && SUBDOMAIN != 'students'){
                $resource_header .= '<li><a class="dropdown-item" href="#" onclick="addToMyLibrary(' . $resourceUser['resourceid'] . ');"><i class="fa fa-file"><span class="plus">+</span></i> '.__('Add to My Library','curriki').'</a></li>';
            }

            if ($resourceUser['type'] == "collection" && ((count($wpdb->get_results($sql_btn)) > 0) || (isset($current_user->caps['administrator'])))) {
                $resource_header .= '<li><a class="dropdown-item" href="#" onclick="addfolder(' . $resourceUser['resourceid'] . ');"><i class="fa fa-folder"><span class="plus">+</span></i> '.__('Add Folder','curriki').'</a></li>';
                $resource_header .= '<li><a class="dropdown-item" href="#" onclick="addresource(' . $resourceUser['resourceid'] . ');"><i class="fa fa-plus-square"></i> '.__('Add Resource','curriki').'</a></li>';
            }

            $resource_header .= '<li class="divider"></li>';
        }

        if (!isset($_GET['viewer'])) {
            if((($owner_of_this_post && in_array("content_creator", $current_user->roles)) || in_array("resourceEditor", $current_user->roles) ) && !isset($current_user->caps['administrator'])) {
                $resource_header .= '<li><a class="dropdown-item" href="' . get_bloginfo('url') . "/create-resource/?resourceid=" . $resourceUser['resourceid'] . '"><i class="fa fa-pencil-square"></i> '.__('Edit a Collection','curriki').'</a></li>';
                $resource_header .= '<li class="divider"></li>';

                if ($owner_of_this_post && $resourceUser['type'] == "collection")
                    $resource_header .= '<li><a class="dropdown-item organize-collections-title" href="javascript:;" id="organize-collections-title-'.$resourceUser['resourceid'].'"><i class="fa fa-list"></i> '.__('Organize Collection','curriki').'</a></li>';
            }

            if (isset($current_user->caps['administrator'])  ) {
                $resource_header .= '<li><a class="dropdown-item" href="' . get_bloginfo('url') . "/create-resource/?resourceid=" . $resourceUser['resourceid'] . '"><i class="fa fa-pencil-square"></i> '.__('Edit a Collection','curriki').'</a></li>';

                if ($owner_of_this_post && $resourceUser['type'] == "collection")
                    $resource_header .= '<li><a class="dropdown-item organize-collections-title" href="javascript:;" id="organize-collections-title-'.$resourceUser['resourceid'].'"><i class="fa fa-list"></i> '.__('Organize Collection','curriki').'</a></li>';

                $review_popup_link = get_bloginfo('url') . '/oer/' . htmlentities($resourceUser['pageurl'])."/?action=review_resource";
                $resource_header .= '<li><a class="dropdown-item" href="'.$review_popup_link.'"><i class="fa fa-eye"></i> '.__('Review','curriki').'</a></li>';
                $resource_header .= '<li class="divider"></li>';
            }

            $resource_url = urlencode(get_bloginfo('url') . '/oer/?rid=');
            $facebook = 'https://www.facebook.com/sharer/sharer.php?u=' . site_url() .$current_language_slug. '/oer/' . htmlentities($resourceUser['pageurl']);
            $twitter = 'https://twitter.com/intent/tweet?text=Check out this great resource I found on Curriki! ' . htmlentities($resourceUser['title']) . '-OER via @Curriki' . '&url=' . get_bloginfo('url') . '/oer/' . htmlentities($resourceUser['pageurl']) . '%23.VVSralG2Opo.twitter&related=';

            $resource_header .= '<li class="text-center">';
            $resource_header .= __('Share Collection','curriki');
            $resource_header .= '<div class="resource-share share-icons">';
            $resource_header .= '<a href="' . $facebook . '" class="share-facebook"><span class="fa fa-facebook"></span></a>';
            $resource_header .= '<a href="' . $twitter . '" class="share-twitter"><span class="fa fa-twitter"></span></a>';
            $resource_header .= '<a href="mailto:?subject=See this article&amp;body=' . $resource_url . $resourceUser['resourceid'] . '" class="share-email"><span class="fa fa-envelope-o"></span></a>';
            $resource_header .= '<a href="#" id="google-classroom-btn" class="google-classroom-btn-cls">';
            $google_classroom_img = site_url() . "/wp-content/themes/genesis-curriki/images/GoogleClassroomIcon_gray.png";
            $resource_header .= '<img src="' . $google_classroom_img . '" alt="">';
            $resource_header .= '</a>';
            $resource_header .= '</div>';
            $resource_header .= '</li>';
            $resource_header .= '<li class="divider"></li>';

            if(SUBDOMAIN != 'studentsearch' && SUBDOMAIN != 'students'){
                $resource_header .= '<li><a class="dropdown-item item-danger" href="#" onclick="resourceInappropriate(' . ( isset($resourceUser['resourceid']) ? $resourceUser['resourceid'] : "" ) . ')"><i class="fa fa-flag"></i> '.__('Flag as inappropriate','curriki').'</a></li>';

                if ($do_nominate) {
                    $resource_header .= '<li><a class="dropdown-item item-danger" href="#" onclick="resourceReviewed(' . ( isset($resourceUser['resourceid']) ? $resourceUser['resourceid'] : "" ) . ')"><i class="fa fa-star"></i> '.__('Nominate for Review','curriki').'</a></li>';
                }

                $resource_header .= '<li class="divider"></li>';
            }

            $resource_header .= '<li><a class="dropdown-item" href="?rid=' . $_GET['rid'] . '&screenaction=print"><i class="fa fa-print"></i> '.__('Print this collection','curriki').'</a></li>';

            if (trim($resourceUser['uniquename']) != '')
                $resource_header .= '<li><a class="dropdown-item" href="#" onclick="resourceFileDownload(' . $resourceUser['fileid'] . ', \'https://' . DBI_AWS_S3_CDN_URL . '/' . $resourceUser['folder'] . $resourceUser['uniquename'] . '\')"><i class="fa fa-download"></i> '.__('Download File','curriki').'</a></li>';

            $resource_header .= '<input type="hidden" name="fileid" id="fileid" value="' . $resourceUser['fileid'] . '" />';
        }

        if (isset($_GET['back_url']))
            $resource_header .= '<li><a class="dropdown-item" href="' . base64_decode($_GET['back_url']) . '"><i class="fa fa-arrow-left"></i> '.__('Return to Group','curriki').'</a></li>';

        $resource_header .= '</ul>';
        $resource_header .= '</div>';
    }

    $resource_header .= '</div>'; // Closing .resource-head
    $resource_header .= '</div>'; // Closing .resource-info page-info
    $resource_header .= '</div>'; // Closing .wrap container_12 page-gutter
    $resource_header .= '</div>'; // Closing .resource-header page-header

    if (!isset($_GET['viewer'])) {
        $resource_header .= '<div class="modal modal-secondary fade" id="modal-resource-added" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-wrap">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">×</button>
                                                <h4 class="modal-title" id="msg_title">Resource Added!</h4>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong id="msg_para">The resource has been added to your collection</strong></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>';
    }

    echo $resource_header;
}

function curriki_resource_page_body() {
    $resource = array();
    if (isset($_GET['rid']) || isset($_GET['pageurl'])) {
        $res = new CurrikiResources();
        $resource = $res->getResourceById((int) $_GET['rid'], rtrim($_GET['pageurl'], '/'), true);
        
        $_SESSION["resourceid_val"] = $resource["resourceid"];
        $_SESSION["pageurl_val"] = $resource["pageurl"];
        
        if ($resource['access'] == 'public')
            $resource['access'] = 'Public - Available to anyone';
        elseif ($resource['access'] == 'private')
            $resource['access'] = 'Private';
        elseif ($resource['access'] == 'members')
            $resource['access'] = 'Members';

        $componentRatings = $reviewerComments = '';
        $newRatings = false;

        if ((int) $resource['standardsalignment'] && (int) $resource['standardsalignment'] >= 0) {
            //$componentRatings .= '<li>' . $resource['standardsalignmentcomment'] . ': ' . $resource['standardsalignment'];
            $componentRatings .= '<li>Standards Alignment: ' . $resource['standardsalignment'] . '</li>';
            $newRatings = true;
        }
        if (isset($resource['standardsalignmentcomment']) && strlen($resource['standardsalignmentcomment']) > 0) {
            $reviewerComments .= '<li>Standards Alignment: ' . $resource['standardsalignmentcomment'] . '</li>';
            $newRatings = true;
        }

        if ((int) $resource['subjectmatter'] && (int) $resource['subjectmatter'] >= 0) {
            //$componentRatings .= '<li>' . $resource['subjectmattercomment'] . ': ' . $resource['subjectmatter'];
            $componentRatings .= '<li>Subject Matter: ' . $resource['subjectmatter'] . '</li>';
            $newRatings = true;
        }

        if (isset($resource['subjectmattercomment']) && strlen($resource['subjectmattercomment']) > 0) {
            $reviewerComments .= '<li>Subject Matter: ' . $resource['subjectmattercomment'] . '</li>';
            $newRatings = true;
        }

        if ((int) $resource['supportsteaching'] && (int) $resource['supportsteaching'] >= 0) {
            //$componentRatings .= '<li>' . $resource['supportsteachingcomment'] . ': ' . $resource['supportsteaching'];
            $componentRatings .= '<li>Support Steaching: ' . $resource['supportsteaching'] . '</li>';
            $newRatings = true;
        }

        if (isset($resource['supportsteachingcomment']) && strlen($resource['supportsteachingcomment']) > 0) {
            $reviewerComments .= '<li>Support Steaching: ' . $resource['supportsteachingcomment'] . '</li>';
            $newRatings = true;
        }

        if ((int) $resource['assessmentsquality'] && (int) $resource['assessmentsquality'] >= 0) {
            //$componentRatings .= '<li>' . $resource['assessmentsqualitycomment'] . ': ' . $resource['assessmentsquality'];
            $componentRatings .= '<li>Assessments Quality: ' . $resource['assessmentsquality'] . '</li>';
            $newRatings = true;
        }

        if (isset($resource['assessmentsqualitycomment']) && strlen($resource['assessmentsqualitycomment']) > 0) {
            $reviewerComments .= '<li>Assessments Quality: ' . $resource['assessmentsqualitycomment'] . '</li>';
            $newRatings = true;
        }

        if ((int) $resource['interactivityquality'] && (int) $resource['interactivityquality'] >= 0) {
            //$componentRatings .= '<li>' . $resource['interactivityqualitycomment'] . ': ' . $resource['interactivityquality'];
            $componentRatings .= '<li>Interactivity Quality: ' . $resource['interactivityquality'] . '</li>';
            $newRatings = true;
        }

        if (isset($resource['interactivityqualitycomment']) && strlen($resource['interactivityqualitycomment']) > 0) {
            $reviewerComments .= '<li>Interactivity Quality: ' . $resource['interactivityqualitycomment'] . '</li>';
            $newRatings = true;
        }

        if ((int) $resource['instructionalquality'] && (int) $resource['instructionalquality'] >= 0) {
            //$componentRatings .= '<li>' . $resource['instructionalqualitycomment'] . ': ' . $resource['instructionalquality'];
            $componentRatings .= '<li>Instructional Quality: ' . $resource['instructionalquality'] . '</li>';
            $newRatings = true;
        }

        if (isset($resource['instructionalqualitycomment']) && strlen($resource['instructionalqualitycomment']) > 0) {
            $reviewerComments .= '<li>Instructional Quality: ' . $resource['instructionalqualitycomment'] . '</li>';
            $newRatings = true;
        }

        if ((int) $resource['deeperlearning'] && (int) $resource['deeperlearning'] >= 0) {
            $componentRatings .= '<li>Deeper Learning: ' . $resource['deeperlearning'] . '</li>';
            $newRatings = true;
        }
        if (isset($resource['deeperlearningcomment']) && strlen($resource['deeperlearningcomment']) > 0) {
            $reviewerComments .= '<li>Deeper Learning: ' . $resource['deeperlearningcomment'] . '</li>';
            $newRatings = true;
        }

        if (!$newRatings && ((int) $resource['technicalcompleteness'] || (int) $resource['contentaccuracy'] || (int) $resource['pedagogy'])) {
            $componentRatings = '<li>Technical Completeness: ' . $resource['technicalcompleteness'] . '</li><li>Content Accuracy: ' . $resource['contentaccuracy'] . '</li><li>Appropriate Pedagogy: ' . $resource['pedagogy'] . '</li>';
        }

        if (isset($resource['ratingcomment']) && strlen($resource['ratingcomment']) > 0) {
            $reviewerComments = '<li>' . trim( str_replace('Reviewer Comments:', '', strip_tags($resource['ratingcomment']) ) ) . '</li>';
        }
    }

    if (empty($resource['resourceid']))
        header(sprintf('Location: %s/resources-curricula', site_url()));

        $resource_content = '<div class="wrap container_12 page-gutter">';

    if (!isset($_GET['viewer'])) {        
        $resource_content .= '<div id="resource-tabs">';

            $resource_content .= '<div class="resource-tabs page-tabs">';
                $resource_content .= '<ul>';
                    $resource_content .= '<li><a href="#content"><span class="tab-icon fa fa-th-list"></span> <span class="tab-text">'.__('Content','curriki').'</span></a></li>';
                    $resource_content .= '<li><a data-toggle="modal" href="#modal-information"><span class="tab-icon fa fa-info-circle"></span> <span class="tab-text">'.__('Information','curriki').'</span></a></li>';
                    // $resource_content .= '<li><a href="#standards"><span class="tab-icon fa fa-user"></span> <span class="tab-text">'.__('Standards','curriki').'</span></a></li>';
                    $resource_content .= '<li><a data-toggle="modal" href="#modal-reviews"><span class="tab-icon fa fa-star"></span> <span class="tab-text">'.__('Reviews','curriki').'</span></a></li>';
                $resource_content .= '</ul>';

                $resource_content .= '<div class="modal modal-secondary fade" id="modal-reviews" tabindex="-1" role="dialog">';
                    $resource_content .= '<div class="modal-dialog" role="document">';
                        $resource_content .= '<div class="modal-content">';
                            $resource_content .= '<div class="modal-wrap">';
                                $resource_content .= '<div class="modal-header">';
                                    $resource_content .= '<button type="button" class="close" data-dismiss="modal">×</button>';
                                    $resource_content .= '<h4 class="modal-title">' . __('Reviews','curriki') . ' - ' . htmlentities($resource['title']) . '</h4>';
                                $resource_content .= '</div>';
                                $resource_content .= '<div class="modal-body">';
                                    $resource_content .= '<h3 class="section-title">'.__('Curriki Rating','curriki').'</h3>';
                                    
                                    if (isset($resource['reviewstatus']) && $resource['reviewstatus'] == 'reviewed' && $resource['reviewrating'] != null && $resource['reviewrating'] >= 0) {
                                        $resource_content .= '<p>'.__('This resource was reviewed using the Curriki Review rubric and received an overall Curriki Review System rating of ','curriki') . $resource['reviewrating'] . ', '.__('as of','curriki').' ' . date("Y-m-d", strtotime($resource['lastreviewdate'])) . '.</p>';
                                    } elseif (isset($resource['reviewstatus']) && $resource['reviewstatus'] == 'reviewed' && $resource['reviewrating'] != null && $resource['reviewrating'] < 0) {
                                        $resource_content .= '<p>'.__('This resource was reviewed using the Curriki Review rubric and received an overall Curriki Review System rating of ','curriki') . '(-)' . ' '.__('as of','curriki').' ' . date("Y-m-d", strtotime($resource['lastreviewdate'])) . '.</p>';
                                    } elseif (isset($resource['partner']) && $resource['partner'] == 'T') {
                                        //$resource_content .= '<p>This resource was reviewed using the Curriki Review rubric and received an overall Curriki Review System rating of ' . '(-)' . ' as of ' . date("Y-m-d", strtotime($resource['lastreviewdate'])) . '.</p>';
                                    } elseif (isset($resource['partner']) && $resource['partner'] == 'C') {
                                        //$resource_content .= '<p>This resource was reviewed using the Curriki Review rubric and received an overall Curriki Review System rating of ' . '(-)' . ' as of ' . date("Y-m-d", strtotime($resource['lastreviewdate'])) . '.</p>';
                                    } else {
                                        $resource_content .= '<p>'.__('This resource has not yet been reviewed.','curriki').'</p>';
                                    }

                                    if ($componentRatings) {
                                        $resource_content .= '<h4>'.__('Component Ratings','curriki').':</h4>';
                                        $resource_content .= '<ul class="list-rating">';
                                            $resource_content .= $componentRatings;
                                        $resource_content .= '</ul>';
                                    }
            
                                    if ($reviewerComments) {
                                        $resource_content .= '<br/><h4>'.__('Reviewer Comments','curriki').':</h4>';
                                        $resource_content .= '<ul class="list-rating">';
                                            $resource_content .= $reviewerComments;
                                        $resource_content .= '</ul>';
                                    }

                                    if(SUBDOMAIN != 'studentsearch' && SUBDOMAIN != 'students'){
                                        if (is_user_logged_in()) {
                                            $resource_content .= '<h3 class="section-title">' . __('Member Rating','curriki') . '</h3>';
                                        } else {
                                            $reviews_tab .= '<input type="hidden" name="resourceid" value="' . $resource['resourceid'] . '" />';
                                        }
                                    }
            
                                    if (isset($resource['comments'])) {
                                        foreach ($resource['comments'] AS $comment) {
                                            if ($comment['rating'] == 0)
                                                $stars = '<i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>';
                                            elseif ($comment['rating'] == 1)
                                                $stars = '<i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>';
                                            elseif ($comment['rating'] == 2)
                                                $stars = '<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>';
                                            elseif ($comment['rating'] == 3)
                                                $stars = '<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>';
                                            elseif ($comment['rating'] == 4)
                                                $stars = '<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i>';
                                            elseif ($comment['rating'] == 5)
                                                $stars = '<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>';
            
                                            $resource_content .= '<div class="media">';
            
                                            if (trim($comment['uniqueavatarfile']) != '')
                                                $resource_content .= '<img class="media-object" src="https://' . DBI_AWS_S3_CDN_URL . '//avatars/' . $comment['uniqueavatarfile'] . '" width="34" height="34" alt="thumb">';
                                            else
                                                $resource_content .= '<img class="media-object" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" width="34" height="34" alt="thumb">';
            
                                            $resource_content .= '<div class="media-body">';
                                                $resource_content .= '<h4>' . $comment['display_name'] . '</h4>';
            
                                                if ($comment['date'] != '0000-00-00 00:00:00')
                                                    $resource_content .= '<p>' . date("F j, Y", strtotime($comment['date'])) . '</p>';

                                            $resource_content .= '<p>' . strip_tags(wp_trim_words($comment['comment'], 30)) . '</p>';

                                            $resource_content .= '</div>';
                                            
                                            $resource_content .= '<span class="rating-stars">';
                                            $resource_content .= $stars;
                                            $resource_content .= '</span>';
            
                                            $resource_content .= '</div>';
                                        }
                                    }
            
                                $resource_content .= '</div>';
                            $resource_content .= '</div>';
                        $resource_content .= '</div>';
                    $resource_content .= '</div>';
                $resource_content .= '</div>';

                $resource_content .= '<div class="modal modal-secondary fade" id="modal-information" tabindex="-1" role="dialog">';
                    $resource_content .= '<div class="modal-dialog" role="document">';
                        $resource_content .= '<div class="modal-content">';
                            $resource_content .= '<div class="modal-wrap">';
                                $resource_content .= '<div class="modal-header">';
                                    $resource_content .= '<button type="button" class="close" data-dismiss="modal">×</button>';
                                    $resource_content .= '<h4 class="modal-title">' . __('Information','curriki') . ' - ' . htmlentities($resource['title']) . '</h4>';
                                $resource_content .= '</div>';
                                $resource_content .= '<div class="modal-body">';
                                    
                                    $resource_content .= '<ul class="list-horizontal">';
                                        $resource_content .= '<li>';
                                            $resource_content .= '<label>'.__('Type','curriki').':</label>';
            
                                            $typeName = '';
                                            if (isset($resource['typeName']))
                                                foreach ($resource['typeName'] as $type)
                                                    $typeName .= $type['typeName'] . ', ';
            
                                            $resource_content .= '<div class="desc">' . substr($typeName, 0, -2) . '</div>';
                                        $resource_content .= '</li>';
                                        $resource_content .= '<li>';
                                            $resource_content .= '<label>'.__('Description','curriki').':</label>';
                                            $resource_content .= '<div class="desc">' . strip_tags(html_entity_decode($resource['description'])) . '</div>';
                                        $resource_content .= '</li>';
                                        $resource_content .= '<li>';
                                            $resource_content .= '<label>'.__('Subjects','curriki').':</label>';
                                            $resource_content .= '<div class="desc">';
                                            
                                            if (isset($resource['subjects']))
                                                foreach ($resource['subjects'] as $subject)
                                                    $resource_content .= $subject . '<br>';
                                            
                                            $resource_content .= '</div>';
                                        $resource_content .= '</li>';
                                        $resource_content .= '<li>';
                                            $resource_content .= '<label>'.__('Keywords','curriki').':</label>';
                                            $resource_content .= '<div class="desc">' . $resource['keywords'] . '</div>';
                                        $resource_content .= '</li>';
                                        $resource_content .= '<li>';
                                            $resource_content .= '<label>'.__('Education Levels','curriki').':</label>';
                                            $resource_content .= '<div class="desc">';
                                                $resource_content .= '<ul class="list-level">';
            
                                                if (isset($resource['educationlevels']))
                                                    foreach ($resource['educationlevels'] as $educationlevel)
                                                        $resource_content .= '<li>' . $educationlevel . '</li>';
            
                                                $resource_content .= '</ul>';
                                            $resource_content .= '</div>';
                                        $resource_content .= '</li>';
                                        $resource_content .= '<li>';
                                            $resource_content .= '<label>'.__('Language','curriki').':</label>';
                                            $resource_content .= '<div class="desc">' . $resource['languageName'] . '</div>';
                                        $resource_content .= '</li>';
                                        $resource_content .= '<li>';
                                            $resource_content .= '<label>'.__('Access Privileges','curriki').':</label>';
                                            $resource_content .= '<div class="desc">' . $resource['access'] . '</div>';
                                        $resource_content .= '</li>';
                                        $resource_content .= '<li>';
                                            $resource_content .= '<label>'.__('License Deed','curriki').':</label>';
                                            $resource_content .= '<div class="desc">' . $resource['licenseName'] . '</div>';
                                        $resource_content .= '</li>';
                                        $resource_content .= '<li>';
                                            $resource_content .= '<label>'.__('Collections','curriki').':</label>';
                                            $resource_content .= '<div class="desc">';
            
                                            if (count($resource['collections_resource_blogngs_to']) == 0) {
                                                $resource_content .= "None";
                                            }
            
                                            if (isset($resource['collections_resource_blogngs_to'])) {
                                                foreach ($resource['collections_resource_blogngs_to'] as $collection_of_resource) {
                                                    $url_collection = site_url() . "/oer/" . $collection_of_resource->pageurl;
                                                    $resource_content .= '<a href="' . $url_collection . '">' . htmlentities($collection_of_resource->title) . '</a> ';
                                                }
                                            }
            
                                            $resource_content .= '</div>';
                                        $resource_content .= '</li>';
                                    $resource_content .= '</ul>';
            
                                $resource_content .= '</div>';
                            $resource_content .= '</div>';
                        $resource_content .= '</div>';
                    $resource_content .= '</div>';
                $resource_content .= '</div>';

            $resource_content .= '</div>';

            $resource_content .= '<div class="resource-content dashboard-tabs-content">';
                $resource_content .= '<div class="wrap container_12">';
                    $resource_content .= '<div id="content" class="tab-contents">';
                    $resource_content .= '</div>';
                    $resource_content .= '<div id="standards" class="tab-contents">';
                    $resource_content .= '</div>';
                $resource_content .= '</div>';
            $resource_content .= '</div>';

        $resource_content .= '</div>';
    }


    $resource_content .= '<div class="resource-columns">';
    $resource_content .= '<div class="grid_3 grid_mx">';

    $resource_content .= '<input type="hidden" value="'.(isset($_GET['rid']) ? $_GET['rid'] : '').'" name="rid_param" id="rid_param" />';
    $resource_content .= '<input type="hidden" value="'.(isset($_GET['pageurl']) ? $_GET['pageurl'] : '').'" name="pageurl_param" id="pageurl_param" />';

    if (!isset($_GET['viewer']) && (isset($resource["toc_persist"]) && count($resource["toc_persist"]) > 0 || (isset($resource['collection']) && count($resource['collection']) > 0))) {
        $resource_content .= '<div class="toc">';
            $resource_content .= '<div class="toc-header">'.__('TABLE OF CONTENTS','curriki').'</div>';
            $resource_content .= '<div class="toc-body">';

                $toc_persist_rids = $resource["toc_persist_rids"];

                foreach ($resource["toc_persist"] as $toc_persist) {
                    //$table_of_content = $resource["resources_table_of_content"];
                    $persist_rids = $toc_persist_rids;
                    $table_of_content = $toc_persist;
        
                    $rid = $toc_persist->main_resource_resources["resource"]->resourceid;
                    //unset($persist_rids[$rid]);
                    $persist_rids[] = $rid;
                    $persist_rids = array_unique($persist_rids);
                    $mrid = implode("-", $persist_rids);
                    if ((isset($resource['collection']) && count($resource['collection']) > 0) || $table_of_content->main_resource_resources["collections"] > 0) {
                        if ($table_of_content->main_resource_resources["collections"] > 0) {
                            $resource_content .= '<h4 class="toc-collection-folder"><span class="fa fa-plus"></span> ' . htmlentities($table_of_content->main_resource_resources["resource"]->title) . '</h4>';

                            $resource_content .= '<ul class="toc-sidenav">';
                            foreach ($table_of_content->main_resource_resources["collections"] as $collection) {
                                $url_toc = get_bloginfo('url') . '/oer/' . $collection['pageurl'] . '/?mrid=' . $mrid;
                                $url_toc .= isset($_GET['oer-only']) && $_GET['oer-only'] == 'true' ? '&oer-only=true':'';
                                $resource_content .= '<li><a href="' . $url_toc . '"><i class="fa fa-file-text"></i> ' . htmlentities($collection['title']) . '</a></li>';
                            }
                            $resource_content .= '</ul>';
                        }
                    }
                }

                if (isset($resource['collection']) && count($resource['collection']) > 0) {
                    $persist_rids = $toc_persist_rids;
                    $rid = $resource['resourceid'];
                    $persist_rids[] = $rid;
                    $persist_rids = array_unique($persist_rids);
                    $mrid = implode("-", $persist_rids);
                    $resource_content .= '<h4 class="toc-collection-folder"><span class="fa fa-plus"></span> ' . htmlentities($resource['title']) . '</h4>';

                    $resource_content .= '<ul class="toc-sidenav">';
                    foreach ($resource['collection'] as $collection) {
                        $url_toc = get_bloginfo('url') . '/oer/' . $collection['pageurl'] . "/?mrid=" . $mrid;
                        $url_toc .= isset($_GET['oer-only']) && $_GET['oer-only'] == 'true' ? '&oer-only=true':'';
                        $resource_content .= '<li><a href="' . $url_toc . '"><i class="fa fa-file-text"></i> ' . htmlentities($collection['title']) . '</a></li>';
                    }
                    $resource_content .= '</ul>';
                }
                
            $resource_content .= '</div>';
        $resource_content .= '</div>';

        if(!empty($resource['collections_resource_blogngs_to'])) {
            $resource_content .= '<div class="toc">';
                $resource_content .= '<div class="toc-header">'.__('IN COLLECTION','curriki').'</div>';
                $resource_content .= '<div class="toc-body">';
                    $resource_content .= '<ul class="toc-sidenav">';

                    foreach($resource['collections_resource_blogngs_to'] as $resourceItem){
                        $url = site_url() . "/oer/".$resourceItem->pageurl;
                        $resource_content .= '<li><a href="' . $url . '"><i class="fa fa-file-text"></i> ' . $resourceItem->title . '</a></li>';
                    }

                    $resource_content .= '</ul>';
                $resource_content .= '</div>';
            $resource_content .= '</div>';
        }
    } else {
        $res = new CurrikiResources();
        $resource = $res->getResourceById((int) $_GET['rid'], rtrim($_GET['pageurl'], '/'), true);

        if(!empty($resource['collections_resource_blogngs_to'])) {
            $resource_content .= '<div class="toc">';
                $resource_content .= '<div class="toc-header">'.__('IN COLLECTION','curriki').'</div>';
                $resource_content .= '<div class="toc-body">';
                    $resource_content .= '<ul class="toc-sidenav">';

                    foreach($resource['collections_resource_blogngs_to'] as $resourceItem){
                        $url = site_url() . "/oer/".$resourceItem->pageurl;
                        $resource_content .= '<li><a href="' . $url . '"><i class="fa fa-file-text"></i> ' . $resourceItem->title . '</a></li>';
                    }

                    $resource_content .= '</ul>';
                $resource_content .= '</div>';
            $resource_content .= '</div>';
        }
    }

    $resource_content .= '</div>';


    $resource_content .= '<div class="grid_9 grid_mx">';
    $resource_content .= '<div class="resource-content-full">';  

    $resource_desc = "";        
    $resource_desc .= isset($resource['description']) ? $resource['description'] : "";
    $content = (empty($resource['content']) ? $resource_desc : $resource['content']);

    $register_program_data = "";
    $program_data = oerPrintProgramButton($resource['resourceid'],get_current_user_id());        
    $print_progress_for_collections = false;
    $print_progress_for_lti_resource = false;    
    $progressMonitorModal = "";   
    $afterRegisterProgramModal = ""; 
    $register_program_data_class = "";
    if(get_current_user_id() > 0 && trim($resource['type']) == 'collection' ){        
        if( !is_null($program_data['register_button']) && is_null($program_data['IsUserRegisterToProgram']) ){
            //$register_program_data = $program_data['register_button'];
            //$register_program_data .= laasGetProgramEntityLoginNotEnrolStatus($resource['resourceid']);
            //$afterRegisterProgramModal = laasAfterRegisterProgramModal($resource['pageurl'], $resource['title']);
        }elseif( !is_null($program_data['register_button']) && !is_null($program_data['IsUserRegisterToProgram']) ){            
            $playlist_collections=[];
            foreach ($resource['collection'] as $playlist) {
                $playlist_collections[] = $playlist['resourceid'];
            }            
            $prgress_data = ltiGetProgressForProgram($resource['resourceid'], get_current_user_id(),$playlist_collections, $res);
            $progress_for_program = progressForProgram($prgress_data);
            $progress_in_percentage = round((intval($progress_for_program['completed'])/intval($progress_for_program['total']))*100,0,PHP_ROUND_HALF_UP);
            $register_program_data_class = "info-text-alt";
            $register_program_data .= '<h3>My Progress</h3>';
            $register_program_data .= '<div class="infobox">';
                $register_program_data .= '<div class="infobox-column">';
                    $register_program_data .= '<div class="infobox-title">Playlists Completed</div>';
                    $register_program_data .= '<div class="infobox-data">'.$progress_for_program['completed']."/".$progress_for_program['total'].'</div>';
                $register_program_data .= '</div>';
                $register_program_data .= '<div class="infobox-column">';
                    $register_program_data .= '<div class="infobox-title">Progress in %</div>';
                    $register_program_data .= '<div class="progress-box">';
                        $register_program_data .= '<div class="progress">';
                            $register_program_data .= '<div class="progress-bar width-'.$progress_in_percentage.'-percent"></div>';
                        $register_program_data .= '</div>';
                        $register_program_data .= '<div class="progress-percent">'.$progress_in_percentage.'%</div>';
                    $register_program_data .= '</div>';
                $register_program_data .= '</div>';
                $register_program_data .= '<div class="button-div">';
                    $register_program_data .= '<a class="btn btn-primary" data-toggle="modal" href="#modal-progress-2">Check Full Progress</a>';
                $register_program_data .= '</div>';
            $register_program_data .= '</div>';
            $print_progress_for_collections = true; 
            $p_collection = isset($resource['collection']) && is_array($resource['collection']) ? $resource['collection']:[];
            $progressMonitorModal = laasProgressMonitorModal($resource['pageurl'], 'program', $p_collection, get_current_user_id(), $resource['resourceid']);
        }elseif(is_null($program_data['register_button']) && !is_null(getParentExternalModuleEnabled($resource['resourceid']) ) ){
            $progress_for_playlist = ltiGetProgressForPlaylist($resource['resourceid'],$res);
            $progress_in_percentage = round((intval($progress_for_playlist['completed'])/intval($progress_for_playlist['total']))*100,0,PHP_ROUND_HALF_UP);
            $register_program_data_class = "info-text-alt";
            $register_program_data .= '<h3>My Progress</h3>';
            $register_program_data .= '<div class="infobox">';
                $register_program_data .= '<div class="infobox-column">';
                    $register_program_data .= '<div class="infobox-title">Activities Completed</div>';
                    $register_program_data .= '<div class="infobox-data">'.$progress_for_playlist['completed'] . '/' . $progress_for_playlist['total'].'</div>';
                $register_program_data .= '</div>';
                $register_program_data .= '<div class="infobox-column">';
                    $register_program_data .= '<div class="infobox-title">Progress in %</div>';
                    $register_program_data .= '<div class="progress-box">';
                        $register_program_data .= '<div class="progress">';
                            $register_program_data .= '<div class="progress-bar width-'.$progress_in_percentage.'-percent"></div>';
                        $register_program_data .= '</div>';
                        $register_program_data .= '<div class="progress-percent">'.$progress_in_percentage.'%</div>';
                    $register_program_data .= '</div>';
                $register_program_data .= '</div>';
                $register_program_data .= '<div class="button-div">';
                    $register_program_data .= '<a class="btn btn-primary" data-toggle="modal" href="#modal-progress-2">Check Full Progress</a>';
                $register_program_data .= '</div>';
            $register_program_data .= '</div>';
            $print_progress_for_lti_resource = true;                        
            $program_id = currGetParentCollection($resource['resourceid']);
            $p_collection = isset($resource['collection']) && is_array($resource['collection']) ? $resource['collection']:[];
            $progressMonitorModal = laasProgressMonitorModal($resource['pageurl'], 'playlist', $p_collection, get_current_user_id(), $program_id, $resource['resourceid']);
        }else{
            //$register_program_data .= laasGetProgramEntityLoginNotEnrolStatus($resource['resourceid']);
        }         
    }elseif(get_current_user_id() == 0){
        $register_program_data .= laasGetProgramEntityLogoutStatus($resource['resourceid']);        
    }
    
    if ($register_program_data) {
        $register_program_data = '<div class="info-text '.$register_program_data_class.'">'.$register_program_data."</div>";
        $content = $register_program_data.$content;
    }
    
    // ====== In content, correcting the path of scripts and styles ===============
    if (isset($_GET["pageurl"]) && strlen($_GET["pageurl"]) > 0) {
        $str_find = "../wp-content/themes";
        $str_replace = site_url() . "/wp-content/themes";
        $content = str_replace($str_find, $str_replace, $content);
    }
    if(get_current_user_id() > 0){                
        $current_user = wp_get_current_user();
        $resourceid = $resource['resourceid'];        
        $lti_url_param = "{\"id\":{$current_user->ID},\"name\":\"{$current_user->display_name}\",\"firstname\":\"{$current_user->user_firstname}\",\"lastname\":\"{$current_user->user_lastname}\",\"email\":\"{$current_user->user_email}\",\"username\":\"{$current_user->user_login}\", \"resourceid\":\"{$resourceid}\"}";
        $lti_url_param = urlencode($lti_url_param);
        $lti_progress = ltiGetResourceProgress($resourceid, $current_user->ID);
        $content_progress = ltiMakeProgressHTML($lti_progress);
        $content_lti = str_replace("[[lti_user_data]]", $lti_url_param, $content);
               
        if( !is_null($lti_progress['status']) || $print_progress_for_lti_resource){
            $breadcrumbs_data = [];
            currGetBreadcrumbs($resource['resourceid'], $breadcrumbs_data);
            $breadcrumbs = '<p>'. implode(' > ', array_reverse($breadcrumbs_data)) .'</p>';
            $content_progress = $breadcrumbs.$content_progress;
            $content = $content_progress.$content_lti;
            if($resource['type'] == 'resource'){
                $content .= laasActivityNaviation($resourceid);
            }
        }elseif( !($print_progress_for_collections == true || $print_progress_for_lti_resource == true) ){
            global $laas_program_name;
            global $laas_program_slug;
            $status_not_enroll = laasGetEnrollStatus($resourceid, get_current_user_id());;
            $content = $status_not_enroll.$content_lti;            
            $afterRegisterProgramModal = laasAfterRegisterProgramModal($laas_program_slug,$laas_program_name);
        }        
    }    

    $resource_content .= $content;

    $resource_content .= '</div>';

    if (isset($resource['type']) && $resource['type'] == 'collection' && isset($resource['collection'])) {
        $resource_content .= '<h2 class="section-title section-title-v2">Collection Contents</h2>';
        $resource_content .= '<div class="resource-content-holder">';
        
        $rid = $resource['resourceid'];
        $persist_rids[] = $rid;
        $persist_rids = array_unique($persist_rids);
        $mrid = implode("-", $persist_rids);
        
        foreach ($resource['collection'] AS $collection) {
            $url = get_bloginfo('url') . '/oer/' . $collection['pageurl'];
            $url .= "/?mrid=" . $mrid;
            $url .= isset($_GET['oer-only']) && $_GET['oer-only'] == 'true' ? '&oer-only=true':'';

            $content = htmlentities((!empty($collection['description'])) ? $collection['description'] : $collection['content']);
            global $wpdb;
            $colObj = $wpdb->get_row("select * from resources where resourceid=".$collection['resourceid']);            
            $progress_content = "";
            if($print_progress_for_collections){
                $progress_for_playlist = ltiGetProgressForPlaylist($collection['resourceid'],$res);
                $progress_content = '<span class="graded-progress-lable">MY PROGRESS: '. $progress_for_playlist['completed'] . '/' . $progress_for_playlist['total'] . '  Activities Completed</span>';                
            }elseif($print_progress_for_lti_resource && is_object($colObj) && trim($colObj->type) === 'resource'){
                $progress_lti_data = ltiGetResourceProgress($collection['resourceid'], get_current_user_id());
                if($progress_lti_data['status'] == 'take-lesson'){
                    $lti_resource_progress = "In Progress";
                    $progress_content = '<span class="graded-progress-lable">'. $lti_resource_progress . '</span>';                    
                }elseif($progress_lti_data['status'] === "completed"){
                    $lti_resource_progress = 'MY SCORE: '.$progress_lti_data['data']['gradepercent'].'%';
                    $progress_content = '<span class="graded-progress-lable">'. $lti_resource_progress . '</span>';                    
                }elseif($progress_lti_data['status'] === null){
                    $lti_resource_progress = "Not Started";
                    $progress_content = '<span class="graded-progress-lable">'. $lti_resource_progress . '</span>';                    
                }
                
            }

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

            $resource_content .= '<div class="resource-content-content">';
                $resource_content .= '<div class="collection-body-title">';
                    $resource_content .= '<div class="collection-title">';
                        $resource_content .= '<h3><a href="' . $url . '">' . htmlentities($collection['title']) . '</a></h3>';
                        $resource_content .= 'by <span class="member-name name">' . $collection['contributorid_Name'] . '</span>';
                    $resource_content .= '</div>';
                $resource_content .= '</div>';
                $resource_content .= '<div class="collection-body-content">';
                    $resource_content .= '<div class="collection-description">';
                        $resource_content .= $progress_content.'<br />' . strip_tags(html_entity_decode($content));
                    $resource_content .= '</div>';
                    $resource_content .= '<div class="collection-rating-meta">';
                        $resource_content .= '<div class="collection-rating rating">';
                            $resource_content .= '<span class="member-rating-title">'.__('Member Rating','curriki').'</span>';
                            $resource_content .= '<span class="rating-stars">';
                                $resource_content .= $m_stars;
                            $resource_content .= '</span>';
                            
                            if (get_current_user_id() > 0) {
                                $resource_content .= '<a class="rate-link" href="' . $url . '">'.__('Rate this collection','curriki').'</a>';
                            }

                        $resource_content .= '</div>';
                        $resource_content .= '<div class="collection-curriki-rating curriki-rating">';
                            $resource_content .= '<span class="rating-title">'.__('Curriki Rating','curriki').'</span>';

                            $collection['reviewrating'] = isset($collection['reviewrating']) ? round((float) $collection['reviewrating'], 1) : null;

                            $qtip_text = "";
                            if ($collection['reviewstatus'] == 'reviewed' && $collection['reviewrating'] != null && $collection['reviewrating'] >= 0) {
                                $qtip_text = '<div class="hidden-qtip">'.__('On a scale of 0 to 3','curriki').'</div>';
                                $resource_content .= '<span class="rating-points curriki-rating-title-text tooltip-rating">' . $collection['reviewrating'] . '</span>' . $qtip_text;
                            } elseif ($collection['reviewstatus'] == 'reviewed' && $collection['reviewrating'] != null && $collection['reviewrating'] < 0) {
                                $qtip_text = '<div class="hidden-qtip">'.__('Commented','curriki').'</div>';
                                $resource_content .= '<span class="rating-points curriki-rating-title-text tooltip-rating">-</span>' . $qtip_text;
                            } elseif ($collection['partner'] == 'T') {
                                $qtip_text = '<div class="hidden-qtip"><strong>\'P\'</strong> - This is a trusted Partner resource</div>';
                                $resource_content .= '<span class="rating-points curriki-rating-title-text tooltip-rating">P</span>' . $qtip_text;
                            } elseif ($collection['partner'] == 'C') {
                                $qtip_text = '<div class="hidden-qtip"><strong>\'C\'</strong> - Curriki rating</div>';
                                $resource_content .= '<span class="rating-points curriki-rating-title-text tooltip-rating">C</span>' . $qtip_text;
                            } else {
                                $qtip_text = '<div class="hidden-qtip"><strong>\'NR\'</strong> - '.__('This resource has not been rated','curriki').'</div>';
                                $resource_content .= '<span class="rating-points curriki-rating-title-text tooltip-rating">NR</span>' . $qtip_text;
                            }

                        $resource_content .= '</div>';
                    $resource_content .= '</div>';
                $resource_content .= '</div>';
            $resource_content .= '</div>';
        }
        $resource_content .= '</div>';        
    }

    $resource_content .= '</div>';

    $resource_content .= '</div>';

    $resource_content .= '</div>';

  
    $resource_content .= $afterRegisterProgramModal;
    $resource_content .= $progressMonitorModal;


    $pageurl_val = isset($resource['pageurl']) ? $resource['pageurl'] : "";
    $rs_url = get_bloginfo('url') . '/oer/' . $pageurl_val;

    $resource_content .= '
    <div style="display:none;">
      <div id="gc-widget-div">
        <div class="g-sharetoclassroom" data-url="' . $rs_url . '" data-title="' . ( isset($resource['title']) ? htmlentities($resource['title']) : "" ) . '" ></div>
      </div>
    </div>
    <script>gapi.sharetoclassroom.go("gc-widget-div");</script>
  ';

    if (isset($_GET['viewer']) && trim($_GET['viewer']) == 'embed')
        $resource_content .= '<p id="content-licensed" style="width:100%;text-align:center"><a href="https://www.curriki.org/copyright-open-licenses/" target="_blank">Copyright &amp; Open Licenses</a></p>';

    echo $resource_content;

    echo '<input type="hidden" id="rid_current" name="rid_current" value="' . $resource['resourceid'] . '" />';

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
        <meta itemprop="name" value="' . (isset($resource['title']) ? htmlentities($resource['title']) : "") . '"/>
        <meta itemprop="description" value="' . (isset($resource['description']) ? strip_tags(addslashes($resource['description'])) : "") . '"/>
        <meta itemprop="mediaType" value="' . (isset($resource['mediatype']) ? $resource['mediatype'] : "") . '"/>
        <meta itemprop="about" value="' . str_replace(' > ', ' ', implode(', ', (isset($resource['subjects']) && is_array($resource['subjects']) ? $resource['subjects'] : array()))) . '"/>
        <meta itemprop="dateCreated" value="' . (isset($resource['createdate']) ? $resource['createdate'] : "") . '"/>
        <meta itemprop="publisher" value="‘Curriki’"/>
        <meta itemprop="inlanguage" value="language"/>
        <meta itemprop="license" value="' . (isset($resource['licenseName']) ? $resource['licenseName'] : "") . '"/>
        <meta itemprop="useRightsURL" value="www.creativecommons.org/licenses"/>
      </div>';
    }


    if (get_current_user_id() > 0) {

        if (isset($resource['currentUser']['uniqueavatarfile']) && strlen($resource['currentUser']['uniqueavatarfile']) > 0)
            $display_image = 'https://' . DBI_AWS_S3_CDN_URL . '//avatars/' . $resource['currentUser']['uniqueavatarfile'];
        else
            $display_image = get_stylesheet_directory_uri() . '/images/user-icon-sample.png';

        $display_name = (isset($resource['currentUser']['display_name'])) ? $resource['currentUser']['display_name'] : 'Member Name';

        echo '<div class="modal modal-secondary fade" id="modal-member-review" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-wrap">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">×</button>
                                <h4 class="modal-title">' . __('Member Review','curriki') . '</h4>
                            </div>
                            <div class="modal-body">
                                <div class="media media-review">
                                    <img class="media-object" src="' . $display_image . '" width="34" height="34" alt="user">
                                    <div class="media-body">
                                        <form action="" method="post">
                                            <div class="member-rating">
                                                <span class="author">' . $display_name . '</span>
                                                <span class="rating-stars">
                                                    <span class="fa fa-star-o" id="resource-rating2-1" onclick="resourceRating2(1);"></span>
                                                    <span class="fa fa-star-o" id="resource-rating2-2" onclick="resourceRating2(2);"></span>
                                                    <span class="fa fa-star-o" id="resource-rating2-3" onclick="resourceRating2(3);"></span>
                                                    <span class="fa fa-star-o" id="resource-rating2-4" onclick="resourceRating2(4);"></span>
                                                    <span class="fa fa-star-o" id="resource-rating2-5" onclick="resourceRating2(5);"></span>
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <textarea name="resource-comments" class="form-control" rows="6" cols="10"></textarea>
                                            </div>
                                            <div class="buttonpane pt-10">
                                                <input type="hidden" name="resourceid" value="' . (isset($resource['resourceid']) ? $resource['resourceid'] : "") . '" />
                                                <input type="hidden" id="resource-rating2" name="resource-rating" />
                                                <button class="btn btn-blue" type="submit">'.__('Submit Review','curriki').'</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
    }
}

add_action('genesis_after', 'curriki_addthis_scripts');

if ($owner_of_this_post)
    add_action('genesis_after', 'curriki_organize_collections_scripts');

function curriki_organize_collections_scripts()
{
?>

    <style type="text/css">
        .library-rating {
            width: 146px !important;
        }
    </style>

    <script type="text/javascript">
        function hideshowcenter($id1, $id2) {
            jQuery($id1).hide();
            jQuery($id2).show();

            if (jQuery.fn.center_func == undefined) {
                jq($id2).center_func();
            } else {
                jQuery($id2).center_func();
            }

            //setInterval(function () {jQuery( $id2 ).center()}, 1);
        }
        jQuery.fn.center_func = function() {
            var h = jQuery(this).height();
            var w = jQuery(this).width();
            var wh = jQuery(window).height();
            var ww = jQuery(window).width();
            var wst = 0; //jQuery(window).scrollTop();
            var wsl = 0; //jQuery(window).scrollLeft();
            this.css("position", "absolute");
            var $top = Math.round((wh - h) / 2 + wst);
            var $left = Math.round((ww - w) / 2 + wsl);


            this.css("top", $top + "px");
            this.css("left", $left + "px");
            this.css("z-index", "1000");
            return this;
        };

        function curriki_RemoveThisCollectionElement($id) {
            jQuery($id).remove();
            curriki_ArrangeCollectionElements();
        }

        function curriki_ArrangeCollectionElements() {
            var data = "",
                $collectionid = jQuery("input[name='collectionid']").val();
            jQuery("#sortable-" + $collectionid + " div.library-collection").each(function(i, el) {
                if (data != '') data += ',';
                data += jQuery(el).attr('id') + '=' + i;
            });
            jQuery("#seq-" + $collectionid).val(data);
        }
        jQuery(document).ready(function() {
            jQuery(".organize-collections-title").click(function() {
                jQuery('.organize-collection-resources').html('Please wait!');
                // hideshowcenter('#organize-collections-dialog', '#organize-collections-dialog');
                jQuery('#modal-collections').modal('show');
                $id = jQuery(this).attr('id');
                $id = $id.substring(27);

                jQuery.ajax({
                        method: "POST",
                        url: "<?php echo get_bloginfo('url'); ?>/organize-collections-step-2/?" + new Date().getTime(),
                        data: {
                            collectionid: $id
                        }
                    })
                    .done(function(msg) {
                        jQuery('.organize-collection-resources').html(msg);
                    });
            });
        });
    </script>




    <div class="modal modal-secondary fade" id="modal-collections" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-wrap">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">×</button>
						<h4 class="modal-title">Organize Collections</h4>
					</div>
					<div class="modal-body organize-collection-resources">

					</div>
				</div>
			</div>
		</div>
	</div>




<?php
}

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
