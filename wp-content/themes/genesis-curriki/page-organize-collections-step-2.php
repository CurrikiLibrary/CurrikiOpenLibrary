<?php
/*
* Template Name: Organize Collections Step 2
*
* Child Theme Name: Curriki Child Theme for Genesis 2.1
* Author: Orange Blossom Media
* Url: http://orangeblossommedia.com/
*/
if(!is_user_logged_in() and function_exists('curriki_redirect_login')){curriki_redirect_login();die;}

    global $wpdb;
    $myid = get_current_user_id();
//    $myid = 10000;
    $cid = addslashes($_POST['collectionid']);
    $q_user = "SELECT * FROM users WHERE userid = '".$myid."'";
    $user = $wpdb->get_row($q_user);
    
    $q_collection = "select * from resources where resourceid = '".$cid."' and contributorid = '".$myid."'";
    $collection = $wpdb->get_row($q_collection);
    //echo
    $q_resources = "select r.resourceid rid, r.resourceid resourceid, sa.subjectareaid, 
concat(s.displayname, '->', sa.displayname) sa_name, 
el.identifier,
it.displayname it_name, r.title, r.description

from resources r inner join collectionelements ce 
on r.resourceid = ce.resourceid ".

//"and r.contributorid = '".$myid."' ".
"

left outer join resource_subjectareas rsa
on rsa.resourceid = r.resourceid
left outer join subjectareas sa on rsa.subjectareaid = sa.subjectareaid
left outer join subjects s on s.subjectid = sa.subjectid

left outer join resource_educationlevels rel on rel.resourceid = r.resourceid
left outer join educationlevels el on el.levelid = rel.educationlevelid

left outer join resource_instructiontypes rit on rit.resourceid = r.resourceid
left outer join instructiontypes it on it.instructiontypeid = rit.instructiontypeid
where ce.collectionid = ".$cid." 
order by ce.displayseqno asc";
        $resources = $wpdb->get_results($q_resources);
    
	// Access
	$user_library = '<div class="pb-40">';
                $user_library .= '<h3 class="section-title">'.($collection->title ? stripslashes($collection->title) : 'Go to Collection' ).'</h3>';
                $user_library .= '<p>'.__('Drag and drop resources to reorder them. Click Remove to take a resource out of your collection.','curriki').'</p>';
    $user_library .= '</div>';

		$user_library .= '<div id="sortable-'.$cid.'" class="ui-sortable">';

			$library = '';
                if(!empty($resources)){
                    $resourcecount = 0;
                    $resource_prev = clone $resources[0];
                    $resource_next = clone $resources[0];
                    $resource_prev->rid = 0;
                    $resource_next->rid = 0;
                    $instructional_components = '';
                    foreach($resources as $resource){
                        $resource_next->rid = 0;
                        if(!empty($resources[$resourcecount+1])){
                            $resource_next = clone $resources[$resourcecount+1];
                        }else{
                            $resource_next->rid = 0;
                        }
                        if($resource->rid == $resource_next->rid){
                            //echo '<br />entered in if';
                            if($resource->sa_name != $resource_next->sa_name){
                                if($subjects != '')$subjects .= ', ';
                                $subjects .= $resource->sa_name;
                                //echo '<br />entered in $resource->sa_name != $resource_next->sa_name = '.$subjects;
                            }elseif(empty($subjects)){
                                $subjects = $resource->sa_name;
                            }
                            if($resource->identifier != $resource_next->identifier){
                                if($educationlevels != '')$educationlevels .= ', ';
                                $educationlevels .= $resource->identifier;
                                //echo '<br />entered in $resource->identifier != $resource_next->identifier = '.$educationlevels;
                            }elseif(empty($educationlevels)){
                                $educationlevels = $resource->identifier;
                            }
                            if($resource->it_name != $resource_next->it_name){
                                if(!empty($instructional_components))$instructional_components .= ', ';
                                $instructional_components .= $resource->it_name;
                                //echo '<br />entered in $resource->it_name != $resource_next->it_name = '.$instructional_components;
                            }elseif(!empty($instructional_components)){
                                $instructional_components = $resource->it_name;
                            }
                        }elseif($resource->rid == $resource_prev->rid){
                            //echo '<br />entered in elseif';
                            if($resource->sa_name != $resource_prev->sa_name){
                                if($subjects != '')$subjects .= ', ';
                                $subjects .= $resource->sa_name;
                                //echo '<br />entered in $resource->sa_name != $resource_prev->sa_name = '.$subjects;
                            }elseif($subjects == ''){
                                $subjects = $resource->sa_name;
                            }
                            if($resource->identifier != $resource_prev->identifier){
                                if($educationlevels != '')$educationlevels .= ', ';
                                $educationlevels .= $resource->identifier;
                                //echo '<br />entered in $resource->identifier != $resource_prev->identifier = '.$educationlevels;
                            }elseif($educationlevels == ''){
                                $educationlevels = $resource->identifier;
                            }
                            if($resource->it_name != $resource_prev->it_name){
                                if($instructional_components != '')$instructional_components .= ', ';
                                $instructional_components .= $resource->it_name;
                                //echo '<br />entered in $resource->it_name != $resource_next->it_name = '.$instructional_components;
                            }elseif($instructional_components == ''){
                                $instructional_components = $resource->it_name;
                            }
                        }else{
                            //echo '<br />entered in else';
                            $subjects = $resource->sa_name;
                            $educationlevels = $resource->identifier;
                            $instructional_components = $resource->it_name;
                        }
			// Collection - First Level
			$resource_first = '<div class="media media-secondary library-collection" id="'.$resource->resourceid.'">';
                                $resource_first .= '<div class="media-thumbnail"><img src="' . get_stylesheet_directory_uri() . '/images/collection-page/icon_file.png" width="26" height="35" alt="icon"></div>';
				$resource_first .= '<div class="media-body"><a href="'.get_bloginfo('url').'/oer/?rid='.$resource->resourceid.'" target="_blank" class="curriki_tooltip"><h5 class="media-title">'.($resource->title? stripslashes(strip_tags($resource->title)) : __('Go to Resource','curriki')).'</h5>';
                $resource_first .= '<div class="tooltip_description" style="display:none" title="'.($resource->title?$resource->title:__('Go to Resource','curriki')).'">'.__('Description','curriki').': '. strip_tags($resource->description);
                                
                                $resource_last = '</div></a></div>';
				$resource_last .= '<div class="action-buttons align-self-center">';
				$resource_last .= '<a  class="btn-remove" href="javascript:;" onclick="curriki_RemoveThisCollectionElement(\'#'.$resource->resourceid.'\');"><i class="fa fa-trash-o"></i></a>';
                                $resource_last .= '</div>';
			$resource_last .= '</div>';
                        
                        if($resource->rid != $resource_next->rid){
                            $library .= $resource_first.'<br /><br />Subjects: <br />'.$subjects.'<br /><br />Education Level(s): <br />'.$educationlevels.'<br /><br />Instructions Component Type(s): <br />'.$instructional_components.$resource_last;
                            $subjects = '';
                            $educationlevels = '';
                            $instructional_components = '';
                        }
                        $resource_prev = $resource;
                        $resourcecount++;
                    }
                }else{
                    $library .= "There are no resources in this Collection to organize.";
                }
			$user_library .= $library;

        $user_library .= '</div>';
                
		echo $user_library;
?>
<div class="buttonpane clearfix pt-60 pb-50">
<form id="seq-form-<?php echo $_POST['collectionid'];?>" action="?curriki_ajax_action=curriki_organize_collection">
<div class="buttons buttons-left">
    <div id="seq-result-<?php echo $_POST['collectionid'];?>"></div>
    <input type="hidden" name="collectionid" id="" value="<?php echo $_POST['collectionid'];?>" />
    <input type="hidden" name="seq" id="seq-<?php echo $_POST['collectionid'];?>" value="" />
    <?php if(!empty($resources)){ ?>
    <button class="btn btn-blue" type="submit">Save</button>
    <?php } ?>
    <button class="btn btn-outline" type="button" onclick="jQuery('#modal-collections').modal('hide');"><?php echo __('Cancel','curriki'); ?></button>
</div>
<div class="buttons-right">
    <?php
    /*
    <button class="btn btn-outline" type="button" onclick="window.location='<?php echo get_bloginfo('url').'/my-library'?>';"><i class="fa fa-angle-left"></i> <?php echo __('Return to MyLibrary','curriki'); ?></button>
    */
    ?>
</div>
</form>
</div>
<script type="text/javascript">
    jQuery(function() {
        jQuery("a.curriki_tooltip").tooltip();
        jQuery( "#sortable-<?php echo $cid?>" ).sortable({
            update: function( event, ui ) {
                curriki_ArrangeCollectionElements();
            }
        });
        jQuery( "#sortable-<?php echo $cid?>" ).disableSelection();
        
        jQuery( "#seq-form-<?php echo $_POST['collectionid'];?>" ).submit(function( event ) {
            jQuery( "#seq-result-<?php echo $_POST['collectionid'];?>" ).empty().append( 'Please wait!' );
            // Stop form from submitting normally
            event.preventDefault();
            // Get some values from elements on the page:
            var $form = jQuery( this ),
            collectionid = $form.find( "input[name='collectionid']" ).val(),
            seq = $form.find( "input[name='seq']" ).val(),
            url = "?curriki_ajax_action=curriki_organize_collection&"+new Date().getTime();
            // Send the data using post
            var posting = jQuery.post( url, { cid: collectionid, s: seq, action: 'curriki_organize_collection' } );
            // Put the results in a div
            posting.done(function( data ) {
                if(data.trim() != '1'){
                    jQuery( "#seq-result-<?php echo $_POST['collectionid'];?>" ).empty().append( data );
                }else{
                    jQuery( "#seq-result-<?php echo $_POST['collectionid'];?>" ).empty().append( 'Changes have been made.' );
                }
            });
            return false;
        });
    });
</script>