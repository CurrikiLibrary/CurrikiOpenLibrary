<?php
global $wpdb;
$community_collections = new stdClass();
$community_collections->url = "";
$community_collections->title = "";
$community_collections->image = "";
$community_collections->displayseqno = 0;
$community_collections->resourceid = 0;

if( isset($_GET["tab_action"]) && $_GET["tab_action"] === "edit_collection" )
{            
    $community_collections = $wpdb->get_row("SELECT cc.*,c.pageurl as url FROM community_collections cc 
            join resources c on cc.resourceid = c.resourceid
            where cc.resourceid = " . $_REQUEST['resourceid'] . " and cc.communityid=".$_REQUEST['communityid'] ,OBJECT);              
}    
?>
<div class="collections-wrapper">
          <h3>Collections</h3> 
          <input type="hidden" name="collections_resourceid" value="<?php echo $community_collections->resourceid; ?>" />
          <div>
              
              <?php if($community_collections->resourceid === 0) { ?>
                <div class="col-wrap collections-alignx">
                  <div class="form-wrap">                                                  
                      <div class="form-field form-required term-name-wrap">
                        <label for="collection-slug">Enter Slug</label>
                        <input name="collectionslug" id="collectionslug" type="text" value="<?php echo $community_collections->url; ?>" size="400" aria-required="true" style="width: 400px;">                    
                      </div>                            
                  </div>
                </div>
              <?php }else{ ?>
                <div class="col-wrap collections-alignx">
                    <div class="form-wrap">                                                  
                        <div class="form-field form-required term-name-wrap">
                          <label for="collection-slug">Slug</label>
                          <?php echo $community_collections->url; ?>                          
                        </div>                            
                    </div>
                </div>
              <?php } ?>
              
              <div class="col-wrap collections-alignx">
                <div class="form-wrap">                                                  
                    <div class="form-field form-required term-name-wrap">
                      <label for="displayseqno_collection">Order</label>
                      <input name="displayseqno_collection" id="displayseqno_collection" type="text" value="<?php echo $community_collections->displayseqno; ?>" size="400" aria-required="true" style="width: 400px;">                    
                    </div>                            
                </div>
              </div>
              <div class="col-wrap collections-alignx">
                <div class="form-wrap">                                                  
                    <div class="form-field form-required term-name-wrap">
                      <label for="image_collection">Image</label>                      
                      <input name="image_collection" id="image_collection" type="file" aria-required="true" multiple="multiple" >
                        <?php echo $community_collections && strlen($community_collections->image) > 0 ? "<p>File: {$community_collections->image}</p>":""; ?>
                      <p>Required file size is <strong>(282px X 168px)</strong></p>
                    </div>                            
                </div>
              </div>
          </div>                    
          <input type="submit" name="submit" id="submit" class="button button-primary" value="Save">
          
          <div class="col-wrap">
            <div class="form-wrap">                                                  
                <div class="form-field form-required term-name-wrap">
                    <?php 
                        if( isset($_GET["tab"]) &&  $_GET["tab"] === "tabs-4")
                        {
                            require_once 'list_collections.php'; 
                        }                    
                    ?>
                </div>                            
            </div>
          </div>
          
        </div>