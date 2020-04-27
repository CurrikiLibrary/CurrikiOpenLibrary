<?php
namespace CurrikiRecommender\Repositories;
use CurrikiRecommender\Models\PremiumResource\PremiumResource;

/**
 * PremiumResourceRepository is used ot get random premium resources
 * based on subjectareas_ids and educationlevels_ids
 *
 * It use PremiumResource model
 * 
 * @author waqarmuneer
 */

class PremiumResourceRepository {
    
    private $premium_resource_model = null;
    
    public function __construct() {
        $this->premium_resource_model = new PremiumResource();
    }    
    
    /**
     * Return random premium resources based on 
     * subjectareas_ids and educationlevels_ids
     * 
     * @param $subjectareas_ids
     * @param $educationlevels_ids
     * @return array $random_premiums
     */
    public function getRandomBySubjectAreasOrEducationLevels($subjectareas_ids = [], $educationlevels_ids = []) {             
        
        $prem_collections = $this->premium_resource_model->getRandomBySubjectAreasOrEducationLevels($subjectareas_ids, $educationlevels_ids);                        
        $random_premiums = [];
        foreach($prem_collections as $prem_collection){
            $random_collection_hierarchy = [];
            $parent_collection_id = null;        
            $this->getRandomHierarchicalChildren($prem_collection, $parent_collection_id, $random_collection_hierarchy);                        
            if( count($random_collection_hierarchy) === 1 ){
                $random_premiums[] = $random_collection_hierarchy[0];
            }elseif( count($random_collection_hierarchy) > 1 ){
                $random_premiums[] = $random_collection_hierarchy[array_rand($random_collection_hierarchy,1)];
            }            
        }        
        return $random_premiums;                
        
    }
    
    private function getRandomHierarchicalChildren($collection, &$parent_collection_id, &$random_collection_hierarchy) {
      
        if($collection){
            $has_child_collections = property_exists($collection, 'has_child_collections') && intval($collection->has_child_collections) === 1;
            $has_child_resources = property_exists($collection, 'has_child_resources') && intval($collection->has_child_resources) === 1;
            if($has_child_collections || $has_child_resources){
                if($parent_collection_id === null){
                    $parent_collection_id = $collection->resourceid;
                    $child = $this->premium_resource_model->getRandomChild($collection->resourceid);                
                    $random_collection_hierarchy[] = (object)['resourceid' => $collection->resourceid, 'pageurl' => $collection->pageurl, 'title' => $collection->title];
                    return $this->getRandomHierarchicalChildren($child, $parent_collection_id, $random_collection_hierarchy);
                }else if($parent_collection_id !== null && intval($parent_collection_id) != intval($collection->resourceid)){
                    $child = $this->premium_resource_model->getRandomChild($collection->resourceid);                
                    $random_collection_hierarchy[] = (object)['resourceid' => $collection->resourceid, 'pageurl' => $collection->pageurl, 'title' => $collection->title];
                    return $this->getRandomHierarchicalChildren($child, $parent_collection_id, $random_collection_hierarchy);
                }else{
                    $child = $this->premium_resource_model->getRandomChild($collection->resourceid);
                    $random_collection_hierarchy[] = (object)['resourceid' => $collection->resourceid, 'pageurl' => $collection->pageurl, 'title' => $collection->title];
                    return $child;
                }                        
            }else{
                $random_collection_hierarchy[] = (object)['resourceid' => $collection->resourceid, 'pageurl' => $collection->pageurl, 'title' => $collection->title];
                return $collection;
            }
        }                
    }        
}
