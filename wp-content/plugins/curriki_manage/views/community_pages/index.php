<style type="text/css">
    #manage_pages_form .ui-tabs-panel
    {        
        min-height: 650px;
    }
    .col-wrap {
        padding: 0 7px !important;
    }
</style>
<?php 
global $community_pages_root,$tab;
global $wpdb,$community,$message,$message_class,$action_text;
$tab = isset($_GET["tab"]) ? $_GET["tab"] : "tab-1";
$community = null;
$message = "";
$message_class = "notice-info notice";
$community_pages_root = get_home_path()."wp-content/themes/genesis-curriki/modules/community-pages/";
require_once $community_pages_root."classes/Repository/CommunitiesRepository.php"; 


function process_tab_action($post,$files)
{
    global $wpdb; 
    $has_errors = false;
    $message = "";
    //============ Collection Validation ============    
    if(!$has_errors)    
    {
        //********* Updating Collection tab **********
        if( isset($post["collections_resourceid"]) && intval($post["collections_resourceid"]) > 0 )
        {        
            $community_collections_row = array( 'displayseqno' => ( isset($post["displayseqno_collection"] ) && strlen($post["displayseqno_collection"]) > 0 ? $post["displayseqno_collection"] : 0) );
            $community_collections_dt = array('%d');
            $wpdb->update( 
                    'community_collections', 
                    $community_collections_row,                 
                    array( "resourceid"=>$post["collections_resourceid"] , "communityid"=>$post["communityid"] ),
                    $community_collections_dt,
                    array( '%d','%d' )
            );
            if( isset($files["image_collection"]) )
            {                                
                upload_image_cloud_for_collection($files,$wpdb,$post["communityid"],"image_collection",$post["collections_resourceid"]);
            }
        }

        $return_url =  site_url().$_SERVER["REQUEST_URI"];    
        $return_url_arr = parse_url($return_url);
        $query_edit_arr = array();
        parse_str( $return_url_arr["query"] , $query_edit_arr );    
        $query_edit_arr["time"] = time();
        unset($query_edit_arr["tab_action"]);
        unset($query_edit_arr["resourceid"]);
        $return_url_arr["query"] = http_build_query($query_edit_arr);    
        $return_url = "{$return_url_arr["scheme"]}://{$return_url_arr["host"]}{$return_url_arr["path"]}?{$return_url_arr["query"]}";    
        wp_redirect($return_url);
        wp_die();    
    }
    return array("has_errors"=>$has_errors , "message"=>$message);
}

if( isset($_GET["action"]) && $_GET["action"] === "edit" )
{   
    
    $action_text = "Edit";
    if( isset($_POST["submit"]) && $_POST["submit"] === "Save" )
    { 
     
        $has_errors = false;
        $message = "";
        
        if( isset($_POST["tab_action"]) && strlen($_POST["tab_action"]) > 0 )
        {        
            process_tab_action($_POST,$_FILES);            
        }
                
        if( !isset($_POST["name"]) || strlen($_POST["name"]) === 0 )
        {
            $has_errors = true;
            $message .= '<p> Missing "Name"</p>';            
        }        
        if( !isset($_POST["tagline"]) || strlen($_POST["tagline"]) === 0 )
        {
            $has_errors = true;
            $message .= '<p> Missing "Tagline"</p>';            
        }
        if( !isset($_POST["url"]) || strlen($_POST["url"]) === 0 )
        {
            $has_errors = true;
            $message .= '<p> Missing "Url"</p>';            
        }
        
        
        //============ Image Validation =============
        $upload_image = false;
        if( $_FILES['image']['tmp_name'] )
        {
            $upload_image = true;
        }        
        $allow_img_ext = array('jpg', 'jpeg', 'pjpeg', 'png', 'gif', 'bmp');
        $clear_ext_check = in_array(pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION), $allow_img_ext);
                       
        if($_FILES['image']['tmp_name'] && !$clear_ext_check)
        {
            $has_errors = true;
            $upload_image = false;
            $message .= "<p>Invalid \"Image\" extension ( ".pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION)." ).</p>";
        }       
        
        //============ Logo Validation =============
        $upload_logo = false;
        if( $_FILES['logo']['tmp_name'] )
        {
            $upload_logo = true;
        }        
        $allow_img_ext_lg = array('jpg', 'jpeg', 'pjpeg', 'png', 'gif', 'bmp');
        $clear_ext_check_lg = in_array(pathinfo($_FILES['logo']['name'],PATHINFO_EXTENSION), $allow_img_ext_lg);
        if($_FILES['logo']['tmp_name'] && !$clear_ext_check_lg)
        {
            $has_errors = true;
            $upload_logo = false;
            $message .= "<p>Invalid \"Logo\" extension ( ".pathinfo($_FILES['logo']['name'],PATHINFO_EXTENSION)." ).</p>";
        } 
        
        $collections = $wpdb->get_row( $wpdb->prepare( "select * from resources where pageurl = %s", $_POST["collectionslug"] ) );        
        if( isset($_POST["collectionslug"]) && strlen($_POST["collectionslug"]) > 0 && !$collections)
        {
            $has_errors = true;            
            $message .= "<p>Collection \"{$_POST["collectionslug"]}\" not found.</p>";
        }elseif( isset($_POST["collectionslug"]) && strlen($_POST["collectionslug"]) > 0 && is_object($collections) && $collections->type === 'resource' ){
            $has_errors = true;
            $message .= "<p>\"{$_POST["collectionslug"]}\" is Resource. Please enter Collection!</p>";            
        }
        
        $group = $wpdb->get_row( $wpdb->prepare( "select * from cur_bp_groups where slug = %s", $_POST["groupslug"] ) );
        if( isset($_POST["groupslug"]) && strlen($_POST["groupslug"])>0 && !$group)
        {
            $has_errors = true;            
            $message .= "<p>Group \"{$_POST["groupslug"]}\" not found.</p>";
        }
        
        
        $community = new Communities();
        $communities_repo = new CommunitiesRepository();
        $communities_repo->wpdb = $wpdb;               
        
        if( $has_errors )
        {  
            $message_class = "error";
            //$community = $communities_repo->getCommunityPageById($_POST["communityid"]);
            $community = new stdClass();
            $community->communityid = $_POST["communityid"];
            $community->name = $_POST["name"];            
            $community->tagline = $_POST["tagline"];
            $community->url = $_POST["url"];                        
            $community->image = "";                        
            $community->logo = "";
        }else{            
            $community->setCommunityid($_POST["communityid"]);
            $community->setName($_POST["name"]);            
            $community->setTagline($_POST["tagline"]);
            $community->setUrl($_POST["url"]);
               
            
            if($upload_image)
            {               
                upload_image_cloud($_FILES,$wpdb,$_POST["communityid"],"image");
            }            
            if($upload_logo)
            {               
                upload_image_cloud($_FILES,$wpdb,$_POST["communityid"],"logo");
            }            
            
            
            $communities_repo->getCommunityUpdate($community); 
            
            /******** Community Groups ********/            
            if($group)
            {                                
                $community_groups_row = array( 'communityid'=>$_POST["communityid"], 'groupid' => $group->id,'displayseqno' => (isset($_POST["displayseqno"])?$_POST["displayseqno"]:0) );
                $community_groups_dt = array('%d','%d');
                $wpdb->insert( 
                        'community_groups', 
                        $community_groups_row,                 
                        $community_groups_dt
                );                               
                updateGroupResources($group->id);
            }
            
            /******** Community Collections ********/
                        
            
            
            if($collections)
            {
                $community_collections_row = array( 'communityid'=>$_POST["communityid"], 'resourceid' => $collections->resourceid,'displayseqno' => (isset($_POST["displayseqno_collection"])?$_POST["displayseqno_collection"]:0) );
                $community_collections_dt = array('%d','%d');
                $wpdb->insert( 
                        'community_collections', 
                        $community_collections_row,                 
                        $community_collections_dt
                );
                //updating collection and resources indexrequired field to T
                updateCollectionsAndChildrens($collections->resourceid);
                
                
                if( isset($_FILES["image_collection"]) )
                {                                
                    upload_image_cloud_for_collection($_FILES,$wpdb,$_POST["communityid"],"image_collection",$collections->resourceid);
                }
            }            
            
            global $community,$message;
            $community = $communities_repo->getCommunityPageById($_POST["communityid"]);
            $message = "Saved!";
        }
        
        
    }else{
        $communities_repo = new CommunitiesRepository();
        $communities_repo->wpdb = $wpdb;        
        $community = $communities_repo->getCommunityPageById($_GET["communityid"]);        
    }
    
    require_once 'form.php'; 
}elseif( isset($_GET["action"]) && $_GET["action"] === "add" ){
    
    $action_text = "Add";
    if( count($_POST) > 0 )
    {   
        
        $has_errors = false;
        if( !isset($_POST["name"]) || strlen($_POST["name"]) === 0 )
        {
            $has_errors = true;
            $message .= '<p> Missing "Name"</p>';            
        }        
        if( !isset($_POST["tagline"]) || strlen($_POST["tagline"]) === 0 )
        {
            $has_errors = true;
            $message .= '<p> Missing "Tagline"</p>';            
        }
        if( !isset($_POST["url"]) || strlen($_POST["url"]) === 0 )
        {
            $has_errors = true;
            $message .= '<p> Missing "Url"</p>';            
        }
        
        // ======= Image upload ========
        $upload_image = false;
        if( $_FILES['image']['tmp_name'] )
        {
            $upload_image = true;
        }        
        $allow_img_ext = array('jpg', 'jpeg', 'pjpeg', 'png', 'gif', 'bmp');
        $clear_ext_check = in_array(pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION), $allow_img_ext);
                       
        if($_FILES['image']['tmp_name'] && !$clear_ext_check)
        {
            $has_errors = true;
            $upload_image = false;
            $message .= "<p>Invalid \"Image\" extension ( ".pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION)." ).</p>";
        }
        if( $_FILES['image']['tmp_name'] )
        {
            list($width, $height) = getimagesize($_FILES['image']["tmp_name"]);            
            if( !(intval($width) === 1440 && intval($height) === 499) )
            {
                $has_errors = true;
                $upload_image = false;
                $message .= "<p>Invalid \"Image\" size ($width X $height). It should be (1440 X 499)</p>";
            }
        }
        
        //============= Logo upload ===============
        $upload_logo = false;
        if( $_FILES['logo']['tmp_name'] )
        {
            $upload_logo = true;
        }        
        $allow_img_ext_lg = array('jpg', 'jpeg', 'pjpeg', 'png', 'gif', 'bmp');
        $clear_ext_check_lg = in_array(pathinfo($_FILES['logo']['name'],PATHINFO_EXTENSION), $allow_img_ext_lg);
        if($_FILES['logo']['tmp_name'] && !$clear_ext_check_lg)
        {
            $has_errors = true;
            $upload_logo = false;
            $message .= "<p>Invalid \"Logo\" extension ( ".pathinfo($_FILES['logo']['name'],PATHINFO_EXTENSION)." ).</p>";
        }
        if( $_FILES['logo']['tmp_name'] )
        {
            list($width, $height) = getimagesize($_FILES['logo']["tmp_name"]);            
            if( (intval($width) >= 438) && (intval($height) >= 150)  )
            {
                $has_errors = true;
                $upload_image = false;
                $message .= "<p>Invalid \"logo\" size \"width:$width px\" and \"height:$height px\". \"Width\" should be equal or less than 438px. \"Height\" should be equal or less than 150px</p>";
            }
        }
        
        
            
        if( $has_errors )
        {
            $message_class = "error";
            $community = new stdClass();
            $community->name = $_POST["name"];            
            $community->tagline = $_POST["tagline"];
            $community->url = $_POST["url"];
            $community->image = "";
            $community->logo = "";
            
        }  else {            
            
            $community = new Communities();       
            $communities_repo = new CommunitiesRepository();

            $community->setName($_POST["name"]);            
            $community->setTagline($_POST["tagline"]);
            $community->setUrl($_POST["url"]);
            $community->setImage("");
            $community->setLogo("");
            
            $communities_repo->wpdb = $wpdb;               
            $communityid = $communities_repo->communityAdd($community);       
            
            if($upload_image)
            {               
                upload_image_cloud($_FILES,$wpdb,$communityid,"image");
            }            
            if($upload_logo)
            {               
                upload_image_cloud($_FILES,$wpdb,$communityid,"logo");
            }
            
            wp_redirect( admin_url()."admin.php?page=community_pages&action=edit&communityid={$communityid}" );
            wp_die();
        }
    }
            
    require_once 'form.php'; 
}elseif( isset($_GET["action"]) && $_GET["action"] === "delete" ){    
    
    $community = new Communities();       
    $communities_repo = new CommunitiesRepository();
    $communities_repo->wpdb = $wpdb;
    $communities_repo->communityDelete($_GET["communityid"]);
    wp_redirect( admin_url()."admin.php?page=community_pages" );
    wp_die();
    
}elseif( isset($_GET["action"]) && $_GET["action"] === "deletegroup" ){        
    
    $back_url = $_SERVER["HTTP_REFERER"];    
    $wpdb->delete( 'community_groups', array( 'communityid' => $_GET["communityid"] , 'groupid' => $_GET["groupid"] ), array( '%d' , '%d' ) );            
    // updating group resources
    updateGroupResources($_GET["groupid"]);
    wp_redirect($back_url);
    wp_die();
    
}elseif( isset($_GET["action"]) && $_GET["action"] === "deletecollection" ){
    
    $back_url = $_SERVER["HTTP_REFERER"];    
    $wpdb->delete( 'community_collections', array( 'communityid' => $_GET["communityid"] , 'resourceid' => $_GET["resourceid"] ), array( '%d' , '%d' ) );            
    //updating collection and resources indexrequired field to T
    updateCollectionsAndChildrens($_GET["resourceid"]);
    wp_redirect($back_url);
    wp_die();
}elseif( isset($_GET["action"]) && $_GET["action"] === "deleteanchor" ){
    
    $back_url = $_SERVER["HTTP_REFERER"];    
    $wpdb->delete( 'community_anchors', array( 'anchorid' => $_GET["anchorid"] ), array( '%d' ) );            
    wp_redirect($back_url);
    wp_die();
    
}else{
    require_once 'list.php'; 
}

function upload_image_cloud($files,$wpdb,$communityid,$field_name)
{
    
    if ( $files[$field_name]['tmp_name'] ) {
        $upload_folder = '/uploads/tmp/';
        $MaxSizeUpload = 5242880; //Bytes

        //$sub_dir = dirname($_SERVER['REQUEST_URI']);
        $sub_dir = "";                        

        $wp_contents = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'] . $sub_dir . '/wp-content');            

        require_once $wp_contents . '/libs/aws_sdk/aws-autoloader.php';

        $base_url = ($_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $sub_dir . $upload_folder;
        $current_path = $wp_contents . $upload_folder; // relative path from filemanager folder to upload files folder
        
        //**********************
        //Allowed extensions
        //**********************

        $ext_img = array('jpg', 'jpeg', 'pjpeg', 'png', 'gif', 'bmp', 'tiff', 'tif'); //Images
        $ext_video = array('mov', 'mpeg', 'mp4', 'avi', 'mpg', 'wma', 'flv', 'wmv'); //Videos
        //$ext_file = array('doc', 'docx', 'pdf', 'xls', 'xlsx', 'txt', 'csv', 'html', 'psd', 'sql', 'log', 'fla', 'xml', 'ade', 'adp', 'ppt', 'pptx'); //Files
        //$ext_music = array('mp3', 'm4a', 'ac3', 'aiff', 'mid'); //Music
        //$ext_misc = array('zip', 'rar', 'gzip'); //Archives
        //$ext = array_merge($ext_img, $ext_file, $ext_misc, $ext_video, $ext_music); //allowed extensions

        $ds = DIRECTORY_SEPARATOR;
        $aws = Aws\Common\Aws::factory($wp_contents . '/libs/aws_sdk/config.php');
        $s3_client = $aws->get('S3');
                
        $bucket = DBI_AWS_S3_BUCKET;

        $ext = pathinfo($files[$field_name]['name'], PATHINFO_EXTENSION);
        $name = preg_replace("/[^a-zA-Z0-9_]+/", "", str_replace(" ", '_', pathinfo($files[$field_name]['name'], PATHINFO_FILENAME))) . time() . rand();
        $tempFile = $files[$field_name]['tmp_name'];

        $targetFile = $current_path . $name . '.' . $ext;       
        
        
        if(move_uploaded_file($files[$field_name]['tmp_name'], $targetFile))
        {
            if (file_exists($targetFile)) {
                $pic = uniqid();
                $upload = $s3_client->putObject(array(
                            'ACL' => 'public-read',
                            'Bucket' => $bucket,
                            'Key' => 'community_pages/' . $pic . '.' . $ext,
                            'Body' => fopen($targetFile, 'r+')
                        ))->toArray();            
                
                unlink($targetFile);
                
                $ex_file_obj = $wpdb->get_row( "SELECT $field_name FROM communities WHERE communityid = $communityid" );          
                $ex_file_obj->{$field_name};
                if( strlen( $ex_file_obj->{$field_name} ) > 0 )
                {
                    $s3_client->deleteObject(array(                    
                        'Bucket' => $bucket,
                        'Key' => 'community_pages/' . $ex_file_obj->{$field_name},                    
                    ));
                }
                $wpdb->update(
                    'communities', array(
                        $field_name => $pic . '.' . $ext,
                    ), array('communityid' => $communityid), array('%s'), array('%d')
                );                
            }
        }else{
            //echo "file not uploaded.";die;
        }
    }
}
function upload_image_cloud_for_collection($files,$wpdb,$communityid,$field_name,$resourceid)
{
    
    if ( $files[$field_name]['tmp_name'] ) {
        $upload_folder = '/uploads/tmp/';
        $MaxSizeUpload = 5242880; //Bytes

        //$sub_dir = dirname($_SERVER['REQUEST_URI']);
        $sub_dir = "";                        
        $wp_contents = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'] . $sub_dir . '/wp-content');            

        require_once $wp_contents . '/libs/aws_sdk/aws-autoloader.php';

        $base_url = ($_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $sub_dir . $upload_folder;
        $current_path = $wp_contents . $upload_folder; // relative path from filemanager folder to upload files folder
        
        //**********************
        //Allowed extensions
        //**********************

        $ext_img = array('jpg', 'jpeg', 'pjpeg', 'png', 'gif', 'bmp', 'tiff', 'tif'); //Images
        $ext_video = array('mov', 'mpeg', 'mp4', 'avi', 'mpg', 'wma', 'flv', 'wmv'); //Videos
        //$ext_file = array('doc', 'docx', 'pdf', 'xls', 'xlsx', 'txt', 'csv', 'html', 'psd', 'sql', 'log', 'fla', 'xml', 'ade', 'adp', 'ppt', 'pptx'); //Files
        //$ext_music = array('mp3', 'm4a', 'ac3', 'aiff', 'mid'); //Music
        //$ext_misc = array('zip', 'rar', 'gzip'); //Archives
        //$ext = array_merge($ext_img, $ext_file, $ext_misc, $ext_video, $ext_music); //allowed extensions

        $ds = DIRECTORY_SEPARATOR;
        $aws = Aws\Common\Aws::factory($wp_contents . '/libs/aws_sdk/config.php');
        $s3_client = $aws->get('S3');
                
        $bucket = DBI_AWS_S3_BUCKET;

        $ext = pathinfo($files[$field_name]['name'], PATHINFO_EXTENSION);
        $name = preg_replace("/[^a-zA-Z0-9_]+/", "", str_replace(" ", '_', pathinfo($files[$field_name]['name'], PATHINFO_FILENAME))) . time() . rand();
        $tempFile = $files[$field_name]['tmp_name'];

        $targetFile = $current_path . $name . '.' . $ext;               
       
        if(move_uploaded_file($files[$field_name]['tmp_name'], $targetFile))
        {
            if (file_exists($targetFile)) {
                $pic = uniqid();
                $upload = $s3_client->putObject(array(
                            'ACL' => 'public-read',
                            'Bucket' => $bucket,
                            'Key' => 'community_pages/' . $pic . '.' . $ext,
                            'Body' => fopen($targetFile, 'r+')
                        ))->toArray();            
                
                unlink($targetFile);
                
                $ex_file_obj = $wpdb->get_row( "SELECT image FROM community_collections WHERE communityid = $communityid and resourceid=$resourceid" );
                if( strlen( $ex_file_obj->image ) > 0 )
                {
                    $s3_client->deleteObject(array(                    
                        'Bucket' => $bucket,
                        'Key' => 'community_pages/' . $ex_file_obj->image,                    
                    ));
                }
                $wpdb->update(
                    'community_collections', array(
                        "image" => $pic . '.' . $ext,
                    ), array('communityid' => $communityid , 'resourceid' => $resourceid), array('%s'), array('%d','%d')
                );                
            }
        }else{
            //echo "file not uploaded.";die;
        }
    }
}

function updateCollectionsAndChildrens($collectionid){
    global $wpdb;
    // updating collection
    $wpdb->update('resources', ['indexrequired' => 'T'], ['resourceid'=>$collectionid]);
    // updating children
    
    $children = findCommunityChildren($collectionid);
    foreach( $children as $key => $row) {
        foreach($row['children'] as $child){
            $wpdb->update('resources', ['indexrequired' => 'T'], ['resourceid'=>$child['resourceid']]);
        }
        
    }
    
}

function findCommunityChildren($collectionid, &$return = array(), &$count = -1, &$leafcount = 0, &$leafarr = array(), &$temp_arr = array()) {
    global $wpdb; // this is how you get access to the database
    $temp_parentresourceid = null;


    // Perform queries 
    // First time search from pageurl and next time search from collectionids
    
        $children_res = $wpdb->get_results($wpdb->prepare(
                        "SELECT collectionelements.collectionid as parentresourceid, 
            collectionelements.resourceid as resourceid,
            resources.pageurl as parentpageurl
            FROM collectionelements
            INNER JOIN resources ON collectionelements.collectionid=resources.resourceid
            WHERE collectionelements.collectionid = %d", $collectionid
        ));
    
    $current_user = wp_get_current_user();
    if ($current_user->user_login == 'wpadmin') {
        if ($wpdb->num_rows == 0) {
            if (!in_array($collectionid, $leafarr)) {
                $leafarr[] = $collectionid;
            }
        }
    }



    // $c is the counter
    $c = 0;
    $cou = 0;
    foreach ($children_res as $child) {
        

        // Select child resources to get their details
        $child_res_data = $wpdb->get_row($wpdb->prepare(
                        "SELECT * FROM resources 
                            WHERE resourceid = %d
                            ", $child->resourceid
        ));


        $pageurl = $child_res_data->pageurl;
        


        // Logic 
        if ($temp_parentresourceid != $child->parentresourceid):
            $count++;
        endif;

        $parentresourceid = $child->parentresourceid;
        $resourceid = $child->resourceid;
        $parentpageurl = $child->parentpageurl;

        $return[$count]['counter'] = $count + 1;
        $return[$count]['parentresourceid'] = $child->parentresourceid;
        $return[$count]['parentpageurl'] = $child->parentpageurl;
        $return[$count]['leafcount'] = count($leafarr);

        $children = array(
            'resourceid' => $child->resourceid,
            'childpageurl' => $pageurl,
        );

        $return[$count]['children'][$c] = $children;

        $temp_parentresourceid = $child->parentresourceid;

        // $temp_arr to be loop through resource ids
        $temp_arr[$c++]['resourceid'] = $child->resourceid;
    }
    foreach ($temp_arr as $ar):
        findChildren($ar['resourceid'], $startdate, $enddate, $return, $count, $leafcount, $leafarr);
    endforeach;

    return $return;
}

function updateGroupResources($groupid){
    global $wpdb;
    // updating group resources
    $group_resources = $wpdb->get_results('select * from group_resources where groupid = '.$groupid);
    
    foreach( $group_resources as $resource) {
        
        $wpdb->update('resources', ['indexrequired' => 'T'], ['resourceid'=>$resource->resourceid]);
        
        
    }
    
}

?>
