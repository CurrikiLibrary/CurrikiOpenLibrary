<?php
namespace CurrikiRecommender\Repositories;
use CurrikiRecommender\Models\Resource;
use CurrikiRecommender\Models\ResourceSubjectAreas;
use CurrikiRecommender\Models\ResourceEducationLevels;
use CurrikiRecommender\Models\FileDownloads;
use CurrikiRecommender\Models\ResourceFiles;
use CurrikiRecommender\Models\ResourceViews;
use CurrikiRecommender\Models\ResourceComments;

/**
 * ResourceRepository is used of database operations for a Resoource and
 * associated SubjectAreas, EducationLevels, FileDownloads, ResourceFiles
 * ResourceViews and ResourceComments
 *
 * @author waqarmuneer
 */

class ResourceRepository {
    
    public function save($resource){        
        $resource_model = new Resource();
        return $resource_model->save($resource);
    }

    public function saveSubjectAreas($resource_subjectareas_list){        
        $resource_subjectareas_model = new ResourceSubjectAreas();
        return $resource_subjectareas_model->save($resource_subjectareas_list);
    }
    
    public function saveEducationLevels($resource_educationlevels_list){        
        $resource_educationlevels_model = new ResourceEducationLevels();
        return $resource_educationlevels_model->save($resource_educationlevels_list);
    }
    
    public function saveFileDownloads($data){
        $file_downloads_model = new FileDownloads();
        return $file_downloads_model->save($data);
    }       
    
    public function getFileDownloadsModel(){
        $file_downloads_model = new FileDownloads();
        return $file_downloads_model;
    }        
    
    public function saveResourceFiles($resourcefiles_list){
        $resourcefiles_model = new ResourceFiles();
        return $resourcefiles_model->save($resourcefiles_list);
    }
    
    public function getLastResourceFile(){
        $resourcefiles_model = new ResourceFiles();
        return $resourcefiles_model->getLast();
    }
    
    public function saveResourceViews($data){        
        $resourceviews_model = new ResourceViews();        
        return $resourceviews_model->save($data);
    }
    
    public function getLastResourceViews(){        
        $resourceviews_model = new ResourceViews(); 
        $result = $resourceviews_model->getLast();
        $row = is_array($result) && count($result) > 0 ? (object)$result[0] : [];
        return $row;
    }
    
    public function saveResourceComments($data){
        $resourcecomments_model = new ResourceComments();
        return $resourcecomments_model->save($data);
    }
    
    public function getResourceCommentsModel(){
        return new ResourceComments();        
    }
    
    public function getLast(){
        $resource_model = new Resource();
        $record = $resource_model->getLast();
        $resource = is_array($record) && !empty($record) ? $record[0] : null;
        return $resource;
    }

    /**
     * delete
     *
     * Delete Resources
     *
     *
     * @param array $resourceIds Ids of resources to delete
     * @return boolean
     */
    public function delete($resourceIds) {
        $resource_model = new Resource();
        $resource_model->delete($resourceIds);
    }

    /**
     * deleteSubjectAreas
     *
     * Delete SubjectAreas
     *
     *
     * @param array $resourceIds Ids of subject areas to delete
     * @return boolean
     */
    public function deleteSubjectAreas($resourceIds) {
        $resource_subjectareas_model = new ResourceSubjectAreas();
        $resource_subjectareas_model->delete($resourceIds);
    }

    /**
     * deleteEducationLevels
     *
     * Delete EducationLevels
     *
     *
     * @param array $resourceIds Ids of education levels to delete
     * @return boolean
     */
    public function deleteEducationLevels($resourceIds) {
        $resource_educationlevels_model = new ResourceEducationLevels();
        $resource_educationlevels_model->delete($resourceIds);
    }

    /**
     * deleteFileDownloads
     *
     * Delete FileDownloads
     *
     *
     * @param array $fileIds Ids of files to delete
     * @return boolean
     */
    public function deleteFileDownloads($fileIds) {
        $file_downloads_model = new FileDownloads();
        $file_downloads_model->delete($fileIds);
    }

    /**
     * deleteResourceFiles
     *
     * Delete ResourceFiles
     *
     *
     * @param array $resourceIds Ids of resource files to delete
     * @return boolean
     */
    public function deleteResourceFiles($resourceIds) {
        $resourcefiles_model = new ResourceFiles();
        $resourcefiles_model->delete($resourceIds);
    }

    /**
     * deleteResourceViews
     *
     * Delete ResourceViews
     *
     *
     * @param array $resourceIds Ids of resource views to delete
     * @return boolean
     */
    public function deleteResourceViews($resourceIds) {
        $resourceviews_model = new ResourceViews();
        $resourceviews_model->delete($resourceIds);
    }

    /**
     * deleteResourceComments
     *
     * Delete ResourceComments
     *
     *
     * @param array $resourceIds Ids of resource comments to delete
     * @return boolean
     */
    public function deleteResourceComments($resourceIds) {
        $resourcecomments_model = new ResourceComments();
        $resourcecomments_model->delete($resourceIds);
    }
}
