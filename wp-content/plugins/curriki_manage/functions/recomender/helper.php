<?php

function saveResourceToCurrikiRecommender($wpdb, $curriki_recommender = null, $data){
    if( $curriki_recommender && intval($wpdb->insert_id) > 0 ){            
        $data_fields = [];
        foreach ($data as $key => $value) {
            $data_fields[] = $key;
        }            
        $resource_obj = $wpdb->get_row( $wpdb->prepare( "SELECT ".implode(',', $data_fields)." FROM resources where resourceid = %d", intval($wpdb->insert_id)));                                    
        $data_rec = array("resourceid" => intval($wpdb->insert_id)) + (array)$resource_obj;        
        try{
            return $curriki_recommender->resource_repository->save($data_rec);
        } catch (Exception $ex) {}
    }
}

function saveSubjectAreasToCurrikiRecommender($curriki_recommender = null, $resource_subjectareas_list = []) {        
    if( $curriki_recommender && is_array($resource_subjectareas_list) && count($resource_subjectareas_list) > 0 ){
        try{
            $curriki_recommender->resource_repository->saveSubjectAreas($resource_subjectareas_list); 
        } catch (Exception $ex) {return $ex;}
    }
}

function saveEducationLevelsToCurrikiRecommender($curriki_recommender = null, $resource_educationlevels_list = []) {        
    if( $curriki_recommender && is_array($resource_educationlevels_list) && count($resource_educationlevels_list) > 0 ){
        try{
            $curriki_recommender->resource_repository->saveEducationLevels($resource_educationlevels_list);
        } catch (Exception $ex) {}        
    }
}

function saveResourceFilesToCurrikiRecommender($curriki_recommender = null, $resourcefiles_list = []) {        
    if( $curriki_recommender && is_array($resourcefiles_list) && count($resourcefiles_list) > 0 ){
        try{
            $curriki_recommender->resource_repository->saveResourceFiles($resourcefiles_list);
        } catch (Exception $ex) {}
    }
}

function saveResourceViewsToCurrikiRecommender($curriki_recommender = null, $data = null) {
    if( $curriki_recommender && $data ){
        try{
            $curriki_recommender->resource_repository->saveResourceViews($data); 
        } catch (Exception $ex) {}
    }
}

function saveResourceCommentsToCurrikiRecommender($curriki_recommender = null, $data = null) {
    if( $curriki_recommender && $data ){
        try{
            $curriki_recommender->resource_repository->saveResourceComments($data);
        } catch (Exception $ex) {}        
    }
}

