<?php
if($_GET['search'] == 'moreinfo'){
    global $wpdb;
    $r_ids = addslashes($_GET['r_ids']);

    $q_resourceviews = "select resourceid, count(*) resourceviews from resourceviews where resourceid in (".$r_ids.") group by resourceid;";
    $resourceviews = $wpdb->get_results($q_resourceviews);
    
    $q_collections = "select resourceid, count(*) collections from collectionelements where resourceid in (".$r_ids.") group by resourceid;";
    $collections = $wpdb->get_results($q_collections);
    
    $q_alignments = "select rs.resourceid, notation from statements s inner join resource_statements rs on rs.statementid = s.statementid where rs.resourceid in (".$r_ids.")";
    $alignments = $wpdb->get_results($q_alignments);
    
    $q_images = "SELECT r.resourceid, l.url, l.name, r.contributorid FROM resources r left join licenses l on r.licenseid = l.licenseid where r.resourceid in (".$r_ids.")";
    $images = $wpdb->get_results($q_images);
    
    $q_resource_collections = "select r.resourceid, title from resources r inner join collectionelements ce on ce.resourceid = r.resourceid where ce.collectionid in (".$r_ids.")";
    $resource_collections = $wpdb->get_results($q_resource_collections);
    
    $q_users = "select * from resources r inner join cur_users u on u.ID = r.contributorid where r.resourceid in (".$r_ids.")";
    $users = $wpdb->get_results($q_users);
    
    $q_subjects = 'SELECT CONCAT(s.displayname, " > " ,sa.displayname) AS displayname, sa.subjectareaid, rs.resourceid FROM `resource_subjectareas` AS rs LEFT JOIN `subjectareas` AS sa ON (rs.`subjectareaid` = sa.`subjectareaid`) inner join subjects s on sa.subjectid = s.subjectid WHERE rs.`resourceid` in ('.$r_ids.') ';
    $subjects = $wpdb->get_results($q_subjects);
    
    $resourceids = explode(',', $r_ids);
    
    $arr = array();
    $i = 0;
    foreach($images as $image){
        $arr[$i]['id'] = $image->resourceid;
        
        $arr[$i]['fields']['license'] = $image->name.','.$image->url;
        $arr[$i]['fields']['resourceviews'] = 0;
        foreach($resourceviews as $resourceview){
            if($image->resourceid == $resourceview->resourceid)
                $arr[$i]['fields']['resourceviews'] = $resourceview->resourceviews;
        }
        $arr[$i]['fields']['collections'] = 0;
        foreach($collections as $collection){
            if($image->resourceid == $collection->resourceid){
                $arr[$i]['fields']['collections'] = $collection->collections;
            }
        }
        $arr[$i]['fields']['alignments'] = '';
        foreach($alignments as $alignment){
            if($image->resourceid == $alignment->resourceid){
                if($arr[$i]['fields']['alignments'] != '')
                    $arr[$i]['fields']['alignments'] .= ', ';
                $arr[$i]['fields']['alignments'] .= $alignment->notation;
            }
        }
        $arr[$i]['fields']['resource_collections'] = '';
        foreach($resource_collections as $resource_collection){
            if($image->resourceid == $resource_collection->resourceid){
                $arr[$i]['fields']['resource_collections'][] = "resource name ".$i;$resource_collection->title;
            }
        }
        $arr[$i]['fields']['contributor'] = '';
        foreach($users as $user){
            if($user->ID == $image->contributorid){
                $arr[$i]['fields']['user_nicename'] = $user->user_nicename;
            }
        }
        $arr[$i]['fields']['subjects'] = '';
        foreach($subjects as $subject){
            if($subject->resourceid == $image->resourceid){
                if($arr[$i]['fields']['subjects'] != '')
                    $arr[$i]['fields']['subjects'] .= ', ';
                $arr[$i]['fields']['subjects'] .= $subject->displayname;
            }
        }
        //$arr[$i]['fields']['grade_levels'] = array(1, 2, 3, 4, 5, 6, 7);
        //$arr[$i]['fields']['type'] = 'Manual';
        $i++;
    }
    //echo '<pre>';print_r($arr);die;
    echo json_encode($arr);
    die;
}
if($_GET['add_to'] == 'my_library'){
    $resourceid = addslashes($_GET['r_id']);
    $myid = get_current_user_id();
    
    $q_collectionid = "select resourceid 
    from resources 
    where title = 'Favorites' 
    and type = 'collection'
    and contributorid = '".$myid."';";
    $collectionid = $wpdb->get_var($q_collectionid);
    
    $q_already_added = "select resourceid from collectionelements where resourceid = '".$resourceid."' and collectionid = '".$collectionid."'";
    $already_added = $wpdb->get_var($q_already_added);
    if($already_added > 0){
        echo '0';
        die;
    }
    
    if($collectionid > 0){
        $q_maxdisplayno = "select max(displayseqno) from collectionelements where collectionid = '".$collectionid."' and resourceid = '".$resourceid."'";
        $max_displayseqno = $wpdb->get_var($q_maxdisplayno);
        
        /*if( intval($collectionid) > 0 && intval($resourceid) > 0 )
        {
            $wpdb->insert( 
                'collectionelements', 
                array( 
                        'collectionid' => $collectionid,
                        'resourceid' => $resourceid,
                        'displayseqno' => ($max_displayseqno+1)
                ), 
                array( 
                        '%d', 
                        '%d', 
                        '%d' 
                ) 
            );
        }*/        
        
    }else{
        $wpdb->insert( 
            'resources', 
            array( 
                    'contributorid' => $myid,
                    'contributiondate' => date("Y-m-d H:i:s"),
                    'licenseid' => 0,
                    'description' =>  '<p itemprop="description">Favorites</p>',
                    'title' => 'Favorites',
                    'currikilicense' => 'T',
                    'language' => 'eng',
                    'resourcechecked' => 'F',
                    'source' => 'contributor',
                    'studentfacing' => 'F',
                    'reviewstatus' => 'none',
                    'type' => 'collection',
                    'active' => 'T',
                    'public' => 'T',
                    'mediatype' => 'collection',
                    'access' => 'public',
                    'aligned' => 'F',
                    'indexrequired' => 'F'
            ), 
            array( 
                    '%d', 
                    '%s', 
                    '%d', 
                    '%s', 
                    '%s', 
                    '%s', 
                    '%s', 
                    '%s', 
                    '%s', 
                    '%s', 
                    '%s', 
                    '%s', 
                    '%s', 
                    '%s', 
                    '%s', 
                    '%s', 
                    '%s', 
                    '%s'
            ) 
        );
        $collectionid = $wpdb->insert_id;
        $wpdb->update( 
            'resources', 
            array( 'pageurl' => 'Favorites-'.$collectionid ), 
            array( 'resourceid' => $collectionid ), 
            array( '%s' ), 
            array( '%d' ) 
        );
        /*$wpdb->insert( 
            'collectionelements', 
            array( 
                    'collectionid' => $myid,
                    'resourceid' => date("Y-m-d H:i:s"),
                    'displayseqno' => 0
            ), 
            array( 
                    '%d', 
                    '%d', 
                    '%d' 
            ) 
        );*/
    }
    echo '1';
    die;
}