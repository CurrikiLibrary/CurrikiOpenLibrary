<?php

/*
  Description: All curriki resource functions are added to this class.
  Author: Tahir Mustafa
 */

class CurrikiResources {

  function getResourceById($resourceid = 0, $pageurl = '', $all = false) {
    global $wpdb;

    if ($resourceid) {
      $query = 'SELECT r.*, rt.thumb_image, lan.displayname AS languageName, l.displayname AS licenseName, cu.`display_name` AS contributorid_Name, rf.fileid, rf.uniquename, rf.folder FROM `resources` AS r LEFT JOIN resource_thumbs AS rt ON r.resourceid = rt.resourceid LEFT JOIN `languages` AS lan ON (lan.language = r.language) LEFT JOIN `licenses` AS l ON (l.licenseid = r.licenseid) LEFT JOIN `cur_users` AS cu ON (cu.ID = r.contributorid) LEFT JOIN `resourcefiles` AS rf ON (rf.resourceid = r.resourceid) WHERE r.`resourceid` = "' . $resourceid . '"';
    } else {
      $query = 'SELECT r.*, rt.thumb_image, lan.displayname AS languageName, l.displayname AS licenseName, cu.`display_name` AS contributorid_Name, rf.fileid, rf.uniquename, rf.folder FROM `resources` AS r LEFT JOIN resource_thumbs AS rt ON r.resourceid = rt.resourceid LEFT JOIN `languages` AS lan ON (lan.language = r.language) LEFT JOIN `licenses` AS l ON (l.licenseid = r.licenseid) LEFT JOIN `cur_users` AS cu ON (cu.ID = r.contributorid) LEFT JOIN `resourcefiles` AS rf ON (rf.resourceid = r.resourceid) WHERE r.`pageurl` = "' . $pageurl . '"';
    }

    $resource = $wpdb->get_row($query);
    if (is_object($resource)) {
      $resource = (array) $resource;

      if ($all) {
        $type = self::getResourceTypeById($resource['resourceid']);
        
        if ($type)
          $resource = array_merge($resource, array('typeName' => $type));

        $collection = self::getResourceCollectionById($resource['resourceid']);
        if ($collection)
          $resource = array_merge($resource, array('collection' => $collection));

        //[start] ==========  FETCHING TABLE OF CONTENTS ==========        
        $toc_persist = array();
        $toc_persist_rids = array();
        if (isset($_GET["mrid"])) {
          $mrid_param = explode("-", $_GET["mrid"]);
          if (in_array($resource['resourceid'], $mrid_param)) {
            $pos = array_search($resource['resourceid'], $mrid_param);
            unset($mrid_param[$pos]);
          }

          foreach ($mrid_param as $mrid) {
            $rid_to_fetech_collection = 0;
            $resources_table_of_content = new stdClass();
            $resources_table_of_content->main_resource_resources = array();
            $resources_table_of_content->current_resource_resources = array();

            $toc_persist_rids[] = $mrid;
            $rid_to_fetech_collection = $mrid;
            $query_r = 'SELECT r.* FROM `resources` AS r WHERE r.`resourceid` = "' . $rid_to_fetech_collection . '"';
            $resource_obj = $wpdb->get_row($query_r);
            $r_data = array(
                "resource" => $resource_obj,
                "collections" => self::getResourceCollectionById($rid_to_fetech_collection)
            );
            $resources_table_of_content->main_resource_resources = $r_data;
            $toc_persist[] = $resources_table_of_content;
          }
        }

        $resource = array_merge($resource, array('toc_persist' => $toc_persist));
        if (in_array($resource['resourceid'], $toc_persist_rids)) {
          //$pos = array_search($resource['resourceid'], $toc_persist_rids);
          //unset($toc_persist_rids[$pos]);
        }
        $resource = array_merge($resource, array('toc_persist_rids' => $toc_persist_rids));
        //[END] ==========  FETCHING TABLE OF CONTENTS ==========


        $subjects = self::getResourceSubjectById($resource['resourceid']);
        if ($subjects)
          $resource = array_merge($resource, array('subjects' => $subjects));

        $educationlevels = self::getResourceEducationLevelsById($resource['resourceid']);
        if ($educationlevels)
          $resource = array_merge($resource, array('educationlevels' => $educationlevels));

        $standards = self::getResourceStandardsById($resource['resourceid']);
        if ($standards)
          $resource = array_merge($resource, array('standards' => $standards));

        $comments = self::getResourceCommentsById($resource['resourceid']);
        //if ($comments)
        $resource = array_merge($resource, array('comments' => $comments));

        if ($userid = get_current_user_id())
          $currentUser = self::getUserNameById($userid);
        if (isset($currentUser))
          $resource = array_merge($resource, array('currentUser' => $currentUser));

        //==== get collection current resource belongs to ========
        $collectionsResourceBlogngsTo = self::getCollectionsResourceBlogngsTo($resource['resourceid']);
        $resource = array_merge($resource, array('collections_resource_blogngs_to' => $collectionsResourceBlogngsTo));
      }

      return $resource;
    } else
      return false;
  }

function getPreviewResourceById($resourceid = 0, $pageurl = '', $all = false) {
    global $wpdb;
    
    
    
    if ($resourceid) {
      $query = 'SELECT r.*, lan.displayname AS languageName, l.displayname AS licenseName, cu.`display_name` AS contributorid_Name, rf.fileid, rf.uniquename, rf.folder FROM `preview_resources` AS r LEFT JOIN `languages` AS lan ON (lan.language = r.language) LEFT JOIN `licenses` AS l ON (l.licenseid = r.licenseid) LEFT JOIN `cur_users` AS cu ON (cu.ID = r.contributorid) LEFT JOIN `resourcefiles` AS rf ON (rf.resourceid = r.resourceid) WHERE r.`resourceid` = "' . $resourceid . '"';
    } else {
      $query = 'SELECT r.*, lan.displayname AS languageName, l.displayname AS licenseName, cu.`display_name` AS contributorid_Name, rf.fileid, rf.uniquename, rf.folder FROM `preview_resources` AS r LEFT JOIN `languages` AS lan ON (lan.language = r.language) LEFT JOIN `licenses` AS l ON (l.licenseid = r.licenseid) LEFT JOIN `cur_users` AS cu ON (cu.ID = r.contributorid) LEFT JOIN `resourcefiles` AS rf ON (rf.resourceid = r.resourceid) WHERE r.`pageurl` = "' . $pageurl . '"';
    }
    
    $resource = $wpdb->get_row($query);
    

    if (is_object($resource)) {
      $resource = (array) $resource;
      
      if ($all) {
          $r_it_arr = unserialize($resource['resource_instructiontypes']);
        $type = self::getPreviewResourceTypeById($r_it_arr);
        
        if ($type)
          $resource = array_merge($resource, array('typeName' => $type));
        // Old collection ids to get its children
//        if(isset($resource['editresourceid']) && $resource['editresourceid'] != ''):
//            $collection = self::getPreviewResourceCollectionById($resource['editresourceid']);
//            if ($collection)
//              $resource = array_merge($resource, array('collection' => $collection));
//        endif;
        
          
        //[start] ==========  FETCHING TABLE OF CONTENTS ==========        
        $toc_persist = array();
        $toc_persist_rids = array();
        if (/*0*/isset($_GET["mrid"])) {
          $mrid_param = explode("-", $_GET["mrid"]);
          if (in_array($resource['resourceid'], $mrid_param)) {
            $pos = array_search($resource['resourceid'], $mrid_param);
            unset($mrid_param[$pos]);
          }

          foreach ($mrid_param as $mrid) {
            $rid_to_fetech_collection = 0;
            $resources_table_of_content = new stdClass();
            $resources_table_of_content->main_resource_resources = array();
            $resources_table_of_content->current_resource_resources = array();

            $toc_persist_rids[] = $mrid;
            $rid_to_fetech_collection = $mrid;
            $query_r = 'SELECT r.* FROM `resources` AS r WHERE r.`resourceid` = "' . $rid_to_fetech_collection . '"';
            $resource_obj = $wpdb->get_row($query_r);
            $r_data = array(
                "resource" => $resource_obj,
                "collections" => self::getResourceCollectionById($rid_to_fetech_collection)
            );
            $resources_table_of_content->main_resource_resources = $r_data;
            $toc_persist[] = $resources_table_of_content;
          }
        }

        $resource = array_merge($resource, array('toc_persist' => $toc_persist));
        if (in_array($resource['resourceid'], $toc_persist_rids)) {
          //$pos = array_search($resource['resourceid'], $toc_persist_rids);
          //unset($toc_persist_rids[$pos]);
        }
        $resource = array_merge($resource, array('toc_persist_rids' => $toc_persist_rids));
        //[END] ==========  FETCHING TABLE OF CONTENTS ==========

        $subjectareas = unserialize($resource['subjectareas']);
        $subjects = self::getPreviewResourceSubjectById($subjectareas);
        
        if ($subjects)
          $resource = array_merge($resource, array('subjects' => $subjects));

        $educationlevels_arr = unserialize($resource['education_levels']);
        $educationlevels = self::getPreviewResourceEducationLevelsById($educationlevels_arr);

        if ($educationlevels)
          $resource = array_merge($resource, array('educationlevels' => $educationlevels));

        $resource_statementids = unserialize($resource['resource_statementids']);
        $standards = self::getPreviewResourceStandardsById($resource_statementids);
        
        if ($standards)
          $resource = array_merge($resource, array('standards' => $standards));

        $comments = self::getResourceCommentsById($resource['resourceid']);
        //if ($comments)
        $resource = array_merge($resource, array('comments' => $comments));

        if ($userid = get_current_user_id())
          $currentUser = self::getUserNameById($userid);
        if (isset($currentUser))
          $resource = array_merge($resource, array('currentUser' => $currentUser));

        //==== get collection current resource belongs to ========
        
        $collectionsResourceBlogngsTo = self::getPreviewCollectionsResourceBlogngsTo($resource['editresourceid']);
        if($collectionsResourceBlogngsTo != null){
            $resource = array_merge($resource, array('collections_resource_blogngs_to' => $collectionsResourceBlogngsTo));
        }
      }


      return $resource;
    } else
      return false;
  }
  
  function getCollectionsResourceBlogngsTo($resourceid) {
    global $wpdb;
    $query_rc = "
                    select title, pageurl
                    from resources r
                    inner join collectionelements ce on r.resourceid = ce.collectionid
                    where ce.resourceid = {$resourceid}  
                    and r.title <> 'Favorites'
                    and r.active = 'T'
                    and r.access = 'public'
                    ";
    return $wpdb->get_results($query_rc);
  }
  function getPreviewCollectionsResourceBlogngsTo($resourceid) {
    global $wpdb;
    if($resourceid != ""){
        $query_rc = "
                        select title, pageurl
                        from resources r
                        where r.resourceid = {$resourceid}  
                        and r.title <> 'Favorites'
                        and r.active = 'T'
                        and r.access = 'public'
                        ";
        return $wpdb->get_results($query_rc);
    } else {
        return null;
    }
    
  }

  function getResourceTypeById($resourceid) {
    global $wpdb;


    $query = 'SELECT it.displayname AS typeName FROM instructiontypes AS it LEFT JOIN `resource_instructiontypes` AS rit ON (rit.instructiontypeid = it.instructiontypeid) WHERE rit.resourceid = "' . $resourceid . '"';

    $result = array();
    $results = $wpdb->get_results($query);
    if (count($results) > 0)
      foreach ($results AS $res)
        $result[] = array('typeName' => $res->typeName);

    return $result;
  }
  function getPreviewResourceTypeById($r_it_arr) {
    global $wpdb;
    $result = array();
    if(is_array($r_it_arr) && count($r_it_arr) > 0){
        $r_it = implode(",", $r_it_arr);
        $query = "SELECT * FROM instructiontypes WHERE instructiontypeid IN ($r_it)";
    //    $query = 'SELECT it.displayname AS typeName FROM instructiontypes AS it LEFT JOIN `resource_instructiontypes` AS rit ON (rit.instructiontypeid = it.instructiontypeid) WHERE rit.resourceid = "' . $resourceid . '"';


        $results = $wpdb->get_results($query);

        if (count($results) > 0)
          foreach ($results AS $res)
            $result[] = array('typeName' => $res->displayname);
    }
    return $result;
  }

  function getResourceCollectionById($resourceid) {
    global $wpdb;


    $query = 'SELECT r.resourceid, r.title, r.content, r.description,r.reviewstatus, r.reviewrating, r.standardsalignment,r.standardsalignmentcomment,r.subjectmatter,r.subjectmattercomment,r.supportsteaching,r.supportsteachingcomment,r.assessmentsquality,r.assessmentsqualitycomment,r.interactivityquality,r.interactivityqualitycomment,r.instructionalquality,r.instructionalqualitycomment,r.deeperlearning,r.deeperlearningcomment, r.memberrating, r.partner, r.pageurl, cu.`display_name` AS contributorid_Name, rt.thumb_image FROM collectionelements AS ce LEFT JOIN `resources` AS r ON (ce.resourceid = r.resourceid) LEFT JOIN resource_thumbs AS rt ON r.resourceid = rt.resourceid LEFT JOIN `cur_users` AS cu ON (cu.ID = r.contributorid) WHERE ce.collectionid = "' . $resourceid . '" AND r.active = "T" order by displayseqno';
    
    $result = array();
    $results = $wpdb->get_results($query,ARRAY_A);
    //$results = $wpdb->get_results($query);
    /*
    if (count($results) > 0)
      foreach ($results AS $res)
        $result[] = array('resourceid' => $res->resourceid, 'title' => $res->title, 'content' => $res->content, 'description' => $res->description, 'reviewstatus' => $res->reviewstatus, 'reviewrating' => $res->reviewrating, 'memberrating' => $res->memberrating, 'contributorid_Name' => $res->contributorid_Name, 'partner' => $res->partner, 'pageurl' => $res->pageurl);
    */        
    //return $result;
    return $results;
  }
  function getPreviewResourceCollectionById($resourceid) {
    global $wpdb;


    $query = 'SELECT r.resourceid, r.title, r.content, r.description,r.reviewstatus, r.reviewrating, r.standardsalignment,r.standardsalignmentcomment,r.subjectmatter,r.subjectmattercomment,r.supportsteaching,r.supportsteachingcomment,r.assessmentsquality,r.assessmentsqualitycomment,r.interactivityquality,r.interactivityqualitycomment,r.instructionalquality,r.instructionalqualitycomment,r.deeperlearning,r.deeperlearningcomment, r.memberrating, r.partner, r.pageurl, cu.`display_name` AS contributorid_Name FROM collectionelements AS ce LEFT JOIN `resources` AS r ON (ce.resourceid = r.resourceid) LEFT JOIN `cur_users` AS cu ON (cu.ID = r.contributorid) WHERE ce.collectionid = "' . $resourceid . '" order by displayseqno';
    
    $result = array();
    $results = $wpdb->get_results($query,ARRAY_A);
    //$results = $wpdb->get_results($query);
    /*
    if (count($results) > 0)
      foreach ($results AS $res)
        $result[] = array('resourceid' => $res->resourceid, 'title' => $res->title, 'content' => $res->content, 'description' => $res->description, 'reviewstatus' => $res->reviewstatus, 'reviewrating' => $res->reviewrating, 'memberrating' => $res->memberrating, 'contributorid_Name' => $res->contributorid_Name, 'partner' => $res->partner, 'pageurl' => $res->pageurl);
    */        
    //return $result;
    return $results;
  }

  function getResourceSubjectById($resourceid) {
    global $wpdb;

    $query = 'SELECT CONCAT(s.displayname, " > " ,sa.displayname) AS displayname, sa.subjectareaid FROM `resource_subjectareas` AS rs LEFT JOIN `subjectareas` AS sa ON (rs.`subjectareaid` = sa.`subjectareaid`) inner join subjects s on sa.subjectid = s.subjectid WHERE rs.`resourceid` = "' . $resourceid . '"';

    $result = array();
    $results = $wpdb->get_results($query);
    if (count($results) > 0)
      foreach ($results AS $res)
        $result[] = $res->displayname;

    return $result;
  }
  function getPreviewResourceSubjectById($subjectareas) {
    global $wpdb;
    $result = [];
    
    if(is_array($subjectareas) && count($subjectareas) > 0){
        $subjectareas = implode(",", $subjectareas);

        $query = "SELECT CONCAT(s.displayname, ' > ' ,sa.displayname) AS displayname FROM subjectareas as sa LEFT JOIN subjects as s ON s.subjectid = sa.subjectid WHERE sa.subjectareaid IN ($subjectareas)";

        $result = array();
        $results = $wpdb->get_results($query);
        if (count($results) > 0)
          foreach ($results AS $res)
            $result[] = $res->displayname;
    }
    return $result;
  }

  function getResourceEducationLevelsById($resourceid) {
    global $wpdb;

    $query = 'SELECT e.`levelid`, e.`displayname` FROM `resource_educationlevels` AS el LEFT JOIN `educationlevels` AS e ON (el.`educationlevelid` = e.`levelid`) WHERE el.`resourceid` = "' . $resourceid . '"';

    $result = array();
    $results = $wpdb->get_results($query);
    if (count($results) > 0)
      foreach ($results AS $res)
        $result[] = $res->displayname;

    return $result;
  }
  function getPreviewResourceEducationLevelsById($educationlevels_arr) {
    global $wpdb;

    $result = [];
    if(isset($educationlevels_arr) && count($educationlevels_arr) == 1 && $educationlevels_arr[0] == ""){
        return $result;
    }
    $educationlevels = implode(",", $educationlevels_arr);
    $query = "Select * from educationlevels WHERE levelid IN ($educationlevels)";
//    $query = 'SELECT e.`levelid`, e.`displayname` FROM `resource_educationlevels` AS el LEFT JOIN `educationlevels` AS e ON (el.`educationlevelid` = e.`levelid`) WHERE el.`resourceid` = "' . $resourceid . '"';
    
    $result = array();
    $results = $wpdb->get_results($query);
    if (count($results) > 0)
      foreach ($results AS $res)
        $result[] = $res->displayname;

    return $result;
  }

  function getResourceStandardsById($resourceid) {
    global $wpdb;

    $query = 'select s.notation, st.title, s.description from resource_statements rs inner join statements s on rs.statementid = s.statementid inner join standards st on s.standardid = st.standardid where resourceid = "' . $resourceid . '"';

    $result = array();
    $results = $wpdb->get_results($query);
    if (count($results) > 0)
      foreach ($results AS $res)
        $result[] = array('notation' => $res->notation, 'title' => $res->title, 'description' => $res->description);

    return $result;
  }
  function getPreviewResourceStandardsById($resource_statementids) {
    global $wpdb;
    $result = [];
    if(is_array($resource_statementids) && count($resource_statementids) > 0){
        $resource_statementids = implode(",", $resource_statementids);
        
        $query = "select s.notation, st.title, s.description from statements s inner join standards st on s.standardid = st.standardid where s.statementid IN ($resource_statementids)";
    //    $query = 'select s.notation, st.title, s.description from resource_statements rs inner join statements s on rs.statementid = s.statementid inner join standards st on s.standardid = st.standardid where resourceid = "' . $resourceid . '"';

        $result = array();
        $results = $wpdb->get_results($query);
        if (count($results) > 0)
          foreach ($results AS $res)
            $result[] = array('notation' => $res->notation, 'title' => $res->title, 'description' => $res->description);
    }
    return $result;
  }

  function getResourceCommentsById($resourceid) {
    global $wpdb;

    $query = 'SELECT c.*, cu.display_name, u.uniqueavatarfile FROM `comments` AS c LEFT JOIN `cur_users` AS cu ON (cu.`ID` = c.`userid`) LEFT JOIN `users` AS u ON (u.userid = cu.ID) WHERE c.`resourceid` = "' . $resourceid . '" ORDER BY c.commentdate DESC';

    $result = array();
    $results = $wpdb->get_results($query);
    if (count($results) > 0)
      foreach ($results AS $res)
        $result[] = array('userid' => $res->userid, 'display_name' => $res->display_name, 'uniqueavatarfile' => $res->uniqueavatarfile, 'rating' => $res->rating, 'date' => $res->commentdate, 'comment' => $res->comment);

    return $result;
  }

  function getUserNameById($userid) {
    global $wpdb;

    $query = 'SELECT cu.display_name, u.uniqueavatarfile FROM `cur_users` AS cu LEFT JOIN `users` AS u ON (u.userid = cu.ID) WHERE cu.`ID` = "' . $userid . '"';
    $user = $wpdb->get_row($query);
    if (is_object($user))
      return array('display_name' => $user->display_name, 'uniqueavatarfile' => $user->uniqueavatarfile);
    else
      return false;
  }

  function getResourceUserById($resourceid = 0, $pageurl = '') {
    global $wpdb;
    
    if ($resourceid)
      $query = 'SELECT r.partner, r.resourceid, r.title, r.description, r.content, r.type, r.reviewstatus, r.reviewrating, r.standardsalignment,r.standardsalignmentcomment,r.subjectmatter,r.subjectmattercomment,r.supportsteaching,r.supportsteachingcomment,r.assessmentsquality,r.assessmentsqualitycomment,r.interactivityquality,r.interactivityqualitycomment,r.instructionalquality,r.instructionalqualitycomment,r.deeperlearning,r.deeperlearningcomment, r.memberrating, r.pageurl, r.contributiondate, r.reviewstatus, r.studentfacing,  r.contentdisplayok, r.reviewresource, r.oldurl, r.mediatype, r.keywords, r.generatedkeywords, r.approvalStatus , u.userid, cu.display_name, u.blogs, u.city, u.state, u.country, u.organization, u.registerdate, u.uniqueavatarfile, rf.fileid, rf.uniquename, rf.folder,r.resourcechecked,r.resourcecheckrequestnote,r.resourcechecknote,l.name AS license, r.active as resource_active FROM `resources` AS r LEFT JOIN `users` AS u ON (u.userid = r.contributorid) LEFT JOIN `cur_users` AS cu ON (cu.ID = u.userid) LEFT JOIN `licenses` AS l ON (l.licenseid = r.licenseid) LEFT JOIN `resourcefiles` AS rf ON (rf.resourceid = r.resourceid) WHERE r.`resourceid` = "' . $resourceid . '"';
    else
      $query = 'SELECT r.partner, r.resourceid, r.title, r.description, r.content, r.type, r.reviewstatus, r.reviewrating, r.standardsalignment,r.standardsalignmentcomment,r.subjectmatter,r.subjectmattercomment,r.supportsteaching,r.supportsteachingcomment,r.assessmentsquality,r.assessmentsqualitycomment,r.interactivityquality,r.interactivityqualitycomment,r.instructionalquality,r.instructionalqualitycomment,r.deeperlearning,r.deeperlearningcomment, r.memberrating, r.pageurl, r.contributiondate, r.reviewstatus, r.studentfacing,  r.contentdisplayok, r.reviewresource, r.oldurl, r.mediatype, r.keywords, r.generatedkeywords, r.approvalStatus , u.userid, cu.display_name, u.blogs, u.city, u.state, u.country, u.organization, u.registerdate, u.uniqueavatarfile, rf.fileid, rf.uniquename, rf.folder,r.resourcechecked,r.resourcecheckrequestnote,r.resourcechecknote,l.name AS license, r.active as resource_active FROM `resources` AS r LEFT JOIN `users` AS u ON (u.userid = r.contributorid) LEFT JOIN `cur_users` AS cu ON (cu.ID = u.userid) LEFT JOIN `licenses` AS l ON (l.licenseid = r.licenseid) LEFT JOIN `resourcefiles` AS rf ON (rf.resourceid = r.resourceid) WHERE r.`pageurl` = "' . $pageurl . '"';
   
    $resource = $wpdb->get_row($query);      
    
    if (is_object($resource)) {
      return (array) $resource;
    } else
      return false;
  }
  function getResourceUserByIdForResourceViews($resourceid = 0, $pageurl = '') {
    global $wpdb;

    if ($resourceid)
      $query = 'SELECT r.resourceid FROM `resources` AS r WHERE r.`resourceid` = "' . $resourceid . '"';
    else
      $query = 'SELECT r.resourceid FROM `resources` AS r WHERE r.`pageurl` = "' . $pageurl . '"';

    $resource = $wpdb->get_row($query);    
    if (is_object($resource)) {
      return (array) $resource;
    } else
      return false;
  }
  function getPreviewResourceUserById($resourceid = 0, $pageurl = '') {
    global $wpdb;

    if ($resourceid)
      $query = 'SELECT r.partner, r.resourceid, r.title, r.description, r.content, r.type, r.reviewstatus, r.reviewrating, r.standardsalignment,r.standardsalignmentcomment,r.subjectmatter,r.subjectmattercomment,r.supportsteaching,r.supportsteachingcomment,r.assessmentsquality,r.assessmentsqualitycomment,r.interactivityquality,r.interactivityqualitycomment,r.instructionalquality,r.instructionalqualitycomment,r.deeperlearning,r.deeperlearningcomment, r.memberrating, r.pageurl, r.contributiondate, r.reviewstatus, r.studentfacing,  r.contentdisplayok, r.reviewresource, r.oldurl, r.mediatype, r.keywords, r.generatedkeywords , u.userid, cu.display_name, u.blogs, u.city, u.state, u.country, u.organization, u.registerdate, u.uniqueavatarfile, rf.fileid, rf.uniquename, rf.folder,r.resourcechecked,r.resourcecheckrequestnote,r.resourcechecknote,l.name AS license FROM `preview_resources` AS r LEFT JOIN `users` AS u ON (u.userid = r.contributorid) LEFT JOIN `cur_users` AS cu ON (cu.ID = u.userid) LEFT JOIN `licenses` AS l ON (l.licenseid = r.licenseid) LEFT JOIN `resourcefiles` AS rf ON (rf.resourceid = r.resourceid) WHERE r.`resourceid` = "' . $resourceid . '"';
    else
      $query = 'SELECT r.partner, r.resourceid, r.title, r.description, r.content, r.type, r.reviewstatus, r.reviewrating, r.standardsalignment,r.standardsalignmentcomment,r.subjectmatter,r.subjectmattercomment,r.supportsteaching,r.supportsteachingcomment,r.assessmentsquality,r.assessmentsqualitycomment,r.interactivityquality,r.interactivityqualitycomment,r.instructionalquality,r.instructionalqualitycomment,r.deeperlearning,r.deeperlearningcomment, r.memberrating, r.pageurl, r.contributiondate, r.reviewstatus, r.studentfacing,  r.contentdisplayok, r.reviewresource, r.oldurl, r.mediatype, r.keywords, r.generatedkeywords , u.userid, cu.display_name, u.blogs, u.city, u.state, u.country, u.organization, u.registerdate, u.uniqueavatarfile, rf.fileid, rf.uniquename, rf.folder,r.resourcechecked,r.resourcecheckrequestnote,r.resourcechecknote,l.name AS license FROM `preview_resources` AS r LEFT JOIN `users` AS u ON (u.userid = r.contributorid) LEFT JOIN `cur_users` AS cu ON (cu.ID = u.userid) LEFT JOIN `licenses` AS l ON (l.licenseid = r.licenseid) LEFT JOIN `resourcefiles` AS rf ON (rf.resourceid = r.resourceid) WHERE r.`pageurl` = "' . $pageurl . '"';

    $resource = $wpdb->get_row($query);    
    if (is_object($resource)) {
      return (array) $resource;
    } else
      return false;
  }

  function getMediatypes() {
    global $wpdb;

    $query = 'SELECT mediatype, displayname FROM mediatypes WHERE active = "T"';

    $result = array();
    $results = $wpdb->get_results($query);
    if (count($results) > 0)
      foreach ($results AS $res)
        $result[$res->mediatype] = $res->displayname;

    return $result;
  }

  function setResourceReview() {
    global $wpdb;

    $_updateValues = array(
        'content' => stripslashes_deep($_POST['content']),
        'studentfacing' => $_POST['studentfacing'],
        'contentdisplayok' => $_POST['contentdisplayok'],
        'reviewresource' => $_POST['reviewresource'],
        'mediatype' => $_POST['mediatype'],
        'oldurl' => $_POST['oldurl']
    );

    $wpdb->update('resources', $_updateValues, array('resourceid' => (int) $_POST['resourceid']));
  }

  function setResourceComments($resourceid, $comment, $rating) {
    global $wpdb;

    $userid = get_current_user_id();
    if (!$userid)
      $userid = '10000';
    
    $data = array('resourceid' => $resourceid, 'userid' => $userid, 'comment' => $comment, 'rating' => $rating, 'commentdate' => date('Y-m-d h:i:s'));    
    $wpdb->insert('comments', $data , array('%d', '%d', '%s', '%d', '%s'));    
    $curriki_recommender = isset($GLOBALS['curriki_recommender']) ? $GLOBALS['curriki_recommender'] : null;    
    if( $curriki_recommender ){                
        //$curriki_recommender->resource_repository->saveResourceComments($data); 
    }
  }

  function setMemberRating($resourceid) {
    global $wpdb;

    $query = 'select sum(rating)/count(*) AS total, sum(rating) AS rating, count(*) AS total_comments from comments where resourceid = "' . $resourceid . '" and rating is not null';

    $rating = $wpdb->get_row($query);
    if (is_object($rating) && $rating->total_comments > 0) {
      $total = round($rating->total);
      $wpdb->update('resources', array('memberrating' => $total), array('resourceid' => $resourceid));
    }
  }

  function setResourceFileDownload($fileid) {
    global $wpdb;

    $userid = get_current_user_id();
    if (!$userid)
      $userid = '10000';
    
    $data = array('fileid' => $fileid, 'userid' => $userid, 'downloaddate' => date('Y-m-d h:i:s'));
    $wpdb->insert('filedownloads', $data , array('%d', '%d', '%s'));    
    
    $data = array('downloadid' => $wpdb->insert_id) + $data;
    $curriki_recommender = isset($GLOBALS['curriki_recommender']) ? $GLOBALS['curriki_recommender'] : null;
    if( $curriki_recommender && intval($wpdb->insert_id) > 0 ){
        try{
            //$curriki_recommender->resource_repository->saveFileDownloads($data);
        } catch (Exception $ex) {}
    }
  }

  function addToMyLibrary($resourceid) {
    global $wpdb;

    $userid = get_current_user_id();

    $query = 'select resourceid from resources where contributorid = "' . $userid . '" and type = "collection" and title = "Favorites"';
    $resource = $wpdb->get_row($query);
    if (!is_object($resource)) {
      $query = "INSERT INTO resources (`licenseid`,`contributorid`,`contributiondate`,`description`,`title`,`keywords`,`generatedkeywords`,`language`,`lasteditorid`,`lasteditdate`,`currikilicense`,`fullname`,`externalurl`,`resourcechecked`,`oldurl`,`content`,`newcontent`,`logView`,`resourcecheckrequestnote`,`resourcecheckdate`,`resourcecheckid`,`resourcechecknote`,`studentfacing`,`source`,`reviewstatus`,`lastreviewdate`,`reviewedbyid`,`reviewrating`,`technicalcompleteness`,`contentaccuracy`,`pedagogy`,`ratingcomment`,`standardsalignment`,`standardsalignmentcomment`,`subjectmatter`,`subjectmattercomment`,`supportsteaching`,`supportsteachingcomment`,`assessmentsquality`,`assessmentsqualitycomment`,`interactivityquality`,`interactivityqualitycomment`,`instructionalquality`,`instructionalqualitycomment`,`deeperlearning`,`deeperlearningcomment`,`partner`,`createdate`,`type`,`featured`,`page`,`active`,`public`,`xwd_id`,`originalcontent`,`mediatype`,`access`,`memberrating`,`aligned`,`resourcename`,`pageurl`,`indexed`,`lastindexdate`,`indexrequired`,`indexrequireddate`) SELECT `licenseid`,$userid AS `contributorid`,`contributiondate`,`description`,'Favorites' AS `title`,`keywords`,`generatedkeywords`,`language`,`lasteditorid`,`lasteditdate`,`currikilicense`,`fullname`,`externalurl`,`resourcechecked`,`oldurl`,`content`,`newcontent`,`logView`,`resourcecheckrequestnote`,`resourcecheckdate`,`resourcecheckid`,`resourcechecknote`,`studentfacing`,`source`,`reviewstatus`,`lastreviewdate`,`reviewedbyid`,`reviewrating`,`technicalcompleteness`,`contentaccuracy`,`pedagogy`,`ratingcomment`,`standardsalignment`,`standardsalignmentcomment`,`subjectmatter`,`subjectmattercomment`,`supportsteaching`,`supportsteachingcomment`,`assessmentsquality`,`assessmentsqualitycomment`,`interactivityquality`,`interactivityqualitycomment`,`instructionalquality`,`instructionalqualitycomment`,`deeperlearning`,`deeperlearningcomment`,`partner`,`createdate`,`type`,`featured`,`page`,`active`,`public`,`xwd_id`,`originalcontent`,`mediatype`,`access`,`memberrating`,`aligned`,`resourcename`,`pageurl`,`indexed`,`lastindexdate`,`indexrequired`,`indexrequireddate` FROM resources WHERE resourceid = '$resourceid'";

      $wpdb->query($query);
      $new_resourceid = $wpdb->insert_id;

      $query = "INSERT INTO collectionelements (`collectionid`, `resourceid`, `displayseqno`) VALUE ($new_resourceid,$new_resourceid,1)";
      $wpdb->query($query);
    }
  }

  function setResourceInappropriate($resourceid) {
    global $wpdb;

    $wpdb->update('resources', array('resourcechecked' => 'Q'), array('resourceid' => $resourceid));
  }

  function setResourceReviewed($resourceid) {
    global $wpdb;

    $wpdb->update('resources', array('reviewstatus' => 'submitted'), array('resourceid' => $resourceid));
  }

  function setResourceViews($resourceid , $visitid = 0) {
    global $wpdb;    
    $userid = get_current_user_id();
    if (!$userid){
      $userid = '10000';
    }
    $data = array('userid' => $userid, 'resourceid' => $resourceid, 'viewdate' => date('Y-m-d H:i:s'), 'sitename' => 'curriki' ,'visitid' => $visitid);
    $wpdb->insert('resourceviews', $data, array('%d', '%d', '%s', '%s' , '%d'));    
  }

  function getJurisdiction() {
    global $wpdb;

    $query = 'select standardid, title, jurisdictioncode from standards where active = "T" order by jurisdictioncode, title';

    $result = array();
    $results = $wpdb->get_results($query);
    if (count($results) > 0)
      foreach ($results AS $res)
        $result[$res->jurisdictioncode][$res->standardid] = $res->title;

    return $result;
  }

  function getAssociatedStatements($resourceid) {
    global $wpdb;

    $query = 'SELECT s.statementid, s.description FROM `statements` AS s RIGHT JOIN `resource_statements` AS rs ON (s.`statementid` = rs.`statementid`) WHERE rs.`resourceid` = "' . $resourceid . '"';

    $result = array();
    $results = $wpdb->get_results($query);
    if (count($results) > 0)
      foreach ($results AS $res)
        $result[$res->statementid] = array('statementid' => $res->statementid, 'description' => $res->description);

    return $result;
  }

  function getStatement($statementid) {
    global $wpdb;

    $query = 'SELECT * FROM `statements` WHERE `active` LIKE "T" AND `statementid` = "' . $statementid . '"';

    $result = $wpdb->get_row($query);
    if (is_object($result))
      return (array) $result;
    else
      return false;
  }

  function getStatements($standardid) {
    global $wpdb;

    $query = "SELECT  CAST(hi.statementid AS CHAR) AS treeitem, parentid, level, description
FROM    (SELECT hierarchy_connect_by_parent_eq_prior_id(statementid) AS statementid, @level AS level
        FROM    (SELECT  @start_with := (select statementid from statements where standardid = '$standardid' and parentid is null),
                         @id := @start_with,
                         @level := 0) vars, statements
        WHERE   @id IS NOT NULL) ho
JOIN    statements hi ON hi.statementid = ho.statementid WHERE hi.standardid = '$standardid'";
    
    $levels = array();
    $results = $wpdb->get_results($query);
    
    if (count($results) > 0)
      foreach ($results AS $res) {
        $levels[$res->level][$res->treeitem] = (array) $res;
      }
    
    for ($i = count($levels); $i > 0; $i--) {
      foreach ($levels[$i] as $res) {
        $levels[$i - 1][$res['parentid']]['children'][] = $res;
      }
    }
    
    return $levels[1];
  }

  function getJurisdictionStandards($standardid) {
    global $wpdb;

    $query = 'select standardid, title, jurisdictioncode from standards where active = "T" AND `standardid` = "' . $standardid . '" order by title';

    $result = array();
    $results = $wpdb->get_results($query);
    if (count($results) > 0)
      foreach ($results AS $res)
        $result[$res->jurisdictioncode][$res->standardid] = $res->title;

    return $result;
  }

  function saveResourceStatement($resourceid, $statementid) {
    global $wpdb;

    $userid = get_current_user_id();
    if (!$userid)
      $userid = '10000';

    $wpdb->insert('resource_statements', array('resourceid' => $resourceid, 'statementid' => $statementid, 'userid' => $userid, 'alignmentdate' => date('Y-m-d h:i:s')), array('%d', '%d', '%d', '%s'));
  }

  function removeResourceStatement($resourceid, $statementid) {
    global $wpdb;

    $wpdb->delete('resource_statements', array('resourceid' => $resourceid, 'statementid' => $statementid), array('%d', '%d'));
  }

}

function strip_spam_fun($haystack, $needles = array(), $offset = 0) {
  $chr = array();
  foreach ($needles as $needle) {
    if (stripos(strtolower($haystack), $needle->phrase) !== false) {
      $chr[] = $needle->phrase;
    }
  }
  if (empty($chr))
    return false;
  else
    return true;
}

if (isset($_POST['resource-rating'])) {
  global $wpdb, $bp;
  $res = new CurrikiResources();

  //echo "<pre>";
  //var_dump($_POST['resource-comments']);
  //==== Checking Spam Data and Setting User as spam ============     
  $cnsr_arr = $wpdb->get_results("SELECT phrase FROM censorphrases");
  $censorphrases = count($cnsr_arr) > 0 ? $cnsr_arr : array();
  if (strip_spam_fun($_POST['resource-comments'], $censorphrases, 1)) {
    $redirect_to = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $redirect_to = urlencode($redirect_to);
    wp_redirect(get_bloginfo('url') . '/message/?error-m=rate-comment-spam&rtn=' . $redirect_to);
    die();
  }
  $res->setResourceComments((int) $_POST['resourceid'], $_POST['resource-comments'], (int) $_POST['resource-rating']);
  $res->setMemberRating((int) $_POST['resourceid']);


  //======== Recording Activity ==============
  $resourceid = (int) $_POST['resourceid'];
  $resource = $wpdb->get_row("SELECT * FROM resources WHERE resourceid = $resourceid", OBJECT);

  $current_resource_link = site_url() . "/oer/" . $_REQUEST["pageurl"];
  $current_user = wp_get_current_user();

  if (isset($current_user) && $current_user->data->ID > 0) {
    $component = "resource_review";
    $profile_url = site_url() . "/members/" . $current_user->data->user_nicename;
    $user_display_name = $current_user->data->display_name;
    $resource_activity_title = '<a href="' . $current_resource_link . '">' . $resource->title . '</a>';


    $bpActType = "resource_review_insert";
    $bpActAction = '<a href="' . $profile_url . '">' . $user_display_name . '</a> rated ' . $resource_activity_title;

    $resource_activity_content = "Rating: " . $_POST['resource-rating'] . " Stars <br />";
    $resource_activity_content .= ( isset($_POST['resource-comments']) && strlen($_POST['resource-comments']) > 0 ) ? " / Comments: " . $_POST['resource-comments'] : "";

    $activity_table = $wpdb->prefix . "bp_activity";
//    $q = $wpdb->prepare("INSERT INTO {$activity_table} ( user_id , component, type, action, content, date_recorded) VALUES ( '{$current_user->data->ID}' , '$component', '$bpActType' , '$bpActAction', '$resource_activity_content' , NOW() )");
    $q = $wpdb->prepare("INSERT INTO {$activity_table} ( user_id , component, type, action, content, date_recorded) VALUES ( %d , %s, %s , %s, %s , NOW() )", $current_user->data->ID, $component, $bpActType, $bpActAction, $resource_activity_content);
    $wpdb->query($q);
  }
}

if ($_GET['resource_file_download'] == 'file') {
  fn_wp_ajax_resource_file_download();
}

function fn_wp_ajax_resource_file_download() {
  
  global $wpdb;
  $response = new stdClass();
  $res = new CurrikiResources();
  
  $settings = get_option("resource-files-download-settings");  
  
  if($settings)
  {
      $settings = json_decode($settings);
      
      if(intval($settings->is_active) === 1)
      {                    
          if( is_user_logged_in() )
          {            
            $res->setResourceFileDownload((int) $_POST['id']);            
            $response->action = "done";
            echo json_encode($response);
          }else{
            $response->action = "redirect";
            $response->redirect_url = site_url()."?a=login";
            
            $resource = $res->getResourceById((int) $_POST['rid'], rtrim($_GET['pageurl'], '/'), true);            
            $response->forward_url = $actual_link = site_url()."/oer/".$resource["pageurl"];
            echo json_encode($response);            
          }
      }else{
          $res->setResourceFileDownload((int) $_POST['id']);
          $response->action = "done";                    
          echo json_encode($response);
      }
  }else{        
        $res->setResourceFileDownload((int) $_POST['id']);        
        $response->action = "done";
        echo json_encode($response);
  }
  die();
}

if ($_GET['addtolibrary'] == 'true') {
  fn_wp_ajax_resource_addtolibrary();
}

function fn_wp_ajax_resource_addtolibrary() {
  $res = new CurrikiResources();
  echo $res->addToMyLibrary((int) $_POST['id']);

  //echo '1';
  die;
}

if ($_GET['reviewed'] == 'true') {
  fn_wp_ajax_resource_reviewed();
}

function fn_wp_ajax_resource_reviewed() {
  $res = new CurrikiResources();
  $res->setResourceReviewed((int) $_POST['id']);

  echo '1';
  die;
}

if ($_GET['inappropriate'] == 'true') {
  fn_wp_ajax_resource_inappropriate();
}

function fn_wp_ajax_resource_inappropriate() {
  $res = new CurrikiResources();
  $res->setResourceInappropriate((int) $_POST['id']);

  echo '1';
  die;
}

if ($_GET['savestatement'] == 'true') {
  $res = new CurrikiResources();
  $res->saveResourceStatement((int) $_GET['rid'], (int) $_GET['sid']);

  echo '1';
  die;
}

if ($_GET['removestatement'] == 'true') {
  $res = new CurrikiResources();
  $res->removeResourceStatement((int) $_GET['rid'], (int) $_GET['sid']);

  echo '1';
  die;
}

if ($_GET['showstatements'] == 'true') {

  $res = new CurrikiResources();
  $statements = $res->getStatements((int) $_GET['statement']);
  //$statements = $res->getStatements(100);

  if ($statements) {
    echo '<ul class="nested_with_switch vertical">';
    foreach ($statements AS $statement) {
      if (isset($statement['children'])) {//print_r($statement);die;
        echo '<li><span><i class="icon-minus-sign"></i> ' . $statement['description'] . '</span><ul>';
        foreach ($statement['children'] AS $statement2) {
          if (isset($statement2['children'])) {
            echo '<li><span><i class="icon-minus-sign"></i> ' . $statement2['description'] . '</span><ul>';
            foreach ($statement2['children'] AS $statement3) {
              if (isset($statement3['children'])) {
                echo '<li><span><i class="icon-minus-sign"></i> ' . $statement3['description'] . '</span><ul>';
                foreach ($statement3['children'] AS $statement4) {
                  if (isset($statement4['children'])) {
                    echo '<li><span><i class="icon-minus-sign"></i> ' . $statement4['description'] . '</span><ul>';
                    foreach ($statement4['children'] AS $statement5) {
                      if (isset($statement5['children'])) {
                        echo '<li><span><i class="icon-minus-sign"></i> ' . $statement5['description'] . '</span><ul>';
                        foreach ($statement5['children'] AS $statement6) {
                          if (isset($statement6['children'])) {
                            echo '<li><span><i class="icon-minus-sign"></i> ' . $statement6['description'] . '</span><ul>';
                            foreach ($statement6['children'] AS $statement7)
                              echo '<li state="' . $statement7['treeitem'] . '"><span class="pointer" onclick="display_Meta(' . $statement7['treeitem'] . ');"><i class="icon-move"></i> ' . $statement7['description'] . '</span></li>';
                            echo '</ul></li>';
                          } else
                            echo '<li state="' . $statement6['treeitem'] . '"><span class="pointer" onclick="display_Meta(' . $statement6['treeitem'] . ');"><i class="icon-move"></i> ' . $statement6['description'] . '</span></li>';
                        }
                        echo '</ul></li>';
                      } else
                        echo '<li state="' . $statement5['treeitem'] . '"><span class="pointer" onclick="display_Meta(' . $statement5['treeitem'] . ');"><i class="icon-move"></i> ' . $statement5['description'] . '</span></li>';
                    }
                    echo '</ul></li>';
                  } else
                    echo '<li state="' . $statement4['treeitem'] . '"><span class="pointer" onclick="display_Meta(' . $statement4['treeitem'] . ');"><i class="icon-move"></i> ' . $statement4['description'] . '</span></li>';
                }
                echo '</ul></li>';
              } else
                echo '<li state="' . $statement3['treeitem'] . '"><span class="pointer" onclick="display_Meta(' . $statement3['treeitem'] . ');"><i class="icon-move"></i> ' . $statement3['description'] . '</span></li>';
            }
            echo '</ul></li>';
          } else
            echo '<li state="' . $statement2['treeitem'] . '"><span class="pointer" onclick="display_Meta(' . $statement2['treeitem'] . ');"><i class="icon-move"></i> ' . $statement2['description'] . '</span></li>';
        }
        echo '</ul></li>';
      } else
        echo '<li state="' . $statement['treeitem'] . '"><span class="pointer" onclick="display_Meta(' . $statement['treeitem'] . ');"><i class="icon-move"></i> ' . $statement['description'] . '</span></li>';
    }
    echo '</ul>';
  }
  else {
    echo '<ul class="nested_with_switch vertical"><li>No related statement available.<li><ul>';
  }


  die;
}

if ($_GET['showmeta'] == 'true') {

  $res = new CurrikiResources();
  $statement = $res->getStatement((int) $_GET['id']);
  $asn_url = "http://asn.jesandco.org/resources/";
  
  $q = $wpdb->prepare("SELECT * FROM standards WHERE standardid=".(int) $_GET['sid']);
  $standard_current = $wpdb->get_row($q,OBJECT);
  
  $statement_education_levels = get_statement_education_levels((int) $_GET['id']);
  $education_level_text = (count($statement_education_levels) > 0) ? join(",", $statement_education_levels) : " None";
  $language_record = get_language_record($statement['language']);
  $language_text = ( count($language_record) > 0 ) ? $language_record[0]->displayname : " None";

  $asn_str='';
  if( $standard_current->publisher === 'ASN')
  {
      $asn_str = '<tr>
            <td width="100px" style="font-weight:bolder;">'.__('ASN URI:','curriki').'</td>
            <td><a target="_blank" href="' . $asn_url . $statement['resourceidentifier'] . '">' . $asn_url . $statement['resourceidentifier'] . '</a></td>
        </tr>';
  }
  if ($statement)
    echo '
<div class="metadata_section">
<br />
    <table border="0" cellspacing="3px" cellspadding="2px" align="center" width="60%">
    <tbody>
        '.$asn_str.'
        <tr>
            <td width="100px" style="font-weight:bolder;">'.__('Education Level(s):','curriki').'</td>
            <td>' . $education_level_text . '</td>
        </tr>
        <tr>
            <td width="100px" style="font-weight:bolder;">Subject:</td>
            <td>' . ucfirst($statement['subject']) . '</td>
        </tr>
        <tr>
            <td width="100px" style="font-weight:bolder;">'.__('Statement Notation:','curriki').'</td>
            <td>' . $statement['notation'] . '</td>
        </tr>        
        <tr>
            <td width="100px" style="font-weight:bolder;">'.__('Description:','curriki').'</td>
            <td>' . $statement['description'] . '</td>
        </tr>        
        <tr>
            <td width="100px" style="font-weight:bolder;">'.__('Language:','curriki').'</td>
            <td>' . $language_text . '</td>
        </tr>
    </tbody>
    </table>
<br />
</div>';

  die;
}

function get_statement_education_levels($statementid) {
  global $wpdb;

  $query = "SELECT * FROM statement_educationlevels";
  $query .= " LEFT JOIN educationlevels ON statement_educationlevels.educationlevelid = educationlevels.levelid";
  $query .= " WHERE statementid = " . $statementid;
  $records = $wpdb->get_results($query, OBJECT);

  $educationlevels_arr = array();
  foreach ($records as $g_sb) {
    $educationlevels_arr[] = $g_sb->displayname;
  }
  $educationlevels = array_unique($educationlevels_arr);
  return $educationlevels;
}

function get_language_record($language) {
  global $wpdb;
  $query = "SELECT * FROM languages";
  //$query .= " LEFT JOIN educationlevels ON statement_educationlevels.educationlevelid = educationlevels.levelid";
  $query .= " WHERE language = '$language'";
  $records = $wpdb->get_results($query, OBJECT);
  return $records;
}
