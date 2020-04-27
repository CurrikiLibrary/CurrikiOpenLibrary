<?php

/**
 * CommunitiesRepository
 *
 */

require_once __DIR__."/../Entity/Communities.php";

class CommunitiesRepository
{
    public $wpdb;
    public $communities;
    
    function __construct($community = null) {
        if(!$community)
        {
            $this->communities = new Communities();
        }
    }
    
    function getCommunityPageByUrl($url)
    {                
        return $this->wpdb->get_row( $this->wpdb->prepare( "select * from communities where url = %s", $url ) );
    }
    function getCommunityPageById($communityid)
    {                
        return $this->wpdb->get_row( $this->wpdb->prepare( "select * from communities where communityid = %s", $communityid ) );
    }
    function getCommunityAnchors($community_id)
    {                
        return $this->wpdb->get_results( $this->wpdb->prepare( "select * from community_anchors where communityid = %d order by displayseqno asc", $community_id ) );
    }
    function getCommunityUpdate($community)
    {                
                 
         $this->wpdb->update( 
                'communities', 
                array( 
                        'name' => $community->getName(),	
                        'tagline' => $community->getTagline(),	
                        'url' => $community->getUrl()                        
                ), 
                array( "communityid" => $community->getCommunityid() ), 
                array( 
                        '%s','%s','%s','%s','%s','%s'
                ), 
                array( '%d' ) 
        );                 
    }
    function communityDelete($communityid)
    {                
        $this->wpdb->delete( 'community_groups', array( 'communityid' => $communityid ), array( '%d' ) );
        $this->wpdb->delete( 'community_collections', array( 'communityid' => $communityid ), array( '%d' ) );
        $this->wpdb->delete( 'community_anchors', array( 'communityid' => $communityid ), array( '%d' ) );
        $this->wpdb->delete( 'communities', array( 'communityid' => $communityid ), array( '%d' ) );
    }
    function communityAdd($community)
    {                
                 
         $this->wpdb->insert( 
                'communities', 
                array( 
                        'name' => $community->getName(),	
                        'tagline' => $community->getTagline(),	
                        'url' => $community->getUrl(),	                        
                        'image' => $community->getImage(),	
                        'logo' => $community->getLogo()	
                ),                 
                array( 
                        '%s',	// value1
                        '%s',	// value1
                        '%s',	// value1
                        '%s',	// value1
                        '%s',	// value1
                        '%s',	// value1
                        '%s',	// value1
                        '%s',	// value1                        
                )
        );
         
        return $this->wpdb->insert_id;
    }
}
