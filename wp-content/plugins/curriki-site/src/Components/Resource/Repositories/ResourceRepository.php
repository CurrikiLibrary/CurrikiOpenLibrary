<?php
namespace CurrikiSite\Components\Resource\Repositories;
use CurrikiSite\Components\Resource\Models\ResourceModel;
use CurrikiSite\Components\Resource\Models\SubjectModel;
use CurrikiSite\Components\Resource\Models\EducationLevelModel;
use CurrikiSite\Components\Resource\Models\StandardModel;
use CurrikiSite\Components\Resource\Models\ResourceViewsModel;
use CurrikiSite\Components\Resource\Models\CommentsModel;
use CurrikiSite\Components\Resource\Models\ResourceFilesModel;

/**
 * Description of Resource
 *
 * @author waqarmuneer
 */

class ResourceRepository {
    
    protected $model = null;
    
    function __construct(){
        $this->model = new ResourceModel();
        $this->subject_model = new SubjectModel();
        $this->education_level_model = new EducationLevelModel();
        $this->standard_model = new StandardModel();
        $this->resource_views_model = new ResourceViewsModel();
        $this->comments_model = new CommentsModel();
        $this->resourcefiles_model = new ResourceFilesModel();
    }
    
    function getOne($resourceid = 0, $pageurl = '') {        
        $resource = null;        
        if( intval($resourceid) > 0 && empty($pageurl) ){
            $args = ["resourceid" => $resourceid];
            return $this->composeResource($args);
        }elseif( !empty($pageurl) ){            
            $args = ["pageurl" => $pageurl];
            return $this->composeResource($args);
        }else{
            return null;
        }        
        return $resource;
    }
    
    private function composeResource($args = []) {                        
                        
        $callback = 'getBy'. (isset($args['pageurl']) ? 'PageUrl' : 'Id');        
        $param = isset($args['pageurl']) ? $args['pageurl'] : $args['resourceid'];                        
        $resource = call_user_func(array($this->model,$callback),$param);        
        if($resource){
            $resource->subjects = $this->subject_model->getWithSubjectAreas($resource->resourceid);
            $resource->education_levels = $this->education_level_model->getByResourceId($resource->resourceid);
            $resource->standards = $this->standard_model->getByResourceId($resource->resourceid);
            $resource->comments = $this->comments_model->getByResourceId($resource->resourceid);
        }                
        return $resource;
    }
    
    
    public function getLatestByApprovalStatus($status = "pending"){
        return $this->model->getLatestByApprovalStatus($status);
    }
    
    public function getAllOnwardFromId($resourceid = 0){
        return $this->model->getAllOnwardFromId($resourceid);
    }
    
    public function getViewsOnwardByResourceId($resourceid = 0) {
        return $this->resource_views_model->getOnwardByResourceId($resourceid);
    }
    
    public function getAllViewsByDate($viewdate) {
        return $this->resource_views_model->getAllViewsByDate($viewdate);
    }
    
    public function getVisitsOnwardByResourceId($resourceid = 0) {
        return $this->resource_views_model->getVisitsOnwardByResourceId($resourceid);
    }
    
    public function getCommentsByResourceId($resourceid = 0){
        return $this->comments_model->getByResourceId($resourceid);
    }
    
}
