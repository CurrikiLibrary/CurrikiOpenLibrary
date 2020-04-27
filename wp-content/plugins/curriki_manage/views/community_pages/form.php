<?php
//global $tab;
global $community,$message,$action_text,$tab; 
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css" />
<!--<script src="//code.jquery.com/jquery-1.10.2.js"></script>-->
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.4/css/jquery.dataTables.min.css">
<script type="text/javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>

<style type="text/css">
    #tabs
    {
        width: 70%;        
    }
    .msg-class
    {
        width: 66%;
        margin-bottom: 15px !important;
    }
    .groups-align{
        float: left;
    }
    .groups-wrapper
    {
        min-height: 800px;
    }
    .anchors-wrapper
    {
        min-height: 1300px;
    }
    .collections-align{
        float: left;
    }
    .collections-wrapper
    {
        min-height: 1300px;
    }
</style>

<script type="text/javascript">
    <?php 
        $tab_arr = explode("-", $tab);
        $tab_index = $tab_arr[count($tab_arr)-1] -1;
        
    ?>
    
    var tabs_to_disable = <?php echo $action_text === "Add" ? "[1,2,3]":"[]"?>;
    var tab_index = <?php echo $tab_index; ?>;
    jQuery("document").ready(function(){        
        jQuery("#tabs").tabs({
            activate: function( event, ui ) {
                //console.log( "event = ", event.currentTarget.baseURI+"&tab="+event.currentTarget.hash.replace("#","") );
                var paged_str = "<?php echo isset($_GET["paged"]) ? "&paged={$_GET["paged"]}" : "" ?>";
                var tab_str = "<?php echo isset($_GET["tab"]) ? "&tab={$_GET["tab"]}" : "" ?>";                
                var rdr = event.currentTarget.baseURI.replace(paged_str,"").replace(tab_str,"");
                rdr +="&tab="+event.currentTarget.hash.replace("#","") ;                                                                
                jQuery(ui.newPanel[0]).html( jQuery("#loader").clone().show() ).css("text-align","center").css("height","500px").css("margin-top","180px");
                window.location = rdr;
            },
            active: tab_index,
            disabled: tabs_to_disable
        });
                
        jQuery("input.groups-display-order").keyup(function(e){
            var field = this;
            var order_value = jQuery(field).val();
            var field_name = jQuery(field).attr("name");
            var communityid = field_name.split("-")[0];
            var groupid = field_name.split("-")[1];
            var t = new Date().valueOf();            
            
            jQuery.ajax({
                method: "POST",
                url: ajaxurl + "?t="+ t,
                data: {action:"update_community_group_order",order_value:order_value,communityid:communityid,groupid:groupid}
            })
            .done( function( data ) {
                //console.log( "---aaaaaaaaaa--->> " , data);
                //jQuery(field).val(data);
            });
            
        });                
        jQuery("input.collections-display-order").keyup(function(e){
            var field = this;
            var order_value = jQuery(field).val();
            var field_name = jQuery(field).attr("name");
            var communityid = field_name.split("-")[0];
            var resourceid = field_name.split("-")[1];
            var t = new Date().valueOf();            
            
            jQuery.ajax({
                method: "POST",
                url: ajaxurl + "?t="+ t,
                data: {action:"update_community_collection_order",order_value:order_value,communityid:communityid,resourceid:resourceid}
            })
            .done( function( data ) {
                //console.log( "---aaaaaaaaaa--->> " , data);
                //jQuery(field).val(data);
            });
            
        });                
        
    });
</script>

<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<h1><?php echo $action_text; ?> Community Page</h1>

<a href="<?php echo admin_url() ?>admin.php?page=community_pages" class="button button-primary">Back to list</a> 
&nbsp;
<?php if( $community && property_exists($community, "communityid") && intval($community->communityid)>0 ){ ?>
    <a href="<?php echo site_url() ?>/community/<?php echo $community->url; ?>" target="__blank" class="button button-primary"><strong>Preview Page</strong></a>
<?php } ?>
    
<br /><br />

<?php if(strlen($message) > 0 ) { ?>
          <div class="<?php echo $message_class; ?> msg-class"><strong><?php echo $message; ?></strong></div>
<?php } ?>


<img src="<?php echo get_stylesheet_directory_uri()."/images/loader.gif" ?>" name="loader" id="loader" alt="loader" style="display: none;margin: 0 auto;" />

<div id="tabs">
    <ul>
        <li><a href="#tabs-1">Main</a></li>
        <li><a href="#tabs-2">Anchors</a></li>
        <li><a href="#tabs-3">Groups</a></li>        
        <li><a href="#tabs-4 ">Collections</a></li>        
    </ul>
<form id="manage_pages_form" method="post" action="<?php echo site_url().$_SERVER["REQUEST_URI"] . '&tab='. $tab .'&time=' . time(); ?>" class="validate" enctype="multipart/form-data">
    <input type="hidden" name="tab_action" value="<?php echo isset($_GET["tab_action"]) && strlen($_GET["tab_action"]) > 0  ?  $_GET["tab_action"]:""; ?>" />
    <div id="tabs-1">
        <div>
          <h3>Main Setting</h3>
          <div id="col-left" class="postbox" style="margin-top:20px;width: 60%; min-width: 500px;">
                <div class="col-wrap">
                  <div class="form-wrap">                                        
                      <input type="hidden" name="action" value="uploaded">

                      <input type="hidden" name="communityid" value="<?php echo $community && property_exists($community, "communityid") ? $community->communityid:""; ?>">

                      <div class="form-field form-required term-name-wrap">
                        <label for="name">Name</label>
                        <input name="name" id="name" type="text" value="<?php echo $community ? $community->name:""; ?>" size="400" aria-required="true" style="width: 400px;">
              <!--          <p>Resource id against which you want to insert the selected files below.</p>-->
                      </div>
                      <div class="form-field form-required term-name-wrap">
                        <label for="tagline">Tagline</label>
                        <input name="tagline" id="tagline" type="text" value="<?php echo $community ? str_replace("\\","",$community->tagline):""; ?>" size="400" aria-required="true" style="width: 400px;">
                          <!--<p>Resource id against which you want to insert the selected files below.</p>-->
                      </div>

                      <div class="form-field form-required term-name-wrap">
                        <label for="url">Url</label>
                        <input name="url" id="url" type="text" value="<?php echo $community ? $community->url:""; ?>" size="400" aria-required="true" style="width: 400px;">
                          <!--<p>Resource id against which you want to insert the selected files below.</p>-->
                      </div>
                      
                      <div class="form-field form-required term-name-wrap">
                        <label for="image">Main Image</label>
                        <input name="image" id="resourcefiles" type="file" aria-required="true" multiple="multiple" >
                        <?php echo $community && strlen($community->image) > 0 ? "<p>File: {$community->image}</p>":""; ?>
                        <p>Required file size is <strong>(1440px X 499px)</strong></p>
                      </div>

                      <div class="form-field form-required term-name-wrap">
                        <label for="logo">Logo</label>
                        <input name="logo" id="resourcefiles" type="file" aria-required="true" multiple="multiple" >
                        <?php echo $community && strlen($community->logo) > 0 ? "<p>File: {$community->logo}</p>":""; ?>
                        <p>Required file size is equal or less then to <strong>(438px X 150px)</strong></p>
                      </div>

                      <p class="submit">
                        <input type="submit" name="submit" id="submit" class="button button-primary" value="Save">
                        <span class="spinner"></span>
                      </p>                    
                  </div>

                </div>
              </div>
        </div>
    </div>    
    
    <div id="tabs-2">
        <?php require_once 'anchors_section.php'; ?>        
    </div>
    
    <div id="tabs-3">
        <div class="groups-wrapper">
          <h3>Groups</h3>          
          <div style="border: 0px solid red; height: 65px">
              <div class="col-wrap groups-align">
                <div class="form-wrap">                                                  
                    <div class="form-field form-required term-name-wrap">
                      <label for="group-slug">Enter Slug</label>
                      <input name="groupslug" id="groupslug" type="text" value="<?php // echo $community ? $community->url:""; ?>" size="400" aria-required="true" style="width: 400px;">                    
                    </div>                            
                </div>
              </div>
              <div class="col-wrap groups-align">
                <div class="form-wrap">                                                  
                    <div class="form-field form-required term-name-wrap">
                      <label for="displayseqno-slug">Display Order</label>
                      <input name="displayseqno" id="displayseqno" type="text" value="<?php // echo $community ? $community->url:""; ?>" size="400" aria-required="true" style="width: 400px;">                    
                    </div>                            
                </div>
              </div>
          </div>          
          
          <input type="submit" name="submit" id="submit" class="button button-primary" value="Save">
          
          <div class="col-wrap">
            <div class="form-wrap">                                                  
                <div class="form-field form-required term-name-wrap">
                    <?php 
                        if( isset($_GET["tab"]) &&  $_GET["tab"] === "tabs-3")
                        {
                            require_once 'list_groups.php'; 
                        }
                    ?>
                </div>                            
            </div>
          </div>
          
        </div>
    </div>    
    
    <div id="tabs-4">
        <?php require_once 'collections_section.php'; ?>
    </div>
    
</form> 
</div>