<?php
namespace CurrikiSite;

use CurrikiSite\Core\Singleton;
use CurrikiSite\Core\Container;
use CurrikiSite\Modules\AnalyticsSync\Cron as AnalyticsSyncCron;

/**
 * Description of Bootstarp
 *
 * @author waqarmuneer
 */

class Applicaton {
    
    use Singleton;
    
    public $container = null;
    
    public function bootstrap(){        
        $this->plugin_url = plugins_url( '/', realpath(dirname(__FILE__)) );
        $this->plugin_path = plugin_dir_path( realpath(dirname(__FILE__)) ); 
        
        $container = new \stdClass();
        $container->analytics_sync_cron = new AnalyticsSyncCron();        
        $this->container = $container;        
        $GLOBALS['curriki'] = Applicaton::getInstance();        
    }       
    
}
