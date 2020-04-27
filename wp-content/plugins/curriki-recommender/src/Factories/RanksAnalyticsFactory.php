<?php
namespace CurrikiRecommender\Factories;
use CurrikiRecommender\Models\Ranks\ResourceViews;
use CurrikiRecommender\Models\Ranks\ResourceRating;
use CurrikiRecommender\Models\Ranks\ResourceFileDownloads;

/**
 * AnalyticsFactory return instance of Rank's Model
 *
 * @author waqarmuneer
 */

class RanksAnalyticsFactory {    

    /**        
     * @param $dimentions - expected values resource_views, resource_rating, resource_file_downloads
     * @return instance of Rank's Model
     */
    public static function createRanksAnalytics($dimentions) {
        $ranksAnalytics = null;
        switch ($dimentions) {
            case 'resource_views':
                $ranksAnalytics = ResourceViews::getInstance();
                break;
            case 'resource_rating':
                $ranksAnalytics = ResourceRating::getInstance();
                break;
            case 'resource_file_downloads':
                $ranksAnalytics = ResourceFileDownloads::getInstance();
                break;
            default:
                $ranksAnalytics = null;
        }
        return $ranksAnalytics;
    }    
}
