<?php
/**
 * Plugin Name: Curriki Manage Panel
 * Plugin URI: http://curriki.org
 * Description: Managing stuff outside wordpress
 * Version: The plugin's version number. 1.0.0
 * Author: Furqan Aziz
 * Author URI: https://plus.google.com/u/1/117186528930994951156/posts
 */
/*
  ----------Add Following capabilities to the AAM plugin-----------
  curriki_admin
  file_check
  user_manage
  curriki_review
  can_mark_featured
  -----------------------------------------------------------------
  Create a UserRole in AAM Plugin and assign appropriate capability
 */


require_once __DIR__.'/functions/index.php';

if (!defined('WP_CONTENT_DIR'))
    define('WP_CONTENT_DIR', ABSPATH . 'wp-content');

//Admin menu
add_action('admin_menu', 'init_admin_menus');

function init_admin_menus() {
    add_menu_page('Curriki Admin', __('Curriki Admin'), 'manage_curriki', 'curriki_admin', 'curriki_file_check');
    add_submenu_page('curriki_admin', __('File Check'), 'File Check', 'file_check', 'curriki_file_check', 'curriki_file_check');
    add_submenu_page('curriki_admin', __('Broken Links'), 'Broken Links', 'curriki_admin', 'broken_links', 'curriki_broken_links');
    add_submenu_page('curriki_admin', __('Demo Requests'), 'Demo Requests', 'curriki_admin', 'demo_requests', 'curriki_demo_requests');
    add_submenu_page('curriki_admin', __('Link Logs'), 'Link Logs', 'curriki_admin', 'link_logs', 'curriki_link_logs');
    add_submenu_page('curriki_admin', __('Curriki Review'), 'Curriki Review', 'curriki_review', 'curriki_res_review', 'curriki_res_review');
    add_submenu_page('curriki_admin', __('Mark Featured'), 'Mark Featured', 'can_mark_featured', 'mark_featured', 'mark_featured');
    add_submenu_page('curriki_admin', __('Resource Files'), 'Resource Files', 'manage_options', 'resource_files', 'resource_files');
    add_submenu_page('curriki_admin', __('User Manage'), 'User Manage', 'user_manage', 'curriki_user_manage', 'curriki_user_manage');
    add_submenu_page('curriki_admin', __('Icontact Update'), 'Icontact Update', 'icontact_update', 'icontact_update', 'icontact_update');
    add_submenu_page('curriki_admin', __('Hierarchical Children'), 'Hierarchical Children', 'curriki_admin', 'hierarchial_children', 'hierarchial_children');
    add_submenu_page('curriki_admin', __('Community Pages'), 'Community Pages', 'curriki_admin', 'community_pages', 'community_pages_admin_view');
    add_submenu_page('curriki_admin', __('Reporting'), 'Reporting', 'curriki_admin', 'reporting', 'curriki_reporting_admin');
    add_submenu_page('curriki_admin', __('Imports'), 'Imports', 'curriki_admin', 'imports', 'curriki_admin_imports');
}

add_action( 'wp_ajax_fetch_community_pages_search', 'fetch_community_pages_search' );
add_action( 'wp_ajax_nopriv_fetch_community_pages_search', 'fetch_community_pages_search' );
function fetch_community_pages_search() {    
    global $wpdb;
//    $res = $wpdb->get_results("select communityid as id, name as label, url as value from communities where name like '%{$_GET["term"]}%'"); //prepared statement added
    $res = $wpdb->get_results( $wpdb->prepare( 
            "
                    select communityid as id, name as label, url as value from communities where name like %s
            ", 
            '%' . $wpdb->esc_like($_GET["term"]) . '%'
    ) );
    header('Content-type: application/json');
    echo '{"items":'.json_encode($res).'}';
    die();
}


add_action( 'wp_ajax_update_community_anchor', 'update_community_anchor' );
add_action( 'wp_ajax_nopriv_update_community_anchor', 'update_community_anchor' );
function update_community_anchor() {
    global $wpdb;        
    
    
    $return_url = urldecode($_POST["return_url"]);    
    $return_url_arr = parse_url($return_url);
    $query_edit_arr = array();
    parse_str( $return_url_arr["query"] , $query_edit_arr );    
    $query_edit_arr["time"] = time();
    unset($query_edit_arr["anchorid"]);
    $return_url_arr["query"] = http_build_query($query_edit_arr);    
    $return_url = "{$return_url_arr["scheme"]}://{$return_url_arr["host"]}{$return_url_arr["path"]}?{$return_url_arr["query"]}";    
    
    $anchor_obj = $_POST["anchor_obj"];
    
    if( $anchor_obj["anchorid"] > 0 )
    {
        $community_anchors_row = array( 'title' => $anchor_obj["anchor_title"] , 'content' => $anchor_obj["anchor_content"] ,'displayseqno' => ( isset($anchor_obj["displayseqno"] ) && strlen($anchor_obj["displayseqno"]) > 0 ? $anchor_obj["displayseqno"] : 0) );
        $community_anchors_row["type"] = $anchor_obj["anchor_type"] === "default" ? null : $anchor_obj["anchor_type"];
        $community_anchors_row["tagline"] = $anchor_obj["anchor_tagline"];
        $community_anchors_dt = array('%s','%s','%d','%s','%s');
        $wpdb->update( 
                'community_anchors', 
                $community_anchors_row,                 
                array("anchorid"=>$anchor_obj["anchorid"]),
                $community_anchors_dt,
                array( '%d' )
        ); 
    }else{
        $community_anchors_row = array( 'communityid'=>$anchor_obj["communityid"], 'title' => $anchor_obj["anchor_title"] , 'content' => $anchor_obj["anchor_content"] ,'displayseqno' => ( isset($anchor_obj["displayseqno"] ) && strlen($anchor_obj["displayseqno"]) > 0 ? $anchor_obj["displayseqno"] : 0) );
        $community_anchors_row["type"] = $anchor_obj["anchor_type"] === "default" ? null : $anchor_obj["anchor_type"];
        $community_anchors_row["tagline"] = $anchor_obj["anchor_tagline"];
        $community_anchors_dt = array('%d','%s','%s','%d',"%s",'%s');
        $wpdb->insert( 
                'community_anchors', 
                $community_anchors_row,
                $community_anchors_dt
        );         
    }
    
    echo $return_url;
    die();
}

add_action( 'wp_ajax_update_community_group_order', 'update_community_group_order' );
add_action( 'wp_ajax_nopriv_update_community_group_order', 'update_community_group_order' );
function update_community_group_order() {
    global $wpdb;        
    
    $order_value = strlen(trim($_POST["order_value"])) === 0 ? 0 : trim($_POST["order_value"]);
    $wpdb->update(
                    'community_groups', array(
                        'displayseqno' => intval($order_value),
                    ), 
                    array('communityid' => $_POST["communityid"] , 'groupid'=>$_POST["groupid"]), 
                    array('%d'), array('%d','%d')
                );      
    die();
}

add_action( 'wp_ajax_update_community_collection_order', 'update_community_collection_order' );
add_action( 'wp_ajax_nopriv_update_community_collection_order', 'update_community_collection_order' );
function update_community_collection_order() {
    global $wpdb;        
    
    $order_value = strlen(trim($_POST["order_value"])) === 0 ? 0 : trim($_POST["order_value"]);
    $wpdb->update(
                    'community_collections', array(
                        'displayseqno' => intval($order_value),
                    ), 
                    array('communityid' => $_POST["communityid"] , 'resourceid'=>$_POST["resourceid"]), 
                    array('%d'), array('%d','%d')
                );      
    die();
}


////////////////////////////
// Search Paeg Ajax Calls //
////////////////////////////
add_action('wp_ajax_nopriv_get_notation', 'ajax_get_notation');
add_action('wp_ajax_get_notation', 'ajax_get_notation');

function ajax_get_notation() {
    ob_clean();
    global $wpdb; // this is how you get access to the database
//    $res = $wpdb->get_results("select statementid,concat(notation, ' ', substring(description, 1, 50)) as description from statements where standardid = '" . $_REQUEST['selectedstandardid'] . "' order by 2 ", ARRAY_A);    //prepared statement added
    $res = $wpdb->get_results( $wpdb->prepare( 
            "
                    select statementid,concat(notation, ' ', substring(description, 1, 100)) as description from statements where standardid = %d order by 2 
            ", 
            $_REQUEST['selectedstandardid']
    ), ARRAY_A );
    foreach ($res as $row)
        if (!empty($row['description']))
            echo '<option value="' . $row['statementid'] . '" >' . $row['description'] . '</option>';
    wp_die(); // this is required to terminate immediately and return a proper response
}

add_action('wp_ajax_nopriv_get_cr_statements', 'ajax_get_cr_statements');
add_action('wp_ajax_get_cr_statements', 'ajax_get_cr_statements');

function ajax_get_cr_statements() {
    ob_clean();
    global $wpdb; // this is how you get access to the database
    
//    Prepared statement added
    /*
    $res = $wpdb->get_results("select *
    from statements s 
    inner join statement_educationlevels el 
    on s.statementid = el.statementid 
    where parentid in (select statementid from statements where standardid =" . $_REQUEST['standardid'] . " and parentid is null)
    and educationlevelid = " . $_REQUEST['levelid'] . " ");
    */
    
    $res = $wpdb->get_results( $wpdb->prepare( 
            "
                select *
                from statements s 
                inner join statement_educationlevels el 
                on s.statementid = el.statementid 
                where parentid in (select statementid from statements where standardid = %d and parentid is null)
                and educationlevelid = %d
            ", 
            $_REQUEST['standardid'], $_REQUEST['levelid']
    ) );
    echo json_encode($res);
    wp_die(); // this is required to terminate immediately and return a proper response
}

add_action('wp_ajax_nopriv_get_cr_aligntag', 'ajax_get_cr_aligntag');
add_action('wp_ajax_get_cr_aligntag', 'ajax_get_cr_aligntag');

function ajax_get_cr_aligntag() {
    ob_clean();
    global $wpdb; // this is how you get access to the database
//    $res = $wpdb->get_row("SELECT statementid,GetFamilyTree(statementid) as leafs FROM statements where statementid = " . $_REQUEST['statementid'] . " ");    //prepared statement added
    $res = $wpdb->get_row( $wpdb->prepare( 
            "
                    SELECT statementid,GetFamilyTree(statementid) as leafs FROM statements where statementid = %d
            ", 
            $_REQUEST['statementid']
    ) );
    $res = $wpdb->get_results("select statementid,standardid,concat(notation,' : ',substring(description, 1, 70)) description from `statements`  where statementid in ( " . $res->leafs . " )");
    echo json_encode($res);
    wp_die(); // this is required to terminate immediately and return a proper response
}

add_action('wp_ajax_nopriv_search_analytics', 'ajax_search_analytics');
add_action('wp_ajax_search_analytics', 'ajax_search_analytics');

function ajax_search_analytics() {
    ob_clean();
    global $wpdb;
    if ($_REQUEST['branding'] == 'common') {
        wp_die();
    }

    if (is_user_logged_in()):
        $user_id = get_current_user_id();
        $wpdb->query($wpdb->prepare(
                        "
                        INSERT INTO searches
                        ( ip, entrypoint, userid)
                        VALUES ( %s, %s, %d )
                ", $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_HOST'], $user_id
        ));
    else:
        $user_id = get_current_user_id();
        $wpdb->query($wpdb->prepare(
                        "
                        INSERT INTO searches
                        ( ip, entrypoint )
                        VALUES ( %s, %s )
                ", $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_HOST']
        ));
    endif;
    echo json_encode(array('success' => 1));
    wp_die();
}

///////////////////////////////
// Crete Resource Ajax Calls //
///////////////////////////////
add_action('wp_ajax_get_cr_standard_box', 'ajax_get_cr_standard_box');

function ajax_get_cr_standard_box() {
    ob_clean();
    global $wpdb;
//    $standard = $wpdb->get_row("SELECT * FROM standards where standardid = " . $_REQUEST['standardid'] . " ");        //prepared statement added
    $standard = $wpdb->get_row( $wpdb->prepare( 
            "
                    SELECT * FROM standards where standardid = %d
            ", 
            $_REQUEST['standardid']
    ) );
//    $level = $wpdb->get_row("SELECT * FROM educationlevels where levelid = " . $_REQUEST['levelid'] . " ");           //prepared statement added
    
    $level = $wpdb->get_row( $wpdb->prepare( 
            "
                    SELECT * FROM educationlevels where levelid = %d
            ", 
            $_REQUEST['levelid']
    ) );
    
    
//    $statement = $wpdb->get_row("SELECT * FROM statements where statementid = " . $_REQUEST['statementid'] . " ");    //prepared statement added
    
    $statement = $wpdb->get_row( $wpdb->prepare( 
            "
                    SELECT * FROM statements where statementid = %d
            ", 
            $_REQUEST['statementid']
    ) );
    
    
//    $aligntag = $wpdb->get_row("SELECT * FROM statements where statementid = " . $_REQUEST['aligntagid'] . " ");      //prepared statement added
    
    $aligntag = $wpdb->get_row( $wpdb->prepare( 
            "
                    SELECT * FROM statements where statementid = %d
            ", 
            $_REQUEST['aligntagid']
    ) );
    
    
//    $parent = $wpdb->get_row("SELECT * FROM statements where statementid = " . $aligntag->parentid . " ");
    
    $parent = $wpdb->get_row( $wpdb->prepare( 
            "
                    SELECT * FROM statements where statementid = %d
            ", 
            $aligntag->parentid
    ) );

    $res = array(
        'standardid' => $standard->standardid,
        'levelid' => $level->levelid,
        'statementid' => $statement->statementid,
        'aligntagid' => $aligntag->statementid,
        'parentid' => $parent->statementid,
        'title' => $standard->title . ' GRADE ' . $level->displayname,
        'notation' => $aligntag->notation,
        'parent' => $parent->description,
        'description' => $aligntag->description
    );
    echo json_encode($res);
    wp_die(); // this is required to terminate immediately and return a proper response
}
add_action('wp_ajax_nopriv_create_resource', 'ajax_create_resource');
add_action('wp_ajax_create_resource', 'ajax_create_resource');

function ajax_create_resource() {
    if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']))
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

          $secret = GOOGLE_RECAPTCHA_SECRET_KEY;
          $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
          $responseData = json_decode($verifyResponse);
          
          if($responseData->success)
          {
              $submit = true;
          }
    }

    $apiCall = false;
    if (isset($_SESSION['api_call'] )) {
       $submit = true;
       $apiCall = true;
	   unset($_SESSION['api_call']);
    }

    if(!$submit){
        echo json_encode(['msg'=>'Wrong Data']);
        wp_die();
    }
    
    $curriki_recommender = isset($GLOBALS['curriki_recommender']) ? $GLOBALS['curriki_recommender'] : null;    
    $site_url = (is_ssl() ? "https://":"http://") . $_SERVER["HTTP_HOST"];    
    ob_clean();
    global $wpdb;    
    $data = array();
    $gkeywords = array();
    $current_user = wp_get_current_user();


    ///$current_user->ID = 10000;
    $_REQUEST['education_levels'] = explode('|', implode('|', (isset($_REQUEST['education_levels']) ? $_REQUEST['education_levels'] : array())));

    if (isset($_REQUEST['subjectarea']) && is_array($_REQUEST['subjectarea']))
        foreach ($wpdb->get_results("SELECT * from subjectareas where subjectareaid in (" . implode(',', $_REQUEST['subjectarea']) . ")", ARRAY_A) as $row)
            $gkeywords[] .= $row['displayname'];

    if (isset($_REQUEST['education_levels']) && is_array($_REQUEST['education_levels']))
    {
        $in_keys = implode(',', $_REQUEST['education_levels']);        
        if( strlen($in_keys) > 0){
            foreach ($wpdb->get_results("SELECT * from educationlevels where levelid in (" . $in_keys . ")", ARRAY_A) as $row)
            {
                $gkeywords[] .= 'Grade ' . $row['identifier'];
            }
        }
    }
    
    $resourceTitle = str_replace(array('\"', "\'", '\/'), array('"', "'", '/'), $_REQUEST['title']);
    $resourceDescription = str_replace(array('\"', "\'", '\/'), array('"', "'", '/'), $_REQUEST['description']);
    $resourceKeywords = str_replace(array('\"', "\'", '\/'), array('"', "'", '/'), $_REQUEST['keywords']);
    $contents = str_replace(array('\"', "\'", '\/'), array('"', "'", '/'), $_REQUEST['content']);

    $data['licenseid'] = $_REQUEST['licenseid'];
    $data['description'] = trim($resourceDescription);
    $data['title'] = substr(trim($resourceTitle), 0, 499);
    $data['keywords'] = trim($resourceKeywords);
    $data['generatedkeywords'] = $resourceTitle . ' ' . implode(',', $gkeywords); //concatenate title, each subjectarea and each 'grade' + educationlevel
    $data['language'] = substr($_REQUEST['language'], 0, 3);
    $data['content'] = $contents;
    $data['mediatype'] = substr($_REQUEST['mediatype'], 0, 10); //you'll have to determine from what is entered in the tinymce.  Options are (audio, document, image, external (link), text, video, mixed).  If only one of those are entered, then use that type.  If more than one type is entered use 'mixed'
    $data['aligned'] = (isset($_REQUEST['statements']) && is_array($_REQUEST['statements']) && count($_REQUEST['statements']) ? 'T' : 'F');
    $data['access'] = substr($_REQUEST['access'], 0, 10);
    $data['studentfacing'] = (isset($_REQUEST['studentfacing']) ? 'T' : 'F');
    $data['topofsearch'] = (isset($_REQUEST['topofsearch']) ? 'T' : 'F');
    $data['partner'] = (isset($_REQUEST['partner']) ? 'T' : 'F');
    $data['lasteditorid'] = $current_user->ID;
    $data['lasteditdate'] = date('Y-m-d H:i:s');
    $data['approvalStatus'] = 'pending';
    $data['approvalStatusDate'] = date('Y-m-d H:i:s');
    
    if (isset($current_user->caps['administrator']) || $apiCall) {
        $data['approvalStatus'] = 'approved';
        $data['active'] = (isset($_REQUEST['active']) ? 'T' : 'F');
    } else {
        $data['active'] = 'T';
    }

    //BP Activity log values
    $component = "resources";
    $profile_url = $site_url . "/members/" . $current_user->data->user_nicename;
    $user_display_name = $current_user->data->display_name;
    $resource_activity_content = $resourceDescription;

    if (isset($_REQUEST['resourceid']) && !empty($_REQUEST['resourceid'])) {
        $res_id = $_REQUEST['resourceid'];
//        $resource = $wpdb->get_row("SELECT * FROM resources where resourceid = '" . $res_id . "'");       //prepared statement added
        $resource = $wpdb->get_row( $wpdb->prepare( 
                "
                        SELECT * FROM resources where resourceid = %d
                ", 
                $res_id
        ));
    }

    
    if (!empty($resource) && ($resource->contributorid == $current_user->ID OR $current_user->caps['administrator'] OR in_array("resourceEditor", $current_user->roles))) {
        //=== reset cloud index if user is editing spam resouces ===
        if($resource->spam === 'T'){
            $data['spam'] = 'F';
            $data['remove'] = 'F';
            $data['indexrequired'] = 'T';
            $data['indexrequireddate'] = date('Y-m-d H:i:s');
            $data['active'] = 'T';
        }
        
        // === preparing query types ===
        foreach ($data as $i => $v) {
            $type[] = '%s';
            $data[$i] = stripcslashes($v);
        }

        $wpdb->update('resources', $data, array('resourceid' => $res_id), $type);
        if($resource->type === "collection"){            
            $data['enable-user-registration'] = isset($_REQUEST['enable-user-registration']) && $_REQUEST['enable-user-registration'] == 1 ? 1 : 0;
            oerEnableUserRegistration($res_id, $data, 'collection', "program");
        }
        $wpdb->delete('resource_subjectareas', array('resourceid' => $res_id));
        $wpdb->delete('resource_educationlevels', array('resourceid' => $res_id));
        $wpdb->delete('resource_instructiontypes', array('resourceid' => $res_id));
        $wpdb->delete('resource_statements', array('resourceid' => $res_id));
        $wpdb->delete('lti_resources', array('resourceid' => $res_id));
        $wpdb->delete('resource_thumbs', array('resourceid' => $res_id));
        //$wpdb->delete('resourcefiles', array('resourceid' => $res_id));

        $pageurl = $data['pageurl'] = $resource->pageurl;
        $bpActType = "resource_udpate";
        $resource_activity_title = '<a href="' . $site_url . '/oer/' . $data['pageurl'] . '">' . $resourceTitle . '</a>';
        $bpActAction = '<a href="' . $profile_url . '">' . $user_display_name . '</a> updated ' . $resource_activity_title;
    } else {
        $data['pageurl'] = $data['title'] ? $data['title'] : substring($data['description'], 1, 30);
        $data['pageurl'] = substr($data['pageurl'] = str_replace(array(' ', ',', ':', '.', '(', ')', '\'', '?', '/', '+', '\\', '--', '&', '#', '"'), array('-', '-', '-', '', '-', '-', '-', '-', '-', '-', '-', '-', 'and', '-', '-'), $data['pageurl']), 0, 499); //If this result is not unique across all resources then concat (the above, '-', cast(resourceid as char(30))) which puts the resourceid at the end and will guarantee uniqueness.
        $data['contributorid'] = $current_user->ID;
        $data['contributiondate'] = date('Y-m-d H:i:s');
        $data['createdate'] = date('Y-m-d H:i:s');
        $data['type'] = substr($_REQUEST['resource_type'], 0, 10);
        foreach ($data as $i => $v)
            $type[] = '%s';

        $wpdb->insert('resources', $data, $type);
        
        $res_id = $wpdb->insert_id;

        if($data['type'] === "collection"){
            $data['enable-user-registration'] = isset($_REQUEST['enable-user-registration']) && $_REQUEST['enable-user-registration'] == 1 ? 1 : 0;
            oerEnableUserRegistration($res_id, $data, $data['type'], "program");
        }        

        $dup_title = $wpdb->get_row("SELECT count(*) CNT FROM resources where pageurl = '" . $data['pageurl'] . "' and  resourceid != '" . $res_id . "'")->CNT;
        if ($dup_title) {
            $data['pageurl'] = $data['pageurl'] . '-' . $res_id;
            $wpdb->update('resources', array('pageurl' => $data['pageurl']), array('resourceid' => $res_id));
        }
        $pageurl = $data['pageurl'];
        $bpActType = "resource_insert";
        $resource_activity_title = '<a href="' . $site_url . '/oer/' . $pageurl . '">' . $resourceTitle . '</a>';
        $bpActAction = '<a href="' . $profile_url . '">' . $user_display_name . '</a> created ' . $resource_activity_title;

        if (isset($_REQUEST['groupid']) && intval($_REQUEST['groupid']) > 0)
            $wpdb->insert('group_resources', array('groupid' => intval($_REQUEST['groupid']), 'resourceid' => $res_id), array('%d', '%d'));

        if (isset($_REQUEST['prid']) && intval($_REQUEST['prid']) > 0)
            $wpdb->insert('collectionelements', array('collectionid' => intval($_REQUEST['prid']), 'resourceid' => $res_id), array('%d', '%d'));
    }

    //===== CHecking for spam ====
    foreach ($wpdb->get_results("SELECT phrase FROM censorphrases", ARRAY_A) as $word) {
        if( is_phrase_consored($data['title'], trim($word['phrase'])) || is_phrase_consored($data['content'],  trim($word['phrase'])) || is_phrase_consored($data['keywords'], trim($word['phrase'])) ) {
                        
            // Prepared statement added            
            $wpdb->query($wpdb->prepare(
                    "
                        update resources
                        set spam = 'T',
                        remove = 'T',
                        indexrequired = 'T',
                        indexrequireddate = current_timestamp(),
                        active = 'F'
                        where resourceid = %d
                    ", $res_id
            ));
            //echo $wpdb->last_query;
            break;
        }
    }

    //Adding User Activity Log
    if ($data['access'] != 'private') {
        $activity_id = bp_activity_add(array(
            'action' => $bpActAction,
            'content' => $resource_activity_content,
            'component' => $component,
            'type' => $bpActType,
        ));
    }

    //Setting Subject Areas
    $resource_subjectareas_list = [];
    $data = array('resourceid' => $res_id);
    if (isset($_REQUEST['subjectarea']) && is_array($_REQUEST['subjectarea'])){
        foreach ($_REQUEST['subjectarea'] as $row) {
            $data['subjectareaid'] = $row;
            $resource_subjectareas_list[] = $data;
            $wpdb->insert('resource_subjectareas', $data);
        }
    }    
    
    //Setting Education Levels
    $resource_educationlevels_list = [];
    $data = array('resourceid' => $res_id);
    if (isset($_REQUEST['education_levels']) && is_array($_REQUEST['education_levels'])){
        foreach ($_REQUEST['education_levels'] as $row) {
            if( strlen($row) > 0 ){
                $data['educationlevelid'] = $row;
                $resource_educationlevels_list[] = $data;
                $wpdb->insert('resource_educationlevels', $data);
            }
        }
    }            
    
    //Setting Instruction Types
    $data = array('resourceid' => $res_id);
    if (isset($_REQUEST['instructiontypes']) && is_array($_REQUEST['instructiontypes']))
        foreach ($_REQUEST['instructiontypes'] as $row) {
            $data['instructiontypeid'] = $row;
            $wpdb->insert('resource_instructiontypes', $data);
        }

    //Setting Statements
    $data = array('resourceid' => $res_id);
    if (isset($_REQUEST['statements']) && is_array($_REQUEST['statements']))
        foreach ($_REQUEST['statements'] as $row) {
            $data['statementid'] = $row;
            $data['alignmentdate'] = date('Y-m-d H:i:s');
            $data['userid'] = $current_user->ID;
            $wpdb->insert('resource_statements', $data);
        }
        
        
    //Inserting LTI resource
    $data = array('resourceid' => $res_id);
    if (isset($_REQUEST['lti_id']) && isset($_REQUEST['type_id'])) {
        $data['type_id'] = $_REQUEST['type_id'];
        $data['lti_id'] = $_REQUEST['lti_id'];
        $wpdb->insert('lti_resources', $data);
    }
    
    //Inserting Resource Thumbnail
    $data = array('resourceid' => $res_id);
    if (isset($_REQUEST['resource_thumb_hidden'])) {
        $data['thumb_image'] = $_REQUEST['resource_thumb_hidden'];
        $wpdb->insert('resource_thumbs', $data);
    }


    //Questions
    
        
    $res_content = $wpdb->get_results($wpdb->prepare("SELECT content FROM resources WHERE resourceid = %d",  $res_id))[0]->content;

    $newDom = new DOMDocument();
    libxml_use_internal_errors(TRUE); //disable libxml errors

    $newDom->loadHTML(mb_convert_encoding($contents, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    
    libxml_clear_errors();

    $main_xpath = new DOMXPath(@$newDom);
    if (!$main_xpath) {
        echo "\nCouldnt find main_xpath\n";
        die();
    }
    
    $question_wrapper = $main_xpath->query('//form[contains(@class, "question_front_form")]');
    
    if($question_wrapper->length > 0) {
        
        $res_content_new = putQuestionData($question_wrapper, $newDom, $main_xpath, $res_id);
        if($res_content_new != null){
            $res_content = $res_content_new;
        }
        
    }
    


    //Setting ResourceFiles
    include_once(dirname(dirname(__DIR__)) . '/libs/functions.php');
    $data = array('resourceid' => $res_id);
    if (isset($_REQUEST['resourcefiles']) && is_array($_REQUEST['resourcefiles'])) {
        $resourcefiles_list = [];
        foreach ($_REQUEST['resourcefiles'] as $row) {
            $file = json_decode(stripcslashes($row));
            if (!strpos($contents, $file->url) && !strpos($contents, $file->s3path)) {
                // Delete Normal File
                deleteFileS3((array) $file);
                // Delete Video Files, Poster, Transcoded Stuff [Pending]
                // Delete LodeStar Unzipped Folder [Pending]
                $wpdb->delete('resourcefiles', array('fileid' => intval($file->fileid)), array('%d'));
            } elseif (!$file->fileid) {
//                print_r($file);
                $data['fileid'] = NULL;
                $data['filename'] = substr($file->filename, 0, 500);
                $data['uploaddate'] = $file->uploaddate ? $file->uploaddate : date('Y-m-d H:i:s');
                $data['uniquename'] = substr($file->uniquename, 0, 500);
                $data['ext'] = substr($file->ext, 0, 10);
                $data['active'] = "T";
                $data['folder'] = substr($file->folder, 0, 100);
                $data['SDFstatus'] = $file->SDFstatus;
                $data['transcoded'] = $file->transcoded;
                $data['s3path'] = substr($file->url, 0, 200);
                $data['lodestar'] = substr($file->lodestar, 0, 250);
                $wpdb->insert('resourcefiles', $data, array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'));                
                
                $data['fileid'] = intval($wpdb->insert_id);                
                $data['tempactive'] = isset($data['tempactive']) ? $data['tempactive'] : 'F';
                $resourcefiles_list[] = $data;
                uploadLodeStarS3((array) $file);
            }
        }
        
    } elseif (strpos($contents, DBI_AWS_S3_CDN_URL)) {
        $newDom = new DOMDocument();
        libxml_use_internal_errors(TRUE); //disable libxml errors

        @$newDom->loadHTML($contents);
        libxml_clear_errors();

        $main_xpath = new DOMXPath(@$newDom);
        if (!$main_xpath) {
            echo "\nCouldnt find main_xpath\n";
            wp_die();
        }
        $a_row = $main_xpath->query('//a[contains(@href, "https://' . DBI_AWS_S3_CDN_URL . '/")]');
        $i_row = $main_xpath->query('//iframe[contains(@src, "' . DBI_AWS_S3_CDN_URL . '")]');
        $img_row = $main_xpath->query('//img[contains(@src, "' . DBI_AWS_S3_CDN_URL . '")]');
        $vid_row = $main_xpath->query('//video//source[contains(@type, "video/mp4")][contains(@src, "' . DBI_AWS_S3_CDN_URL . '")]');

        $urls_arr = array();
        foreach ($a_row as $a) {
            $urls_arr[] = $a->getAttribute('href');
        }
        foreach ($i_row as $i) {
            $i_src = urldecode($i->getAttribute('src'));
            $parts = parse_url($i_src);
            parse_str($parts['query'], $query);
            if (isset($query['url']) && $query['url'] != '') {
                $urls_arr[] = $query['url'];
            }
        }

        foreach ($img_row as $img) {
            $urls_arr[] = urldecode($img->getAttribute('src'));
        }
        foreach ($vid_row as $vid) {
            $urls_arr[] = urldecode($vid->getAttribute('src'));
        }
        $urls_arr = array_unique($urls_arr);
        foreach ($urls_arr as $href) {
            $trimmed_href = str_replace('https://' . DBI_AWS_S3_CDN_URL . '/', '', $href);
            $exploded_href = explode("/", $trimmed_href);
            if (count($exploded_href) == 2) {
                $folder = $exploded_href[0];
                $uniquename = $exploded_href[1];
//                print_r($exploded_href);
                $sql = "SELECT * FROM resourcefiles WHERE uniquename LIKE '$uniquename' LIMIT 1";

                $results = $wpdb->get_results($sql);
                if (count($results) > 0) {
                    $resourcefiles_list = [];
                    foreach ($results as $file) {
                        $data['fileid'] = NULL;
                        $data['filename'] = substr($file->filename, 0, 500);
                        $data['uploaddate'] = $file->uploaddate ? $file->uploaddate : date('Y-m-d H:i:s');
                        $data['uniquename'] = substr($file->uniquename, 0, 500);
                        $data['ext'] = substr($file->ext, 0, 10);
                        $data['active'] = "T";
                        $data['folder'] = substr($file->folder, 0, 100);
                        $data['SDFstatus'] = $file->SDFstatus;
                        $data['transcoded'] = $file->transcoded;
                        $data['s3path'] = substr($file->s3path, 0, 200);
                        $data['lodestar'] = substr($file->lodestar, 0, 250);
                        $sql = "SELECT fileid FROM resourcefiles WHERE uniquename LIKE '$uniquename' AND resourceid = " . $data['resourceid'] . " LIMIT 1";

                        $res = $wpdb->get_results($sql);

                        if ($wpdb->num_rows == 0) {
                            $wpdb->insert('resourcefiles', $data, array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'));
                            $data['fileid'] = intval($wpdb->insert_id);                
                            $data['tempactive'] = isset($data['tempactive']) ? $data['tempactive'] : 'F';
                            $resourcefiles_list[] = $data;
                        }
                    }
                    
                } else {
                    $resourcefiles_list = [];
                    $data['fileid'] = NULL;
                    $data['filename'] = substr($uniquename, 0, 500);
                    $data['uploaddate'] = date('Y-m-d H:i:s');
                    $data['uniquename'] = substr($uniquename, 0, 500);
                    $data['ext'] = substr(end(explode('.', $uniquename)), 0, 10);
                    $data['active'] = "T";
                    $data['folder'] = substr($folder . '/', 0, 100);
                    $data['SDFstatus'] = '';
                    $data['transcoded'] = 'T';
                    $data['s3path'] = $href;
                    $data['lodestar'] = '';                    
                    $wpdb->insert('resourcefiles', $data, array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'));                    
                    $data['fileid'] = intval($wpdb->insert_id);                
                    $data['tempactive'] = isset($data['tempactive']) ? $data['tempactive'] : 'F';
                    $resourcefiles_list[] = $data;                                                            
                }
            }
        }
    }
    //Returning Output
    echo json_encode(array('resourceid' => $res_id, 'pageurl' => $pageurl, 'content'=>$res_content));
    wp_die(); // this is required to terminate immediately and return a proper response
}

function is_phrase_consored($text, $needle){            
    $phrase_found_censord = false;
    if( stripos($text, " {$needle} ") !== false ){
        $phrase_found_censord = true;
    }
    if(  stripos($text, "{$needle} ") !== false && stripos($text, "{$needle} ") === 0 ){        
        $phrase_found_censord = true;
    }
    if( stripos($text, " {$needle}.") !== false ){
        $phrase_found_censord = true;
    }
    
    $need_rule_1 = " {$needle}";
    $is_needle_on_last = stripos($text, $need_rule_1) + strlen($need_rule_1) === strlen($text) ? true:false;
    if( stripos($text, $need_rule_1) !== false && $is_needle_on_last){        
        $phrase_found_censord = true;
    }

    return $phrase_found_censord;
}

function putQuestionData($question_wrapper, $newDom, $main_xpath, $res_id){
     
    global $wpdb;
    $nodes_tracker = 0;
    $count = 0;
    
    $data = array('resourceid' => $res_id);
    
    foreach ($question_wrapper as $question) {
        
        
        $question_info = $main_xpath->query('.//div[contains(@class, "question_frontend_statement")]//div[contains(@class, "question_title")]//div[@class="question"]', $question)->item(0);
        $question_info = DOMinnerHTML($question_info);
        
        
        $question_data[$count]['question'] = $question_info;
        
        
        
        
        $data['resourceid'] = $res_id;
        $data['question'] = trim($question_info);
        
        
        $frontend_question_type = $main_xpath->query('.//div[contains(@class, "question_type")]', $question)->item(0)->nodeValue;
        
        $data['type'] = $frontend_question_type;
        
        $question_exists = $wpdb->get_results($wpdb->prepare("SELECT * FROM resourcequestions rq INNER JOIN resourcequestion_answers rqa ON rq.questionid = rqa.questionid WHERE rq.resourceid = %d", $res_id));
        
        $cnt = -1;
        
        $q = null;
        $db_question_data = array();
        if(count($question_exists) > 0){
            foreach($question_exists as $existing_data){
                if($q == $existing_data->question){
                    $q = $db_question_data[$cnt]['question'] = $existing_data->question;
                    $db_question_data[$cnt]['option'][] = $existing_data->answer;
                    $db_question_data[$cnt]['response'][] = $existing_data->response;
                    if($existing_data->correct == 'T'){
                        $db_question_data[$cnt]['correct_option'] = $correct;
                    }
                    $correct++;
                } else {
                    $correct = 1;
                    $cnt++;
                    $q = $db_question_data[$cnt]['question'] = $existing_data->question;
                    $db_question_data[$cnt]['option'][] = $existing_data->answer;
                    $db_question_data[$cnt]['response'][] = $existing_data->response;
                    if($existing_data->correct == 'T'){
                        $db_question_data[$cnt]['correct_option'] = $correct;
                    }
                    $correct++;
                    
                }
            }
        }

        
        
        $frontend_questionid = $main_xpath->query('.//input[contains(@class, "questionid")]', $question);
//        var_dump($frontend_questionid->item(0));
        
        $options_info = $main_xpath->query('.//div[contains(@class, "question_frontend_options")]', $question);
        
        foreach ($options_info as $option) {
            
            $c = 0;
            
            $frontend_option = $main_xpath->query('.//div[contains(@class, "frontend_option")]', $option);
            
            foreach ($frontend_option as $option1) {
                $frontend_option = $main_xpath->query('.//div[contains(@class, "frontend_option")]//div[contains(@class, "frontend-label")]//div[contains(@class, "option_val")]', $option)->item($c);
                $frontend_option = DOMinnerHTML($frontend_option);
                
                $frontend_response = $main_xpath->query('.//div[contains(@class, "frontend_response")]', $option)->item($c);
                $frontend_response = DOMinnerHTML($frontend_response);
//                $frontend_response = $frontend_response->ownerDocument->saveHTML($frontend_response);
                $question_data[$count]['option'][$c] = $frontend_option;
                $question_data[$count]['response'][$c] = $frontend_response;
                
                $c++;
            }
            $frontend_correct_option = $main_xpath->query('.//div[contains(@class, "correct_option")]', $question)->item(0)->nodeValue;
            
            
            $question_data[$count]['correct_option'] = $frontend_correct_option;
            
//            $question_data[$count]['question_type'] = $frontend_question_type;
            break;
        }
//        echo $count;
//        die();
//        if($count == 1){
//            var_dump(isset($db_question_data[$count]));
//            die();
//        }
        
        $res_content = '';
;
        if(!isset($db_question_data[$count])){  //question does not exist: Insert question
            
            $wpdb->insert('resourcequestions', $data);
            $lastid = $wpdb->insert_id;
            
            $frontend_questionid = $main_xpath->query('.//input[contains(@class, "questionid")]', $question);


            $frontend_questionid[0]->setAttribute('value', $lastid);

            $content = $newDom->saveHTML()."<p>&nbsp;</p>";

            $wpdb->update( 'resources', ['content'=>$content], ['resourceid'=>$res_id]); 

            $res_content = $wpdb->get_results($wpdb->prepare("SELECT content FROM resources WHERE resourceid = %d",  $res_id))[0]->content;
    
            $j = 0;

            
            foreach ($question_data[$count]['option'] as $op) {
                $dt['questionid'] = $lastid;
                $dt['sequence'] = $j + 1;
                $dt['answer'] = $question_data[$count]['option'][$j];
                if ($j + 1 == $question_data[$count]['correct_option']) {
                    $dt['correct'] = 'T';
                } else {
                    $dt['correct'] = 'F';
                }

                $dt['response'] = $question_data[$count]['response'][$j];
                $wpdb->insert('resourcequestion_answers', $dt);
                $j++;
            }
            
        }
        elseif($question_data[$count] == $db_question_data[$count]){
            
        } else {
            $questionid = $frontend_questionid->item(0)->getAttribute('value');
            if($questionid == 'undefined'){
                $wpdb->insert('resourcequestions', $data);
                $lastid = $wpdb->insert_id;

                $frontend_questionid = $main_xpath->query('.//input[contains(@class, "questionid")]', $question);


                $frontend_questionid[0]->setAttribute('value', $lastid);

                $content = $newDom->saveHTML()."<p>&nbsp;</p>";

                $wpdb->update( 'resources', ['content'=>$content], ['resourceid'=>$res_id]); 

                $res_content = $wpdb->get_results($wpdb->prepare("SELECT content FROM resources WHERE resourceid = %d",  $res_id))[0]->content;

                $j = 0;


                foreach ($question_data[$count]['option'] as $op) {
                    $dt['questionid'] = $lastid;
                    $dt['sequence'] = $j + 1;
                    $dt['answer'] = $question_data[$count]['option'][$j];
                    if ($j + 1 == $question_data[$count]['correct_option']) {
                        $dt['correct'] = 'T';
                    } else {
                        $dt['correct'] = 'F';
                    }

                    $dt['response'] = $question_data[$count]['response'][$j];
                    $wpdb->insert('resourcequestion_answers', $dt);
                    $j++;
                }
            } 
            
            
        }
        
        $count++;
        
    }
    return $res_content;
}

function DOMinnerHTML(DOMNode $element) 
{ 
    $innerHTML = ""; 
    $children  = $element->childNodes;

    foreach ($children as $child) 
    { 
        $innerHTML .= $element->ownerDocument->saveHTML($child);
    }

    return $innerHTML; 
} 

add_action('wp_ajax_nopriv_attempt_question', 'ajax_attempt_question');
add_action('wp_ajax_attempt_question', 'ajax_attempt_question');

function ajax_attempt_question() {
    global $wpdb;
    $question_num = $_REQUEST['question_num'];
    $questionid = $_REQUEST['questionid'];
    $selected_option = $_REQUEST['selected_option'];
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        $userid = $current_user->ID;
    } else {
        $userid = 10000;
    }
    $question_data = $wpdb->get_results("SELECT answerid from resourcequestions rq
INNER JOIN resourcequestion_answers rqa
ON rq.questionid = rqa.questionid
where rq.questionid = $questionid AND sequence = $selected_option", ARRAY_A);

    $question_data = array_shift(array_values($question_data));
    $answerid = $question_data['answerid'];
    $wpdb->insert(
            'user_resourcequestions', array(
        "userid" => $userid,
        "answerid" => $answerid,
        "answerdate" => date('Y-m-d H:i:s')
            ), array("%d", "%d", "%s")
    );
    
    $sql = "SELECT * FROM resourcequestions rq INNER JOIN resourcequestion_answers rqa ON rq.questionid = rqa.questionid WHERE rqa.answerid = $answerid";
    
    
    $results = $wpdb->get_row($sql);
    
    if(count($results) > 0){
        
        
        if($results->correct == 'T'){
            $message = "<div class='status'></div>";
            $message .= $results->response;
            $return = ['correct'=>1, 'message'=>$message];
        } else {
            $message = "<div class='status'></div>";
            $message .= $results->response;
            $return = ['correct'=>0, 'message'=>$message];
        }
        echo json_encode($return);
        
    } else {
        
        echo json_encode(['correct'=>0, 'message'=>'Invalid Answer']);
    }
    
    wp_die();
}

function delete_one_day_previous_previews() {
    global $wpdb;
    $sql = 'DELETE FROM preview_resources'
            . ' WHERE preview_date < DATE_SUB(NOW(), INTERVAL 1 DAY)';
    $wpdb->query($sql);
}

add_action('wp_ajax_nopriv_preview_resource', 'ajax_preview_resource');
add_action('wp_ajax_preview_resource', 'ajax_preview_resource');

function ajax_preview_resource() {
    ob_clean();
    global $wpdb;

    delete_one_day_previous_previews();


    $data = array();
    $gkeywords = array();
    $current_user = wp_get_current_user();

    ///$current_user->ID = 10000;
    $_REQUEST['education_levels'] = explode('|', implode('|', (isset($_REQUEST['education_levels']) ? $_REQUEST['education_levels'] : array())));

    if (isset($_REQUEST['subjectarea']) && is_array($_REQUEST['subjectarea']))
        foreach ($wpdb->get_results("SELECT * from subjectareas where subjectareaid in (" . implode(',', $_REQUEST['subjectarea']) . ")", ARRAY_A) as $row)
            $gkeywords[] .= $row['displayname'];

    if (isset($_REQUEST['education_levels']) && is_array($_REQUEST['education_levels']))
    {
        $education_levels_in_val = implode(',', $_REQUEST['education_levels']);
        if(strlen($education_levels_in_val) > 0){
            foreach ($wpdb->get_results("SELECT * from educationlevels where levelid in (" . $education_levels_in_val . ")", ARRAY_A) as $row){
                $gkeywords[] .= 'Grade ' . $row['identifier'];
            }
        }
    }
        
    $resourceTitle = str_replace(array('\"', "\'", '\/'), array('"', "'", '/'), $_REQUEST['title']);
    $resourceDescription = str_replace(array('\"', "\'", '\/'), array('"', "'", '/'), $_REQUEST['description']);
    $resourceKeywords = str_replace(array('\"', "\'", '\/'), array('"', "'", '/'), $_REQUEST['keywords']);
    $contents = str_replace(array('\"', "\'", '\/'), array('"', "'", '/'), $_REQUEST['content']);
    $data['licenseid'] = $_REQUEST['licenseid'];
    $data['description'] = trim($resourceDescription);
    $data['title'] = substr(trim($resourceTitle), 0, 499);
    $data['keywords'] = trim($resourceKeywords);
    $data['generatedkeywords'] = $data['title'] . ' ' . implode(',', $gkeywords); //concatenate title, each subjectarea and each 'grade' + educationlevel
    $data['language'] = substr($_REQUEST['language'], 0, 3);
    $data['content'] = $contents;
    $data['mediatype'] = substr($_REQUEST['mediatype'], 0, 10); //you'll have to determine from what is entered in the tinymce.  Options are (audio, document, image, external (link), text, video, mixed).  If only one of those are entered, then use that type.  If more than one type is entered use 'mixed'
    $data['aligned'] = (isset($_REQUEST['statements']) && is_array($_REQUEST['statements']) && count($_REQUEST['statements']) ? 'T' : 'F');
    $data['access'] = substr($_REQUEST['access'], 0, 10);
    $data['studentfacing'] = (isset($_REQUEST['studentfacing']) ? 'T' : 'F');
    $data['topofsearch'] = (isset($_REQUEST['topofsearch']) ? 'T' : 'F');
    $data['partner'] = (isset($_REQUEST['partner']) ? 'T' : 'F');
    $data['lasteditorid'] = $current_user->ID;
    $data['lasteditdate'] = date('Y-m-d H:i:s');

    if (isset($current_user->caps['administrator'])) {
        $data['active'] = ($_REQUEST['active'] ? 'T' : 'F');
    } else {
        $data['active'] = 'T';
    }

    //BP Activity log values
    $component = "resources";
    $profile_url = site_url() . "/members/" . $current_user->data->user_nicename;
    $user_display_name = $current_user->data->display_name;
    $resource_activity_content = $resourceDescription;

    if (isset($_REQUEST['resourceid']) && !empty($_REQUEST['resourceid'])) {
        $res_id = $_REQUEST['resourceid'];
//        $resource = $wpdb->get_row("SELECT * FROM preview_resources where resourceid = '" . $res_id . "'");       //prepared statement added
        
        $resource = $wpdb->get_row( $wpdb->prepare( 
                "
                        SELECT * FROM preview_resources where resourceid = %d
                ", 
                $res_id
        ));
    }

    if (0/* !empty($resource) && ($resource->contributorid == $current_user->ID OR $current_user->caps['administrator']) */) {
        foreach ($data as $i => $v) {
            $type[] = '%s';
            $data[$i] = stripcslashes($v);
        }

        $wpdb->update('resources', $data, array('resourceid' => $res_id), $type);
        $wpdb->delete('resource_subjectareas', array('resourceid' => $res_id));
        $wpdb->delete('resource_educationlevels', array('resourceid' => $res_id));
        $wpdb->delete('resource_instructiontypes', array('resourceid' => $res_id));
        $wpdb->delete('resource_statements', array('resourceid' => $res_id));
        //$wpdb->delete('resourcefiles', array('resourceid' => $res_id));

        $pageurl = $data['pageurl'] = $resource->pageurl;
        $bpActType = "resource_udpate";
        $resource_activity_title = '<a href="' . site_url() . '/oer/' . $data['pageurl'] . '">' . $resourceTitle . '</a>';
        $bpActAction = '<a href="' . $profile_url . '">' . $user_display_name . '</a> updated ' . $resource_activity_title;
    } else {
        $data['pageurl'] = $data['title'] ? $data['title'] : substring($data['description'], 1, 30);
        $data['pageurl'] = substr($data['pageurl'] = str_replace(array(' ', ',', ':', '.', '(', ')', '\'', '?', '/', '+', '\\', '--', '&', '#', '"'), array('-', '-', '-', '', '-', '-', '-', '-', '-', '-', '-', '-', 'and', '-', '-'), $data['pageurl']), 0, 499); //If this result is not unique across all resources then concat (the above, '-', cast(resourceid as char(30))) which puts the resourceid at the end and will guarantee uniqueness.
        $data['contributorid'] = $current_user->ID;
        $data['contributiondate'] = date('Y-m-d H:i:s');
        $data['createdate'] = date('Y-m-d H:i:s');
        $data['type'] = substr($_REQUEST['resource_type'], 0, 10);

        $timestamp = date('Y-m-d G:i:s');
        $data['preview_date'] = $timestamp;
        $preview_resource_id = $_REQUEST['preview_resource_id'];
        foreach ($data as $i => $v)
            $type[] = '%s';
        if ($_REQUEST['prid'] != ''):
            $data['editresourceid'] = $_REQUEST['prid'];
        endif;

        if ($preview_resource_id == "0" || $preview_resource_id == ''):
            $wpdb->insert('preview_resources', $data, $type);
            $res_id = $wpdb->insert_id;
        else:
            $wpdb->update('preview_resources', $data, array('resourceid' => (int) $preview_resource_id));
            $res_id = $preview_resource_id;
        endif;



        $dup_title = $wpdb->get_row("SELECT count(*) CNT FROM resources where pageurl = '" . $data['pageurl'] . "' and  resourceid != '" . $res_id . "'")->CNT;
        if ($dup_title) {
            $data['pageurl'] = $data['pageurl'] . '-' . $res_id;
//            $wpdb->update('resources', array('pageurl' => $data['pageurl']), array('resourceid' => $res_id));
        }
        $pageurl = $data['pageurl'];
        $bpActType = "resource_insert";
        $resource_activity_title = '<a href="' . site_url() . '/oer/' . $pageurl . '">' . $resourceTitle . '</a>';
        $bpActAction = '<a href="' . $profile_url . '">' . $user_display_name . '</a> created ' . $resource_activity_title;

        /*
          if (isset($_REQUEST['groupid']) && intval($_REQUEST['groupid']) > 0)
          $wpdb->insert('group_resources', array('groupid' => intval($_REQUEST['groupid']), 'resourceid' => $res_id), array('%d', '%d'));

          if (isset($_REQUEST['prid']) && intval($_REQUEST['prid']) > 0)
          $wpdb->insert('collectionelements', array('collectionid' => intval($_REQUEST['prid']), 'resourceid' => $res_id), array('%d', '%d'));
         * 
         */
    }

    //CHecking for spam
    /*
      foreach ($wpdb->get_results("SELECT phrase FROM censorphrases", ARRAY_A) as $word) {
      if ((strpos(' ' . $data['title'], $word['phrase']) > 0 ) || (strpos(' ' . $data['title'], $word['phrase']) > 0 ) || (strpos(strip_tags(' ' . $data['content']), $word['phrase']) > 0 ) || (striposa(' ' . $data['keywords'], $word['phrase']) > 0)) {
      $wpdb->query("update resources
      set spam = 'T',
      remove = 'T',
      indexrequired = 'T',
      indexrequireddate = current_timestamp(),
      active = 'F'
      where resourceid = '{$res_id}' ");
      //echo $wpdb->last_query;
      break;
      }
      }
     * 
     */

    //Adding User Activity Log
    /*
      if ($data['access'] != 'private') {
      $activity_id = bp_activity_add(array(
      'action' => $bpActAction,
      'content' => $resource_activity_content,
      'component' => $component,
      'type' => $bpActType,
      ));
      }
     * 
     */

    //Setting Subject Areas
    $data = array('resourceid' => $res_id);
    if (isset($_REQUEST['subjectarea']) && is_array($_REQUEST['subjectarea'])) {
        foreach ($_REQUEST['subjectarea'] as $row) {
//            $data['subjectareaid'] = $row;
            $subjectarea_ids[] = $row;
//            $wpdb->insert('resource_subjectareas', $data);
        }
        $subjectareas = serialize($subjectarea_ids);
        $wpdb->update('preview_resources', array('subjectareas' => $subjectareas), array('resourceid' => (int) $res_id));
    }

    //Setting Education Levels
    $data = array('resourceid' => $res_id);
    if (isset($_REQUEST['education_levels']) && is_array($_REQUEST['education_levels'])) {
        foreach ($_REQUEST['education_levels'] as $row) {
            $education_levels[] = $row;
        }


        $edu_levels = serialize($education_levels);
        $wpdb->update('preview_resources', array('education_levels' => $edu_levels), array('resourceid' => (int) $res_id));
    }

    //Setting Instruction Types
    $data = array('resourceid' => $res_id);
    if (isset($_REQUEST['instructiontypes']) && is_array($_REQUEST['instructiontypes'])) {
        foreach ($_REQUEST['instructiontypes'] as $row) {
            $resource_instructiontypes[] = $row;
        }

        // For adding resource_instructiontypes
        $res_it = serialize($resource_instructiontypes);
        $wpdb->update('preview_resources', array('resource_instructiontypes' => $res_it), array('resourceid' => (int) $res_id));
    }

    //Setting Statements
    $data = array('resourceid' => $res_id);

    if (isset($_REQUEST['statements']) && is_array($_REQUEST['statements'])) {
        foreach ($_REQUEST['statements'] as $row) {
//            $data['statementid'] = $row;
//            $data['alignmentdate'] = date('Y-m-d H:i:s');
//            $data['userid'] = $current_user->ID;
//            $wpdb->insert('resource_statements', $data);
            $resource_statementids[] = $row;
        }
        $res_statement_ids = serialize($resource_statementids);

        $d = $wpdb->update('preview_resources', array('resource_statementids' => $res_statement_ids), array('resourceid' => (int) $res_id));
//        var_dump($d);
//    wp_die();
    }

    //Setting ResourceFiles
    include_once(dirname(dirname(__DIR__)) . '/libs/functions.php');
    $data = array('resourceid' => $res_id);
    if (isset($_REQUEST['resourcefiles']) && is_array($_REQUEST['resourcefiles']))
        foreach ($_REQUEST['resourcefiles'] as $row) {
            $file = json_decode(stripcslashes($row));
            if (!strpos($contents, $file->uniquename)) {
                // Delete Normal File
                deleteFileS3((array) $file);
                // Delete Video Files, Poster, Transcoded Stuff [Pending]
                // Delete LodeStar Unzipped Folder [Pending]
                $wpdb->delete('resourcefiles', array('fileid' => intval($file->fileid)), array('%d'));
            } elseif (!$file->fileid) {
                $data['fileid'] = NULL;
                $data['filename'] = substr($file->filename, 0, 500);
                $data['uploaddate'] = $file->uploaddate ? $file->uploaddate : date('Y-m-d H:i:s');
                $data['uniquename'] = substr($file->uniquename, 0, 500);
                $data['ext'] = substr($file->ext, 0, 10);
                $data['active'] = "T";
                $data['folder'] = substr($file->folder, 0, 100);
                $data['SDFstatus'] = $file->SDFstatus;
                $data['transcoded'] = $file->transcoded;
                $data['s3path'] = substr($file->url, 0, 200);
                $data['lodestar'] = substr($file->lodestar, 0, 250);
                $wpdb->insert('resourcefiles', $data, array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'));
                uploadLodeStarS3((array) $file);
            }
        }

    //Returning Output
    echo json_encode(array('resourceid' => $res_id, 'pageurl' => $pageurl));
    wp_die(); // this is required to terminate immediately and return a proper response
}

//////////////////////////////
// Ajax OER REsource Rating //
//////////////////////////////
//LIST ALL HOOKS
add_action('wp_ajax_get_OER_pop_up', 'get_OER_pop_up'); 

function get_OER_pop_up() {
       
    ob_clean();
    global $wpdb;

    $query = 'UPDATE resources set ';
    if(isset($_REQUEST['rubric_id'])){
        switch ($_REQUEST['rubric_id']) {
            case '0':
                $query .= " Standardsalignment = " . (isset($_REQUEST['delete']) ? 'NULL' : intval($_REQUEST['score_id']) ) . ",";
                $query .= " Standardsalignmentcomment = '" . addslashes($_REQUEST['comment']) . "'";
                break;
            case '1':
                $query .= " Subjectmatter = " . (isset($_REQUEST['delete']) ? 'NULL' : intval($_REQUEST['score_id']) ) . ",";
                $query .= " Subjectmattercomment = '" . addslashes($_REQUEST['comment']) . "'";
                break;
            case '2':
                $query .= " Supportsteaching = " . (isset($_REQUEST['delete']) ? 'NULL' : intval($_REQUEST['score_id']) ) . ",";
                $query .= " Supportsteachingcomment = '" . addslashes($_REQUEST['comment']) . "'";
                break;
            case '3':
                $query .= " Assessmentsquality = " . (isset($_REQUEST['delete']) ? 'NULL' : intval($_REQUEST['score_id']) ) . ",";
                $query .= " Assessmentsqualitycomment = '" . addslashes($_REQUEST['comment']) . "'";
                break;
            case '4':
                $query .= " Interactivityquality = " . (isset($_REQUEST['delete']) ? 'NULL' : intval($_REQUEST['score_id']) ) . ",";
                $query .= " Interactivityqualitycomment = '" . addslashes($_REQUEST['comment']) . "'";
                break;
            case '5':
                $query .= " Instructionalquality = " . (isset($_REQUEST['delete']) ? 'NULL' : intval($_REQUEST['score_id']) ) . ",";
                $query .= " Instructionalqualitycomment = '" . addslashes($_REQUEST['comment']) . "'";
                break;
            case '6':
                $query .= " Deeperlearning = " . (isset($_REQUEST['delete']) ? 'NULL' : intval($_REQUEST['score_id']) ) . ",";
                $query .= " Deeperlearningcomment = '" . addslashes($_REQUEST['comment']) . "'";
                break;
            default:
                $query .= " Standardsalignment = " . (isset($_REQUEST['delete']) ? 'NULL' : intval($_REQUEST['score_id']) ) . ",";
                $query .= " Standardsalignmentcomment = '" . addslashes($_REQUEST['comment']) . "'";
                break;
        }
    }elseif(isset($_REQUEST['tag_id'])){
        switch ($_REQUEST['tag_id']) {
            case '34':
                $query .= " Standardsalignment = " . (isset($_REQUEST['delete']) ? 'NULL' : intval($_REQUEST['score_id']) ) . ",";
                $query .= " Standardsalignmentcomment = '" . addslashes($_REQUEST['comment']) . "'";
                break;
        }
    }
    $query .= " where resourceid = '" . $_REQUEST['resourceid'] . "'";
    $wpdb->query($query);
    echo $wpdb->last_query;
}

//////////////////////////////
// On Registration Callback //
//////////////////////////////
add_action('user_register', 'curriki_registration_save', 10, 1);

function curriki_registration_save($user_id) {
    global $wpdb;

    $user = $wpdb->get_results("SELECT * from cur_users where ID = " . $user_id);
    $firstname = get_user_meta($user_id, 'first_name', 1);
    $lastname = get_user_meta($user_id, 'last_name', 1);
    $user_rcd = $wpdb->get_row("select * from users where userid=" . $user_id);  
    if($user_rcd === null){
        $wpdb->query("insert into users set userid = '" . $user_id . "'"
            //. ",username = '" . $user[0]->user_login . "'"
            . ",firstname = '" . $firstname . "'"
            . ",lastname = '" . $lastname . "'"
            //. ",email = '" . $user[0]->user_email . "'"
            . ",active = 'T'"
            . ",sitename = 'curriki'"
            . ",registerdate = '" . date('Y-m-d H:i:s') . "'");
    }
    
}

////////////////////////
// Plugin Controllers //
////////////////////////
function curriki_file_check() {
    global $wpdb;
    $view = 'file_check_list';
    $data = array();
    if (!empty($_REQUEST['action']) AND trim($_REQUEST['action']) == 'file_checked') {
        $current_user = wp_get_current_user();

        $resource = $wpdb->get_row(sprintf("SELECT resourceid,pageurl,contributorid from resources where resourceid = '%s' ", $_REQUEST['resourceid']), OBJECT);
        $firstname = $wpdb->get_row(sprintf("SELECT meta_value from cur_usermeta where meta_key = 'first_name' and user_id = '%s' ", $resource->contributorid), OBJECT)->meta_value;
        $firstname = trim($firstname) ? $firstname : $wpdb->get_row(sprintf("SELECT trim(concat(firstname,' ',lastname)) as name from users where userid = '%s' ", $resource->contributorid), OBJECT)->name;
        $email = $wpdb->get_row(sprintf("SELECT user_email from cur_users  where ID = '%s'", $resource->contributorid), OBJECT)->user_email;
        //$email = 'furqan.curriki@nxvt.com';

        $wpdb->query("UPDATE resources set "
                . "active= '" . ($_REQUEST['status'] == 'T' ? 'T' : 'F') . "', "
                . "remove= '" . ($_REQUEST['status'] == 'T' ? 'F' : 'T') . "', "
                . "resourcechecked = '" . $_REQUEST['status'] . "', "
                . "resourcecheckdate = '" . date('Y-m-d H:i:s') . "', "
                . "resourcecheckid = " . $current_user->ID . ", "
                . "resourcechecknote = '" . $_REQUEST['notes'] . "' "
                . "where resourceid = '" . $_REQUEST['resourceid'] . "'");

        if ($_REQUEST['status'] == 'I') {
            $subject = 'Curriki Resource Improvement Required';
            $message = sprintf('Dear %s,

Thank you contributing an educational resource to the Curriki collection.  Your participation in the Curriki community is much appreciated.  The following resource you contributed was reviewed and has been identified as needing improvement for the following reason(s):

Resource: %s/oer/%s

Improvement Requested: %s

Please make the requested changes to ensure that your resource can be widely available to the Curriki community.  Let us know when you\'ve done that in a reply to this email. Thank you for being a valued member of Curriki!

Sincerely,
Curriki', $firstname, site_url(), $resource->pageurl, $_REQUEST['notes']);

            $headers = sprintf('To: %s <%s>', $firstname, $email) . "\r\n";
            $headers .= 'From: Curriki Reviewer<reviewer@curriki.org>' . "\r\n";
            $headers .= 'Cc: Curriki Reviewer<reviewer@curriki.org>' . "\r\n";
            @mail($email, $subject, $message, $headers);
        }
    }

    if (isset($_REQUEST['_wpnonce'])) {
        unset($_REQUEST['_wpnonce']);
        unset($_REQUEST['_wp_http_referer']);
        header('Location: ?' . http_build_query(array_unique($_REQUEST)));
    }
    curriki_load_views($view, $data);
}

function curriki_broken_links() {
    global $wpdb;
    $view = 'broken_links/index';
    $data = array();

    if (isset($_REQUEST['_wpnonce'])) {
        unset($_REQUEST['_wpnonce']);
        unset($_REQUEST['_wp_http_referer']);
        header('Location: ?' . http_build_query(array_unique($_REQUEST)));
    }

    curriki_load_views($view, $data);
}

function curriki_demo_requests() {
    global $wpdb;
    $view = 'demo_requests/index';
    $data = array();

    if (isset($_REQUEST['_wpnonce'])) {
        unset($_REQUEST['_wpnonce']);
        unset($_REQUEST['_wp_http_referer']);
        header('Location: ?' . http_build_query(array_unique($_REQUEST)));
    }

    curriki_load_views($view, $data);
}

function curriki_link_logs() {
    global $wpdb;
    $view = 'link_logs/index';
    $data = array();

    if (isset($_REQUEST['_wpnonce'])) {
        unset($_REQUEST['_wpnonce']);
        unset($_REQUEST['_wp_http_referer']);
        header('Location: ?' . http_build_query(array_unique($_REQUEST)));
    }

    curriki_load_views($view, $data);
}

function curriki_res_review() {
    global $wpdb;
    $view = 'res_review_list';
    $data = array('');
    if (isset($_GET['remove'])) {
//        $sql = "UPDATE resources
//            SET resources.reviewstatus = 'none'
//            WHERE pageurl LIKE '{$_GET['remove']}'";
//        echo $sql;
//        die();
        $updated = $wpdb->update('resources', ['reviewstatus' => 'none'], ['pageurl' => $_GET['remove']]);
        if ($updated) {
            $_SESSION['message'] = 'Resource Removed Successfully!';
        }
    }
    if (!empty($_GET['action'])) {
        switch ($_GET['action']) {
            case 'review_finalize':
                $view = 'res_review_final';
                $current_user = wp_get_current_user();
                // Prepared statement added
                /*
                $data = $wpdb->get_results("SELECT resourceid, title, description, content,Standardsalignment,Standardsalignmentcomment,
            Subjectmatter,Subjectmattercomment,Supportsteaching,Supportsteachingcomment,Assessmentsquality,
            Assessmentsqualitycomment,Interactivityquality,Interactivityqualitycomment,Instructionalquality,
            Instructionalqualitycomment,Deeperlearning,Deeperlearningcomment
            FROM resources WHERE pageurl = '" . trim($_GET['pageurl'], '/') . "'", OBJECT);
                 * 
                 */
                
                $data = $wpdb->get_results( $wpdb->prepare( 
                            "
                                SELECT resourceid, title, description, content,Standardsalignment,Standardsalignmentcomment,
                                Subjectmatter,Subjectmattercomment,Supportsteaching,Supportsteachingcomment,Assessmentsquality,
                                Assessmentsqualitycomment,Interactivityquality,Interactivityqualitycomment,Instructionalquality,
                                Instructionalqualitycomment,Deeperlearning,Deeperlearningcomment
                                FROM resources WHERE pageurl = %s
                            ", 
                            trim($_GET['pageurl'], '/')
                    ), OBJECT );
                
                $data = $data[0];
                $count = 0;
                $sum = 0;

                if ($data->Standardsalignment != null && $data->Standardsalignment >= 0) {
                    $sum += $data->Standardsalignment;
                    $count ++;
                }
                if ($data->Subjectmatter != null && $data->Subjectmatter >= 0) {
                    $sum += $data->Subjectmatter;
                    $count ++;
                }
                if ($data->Supportsteaching != null && $data->Supportsteaching >= 0) {
                    $sum += $data->Supportsteaching;
                    $count ++;
                }
                if ($data->Assessmentsquality != null && $data->Assessmentsquality >= 0) {
                    $sum += $data->Assessmentsquality;
                    $count ++;
                }
                if ($data->Interactivityquality != null && $data->Interactivityquality >= 0) {
                    $sum += $data->Interactivityquality;
                    $count ++;
                }
                if ($data->Instructionalquality != null && $data->Instructionalquality >= 0) {
                    $sum += $data->Instructionalquality;
                    $count ++;
                }
                if ($data->Deeperlearning != null && $data->Deeperlearning >= 0) {
                    $sum += $data->Deeperlearning;
                    $count ++;
                }

                $aevrage = $count ? round($sum / $count, 1) : -1;

                $wpdb->query("UPDATE resources set "
                        . " reviewstatus = 'reviewed', "
                        . " lastreviewdate = current_date(), "
                        . " reviewedbyid = " . $current_user->ID . ", "
                        . " reviewrating = " . $aevrage
                        . " where pageurl = '" . trim($_GET['pageurl'], '/') . "'");

                $data = array('');
                break;
            case 'review_result':
                $view = 'res_review_result';
                
                //prepared statement added
                /*
                $data = $wpdb->get_results("SELECT resourceid, title, description, content,Standardsalignment,Standardsalignmentcomment,
            Subjectmatter,Subjectmattercomment,Supportsteaching,Supportsteachingcomment,Assessmentsquality,
            Assessmentsqualitycomment,Interactivityquality,Interactivityqualitycomment,Instructionalquality,
            Instructionalqualitycomment,Deeperlearning,Deeperlearningcomment
            FROM resources WHERE pageurl = '" . trim($_GET['pageurl'], '/') . "'", OBJECT);
                 * 
                 */
                
                $data = $wpdb->get_results( $wpdb->prepare( 
                            "
                                SELECT resourceid, title, description, content,Standardsalignment,Standardsalignmentcomment,
                                Subjectmatter,Subjectmattercomment,Supportsteaching,Supportsteachingcomment,Assessmentsquality,
                                Assessmentsqualitycomment,Interactivityquality,Interactivityqualitycomment,Instructionalquality,
                                Instructionalqualitycomment,Deeperlearning,Deeperlearningcomment
                                FROM resources WHERE pageurl =  %s
                            ", 
                            trim($_GET['pageurl'], '/')
                    ), OBJECT );
                break;
            case 'review_resource':
                $view = 'res_review_edit';
                // prepared statement added
                /*
                $data = $wpdb->get_results("SELECT resourceid, title,Standardsalignment,Standardsalignmentcomment,
            Subjectmatter,Subjectmattercomment,Supportsteaching,Supportsteachingcomment,Assessmentsquality,
            Assessmentsqualitycomment,Interactivityquality,Interactivityqualitycomment,Instructionalquality,
            Instructionalqualitycomment,Deeperlearning,Deeperlearningcomment
            FROM resources WHERE pageurl = '" . trim($_GET['pageurl'], '/') . "'", OBJECT);
                 * 
                 */
                
                
                $data = $wpdb->get_results( $wpdb->prepare( 
                            "
                                SELECT resourceid, title,Standardsalignment,Standardsalignmentcomment,
                                Subjectmatter,Subjectmattercomment,Supportsteaching,Supportsteachingcomment,Assessmentsquality,
                                Assessmentsqualitycomment,Interactivityquality,Interactivityqualitycomment,Instructionalquality,
                                Instructionalqualitycomment,Deeperlearning,Deeperlearningcomment
                                FROM resources WHERE pageurl = %s
                            ", 
                            trim($_GET['pageurl'], '/')
                    ), OBJECT );
                break;
        }
    }

    if (isset($_REQUEST['_wpnonce'])) {
        unset($_REQUEST['_wpnonce']);
        unset($_REQUEST['_wp_http_referer']);
        header('Location: ?' . http_build_query(array_unique($_REQUEST)));
    }
    curriki_load_views($view, $data[0]);
}

function resource_files() {

    global $wpdb;
    $data = array();
    if (!empty($_REQUEST['action']) AND trim($_REQUEST['action']) == 'uploaded') {
        include_once(dirname(dirname(__DIR__)) . '/libs/functions.php');

        $files = $_FILES['resourcefiles'];
        foreach ($files['name'] as $i => $f) {
            $data['file' . $i] = array();
            $_FILES['file' . $i] = array(
                'name' => $files['name'][$i],
                'type' => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error' => $files['error'][$i],
                'size' => $files['size'][$i]
            );

            validateUploadFile('file' . $i, $_REQUEST['type'], $data['file' . $i]);
            if ($data['file' . $i]['status']) {
                uploadFileS3($data['file' . $i]);
                $data['file' . $i] = getEmbedHTML($data['file' . $i]);
            }

            $row = array('resourceid' => intval($_REQUEST['resourceid']));
            $row['fileid'] = NULL;
            $row['filename'] = substr($data['file' . $i]['filename'], 0, 500);
            $row['uploaddate'] = date('Y-m-d H:i:s');
            $row['uniquename'] = substr($data['file' . $i]['uniquename'], 0, 500);
            $row['ext'] = substr($data['file' . $i]['ext'], 0, 10);
            $row['active'] = "T";
            $row['folder'] = substr($data['file' . $i]['folder'], 0, 100);
            $row['SDFstatus'] = $data['file' . $i]['SDFstatus'];
            $row['transcoded'] = $data['file' . $i]['transcoded'];
            $row['s3path'] = substr($data['file' . $i]['url'], 0, 200);

            $wpdb->insert('resourcefiles', $row, array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'));
            $data['file' . $i]['fileid'] = $wpdb->insert_id;
        }
    }
    curriki_load_views('resource_files_upload', $data);
}

function curriki_user_manage() {
    global $wpdb;
    $view = 'user_manage_list';
    $data = array();
    global $wpdb;

    if (!empty($_REQUEST['action']) AND ! empty($_REQUEST['userid'])) {
        if ($_REQUEST['action'] == 'edited') {
            $current_user = wp_get_current_user();
            update_user_meta($_REQUEST['userid'], 'first_name', $_REQUEST['firstname']);
            update_user_meta($_REQUEST['userid'], 'last_name', $_REQUEST['lastname']);

            $wpdb->query("UPDATE cur_users set "
                    . "display_name = '" . esc_sql($_REQUEST['firstname'] . ' ' . $_REQUEST['lastname']) . "', "
                    . "user_email= '" . esc_sql($_REQUEST['email']) . "', "
                    . "user_status= '" . intval(isset($_REQUEST['active'])) . "' "
                    . "where ID = '" . $_REQUEST['userid'] . "'");

            $wpdb->query("UPDATE users set "
                    . "firstname = '" . $_REQUEST['firstname'] . "', "
                    . "lastname = '" . $_REQUEST['lastname'] . "', "
                    . "active = '" . ($_REQUEST['active'] ? 'T' : 'F') . "' "
                    . ($_REQUEST['active'] ? '' : ", inactivedate = '" . date('Y-m-d H:i:s') . "' ")
                    . "where userid = '" . $_REQUEST['userid'] . "'");

            if (isset($_REQUEST['notify'])) {
                $subject = 'Curriki user info changed';
                $message = get_option('user_info_changed_notification', 'Hello, Your Curriki User Information is changed by curriki admin. Please login to view changes. Thanks, Curriki Team');
                $headers = 'From: Curriki Admin <admin@curriki.org>' . "\r\n";
                @mail($_REQUEST['email'], $subject, $message, $headers);
            }
        } else if ($_REQUEST['action'] == 'edit') {
            $view = 'user_manage_edit';
            
//            Prepared statement added
            /*
            $data = $wpdb->get_results("select u.active,u.registerdate,u.inactivedate,u.membertype,u.sitename,"
                    . "wpu.user_email, wpu.user_login, u.userid,u.membertype,u.firstname,u.lastname "
                    . " from users u join cur_users as wpu on wpu.ID = u.userid where u.userid = '" . $_REQUEST['userid'] . "' ", OBJECT);
             * 
             */
            
            $data = $wpdb->get_results( $wpdb->prepare( 
                            "
                                select u.active,u.registerdate,u.inactivedate,u.membertype,u.sitename,"
                                . "wpu.user_email, wpu.user_login, u.userid,u.membertype,u.firstname,u.lastname "
                                . " from users u join cur_users as wpu on wpu.ID = u.userid where u.userid = %d
                            ", 
                            $_REQUEST['userid']
                    ), OBJECT );
        }
    }

    if (isset($_REQUEST['_wpnonce'])) {
        unset($_REQUEST['_wpnonce']);
        unset($_REQUEST['_wp_http_referer']);
        header('Location: ?' . http_build_query(array_unique($_REQUEST)));
    }
    curriki_load_views($view, $data);
}

function check_resource_rating() {
    if (isset($_REQUEST['q']) || (isset($_GET['action']) && in_array($_GET['action'], array('review_resource', 'review_result', 'review_finalize')))) {
        if (isset($_REQUEST['q'])) {
            global $wpdb;
            $resourcename = str_replace(array('http://www.', 'https://www.', 'http://', 'https://', 'curriki.org/xwiki/bin/view/', '/'), array('', '', '', '', '', '.'), $_REQUEST['q']);
            $_GET['pageurl'] = $wpdb->get_row(sprintf("SELECT pageurl FROM resources where oldurl = '%s' or pageurl = '%s' ", $_REQUEST['q'], $_REQUEST['q']))->pageurl;
        }
        curriki_res_review();
        ?>

        <script>
            var $q = '<?php echo $_REQUEST['q']; ?>';
        </script>

        <?php
    }
}

////////////////////////////////////////////
// Mark Featured Controller and Ajax Calls//
////////////////////////////////////////////
function mark_featured() {
    global $wpdb;
    $view = 'featured_list';
    $data = array();
    if (isset($_REQUEST['submit'])) {

        $wpdb->query("DELETE FROM featureditems where location = '" . $_REQUEST['location'] . "'");
        $seq = 1;
        $upload_dir = WP_CONTENT_DIR . '/uploads/images/';

        foreach ($_REQUEST['featuredid'] as $i => $featured) {

            $image = '';
            if (!empty($_FILES['featuredimage']['name'][$i]) && $_FILES['featuredimage']['error'][$i] == UPLOAD_ERR_OK) {
                $image = time() . $i . '.' . basename($_FILES['featuredimage']['name'][$i], PATHINFO_EXTENSION);
                move_uploaded_file($_FILES['featuredimage']['tmp_name'][$i], $upload_dir . $image);
            } else {
                $image = $_REQUEST['featuredimage'][$i];
            }

            $query = "INSERT INTO featureditems set " .
                    "location = '" . $_REQUEST['location'] . "'" .
                    ",itemidtype = '" . $_REQUEST['itemidtype'][$i] . "'" .
                    ",itemid = '" . $_REQUEST['featuredid'][$i] . "'" .
                    ",featuredstartdate = '" . addslashes($_REQUEST['startdate'][$i]) . " 03:00:00'" .
                    ",featuredenddate = '" . addslashes($_REQUEST['enddate'][$i]) . " 03:00:00'" .
                    ",displaytitle = '" . addslashes(str_replace(array('\"', "\'"), array('"', "'"), $_REQUEST['featuredtitle'][$i])) . "'" .
                    ",featuredtext = '" . addslashes(str_replace(array('\"', "\'"), array('"', "'"), $_REQUEST['featuredtext'][$i])) . "'" .
                    ",link = '" . addslashes($_REQUEST['featuredlink'][$i]) . "'" .
                    ",image = '" . $image . "'" .
                    ",displayseqno = '" . $seq . "'" .
                    ",active = '" . ($_REQUEST['featuredactive'][$_REQUEST['featuredid'][$i]] ? 'T' : 'F') . "'" .
                    ",featured = '" . ($_REQUEST['featuredfeatured'][$_REQUEST['featuredid'][$i]] ? 'T' : 'F') . "'";

            $wpdb->query($query);
            $seq++;
        }
    }

    //$data['all_groups'] = $wpdb->get_results("SELECT id,name FROM cur_bp_groups where id not in (SELECT groupid from groups where featured = 'T' and featuredenddate >current_date())", OBJECT);
    $data['featured'] = $wpdb->get_results("SELECT * from featureditems order by location, displayseqno asc", OBJECT);
    $data['subjects'] = $wpdb->get_results("SELECT * from subjects WHERE subjectid IN (1,2,7,9,10,11,13) order by displayname asc", OBJECT);
    curriki_load_views($view, $data);
}

add_action('wp_ajax_get_user', 'ajax_get_user');

function ajax_get_user() {
    ob_clean();
    global $wpdb; // this is how you get access to the database
    $user = $wpdb->get_results("select u.userid,ifnull(u.lastname, ifnull(u.firstname, cu.user_login)) as lastname from cur_users cu inner join users u on cu.id = u.userid where BINARY LOWER(cu.user_login) = '" . strtolower($_POST['query']) . "'");
    echo json_encode($user[0]);
    wp_die(); // this is required to terminate immediately and return a proper response
}

add_action('wp_ajax_get_resource', 'ajax_get_resource');

function ajax_get_resource() {
    ob_clean();
    global $wpdb; // this is how you get access to the database
    $where = '';
    if (strpos($_POST['query'], 'id=')) {
        $_POST['query'] = intval(current(explode('&', end(explode('id=', $_POST['query'])))));
        $where = "where resourceid  = '" . $_POST['query'] . "'";
    } else {
        // exploding to find the slug
        $where = "where pageurl like '" . end(explode('/', trim($_POST['query'], '/'))) . "'";
    }

    $res = $wpdb->get_results("select resourceid,ifnull(title,substring(oldurl, 35)) title from resources $where");
    echo json_encode($res[0]);
    wp_die(); // this is required to terminate immediately and return a proper response
}

add_action('wp_ajax_get_collection', 'ajax_get_collection');

function ajax_get_collection() {
    ob_clean();
    global $wpdb; // this is how you get access to the database
    $where = '';
    $response = [];

    if (strpos($_POST['query'], 'comm_url=')) {
        $_POST['query'] = current(explode('&', end(explode('comm_url=', $_POST['query']))));
    } else if (strpos($_POST['query'], '/community/')) {
        $_POST['query'] = end(explode('/', trim($_POST['query'], '/')));
    } else {
        if (strpos($_POST['query'], 'id=')) {
            $_POST['query'] = intval(current(explode('&', end(explode('id=', $_POST['query'])))));
            $where = "where resourceid  = '" . $_POST['query'] . "'";
        } else {
            // exploding to find the slug
            $where = "where pageurl like '" . end(explode('/', trim($_POST['query'], '/'))) . "'";
        }

        $res = $wpdb->get_results("select resourceid,ifnull(title,substring(oldurl, 35)) title from resources $where");
        $itemObject = $res[0];
        $response['id'] = $itemObject->resourceid;
        $response['title'] = $itemObject->title;
        $response['type'] = 'collection';
    }

    if (count($response) == 0) {
        $itemObject = $wpdb->get_row( $wpdb->prepare( "select * from communities where url = %s", $_POST['query'] ) );
        $response['id'] = $itemObject->communityid;
        $response['title'] = $itemObject->name;
        $response['type'] = 'community';
    }

    echo json_encode($response);
    wp_die(); // this is required to terminate immediately and return a proper response
}

add_action('wp_ajax_get_group', 'ajax_get_group');

function ajax_get_group() {
    ob_clean();
    global $wpdb; // this is how you get access to the database
//    $user = $wpdb->get_results("select id,name,slug,description from cur_bp_groups where status = 'public' and BINARY slug = '" . $_POST['query'] . "'");     // prepared statement added
    $user = $wpdb->get_results( $wpdb->prepare( 
            "
                select id,name,slug,description from cur_bp_groups where status = 'public' and BINARY slug = %s
            ", 
            $_POST['query']
    ) );
    echo json_encode($user[0]);
    wp_die(); // this is required to terminate immediately and return a proper response
}

///////////////////////////
// iContact Update Plugin//
///////////////////////////
function icontact_update() {

    global $wpdb;
    $updates = $wpdb->get_row("SELECT COUNT(*) AS size from icontactupdates where status = 'N'");
    $view = 'icontact_update';
    $data['size'] = $updates->size;
    curriki_load_views($view, $data);

//    $updates = $wpdb->get_results("SELECT * from icontactupdates where status = 'N' LIMIT 1");
//    echo '<h2>I-Contact Updates</h2>';
//
//    if (!empty($_REQUEST['run_update']) && count($updates)) {
//        define('ACC_ID', '973153');
//        define('CLIENT_FD_ID', '45691');
//        define('LIST_ID', '88565');
//
//        $dir = __DIR__;
//
//        @require_once($dir . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'iContactApi.php');
//        iContactApi::getInstance()->setConfig(array(
//            'appId' => '5MoochZy3kF5hQq7YLj0Es6nktFsEXRz',
//            'apiPassword' => 'Tele$marty986/^&*',
//            'apiUsername' => 'janetpinto'
//        ));
//
//        $oiContact = iContactApi::getInstance();
//        $oiContact->setAccountId(ACC_ID);
//        $oiContact->setClientFolderId(CLIENT_FD_ID);
//        //$oiContact->useSandbox(true);
//
//        $statuses = array();
//        foreach ($updates as $update) {
//            try {
//                $initialTime = time();
//                switch ($update->source) {
//                    case 'users':
//                        echo $email = $wpdb->get_row(sprintf("select user_email from cur_users WHERE ID = '%s' ", $update->sourceid), OBJECT)->user_email;
//                        echo ' - ';
//                        echo $name = trim(get_user_meta($update->sourceid, 'first_name', true) . ' ' . get_user_meta($update->sourceid, 'last_name', true));
//                        if (empty($name))
//                            echo $name = $wpdb->get_row(sprintf("select concat(firstname,' ',lastname) as name from users WHERE userID = '%s' ", $update->sourceid), OBJECT)->name;
//                        echo '<br/>';
//                        break;
//
//                    case 'newsletters':
//                        echo $email = $wpdb->get_row(sprintf("select email from newsletters WHERE newslettersid = '%s' ", $update->sourceid), OBJECT)->email;
//                        echo ' - ';
//                        echo $name = $wpdb->get_row(sprintf("select name from newsletters WHERE newslettersid = '%s' ", $update->sourceid), OBJECT)->name;
//                        echo '<br/>';
//                        break;
//                }
//
//
//                switch ($update->type) {
//                    //*********************Add********************** //
//                    case 'A':
//                        $response = $oiContact->addContact(
//                                $sEmail = $email, $sStatus = 'normal', $sPrefix = '', $sFirstName = $name, $sLastName = '', $sSuffix = '', $sStreet = '', $sStreet2 = '', $sCity = '', $sState = '', $sPostalCode = '', $sPhone = '', $sFax = '', $sBusiness = ''
//                        );
//                        $response2 = $oiContact->subscribeContactToList($response->contactId, LIST_ID, 'normal');
//                        $statuses[$update->updateid] = '1';
//                        break;
//                    case 'R':
//                        //*********************Delete********************** //
//                        $contacts = $oiContact->getContactBy('email', $email);
//                        //print_r($contacts);
//                        $contact = $contacts->contacts[0];
//                        if (!empty($contact->contactId))
//                            $response = $oiContact->deleteContact($contact->contactId);
//                        $statuses[$update->updateid] = '1';
//                        break;
//                }
//                $total = time() - $initialTime;
//                echo "(Time: ".($total)." seconds)<br />";
//                $wpdb->query("UPDATE icontactupdates set status = 'P', processdate = '" . date('Y-m-d H:i:s') . "' where updateid = '" . $update->updateid . "'");
//            } catch (Exception $oException) {
//                $error = 'ERROR:'
//                        . print_r($oiContact->getErrors(), 1)
//                        . print_r($oiContact->getLastRequest(), 1)
//                        . print_r($oiContact->getLastResponse(), 1);
//                $statuses[] = '0';
//            }
//        }
//    }
//
//    if (count($statuses)) {
//        $vals = array_count_values($statuses);
//        echo '<p>Update is done. <strong><br>Success Records: ' . intval($vals[1]) . ' <br>Failed Records:' . intval($vals[0]) . '</strong> !</p>';
//    } elseif (count($updates)) {
//        echo '<p>Currently there is <strong>' . count($updates) . '</strong> Update pending (50 at a time)!</p>';
//        echo '<p><a href="admin.php?page=icontact_update&run_update=true">Click Here</a> to run Update</p>';
//    } else {
//        echo '<p>Currently there is no Update pending !</p>';
//    }
}

add_action('wp_ajax_nopriv_icontactajax_update', 'ajax_icontactajax_update');
add_action('wp_ajax_icontactajax_update', 'ajax_icontactajax_update');

function ajax_icontactajax_update() {

    global $wpdb;

    $sql = "SELECT icontactupdates.source, icontactupdates.type, icontactupdates.sourceid, icontactupdates.updateid,   cur_users.user_email, concat(users.firstname,' ',users.lastname) as name, newsletters.email as nl_email, newsletters.name as nl_name
from icontactupdates
left outer JOIN cur_users
ON icontactupdates.sourceid = cur_users.ID
left outer JOIN users
ON icontactupdates.sourceid  = users.userID
left outer JOIN newsletters
ON icontactupdates.sourceid = newsletters.newslettersid
where icontactupdates.status = 'N' LIMIT 5";
    $updates = $wpdb->get_results($sql);
//    $updates = $wpdb->get_results("SELECT * from icontactupdates where status = 'N' LIMIT 5");
//    echo '<h2>I-Contact Updates</h2>';

    if (!empty($_REQUEST['run_update']) && count($updates)) {

        define('ACC_ID', '973153');
        define('CLIENT_FD_ID', '45691');
        define('LIST_ID', '88565');

        $dir = __DIR__;

        @require_once($dir . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'iContactApi.php');
        iContactApi::getInstance()->setConfig(array(
            'appId' => '5MoochZy3kF5hQq7YLj0Es6nktFsEXRz',
            'apiPassword' => 'Tele$marty986/^&*',
            'apiUsername' => 'janetpinto'
        ));

        $oiContact = iContactApi::getInstance();
        $oiContact->setAccountId(ACC_ID);
        $oiContact->setClientFolderId(CLIENT_FD_ID);
        //$oiContact->useSandbox(true);
        $msg = '';
        $statuses = array();
        $totalTime = 0;
        foreach ($updates as $update) {

//            $initialTime = time();
            try {

                switch ($update->source) {
                    case 'users':
                        $msg .= $email = $update->user_email;
                        $msg .= ' - ';
                        $msg .= $name = trim(get_user_meta($update->sourceid, 'first_name', true) . ' ' . get_user_meta($update->sourceid, 'last_name', true));
                        if (empty($name))
                            $msg .= $name = $update->name;
//                        $msg .= '<br/>';
                        break;

                    case 'newsletters':
                        $msg .= $email = $update->nl_email;
                        $msg .= ' - ';
                        $msg .= $name = $update->nl_name;
//                        $msg .= '<br/>';
                        break;
                }
                $initialTime = round(microtime(true) * 1000);

                switch ($update->type) {
                    //*********************Add********************** //
                    case 'A':
                        $response = $oiContact->addContact(
                                $sEmail = $email, $sStatus = 'normal', $sPrefix = '', $sFirstName = $name, $sLastName = '', $sSuffix = '', $sStreet = '', $sStreet2 = '', $sCity = '', $sState = '', $sPostalCode = '', $sPhone = '', $sFax = '', $sBusiness = ''
                        );
                        $response2 = $oiContact->subscribeContactToList($response->contactId, LIST_ID, 'normal');
                        $statuses[$update->updateid] = '1';
                        break;
                    case 'R':
                        //*********************Delete********************** //
                        $contacts = $oiContact->getContactBy('email', $email);
                        //print_r($contacts);
                        $contact = $contacts->contacts[0];
                        if (!empty($contact->contactId))
//                            $response = $oiContact->deleteContact($contact->contactId);
                            $statuses[$update->updateid] = '1';
                        break;
                }
                $total = round(microtime(true) * 1000) - $initialTime;
                $totalTime += $total;
//                $total = time() - $initialTime;

                $wpdb->query("UPDATE icontactupdates set status = 'P', processdate = '" . date('Y-m-d H:i:s') . "' where updateid = '" . $update->updateid . "'");
                $msg .= "(Time: " . ($total) . " ms)<br />";
            } catch (Exception $oException) {
                $error = 'ERROR:'
                        . print_r($oiContact->getErrors(), 1)
                        . print_r($oiContact->getLastRequest(), 1)
                        . print_r($oiContact->getLastResponse(), 1);
                $statuses[] = '0';
            }
        }
    }

    if (count($statuses)) {
        $vals = array_count_values($statuses);
//        $msg .= '<p>Update is done. <strong><br>Success Records: ' . intval($vals[1]) . ' <br>Failed Records:' . intval($vals[0]) . '</strong> !</p>';
    } elseif (count($updates)) {
//        $msg .= '<p>Currently there is <strong>' . count($updates) . '</strong> Update pending (50 at a time)!</p>';
//        $msg .= '<p><a href="admin.php?page=icontact_update&run_update=true">Click Here</a> to run Update</p>';
    } else {
//        $msg .= '<p>Currently there is no Update pending !</p>';
    }

//    $msg .= "<br />Records count = 5 <br />(Total Time: ".($totalTime/1000.00)." seconds)<br />";
    $time = $totalTime;
    echo json_encode(["msg" => $msg, "time" => $time]);
    wp_die();
}

function community_pages_admin_view() {
    $view = 'community_pages/index';
    $data = array();

    $dir = __DIR__;
     @include_once($dir . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $view . '.php');    
}
function curriki_reporting_admin() {
    $view = 'reporting/index';    
    $data = array();

    $dir = __DIR__;
     @include_once($dir . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $view . '.php');    
}

function hierarchial_children() {
    $view = 'hierarchial_children';
    $data = array();
    curriki_load_views($view);
}

add_action('wp_ajax_nopriv_hierarchial_child', 'ajax_hierarchial_child');
add_action('wp_ajax_hierarchial_child', 'ajax_hierarchial_child');

function ajax_hierarchial_child() {
    global $wpdb; // this is how you get access to the database
//    $res = $wpdb->get_results("SELECT * FROM resources ORDER BY RAND() LIMIT 15", ARRAY_A);
//    $data = array();
//    $count = 0;
//    foreach ($res as $row) {
//        $data[$count]['resourceid'] = $row['resourceid'];
//        $data[$count++]['title'] = $row['title'];
//    }
    check_ajax_referer('my-special-string', 'security');
    if (!isset($_REQUEST['pageurl'])) {
        echo null;
        wp_die();
    }

    $startdate = isset($_REQUEST['startdate']) ? $_REQUEST['startdate'] : '';
    $enddate = isset($_REQUEST['enddate']) ? $_REQUEST['enddate'] : '';


    $children = findChildren($_REQUEST['pageurl'], $startdate, $enddate);
    echo json_encode($children);
    wp_die();
}

function findChildren($collectionid, $startdate = '', $enddate = '', &$return = array(), &$count = -1, &$leafcount = 0, &$leafarr = array(), &$temp_arr = array()) {
    global $wpdb; // this is how you get access to the database
    $temp_parentresourceid = null;


    // Perform queries 
    // First time search from pageurl and next time search from collectionids
    if ($count >= 0):
        $children_res = $wpdb->get_results($wpdb->prepare(
                        "SELECT collectionelements.collectionid as parentresourceid, 
            collectionelements.resourceid as resourceid,
            resources.pageurl as parentpageurl
            FROM collectionelements
            INNER JOIN resources ON collectionelements.collectionid=resources.resourceid
            WHERE collectionelements.collectionid = %d", $collectionid
        ));
    else:
        $children_res = $wpdb->get_results($wpdb->prepare(
                        "SELECT collectionelements.collectionid as parentresourceid, 
            collectionelements.resourceid as resourceid,
            resources.pageurl as parentpageurl
            FROM collectionelements
            INNER JOIN resources ON collectionelements.collectionid=resources.resourceid
            WHERE resources.pageurl = %s", $collectionid
        ));
    endif;
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
        // Get Page Views Count
        if ($startdate != '' && $enddate != '') {
            $res_page_views = $wpdb->get_row($wpdb->prepare(
                            "SELECT COUNT(resourceid) as pageviews FROM resourceviews
                            WHERE resourceid = %d
                            and (viewdate BETWEEN %s AND DATE_ADD(%s, INTERVAL 1 DAY))
                            ", $child->resourceid, $startdate, $enddate
            ));
        } else {
            $res_page_views = $wpdb->get_row($wpdb->prepare(
                            "SELECT COUNT(*) as pageviews FROM resourceviews WHERE resourceid = %d", $child->resourceid
            ));
        }

        // Select child resources to get their details
        $child_res_data = $wpdb->get_row($wpdb->prepare(
                        "SELECT * FROM resources 
                            WHERE resourceid = %d
                            ", $child->resourceid
        ));


        $pageurl = $child_res_data->pageurl;
        $pageviews = $res_page_views->pageviews;


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
            'pageviews' => $pageviews,
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

function findChildrenNLeafnodes($collectionid, $startdate = '', $enddate = '', &$return = array(), &$count = -1, &$leafcount = 0, &$leafarr = array(), &$temp_arr = array()) {
    global $wpdb; // this is how you get access to the database
    $temp_parentresourceid = null;


    // Perform queries 
    // First time search from pageurl and next time search from collectionids
    if ($count >= 0):
        $children_res = $wpdb->get_results($wpdb->prepare(
                        "SELECT collectionelements.collectionid as parentresourceid, 
            collectionelements.resourceid as resourceid,
            resources.pageurl as parentpageurl
            FROM collectionelements
            INNER JOIN resources ON collectionelements.collectionid=resources.resourceid
            WHERE collectionelements.collectionid = %d", $collectionid
        ));
    else:
        $children_res = $wpdb->get_results($wpdb->prepare(
                        "SELECT collectionelements.collectionid as parentresourceid, 
            collectionelements.resourceid as resourceid,
            resources.pageurl as parentpageurl
            FROM collectionelements
            INNER JOIN resources ON collectionelements.collectionid=resources.resourceid
            WHERE resources.pageurl = %s", $collectionid
        ));
    endif;
    $current_user = wp_get_current_user();
    if ($current_user->user_login == 'wpadmin') {
        if ($wpdb->num_rows == 0) {
            if (!in_array($collectionid, $leafarr)) {
                $leafarr[] = $collectionid;
            }

//            ++$leafcount;
//            wp_die();
        }
//       var_dump($wpdb->num_rows) ;
//       var_dump($user->user_login) ;
    }



    // $c is the counter
    $c = 0;
    $cou = 0;
    foreach ($children_res as $child) {
        // Get Page Views Count
        if ($startdate != '' && $enddate != '') {
            $res_page_views = $wpdb->get_row($wpdb->prepare(
                            "SELECT COUNT(resourceid) as pageviews FROM resourceviews
                            WHERE resourceid = %d
                            and (viewdate BETWEEN %s AND DATE_ADD(%s, INTERVAL 1 DAY))
                            ", $child->resourceid, $startdate, $enddate
            ));
        } else {
            $res_page_views = $wpdb->get_row($wpdb->prepare(
                            "SELECT COUNT(*) as pageviews FROM resourceviews WHERE resourceid = %d", $child->resourceid
            ));
        }

        // Select child resources to get their details
        $child_res_data = $wpdb->get_row($wpdb->prepare(
                        "SELECT * FROM resources 
                            WHERE resourceid = %d
                            ", $child->resourceid
        ));


        $pageurl = $child_res_data->pageurl;
        $pageviews = $res_page_views->pageviews;


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
        $return[$count]['leafarr'] = $leafarr;

        $children = array(
            'resourceid' => $child->resourceid,
            'childpageurl' => $pageurl,
            'pageviews' => $pageviews,
        );

        $return[$count]['children'][$c] = $children;

        $temp_parentresourceid = $child->parentresourceid;

        // $temp_arr to be loop through resource ids
        $temp_arr[$c++]['resourceid'] = $child->resourceid;
    }
    foreach ($temp_arr as $ar):
        findChildrenNLeafnodes($ar['resourceid'], $startdate, $enddate, $return, $count, $leafcount, $leafarr);
    endforeach;

    return $return;
}

add_action('wp_ajax_nopriv_export_excel', 'ajax_export_excel');
add_action('wp_ajax_export_excel', 'ajax_export_excel');

function ajax_export_excel() {
    check_ajax_referer('my-special-string', 'security');
    global $wpdb; // this is how you get access to the database
    if (!isset($_REQUEST['pageurl'])) {
        echo null;
        wp_die();
    }

    $startdate = isset($_REQUEST['startdate']) ? $_REQUEST['startdate'] : '';
    $enddate = isset($_REQUEST['enddate']) ? $_REQUEST['enddate'] : '';

    $data = findChildren($_REQUEST['pageurl'], $startdate, $enddate);


    ExportToExcel($data);
    wp_die();
}

function ExportToExcel($data) {
    require_once 'PHPExcel/PHPExcel.php';
    // Instantiate a new PHPExcel object
    $objPHPExcel = new PHPExcel();
    // Set the active Excel worksheet to sheet 0
    $objPHPExcel->setActiveSheetIndex(0);
    // Initialise the Excel row number
    $rowCount = 1;
    // Iterate through each result from the SQL query in turn
    // We fetch each database result row into $row in turn
    $objPHPExcel->getActiveSheet()->getStyle("A1:E1")->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, "Parent Resource ID");
    $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, "Parent Page URL");
    $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, "Resource ID");
    $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, "Child Page URL");
    $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, "Page Views");

    // Increment the Excel row counter
    $rowCount++;
    foreach ($data as $row) {
        //    while ($row = mysql_fetch_array($result)) {
        // Set cell An to the "name" column from the database (assuming you have a column called name)
        //    where n is the Excel row number (ie cell A1 in the first row)
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $row['parentresourceid']);
        // Set cell Bn to the "age" column from the database (assuming you have a column called age)
        //    where n is the Excel row number (ie cell A1 in the first row)
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $row['parentpageurl']);
        foreach ($row['children'] as $ch) {
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $ch['resourceid']);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $ch['childpageurl']);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $ch['pageviews']);

            // Increment the Excel row counter
            $rowCount++;
        }
    }

    // Instantiate a Writer to create an OfficeOpenXML Excel .xlsx file
    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    // Write the Excel file to filename some_excel_file.xlsx in the current directory
    $dir_path = __DIR__ . '/PHPExcel/output_files/';
    $fileName = rand(0, getrandmax()) . rand(0, getrandmax()) . ".xlsx";
    $filePath = $dir_path . $fileName;
    $objWriter->save($filePath);
    echo json_encode(array('filename' => $fileName));
    wp_die();
}

add_action('wp_ajax_nopriv_export_excel_children_leafnodes', 'ajax_export_excel_children_leafnodes');
add_action('wp_ajax_export_excel_children_leafnodes', 'ajax_export_excel_children_leafnodes');

function ajax_export_excel_children_leafnodes() {
    check_ajax_referer('my-special-string', 'security');
    global $wpdb; // this is how you get access to the database
    if (!isset($_REQUEST['pageurl'])) {
        echo null;
        wp_die();
    }

    $startdate = isset($_REQUEST['startdate']) ? $_REQUEST['startdate'] : '';
    $enddate = isset($_REQUEST['enddate']) ? $_REQUEST['enddate'] : '';

    $data = findChildrenNLeafnodes($_REQUEST['pageurl'], $startdate, $enddate);

    ExportToExcelChildrenLeafnodes($data);
    wp_die();
}

function ExportToExcelChildrenLeafnodes($data) {
    require_once 'PHPExcel/PHPExcel.php';
    // Instantiate a new PHPExcel object
    $objPHPExcel = new PHPExcel();
    // Set the active Excel worksheet to sheet 0
    $objPHPExcel->setActiveSheetIndex(0);
    // Initialise the Excel row number
    $rowCount = 1;
    // Iterate through each result from the SQL query in turn
    // We fetch each database result row into $row in turn
    $objPHPExcel->getActiveSheet()->getStyle("A1:E1")->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, "Sr #");
    $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, "Nodes");
//    $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, "Resource ID");
//    $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, "Child Page URL");
//    $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, "Page Views");
    // Increment the Excel row counter
    $rowCount++;
    $cnt = 0;
    foreach ($data as $row) {
        //    while ($row = mysql_fetch_array($result)) {
        // Set cell An to the "name" column from the database (assuming you have a column called name)
        //    where n is the Excel row number (ie cell A1 in the first row)
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, ++$cnt);
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $row['parentresourceid']);
        // Set cell Bn to the "age" column from the database (assuming you have a column called age)
        //    where n is the Excel row number (ie cell A1 in the first row)
//        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $row['parentpageurl']);
//        foreach ($row['children'] as $ch) {
//            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $ch['resourceid']);
//            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $ch['childpageurl']);
//            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $ch['pageviews']);
//
//            // Increment the Excel row counter
        $rowCount++;
        $leafarr = $row['leafarr'];
//        }
    }
    $objPHPExcel->getActiveSheet()->getStyle("A")->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle("B")->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, "Sr #");
    $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, "Leaf Nodes");
    $rowCount++;
    $objPHPExcel->getActiveSheet()->getStyle("A")->getFont()->setBold(false);
    $objPHPExcel->getActiveSheet()->getStyle("B")->getFont()->setBold(false);
    foreach ($leafarr as $r) {
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, ++$cnt);
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $r);
        $rowCount++;
    }

    // Instantiate a Writer to create an OfficeOpenXML Excel .xlsx file
    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    // Write the Excel file to filename some_excel_file.xlsx in the current directory
    $dir_path = __DIR__ . '/PHPExcel/output_files/';
    $fileName = rand(0, getrandmax()) . rand(0, getrandmax()) . ".xlsx";
    $filePath = $dir_path . $fileName;
    $objWriter->save($filePath);
    echo json_encode(array('filename' => $fileName));
    wp_die();
}

////////////////////
// Helper Function//
////////////////////
function curriki_load_views($view = '', $data = array()) {
    $dir = __DIR__;
    @include_once($dir . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $view . '.php');
}

function debug($data = array(), $exit = false) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    if ($exit)
        exit;
}

add_action('wp_ajax_nopriv_save_admin_featureditems_ml', 'ajax_save_admin_featureditems_ml');
add_action('wp_ajax_save_admin_featureditems_ml', 'ajax_save_admin_featureditems_ml');

function ajax_save_admin_featureditems_ml() {
    /* echo "<pre>";
      var_dump($_POST);
      die; */
    $data = $_POST["data"];
    ob_clean();
    global $wpdb; // this is how you get access to the database
    $wpdb->delete("featureditems_ml", array(
        "featureditemid" => $_POST["featureditemid"],
        "language" => $data["language"]
            ), array("%d", "%s")
    );
    $wpdb->insert(
            'featureditems_ml', array(
        "featureditemid" => $_POST["featureditemid"],
        "language" => $data["language"],
        "displaytitle" => $data["displaytitle"],
        "featuredtext" => $data["featuredtext"]
            ), array("%d", "%s", "%s", "%s")
    );
    wp_die(); // this is required to terminate immediately and return a proper response
}

add_action('wp_ajax_nopriv_load_admin_featureditems_ml', 'ajax_load_admin_featureditems_ml');
add_action('wp_ajax_load_admin_featureditems_ml', 'ajax_load_admin_featureditems_ml');

function ajax_load_admin_featureditems_ml() {
    /* echo "<pre>";
      var_dump($_POST);
      die; */
    $featureditemid = $_POST["featureditemid"];
    $language = $_POST["language"];
    ob_clean();
    global $wpdb; // this is how you get access to the database
    $ml_row = $wpdb->get_row($wpdb->prepare(
                    "SELECT * FROM featureditems_ml WHERE featureditemid = %d AND language = %s", $featureditemid, $language
    ));
    //echo $wpdb->last_query;die;
    $row = new stdClass();
    $row = $ml_row;
    if ($row) {
        $row->displaytitle = str_replace('\\', '', $row->displaytitle);
        $row->featuredtext = str_replace('\\', '', $row->featuredtext);
        echo json_encode($row);
    } else {
        echo "false";
    }
    wp_die(); // this is required to terminate immediately and return a proper response
}


$dir = __DIR__;
require_once($dir . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'partners/init' . '.php');    
require_once($dir . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'moderate/init' . '.php');    
