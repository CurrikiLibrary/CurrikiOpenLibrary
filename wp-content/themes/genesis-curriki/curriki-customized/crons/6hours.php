<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once( '../../../../../wp-load.php' );

$wp_analytify = new WP_Analytify();
$start_date = "2006-01-01";
$end_date = date("Y-m-d");
$stats = $wp_analytify->pa_get_analytics_dashboard( 'ga:sessions,ga:bounces,ga:newUsers,ga:entrances,ga:pageviews,ga:sessionDuration,ga:avgTimeOnPage,ga:users', $start_date, $end_date);

if(!is_null($stats)){
    
    echo '<pre>';
    print_r($stats);
    print_r($stats->totalsForAllResults);

    global $wpdb;
    $visitors = $stats->totalsForAllResults['ga:newUsers'];
    $users = $wpdb->get_var("select count(*) from users;");
    //CloudSearch processing processes the resources (approx 55,000) like bookshare.org and nsta.org
    $resources = $wpdb->get_var("select count(*) + 60060 from resources where ((type = 'collection' and title <> 'Favorites') or type = 'resource') and active = 'T';");
    $groups = $wpdb->get_var("select count(*) from cur_bp_groups;");
    $searchresources = $wpdb->get_var("select count(*)
                            from resources
                            where active = 'T'
                            and ((type = 'collection' and title <> 'Favorites') or type = 'resource')
                            and access <> 'private'; ");
    if($visitors){
        $data = array( 
                'visitors' => $visitors,
                'members' => $users,
                'groups' => $groups,
                'resources' => $resources,
                'searchresources' => $searchresources,
                'lastprocessed' => date('Y-m-d H:i:s')
        );
    } else {
        $data = array(
                'members' => $users,
                'groups' => $groups,
                'resources' => $resources,
                'searchresources' => $searchresources,
                'lastprocessed' => date('Y-m-d H:i:s')
        );
    }
    $wpdb->update( 
            'sites', 
            $data, 
            array( 'sitename' => 'curriki' ), 
            array( 
                    '%d',
                    '%d',
                    '%d',
                    '%d',
                    '%s'
            ), 
            array( '%s' ) 
    );
    echo $wpdb->last_query;
    //wp_mail("sajid.curriki@nxvt.com", "Cron Job Executed!", $wpdb->last_query);
    
}