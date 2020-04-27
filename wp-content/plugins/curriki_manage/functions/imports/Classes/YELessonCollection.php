<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of YELessonCollection
 *
 * @author waqarmuneer
 */
class YELessonCollection {
    
    public $resourceid = 0;    
    public $title = null;
    public $description = null;
    public $content = null;
    public $keywords = null;
    public $topic = null;
    public $db_record = null;
    public $excluded_topics_to_modify = ['YE Culture'];

    public function __construct($record = null) {
        if($record !== null){            
            $this->title = $record->post_title;                          
            
            $this->topic = is_array($record->topics) && count($record->topics) > 0 ? $record->topics[0]->name : "";            
            if( !in_array($this->topic, $this->excluded_topics_to_modify) ){
                $this->topic = "YE ".$this->topic;
            }
            
            $this->formatContent($record);
            $this->setKeywords($record);
        }                
    }
    
    public function setKeywords($record){
        if( is_array($record->subtopics) && count($record->subtopics) > 0 ){
            $topics_arr = [];
            foreach($record->subtopics as $topic){
                $topics_arr[] = $topic->name;
            }
            $this->keywords = implode(',', $topics_arr);
        }
    }
    
    public function formatContent($record, $collectionResources = array()){
        
        $videos_html = "";
        $resources_html = "";
        if(count($collectionResources) > 0){
            
            $videos = is_array($collectionResources['video_resources']) ? $collectionResources['video_resources'] : [];
            if( count($videos) > 0 ){
                $videos_html = '<h4>VIDEOS</h4><ul>';
                foreach ($videos as $video) {
                    $video_slug = "/oer/{$video->db_record['pageurl']}/?mrid={$this->resourceid}";
                    $videos_html .='<li><a href="'.$video_slug.'">'.$video->title_without_prefix.'</a></li>';            
                }
                $videos_html .='</ul><br />';
            }           
            
            $resources = is_array($collectionResources['resource_resources']) ? $collectionResources['resource_resources'] : []; 
            if(count($resources) > 0 ){
                $resources_html = '<h4>LESSON RESOURCES</h4><ul>';
                foreach($resources as $resource){
                    $resource_slug = "/oer/{$resource->db_record['pageurl']}/?mrid={$this->resourceid}"; 
                    $resources_html .= '<li><a href="'.$resource_slug.'">'.$resource->title_without_prefix.'</a></li>';
                }        
                $resources_html .='</ul><br />';
            }
        }
        
        $themes = is_array($record->themes) ? $record->themes : [];
        if( count($themes) > 0 ){            
            $themes_html = '<h4>LESSON THEMES</h4><ul>';
            foreach ($themes as $theme){
                $themes_html .= '<li>'.$theme.'</li>';
            }
            $themes_html .= '</ul><br />';
        }
        
        $materials = is_array($record->materials) ? $record->materials : [];
        if( count($materials) > 0 ){
            $materials_html = '<h4>MATERIALS LIST</h4><ul>';        
            foreach($materials as $material){
                $materials_html .='<li>'.$material.'</li>';
            }        
            $materials_html .= '</ul><br />';  
        }
                
        $content = '<p>'.$this->description.'</p>'.$videos_html.$resources_html.$materials_html.$themes_html;        
        $this->content = $content;                         
    }
}
