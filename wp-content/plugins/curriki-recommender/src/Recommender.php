<?php
namespace CurrikiRecommender;

use CurrikiRecommender\Core\Singleton;
use CurrikiRecommender\Repositories\ResourceRepository;
use CurrikiRecommender\Repositories\PremiumResourceRepository;
use CurrikiRecommender\Factories\RanksAnalyticsFactory;
use CurrikiRecommender\Services\RandomResources;

/**
 * Recommender is main class which initiate plugin settings,
 * premium resources and analytics classes
 *
 * @author waqarmuneer
 */

class Recommender{
    
    use Singleton;
    
    public $plugin_url = '';
    public $plugin_path = '';    
    public $ranks_analytics = [];
    public $resource_repository = null;
    public $premium_resource_repository = null;
    public $services = [];


    private function __construct(){}

    /**
     * Setup basic setting variable and initialize premium resources 
     * and analytics classes.
     * It is used by 'plugins_loaded' WP action
     */
    public function pluginSetup(){
        $this->plugin_url = plugins_url( '/', realpath(dirname(__FILE__)) );
        $this->plugin_path = plugin_dir_path( realpath(dirname(__FILE__)) ); 
        $this->initializePlugin();        
        $GLOBALS['curriki_recommender'] = Recommender::getInstance();        
    }
    
    
    public function initializePlugin() {        
        try{            
            $this->ranks_analytics['resource_views'] = RanksAnalyticsFactory::createRanksAnalytics('resource_views');
            $this->ranks_analytics['resource_rating'] = RanksAnalyticsFactory::createRanksAnalytics('resource_rating');
            $this->ranks_analytics['resource_file_downloads'] = RanksAnalyticsFactory::createRanksAnalytics('resource_file_downloads');              
            $this->resource_repository = new ResourceRepository();  
            $this->premium_resource_repository = new PremiumResourceRepository();
            
            $random_resources = new RandomResources();
            $random_resources->setRanksAnalytics($this->ranks_analytics);
            $this->services['random_resources'] = $random_resources;
            
        } catch (\Exception $ex) {}
    }
}

