<?php
namespace CurrikiSite\Core;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use CurrikiSite\Components\Resource\Resource;
use CurrikiSite\Modules\Oer\Oer;
use CurrikiSite\Modules\AnalyticsSync\Cron as AnalyticsSyncCron;
use CurrikiSite\WP\Oer\CustomPost as OerCustomPost;


/**
 * IoC Container
 *
 * @author waqarmuneer
 */

class Container {
    public static function load(){
        $container_builder = new ContainerBuilder();
        
        //*** Curriki Components ***
        $container_builder->register('resource', Resource::class);
        
        //*** Curriki Modules ***
        $container_builder->register('oer', Oer::class)->addArgument(new Reference('resource'));
        $container_builder->register('analytics_sync_cron', AnalyticsSyncCron::class)->addArgument(new Reference('resource'));
        
        //*** WP related classes *** 
        $container_builder->register('wp_oer_custom_post', OerCustomPost::class);
        
        return $container_builder;
    }
}
