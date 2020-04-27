<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of YEHelper
 *
 * @author waqarmuneer
 */
require_once 'Scrape.php';
require_once 'YELessonCollection.php';
require_once 'YEResource.php';

class YEHelper {

    public static $yePost = null;
    public static $yeFetchedPost = null;
    public static $yeData = [];
    public static $http_client = null;
    public static $YELessonCollection = null;
    public static $YELessonCollectionResources = null;
    public static $dataIds = [];

    public static function clearData() {
        self::$yePost = null;
        self::$yeFetchedPost = null;
        self::$yeData = [];
        self::$http_client = null;
        self::$YELessonCollection = null;
        self::$YELessonCollectionResources = null;
        self::$dataIds = [];
    }
    
    public static function initDataIDs() {
        foreach (self::$yeData as $data) {
            self::$dataIds[] = $data->ID;
        }
    }
    
    public static function initYeData($api_endpoint) {
        self::$http_client = new Scrape();
        /* $http_client = new Scrape();
          $http_client->_url = $_REQUEST['ye_api_url'];
          $http_client->init_simple_request_setting();
          $http_client->exe_requrest();
          $ye_data =  $http_client->_result;
         */

        $json_data = file_get_contents(realpath(__DIR__) . '/ye_data.txt');
        self::$yeData = json_decode(json_encode(json_decode($json_data)));
        self::initDataIDs();
    }

    public static function initYePost($record) {
        if (self::$http_client === null) {
            self::$http_client = new Scrape();
        }
        self::$http_client->_url = $record->api_url;
        self::$http_client->init_simple_request_setting();
        self::$http_client->exe_requrest();
        self::$yeFetchedPost = json_decode(json_encode(json_decode(self::$http_client->_result)));
        self::prepareYeCollection($record);
        self::prepareResources($record);
    }

    public static function prepareYeCollection($record) {

        $post_data = YEHelper::$yeFetchedPost;
        $description = strip_tags($post_data->content->rendered);

        $yeLessonCollection = new YELessonCollection($record);
        $yeLessonCollection->description = $description;
        $yeLessonCollection->formatContent($record);

        self::$YELessonCollection = $yeLessonCollection;
    }

    public static function prepareResources($record) {
                
        $videos = is_array($record->videos) ? $record->videos : [];
        foreach ($videos as $video_record) {            
            $videoResource = new YEResource($video_record,'video' , self::$YELessonCollection);            
            self::$YELessonCollectionResources['video_resources'][] = $videoResource;
        }        
                
        $resources = is_array($record->resources) ? $record->resources : [];
        foreach ($resources as $resource_record) {
            $resourceResource = new YEResource($resource_record,'resource', self::$YELessonCollection);            
            self::$YELessonCollectionResources['resource_resources'][] = $resourceResource;
        }
        
    }
    
    public static function syncCollectionAndResourcesContents($record){        
        
        self::$YELessonCollection->formatContent($record,self::$YELessonCollectionResources);        
        
        foreach (YEHelper::$YELessonCollectionResources['video_resources'] as $key => $video_resource) {
            $video_resource->formatVideoContent(null,self::$YELessonCollection);
            YEHelper::$YELessonCollectionResources['video_resources'][$key] = $video_resource;
        }
        foreach (YEHelper::$YELessonCollectionResources['resource_resources'] as $key => $resource) {
            $resource->formateResourceContent(null,self::$YELessonCollection);
            YEHelper::$YELessonCollectionResources['resource_resources'][$key] = $resource;            
        }
    }
    
    public static function yeAssingResourceToCollection($resourceid, $collectionid ,$cntr) {
        global $wpdb;
        $wpdb->insert(  'collectionelements', array(
                        "collectionid"=> $collectionid , 
                        'resourceid' => $resourceid,
                        'displayseqno' => $cntr
                    ));
    }
    
    public static function topicsCollectionsIdMap(){
        
        $topicsCollectionsIdMap = [];             
        
        if($_SERVER['HTTP_HOST'] === 'www.curriki.local'){
            $topicsCollectionsIdMap = array(
                'YE Applied Princ. Entre' => 104631,
                'YE Business Finance' => 104632,
                'YE Economics' => 104633,
                'YE Entrepreneurial Mindset' => 104634,
                'YE Foundational Values' => 104635,
                'YE Culture' => 104636,
                'YE Marketing' => 104637,
                'YE Resources' => 104638
            );
        }else{            
            $topicsCollectionsIdMap = array(
                'YE Applied Princ. Entre' => 308883,
                'YE Business Finance' => 308884,
                'YE Economics' => 308885,
                'YE Entrepreneurial Mindset' => 308886,
                'YE Foundational Values' => 308887,
                'YE Culture' => 308888,
                'YE Marketing' => 308889,
                'YE Resources' => 308890
            );
        }                        
        return $topicsCollectionsIdMap;
    }
}
