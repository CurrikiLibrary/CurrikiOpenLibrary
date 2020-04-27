<?php
global $wpdb;
$community_anchors = new stdClass();
$community_anchors->anchorid = 0;
$community_anchors->title = "";
$community_anchors->tagline = "";
$community_anchors->content = "";
$community_anchors->displayseqno = 0;
$community_anchors->type = "default";
if( isset($_GET["anchorid"]) && intval($_GET["anchorid"])>0 )
{
    $community_anchors = $wpdb->get_row("SELECT * FROM community_anchors where anchorid = " . $_REQUEST['anchorid'],OBJECT);
}
?>

<style type="text/css">
    .color-option
    {
        width: auto !important;
        padding: 5px !important;        
    }
    .color-option label
    {
        display: inline !important;
    }
</style>

<script type="text/javascript">
    jQuery('document').ready(function(e){
        /*
        $(document).keypress(
            function(event){
             if (event.which == '13') {
                event.preventDefault();
              }
        });
        */
       
        window.init_form_submit = false;        
        jQuery("#submit_anchors_form").click(function(eb){            
            if(!window.init_form_submit)
            {
                window.init_form_submit = true;
                jQuery("#manage_pages_form").submit(function(ef){
                    ef.preventDefault();               
                    var errors = [];
                    jQuery( this ).serializeArray().forEach(x => {                                                    
                                                        if( x.name === "anchor_title" && x.value.length === 0 )
                                                        {        
                                                            errors.push("Please enter Title!");
                                                        }
                                                        if( x.name === "anchor_tagline" && x.value.length === 0 )
                                                        {        
                                                            errors.push("Please enter Tagline!");
                                                        }
                                                        /*
                                                        if( x.name === "anchor_content" && x.value.length === 0 )
                                                        {                                                        
                                                            errors.push("Please enter Content!");
                                                        }
                                                        */
                                                    });

                    if(errors.length > 0)
                    {
                        var msg = "";
                        errors.forEach(er => msg += (er+"\n"));
                        alert(msg);
                    }else{                        
                        
                        
                        var anchor_obj = {anchor_title:"",anchor_content:"",displayseqno:"",communityid:"",anchor_type:"",anchor_tagline:""};
                        
                        jQuery( this ).serializeArray().forEach(x => {
                                                        if( x.name === "communityid" )
                                                        {        
                                                            anchor_obj.communityid = x.value;
                                                        }
                                                        if( x.name === "anchor_title" )
                                                        {        
                                                            anchor_obj.anchor_title = x.value;
                                                        }
                                                        if( x.name === "anchor_tagline" )
                                                        {        
                                                            anchor_obj.anchor_tagline = x.value;
                                                        }
                                                        if( x.name === "anchor_content" )
                                                        {        
                                                            anchor_obj.anchor_content = x.value;
                                                        }                                                        
                                                        if( x.name === "anchor_displayseqno" )
                                                        {        
                                                            anchor_obj.displayseqno = x.value;
                                                        }
                                                        if( x.name === "anchorid" )
                                                        {
                                                            anchor_obj.anchorid = x.value;
                                                        }
                                                        if( x.name === "anchor_type" )
                                                        {
                                                            anchor_obj.anchor_type = x.value;                                                            
                                                        }
                                                    });                                                              
                        var t = new Date().valueOf();
                        
                        jQuery.ajax({
                            method: "POST",
                            url: ajaxurl + "?t="+ t,
                            data: {action:"update_community_anchor", anchor_obj:anchor_obj, return_url: "<?php echo urlencode(site_url().$_SERVER["REQUEST_URI"]); ?>"}
                        })
                        .done( function( redirect_url ) {                            
                            window.location = redirect_url;                            
                        });
                        
                    }

                });
            }                        
        });
        
        <?php if( isset($_GET["tab"]) && $_GET["tab"] === "tabs-2" ) { ?>
                jQuery("#manage_pages_form").submit(function(ef){
                    ef.preventDefault(); 
                });
        <?php } ?>
    });
</script>
<div class="anchors-wrapper">
    <h3>Anchors</h3>
    <input type="hidden" name="anchorid" id="anchorid" value="<?php echo $community_anchors->anchorid; ?>" />
    <div style="border: 0px solid red; height: 65px">
        <div class="col-wrap">
          <div class="form-wrap">                                                  
              <div class="form-field form-required term-name-wrap">
                <label for="anchor_title">Title</label>
                <input name="anchor_title" id="anchor_title" type="text" value="<?php echo $community_anchors->title; ?>" size="400" aria-required="true" style="width: 400px;" />
              </div>                            
          </div>
        </div>
        <div class="col-wrap">
          <div class="form-wrap">                                                  
              <div class="form-field form-required term-name-wrap">
                <label for="anchor_tagline">Tagline</label>
                <input name="anchor_tagline" id="anchor_tagline" type="text" value="<?php echo $community_anchors->tagline; ?>" size="400" aria-required="true" style="width: 400px;" />
              </div>                            
          </div>
        </div>
        <div class="col-wrap">
          <div class="form-wrap">                                                  
              <div class="form-field form-required term-name-wrap">
                <label for="anchor_content">Content</label>                
                <textarea name="anchor_content" id="anchor_content" rows="3" cols="45" style="width: auto;"><?php echo $community_anchors->content; ?></textarea>
              </div>                            
          </div>
        </div>
        <div class="col-wrap">
          <div class="form-wrap">                                                  
              <div class="form-field form-required term-name-wrap">
                <label for="anchor_displayseqno">Display Order</label>
                <input name="anchor_displayseqno" id="anchor_displayseqno" type="text" value="<?php echo $community_anchors->displayseqno; ?>" size="400" aria-required="true" style="width: 400px;" />
              </div>                            
          </div>
        </div>
        <div class="col-wrap">
          <div class="form-wrap">                                                  
              <div class="form-field form-required term-name-wrap">
                <label for="anchor_type">Anchor Type</label>                
                <fieldset class="scheme-list" id="color-picker">
                        <div class="color-option <?php echo !$community_anchors->type || $community_anchors->type==='default' ? "selected":"";?>">
                            <label for="admin_color_fresh"><input type="radio" class="tog" value="default" id="admin_color_fresh" name="anchor_type" <?php echo !$community_anchors->type || $community_anchors->type==='default' ? "checked=checked":"";?> /> Default</label>
			</div>
                        <div class="color-option <?php echo $community_anchors->type==='collections' ? "selected":"";?>">
                            <label for="admin_color_light"><input type="radio" class="tog" value="collections" id="admin_color_light" name="anchor_type" <?php echo $community_anchors->type==='collections' ? "checked=checked":"";?> /> Collections</label>
			</div>
                        <div class="color-option <?php echo $community_anchors->type==='groups' ? "selected":"";?>">
                            <label for="admin_color_bbp-evergreen"><input type="radio" class="tog" value="groups" id="admin_color_bbp-evergreen" name="anchor_type" <?php echo $community_anchors->type==='groups' ? "checked=checked":"";?> /> Groups</label>
			</div>
                </fieldset>
              </div>                            
          </div>
        </div>
        <div class="col-wrap">
          <div class="form-wrap">                                                  
              <div class="form-field form-required term-name-wrap">
                <input type="submit" name="submit" id="submit_anchors_form" class="button button-primary" value="Save">
              </div>                            
          </div>
        </div>
        
        <div class="col-wrap">
            <div class="form-wrap">
                <div class="form-field form-required term-name-wrap">
                    <?php 
                        if( isset($_GET["tab"]) &&  $_GET["tab"] === "tabs-2")
                        {
                            require_once 'list_anchors.php'; 
                        }
                    ?>
                </div>                            
            </div>
        </div>
        
    </div>          
</div>