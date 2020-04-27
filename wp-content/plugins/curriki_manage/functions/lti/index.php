<?php

if( isset($_GET['test_bug']) ){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

define("ACTIVITY_STATUS_NOT_COMPLETED", "MY PROGRESS: YOU HAVE NOT COMPLETED THIS ACTIVITY");
define("ACTIVITY_STATUS_NOT_COMPLETED_PROGRESS_MONITOR", "MY PROGRESS: NOT COMPLETED");

$app_path = ABSPATH . 'curriki-xapi-app/app/Curriki/curriki-lti/bootstrap.php';
require_once $app_path;  
require_once "lass-progress-monitor.php";
require_once "program-statuses.php";
require_once "oer-features.php";

function currGetBreadcrumbs($resourceid, &$breadcrumbs){    
    global $wpdb;
    $row = $wpdb->get_row("select * from resources where resourceid = ".$resourceid, ARRAY_A);
    if(is_array($row)){
        //$bc = array($row['pageurl'] => $row['title']);
        $bc = '<a class="resource-url-link" href="'.site_url().'/oer/'.$row['pageurl'].'">'.$row['title'].'</a>';
        array_push($breadcrumbs, $bc);
        $parent_id = currGetParentCollection($row['resourceid']);
        if(!is_null($parent_id)){
            currGetBreadcrumbs($parent_id, $breadcrumbs);
        }
    }
}


function currGetParentCollection($playlist_collection_id){
    global $wpdb;
    $p_collection = $wpdb->get_row("select * from collectionelements where resourceid = ".$playlist_collection_id, ARRAY_A);
    if(is_array($p_collection)){
        return $p_collection['collectionid'];
    }else{
        return null;
    }
}

// process for lti resource in playlist
function ltiGetProgressForPlaylist($playlist_collection_id){
    global $curriki_lti_instance;    
    $program_collection_id = currGetParentCollection($playlist_collection_id);    
    if(!is_null($program_collection_id)){        
        $external_module_obj = $curriki_lti_instance->get('CurrikiLti\Core\Services\LaaS\ExternalModule');
        $external_module = $external_module_obj->getExternalModuleEnabled("program", "collection", $program_collection_id , "curriki.org");
        if(is_array($external_module)){
            $prgress_data = ltiPrepareProgressForPlaylist($playlist_collection_id, get_current_user_id());
            return progressForPlaylist($prgress_data);
        }else{
            return null;
        }
    }else{
        return null;
    }
}

// prgress for playlist
function progressForPlaylist($playlist_progress_data){
    $total = count($playlist_progress_data['progress_lti_resources']);
    $completed = 0;
    foreach($playlist_progress_data['progress_lti_resources'] as $p_rs){            
        if(isset($p_rs['is_completed'])){
            $completed++;
        }
    }
    return ["completed" => $completed, "total" => $total];
}

// progress for program
function progressForProgram($data){
    $total = count($data);
    $completed = 0;
    foreach($data as $p_dt){
        $p_total = count($p_dt['progress_lti_resources']);
        $p_completed = 0;
        foreach($p_dt['progress_lti_resources'] as $p_rs){            
            if(isset($p_rs['is_completed'])){
                $p_completed++;
            }
        }
        if( ($p_total > 0 && $p_completed > 0) && ($p_total === $p_completed) ){
            $completed++;
        }
    }

    return ["completed" => $completed, "total" => $total];
}
  
function ltiGetApp(){
    global $curriki_lti_instance;
    return $curriki_lti_instance;   
}

// get progress for LTI resource
function ltiGetResourceProgress($resourceid, $userid, $check_as_collection_item = false){    
    $curriki_lti_instance = ltiGetApp();
    $progress = [];
    if(is_object($curriki_lti_instance)){        
        $lti_progress = $curriki_lti_instance->get('CurrikiLti\Core\Services\Lti\LtiProgress');        
        $lti_progress->userid = $userid;
        $lti_progress->resourceid = $resourceid;
        $lti_progress->lti_resource_component = "curriki-resource";
        $lti_progress_data = $lti_progress->getForResource();

        if($lti_progress_data['is_completed'] === false){                       
            $progress['status'] = "take-lesson";
            $progress['data'] = null;            
        }elseif($lti_progress_data['is_completed'] === true){
            $progress['status'] = "completed";
            $progress['data'] = $lti_progress_data;
        }else{
            $progress['status'] = null;
            $progress['data'] = null;            
        }       
        return $progress;
    }else{
        $progress['status'] = null;
        $progress['data'] = null;
        return $progress;
    }
    
}

// make html for LTI resource progress
function ltiMakeProgressHTML($data){
    
    $status = '';
    if($data['status'] === "take-lesson"){
        //$status = '<span class="take-lesson-oer-content">TAKE THIS LESSON</span>';
        $status = '<span class="graded-progress-lable">'.ACTIVITY_STATUS_NOT_COMPLETED.'</span>';
    }elseif($data['status'] === "completed"){        
        //$status = '<span class="review-aggregate">Completed </span>';
        $status = '<span class="graded-progress-lable">Score: '.$data['data']['originalgrade'];
        $status .= ' ('.$data['data']['gradepercent'].'%)';
        $status .= ' completed on '.$data['data']['datesubmitted'].' UTC';
        $status .='</span>';
    }

    $html = '<div class="lti-progress-container">';
        $html .= $status;
    $html .= '</div>';
    return $html;
}

function oerEnableUserRegistration($resourceid, $request, $resource_type, $module_type){
    $enable_user_registration = ($request['enable-user-registration'] == 1 ? 1 : 0);
    $curriki_lti_instance = ltiGetApp();
    if(is_object($curriki_lti_instance)){
        $external_module = $curriki_lti_instance->get('CurrikiLti\Core\Services\LaaS\ExternalModule');
        $external_module->setExternalModule($module_type, $resource_type, $resourceid, $enable_user_registration, "curriki.org");
    }else {
        return null;
    }
}

function oerPrintProgramButton($resourceid, $userid){    
    global $curriki_lti_instance;
    $data = array(
        'register_button' => null,
        'IsUserRegisterToProgram' => null
    );

    if(is_object($curriki_lti_instance)){ 
        $external_module_obj = $curriki_lti_instance->get('CurrikiLti\Core\Services\LaaS\ExternalModule');
        $external_module = $external_module_obj->getExternalModuleEnabled("program", "collection", $resourceid, "curriki.org");        
        if( is_array( $external_module )){
            $data['register_button'] = '<p><center><button id="oer-register-program" class="green-button" onclick="oerRegisterProgram('.$external_module['id'].');">Register Program</button></center></p>';

            $external_program_registration = $curriki_lti_instance->get('CurrikiLti\Core\Services\LaaS\ExternalProgramRegistration');            
            $registration = $external_program_registration->getUserRegistration($userid, $external_module['id']);
            if(is_object($registration)){                
                $data['IsUserRegisterToProgram'] = array(
                    'id' => $registration->getId(),
                    'external_user_id' => $registration->getExternalUserId(),
                    'external_module_id' => $registration->getExternalModuleId()
                );
            }
        }
    }  
    return $data;
}

function ltiPrepareProgressForPlaylist($playlist_collection_id, $userid){    
    
    global $curriki_lti_instance, $wpdb;        
    $playlist_collection = currGetPlaylistCollectionResources($playlist_collection_id);       
    $progess_playlist = array(
            'playlist_collection_id' => $playlist_collection_id,                
            'lti_resources_total' => count($playlist_collection),
            'progress_lti_resources' => []                
        );        
        
    foreach($playlist_collection as $lti_resource){        
        $resourceid = $lti_resource['resourceid'];                        
        $lti_progress = $curriki_lti_instance->get('CurrikiLti\Core\Services\Lti\LtiProgress');        
        $lti_progress->userid = $userid;
        $lti_progress->resourceid = $resourceid;
        $lti_progress->lti_resource_component = "curriki-resource";
        $lti_progress_data = $lti_progress->getForResource();

        $progress_lti_resource = ['resourceid' => $resourceid];
        if($lti_progress_data['is_completed'] === false){                       
            $progress_lti_resource['in_progress']['status'] = "take-lesson";
            $progress_lti_resource['in_progress']['data'] = null;            
        }elseif($lti_progress_data['is_completed'] === true){
            $progress_lti_resource['is_completed']['status'] = "completed";
            $progress_lti_resource['is_completed']['data'] = $lti_progress_data;
        }else{
            $progress_lti_resource['no_status']['status'] = "";
            $progress_lti_resource['no_status']['data'] = null;            
        }
        array_push($progess_playlist['progress_lti_resources'],$progress_lti_resource);            
    }        

    return $progess_playlist;
}

function currGetPlaylistCollectionResources($playlist_collection_id){
    global $wpdb;        
    $sql = "
    select r.resourceid , ce.resourceid as ColRid , r.title, r.pageurl, ce.displayseqno, r.type
    from collectionelements ce
        join resources r on ce.resourceid = r.resourceid
    and ce.collectionid IN ($playlist_collection_id)
        order by r.type, ce.displayseqno asc
        ";  
    $playlist_collection = $wpdb->get_results($sql,ARRAY_A);
    return $playlist_collection;
}

function ltiGetProgressForProgram($program_collection_id, $userid, $plalist_collections){    
    global $curriki_lti_instance;
    $program_progress = [];
    foreach($plalist_collections as $playlist_collection_id){       
        $playlist_collection = currGetPlaylistCollectionResources($playlist_collection_id);        
        $progess_playlist = array(
                'playlist_collection_id' => $playlist_collection_id,                
                'lti_resources_total' => count($playlist_collection),
                'progress_lti_resources' => []                
            );        
            
        foreach($playlist_collection as $lti_resource){            
            $resourceid = $lti_resource['resourceid'];                            
            $lti_progress = $curriki_lti_instance->get('CurrikiLti\Core\Services\Lti\LtiProgress');        
            $lti_progress->userid = $userid;
            $lti_progress->resourceid = $resourceid;
            $lti_progress->lti_resource_component = "curriki-resource";
            $lti_progress_data = $lti_progress->getForResource();

            $progress_lti_resource = ['resourceid' => $resourceid];
            if($lti_progress_data['is_completed'] === false){                       
                $progress_lti_resource['in_progress']['status'] = "take-lesson";
                $progress_lti_resource['in_progress']['data'] = null;            
            }elseif($lti_progress_data['is_completed'] === true){
                $progress_lti_resource['is_completed']['status'] = "completed";
                $progress_lti_resource['is_completed']['data'] = $lti_progress_data;
            }else{
                $progress_lti_resource['no_status']['status'] = "";
                $progress_lti_resource['no_status']['data'] = null;            
            }
            array_push($progess_playlist['progress_lti_resources'],$progress_lti_resource);            
        }
        
        $program_progress[] = $progess_playlist;
    }

    return $program_progress;
}


function getParentExternalModuleEnabled($resourceid){    
    global $curriki_lti_instance;
    $program_collection_id = currGetParentCollection($resourceid);    
    if(is_object($curriki_lti_instance) && !is_null($program_collection_id)){ 
        $external_module_obj = $curriki_lti_instance->get('CurrikiLti\Core\Services\LaaS\ExternalModule');
        $external_module =  $external_module_obj->getExternalModuleEnabled("program", "collection", $program_collection_id, "curriki.org");        
        if( is_array( $external_module )){            
            $external_program_registration = $curriki_lti_instance->get('CurrikiLti\Core\Services\LaaS\ExternalProgramRegistration');            
            $registration = $external_program_registration->getUserRegistration(get_current_user_id(), $external_module['id']);
            if(is_object($registration)){
                return $external_module; 
            }else{
                return null;
            }
        }else{
            return null;
        }
    }else{
        return null;
    }
}

function getExternalModule($resourceid){    
    $curriki_lti_instance = ltiGetApp();    
    if(is_object($curriki_lti_instance)){ 
        $external_module = $curriki_lti_instance->get('CurrikiLti\Core\Services\LaaS\ExternalModule');
        return $external_module->getExternalModule("program", "collection", $resourceid, "curriki.org");        
    }else{
        return null;
    }
}

function oerIsUserRegisterToProgram($resourceid, $userid){
    
    global $curriki_lti_instance;    

    if(is_object($curriki_lti_instance)){                 
        $external_module_obj = $curriki_lti_instance->get('CurrikiLti\Core\Services\LaaS\ExternalModule');
        $external_module = $external_module_obj->getExternalModuleEnabled("program", "collection", $resourceid, "curriki.org");

        if(is_array($external_module)){        
            $external_program_registration = $curriki_lti_instance->get('CurrikiLti\Core\Services\LaaS\ExternalProgramRegistration');            
            $registration = $external_program_registration->getUserRegistration($userid, $external_module['id']);            

            if(is_object($registration)){
                $user_registration_to_program = array(
                    'id' => $registration->getId(),
                    'external_user_id' => $registration->getExternalUserId(),
                    'external_module_id' => $registration->getExternalModuleId()
                );                                
                return $user_registration_to_program;
            }else{
                return null;
            }            
        }else{
            return null;
        }
    }else{
        return null;
    }    
}

function getProgramCollections($program_id){

    global $wpdb;    
    $sql = "
    select r.resourceid , ce.resourceid as ColRid , r.title, ce.displayseqno, r.type
    from collectionelements ce
        join resources r on ce.resourceid = r.resourceid
    and ce.collectionid IN ($program_id)
    and r.type = 'collection'
        order by r.type, ce.displayseqno asc
        ";  
    $program_playlist = $wpdb->get_results($sql,ARRAY_A);
    return $program_playlist;

}

// logic abstraction
function laas_get_user_performance($user_id, $program_id, $playlist_id_filter = 0,  $activity_id_filter = 0){

    global $curriki_lti_instance;   
    if(is_object($curriki_lti_instance)){ 
        $external_module_obj = $curriki_lti_instance->get('CurrikiLti\Core\Services\LaaS\ExternalModule');
        $external_module = $external_module_obj->getExternalModule("program", "collection", $program_id, "curriki.org");
        if( is_null( $external_module )){
            return null;
        }
    }else{
        return null;
    }

    if($user_id > 0 && $program_id > 0 && intval($playlist_id_filter) === 0 && intval($activity_id_filter) === 0){
        // for program only
        $playlist_collections = getProgramCollections($program_id);
        $playlist_collections_ids = [];
        foreach($playlist_collections as $playlist_collection){
            $playlist_collections_ids[] = $playlist_collection['resourceid'];
        }
        $prgress_data = ltiGetProgressForProgram($program_id, get_current_user_id(), $playlist_collections_ids);
        $progress_for_program = progressForProgram($prgress_data);
        return ['progress_for' => 'program', 'progress' => $progress_for_program];
        //$register_program_data = '<span class="graded-progress-lable">'."MY PROGRESS ".$progress_for_program['completed']."/".$progress_for_program['total'].'</span>';
    }elseif($user_id > 0 && $program_id > 0 && intval($playlist_id_filter) > 0 && intval($activity_id_filter) === 0){
        // for a playlist only
        $progress_for_playlist = ltiGetProgressForPlaylist($playlist_id_filter);
        return ['progress_for' => 'playlist', 'progress' => $progress_for_playlist];
    }elseif($user_id > 0 && $program_id > 0 && intval($playlist_id_filter) > 0 && intval($activity_id_filter) > 0){
        // for a resource activity        
        $lti_resource_progress = ltiMakeProgressHTML(ltiGetResourceProgress($activity_id_filter, $user_id));
        return ['progress_for' => 'lti_resource', 'progress' => $lti_resource_progress];
    }else{
        return null;
    }
}

add_action('wp_ajax_nopriv_register_user_for_program', 'ajax_register_user_for_program');
add_action('wp_ajax_register_user_for_program', 'ajax_register_user_for_program');

function ajax_register_user_for_program() {

    global $curriki_lti_instance;      

    if(is_object($curriki_lti_instance)){ 
        $external_program_registration = $curriki_lti_instance->get('CurrikiLti\Core\Services\LaaS\ExternalProgramRegistration');
        $external_module_id = $_POST['external_module_id'];
        $external_user_id = get_current_user_id();
        return $external_program_registration->registerUser($external_user_id, $external_module_id);
    }else{
        return null;
    }
}