<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of YEResource
 *
 * @author waqarmuneer
 */
class YEResource {

    public $resourceid = 0;
    public $title = null;
    public $title_without_prefix = null;
    public $description = null;
    public $content = null;
    
    public $video_url = '';
    public $resource_url = '';


    public $temp_id = null;
    public $title_prefix = null;
    public $db_record = null;
    
    public $back_to_lesson_url = '';

    public function __construct($record = null, $type = null , $parent_collection = null) {
        if ($record !== null && is_object($record)) {

            $excluded_topics_to_modify = ['YE Culture'];
            if ( property_exists($record, 'post_title') ) {
                $this->title = $record->post_title;                
                $this->title_without_prefix = $record->post_title;                
                
                
                $this->temp_id = implode('_', explode( ' ', strtolower($this->title) ));                
                $prfx = implode('_', explode( ' ', strtolower($parent_collection->title) ));                
                $this->temp_id = $prfx . '_-_' .$this->temp_id;
                
                $this->title = $parent_collection->title . ' - ' . $this->title;
            }                        
            
            if (property_exists($record, 'post_content')) {
                $this->description = strip_tags($record->post_content);
            }
            if ($type === 'video') {
                $this->formatVideoContent($record);
            }
            if ($type === 'resource') {
                $this->formateResourceContent($record);
            }
        }
    }

     public function setBackToLessonUrl($lessonCollection = null){
        if($lessonCollection === null){
            $this->back_to_lesson_url = '';
        }else{
            $this->back_to_lesson_url = '<p style="text-align: center;"><span style="box-sizing: border-box; color: #3d3d3d; font-family: azo-sans-web, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: #ffffff; text-decoration-style: initial; text-decoration-color: initial;">
            <a href="/oer/'.$lessonCollection->db_record['pageurl'].'/">Go to '.$lessonCollection->title.'</a>
            </span></p>';
        }
    }
    
    public function formatVideoContent($record = null, $lessonCollection = null) {
        
        if($record !== null){
            $this->video_url = $record->video_urls->sd;
        }
        
        if($lessonCollection != null){
            $this->setBackToLessonUrl($lessonCollection);
        }
        
        $content = '
<p>
' . $this->description . '
</p>
<p style="text-align: center;">
    <span style="box-sizing: border-box; color: #3d3d3d; font-family: azo-sans-web, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: #ffffff; text-decoration-style: initial; text-decoration-color: initial;">
        <video id="video" preload="metadata" autoplay="autoplay" controls="true">
            <source class="sd" src="' . $this->video_url . '" type="video/mp4">
        </video>
    </span>
</p>
'.$this->back_to_lesson_url;
        
        $this->content = $content;
    }
    
    public function formateResourceContent($record = null, $lessonCollection = null) { 
        
        if($record !== null){
            $this->resource_url = $record->url;
            $this->title = $record->post_title;
        }
        
        if($lessonCollection != null){                                    
            $this->setBackToLessonUrl($lessonCollection);            
        }                
        
        $content = '
            <p>'.$this->description.'</p>
            <div class="oembedall-container" style="display: block; margin-left: auto; margin-right: auto; text-align: center; width: 100%;">
                <span class="oembedall-closehide">&darr;</span>
                <a href="'. $this->resource_url .'" target="_blank">'.$this->title.'</a>
                <br /> 
                <iframe style="border: none;" src="https://docs.google.com/viewer?embedded=true&amp;url='.urlencode($this->resource_url).'" width="98%" height="600"></iframe>
            </div>
            <div class="asset-display-download">&nbsp;</div>
                '.$this->back_to_lesson_url;
        $this->content = $content;
    }
}