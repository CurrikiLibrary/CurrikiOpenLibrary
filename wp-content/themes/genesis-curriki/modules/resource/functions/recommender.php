<?php
require_once get_stylesheet_directory()."/modules/resource/models/ResourceModel.php";
require_once get_stylesheet_directory()."/modules/resource/views/index.php";

add_action( 'wp_ajax_nopriv_cur_load_recommender_widget', 'cur_load_recommender_widget' ); 
add_action( 'wp_ajax_cur_load_recommender_widget', 'cur_load_recommender_widget'); 

function cur_load_recommender_widget() {
    
//    $resourceModel = new ResourceModel();
//    $sub_res = $resourceModel->getSubjectAreasIds($_POST['resourceid']);                
//    $current_resource = $resourceModel->getResource($_POST['resourceid']);                
//    $educationlevels_ids = $resourceModel->getEducationLevelIds($_POST['resourceid']);                                
//    $recommender_widget_html = recommender_widgets_html( $sub_res['subject_ids'], $sub_res['subjectareas_ids'], $educationlevels_ids , $current_resource);
//    echo $recommender_widget_html;
//    wp_die();
    
}

function recommender_widgets_html($subject_ids = [], $subjectareas_ids = [], $educationlevels_ids = [], $current_resource = null) {
    
    $curriki_recommender = $GLOBALS['curriki_recommender'] ? $GLOBALS['curriki_recommender'] : null;           
    $premium_resources = $curriki_recommender->premium_resource_repository->getRandomBySubjectAreasOrEducationLevels($subjectareas_ids, $educationlevels_ids); 
    
    $measures = new CurrikiRecommender\Entities\Measures();
    $measures->setSubjectIds($subject_ids);
    $measures->setSubjectareaIds($subjectareas_ids);
    $measures->setEducationlevelids($educationlevels_ids);              
    $keywords = explode(' ', trim($current_resource->keywords));
    $keywords = is_array($keywords) && !empty($keywords) ? $keywords : [];    
    $measures->setKeywords($keywords);
    
    $recommended_resources = [];
    if($curriki_recommender){
        $curriki_recommender->services['random_resources']->setMeasures($measures);
        $recommended_resources = $curriki_recommender->services['random_resources']->getByAnalytics();                
    }        
     
    $widgets = array("widget_you_may_like" => "","widget_premium_resources" => "");      
    if( !is_null($curriki_recommender) && !empty($recommended_resources) ){
        $widgets["widget_you_may_like"] = widget_side_bar("You May Like",$recommended_resources);        
    }
    
    if( $curriki_recommender && !empty($premium_resources) ){
        $widgets["widget_premium_resources"] = widget_side_bar("Featured",$premium_resources);
    }
    
    return json_encode($widgets);    
}
