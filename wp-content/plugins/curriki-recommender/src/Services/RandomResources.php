<?php
namespace CurrikiRecommender\Services;
use CurrikiRecommender\Entities\Measures;

/**
 * RandomResources use setRanksAnalytics funtion to hold ranks related analyitcs based on Measures.
 * It randomize and maintain analytics history and return the resources with getByAnalytics funciton
 *
 * @author waqarmuneer
 */

class RandomResources {
    
    private $measures;
    private $ranks_analytics;   
    
    /**
     * Set ranks_analytics property
     * 
     * @param type $ranks_analytics
     */
    public function setRanksAnalytics($ranks_analytics) {
        $this->ranks_analytics = $ranks_analytics;
    }
    
    /**
     * Set measures property
     * 
     * @param Measures $measures
     */
    public function setMeasures(Measures $measures) {
        $this->measures = $measures;
    }
    
    /**
     * Return the random 'resources based' on ranks analytics and measures 
     *      
     * @return $recommended_resources
     */
    public function getByAnalytics() {
        $random_dimensions = $this->getRandomDimenstions($this->ranks_analytics);        
        $random_analytics = $this->getRandomDimensionAnalytics($random_dimensions, $this->measures);  

        if( empty($random_analytics) && !empty($this->getAnalyticsHistory()) ){        
            $this->resetAnalyticsHistory();
            $random_analytics = $this->getRandomDimensionAnalytics($random_dimensions, $this->measures);
        }

        $n_random = 4;
        $n_random_analytics = !empty($random_analytics) ? $this->nRandomizeAnalytics($random_analytics, $n_random) : [];
        $this->addAnalyticsHistory($n_random_analytics);        

        global $wpdb;
        $recommended_resources = [];
        foreach($n_random_analytics as $analytics){
            $resource = $wpdb->get_row("select resourceid, title, pageurl from resources where resourceid = ".$analytics['resourceid']);
            if($resource){
                $recommended_resources[] = $resource;
            }        
        }              

        return $recommended_resources;
    }
    
    
    /**
     * Return random 'analytics' base on random dimensions and measures
     * 
     * @param type $random_dimensions
     * @param type $measures
     * @return $random_analytics
     */
    private function getRandomDimensionAnalytics($random_dimensions, $measures) {
        $recommender_remembered_resourceids = isset($_SESSION['recommender_remembered_resourceids']) ?
                                    unserialize($_SESSION['recommender_remembered_resourceids']): [];        
        $random_analytics = [];    
        foreach ($random_dimensions as $dimension) {
            try{            

                $extra_params['exclude_resources_ids'] = $recommender_remembered_resourceids;
                $stats_records = $dimension->getStats($measures, $extra_params);                        
                foreach($stats_records as $record){
                    if(!array_key_exists($record['resourceid'], $random_analytics)){
                        $random_analytics[$record['resourceid']] = $record;
                    }
                }                                    
            } catch (Exception $ex) {}
        }

        return $random_analytics;
    }
    
    /**
     * Randomize dimenstions
     * 
     * @param type $dimensions
     * @return $random_dimensions
     */
    private function getRandomDimenstions($dimensions = []){
    
        if( !empty($dimensions) && count($dimensions) === 1){
            return $dimensions;
        }

        $random_dimension_names = array_rand($dimensions, 2);
        $random_dimensions = [];        
        foreach ($random_dimension_names as $dimension) {
            $random_dimensions[] = $dimensions[$dimension];        
        }    
        return $random_dimensions;

    }
    
    /**
     * Maitain resource ids in session for analytics
     * 
     * @param type $analytics
     * @param type $force_reset
     */
    function addAnalyticsHistory($analytics, $force_reset = false){
    
        $this->resetAnalyticsHistoryOnLimit($force_reset);
        $recommender_remembered_resourceids = isset($_SESSION['recommender_remembered_resourceids']) ? 
                unserialize($_SESSION['recommender_remembered_resourceids']) : [];
        foreach($analytics as $record){
            if( !in_array( intval($record['resourceid']) , $recommender_remembered_resourceids) ){
                $recommender_remembered_resourceids[] = intval($record['resourceid']);
            }
        }    
        $_SESSION['recommender_remembered_resourceids'] = serialize($recommender_remembered_resourceids);

    }
    
    function resetAnalyticsHistoryOnLimit($force_reset = false){        
        $recommender_remembered_resourceids = isset($_SESSION['recommender_remembered_resourceids']) ? 
                unserialize($_SESSION['recommender_remembered_resourceids']) : [];
        if(!empty($recommender_remembered_resourceids) && count($recommender_remembered_resourceids) >= 100){
            unset($_SESSION['recommender_remembered_resourceids']);
        }
        if($force_reset){
            unset($_SESSION['recommender_remembered_resourceids']);
        }
    }

    private function getAnalyticsHistory(){
        return isset($_SESSION['recommender_remembered_resourceids']) ? 
                unserialize($_SESSION['recommender_remembered_resourceids']) : [];
    }

    private function resetAnalyticsHistory(){
        unset($_SESSION['recommender_remembered_resourceids']);    
    }
    
    /**
     * Randomize analytics n time
     * 
     * @param type $random_analytics
     * @param type $n_random     
     */
    private function nRandomizeAnalytics($random_analytics,$n_random){
    
        if( is_array($random_analytics) && count($random_analytics) === 1){
            return $random_analytics;
        }        

        if(intval($n_random) > count($random_analytics)){
            $n_random = count($random_analytics) - 1;
        }   

        $random_analytics_n = [];
        $random_record = array_rand($random_analytics,$n_random);
        $random_analytics_records = is_array($random_record) ? $random_record : array($random_record);    
        foreach ($random_analytics_records as $key) {
            $random_analytics_n[] = $random_analytics[$key];
        }    

        return $random_analytics_n;
    }
    
}
