<?php
namespace CurrikiSite\Components\Resource;
use CurrikiSite\Components\Resource\Repositories\ResourceRepository;

/**
 * Description of Resource
 *
 * @author waqarmuneer
 */

class Resource {
    
    public function getOne($resourceid = 0, $pageurl = ''){        
        $resource = new ResourceRepository();
        return $resource->getOne($resourceid, $pageurl);        
    }
    
    public function getAllOnwardFromId($resourceid = 0){
        $resource = new ResourceRepository();
        return $resource->getAllOnwardFromId($resourceid);
    }
    
    public function getViewsOnwardByResourceId($resourceid = 0){
        $resource = new ResourceRepository();
        return $resource->getViewsOnwardByResourceId($resourceid);
    }
    
    public function getAllViewsByDate($viewdate = null){
        $resource = new ResourceRepository();
        $viewdate = is_null($viewdate) ?  date('Y-m-d H:i:s') : $viewdate;
        return $resource->getAllViewsByDate($viewdate);
    }
    
    public function getVisitsOnwardByResourceId($resourceid = 0){
        $resource = new ResourceRepository();
        return $resource->getVisitsOnwardByResourceId($resourceid);
    }
    
    public function getResourcefilesModel() {
        $resource = new ResourceRepository();
        return $resource->resourcefiles_model;
    }
    
    public function getCommentsModel() {
        $resource = new ResourceRepository();
        return $resource->comments_model;
    }
    
}
