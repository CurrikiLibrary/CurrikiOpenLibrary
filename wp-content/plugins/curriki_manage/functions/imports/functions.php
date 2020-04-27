<?php

// **** loading views and assets ****
function curriki_admin_imports() {
    $path_to_view = realpath(__DIR__ . '/../../views/imports');
    $view = $path_to_view . '/index.php';
    wp_enqueue_script('ga-script', plugins_url() . "/curriki_manage/views/imports/js/script.js", array('jquery'), false, true);
    @include_once($view);
}

add_action('wp_ajax_process_ye_api_import', 'process_ye_api_import');
add_action('wp_ajax_nopriv_process_ye_api_import', 'process_ye_api_import');
function process_ye_api_import() {
    
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    ini_set('max_execution_time', 900);
    
    
    /*
    require_once realpath(__DIR__) . '/Classes/CurrikiReview.php';
    $file = fopen(realpath(__DIR__) . '/ye.csv', 'r');        
    global $wpdb;
    while ( (($line = fgetcsv($file)) !== FALSE)  ) {
        
        $ignore_partner_vals = array();
        //if(is_array($line) && !in_array($line[1], $ignore_partner_vals)){
        if(is_array($line)){
            
            $pageurl = $line[0];
            $partner_val = $line[1];                        
            
            //echo "$pageurl ** $partner_val | ";
            CurrikiReview::udpateResource($pageurl, $partner_val);              
        }
        
    }
    fclose($file);
    die;*/
        
    /***********************************************/
    
    $response = array();
    $response['status'] = 200;
    
    if( !( isset($_REQUEST['ye_api_url']) && strlen($_REQUEST['ye_api_url'])>0  ) ){
        $response['status'] = 400;
        $response['message'] = "Please enter API endpoint !";
    }else{
        
        
        require_once realpath(__DIR__).'/Classes/YEHelper.php';                        
        YEHelper::initYeData($_REQUEST['ye_api_url']);                                                
                
        $min = -2;
        $max = -1;
        
        foreach (YEHelper::$yeData as $record_index => $record) {
            if( ($min <= $record_index) && ($record_index <= $max) ){
                
                //************************* Create Contents *****************************
                //***********************************************************************
                
                YEHelper::initYePost($record);                  
                //==================== Process & Create Collection ======================
                $params_col = array(
                    'title' => YEHelper::$YELessonCollection->title,
                    "description" => YEHelper::$YELessonCollection->description,
                    'subject' => array(0 => "CareerTechnicalEducation" , 1 => "SocialStudies"),
                    "subjectarea" => array(0 => 25, 1 => 141),
                    "education_levels" => array("15|16","17|18"),
                    "content" => YEHelper::$YELessonCollection->content,
                    "keywords" => YEHelper::$YELessonCollection->keywords,
                    "resource_type" => "collection"
                );
                $_REQUEST = imports_prepare_params($params_col);
                $cur_resource = imports_create_resource();
                YEHelper::$YELessonCollection->db_record = $cur_resource;
                YEHelper::$YELessonCollection->resourceid = $cur_resource["resourceid"];                
                
                //==================== Process & CREATE Collection's Resources ===================
                foreach (YEHelper::$YELessonCollectionResources['video_resources'] as $key => $video_resource) {                    
                    $params_vid = array(
                        'title' => $video_resource->title,
                        "description" => $video_resource->description,
                        'subject' => array(0 => "CareerTechnicalEducation" , 1 => "SocialStudies"),
                        "subjectarea" => array(0 => 25, 1 => 141),
                        "education_levels" => array("15|16","17|18"),
                        "content" => $video_resource->content,
                        "keywords" => "",
                        "resource_type" => "resource"
                    );
                    $_REQUEST = imports_prepare_params($params_vid);
                    $cur_resource = imports_create_resource();                    
                    YEHelper::$YELessonCollectionResources['video_resources'][$key]->db_record = $cur_resource;
                    YEHelper::$YELessonCollectionResources['video_resources'][$key]->resourceid = $cur_resource['resourceid'];
                }                
                foreach (YEHelper::$YELessonCollectionResources['resource_resources'] as $key => $resource) {
                    $params_rs = array(
                        'title' => $resource->title,
                        "description" => $resource->description,
                        'subject' => array(0 => "CareerTechnicalEducation" , 1 => "SocialStudies"),
                        "subjectarea" => array(0 => 25, 1 => 141),
                        "education_levels" => array("15|16","17|18"),
                        "content" => $resource->content,
                        "keywords" => "",
                        "resource_type" => "resource"
                    );
                    $_REQUEST = imports_prepare_params($params_rs);
                    $cur_resource = imports_create_resource();                    
                    YEHelper::$YELessonCollectionResources['resource_resources'][$key]->db_record = $cur_resource;
                    YEHelper::$YELessonCollectionResources['resource_resources'][$key]->resourceid = $cur_resource['resourceid'];
                }
                
                
                //************************* Sync Contents *****************************
                //*********************************************************************
                
                YEHelper::syncCollectionAndResourcesContents($record);
                //==================== Update Collection after Sync ===================                
                $params_col = array(
                    'title' => YEHelper::$YELessonCollection->title,
                    "description" => YEHelper::$YELessonCollection->description,
                    'subject' => array(0 => "CareerTechnicalEducation" , 1 => "SocialStudies"),
                    "subjectarea" => array(0 => 25, 1 => 141),
                    "education_levels" => array("15|16","17|18"),
                    "content" => YEHelper::$YELessonCollection->content,
                    "keywords" => YEHelper::$YELessonCollection->keywords,
                    "resource_type" => "collection"
                );
                $params_col['resourceid'] = YEHelper::$YELessonCollection->resourceid;
                $_REQUEST = imports_prepare_params($params_col);
                $cur_resource = imports_create_resource();
                YEHelper::$YELessonCollection->db_record = $cur_resource;
                YEHelper::$YELessonCollection->resourceid = $cur_resource["resourceid"];
                                
                //==================== Process & UPDATE Collection's Resources ===================
                $rs_order = 0;
                foreach (YEHelper::$YELessonCollectionResources['video_resources'] as $key => $video_resource) {                    
                    $params_vid = array(
                        'title' => $video_resource->title,
                        "description" => $video_resource->description,
                        'subject' => array(0 => "CareerTechnicalEducation" , 1 => "SocialStudies"),
                        "subjectarea" => array(0 => 25, 1 => 141),
                        "education_levels" => array("15|16","17|18"),
                        "content" => $video_resource->content,
                        "keywords" => "",
                        "resource_type" => "resource"
                    );
                    $params_vid['resourceid'] = $video_resource->resourceid;
                    $_REQUEST = imports_prepare_params($params_vid);
                    $cur_resource = imports_create_resource();                    
                    YEHelper::$YELessonCollectionResources['video_resources'][$key]->db_record = $cur_resource;
                    YEHelper::$YELessonCollectionResources['video_resources'][$key]->resourceid = $cur_resource['resourceid'];
                    YEHelper::yeAssingResourceToCollection(YEHelper::$YELessonCollectionResources['video_resources'][$key]->resourceid, YEHelper::$YELessonCollection->resourceid, $rs_order);
                    $rs_order++;
                }                
                
                foreach (YEHelper::$YELessonCollectionResources['resource_resources'] as $keyUp => $resourceObj) {                                       
                    $params_rs_up = array(
                        'title' => $resourceObj->title,
                        "description" => $resourceObj->description,
                        'subject' => array(0 => "CareerTechnicalEducation" , 1 => "SocialStudies"),
                        "subjectarea" => array(0 => 25, 1 => 141),
                        "education_levels" => array("15|16","17|18"),
                        "content" => $resourceObj->content,
                        "keywords" => "",
                        "resource_type" => "resource"
                    );
                    $params_rs_up['resourceid'] = $resourceObj->resourceid;
                    $_REQUEST = imports_prepare_params($params_rs_up);                                                    
                    $cur_resource_ye_rs = imports_create_resource();                                        
                    YEHelper::$YELessonCollectionResources['resource_resources'][$keyUp]->db_record = $cur_resource_ye_rs;
                    YEHelper::$YELessonCollectionResources['resource_resources'][$keyUp]->resourceid = $cur_resource_ye_rs['resourceid'];
                    YEHelper::yeAssingResourceToCollection(YEHelper::$YELessonCollectionResources['resource_resources'][$keyUp]->resourceid, YEHelper::$YELessonCollection->resourceid, $rs_order);
                    $rs_order++;
                }
                
                if(strlen(YEHelper::$YELessonCollection->topic) > 0){
                    $topicsCollectionsIdMap = YEHelper::topicsCollectionsIdMap();
                    $topic_collectionid = $topicsCollectionsIdMap[YEHelper::$YELessonCollection->topic];
                    if(intval($topic_collectionid) > 0){
                        YEHelper::yeAssingResourceToCollection(YEHelper::$YELessonCollection->resourceid,$topic_collectionid, $record_index);
                    }
                } 
                
                YEHelper::clearData();
            }
        }                
    }
    
    echo json_encode($response);
    wp_die();
}

add_action('wp_ajax_process_imports_csv_upload', 'process_imports_csv_upload');
add_action('wp_ajax_nopriv_process_imports_csv_upload', 'process_imports_csv_upload');

function process_imports_csv_upload() {
    
    ini_set('max_execution_time', 900);
            
    $response = array();
    $response['status'] = 200;
    $csv_file = isset($_FILES['csv_file']) ? $_FILES['csv_file'] : null;
    $file_ext = is_array($csv_file) ? strtolower(end(explode('.', $csv_file['name']))) : "";
    $expensions = array("csv");

    if ($csv_file === null || ($csv_file !== null && !in_array($file_ext, $expensions))) {
        $response['status'] = 400;
        $response['message'] = "Please upload CSV file!";
    } else {
       
        $ed_lvs_val_maps = array(
            '0-4' => array('8|9'),
            '5-7' => array('3|4'),
            '3-5' => array('5|6|7'),
            '3-8' => array('5|6|7'),
            '6-8' => array('11|12|13'),
            '6-12' => array('11|12|13', '15|16', '17|18'),
            '9-10' => array('15|16'),
            '9-12' => array('15|16'),
            '11-12' => array('17|18'),
            'college' => array('23|24|25'),
            'Professional Development' => array('19|20'),
            'Special Education' => array('26|21'),
        );

        $subject_area_codes_map = array(
            'Arithmetic' => '101',
            'Algorithms' => '9',
            'Algebra' => '99',
            '' => '9',
        );

        $rs_csv_index_map = array();
        $rs_csv_index_map['title'] = 2;
        $rs_csv_index_map['subjectarea'] = 14;
        $rs_csv_index_map['keywords'] = 16;
        $rs_csv_index_map['grads'] = 17;
        $rs_csv_index_map['description'] = 12;
        $rs_csv_index_map['long_description'] = 13;
        $rs_csv_index_map['link'] = 10;
        $rs_csv_index_map['video'] = 18;

        $file = fopen($csv_file['tmp_name'], "r");
        $cntr = 1;
        while (($row = fgetcsv($file)) !== FALSE) {

            $video = trim($row[$rs_csv_index_map['video']]);
            $link = trim($row[$rs_csv_index_map['link']]);
            
            if ( $cntr > 1 && strlen($video) > 0 && strlen($link) > 0 ) {
     
                $csv_subjectarea_text = $row[$rs_csv_index_map['subjectarea']];
                $subjectarea_code = array_key_exists($csv_subjectarea_text, $subject_area_codes_map) ? $subject_area_codes_map[$csv_subjectarea_text] : '9';
                
                $vid_param = null;
                parse_str(parse_url( $video )['query'],$vid_param);                
                
                $video_emb_link = "https://www.youtube.com/embed/".$vid_param['v'];
                
                $short_description = $row[$rs_csv_index_map['description']];
                $long_description = $row[$rs_csv_index_map['long_description']];
                $content = prepare_content_html($short_description, $long_description, $video_emb_link, $link);                

                $p = array(
                    'title' => 'Polyup ' . $row[$rs_csv_index_map['title']],
                    "description" => $row[$rs_csv_index_map['description']],
                    'subject' => array(0 => "Mathematics"),
                    "subjectarea" => array(0 => $subjectarea_code),
                    "education_levels" => $ed_lvs_val_maps[$row[$rs_csv_index_map['grads']]],
                    "content" => $content,
                    "keywords" => $row[$rs_csv_index_map['keywords']]
                );  
                
                $_REQUEST = imports_prepare_params($p);               
                imports_create_resource(); 
            }

            $cntr++;
        }
        
        fclose($file);
    }

    echo json_encode($response);
    wp_die();
}

function prepare_content_html($short_description, $long_description, $video_link, $polyup_link) {
    $content =  '        
<div class="resource-content-content rounded-borders-full border-grey">
    <img style="display: block; margin-left: auto; margin-right: auto;" src="https://' . DBI_AWS_S3_CDN_URL . '/resourceimgs/5b50d4e287b9f.png" alt="" width="">
    <br />
    <p style="text-align: center;">
        '.$short_description.'
    </p>
    <div style="overflow: hidden;">
    ';
    
    $video_link_style = 'width: 100%; text-align:center;';
    $polyup_link_style = 'width: 100%; text-align:center;';
    $is_one_column = strlen($video_link) > 0 && strlen($polyup_link) > 0 ? false : true;
    
    
    if(strlen($video_link) > 0){
        if(!$is_one_column){
            $video_link_style = 'float: left;width: 65%;';
        }
        $content .= '<div style="'.$video_link_style.'">
            <iframe src="'.$video_link.'" height="500" width="700"></iframe>
        </div>';
    }    
    
    if(strlen($polyup_link) > 0){
        if(!$is_one_column){
            $polyup_link_style = 'float: left;width: 35%;';
        }
        $content .= '<div style="'.$polyup_link_style.'">
                <iframe src="'.$polyup_link.'" height="500" width="400" scrolling="auto"></iframe>
            </div>
        ';
    }        
    $content .= '
    </div>
    <br />
    <p>
        '.$long_description.'
    </p>
</div>            
    ';
    return $content;
}

function imports_prepare_params($p) {
    $params = array(
        "tm" => 1532700796814,
        "preview_resource_id" => 0,
        "action" => array_key_exists('action', $p) ? $p["action"] : "create_resource",
        "groupid" => "",
        "prid" => "",
        "mediatype" => "text",
        "resource_type" => array_key_exists('resource_type', $p) ? $p["resource_type"] : "resource",
        "resourceid" => array_key_exists('resourceid', $p) ? $p["resourceid"]:"",
        "title" => $p['title'],
        "description" => $p['description'],
        "content" => $p['content'],
        "subject" => $p['subject'],
        "subjectarea" => $p['subjectarea'],
        "education_levels" => $p['education_levels'],
        "keywords" => $p['keywords'],
        "instructiontypes" => array(),
        "standardid" => "Select a Standard",
        "levelid" => "Select a Grade Level",
        "statementid" => "0",
        "aligntagid" => "0",
        "access" => "public",
        "licenseid" => "1",
        "language" => "eng",
        "active" => true
    );
    return $params;
}

function imports_create_resource() {

    $site_url = (is_ssl() ? "https://" : "http://") . $_SERVER["HTTP_HOST"];

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

    if (isset($_REQUEST['education_levels']) && is_array($_REQUEST['education_levels'])) {
        $in_keys = implode(',', $_REQUEST['education_levels']);
        if (strlen($in_keys) > 0) {
            foreach ($wpdb->get_results("SELECT * from educationlevels where levelid in (" . $in_keys . ")", ARRAY_A) as $row) {
                $gkeywords[] .= 'Grade ' . $row['identifier'];
            }
        }
    }

    $contents = str_replace(array('\"', "\'", '\/'), array('"', "'", '/'), $_REQUEST['content']);

    $data['licenseid'] = $_REQUEST['licenseid'];
    $data['description'] = trim($_REQUEST['description']);
    $data['title'] = substr(trim($_REQUEST['title']), 0, 499);
    $data['keywords'] = trim($_REQUEST['keywords']);
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
    $profile_url = $site_url . "/members/" . $current_user->data->user_nicename;
    $user_display_name = $current_user->data->display_name;
    $resource_activity_content = $_REQUEST['description'];

    if (isset($_REQUEST['resourceid']) && !empty($_REQUEST['resourceid'])) {
        $res_id = $_REQUEST['resourceid'];
//        $resource = $wpdb->get_row("SELECT * FROM resources where resourceid = '" . $res_id . "'");       //prepared statement added
        $resource = $wpdb->get_row($wpdb->prepare(
                        "
                        SELECT * FROM resources where resourceid = %d
                ", $res_id
        ));
    }


    if (!empty($resource) && ($resource->contributorid == $current_user->ID OR $current_user->caps['administrator'] OR in_array("resourceEditor", $current_user->roles))) {
        
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
        $resource_activity_title = '<a href="' . $site_url . '/oer/' . $data['pageurl'] . '">' . $_REQUEST['title'] . '</a>';
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

        $dup_title = $wpdb->get_row("SELECT count(*) CNT FROM resources where pageurl = '" . $data['pageurl'] . "' and  resourceid != '" . $res_id . "'")->CNT;
        if ($dup_title) {
            $data['pageurl'] = $data['pageurl'] . '-' . $res_id;
            $wpdb->update('resources', array('pageurl' => $data['pageurl']), array('resourceid' => $res_id));
        }
        $pageurl = $data['pageurl'];
        $bpActType = "resource_insert";
        $resource_activity_title = '<a href="' . $site_url . '/oer/' . $pageurl . '">' . $_REQUEST['title'] . '</a>';
        $bpActAction = '<a href="' . $profile_url . '">' . $user_display_name . '</a> created ' . $resource_activity_title;

        if (isset($_REQUEST['groupid']) && intval($_REQUEST['groupid']) > 0)
            $wpdb->insert('group_resources', array('groupid' => intval($_REQUEST['groupid']), 'resourceid' => $res_id), array('%d', '%d'));

        if (isset($_REQUEST['prid']) && intval($_REQUEST['prid']) > 0)
            $wpdb->insert('collectionelements', array('collectionid' => intval($_REQUEST['prid']), 'resourceid' => $res_id), array('%d', '%d'));
    }

    //CHecking for spam
    foreach ($wpdb->get_results("SELECT phrase FROM censorphrases", ARRAY_A) as $word) {
        if ((strpos(' ' . $data['title'], $word['phrase']) > 0 ) || (strpos(' ' . $data['title'], $word['phrase']) > 0 ) || (strpos(strip_tags(' ' . $data['content']), $word['phrase']) > 0 ) || (striposa(' ' . $data['keywords'], $word['phrase']) > 0)) {
            // Prepared statement added
            /*
              $wpdb->query("update resources
              set spam = 'T',
              remove = 'T',
              indexrequired = 'T',
              indexrequireddate = current_timestamp(),
              active = 'F'
              where resourceid = '{$res_id}' ");
             * 
             */

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
    $data = array('resourceid' => $res_id);
    if (isset($_REQUEST['subjectarea']) && is_array($_REQUEST['subjectarea']))
        foreach ($_REQUEST['subjectarea'] as $row) {
            $data['subjectareaid'] = $row;
            $wpdb->insert('resource_subjectareas', $data);
        }

    //Setting Education Levels
    $data = array('resourceid' => $res_id);
    if (isset($_REQUEST['education_levels']) && is_array($_REQUEST['education_levels'])) {
        foreach ($_REQUEST['education_levels'] as $row) {
            if (strlen($row) > 0) {
                $data['educationlevelid'] = $row;
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


    //Questions


    $res_content = $wpdb->get_results($wpdb->prepare("SELECT content FROM resources WHERE resourceid = %d", $res_id))[0]->content;

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

    if ($question_wrapper->length > 0) {

        $res_content_new = putQuestionData($question_wrapper, $newDom, $main_xpath, $res_id);
        if ($res_content_new != null) {
            $res_content = $res_content_new;
        }
    }



    //Setting ResourceFiles
    //include_once(dirname(dirname(__DIR__)) . '/libs/functions.php');
    $data = array('resourceid' => $res_id);
    if (isset($_REQUEST['resourcefiles']) && is_array($_REQUEST['resourcefiles'])) {
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
                        }
                    }
                } else {
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
                }
            }
        }
    }
    //Returning Output
    return array('resourceid' => $res_id, 'pageurl' => $pageurl, 'content' => $res_content);

    //echo json_encode(array('resourceid' => $res_id, 'pageurl' => $pageurl, 'content'=>$res_content));
    //wp_die(); // this is required to terminate immediately and return a proper response
}
