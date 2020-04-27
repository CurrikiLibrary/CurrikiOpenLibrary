<?php
namespace CurrikiSite\Modules\AnalyticsSync;
use CurrikiSite\Components\Resource\Resource;
use CurrikiRecommender\Recommender;

class Cron {
    
    public $curriki_recommender = null;
    
    public function __construct() {
        $resource_component = new Resource();
        if( isset($_GET["cron"]) && $_GET["cron"] === "analytics_sync_cron" ){
            $this->loadCurrikiRecommenderPlugin();            
            if(!is_null($this->curriki_recommender)){                
                $this->execute($resource_component);
            }                        
        }
    }
    
    public function loadCurrikiRecommenderPlugin() {
        $curriki_recommender_dir = WP_PLUGIN_DIR . '/curriki-recommender';
        $curriki_recommender_path = $curriki_recommender_dir.'/curriki-recommender.php';
        if(file_exists($curriki_recommender_path)){
            require_once $curriki_recommender_dir.'/vendor/autoload.php';                
            Recommender::getInstance()->pluginSetup();
            $this->curriki_recommender = $GLOBALS['curriki_recommender'];                
        }                        
    }
    
    public function execute($resource_component) {
        
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        
        $this->syncResources($resource_component);
        $this->syncResourceViews($resource_component);
        $this->syncResourceFilesAndDownloads($resource_component);
        $this->syncResourceComments($resource_component);
        die("**** Sync Executed! ****");
    }
    
    
    
    public function syncResourceFilesAndDownloads($resource_component) {        
        $resourcefile_last_from_recommender = (object)$this->curriki_recommender->resource_repository->getLastResourceFile(); 
        $resourcefiles_onward = $resource_component->getResourcefilesModel()->getOnwardById($resourcefile_last_from_recommender->fileid);        
        $resourcefiles_to_sync = [];        
        for($i=0; $i < count($resourcefiles_onward); $i++){            
            $resourcefiles_to_sync[] = (array) $resourcefiles_onward[$i];                                    
        }                          
        
        if(count($resourcefiles_to_sync) > 0){
            $this->curriki_recommender->resource_repository->saveResourceFiles($resourcefiles_to_sync);        
        }        
        
        $filedownload_last_from_recommender = (object)$this->curriki_recommender->resource_repository->getFileDownloadsModel()->getLast();         
        $filedownloads_onward = $resource_component->getResourcefilesModel()->fileDownloadOnwards($filedownload_last_from_recommender->downloaddate);        
        for($x=0; $x < count($filedownloads_onward); $x++){
            $filedownload_to_sync = (array)$filedownloads_onward[$x];
            $this->curriki_recommender->resource_repository->saveFileDownloads($filedownload_to_sync);
        }        
    }
    
    public function syncResourceComments($resource_component) {        
        $resourcecomment_last_from_recommender = (object)$this->curriki_recommender->resource_repository->getResourceCommentsModel()->getLast();        
        $query_date = $resourcecomment_last_from_recommender->commentdate;         
        $latest_comments = $resource_component->getCommentsModel()->getOnwardByDate($query_date);                                                
        for($i=0; $i < count($latest_comments); $i++){
            $comment_to_sync = (array)$latest_comments[$i];
            $this->curriki_recommender->resource_repository->saveResourceComments($comment_to_sync);            
        }                      
    }
    
    public function syncResourceViews($resource_component) {        
        $resourceview_last_from_recommender = (object)$this->curriki_recommender->resource_repository->getLastResourceViews();                
        $query_date = $resourceview_last_from_recommender->viewdate;         
        $latest_resourceviews = $resource_component->getAllViewsByDate($query_date);
          
        for($i=0; $i < count($latest_resourceviews); $i++){
            $resourceview_to_sync = (array)$latest_resourceviews[$i];
            $this->curriki_recommender->resource_repository->saveResourceViews($resourceview_to_sync);            
        }                      
    }
    
    
    public function syncResources($resource_component) {
                
        $resource_last_from_recommender = (object)$this->curriki_recommender->resource_repository->getLast();                
        $latest_resources = $resource_component->getAllOnwardFromId($resource_last_from_recommender->resourceid);
        
        for($i=0; $i < count($latest_resources); $i++){
            $rs = $latest_resources[$i];            
            $resource_to_sync = (array)$resource_component->getOne($rs->resourceid);            
            if($resource_to_sync){
                $this->curriki_recommender->resource_repository->save($resource_to_sync);                                
                $subjects_to_sync = [];
                foreach($resource_to_sync['subjects'] as $subject){
                    $subjects_to_sync[] = [ 'subjectareaid' => $subject->subjectareaid, 'resourceid' => $resource_to_sync['resourceid'] ];
                }
                if(count($subjects_to_sync) > 0){
                    $this->curriki_recommender->resource_repository->saveSubjectAreas($subjects_to_sync);
                }                
                $education_levels_to_sync = [];
                foreach($resource_to_sync['education_levels'] as $education_level){
                    $education_levels_to_sync[] = [ 'educationlevelid' => $education_level->levelid, 'resourceid' => $resource_to_sync['resourceid'] ];
                }
                if(count($education_levels_to_sync) > 0){
                    $this->curriki_recommender->resource_repository->saveEducationLevels($education_levels_to_sync);
                }                                                                                 
            }
        }                
        
    }
    
}
