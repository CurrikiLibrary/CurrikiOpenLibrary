<?php
if (isset($_REQUEST['location'])) {
  $locations = array(
      'quote' => 'Featured Quotes',
      'dashboarduser' => 'Dashboard Featured Members',
      'partner' => 'Featured Partners',
      'homepageresource' => 'Home Page Featured Resources',
      'homepagealigned' => 'Home Page Featured Aligned Resources',
      'homepagemember' => 'Home Page Featured Members',
      'dashboardresource' => 'Dashboard Featuerd Resources',
      'dashboardgroup' => 'Dashboard Featured Groups',
      'homepagecollection' => 'Home Page Featured Collections',
      'homepagepartner' => 'Home Page Featured Partners',
  );
  $msg = $locations[$_REQUEST['location']] . ' Saved Successfully';
  ?>
  <h2></h2>
  <div id="message" class="updated below-h2">
    <p>
      <?php echo $msg; ?>
    </p>
  </div>
  <p></p>
<?php } ?>

<?php global $wpdb; ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css" />
<!--<script src="//code.jquery.com/jquery-1.10.2.js"></script>-->
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.4/css/jquery.dataTables.min.css">
<script type="text/javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>

<style>
  .sortable { list-style-type: none; margin: 0; padding: 0; width: 100%; }
  .sortable li { margin: 0 3px 3px 3px; padding: 0.3em 0 0.4em 0; padding-left: 1.5em; font-size: 1.0em;   }
  .sortable li span { position: absolute; margin-left: -1.3em; margin-top: 4px;}
  .sortable li text { width: 200px;display: inline-block;}
  .ui-icon-close { float: right !important;position: relative !important;cursor: pointer !important; }
  .left, .right {border: solid 1px #999; padding: 5px;border-bottom-radius:4px; height: 650px;}
  .left {float: left; width: 30%;}
  .right { overflow: hidden;}
  .btn{float: right}
  startdate,enddate,text,logofile{display: inline-block;}

  row{display: block; max-width: 800px; width:100% }
  col-left{display: inline-block; width: 49%;min-width: 250px;}
  col-right{display:inline-block; width: 49%;min-width: 250px;}
  
  col-left-medium{display: inline-block; width: 59%;min-width: 350px;}
  col-right-medium{display:inline-block; width: 59%;min-width: 350px;}
  
  col-full{display:inline-block; width: 99%;min-width: 250px;}
  row label{display: inline-block;width: 30%;text-align: right; float: left;line-height: 26px;}
  col-full label{width: 15%;}
  col-full textarea{width: 77%;}
  
  .ml-link{
      color: #001364 !important;
  }
  .hide-ml-row{
      display: none !important;
  }
</style>

<script>
  jQuery(function ($) {
    $(".sortable").sortable();
    //$(".sortable").disableSelection();
    $('.sortable .datepicker').datepicker({dateFormat: "yy-mm-dd"});
    $("#tabs").tabs();
    $('.datatable').DataTable();
    /*************** Users URL *************/
    $('form.searchForm').on('submit', function (e) {
      e.preventDefault();
      $data = $(this).serializeArray();
      var $type = $(this).attr('data-div');
      var $btn = $(this).find('.button');
      if ($data[0].value != '') {
        $btn.attr('disabled', 'disabled');
        $.post(ajaxurl, $data,
                function (response) {
                  $btn.removeAttr('disabled');
                  if (response == null) {
                    alert('No Record Found');
                  } else {
                    switch ($type) {
                      case 'featured_resources':
                        add_new(response.resourceid, response.title, '', 'resource', $type, 'image', $('nothing'));
                        break;
                      case 'aligned_resources':
                        add_new(response.resourceid, response.title, '', 'resource', $type, 'image', $('nothing'));
                        break;
                      case 'dashboard_resources':
                        add_new(response.resourceid, response.title, '', 'resource', $type, 'dashboard_resources', $('nothing'));
                        break;
                      case 'featured_members':
                        if ($data[1].value == 'get_group')
                          add_new(response.id, response.name, response.description, 'group', $type, 'sample', $('nothing'));
                        else
                          add_new(response.userid, response.lastname, '', 'user', $type, 'sample', $('nothing'));
                        break;
                      case 'dashboard_groups':
                        add_new(response.id, response.name, '', 'group', $type, 'dashboard_resources', $('nothing'));
                        break;

                      case 'featured_quotes':
                        add_new(response.userid, response.lastname, '', 'user', $type, 'sample', $('nothing'));
                        break;
                      case 'dashboard_members':
                        add_new(response.userid, response.lastname, '', 'user', $type, 'dashboard_members', $('nothing'));
                        break;
                      case 'featured_collections':
                        add_new(response.id, response.title, '', response.type, $type, 'featured_collections', $('nothing'));
                        break;
                      case 'featured_homepage_partners':
                        add_new(response.id, response.title, '', response.type, $type, 'featured_homepage_partners', $('nothing'));
                        break;
                    }
                  }
                }, 'json');
      } else {
        alert('Error: Search field is Empty!');
      }
    });
    
    $("body").on("click","input.mlSaveBtn",function(e){        
        var fid = $(this).attr("name").split("_")[1];
        
        var validated = true;        
        var language = $("#ml_language_"+fid).val();
        var displaytitle = $("#ml_displaytitle_"+fid).val();
        var featuredtext = $("#ml_featuredtext_"+fid).val();
        
        if(language.length === 0)
            validated = false;
        if(displaytitle.length === 0)
            validated = false;        
        
        $("#ml-alert-msg-"+fid).text("Saving....");
        $("#ml-alert-msg-"+fid).removeClass("hide-ml-row");
        if(validated)
        {
            var data = {'language':language,'displaytitle':displaytitle,'featuredtext':featuredtext};       
            var timeStamp = Math.floor(Date.now() / 1000);
            var ajax_url = ajaxurl+"?tm="+timeStamp;
            jQuery.ajax({
                method: "POST",
                url: ajax_url ,
                data : {'action':'save_admin_featureditems_ml','featureditemid':fid,'data':data}
            })
            .done(function( data ) {
                $("#ml-alert-msg-"+fid).text("Saving....");
                $("#ml-alert-msg-"+fid).addClass("hide-ml-row");
                //var data = JSON.parse(data);                        
                console.log("rtn >> " , data);
                alert("Translation Added!");

            });
        }else{
            alert("Please Complete Multilingual form")
        }
    
    });

    $("body").on("click","a.ml-link",function(e){        
        var class_arr = $(this).attr("class").split(" ");
        var id_class = class_arr[class_arr.length-1];
        var fid = id_class.split("-")[1];
        console.log("fid >>> " , fid);
        //console.log("aaaa >>> " , $(this).parents("ul.sortable ui-sortable-handle") );
        
        window.featureditemid_selected = fid;
        console.log("featureditemid_selected >>> " , window.featureditemid_selected);
        
        $(this).parents("ul.sortable ui-sortable-handle").focus();
        if( $("#ml-row-"+fid).hasClass("hide-ml-row") )
        {
            $("#ml-row-"+fid).removeClass("hide-ml-row");
        }else{
            $("#ml-row-"+fid).addClass("hide-ml-row");
        }
        
        
        e.preventDefault();
    });
    
    $("body").on("change","select.ml_language_select",function(e){
        var ml_language_val = $(this).val();
        
        var ml_language_id_arr = $(this).attr("id").split("_");
        var featureditemid = ml_language_id_arr[ml_language_id_arr.length-1];
        
        //console.log("featureditemid = ", featureditemid);
        //console.log("ml_language_val = ", ml_language_val);   
        
        $("#ml-loading-"+featureditemid).removeClass("hide-ml-row");
        var timeStamp = Math.floor(Date.now() / 1000);
        var ajax_url = ajaxurl+"?tm="+timeStamp;
        jQuery.ajax({
            method: "POST",
            url: ajax_url ,
            data : {'action':'load_admin_featureditems_ml','featureditemid':featureditemid,'language':ml_language_val}
        })
        .done(function( data ) {            
            $("#ml-loading-"+featureditemid).addClass("hide-ml-row");            
    
            if( data === "false")
            {
                $("#ml-alert-msg-"+featureditemid).text("No Translation. Fill form and save.");
                //$("#ml-alert-msg-"+featureditemid).show().delay(5000).fadeOut();
                $("#ml-alert-msg-"+featureditemid).show(function(){
                    $(this).removeClass("hide-ml-row");
                }).delay(3000).queue(function(n) {
                    $(this).addClass("hide-ml-row");
                    $(this).text("").hide(); n();
                  });
                
                $("#ml_displaytitle_"+featureditemid).val("");
                $("#ml_featuredtext_"+featureditemid).val("");
            }else{
                var data = JSON.parse(data);                                        
                console.log("rtn >> " , data);            
                $("#ml_displaytitle_"+data.featureditemid).val(data.displaytitle);
                $("#ml_featuredtext_"+data.featureditemid).val(data.featuredtext);
            }
        });
        
    });

    $("body").on("change","#featured_collections_subjects",function(e){
        var subject = $(this).val();
        var status = $("#featured_collections_status").val();
        var statusClass = '';

        if (status) {
          statusClass = ".featured_collections_" + status;
        }

        if (subject) {
          $(".ui-state-default").hide();
          $(".featured_collections_" + subject + statusClass).show();
        } else {
          $(".ui-state-default" + statusClass).show();
        }
    });

    $("body").on("change","#featured_collections_status",function(e){
        var status = $(this).val();
        var subject = $("#featured_collections_subjects").val();
        var subjectClass = '';

        if (subject) {
          subjectClass = ".featured_collections_" + subject;
        }

        if (status) {
          if (status == 'active') {
            $(".featured_collections_active" + subjectClass).show();
            $(".featured_collections_inactive").hide();
          } else {
            $(".featured_collections_active").hide();
            $(".featured_collections_inactive" + subjectClass).show();
          }
        } else {
          $(".featured_collections_active" + subjectClass).show();
          $(".featured_collections_inactive" + subjectClass).show();
        }
    });
  });

  //$( "#users" ).sortable( "refresh" );
  function remove_featured($obj) {
    $obj.parent().remove();
  }

  function add_new(id, name, desc, itemidtype, div, sample, $row) {
    name = name.replace(/['"]+/g, '&quot;')
    desc = desc.replace(/['"]+/g, '&quot;')
    $sample = jQuery('#sample_' + sample).html().replace(/\ITEM_ID/g, id).replace(/\ITEM_NAME/g, name).replace(/\ITEM_DESC/g, desc).replace(/\ITEMID_TYPE/g, itemidtype);
    jQuery('#' + div).append($sample);
    jQuery('#' + div + ' li:last-child').find('.datepicker').datepicker({dateFormat: "yy-mm-dd"});
    jQuery("html, body").animate({ scrollTop: jQuery(document).height() }, 1000);
    $row.parent().parent().remove();
  }
</script>

<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Featured Resource</a></li>
    <li><a href="#tabs-2">Aligned Resource</a></li>
    <li><a href="#tabs-3">Featured Member</a></li>
    <li><a href="#tabs-4">Dashboard Resources</a></li>
    <li><a href="#tabs-5">Dashboard Members</a></li>
    <li><a href="#tabs-6">Dashboard Groups</a></li>
    <li><a href="#tabs-7">Quotes</a></li>
    <li><a href="#tabs-8">Partners</a></li>
    <li><a href="#tabs-9">Featured Collections</a></li>
    <li><a href="#tabs-10">Featured Partners</a></li>
  </ul>

  <!----------  Featured Resource --------------->
  <div id="tabs-1">
    <div >
      <h3>Featured Resources</h3>
      <form action="" method="post" class="searchForm" data-div='featured_resources' >
        <label>Resource URL: </label><input type="text" name="query" class="query" /> 
        <input type="hidden" name="action" value="get_resource" class="action" /><input  class="button" type="submit" value="Add" />
      </form>
      <hr/>
      <form action="admin.php?page=mark_featured#tabs-1" method="post" enctype="multipart/form-data" >
        <input type="hidden" name="submit" value="true" />
        <input type="hidden" name="location" value="homepageresource" />
        <!--input type="hidden" name="itemidtype" value="resource" /-->
        <ul id="featured_resources" class="sortable">
          <?php
          foreach ($data["featured"] as $f) {
            if ($f->location != 'homepageresource')
              continue;
            ?>
            <li class="ui-state-default">
              <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
              <input type="hidden" value="<?php echo $f->itemid; ?>" class="featured_id" name="featuredid[]" />
              <input type="hidden" value="<?php echo $f->itemidtype; ?>" class="featured_id" name="itemidtype[]" />
              <span class="ui-icon-close ui-button-icon-primary ui-icon ui-icon-closethick" onclick="return remove_featured(jQuery(this));"></span>
            <row>
              <col-left><label>Title: </label><input type="text" value="<?php echo htmlspecialchars($f->displaytitle); ?>" placeholder="Featured Title" class="featured_title" name="featuredtitle[]" /></col-left>
              <col-right><label>Active: </label><label style="width: 68%;text-align: left; "><input type="checkbox" value="1" <?php echo $f->active == 'T' ? 'checked="checked"' : ''; ?> name="featuredactive[<?php echo $f->itemid; ?>]" /> Check to active</label></col-right>
            </row>
            <row>
              <col-left><label>Start: </label><input type="text" class="datepicker" name="startdate[]" value="<?php echo date('Y-m-d', strtotime($f->featuredstartdate)); ?>" /></col-left>
              <col-right><label>End: </label><input type="text" class="datepicker" name="enddate[]" value="<?php echo date('Y-m-d', strtotime($f->featuredenddate)); ?>" /></col-right>
            </row>
            <row>
              <col-full>
                <label>Image: </label>
                <input type="text" name="featuredimage[]" value="<?php echo $f->image; ?>" class="regular-text" style="width:61.5%">
                <input type="button" name="upload-btn" class="button-secondary" value="Upload Image" onclick="get_image_gallery(jQuery(this))">
              </col-full>
            </row>
            <row>
              <col-full><label>Text: </label><textarea placeholder="Featured Text" name="featuredtext[]" ><?php echo $f->featuredtext; ?></textarea></col-full>
            </row>
            
            <row>
              <col-full>
                  <label><strong><a href="#" class="ml-link lnk-<?php echo $f->featureditemid; ?>">Multilingual</a></strong></label>
              </col-full>
            </row>
            
            <row id="ml-row-<?php echo $f->featureditemid; ?>" class="hide-ml-row">
                <col-full>
                  <label class="md-lbl">Language: </label>                                  
                  <select name="ml_language_<?php echo $f->featureditemid; ?>" id="ml_language_<?php echo $f->featureditemid; ?>" class="ml_language_select">
                      <option value="" >--Select--</option>
                      <!--<option value="eng" >English</option>-->
                      <option value="spa" >Spanish</option>                          
                  </select>                   
                  <span id="ml-loading-<?php echo $f->featureditemid; ?>" class="hide-ml-row" style="margin-left: 10px;">Loading...</span>
                </col-full>
                <col-full>
                  <label class="md-lbl">Title: </label>                  
                  <input type="text" name="ml_displaytitle_<?php echo $f->featureditemid; ?>" id="ml_displaytitle_<?php echo $f->featureditemid; ?>" value="" />
                </col-full>
                <col-full>
                  <label class="md-lbl">Featured Text: </label>                  
                  <textarea name="ml_featuredtext_<?php echo $f->featureditemid; ?>" id="ml_featuredtext_<?php echo $f->featureditemid; ?>"></textarea>
                </col-full>
                <col-left>
                    <input type="button" name="mlsav_<?php echo $f->featureditemid; ?>" name="mlsav_<?php echo $f->featureditemid; ?>" class="mlSaveBtn" value="Save" />
                </col-left> 
            </row>
            
            </li>
          <?php } ?>
        </ul>
        <br/>
        <input type="submit" class="btn" name="Save" value="Save" />
      </form>
      <div style="clear:both;"></div>
    </div>
  </div><!------ Featured Resource end -------->

  <!----------  Aligned Resource --------------->
  <div id="tabs-2">
    <div >
      <h3>Aligned Resources</h3>
      <form action="" method="post" class="searchForm" data-div='aligned_resources'>
        <label>Resource URL: </label><input type="text" name="query" class="query" /> 
        <input type="hidden" name="action" value="get_resource" class="action" /><input  class="button" type="submit" value="Add" />
      </form>
      <hr/>
      <form action="admin.php?page=mark_featured#tabs-2" method="post" enctype="multipart/form-data" >
        <input type="hidden" name="submit" value="true" />
        <input type="hidden" name="location" value="homepagealigned" />
        <!--input type="hidden" name="itemidtype" value="resource" /-->
        <ul id="aligned_resources" class="sortable">
          <?php
          foreach ($data["featured"] as $f) {
            if ($f->location != 'homepagealigned')
              continue;
            ?>
            <li class="ui-state-default">
              <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
              <input type="hidden" value="<?php echo $f->itemid; ?>" class="featured_id" name="featuredid[]" />
              <input type="hidden" value="<?php echo $f->itemidtype; ?>" class="featured_id" name="itemidtype[]" />
              <span class="ui-icon-close ui-button-icon-primary ui-icon ui-icon-closethick" onclick="return remove_featured(jQuery(this));"></span>
            <row>
              <col-left><label>Title: </label><input type="text" value="<?php echo htmlspecialchars($f->displaytitle); ?>" placeholder="Featured Title" class="featured_title" name="featuredtitle[]" /></col-left>
              <col-right><label>Active: </label><label style="width: 68%;text-align: left; "><input type="checkbox" value="1" <?php echo $f->active == 'T' ? 'checked="checked"' : ''; ?> name="featuredactive[<?php echo $f->itemid; ?>]" /> Check to active</label></col-right>
            </row>
            <row>
              <col-left><label>Start: </label><input type="text" class="datepicker" name="startdate[]" value="<?php echo date('Y-m-d', strtotime($f->featuredstartdate)); ?>" /></col-left>
              <col-right><label>End: </label><input type="text" class="datepicker" name="enddate[]" value="<?php echo date('Y-m-d', strtotime($f->featuredenddate)); ?>" /></col-right>
            </row>
            <row>
              <col-full>
                <label>Image: </label>
                <input type="text" name="featuredimage[]" value="<?php echo $f->image; ?>" class="regular-text" style="width:61.5%">
                <input type="button" name="upload-btn" class="button-secondary" value="Upload Image" onclick="get_image_gallery(jQuery(this))">
              </col-full>
            </row>
            <row>
              <col-full><label>Text: </label><textarea placeholder="Featured Text" name="featuredtext[]" ><?php echo $f->featuredtext; ?></textarea></col-full>
            </row>
            
            <row>
              <col-full>
                  <label><strong><a href="#" class="ml-link lnk-<?php echo $f->featureditemid; ?>">Multilingual</a></strong></label>
              </col-full>
            </row>
            <row id="ml-row-<?php echo $f->featureditemid; ?>" class="hide-ml-row">
                <col-full>
                  <label class="md-lbl">Language: </label>                                  
                  <select name="ml_language_<?php echo $f->featureditemid; ?>" id="ml_language_<?php echo $f->featureditemid; ?>" class="ml_language_select">
                      <option value="" >--Select--</option>
                      <!--<option value="eng" >English</option>-->
                      <option value="spa" >Spanish</option>                          
                  </select>                   
                  <span id="ml-loading-<?php echo $f->featureditemid; ?>" class="hide-ml-row" style="margin-left: 10px;">Loading...</span>
                </col-full>
                <col-full>
                  <label class="md-lbl">Title: </label>                  
                  <input type="text" name="ml_displaytitle_<?php echo $f->featureditemid; ?>" id="ml_displaytitle_<?php echo $f->featureditemid; ?>" value="" />
                </col-full>
                <col-full>
                  <label class="md-lbl">Featured Text: </label>                  
                  <textarea name="ml_featuredtext_<?php echo $f->featureditemid; ?>" id="ml_featuredtext_<?php echo $f->featureditemid; ?>"></textarea>
                </col-full>
                <col-left>
                    <input type="button" name="mlsav_<?php echo $f->featureditemid; ?>" name="mlsav_<?php echo $f->featureditemid; ?>" class="mlSaveBtn" value="Save" />
                </col-left> 
            </row>
            
            </li>
          <?php } ?>
        </ul>
        <br/>
        <input type="submit" class="btn" name="Save" value="Save" />
      </form>
      <div style="clear:both;"></div>
    </div>
  </div><!------ Aligned Resource end -------->

  <!----------  Featured Member --------------->
  <div id="tabs-3">
    <div>
      <h3>Featured Member</h3>
      <table><tr><td>
            <form action="" method="post" class="searchForm" data-div='featured_members'>
              <label>Member login: </label><input type="text" name="query" class="query" /> 
              <input type="hidden" name="action" value="get_user" class="action" /><input  class="button" type="submit" value="Add" />
            </form>
          </td><td>
            &nbsp;&nbsp;&nbsp;<strong>OR</strong>&nbsp;&nbsp;&nbsp;
          </td><td>
            <form action="" method="post" class="searchForm" data-div='featured_members'>
              <label>Group Slug: </label><input type="text" name="query" class="query" /> 
              <input type="hidden" name="action" value="get_group" class="action" /><input  class="button" type="submit" value="Add" />
            </form>
          </td></tr></table>
      <hr/>
      <form action="admin.php?page=mark_featured#tabs-3" method="post" enctype="multipart/form-data" >
        <input type="hidden" name="submit" value="true" />
        <input type="hidden" name="location" value="homepagemember" />
        <!--input type="hidden" name="itemidtype" value="user" /-->
        <ul id="featured_members" class="sortable">
          <?php
          foreach ($data["featured"] as $f) {
            if ($f->location != 'homepagemember')
              continue;
            ?>
            <li class="ui-state-default">
              <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
              <input type="hidden" value="<?php echo $f->itemid; ?>" class="featured_id" name="featuredid[]" />
              <input type="hidden" value="<?php echo $f->itemidtype; ?>" class="featured_id" name="itemidtype[]" />
              <span class="ui-icon-close ui-button-icon-primary ui-icon ui-icon-closethick" onclick="return remove_featured(jQuery(this));"></span>
            <row>
              <col-left><label>Title: </label><input type="text" value="<?php echo htmlspecialchars($f->displaytitle); ?>" placeholder="Featured Title" class="featured_title" name="featuredtitle[]" /></col-left>
              <col-right><label>Active: </label><label style="width: 68%;text-align: left; "><input type="checkbox" value="1" <?php echo $f->active == 'T' ? 'checked="checked"' : ''; ?> name="featuredactive[<?php echo $f->itemid; ?>]" /> Check to active</label></col-right>
            </row>
            <row>
              <col-left><label>Start: </label><input type="text" class="datepicker" name="startdate[]" value="<?php echo date('Y-m-d', strtotime($f->featuredstartdate)); ?>" /></col-left>
              <col-right><label>End: </label><input type="text" class="datepicker" name="enddate[]" value="<?php echo date('Y-m-d', strtotime($f->featuredenddate)); ?>" /></col-right>
            </row>
            <row>
              <col-full><label>Text: </label><textarea placeholder="Featured Text" name="featuredtext[]" ><?php echo $f->featuredtext; ?></textarea></col-full>
            </row>
            
            <row>
              <col-full>
                  <label><strong><a href="#" class="ml-link lnk-<?php echo $f->featureditemid; ?>">Multilingual</a></strong></label>
              </col-full>
            </row>
            <row id="ml-row-<?php echo $f->featureditemid; ?>" class="hide-ml-row">
                <col-full>
                  <label class="md-lbl">Language: </label>                                  
                  <select name="ml_language_<?php echo $f->featureditemid; ?>" id="ml_language_<?php echo $f->featureditemid; ?>" class="ml_language_select">
                      <option value="" >--Select--</option>
                      <!--<option value="eng" >English</option>-->
                      <option value="spa" >Spanish</option>                          
                  </select>                   
                  <span id="ml-loading-<?php echo $f->featureditemid; ?>" class="hide-ml-row" style="margin-left: 10px;">Loading...</span>
                </col-full>
                <col-full>
                  <label class="md-lbl">Title: </label>                  
                  <input type="text" name="ml_displaytitle_<?php echo $f->featureditemid; ?>" id="ml_displaytitle_<?php echo $f->featureditemid; ?>" value="" />
                </col-full>
                <col-full>
                  <label class="md-lbl">Featured Text: </label>                  
                  <textarea name="ml_featuredtext_<?php echo $f->featureditemid; ?>" id="ml_featuredtext_<?php echo $f->featureditemid; ?>"></textarea>
                </col-full>
                <col-left>
                    <input type="button" name="mlsav_<?php echo $f->featureditemid; ?>" name="mlsav_<?php echo $f->featureditemid; ?>" class="mlSaveBtn" value="Save" />
                </col-left> 
            </row>
            
            </li>
          <?php } ?>
        </ul>
        <br/>
        <input type="submit" class="btn" name="Save" value="Save" />
      </form>
      <div style="clear:both;"></div>
    </div>
  </div><!------ Featured Member end -------->

  <!----------  Dashboard Resources --------------->
  <div id="tabs-4">
    <div >
      <h3>Dashboard Resources</h3>
      <form action="" method="post" class="searchForm" data-div='dashboard_resources'>
        <label>Resource URL: </label><input type="text" name="query" class="query" /> 
        <input type="hidden" name="action" value="get_resource" class="action" /><input  class="button" type="submit" value="Add" />
      </form>
      <hr/>
      <form action="admin.php?page=mark_featured#tabs-4" method="post" enctype="multipart/form-data" >
        <input type="hidden" name="submit" value="true" />
        <input type="hidden" name="location" value="dashboardresource" />
        <!--input type="hidden" name="itemidtype" value="resource" /-->
        <ul id="dashboard_resources" class="sortable">
          <?php
          foreach ($data["featured"] as $f) {
            if ($f->location != 'dashboardresource')
              continue;
            ?>
            <li class="ui-state-default">
              <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
              <input type="hidden" value="<?php echo $f->itemid; ?>" class="featured_id" name="featuredid[]" />
              <input type="hidden" value="<?php echo $f->itemidtype; ?>" class="featured_id" name="itemidtype[]" />
              <span class="ui-icon-close ui-button-icon-primary ui-icon ui-icon-closethick" onclick="return remove_featured(jQuery(this));"></span>
            <row>
              <col-left><label>Title: </label><input type="text" value="<?php echo htmlspecialchars($f->displaytitle); ?>" placeholder="Featured Title" class="featured_title" name="featuredtitle[]" /></col-left>
              <col-right><label>Active: </label><label style="width: 68%;text-align: left; "><input type="checkbox" value="1" <?php echo $f->active == 'T' ? 'checked="checked"' : ''; ?> name="featuredactive[<?php echo $f->itemid; ?>]" /> Check to active</label></col-right>
            </row>
            <row>
              <col-left><label>Start: </label><input type="text" class="datepicker" name="startdate[]" value="<?php echo date('Y-m-d', strtotime($f->featuredstartdate)); ?>" /></col-left>
              <col-right><label>End: </label><input type="text" class="datepicker" name="enddate[]" value="<?php echo date('Y-m-d', strtotime($f->featuredenddate)); ?>" /></col-right>
            </row>
            <row>
              <col-full>
                <label>Image: </label>
                <input type="text" name="featuredimage[]" value="<?php echo $f->image; ?>" class="regular-text" style="width:61.5%">
                <input type="button" name="upload-btn" class="button-secondary" value="Upload Image" onclick="get_image_gallery(jQuery(this))">
              </col-full>
            </row>
            
            <row>
              <col-full>
                  <label><strong><a href="#" class="ml-link lnk-<?php echo $f->featureditemid; ?>">Multilingual</a></strong></label>
              </col-full>
            </row>
            <row id="ml-row-<?php echo $f->featureditemid; ?>" class="hide-ml-row">
                <col-full>
                  <label class="md-lbl">Language: </label>                                  
                  <select name="ml_language_<?php echo $f->featureditemid; ?>" id="ml_language_<?php echo $f->featureditemid; ?>" class="ml_language_select">
                      <option value="" >--Select--</option>
                      <!--<option value="eng" >English</option>-->
                      <option value="spa" >Spanish</option>                          
                  </select>                   
                  <span id="ml-loading-<?php echo $f->featureditemid; ?>" class="hide-ml-row" style="margin-left: 10px;">Loading...</span>
                </col-full>
                <col-full>
                  <label class="md-lbl">Title: </label>                  
                  <input type="text" name="ml_displaytitle_<?php echo $f->featureditemid; ?>" id="ml_displaytitle_<?php echo $f->featureditemid; ?>" value="" />
                </col-full>
                <col-full>
                  <label class="md-lbl">Featured Text: </label>                  
                  <textarea name="ml_featuredtext_<?php echo $f->featureditemid; ?>" id="ml_featuredtext_<?php echo $f->featureditemid; ?>"></textarea>
                </col-full>
                <col-left>
                    <input type="button" name="mlsav_<?php echo $f->featureditemid; ?>" name="mlsav_<?php echo $f->featureditemid; ?>" class="mlSaveBtn" value="Save" />
                </col-left> 
            </row>
            
            </li>
          <?php } ?>
        </ul>
        <br/>
        <input type="submit" class="btn" name="Save" value="Save" />
      </form>
      <div style="clear:both;"></div>
    </div>
  </div><!------ Dashboard Resources end -------->

  <!----------  Dashboard Members --------------->
  <div id="tabs-5">
    <div>
      <h3>Dashboard Member</h3>
      <form action="" method="post" class="searchForm" data-div='dashboard_members'>
        <label>Member login: </label><input type="text" name="query" class="query" /> 
        <input type="hidden" name="action" value="get_user" class="action" /><input  class="button" type="submit" value="Add" />
      </form>
      <hr/>
      <form action="admin.php?page=mark_featured#tabs-5" method="post" enctype="multipart/form-data" >
        <input type="hidden" name="submit" value="true" />
        <input type="hidden" name="location" value="dashboarduser" />
        <input type="hidden" name="itemidtype" value="user" />
        <ul id="dashboard_members" class="sortable">
          <?php
          foreach ($data["featured"] as $f) {
            if ($f->location != 'dashboarduser')
              continue;
            ?>
            <li class="ui-state-default">
              <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
              <input type="hidden" value="<?php echo $f->itemid; ?>" class="featured_id" name="featuredid[]" />
              <input type="hidden" value="<?php echo $f->itemidtype; ?>" class="featured_id" name="itemidtype[]" />
              <span class="ui-icon-close ui-button-icon-primary ui-icon ui-icon-closethick" onclick="return remove_featured(jQuery(this));"></span>
            <row>
              <col-left><label>Title: </label><input type="text" value="<?php echo htmlspecialchars($f->displaytitle); ?>" placeholder="Featured Title" class="featured_title" name="featuredtitle[]" /></col-left>
              <col-right><label>Active: </label><label style="width: 68%;text-align: left; "><input type="checkbox" value="1" <?php echo $f->active == 'T' ? 'checked="checked"' : ''; ?> name="featuredactive[<?php echo $f->itemid; ?>]" /> Check to active</label></col-right>
            </row>
            <row>
              <col-left><label>Start: </label><input type="text" class="datepicker" name="startdate[]" value="<?php echo $f->featuredstartdate; ?>" /></col-left>
              <col-right><label>End: </label><input type="text" class="datepicker" name="enddate[]" value="<?php echo $f->featuredenddate; ?>" /></col-right>
            </row>
            
            <row>
              <col-full>
                  <label><strong><a href="#" class="ml-link lnk-<?php echo $f->featureditemid; ?>">Multilingual</a></strong></label>
              </col-full>
            </row>
            
            <row id="ml-row-<?php echo $f->featureditemid; ?>" class="hide-ml-row">
                <col-full>
                  <label class="md-lbl">Language: </label>                                  
                  <select name="ml_language_<?php echo $f->featureditemid; ?>" id="ml_language_<?php echo $f->featureditemid; ?>" class="ml_language_select">
                      <option value="" >--Select--</option>
                      <!--<option value="eng" >English</option>-->
                      <option value="spa" >Spanish</option>                          
                  </select>                   
                  <span id="ml-loading-<?php echo $f->featureditemid; ?>" class="hide-ml-row" style="margin-left: 10px;">Loading...</span>
                </col-full>
                <col-full>
                  <label class="md-lbl">Title: </label>                  
                  <input type="text" name="ml_displaytitle_<?php echo $f->featureditemid; ?>" id="ml_displaytitle_<?php echo $f->featureditemid; ?>" value="" />
                </col-full>
                <col-full>
                  <label class="md-lbl">Featured Text: </label>                  
                  <textarea name="ml_featuredtext_<?php echo $f->featureditemid; ?>" id="ml_featuredtext_<?php echo $f->featureditemid; ?>"></textarea>
                </col-full>
                <col-left>
                    <input type="button" name="mlsav_<?php echo $f->featureditemid; ?>" name="mlsav_<?php echo $f->featureditemid; ?>" class="mlSaveBtn" value="Save" />
                </col-left> 
            </row>
            
            </li>
          <?php } ?>
        </ul>
        <br/>
        <input type="submit" class="btn" name="Save" value="Save" />
      </form>
      <div style="clear:both;"></div>
    </div>
  </div><!------ Dashboard Members end -------->

  <!----------  Dashboard Groups --------------->
  <div id="tabs-6">
    <div >
      <h3>Dashboard Groups</h3>
      <form action="" method="post" class="searchForm" data-div='dashboard_groups'>
        <label>Group Slug: </label><input type="text" name="query" class="query" /> 
        <input type="hidden" name="action" value="get_group" class="action" /><input  class="button" type="submit" value="Add" />
      </form>
      <hr/>
      <form action="admin.php?page=mark_featured#tabs-4" method="post" enctype="multipart/form-data" >
        <input type="hidden" name="submit" value="true" />
        <input type="hidden" name="location" value="dashboardgroup" />
        <!--input type="hidden" name="itemidtype" value="group" /-->
        <ul id="dashboard_groups" class="sortable">
          <?php
          foreach ($data["featured"] as $f) {
            if ($f->location != 'dashboardgroup')
              continue;
            ?>
            <li class="ui-state-default">
              <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
              <input type="hidden" value="<?php echo $f->itemid; ?>" class="featured_id" name="featuredid[]" />
              <input type="hidden" value="<?php echo $f->itemidtype; ?>" class="featured_id" name="itemidtype[]" />
              <span class="ui-icon-close ui-button-icon-primary ui-icon ui-icon-closethick" onclick="return remove_featured(jQuery(this));"></span>
            <row>
              <col-left><label>Title: </label><input type="text" value="<?php echo htmlspecialchars($f->displaytitle); ?>" placeholder="Featured Title" class="featured_title" name="featuredtitle[]" /></col-left>
              <col-right><label>Active: </label><label style="width: 68%;text-align: left; "><input type="checkbox" value="1" <?php echo $f->active == 'T' ? 'checked="checked"' : ''; ?> name="featuredactive[<?php echo $f->itemid; ?>]" /> Check to active</label></col-right>
            </row>
            <row>
              <col-left><label>Start: </label><input type="text" class="datepicker" name="startdate[]" value="<?php echo date('Y-m-d', strtotime($f->featuredstartdate)); ?>" /></col-left>
              <col-right><label>End: </label><input type="text" class="datepicker" name="enddate[]" value="<?php echo date('Y-m-d', strtotime($f->featuredenddate)); ?>" /></col-right>
            </row>
            <row>
              <col-full>
                <label>Image: </label>
                <input type="text" name="featuredimage[]" value="<?php echo $f->image; ?>" class="regular-text" style="width:61.5%">
                <input type="button" name="upload-btn" class="button-secondary" value="Upload Image" onclick="get_image_gallery(jQuery(this))">
              </col-full>
            </row>
            
            <row>
              <col-full>
                  <label><strong><a href="#" class="ml-link lnk-<?php echo $f->featureditemid; ?>">Multilingual</a></strong></label>
              </col-full>
            </row>
            
            <row id="ml-row-<?php echo $f->featureditemid; ?>" class="hide-ml-row">
                <col-full>
                  <label class="md-lbl">Language: </label>                                  
                  <select name="ml_language_<?php echo $f->featureditemid; ?>" id="ml_language_<?php echo $f->featureditemid; ?>" class="ml_language_select">
                      <option value="" >--Select--</option>
                      <!--<option value="eng" >English</option>-->
                      <option value="spa" >Spanish</option>                          
                  </select>                   
                  <span id="ml-loading-<?php echo $f->featureditemid; ?>" class="hide-ml-row" style="margin-left: 10px;">Loading...</span>
                  <span id="ml-alert-msg-<?php echo $f->featureditemid; ?>" class="hide-ml-row" style="margin-left: 10px;">Saving...</span>
                </col-full>
                <col-full>
                  <label class="md-lbl">Title: </label>                  
                  <input type="text" name="ml_displaytitle_<?php echo $f->featureditemid; ?>" id="ml_displaytitle_<?php echo $f->featureditemid; ?>" value="" />
                </col-full>
                <col-full>
                  <label class="md-lbl">Featured Text: </label>                  
                  <textarea name="ml_featuredtext_<?php echo $f->featureditemid; ?>" id="ml_featuredtext_<?php echo $f->featureditemid; ?>"></textarea>
                </col-full>
                <col-left>
                    <input type="button" name="mlsav_<?php echo $f->featureditemid; ?>" name="mlsav_<?php echo $f->featureditemid; ?>" class="mlSaveBtn" value="Save" />
                </col-left> 
            </row>
            
            </li>
          <?php } ?>
        </ul>
        <br/>
        <input type="submit" class="btn" name="Save" value="Save" />
      </form>
      <div style="clear:both;"></div>
    </div>
  </div><!------ Featuerd Quotes end -------->

  <!----------  Featured Quotes --------------->
  <div id="tabs-7">
    <div >
      <h3>Featured Quotes</h3>
      <form action="" method="post" class="searchForm" data-div='featured_quotes'>
        <label>User login: </label><input type="text" name="query" class="query" /> 
        <input type="hidden" name="action" value="get_user" class="action" /><input  class="button" type="submit" value="Add" />
      </form>
      <hr/>
      <form action="admin.php?page=mark_featured#tabs-7" method="post" enctype="multipart/form-data" >
        <input type="hidden" name="submit" value="true" />
        <input type="hidden" name="location" value="quote" />
        <input type="hidden" name="itemidtype" value="user" />
        <ul id="featured_quotes" class="sortable">
          <?php
          foreach ($data["featured"] as $f) {
            if ($f->location != 'quote')
              continue;
            ?>
            <li class="ui-state-default">
              <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
              <input type="hidden" value="<?php echo $f->itemid; ?>" class="featured_id" name="featuredid[]" />
              <input type="hidden" value="<?php echo $f->itemidtype; ?>" class="featured_id" name="itemidtype[]" />
              <span class="ui-icon-close ui-button-icon-primary ui-icon ui-icon-closethick" onclick="return remove_featured(jQuery(this));"></span>
            <row>
              <col-left><label>Title: </label><input type="text" value="<?php echo htmlspecialchars($f->displaytitle); ?>" placeholder="Featured Title" class="featured_title" name="featuredtitle[]" /></col-left>
              <col-right><label>Active: </label><label style="width: 68%;text-align: left; "><input type="checkbox" value="1" <?php echo $f->active == 'T' ? 'checked="checked"' : ''; ?> name="featuredactive[<?php echo $f->itemid; ?>]" /> Check to active</label></col-right>
            </row>
            <row>
              <col-left><label>Start: </label><input type="text" class="datepicker" name="startdate[]" value="<?php echo date('Y-m-d', strtotime($f->featuredstartdate)); ?>" /></col-left>
              <col-right><label>End: </label><input type="text" class="datepicker" name="enddate[]" value="<?php echo date('Y-m-d', strtotime($f->featuredenddate)); ?>" /></col-right>
            </row>
            <row>
              <col-full><label>Text: </label><textarea placeholder="Featured Text" name="featuredtext[]" ><?php echo $f->featuredtext; ?></textarea></col-full>
            </row>
            
            <row>
              <col-full>
                  <label><strong><a href="#" class="ml-link lnk-<?php echo $f->featureditemid; ?>">Multilingual</a></strong></label>
              </col-full>
            </row>
            
            <row id="ml-row-<?php echo $f->featureditemid; ?>" class="hide-ml-row">
                <col-full>
                  <label class="md-lbl">Language: </label>                                  
                  <select name="ml_language_<?php echo $f->featureditemid; ?>" id="ml_language_<?php echo $f->featureditemid; ?>" class="ml_language_select">
                      <option value="" >--Select--</option>
                      <!--<option value="eng" >English</option>-->
                      <option value="spa" >Spanish</option>                          
                  </select>                   
                  <span id="ml-loading-<?php echo $f->featureditemid; ?>" class="hide-ml-row" style="margin-left: 10px;">Loading...</span>
                </col-full>
                <col-full>
                  <label class="md-lbl">Title: </label>                  
                  <input type="text" name="ml_displaytitle_<?php echo $f->featureditemid; ?>" id="ml_displaytitle_<?php echo $f->featureditemid; ?>" value="" />
                </col-full>
                <col-full>
                  <label class="md-lbl">Featured Text: </label>                  
                  <textarea name="ml_featuredtext_<?php echo $f->featureditemid; ?>" id="ml_featuredtext_<?php echo $f->featureditemid; ?>"></textarea>
                </col-full>
                <col-left>
                    <input type="button" name="mlsav_<?php echo $f->featureditemid; ?>" name="mlsav_<?php echo $f->featureditemid; ?>" class="mlSaveBtn" value="Save" />
                </col-left> 
            </row>
            
            </li>
          <?php } ?>
        </ul>
        <br/>
        <input type="submit" class="btn" name="Save" value="Save" />
      </form>
      <div style="clear:both;"></div>
    </div>
  </div> <!------ Featuerd Quotes end -------->

  <!----------  Featured Partners --------------->
  <div id="tabs-8">

    <div >
        <a href="javascript://" onclick="add_new('', '', '', 'partner', 'featured_partners', 'partner', jQuery('#nothing'))" style="background-color: #1583cc;    box-shadow: 0 -2px 0 rgba(0,0,0,0.2);font-weight: bold;    border: none;    display: inline-block;text-align: center;    line-height: 1;    cursor: pointer;    border-radius: 3px;padding: 0.85em 1em;color: #fff;text-decoration: none;">Add</a>
      <hr/>
      <h3>Featured Partners</h3>
      <form action="admin.php?page=mark_featured" method="post" enctype="multipart/form-data" >
        <input type="hidden" name="submit" value="true" />
        <input type="hidden" name="location" value="partner" />
        <input type="hidden" name="itemidtype" value="partner" />
        <ul id="featured_partners" class="sortable">
          <?php
          foreach ($data["featured"] as $f) {
            if ($f->location != 'partner')
              continue;
            ?>
            <li class="ui-state-default">
              <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
              <input type="hidden" value="<?php echo $f->itemid; ?>" class="featured_id" name="featuredid[]" />
              <input type="hidden" value="<?php echo $f->itemidtype; ?>" class="featured_id" name="itemidtype[]" />
              <span class="ui-icon-close ui-button-icon-primary ui-icon ui-icon-closethick" onclick="return remove_featured(jQuery(this));"></span>
            <row>
              <col-left><label>Title: </label><input type="text" value="<?php echo htmlspecialchars($f->displaytitle); ?>" placeholder="Featured Title" class="featured_title" name="featuredtitle[]" /></col-left>
              <col-right><label>Active: </label><label style="width: 68%;text-align: left; "><input type="checkbox" value="1" <?php echo $f->active == 'T' ? 'checked="checked"' : ''; ?> name="featuredactive[<?php echo $f->itemid; ?>]" /> Check to active</label></col-right>
            </row>
            <row>
              <col-left><label>Start: </label><input type="text" class="datepicker" name="startdate[]" value="<?php echo date('Y-m-d', strtotime($f->featuredstartdate)); ?>" /></col-left>
              <col-right><label>End: </label><input type="text" class="datepicker" name="enddate[]" value="<?php echo date('Y-m-d', strtotime($f->featuredenddate)); ?>" /></col-right>
            </row>
            <row>
              <col-full>
                <label>Image: </label>
                <input type="text" name="featuredimage[]" value="<?php echo $f->image; ?>" class="regular-text" style="width:61.5%">
                <input type="button" name="upload-btn" class="button-secondary" value="Upload Image" onclick="get_image_gallery(jQuery(this))">
              </col-full>
            </row>
            <row>
              <col-full><label>Link: </label><input type="text" value="<?php echo $f->link; ?>" placeholder="http://" class="featured_link" name="featuredlink[]" style="width: 77%" /></col-full>
            </row>
            <row>
              <col-full><label>Text: </label><textarea placeholder="Featured Text" name="featuredtext[]" ><?php echo $f->featuredtext; ?></textarea></col-full>
            </row>
            
            <row>
              <col-full>
                  <label><strong><a href="#" class="ml-link lnk-<?php echo $f->featureditemid; ?>">Multilingual</a></strong></label>
              </col-full>
            </row>
            
            <row id="ml-row-<?php echo $f->featureditemid; ?>" class="hide-ml-row">
                <col-full>
                  <label class="md-lbl">Language: </label>                                  
                  <select name="ml_language_<?php echo $f->featureditemid; ?>" id="ml_language_<?php echo $f->featureditemid; ?>" class="ml_language_select">
                      <option value="" >--Select--</option>
                      <!--<option value="eng" >English</option>-->
                      <option value="spa" >Spanish</option>                          
                  </select>                   
                  <span id="ml-loading-<?php echo $f->featureditemid; ?>" class="hide-ml-row" style="margin-left: 10px;">Loading...</span>
                </col-full>
                <col-full>
                  <label class="md-lbl">Title: </label>                  
                  <input type="text" name="ml_displaytitle_<?php echo $f->featureditemid; ?>" id="ml_displaytitle_<?php echo $f->featureditemid; ?>" value="" />
                </col-full>
                <col-full>
                  <label class="md-lbl">Featured Text: </label>                  
                  <textarea name="ml_featuredtext_<?php echo $f->featureditemid; ?>" id="ml_featuredtext_<?php echo $f->featureditemid; ?>"></textarea>
                </col-full>
                <col-left>
                    <input type="button" name="mlsav_<?php echo $f->featureditemid; ?>" name="mlsav_<?php echo $f->featureditemid; ?>" class="mlSaveBtn" value="Save" />
                </col-left> 
            </row>
            
            </li>
          <?php } ?>
        </ul>
        <br/>
        <input type="submit" class="btn" name="Save" value="Save" />
      </form>
      <div style="clear:both;"></div>
    </div>
  </div> <!------ Featuerd PArtenrs end -------->

  <!----------  Featured Collections --------------->
  <div id="tabs-9">
    <div >
      <h3>Featured Collections</h3>
      <form action="" method="post" class="searchForm" data-div='featured_collections' >
        <label>Collection URL: </label><input type="text" name="query" class="query" />
        <input type="hidden" name="action" value="get_collection" class="action" /><input  class="button" type="submit" value="Add" />
        <label>| Filter By: </label>
        <select id="featured_collections_status" name="featured_collections_status">
          <option value="">All Items</option>
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
        </select>
        <select id="featured_collections_subjects" name="featured_collections_subject">
          <option value="">All Subjects</option>
          <?php
            foreach ($data["subjects"] as $s) {
              ?>
              <option value="<?php echo $s->subject; ?>"><?php echo $s->displayname; ?></option>
            <?php } ?>
        </select>
      </form>
      <hr/>
      <form action="admin.php?page=mark_featured#tabs-9" method="post" enctype="multipart/form-data" >
        <input type="hidden" name="submit" value="true" />
        <input type="hidden" name="location" value="homepagecollection" />
        <!--input type="hidden" name="itemidtype" value="resource" /-->
        <ul id="featured_collections" class="sortable">
          <?php
          foreach ($data["featured"] as $f) {
            if ($f->location != 'homepagecollection')
              continue;

            $itemUrl = '';
            $itemTitle = '';
            if ($f->itemidtype == 'collection') {
              $q_resource = "SELECT * FROM resources WHERE resourceid = '" . $f->itemid . "'";
              $resource = $wpdb->get_row($q_resource);
              $itemUrl = get_bloginfo('url') . '/oer/' . $resource->pageurl;
              $itemTitle = $resource->title;
            } else if ($f->itemidtype == 'community') {
              $q_community = "SELECT * FROM communities WHERE communityid = '" . $f->itemid . "'";
              $community = $wpdb->get_row($q_community);
              $itemUrl = get_bloginfo('url') . '/community/' . $community->url;
              $itemTitle = $community->name;
            }
            ?>
            <li class="ui-state-default <?php echo $f->active == 'T' ? 'featured_collections_active' : 'featured_collections_inactive'; ?> <?php echo 'featured_collections_'.$f->link; ?>">
              <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
              <input type="hidden" value="<?php echo $f->itemid; ?>" class="featured_id" name="featuredid[]" />
              <input type="hidden" value="<?php echo $f->itemidtype; ?>" class="featured_id" name="itemidtype[]" />
              <span class="ui-icon-close ui-button-icon-primary ui-icon ui-icon-closethick" onclick="return remove_featured(jQuery(this));"></span>
            <row>
              <col-left><label>Title: </label><input type="text" value="<?php echo htmlspecialchars($f->displaytitle); ?>" placeholder="Featured Title" class="featured_title" name="featuredtitle[]" /></col-left>
              <col-right><label>Active: </label><label style="width: 68%;text-align: left; "><input type="checkbox" value="1" <?php echo $f->active == 'T' ? 'checked="checked"' : ''; ?> name="featuredactive[<?php echo $f->itemid; ?>]" /> Check to active</label></col-right>
            </row>
            <row>
              <col-left><label>Start: </label><input type="text" class="datepicker" name="startdate[]" value="<?php echo date('Y-m-d', strtotime($f->featuredstartdate)); ?>" /></col-left>
              <col-right><label>End: </label><input type="text" class="datepicker" name="enddate[]" value="<?php echo date('Y-m-d', strtotime($f->featuredenddate)); ?>" /></col-right>
            </row>
            <row>
              <col-full>
                <label>Image: </label>
                <input type="text" name="featuredimage[]" value="<?php echo $f->image; ?>" class="regular-text" style="width:61.5%">
                <input type="button" name="upload-btn" class="button-secondary" value="Upload Image" onclick="get_image_gallery(jQuery(this))">
              </col-full>
            </row>
            <row>
              <col-full>
                <label>Subject: </label>
                <select name="featuredlink[]">
                  <?php
                    foreach ($data["subjects"] as $s) {
                      ?>
                      <option value="<?php echo $s->subject; ?>" <?php echo ( $s->subject == $f->link ? 'selected="selected"' : '' ); ?>><?php echo $s->displayname; ?></option>
                    <?php } ?>
                </select>
              </col-full>
            </row>
            <row>
              <col-full><label>Text: </label><textarea placeholder="Featured Text" name="featuredtext[]" ><?php echo $f->featuredtext; ?></textarea></col-full>
            </row>
            <row>
              <col-left>
                <label>Featured: </label><label style="width: 68%;text-align: left; "><input type="checkbox" value="1" <?php echo $f->featured == 'T' ? 'checked="checked"' : ''; ?> name="featuredfeatured[<?php echo $f->itemid; ?>]" /> Check to feature</label>
              </col-left>
              <col-right>
                <label>URL: </label>
                <span style="margin-left: 0;">
                  <strong>
                    <a style="color: #0073aa;" target="_blank" href="<?php echo $itemUrl; ?>"><?php echo $itemTitle; ?></a>
                  </strong>
                </span>
              </col-right>
            </row>
            <row>
              <col-full>
                  <label><strong><a href="#" class="ml-link lnk-<?php echo $f->featureditemid; ?>">Multilingual</a></strong></label>
              </col-full>
            </row>
            <row id="ml-row-<?php echo $f->featureditemid; ?>" class="hide-ml-row">
                <col-full>
                  <label class="md-lbl">Language: </label>
                  <select name="ml_language_<?php echo $f->featureditemid; ?>" id="ml_language_<?php echo $f->featureditemid; ?>" class="ml_language_select">
                      <option value="" >--Select--</option>
                      <!--<option value="eng" >English</option>-->
                      <option value="spa" >Spanish</option>
                  </select>
                  <span id="ml-loading-<?php echo $f->featureditemid; ?>" class="hide-ml-row" style="margin-left: 10px;">Loading...</span>
                </col-full>
                <col-full>
                  <label class="md-lbl">Title: </label>
                  <input type="text" name="ml_displaytitle_<?php echo $f->featureditemid; ?>" id="ml_displaytitle_<?php echo $f->featureditemid; ?>" value="" />
                </col-full>
                <col-full>
                  <label class="md-lbl">Featured Text: </label>
                  <textarea name="ml_featuredtext_<?php echo $f->featureditemid; ?>" id="ml_featuredtext_<?php echo $f->featureditemid; ?>"></textarea>
                </col-full>
                <col-left>
                    <input type="button" name="mlsav_<?php echo $f->featureditemid; ?>" name="mlsav_<?php echo $f->featureditemid; ?>" class="mlSaveBtn" value="Save" />
                </col-left>
            </row>
            </li>
          <?php } ?>
        </ul>
        <br/>
        <input type="submit" class="btn" name="Save" value="Save" />
      </form>
      <div style="clear:both;"></div>
    </div>
  </div><!------ Featured Collections end -------->

  <!----------  Featured Partners --------------->
  <div id="tabs-10">
    <div >
      <h3>Featured Partners</h3>
      <form action="" method="post" class="searchForm" data-div='featured_homepage_partners' >
        <label>Partner URL: </label><input type="text" name="query" class="query" />
        <input type="hidden" name="action" value="get_collection" class="action" /><input  class="button" type="submit" value="Add" />
      </form>
      <hr/>
      <form action="admin.php?page=mark_featured#tabs-10" method="post" enctype="multipart/form-data" >
        <input type="hidden" name="submit" value="true" />
        <input type="hidden" name="location" value="homepagepartner" />
        <!--input type="hidden" name="itemidtype" value="resource" /-->
        <ul id="featured_homepage_partners" class="sortable">
          <?php
          foreach ($data["featured"] as $f) {
            if ($f->location != 'homepagepartner')
              continue;

            $itemUrl = '';
            $itemTitle = '';
            if ($f->itemidtype == 'collection') {
              $q_resource = "SELECT * FROM resources WHERE resourceid = '" . $f->itemid . "'";
              $resource = $wpdb->get_row($q_resource);
              $itemUrl = get_bloginfo('url') . '/oer/' . $resource->pageurl;
              $itemTitle = $resource->title;
            } else if ($f->itemidtype == 'community') {
              $q_community = "SELECT * FROM communities WHERE communityid = '" . $f->itemid . "'";
              $community = $wpdb->get_row($q_community);
              $itemUrl = get_bloginfo('url') . '/community/' . $community->url;
              $itemTitle = $community->name;
            }
            ?>
            <li class="ui-state-default">
              <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
              <input type="hidden" value="<?php echo $f->itemid; ?>" class="featured_id" name="featuredid[]" />
              <input type="hidden" value="<?php echo $f->itemidtype; ?>" class="featured_id" name="itemidtype[]" />
              <span class="ui-icon-close ui-button-icon-primary ui-icon ui-icon-closethick" onclick="return remove_featured(jQuery(this));"></span>
            <row>
              <col-left><label>Title: </label><input type="text" value="<?php echo htmlspecialchars($f->displaytitle); ?>" placeholder="Featured Title" class="featured_title" name="featuredtitle[]" /></col-left>
              <col-right><label>Active: </label><label style="width: 68%;text-align: left; "><input type="checkbox" value="1" <?php echo $f->active == 'T' ? 'checked="checked"' : ''; ?> name="featuredactive[<?php echo $f->itemid; ?>]" /> Check to active</label></col-right>
            </row>
            <row>
              <col-left><label>Start: </label><input type="text" class="datepicker" name="startdate[]" value="<?php echo date('Y-m-d', strtotime($f->featuredstartdate)); ?>" /></col-left>
              <col-right><label>End: </label><input type="text" class="datepicker" name="enddate[]" value="<?php echo date('Y-m-d', strtotime($f->featuredenddate)); ?>" /></col-right>
            </row>
            <row>
              <col-full>
                <label>Image: </label>
                <input type="text" name="featuredimage[]" value="<?php echo $f->image; ?>" class="regular-text" style="width:61.5%">
                <input type="button" name="upload-btn" class="button-secondary" value="Upload Image" onclick="get_image_gallery(jQuery(this))">
              </col-full>
            </row>
            <row>
              <col-full><label>Text: </label><textarea placeholder="Featured Text" name="featuredtext[]" ><?php echo $f->featuredtext; ?></textarea></col-full>
            </row>
            <row>
              <col-full>
                <label>URL: </label>
                <span style="margin-left: 0;">
                  <strong>
                    <a style="color: #0073aa;" target="_blank" href="<?php echo $itemUrl; ?>"><?php echo $itemTitle; ?></a>
                  </strong>
                </span>
              </col-full>
            </row>
            <row>
              <col-full>
                  <label><strong><a href="#" class="ml-link lnk-<?php echo $f->featureditemid; ?>">Multilingual</a></strong></label>
              </col-full>
            </row>
            <row id="ml-row-<?php echo $f->featureditemid; ?>" class="hide-ml-row">
                <col-full>
                  <label class="md-lbl">Language: </label>
                  <select name="ml_language_<?php echo $f->featureditemid; ?>" id="ml_language_<?php echo $f->featureditemid; ?>" class="ml_language_select">
                      <option value="" >--Select--</option>
                      <!--<option value="eng" >English</option>-->
                      <option value="spa" >Spanish</option>
                  </select>
                  <span id="ml-loading-<?php echo $f->featureditemid; ?>" class="hide-ml-row" style="margin-left: 10px;">Loading...</span>
                </col-full>
                <col-full>
                  <label class="md-lbl">Title: </label>
                  <input type="text" name="ml_displaytitle_<?php echo $f->featureditemid; ?>" id="ml_displaytitle_<?php echo $f->featureditemid; ?>" value="" />
                </col-full>
                <col-full>
                  <label class="md-lbl">Featured Text: </label>
                  <textarea name="ml_featuredtext_<?php echo $f->featureditemid; ?>" id="ml_featuredtext_<?php echo $f->featureditemid; ?>"></textarea>
                </col-full>
                <col-left>
                    <input type="button" name="mlsav_<?php echo $f->featureditemid; ?>" name="mlsav_<?php echo $f->featureditemid; ?>" class="mlSaveBtn" value="Save" />
                </col-left>
            </row>
            </li>
          <?php } ?>
        </ul>
        <br/>
        <input type="submit" class="btn" name="Save" value="Save" />
      </form>
      <div style="clear:both;"></div>
    </div>
  </div><!------ Featured Partners end -------->
</div>


<span id="sample_sample" style="display:none">
  <li class="ui-state-default">
    <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
    <input type="hidden" value="ITEM_ID" class="featured_id" name="featuredid[]" />
    <input type="hidden" value="ITEMID_TYPE" class="featured_id" name="itemidtype[]" />
    <span class="ui-icon-close ui-button-icon-primary ui-icon ui-icon-closethick" onclick="return remove_featured(jQuery(this));"></span>
  <row>
    <col-left><label>Title: </label><input type="text" value="ITEM_NAME" placeholder="Featured Title" class="featured_title" name="featuredtitle[]" /></col-left>
    <col-right><label>Active: </label><label style="width: 68%;text-align: left; "><input type="checkbox" value="1" name="featuredactive[ITEM_ID]" /> Check to active</label></col-right>
  </row>
  <row>
    <col-left><label>Start: </label><input type="text" class="datepicker" name="startdate[]" value="<?php echo date('Y-m-d'); ?>" /></col-left>
    <col-right><label>End: </label><input type="text" class="datepicker" name="enddate[]" value="<?php echo date('Y-m-d'); ?>" /></col-right>
  </row>
  <row>
    <col-full><label>Text: </label><textarea placeholder="Featured Text" name="featuredtext[]">ITEM_DESC</textarea></col-full>
  </row>
</li>
</span>

<span id="sample_image" style="display:none">
  <li class="ui-state-default">
    <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
    <input type="hidden" value="ITEM_ID" class="featured_id" name="featuredid[]" />
    <input type="hidden" value="ITEMID_TYPE" class="featured_id" name="itemidtype[]" />
    <span class="ui-icon-close ui-button-icon-primary ui-icon ui-icon-closethick" onclick="return remove_featured(jQuery(this));"></span>
  <row>
    <col-left><label>Title: </label><input type="text" value="ITEM_NAME" placeholder="Featured Title" class="featured_title" name="featuredtitle[]" /></col-left>
    <col-right><label>Active: </label><label style="width: 68%;text-align: left; "><input type="checkbox" value="1" name="featuredactive[ITEM_ID]" /> Check to active</label></col-right>
  </row>
  <row>
    <col-left><label>Start: </label><input type="text" class="datepicker" name="startdate[]" value="<?php echo date('Y-m-d'); ?>" /></col-left>
    <col-right><label>End: </label><input type="text" class="datepicker" name="enddate[]" value="<?php echo date('Y-m-d'); ?>" /></col-right>
  </row>
  <row>
    <col-full>
      <label>Image: </label>
      <input type="text" name="featuredimage[]" value="" class="regular-text" style="width:61.5%">
      <input type="button" name="upload-btn" class="button-secondary" value="Upload Image" onclick="get_image_gallery(jQuery(this))">
    </col-full>
  </row>
  <row>
    <col-full><label>Text: </label><textarea value="" placeholder="Featured Text" name="featuredtext[]" />ITEM_DESC</textarea></col-full>
  </row>
</li>
</span>

<span id="sample_featured_collections" style="display:none">
  <li class="ui-state-default">
    <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
    <input type="hidden" value="ITEM_ID" class="featured_id" name="featuredid[]" />
    <input type="hidden" value="ITEMID_TYPE" class="featured_id" name="itemidtype[]" />
    <span class="ui-icon-close ui-button-icon-primary ui-icon ui-icon-closethick" onclick="return remove_featured(jQuery(this));"></span>
  <row>
    <col-left><label>Title: </label><input type="text" value="ITEM_NAME" placeholder="Featured Title" class="featured_title" name="featuredtitle[]" /></col-left>
    <col-right><label>Active: </label><label style="width: 68%;text-align: left; "><input type="checkbox" value="1" name="featuredactive[ITEM_ID]" /> Check to active</label></col-right>
  </row>
  <row>
    <col-left><label>Start: </label><input type="text" class="datepicker" name="startdate[]" value="<?php echo date('Y-m-d'); ?>" /></col-left>
    <col-right><label>End: </label><input type="text" class="datepicker" name="enddate[]" value="<?php echo date('Y-m-d'); ?>" /></col-right>
  </row>
  <row>
    <col-full>
      <label>Image: </label>
      <input type="text" name="featuredimage[]" value="" class="regular-text" style="width:61.5%">
      <input type="button" name="upload-btn" class="button-secondary" value="Upload Image" onclick="get_image_gallery(jQuery(this))">
    </col-full>
  </row>
  <row>
    <col-full>
      <label>Subject: </label>
      <select name="featuredlink[]">
        <?php
          foreach ($data["subjects"] as $s) {
            ?>
            <option value="<?php echo $s->subject; ?>"><?php echo $s->displayname; ?></option>
          <?php } ?>
      </select>
    </col-full>
  </row>
  <row>
    <col-full><label>Text: </label><textarea value="" placeholder="Featured Text" name="featuredtext[]" />ITEM_DESC</textarea></col-full>
  </row>
  <row>
    <col-left>
      <label>Featured: </label><label style="width: 68%;text-align: left; "><input type="checkbox" value="1" name="featuredfeatured[ITEM_ID]" /> Check to feature</label>
    </col-left>
  </row>
</li>
</span>

<span id="sample_featured_homepage_partners" style="display:none">
  <li class="ui-state-default">
    <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
    <input type="hidden" value="ITEM_ID" class="featured_id" name="featuredid[]" />
    <input type="hidden" value="ITEMID_TYPE" class="featured_id" name="itemidtype[]" />
    <span class="ui-icon-close ui-button-icon-primary ui-icon ui-icon-closethick" onclick="return remove_featured(jQuery(this));"></span>
    <row>
      <col-left><label>Title: </label><input type="text" value="ITEM_NAME" placeholder="Featured Title" class="featured_title" name="featuredtitle[]" /></col-left>
      <col-right><label>Active: </label><label style="width: 68%;text-align: left; "><input type="checkbox" value="1" name="featuredactive[ITEM_ID]" /> Check to active</label></col-right>
    </row>
    <row>
      <col-left><label>Start: </label><input type="text" class="datepicker" name="startdate[]" value="<?php echo date('Y-m-d'); ?>" /></col-left>
      <col-right><label>End: </label><input type="text" class="datepicker" name="enddate[]" value="<?php echo date('Y-m-d'); ?>" /></col-right>
    </row>
    <row>
      <col-full>
        <label>Image: </label>
        <input type="text" name="featuredimage[]" value="" class="regular-text" style="width:61.5%">
        <input type="button" name="upload-btn" class="button-secondary" value="Upload Image" onclick="get_image_gallery(jQuery(this))">
      </col-full>
    </row>
    <row>
      <col-full><label>Text: </label><textarea value="" placeholder="Featured Text" name="featuredtext[]" />ITEM_DESC</textarea></col-full>
    </row>
  </li>
</span>

<span id="sample_dashboard_resources" style="display:none">
  <li class="ui-state-default">
    <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
    <input type="hidden" value="ITEM_ID" class="featured_id" name="featuredid[]" />
    <input type="hidden" value="ITEMID_TYPE" class="featured_id" name="itemidtype[]" />
    <span class="ui-icon-close ui-button-icon-primary ui-icon ui-icon-closethick" onclick="return remove_featured(jQuery(this));"></span>
  <row>
    <col-left><label>Title: </label><input type="text" value="ITEM_NAME" placeholder="Featured Title" class="featured_title" name="featuredtitle[]" /></col-left>
    <col-right><label>Active: </label><label style="width: 68%;text-align: left; "><input type="checkbox" value="1" name="featuredactive[ITEM_ID]" /> Check to active</label></col-right>
  </row>
  <row>
    <col-left><label>Start: </label><input type="text" class="datepicker" name="startdate[]" value="<?php echo date('Y-m-d'); ?>" /></col-left>
    <col-right><label>End: </label><input type="text" class="datepicker" name="enddate[]" value="<?php echo date('Y-m-d'); ?>" /></col-right>
  </row>
  <row>
    <col-full>
      <label>Image: </label>
      <input type="text" name="featuredimage[]" value="" class="regular-text" style="width:61.5%">
      <input type="button" name="upload-btn" class="button-secondary" value="Upload Image" onclick="get_image_gallery(jQuery(this))">
    </col-full>
  </row>
</li>
</span>


<span id="sample_dashboard_members" style="display:none">
  <li class="ui-state-default">
    <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
    <input type="hidden" value="ITEM_ID" class="featured_id" name="featuredid[]" />
    <input type="hidden" value="ITEMID_TYPE" class="featured_id" name="itemidtype[]" />
    <span class="ui-icon-close ui-button-icon-primary ui-icon ui-icon-closethick" onclick="return remove_featured(jQuery(this));"></span>
  <row>
    <col-left><label>Title: </label><input type="text" value="ITEM_NAME" placeholder="Featured Title" class="featured_title" name="featuredtitle[]" /></col-left>
    <col-right><label>Active: </label><label style="width: 68%;text-align: left; "><input type="checkbox" value="1" name="featuredactive[ITEM_ID]" /> Check to active</label></col-right>
  </row>
  <row>
    <col-left><label>Start: </label><input type="text" class="datepicker" name="startdate[]" value="<?php echo date('Y-m-d'); ?>" /></col-left>
    <col-right><label>End: </label><input type="text" class="datepicker" name="enddate[]" value="<?php echo date('Y-m-d'); ?>" /></col-right>
  </row>
</li>
</span>


<span id="sample_partner" style="display:none">
  <li class="ui-state-default">
    <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
    <input type="hidden" value="ITEM_ID" class="featured_id" name="featuredid[]" />
    <input type="hidden" value="ITEMID_TYPE" class="featured_id" name="itemidtype[]" />
    <span class="ui-icon-close ui-button-icon-primary ui-icon ui-icon-closethick" onclick="return remove_featured(jQuery(this));"></span>
  <row>
    <col-left><label>Title: </label><input type="text" value="ITEM_NAME" placeholder="Featured Title" class="featured_title" name="featuredtitle[]" /></col-left>
    <col-right><label>Active: </label><label style="width: 68%;text-align: left; "><input type="checkbox" value="1" name="featuredactive[ITEM_ID]" /> Check to active</label></col-right>
  </row>
  <row>
    <col-left><label>Start: </label><input type="text" class="datepicker" name="startdate[]" value="<?php echo date('Y-m-d'); ?>" /></col-left>
    <col-right><label>End: </label><input type="text" class="datepicker" name="enddate[]" value="<?php echo date('Y-m-d'); ?>" /></col-right>
  </row>
  <row>
    <col-full><label>link: </label><input type="text" name="featuredlink[]" value="" placeholder="http://" style="width: 77%" /></col-full>
  </row>
  <row>
    <col-full>
      <label>Image: </label>
      <input type="text" name="featuredimage[]" value="" class="regular-text" style="width:61.5%">
      <input type="button" name="upload-btn" class="button-secondary" value="Upload Image" onclick="get_image_gallery(jQuery(this))">
    </col-full>
  </row>
  <row>
    <col-full><label>Text: </label><textarea value="" placeholder="Featured Text" name="featuredtext[]" >ITEM_DESC</textarea></col-full>
  </row>
</li>
</span>


<?php
wp_enqueue_script('jquery');
wp_enqueue_media();
?>

<script type="text/javascript">
  function get_image_gallery($btn) {
    var image = wp.media({
      title: 'Upload Image',
      multiple: false
    }).open()
            .on('select', function (e) {
              var uploaded_image = image.state().get('selection').first();
              var image_url = uploaded_image.toJSON().url;
              $btn.parent().find('.regular-text').val(image_url)
              //$('#image_url').val(image_url);
            });
  }

  jQuery(document).ready(function ($) {
    var hash = window.location.hash;
    $('a[href="' + hash + '"]').click();
    window.scrollTo(0, 0);
<?php
if ($msg) {
  echo "alert('" . $msg . "');";
}
?>
  });
</script>