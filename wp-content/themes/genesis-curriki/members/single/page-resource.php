<?php
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
add_filter('body_class', 'curriki_resource_page_add_body_class');

function curriki_resource_page_add_body_class($classes) {
  $classes[] = 'backend resource-page';
  return $classes;
}

global $bp;
if ($bp->unfiltered_uri[0] == 'oer' && get_current_user_id() == 0) {
  /* echo session_id();
    echo "<br />";

    if(isset($_SESSION["visit_ip"]) && isset($_SESSION["visit_count"]) && $_SESSION["visit_ip"] == get_client_ip_address())
    {
    $_SESSION["visit_count"] = $_SESSION["visit_count"] + 1;
    }else{
    $_SESSION["visit_ip"] = get_client_ip_address();
    $_SESSION["visit_count"] = 1;
    }

    var_dump( $_SESSION ); */

  //===== [start] Manage User view  =============


  if (isset($_COOKIE["visit_counter"])) {
    //setcookie('visit_ip', null, -1, SITECOOKIEPATH);
    //setcookie('visit_counter', null, -1, SITECOOKIEPATH);
    //die;        
    $ck_val = $_COOKIE["visit_counter"];
    $visit_counter_new = $ck_val + 1;
    $secure = ( 'https' === parse_url(site_url(), PHP_URL_SCHEME) );
    setcookie('visit_ip', get_client_ip_address(), time() + YEAR_IN_SECONDS, SITECOOKIEPATH, null, $secure);
    setcookie('visit_counter', $visit_counter_new, time() + YEAR_IN_SECONDS, SITECOOKIEPATH, null, $secure);
    //var_dump($visit_counter_new);
    if ($visit_counter_new > 2) {
      //=== redirct to login ===                
      $redirect_url = bp_get_root_domain() . "/?a=login";
      wp_redirect($redirect_url);
      wp_safe_redirect($redirect_url);
      exit();
    }
  } else {

    //setcookie('visit_ip', null, -1, SITECOOKIEPATH);
    //setcookie('visit_counter', null, -1, SITECOOKIEPATH);
    //echo "in else";        
    $secure = ( 'https' === parse_url(site_url(), PHP_URL_SCHEME) );
    setcookie('visit_ip', get_client_ip_address(), time() + YEAR_IN_SECONDS, SITECOOKIEPATH, null, $secure);
    setcookie('visit_counter', 1, time() + YEAR_IN_SECONDS, SITECOOKIEPATH, null, $secure);
    //var_dump($_COOKIE);        
  }


  //setcookie('visit_ip', null, -1, SITECOOKIEPATH);
  //setcookie('visit_counter', null, -1, SITECOOKIEPATH);
}

function get_client_ip_address() {
  $ipaddress = '';
  if ($_SERVER['HTTP_CLIENT_IP'])
    $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
  else if ($_SERVER['HTTP_X_FORWARDED_FOR'])
    $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
  else if ($_SERVER['HTTP_X_FORWARDED'])
    $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
  else if ($_SERVER['HTTP_FORWARDED_FOR'])
    $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
  else if ($_SERVER['HTTP_FORWARDED'])
    $ipaddress = $_SERVER['HTTP_FORWARDED'];
  else if ($_SERVER['REMOTE_ADDR'])
    $ipaddress = $_SERVER['REMOTE_ADDR'];
  else
    $ipaddress = 'UNKNOWN';

  return $ipaddress;
}

//===== [end] Manage User view  =============


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
    $resource_content = trim(strip_tags($resourceUser['description']));

    echo '<meta property="og:title" content="' . $resourceUser['title'] . '"/>
        <meta property="og:url" content="' . get_bloginfo('url') . '/oer/' . $resourceUser['pageurl'] . '" />
        <meta property="og:image" content="' . $imageUrl . '"/>
        <meta property="og:site_name" content="Curriki"/>
        <meta property="og:description" content="' . strip_tags($resource_content) . '" />';
  }
}

// Execute custom style guide page
add_action('genesis_meta', 'curriki_custom_resource_page_loop');
add_action('genesis_meta', 'curriki_AddShareMeta');

function curriki_custom_resource_page_loop() {
  //* Force full-width-content layout setting
  add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');

  remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');
  remove_action('genesis_loop', 'genesis_do_loop');

  remove_action('wp_head', 'genesis_canonical');
  remove_action('wp_head', 'rel_canonical');

  add_action('genesis_before', 'curriki_resource_page_scripts');
  add_action('genesis_after_header', 'curriki_resource_header', 10);
  add_action('genesis_after_header', 'curriki_resource_page_body', 15);
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
  
  ?>
 <style type="text/css">      
    .directory .item-list-tabs ul li, .internal-page #content ul li
    {
        display: block;
    }
    .bp-template-notice{
        width: 95% !important;
    }
    .qtipCustomClass{
        border-color: #0E9236 !important;
        background-color: #99c736 !important;
    }
    .qtipCustomClass .qtip-content{
        font-size: 12px !important;
        color: #FFF !important;
    }
    .tooltip:hover
    {
        cursor: help !important;
    }    
  </style>
    <script>
      window.___gcfg = {
        parsetags: 'explicit'
      };
      
    </script>
    <script src="https://apis.google.com/js/platform.js">
    </script>

    
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


       jQuery(document).ready(function(){
            
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
                    render: function(event, api) {
                        // Grab the tip element
                        var elem = api.elements.tip;                        
                        /*var rs_url = "<?php echo urlencode(get_bloginfo('url') . '/oer/' . $resourceUser['pageurl'] . '">' . get_bloginfo('url') . '/oer/' . $resourceUser['pageurl']); ?>";                        
                        var params = {"data-url":encodeURI(rs_url) , "data-title": jQuery(".resource-title").text()};
                        console.log("params = " , params);
                        gapi.sharetoclassroom.render("gc-widget-div" , params);*/
                        jQuery("#gc-widget-div-hld").html( jQuery("#gc-widget-div").clone() );
                    }
                }
            });

            
            jQuery("#google-classroom-btn").click(function(){
            
                jq("#google-class-room-dialog").show();
                jq("#google-class-room-dialog").centergc(); 
            });

            jQuery("#google-classroom-btn").mouseenter(function(){
                var gc_img = "<?php echo site_url()."/wp-content/themes/genesis-curriki/images/GoogleClassroomIcon_goldhover.png";?>"            
                jQuery(this).find("img").attr("src",gc_img);                
            });
            jQuery("#google-classroom-btn").mouseleave(function(){
                var gc_img = "<?php echo site_url()."/wp-content/themes/genesis-curriki/images/GoogleClassroomIcon_gray.png";?>"
                jQuery(this).find("img").attr("src",gc_img);                
            });
       });
       
        
        


      jQuery(".close-add-to-lib-alert").on("click", function () {
        jQuery("#add-to-lib-alert").hide();
        jQuery("#resourceReviewed").hide();
      });

      jQuery.fn.centerx = function () {
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
        console.log("=====>  ");
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
                  jQuery("#add-to-lib-alert #msg_title").html("Resource Flagged as inappropriate!");
                  jQuery("#add-to-lib-alert #msg_para").html("");
                  jQuery("#add-to-lib-alert").show();
                  jQuery("#add-to-lib-alert").centerx();
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
                  jQuery("#add-to-lib-alert #msg_title").html("Resource Nominated For Review!");
                  jQuery("#add-to-lib-alert #msg_para").html("");
                  jQuery("#add-to-lib-alert").show();
                  jQuery("#add-to-lib-alert").centerx();
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




      var tb = "<?php $_GET['tb'] == "standard" ? "standard" : "" ?>";

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
  
    $current_language = "eng";
    $current_language_slug = "";
    if( defined('ICL_LANGUAGE_CODE') )
    {
        $current_language = cur_get_current_language(ICL_LANGUAGE_CODE); 
        $current_language_slug = ( ICL_LANGUAGE_CODE == "en" ? "":"/".ICL_LANGUAGE_CODE );
    }

  if (function_exists('check_old_resource_rating'))
    check_old_resource_rating();

  if (isset($_GET['rid']) || isset($_GET['pageurl'])) {
    $res = new CurrikiResources();
    $resourceUser = $res->getResourceUserById((int) $_GET['rid'], rtrim($_GET['pageurl'], '/'));
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

  $resource_header = '<div class="resource-header page-header">';
  $resource_header .= '<div class="wrap container_12">';
  if (is_user_logged_in()) {
    $resource_header .= '<div class="resource-join page-join grid_2">';
    //$resource_header .= '<img class="aligncenter" src="http://placehold.it/176x146" alt="resource-name" />';
    $resource_header .= '<button id="addtolibrary" class="green-button" onclick="addToMyLibrary(' . $resourceUser['resourceid'] . ');"><span class="fa fa-plus-circle"></span> Add to My Library</button>';

    if ($_GET['wtest'] == 1) {
      echo "<br /><br /><br /><br /><br /><br /><br /><br /><br /><br />";
      echo "<pre>";

      echo $sql_btn;

      var_dump($wpdb->get_results($sql_btn));
      die;
    }

    if ($resourceUser['type'] == "collection" && count($wpdb->get_results($sql_btn)) > 0) {
      // $resource_header .= '<button id="addfolder" class="green-button" onclick="addfolder(' . $resourceUser['resourceid'] . ');"><span class="fa fa-plus-circle"></span> Add Folder</button>';
      // $resource_header .= '<button id="addresource" class="green-button" onclick="addresource(' . $resourceUser['resourceid'] . ');"><span class="fa fa-plus-circle"></span> Add Resource</button>';
    }

    $resource_header .= '</div>';
  }
  
  $resource_header .= '<div class="resource-info page-info grid_10">';
  $resource_header .= '<h3 class="resource-title page-title">' . $resourceUser['title'] . '</h3>';
    $website_address = site_url().$current_language_slug. '/oer/' . $resourceUser['pageurl'];
  $resource_header .= '<div class="resource-link page-link"><strong>Website Address:</strong> <a class="resource-url-link" href="' . $website_address  . '">' . $website_address . '</a></div>';
  $resource_header .= '<div class="resource-tools clearfix">';
  $resource_header .= '<div class="resource-share page-share share-icons" style="width:310px;">';
  $resource_header .= '<a target="_blank" href="?rid=' . $_GET['rid'] . '&screenaction=print" class="share-print"><span class="fa fa-print"></span></a>';

  if (trim($resourceUser['uniquename']) != '')
    $resource_header .= '<a href="#" class="share-download" onclick="resourceFileDownload(' . $resourceUser['fileid'] . ', \'https://' . DBI_AWS_S3_CDN_URL . '//resourcefiles/' . $resourceUser['uniquename'] . '\')"><span class="fa fa-download"></span></a>';

  $resource_url = urlencode(get_bloginfo('url') . '/oer/?rid=');
  $facebook = 'https://www.facebook.com/sharer/sharer.php?u=' . get_bloginfo('url') . '/oer/' . $resourceUser['pageurl'] . '%23.VVSq-YDZN0Y.facebook';
  $twitter = 'https://twitter.com/intent/tweet?text=Check out this great resource I found on Curriki! ' . $resourceUser['title'] . '-OER via @Curriki' . '&url=' . get_bloginfo('url') . '/oer/' . $resourceUser['pageurl'] . '%23.VVSralG2Opo.twitter&related=';

  $resource_header .= '<span class="separator">|</span>';
  $resource_header .= '<a href="' . $facebook . '" class="share-facebook" target="_blank"><span class="fa fa-facebook"></span></a>';
  $resource_header .= '<a href="' . $twitter . '" class="share-twitter" target="_blank"><span class="fa fa-twitter"></span></a>';
  //$resource_header .= '<a href="#" class="share-pinterest"><span class="fa fa-pinterest"></span></a>';
  $resource_header .= '<a onclick="return addthis_sendto(\'pinterest_share\');" 
           onblur="if(_ate.maf.key==9){_ate.maf.key=null;}else{_ate.maf.key=null;addthis_close();}" 
           onkeydown="if(!e){var e = window.event||event;}if(e.keyCode){_ate.maf.key=e.keyCode;}else{if(e.which){_ate.maf.key=e.which;}}" 
               onkeypress="if(!e){var e = window.event||event;}if(e.keyCode){_ate.maf.key=e.keyCode;}else{if(e.which){_ate.maf.key=e.which;}}" 
                   href="' . $resource_url . $resourceUser['resourceid'] . '" id="atic_pinterest_share" class="share-pinterest"><span class="fa fa-pinterest"></span></a>';

  $resource_header .= '<a href="mailto:?subject=See this article&amp;body=' . $resource_url . $resourceUser['resourceid'] . '" class="share-email"><span class="fa fa-envelope-o"></span></a>';
  $google_classroom_img = site_url()."/wp-content/themes/genesis-curriki/images/GoogleClassroomIcon_gray.png";      
  $resource_header .= '<a href="#" id="google-classroom-btn" class="google-classroom-btn-cls"> <img src="'.$google_classroom_img.'" alt="" /> </a>';

  if(($owner_of_this_post || in_array("resourceEditor", $current_user->roles) ) && !isset($current_user->caps['administrator'])) {
    // $resource_header .= '<span class="separator">|</span>';
    // $resource_header .= '<a href="' . get_bloginfo('url') . "/create-resource/?resourceid=" . $resourceUser['resourceid'] . '" class="edit-resource" target="_blank"><span class="fa fa-pencil"></span></a>';
  }
  if ($current_user->caps['administrator']) {
    // $resource_header .= '<span class="separator">|</span>';
    // $resource_header .= '<a href="' . get_bloginfo('url') . "/create-resource/?resourceid=" . $resourceUser['resourceid'] . '" class="edit-resource" target="_blank"><span class="fa fa-pencil"></span></a>';
  }

  $resource_header .= '</div>';
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

  $resource_header .= '<div class="author"><span class="member-name name">' . '<a href="javascript:void(0);">' . $resourceUser['display_name'] . '</a>' . '</span><span class="occupation">' . $resourceUser['organization'] . '</span><span class="location">' . ucfirst(strtolower(rtrim($location, ','))) . '</span></div>';
  $resource_header .= '</div>';

  $cnt_date = isset($resourceUser['contributiondate']) ? date("F j, Y", strtotime($resourceUser['contributiondate'])) : "";
  $resource_header .= '<div class="resource-header-date page-date vertical-align">' . $cnt_date . '</div>';
  $resource_header .= '<div class="resource-header-rating rating vertical-align"><span class="member-rating-title">Member Rating</span>';
  $resource_header .= $stars;
  
  if( is_user_logged_in() )
  {
    $resource_header .= '<a href="#" class="link_cls" onclick="jQuery(\'#resource-member-review\').show(); setInterval(function(){jQuery( \'#resource-member-review\' ).center()},1);">Rate this resource</a>';
  }
  
  $resource_header .= '</div>';

  $reviewrating_val = round( (float)$resourceUser['reviewrating'] , 1 );
  if ( isset($reviewrating_val) && $reviewrating_val >= 0 ) {
      $resource_header .= '<div class="resource-header-curriki-rating curriki-rating vertical-align"><span class="curriki-rating-title">Curriki Rating</span><span class="rating-badge">' . $reviewrating_val . '</span></div>';
  } elseif (intval($reviewrating_val) == -1) {
    $resource_header .= '<div class="resource-header-curriki-rating curriki-rating vertical-align"><span class="curriki-rating-title">Curriki Rating</span><span class="rating-badge">-</span></div>';
  } elseif (!isset($resourceUser['reviewrating']) && $resourceUser['partner'] == 'T') {
    $resource_header .= '<div class="resource-header-curriki-rating curriki-rating vertical-align"><span class="curriki-rating-title">Curriki Rating</span><span class="rating-badge">P</span></div>';
  } else {
    $resource_header .= '<div class="resource-header-curriki-rating curriki-rating vertical-align"><span class="curriki-rating-title">Curriki Rating</span><span class="rating-badge">NR</span></div>';
  }

  $resource_header .= '</div>';
  $resource_header .= '</div>';

  $resource_header .= '<div class="flag_wrapper">';
  $resource_header .= '<div class="flag" id="resourceInappropriate"><a href="#" onclick="resourceInappropriate(' . $resourceUser['resourceid'] . ')">Flag as inappropriate</a></div>';
  if ($resourceUser['reviewstatus'] != 'submitted')
    $resource_header .= '<div class="flag" id="resourceReviewed"><a href="#" onclick="resourceReviewed(' . $resourceUser['resourceid'] . ')">Nominate for Review</a></div>';
  $resource_header .= '</div>';

  if (isset($_GET['back_url']))
    $resource_header .= '<div class="flag" id="returnToGroup"><a href="' . base64_decode($_GET['back_url']) . '">Return to Group</a></div>';
  $resource_header .= '</div>';
  $resource_header .= '</div>';
  $resource_header .= '</div>';

  $resource_header .= '<div id="add-to-lib-alert" class="my-library-modal modal border-grey rounded-borders-full grid_6" style="display: none;">';

  $resource_header .= '<h3 class="modal-title" id="msg_title">Resource Added!</h3>
    <div class="grid_8 center">
        <div style="margin: 0 auto;">
            <p id="msg_para">
                The resource has been added to your collection
            </p>                
        </div>
        <div class="my-library-actions" style="margin: 0 auto;">                                    
            <button class="button-cancel close-add-to-lib-alert">Close</button>
        </div>
    </div>
<div class="close close-add-to-lib-alert"><span class="fa fa-close"></span></div>';

  $resource_header .= '</div>';

  echo $resource_header;
}

function curriki_resource_page_body() {
  $resource = array();
  if (isset($_GET['rid']) || isset($_GET['pageurl'])) {
    $res = new CurrikiResources();
    $resource = $res->getResourceById((int) $_GET['rid'], rtrim($_GET['pageurl'], '/'), true);
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
    
    if (isset($resource['subjectmattercomment']) && strlen( $resource['subjectmattercomment'] ) > 0) {
      $reviewerComments .= '<br />Subject Matter: ' . $resource['subjectmattercomment'];
      $newRatings = true;
    }
    
    
    if ((int) $resource['supportsteaching'] && (int) $resource['supportsteaching'] >= 0) {
      //$componentRatings .= '<br />' . $resource['supportsteachingcomment'] . ': ' . $resource['supportsteaching'];
      $componentRatings .= '<br />Support Steaching: ' . $resource['supportsteaching'];      
      $newRatings = true;
    }
    
    if (isset($resource['supportsteachingcomment']) && strlen( $resource['supportsteachingcomment'] ) > 0) {
      $reviewerComments .= '<br />Support Steaching: ' . $resource['supportsteachingcomment'];
      $newRatings = true;
    }
    
    
    if ((int) $resource['assessmentsquality'] && (int) $resource['assessmentsquality'] >= 0) {
      //$componentRatings .= '<br />' . $resource['assessmentsqualitycomment'] . ': ' . $resource['assessmentsquality'];
      $componentRatings .= '<br />Assessments Quality: ' . $resource['assessmentsquality'];      
      $newRatings = true;
    }
    
    if (isset($resource['assessmentsqualitycomment']) && strlen( $resource['assessmentsqualitycomment'] ) > 0) {      
      $reviewerComments .= '<br />Assessments Quality: ' . $resource['assessmentsqualitycomment'];
      $newRatings = true;
    }
    
    
    
    
    if ((int) $resource['interactivityquality'] && (int) $resource['interactivityquality'] >= 0) {
      //$componentRatings .= '<br />' . $resource['interactivityqualitycomment'] . ': ' . $resource['interactivityquality'];
      $componentRatings .= '<br />Interactivity Quality: ' . $resource['interactivityquality'];      
      $newRatings = true;
    }
    
    if (isset($resource['interactivityqualitycomment']) && strlen( $resource['interactivityqualitycomment'] ) > 0) {      
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

  if (empty($resource['resourceid'])) {
    header(sprintf('Location: %s/resources-curricula', site_url()));
  }

  echo '<div id="resource-tabs">';

  $resource_tabs = '';
  $resource_tabs .= '<div class="resource-tabs page-tabs"><div class="wrap container_12">';
  $resource_tabs .= '<ul>';
  $resource_tabs .= '<li><a href="#content"><span class="tab-icon fa fa-file-text-o"></span> <span class="tab-text">Content</span></a></li>';
  $resource_tabs .= '<li><a href="#information"><span class="tab-icon fa fa-info-circle"></span> <span class="tab-text">Information</span></a></li>';
  $resource_tabs .= '<li><a href="#standards"><span class="tab-icon fa fa-graduation-cap"></span> <span class="tab-text">Standards</span></a></li>';
  $resource_tabs .= '<li><a href="#reviews"><span class="tab-icon fa fa-star"></span> <span class="tab-text">Reviews</span></a></li>';
  $resource_tabs .= '</ul>';
  $resource_tabs .= '</div></div>';

  echo $resource_tabs;


  echo '<div class="resource-content dashboard-tabs-content"><div class="wrap container_12">';

  // Content
  $content_tab = '';
  $content_tab .= '<div id="content" class="tab-contents">';
  $content_tab .= '</div>';

  echo $content_tab;


  // Information
  $information_tab = '';
  $information_tab .= '<div id="information" class="tab-contents">';
  $information_tab .= '<div class="grid_9">';
  $information_tab .= '<div class="information-section">';
  $information_tab .= '<h4 class="resource-subheadline">Type:</h4>';
  $typeName = '';
  if (isset($resource['typeName']))
    foreach ($resource['typeName'] as $type)
      $typeName .= $type['typeName'] . ', ';
  $information_tab .= substr($typeName, 0, -2);
  $information_tab .= '</div>';
  $information_tab .= '<div class="information-section">';
  $information_tab .= '<h4 class="resource-subheadline">Description:</h4>';
  $information_tab .= '<p>' . strip_tags(html_entity_decode($resource['description'])) . '</p>';
  $information_tab .= '</div>';
  $information_tab .= '<div class="information-section">';
  $information_tab .= '<div class="grid_6">';
  $information_tab .= '<h4 class="resource-subheadline">Subjects:</h4>';
  $information_tab .= '<ul>';
  if (isset($resource['subjects']))
    foreach ($resource['subjects'] as $subject)
      $information_tab .= '<li>' . $subject . '</li>';
  $information_tab .= '</ul>';
  $information_tab .= '</div>';
  $information_tab .= '<div class="grid_6">';
  $information_tab .= '<h4 class="resource-subheadline">Education Levels:</h4>';
  $information_tab .= '<ul>';
  if (isset($resource['educationlevels']))
    foreach ($resource['educationlevels'] as $educationlevel)
      $information_tab .= '<li>' . $educationlevel . '</li>';
  $information_tab .= '</ul>';
  $information_tab .= '</div>';
  $information_tab .= '</div>';
  $information_tab .= '<div class="information-section">';
  $information_tab .= '<div class="grid_6">';
  $information_tab .= '<h4 class="resource-subheadline">Keywords:</h4>';
  $information_tab .= $resource['keywords'];
  $information_tab .= '</div>';
  $information_tab .= '<div class="grid_6">';
  $information_tab .= '<h4 class="resource-subheadline">Language:</h4>';
  $information_tab .= $resource['languageName'];
  $information_tab .= '</div>';
  $information_tab .= '</div>';
  $information_tab .= '</div>';
  $information_tab .= '<div class="grid_2 push_1">';
  $information_tab .= '<div class="information-section">';
  $information_tab .= '<h4 class="resource-subheadline">Access Privileges:</h4>';
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
  $information_tab .= '<h4 class="resource-subheadline">License Deed:</h4>';
  $information_tab .= $resource['licenseName'];
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
    $standards_tab .= 'Update Standards? <a href="' . get_bloginfo('url') . '/alignment/?rid=' . $resource['resourceid'] . '" class="fancybox fancybox.iframe"><button class="modal-button green-button">Align Now</button></a>';
    $standards_tab .= '</div>';
  } else {
    $standards_tab .= '<div class="alignment-standard-section information-section">';
    $standards_tab .= 'This resource has not yet been aligned. <a href="' . get_bloginfo('url') . '/alignment/?rid=' . $resource['resourceid'] . '" class="fancybox fancybox.iframe"><button class="modal-button white-button">Align Now</button></a>';
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
  
  $reviewrating_val = round( (float) $resource['reviewrating'] , 1);
  
  if( isset($reviewrating_val) && $reviewrating_val >= 0)
  {
    
    $reviews_tab .= '<div class="review-aggregate curriki-rating">Curriki Review <span class="rating-badge">' . $reviewrating_val . '</span></div>';  
     
  }elseif(intval($reviewrating_val) == -1)
  {
    $reviews_tab .= '<div class="review-aggregate curriki-rating">Curriki Review <span class="rating-badge">-</span></div>';
  }elseif (!isset($resource['reviewrating']) && $resource['partner'] == 'T') {
    $reviews_tab .= '<div class="review-aggregate curriki-rating">Curriki Review <span class="rating-badge">P</span></div>';
  }else
  {
    $reviews_tab .= '<div class="review-aggregate curriki-rating">Curriki Review <span class="rating-badge">NR</span></div>';
  }
  
  $reviews_tab .= '<div class="review-content-box scrollbar rounded-borders-full border-grey">';
  
  
  if( $_GET["kill"] == 1 )
    {
        echo ">>>>>>**********>>>>>>>>>>> <pre>";
        var_dump( $resource );
        die;
    }
    
  if( isset($reviewrating_val) && $reviewrating_val >= 0)
  {      
    $reviews_tab .= '<p>This resource was reviewed using the Curriki Review rubric and received an overall Curriki Review System rating of ' . $reviewrating_val . ', as of ' . date("Y-m-d", strtotime($resource['lastreviewdate'])) . '.</p>';
  }elseif(intval($reviewrating_val) == -1)
  {
    $reviews_tab .= '<p>This resource was reviewed using the Curriki Review rubric and received an overall Curriki Review System rating of ' . '(-)' . ' as of ' . date("Y-m-d", strtotime($resource['lastreviewdate'])) . '.</p>';
  }
  else
  {
    $reviews_tab .= '<p>This resource has not yet been reviewed.</p>';
  }
  
  
  $reviews_tab .= '<h4 class="resource-subheadline">Component Ratings:</h4>';
  //$reviews_tab .= 'Technical Completeness:'.$resource['technicalcompleteness'].' Content Accuracy:'.$resource['contentaccuracy'].' Appropriate Pedagogy:'.$resource['pedagogy'].'';
  $reviews_tab .= $componentRatings;
  $reviews_tab .= '<br/><h4 class="resource-subheadline">Reviewer Comments:</h4>';
  $reviews_tab .= $reviewerComments;
  $reviews_tab .= '</div>';
  $reviews_tab .= '</div>';
  $reviews_tab .= '<div class="grid_6 review-members">';
  $reviews_tab .= '<div class="review-aggregate member-rating rating">Member Rating ' . $stars . '</div>';
  $reviews_tab .= '<div class="review-content-box scrollbar rounded-borders-full border-grey">';
  $reviews_tab .= '<div class="review review-form">';

  if (isset($resource['currentUser']['uniqueavatarfile']))
    $reviews_tab .= '<img class="border-grey circle" src="https://' . DBI_AWS_S3_CDN_URL . '//avatars/' . $resource['currentUser']['uniqueavatarfile'] . '" alt="member-name" />';
  else
    $reviews_tab .= '<img class="border-grey circle" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
  $reviews_tab .= '<div class="review-content">';


  if (isset($resource['currentUser']['display_name']))
    $display_name = $resource['currentUser']['display_name'];
  else
    $display_name = 'Member Name';

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
  $reviews_tab .= '<button class="green-button">Submit Review</button></form>';
  $reviews_tab .= '</div>';
  $reviews_tab .= '</div>';

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

  $reviews_tab .= '</div>';
  $reviews_tab .= '</div>';
  $reviews_tab .= '</div>';

  echo $reviews_tab;

//print_r($resource);
  /* $video = '';
    if(trim($resource['uniquename']) != '' AND $resource['folder'] == 'sourcevideos/') {
    $video = '
    <!--<h5>https://archivecurrikicdn.s3-us-west-2.amazonaws.com/sourcevideos/'. $resource['uniquename'] .'</h5>-->
    <script type="text/javascript" src="'. get_bloginfo('url') .'/wp-content/libs/jwplayer/jwplayer.js"></script>
    <script>jwplayer.key="XlnEnswS1k0cpvBFXqJYwsnzSWECBaplnchsHRncTA4=";</script>
    <div id="myElement">Loading the player...</div>
    <script type="text/javascript">
    jwplayer("myElement").setup({
    file: "https://archivecurrikicdn.s3-us-west-2.amazonaws.com/'. $resource['folder'] . $resource['uniquename'] .'",
    width: 640,
    height: 360
    });
    </script>
    ';
    } */

  // Resource Content (always visible below tabs)
  $resource_content = '';
  $resource_content .= '<div class="resource-content-sidebar">';
  $resource_content .= '<div class="resource-sidebar page-sidebar grid_2">';
  if ($resource['type'] == 'collection' && isset($resource['collection'])) {
    $resource_content .= '<div class="toc toc-card card rounded-borders-full border-grey no-min-width">';
    $resource_content .= '<div class="toc-header">Table of Contents</div>';
    $resource_content .= '<div class="toc-body">';
    $resource_content .= '<h4 class="toc-collection-folder"><span class="fa fa-folder-open"></span> ' . $resource['title'] . '</h4>';
    $resource_content .= '<ul class="fa fa-ul toc-collection toc-folder">';

    foreach ($resource['collection'] AS $collection)
      $resource_content .= '<li class="toc-file toc-image"><span class="fa fa-li fa-file-o"></span> <a href="' . get_bloginfo('url') . '/oer/?rid=' . $collection['resourceid'] . '">' . $collection['title'] . '</a></li>';

    $resource_content .= '</ul>';
    $resource_content .= '</div>';
    $resource_content .= '</div>';
  }
  //$resource_content .= '<div class="curriki-ad-sidebar">';
  //$resource_content .= '<img src="http://placehold.it/160x600" />';
  //$resource_content .= '</div>';
  $resource_content .= '</div>';
  $resource_content .= '<div class="resource-content grid_10">';
  $resource_content .= '<div class="resource-content-content rounded-borders-full border-grey">';

  //$resource_content .= '<h3>'.$resource['content']. $video .'</h3>';
  //$resource_content .= $resource['content']. $video;
  //$resource_content .= $resource['originalcontent']. $video;

  /* $resource_content .= '$_GET["pageurl"] = '. $_GET['pageurl'] .'<br />';
    $resource_content .= '$_REQUEST["pageurl"] = '. $_REQUEST['pageurl'] .'<br />';
    $resource_content .= $resource['newcontent']; */

  if (empty($resource['content']))
    $resource_content .= $resource['description'];
  else
    $resource_content .= $resource['content']; //. $video;
  $resource_content .= '</div>';

  /* if($resource['type'] == 'collection' && isset($resource['collection'])) {
    $resource_content .= '<br /><div class="resource-content-content rounded-borders-full border-grey">';
    foreach($resource['collection'] AS $collection)
    $resource_content .= '<div class="resource-content-content rounded-borders-full border-grey"><h3>' . $collection['title'] . '</h3><p>' . $collection['description'] . '</p></div><br />';
    $resource_content .= '</div>';
    } */



  if ($resource['type'] == 'collection' && isset($resource['collection'])) {
    $resource_content .= '<br /><div class="resource-content-content rounded-borders-full border-grey">';

    foreach ($resource['collection'] AS $collection) {
      $url = get_bloginfo('url') . '/oer/?rid=' . $collection['resourceid'];
      if (!empty($collection['description']))
        $content = $collection['description'];
      else
        $content = $collection['content'];
      /* if ((int) $collection['reviewrating'])
        $reviewrating = (int) $collection['reviewrating'];
        else
        $reviewrating = 'NR';
       */
      $reviewrating_val = round( (float) $collection['reviewrating'] );
      if ( isset($reviewrating_val) && $reviewrating_val >= 0) {         
        $reviewrating = $reviewrating_val;                 
      } elseif (intval($reviewrating_val) == -1) {
          $reviewrating = '-';
      } elseif (!isset($collection['reviewrating']) && $collection['partner'] == 'T') {
        $reviewrating = 'P';
      } else {
        $reviewrating = 'NR';
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

      $resource_content .= '<div class="resource-content-content rounded-borders-full border-grey"><div class="collection-body-title"><div class="collection-title"><h3><a href="' . $url . '">' . $collection['title'] . '</a></h3> by <span class="member-name name">' . $collection['contributorid_Name'] . '</span></div></div><div class="collection-body-content"><div class="collection-description">' . strip_tags(html_entity_decode($content)) . '</div><div class="collection-rating rating"><span class="member-rating-title">Member Rating</span>' . $m_stars . '<a href="#">Rate this collection</a></div><div class="collection-curriki-rating curriki-rating"><span class="curriki-rating-title">Curriki Rating</span><span class="rating-badge">' . $reviewrating . '</span></div></div></div><br />';
    }

    $resource_content .= '</div>';
  }


  $rs_url = get_bloginfo('url') . '/oer/' . $resource['pageurl'];
  
  $resource_content .= '<div style="display:none;"> <div id="gc-widget-div"> <div class="g-sharetoclassroom" data-url="'.$rs_url.'" data-title="'. $resource['title'].'" ></div> </div> </div>';
  
  $resource_content.="<script>gapi.sharetoclassroom.go(\"gc-widget-div\");</script>";
  
  $resource_content .= '</div>';
  $resource_content .= '</div>';

  echo $resource_content;


  echo '</div></div>';

  echo '</div>';

  /*
    educationalUse – (assignment, group work, curriculum) – name from resource_instructiontypes
    o   learningResourceType – name from resource_instructiontypes
    o   author - contributor name
    o   typicalAgeRange – convert educationallevel to age by adding 5.  Example:  educationallevel = 1 then age = 6.
    o   about – subject->subjectarea
    o   license & useRightsURL – www.creativecommons.org/licenses - licenses.url
   */
  if (!empty($resource) and is_array($resource)) {
    echo '
<div style="display:none;">
<meta itemprop="educationalUse" value="' . substr($typeName, 0, -2) . '"/>
<meta itemprop="learningResourceType" value="' . substr($typeName, 0, -2) . '"/>
<meta itemprop="interactivityType" value="mixed"/>
<meta itemprop="intendedEndUserRole" value="' . ($resource['studentfacing'] == 'T' ? 'student' : 'teacher') . '"/>
<meta itemprop="author" value="' . $resource['contributorid_Name'] . '"/>
<meta itemprop="typicalAgeRange" value="1-12"/>
<meta itemprop="name" value="' . $resource['title'] . '"/>
<meta itemprop="description" value="' . addslashes($resource['description']) . '"/>
<meta itemprop="mediaType" value="' . $resource['mediatype'] . '"/>
<meta itemprop="about" value="' . str_replace(' > ', ' ', implode(', ', $resource['subjects'])) . '"/>
<meta itemprop="dateCreated" value="' . $resource['createdate'] . '"/>
<meta itemprop="publisher" value="‘Curriki’"/>
<meta itemprop="inlanguage" value="language"/>
<meta itemprop="license" value="' . $resource['licenseName'] . '"/>
<meta itemprop="useRightsURL" value="www.creativecommons.org/licenses"/>
</div>
';
  }
  if (isset($resource['currentUser']['uniqueavatarfile']))
    $display_image = '<img class="border-grey circle" src="https://' . DBI_AWS_S3_CDN_URL . '//avatars/' . $comment['uniqueavatarfile'] . '" alt="member-name" />';
  else
    $display_image = '<img class="border-grey circle" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
  $reviews_tab .= '<div class="review-content">';


  if (isset($resource['currentUser']['display_name']))
    $display_name = $resource['currentUser']['display_name'];
  else
    $display_name = 'Member Name';

  $reviews_popup = '
									<div class="review-content-box scrollbar rounded-borders-full border-grey">
									<div class="review review-form">
									' . $display_image . '
									<div class="review-content">
										<div class="review-rating rating">
											<span class="member-name name">' . $display_name . '</span> <span>
											<span class="fa fa-star-o" id="resource-rating2-1" onclick="resourceRating2(1);"></span>
											<span class="fa fa-star-o" id="resource-rating2-2" onclick="resourceRating2(2);"></span>
											<span class="fa fa-star-o" id="resource-rating2-3" onclick="resourceRating2(3);"></span>
											<span class="fa fa-star-o" id="resource-rating2-4" onclick="resourceRating2(4);"></span>
											<span class="fa fa-star-o" id="resource-rating2-5" onclick="resourceRating2(5);"></span></span>
										</div>
										<form action="" method="post"><input type="hidden" name="resourceid" value="' . $resource['resourceid'] . '" /><input type="hidden" id="resource-rating2" name="resource-rating" /><textarea name="resource-comments"></textarea>
										<button class="green-button">Submit Review</button></form>
									</div></div></div>
  ';



  echo '
	<div id="resource-member-review" class="join-oauth-modal modal border-grey rounded-borders-full grid_8" style="display: none; width: 50%;">
		<h3 class="modal-title">Member Review</h3>
		<div><span id="login_result" class="dialog_result"></span></div>
		' . $reviews_popup . '
		
		<div class="close"><span class="fa fa-close" onclick="jQuery(\'#resource-member-review\').hide();"></span></div>
	</div>';

  $asdf = '<!--<div class="join-login-section grid_5 resource-member-review" style="width: 100%; max-height: 50%; overflow: scroll;">
			contents goes here.
		</div>-->';
}

add_action('genesis_after', 'curriki_addthis_scripts');

genesis();
