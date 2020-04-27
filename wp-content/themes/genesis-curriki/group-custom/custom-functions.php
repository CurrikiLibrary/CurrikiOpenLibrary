<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include("member-custom-functions.php");
include("library/misc-functions.php");
include("library/hooks.php");
include( realpath(__DIR__ . "/../modules/analytics/functions.php") );
include( realpath(__DIR__ . "/../modules/banners/functions.php") );
include( realpath(__DIR__ . "/../modules/application-handler/functions.php") );


global $group_loop_source,$group_user,$bp;
$group_loop_source = null;
$group_user = null;

function curr_set_global_vars()
{
    
    global $group_loop_source,$group_user,$bp;     
    if(isset($_POST['action']) && $_POST['action'] == 'groups_filter' && $_POST['object'] == 'groups' && isset($_POST['page']) )
    {       
        if( is_array( $bp->unfiltered_uri ) && in_array("groups", $bp->unfiltered_uri) && !in_array("members", $bp->unfiltered_uri) )
        {            
            $group_loop_source = "groups";               
        }elseif( is_array( $bp->unfiltered_uri ) && in_array("groups", $bp->unfiltered_uri) && in_array("members", $bp->unfiltered_uri) )
        {
            $group_loop_source = "members";           
            if($bp->displayed_user->userdata)
            {                
                $group_user = $bp->displayed_user->userdata;
            }
        }
    }
}


function curr_add_to_library_modal($error = ''){     
        
    global $wpdb , $bp;      
    $pagename = get_query_var('pagename');     
    
    if(is_user_logged_in())
    {
        
                
        $allowed_pages = array("oer","search","search-page","resources-curricula","community-pages-search");
        //$allowed_pages = array("oer");                    
        if(isset($pagename) && $pagename!=null && in_array($pagename, $allowed_pages))
        {              
            ob_start();
            include("modals.php");
            $model_screen = ob_get_contents();
            ob_end_clean();
            echo $model_screen;
        }
        
        $allowed_page_changepassword = array("edit-profile");
        //$allowed_pages = array("oer");                            
        if(isset($pagename) && $pagename!=null && in_array($pagename, $allowed_page_changepassword))
        {                        
            ob_start();
            include("change-password-modal.php");
            $model_screen = ob_get_contents();
            ob_end_clean();
            echo $model_screen;
        }
        
        
                
        
        //==== [start] Donation Modal ====       
        $allowed_page_dashboard = array("dashboard");
        //$allowed_pages = array("oer");                            
        if(isset($pagename) && $pagename!=null && in_array($pagename, $allowed_page_dashboard))
        {
            $q = "SELECT * FROM cur_options WHERE option_name='donationmodal'";
            $modal_options = $wpdb->get_row($q,OBJECT);
            $m_options = json_decode($modal_options->option_value);
            if(property_exists($m_options, "is_active") && $m_options->is_active === 1)
            {
                ob_start();
                include("donation-modal.php");
                $model_screen = ob_get_contents();
                ob_end_clean();
                echo $model_screen;
            }
        }
        //==== [end] Donation Modal =====
        
        
        //==== [start] Group Modal ====                  
        if(isset($bp->unfiltered_uri) && is_array($bp->unfiltered_uri) && count($bp->unfiltered_uri) == 2 && bp_current_component() == "groups" && $bp->unfiltered_uri[0] == "groups")
        {
            
            $group_meta = groups_get_groupmeta( bp_get_group_id());
            
            if( (get_current_user_id() > 0 && isset($group_meta["members_visited"])) && (bp_group_is_admin() || bp_group_is_mod() || bp_group_is_member()) )
            {                 
                $members_visited = array();
                if( $group_meta["members_visited"][0] === "" )  
                    $members_visited = array();                        
                else
                    $members_visited = explode(",",  $group_meta["members_visited"][0] );                                                
                                
                if( !in_array(get_current_user_id() , $members_visited) )
                {                                        
                    include("group-modal.php");
                    $model_screen = ob_get_contents();
                    ob_end_clean();
                    echo $model_screen;                    
                    $members_visited[] = get_current_user_id();                                                           
                    groups_update_groupmeta(bp_get_group_id(), "members_visited", implode(",", $members_visited) );                            
                }
            }                        
        }                    
        //=== [end] Group Modal ====
        
    }else{
        //===== Page-Resource process if user not logged-in========
        $allowed_pages = array("oer");        
        if(isset($pagename) && $pagename!=null && in_array($pagename, $allowed_pages))
        {                                      
            wp_enqueue_style('oer-custom-style', get_stylesheet_directory_uri() . '/js/oer-custom-script/oer-custom-style.css');
            wp_enqueue_script('oer-custom-script', get_stylesheet_directory_uri() . '/js/oer-custom-script/oer-custom-script.js', array('jquery'), false, true);
        }
    } 
    
    initialize_modals();
}
add_action('genesis_after', 'curr_add_to_library_modal');


add_action('wp_ajax_nopriv_get_user_library_collection', 'ajax_get_user_library_collection');
add_action('wp_ajax_get_user_library_collection', 'ajax_get_user_library_collection');

function ajax_get_user_library_collection() {
    
  global $wpdb;
  $user_id = get_current_user_id(); //123653
  
  $res = array();
  
    //var_dump($_REQUEST["libraryTopTreeSelectedValue"]);die;
  
  //and gr.groupid is null

  
    if($_POST["libraryTopTreeSelectedValue"] == "My Collections")
    {
        /*$sql = "
        select * from
              (select c.resourceid as RID , c.title as Collection, r.title as Resource, ce.displayseqno , 'My Collections' as Source, c.lasteditdate LastEditDate
                  from resources c
                      left outer join collectionelements ce on c.resourceid = ce.collectionid
                      left outer join resources r on ce.resourceid = r.resourceid
                      
                      left join group_resources gr on gr.resourceid = r.resourceid

              where c.type = 'collection'
                  and c.contributorid = $user_id
                  and c.active = 'T'
                                    
              group by Collection) as a";*/
        $sql = "
        select * from
              (select c.resourceid as `key` , REPLACE(c.title,'\\\','') as title, r.title as Resource, ce.displayseqno , 'My Collections' as Source, c.lasteditdate LastEditDate , true as folder, true as lazy
                  from resources c
                      left outer join collectionelements ce on c.resourceid = ce.collectionid
                      left outer join resources r on ce.resourceid = r.resourceid
                      
                      left join group_resources gr on gr.resourceid = r.resourceid

              where c.type = 'collection'
                  and c.contributorid = $user_id
                  and c.active = 'T'
                                    
              group by title) as a";
        
        $sql.= " order by Source asc,";
        
        
        if( isset($_POST['sort_by']) && $_POST['sort_by'] == 'most_recent')
        {
            //$sql.= " order by LastEditDate desc";
            $sql.= "LastEditDate desc";
        }
        if( isset($_POST['sort_by']) && $_POST['sort_by'] == 'a_to_z')
        {
            $sql.= "title asc";
        }
        if( isset($_POST['sort_by']) && $_POST['sort_by'] == 'z_to_a')
        {
            $sql.= "title desc";
        }
                
        $res = $wpdb->get_results($sql);
    }
    //$sql .= " union all ";
    if($_POST["libraryTopTreeSelectedValue"] == "My Groups" && $_POST["selected_group"] == 0)
    {
        //$my_groups_rs = groups_get_groups( array( 'user_id' => $user_id ) );                
        //$my_groups = $my_groups_rs["groups"];        
        
        $sql_g ="select cbg.id as id,cbg.name as name , c.lasteditdate LastEditDate
                       from cur_bp_groups cbg
                        inner join cur_bp_groups_members cbgm on cbgm.group_id = cbg.id
                        left join group_resources gr on gr.groupid = cbg.id
                        left join resources c on gr.resourceid = c.resourceid
                       where
                        cbgm.user_id = $user_id and cbgm.is_confirmed = 1
                        group by cbgm.group_id order by 
                ";
        
        if( isset($_POST['sort_by']) && $_POST['sort_by'] == 'most_recent')
        {
            //$sql.= " order by LastEditDate desc";
            $sql_g.= "LastEditDate desc";
        }
        if( isset($_POST['sort_by']) && $_POST['sort_by'] == 'a_to_z')
        {
            $sql_g.= "name asc";
        }
        if( isset($_POST['sort_by']) && $_POST['sort_by'] == 'z_to_a')
        {
            $sql_g.= "name desc";
        }
        /*$sql_g ="select cbg.id as id,cbg.name as name , c.lasteditdate LastEditDate
                from cur_bp_groups cbg
                        inner join cur_bp_groups_members cbgm on cbgm.group_id = cbg.id
                        inner join group_resources gr on gr.groupid = cbg.id
                        inner join resources c on gr.resourceid = c.resourceid
                        left outer join collectionelements ce on c.resourceid = ce.collectionid
                        left outer join resources r on ce.resourceid = r.resourceid
                where c.type = 'collection'
                        and c.active = 'T'
                        and cbgm.user_id = $user_id			
                group by cbgm.group_id order by LastEditDate desc";*/
        
        $res_g = $wpdb->get_results($sql_g);
        $my_groups = $res_g;
        
        $group_arr = array();        
        foreach ($my_groups as $group)
        {
            $g = new stdClass();
            $g->key = $group->id;
            $g->title = $group->name;
            $g->Source = "My Groups";            
            $g->folder = true;            
            $g->lazy = true;            
            $group_arr[] = $g;
        }
        //$group_ids = implode(',', $group_ids_arr);
        $res = $group_arr;
    }
    
    if($_POST["libraryTopTreeSelectedValue"] == "My Groups" && $_POST["selected_group"] > 0)
    {        
      
        $group_id = $_POST["selected_group"];
        
      
        /*
        $sql .=" 
                    SELECT r.resourceid as `key`, r.contributorid, r.title as Collection, r.title , 'My Collections' as Source, r.lasteditdate LastEditDate, gr.groupid as groupid , r.type as type , (select count(ce.resourceid) from collectionelements ce where ce.collectionid = r.resourceid) as resourcescount , true as folder, true as lazy
                    FROM group_resources as gr
                       left join resources as r on gr.resourceid = r.resourceid            
                    where groupid = $group_id
                        and type = 'collection'
                        and r.contributorid in (
                                                    SELECT user_id
                                                            FROM cur_bp_groups_members gm														
                                                    where 
                                                            gm.group_id = $group_id
                                                            and gm.is_banned = 0
                                                            and gm.is_confirmed = 1
                                                )
                    UNION   
                       SELECT r.resourceid as `key`, r.contributorid, r.title as Collection, r.title , 'My Collections' as Source, r.lasteditdate LastEditDate, gr.groupid as groupid , r.type as type , (select count(ce.resourceid) from collectionelements ce where ce.collectionid = r.resourceid) as resourcescount , false as folder, false as lazy
                       FROM group_resources as gr
                          left join resources as r on gr.resourceid = r.resourceid            
                       where groupid = $group_id
                           and type = 'resource'                    
                           and r.contributorid in (
                                                        SELECT user_id
                                                                FROM cur_bp_groups_members gm														
                                                        where 
                                                                gm.group_id = $group_id
                                                                and gm.is_banned = 0
                                                                and gm.is_confirmed = 1
                                                   )
             ";
        */
        $sql .="                     
            select * from(
                    SELECT r.resourceid as `key`, r.contributorid, r.title as Collection, REPLACE(r.title,'\\\','') as title , 'My Collections' as Source, r.lasteditdate LastEditDate, gr.groupid as groupid ,r.type as type , 
                    case when gm.user_id is null then false else true end as folder,
                    case when gm.user_id is null then false else true end as lazy
                    FROM group_resources as gr
                    inner join resources as r on gr.resourceid = r.resourceid
                    left outer join (select distinct user_id from cur_bp_groups_members where group_id = $group_id) gm on gm.user_id = r.contributorid     
                    where gr.groupid = $group_id
                    and type = 'collection'                      
                    UNION 
                    SELECT r.resourceid as `key`, r.contributorid, r.title as Collection, REPLACE(r.title,'\\\','') as title , 'My Collections' as Source, r.lasteditdate LastEditDate, gr.groupid as groupid ,r.type as type , false, false as lazy
                    FROM group_resources as gr
                    inner join resources as r on gr.resourceid = r.resourceid          
                    where groupid = $group_id
                    and type = 'resource'
                ) as a
             ";
        
        $sql.= " order by folder desc, type asc";
        /*
        $sql.= " order by Source asc,";
        
        if( isset($_POST['sort_by']) && $_POST['sort_by'] == 'most_recent')
        {
            //$sql.= " order by LastEditDate desc";
            $sql.= "LastEditDate desc";
        }
        if( isset($_POST['sort_by']) && $_POST['sort_by'] == 'a_to_z')
        {
            $sql.= "Collection asc";
        }
        if( isset($_POST['sort_by']) && $_POST['sort_by'] == 'z_to_a')
        {
            $sql.= "Collection desc";
        }
        */
        
        $res = $wpdb->get_results($sql);
        
        $group_resources_arr = array();
        foreach ($res as $gc) {                        
            if($gc->folder == 0)
            {
                unset($gc->folder);
            }
            if($gc->lazy == 0)
            {
                unset($gc->lazy);
            }
            $group_resources_arr[] = $gc;
        }        
        $res = $group_resources_arr; 
        
        
        if(count($res) == 0)
        {
            $no_record_obj = new stdClass();
            $no_record_obj->title = "No Record Found !";
            $no_record_obj->no_record = 1;
            $res[] = $no_record_obj;
        }
        
    }

  /*$wpdb->show_errors();
  $wpdb->print_error();
  die;*/
  
  echo json_encode($res);  
  //echo "<pre";
  //var_dump($_REQUEST);  
  wp_die(); 
}

add_action('wp_ajax_nopriv_get_user_library_collection_resources', 'ajax_get_user_library_collection_resources');
add_action('wp_ajax_get_user_library_collection_resources', 'ajax_get_user_library_collection_resources');

function ajax_get_user_library_collection_resources() {
  global $wpdb;
  $rid = $_REQUEST["rid"];
  
  /*
  $sql = "
            select r.resourceid as RID , ce.resourceid as ColRid , r.title as Resource, ce.displayseqno from collectionelements ce
                join resources r on ce.resourceid = r.resourceid
            and ce.collectionid IN ($rid)
                order by ce.displayseqno asc
         ";
  */
  
  //========== Re-arranging the sort order ============
  /*$sql_order_a = "set @ordval := 0";
  $sql_order_b = "update collectionelements set `displayseqno` = (select @ordval := @ordval + 1) where collectionid=$rid order by displayseqno asc;";
  $wpdb->query($sql_order_a);
  $wpdb->query($sql_order_b);
  */
  $sql = "
            select r.resourceid as `key` , ce.resourceid as ColRid , REPLACE(r.title,'\\\','') as title, ce.displayseqno, r.type
            from collectionelements ce
                join resources r on ce.resourceid = r.resourceid
            and ce.collectionid IN ($rid)
                order by r.type, ce.displayseqno asc
         ";  
  $res = $wpdb->get_results($sql);
  
  if(count($res) > 0)
  {
      $rs = array();
      foreach($res as $r)
      {
          if($r->type === "collection")
          {
              $r->folder = 1;
              $r->lazy = 1;
              $r->ExpandableNode = 1;
          }
          $r->ExtendedNodeType = $r->type;          
          $r->ExtendedNode = 1;          
          $rs[] = $r;
      }
      $res = $rs;
  }
  elseif(count($res) == 0)
  {
      $no_record_obj = new stdClass();
      $no_record_obj->title = "No Record Found !";
      $no_record_obj->no_record = 1;
      $no_record_obj->ExtendedNode = 1;
      $res[] = $no_record_obj;
  }
  
  echo json_encode($res);  
  //echo "<pre";
  //var_dump($_REQUEST);  
  //echo $wpdb->last_query;
  wp_die(); 
}


add_action('wp_ajax_nopriv_profile_password_change', 'ajax_profile_password_change');
add_action('wp_ajax_profile_password_change', 'ajax_profile_password_change');
function ajax_profile_password_change() {
    
    if(get_current_user_id() > 0)
    {
        if(isset($_POST['newpassword']) && isset($_POST['confirmpassword']) && $_POST['newpassword'] == $_POST['confirmpassword'])
        {
            global $current_user;            
            $username = $current_user->data->user_login;
            //wp_set_password($_POST['newpassword'], get_current_user_id());                       
            global $wpdb;
            $hash = wp_hash_password( $_POST['newpassword'] );
            $wpdb->update($wpdb->users, array('user_pass' => $hash), array('ID' => get_current_user_id()) );            
            echo json_encode(array("message"=>"Password Changed Successfully!"));
        }else{
            echo json_encode(array("message"=>"Invalid Password Given"));
        }
    }else{
        echo json_encode(array("message"=>"Invalid Request"));
    }
    wp_die();
}


add_action('wp_ajax_nopriv_add_user_library_collection_resource', 'ajax_add_user_library_collection_resource');
add_action('wp_ajax_add_user_library_collection_resource', 'ajax_add_user_library_collection_resource');

function ajax_add_user_library_collection_resource() {
    
    ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    global $wpdb;
    $collection_resources = $_POST["collection_resources"];
    
    $hit_node = $_POST["hit_node"];
    $new_node = $_POST["new_node"];
    //$displayseqno_selected = $hit_node["data"]["displayseqno"];
    $collectionid = $collection_resources["key"];
    $resourceid = isset($new_node["resourceid"]) ? $new_node["resourceid"] : 0;        
    
    $source = $_POST["source"];
    
    $collection_resources_arr = $collection_resources["children"];
    
        
    if($source === "My Collections" || $source === "ExpandableNode")
    {        
        $query_rcd = "DELETE FROM collectionelements WHERE collectionid=$collectionid";                        
        $wpdb->query($query_rcd);            
        
        $cntr = 0;
        foreach ($collection_resources_arr as $col_rs)
        {        
            $rid = isset($col_rs["key"]) ? $col_rs["key"] : 0;
            if(isset($col_rs["title"]) && isset($new_node["title"]) && $col_rs["title"] == $new_node["title"])
            {
                $rid = $resourceid;                            
            }            
            
            $rs_row = $wpdb->get_row("select * from resources where resourceid = $rid");
            if($rs_row){
                $wpdb->update('resources', ['indexrequired'=>'T'], ['resourceid'=>$rid]);
                $wpdb->insert( 'collectionelements', array(
                              "collectionid"=> $collectionid , 
                              'resourceid' => $rid,
                              'displayseqno' => $cntr
                         ));
            }
                                     
            $cntr++;
        }        
    }
    
    if($source === "My Groups")
    {   
        
        /*
        $group_id = $collection_resources["key"];
        $query_rcd = "DELETE FROM group_resources                       
                      left join resources as r on gr.resourceid = r.resourceid            
                       where groupid = $group_id                            
                            and r.contributorid in (
                                                        SELECT user_id
                                                                FROM cur_bp_groups_members gm														
                                                        where 
                                                                gm.group_id = $group_id
                                                                and gm.is_banned = 0
                                                                and gm.is_confirmed = 1
                                                   )";
        $wpdb->query($query_rcd);    
        
        $cntr = 0;
        foreach ($collection_resources_arr as $col_rs)
        {        
            $rid = $col_rs["key"];
            if($col_rs["title"] == $new_node["title"])
            {
                $rid = $resourceid;            
            }

            $wpdb->insert('group_resources', array(
                              'groupid'=> $group_id, 
                              'resourceid' => $rid
                     ));

            $cntr++;
        }
        */
        
        $group_id = $collection_resources["key"];        
        $resourceid = $new_node["resourceid"];
        
        $query_rcd = "DELETE FROM group_resources where groupid = $group_id and resourceid = $resourceid";                               
        $wpdb->query($query_rcd);    
        
        $wpdb->insert('group_resources', array(
                              'groupid'=> $group_id, 
                              'resourceid' => $resourceid
                     ));
        
    }
    
    wp_die();
}

/*
function ajax_add_user_library_collection_resource() {
    
  global $wpdb;
  
  $collectionid = $_REQUEST["collectionid"];
  $resourceid = $_REQUEST["resourceid"];
  $selected_resource = $_REQUEST["selected_resource"];
  $displayseqno_selected = $_REQUEST["displayseqno_selected"];
  
  
  if( is_array($_REQUEST["new_group_resource"]) && count($_REQUEST["new_group_resource"]) > 0)
  {
      $new_group_resource = $_REQUEST["new_group_resource"];      
      
      $sql = "select count(groupid) from group_resources where groupid={$new_group_resource["groupid"]} and resourceid={$new_group_resource["RID"]}";  
      $row_count = $wpdb->get_var($sql);
      if($row_count == 0)
      {
        $wpdb->insert('group_resources', array(
                            'groupid'=> $new_group_resource["groupid"], 
                            'resourceid' => $new_group_resource["RID"]
                   ));
      }
  }  
  
  if(strlen($collectionid) > 0)
  {
        $sql = "select * from collectionelements where collectionid=$collectionid and resourceid=$resourceid";  
        $coll_res_rcd = $wpdb->get_results($sql);

        if(count($coll_res_rcd) == 0)
        {
          $displayseqno = 0;


          if(strlen($selected_resource) > 0 )
          {

              $query = "UPDATE collectionelements SET displayseqno = displayseqno + 1 WHERE displayseqno > $displayseqno_selected and collectionid=$collectionid order by displayseqno asc";
              $wpdb->query($query);
              $displayseqno = $displayseqno_selected + 1;
          }
          else
          {
              $sql_coll = "select * from collectionelements where collectionid=$collectionid order by displayseqno asc";  
              $coll_rcd = $wpdb->get_results($sql_coll);
              if(count( $coll_rcd ) > 0)
              {
                  $displayseqno = ($coll_rcd[count( $coll_rcd )-1]->displayseqno + 1);
              }    
          }

          $wpdb->insert( 'collectionelements', array(
                              "collectionid"=> $collectionid , 
                              'resourceid' => $resourceid,
                              'displayseqno' => $displayseqno
                   ));

          $current_date = date("Y-m-d H:i:s");
          $query_coll_update = "UPDATE resources SET lasteditdate =  '$current_date' WHERE resourceid = $collectionid";
          $wpdb->query($query_coll_update);

        } 
        else {
              //== update ahead records
              $query = "UPDATE collectionelements SET displayseqno = displayseqno + 1 WHERE displayseqno > $displayseqno_selected and collectionid=$collectionid and resourceid!=$resourceid order by displayseqno asc";
              $wpdb->query($query);        
              $displayseqno = $displayseqno_selected + 1;

              //== update current record
              $query_rcd = "UPDATE collectionelements SET displayseqno = $displayseqno WHERE collectionid=$collectionid and resourceid=$resourceid";
              $wpdb->query($query_rcd);

        }
  }
                    
  wp_die(); 
}
*/
function curr_bpfr_filtering_activity( $retval ) {
    global $bp;
    if(isset( $_GET['act'] ) && $_GET['act'] == 'rs' )
    {                
        $retval['object'] = 'resources';
    }
    return $retval;    
}

add_filter( 'bp_before_has_activities_parse_args', 'curr_bpfr_filtering_activity' );



add_action('wp_ajax_nopriv_save_statements', 'ajax_save_statements');
add_action('wp_ajax_save_statements', 'ajax_save_statements');

function ajax_save_statements() {
    
  global $wpdb;
  
  $res = new CurrikiResources();  
  $sate_ids = $_POST["sate_ids"];  
  $sate_ids = is_array($sate_ids) ? $sate_ids : array();  
  $sate_ids = array_unique($sate_ids);
  $rid = $_POST['rid'];
  
  
  if( intval($rid) > 0)
  {
    $query_del = "DELETE FROM resource_statements WHERE resourceid IN ($rid)";
    $wpdb->query($query_del);
  }
  
  foreach ($sate_ids as $sid)
  {      
     $res->saveResourceStatement((int)$rid, (int)$sid );      
  }
  wp_die(); 
}


add_action('wp_ajax_nopriv_load_statements', 'ajax_load_statements');
add_action('wp_ajax_load_statements', 'ajax_load_statements');

function ajax_load_statements() {    
    $res = new CurrikiResources();    
    $resource = $res->getResourceById((int) $_POST['rid'], rtrim($_POST['pageurl'], '/'), true);
    echo json_encode($resource);
    wp_die(); 
}

// Function to get the client IP address
function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';

    return $ipaddress;
}

add_action('wp_ajax_nopriv_resource_content_link_log', 'ajax_resource_content_link_log');
add_action('wp_ajax_resource_content_link_log', 'ajax_resource_content_link_log');

function ajax_resource_content_link_log() {

    global $wpdb;

    if ( isset( $_POST['nonce'] ) &&  isset( $_POST['resource_id'] ) && wp_verify_nonce( $_POST['nonce'], 'resource_content_link_log_' . $_POST['resource_id'] ) ) {

        $logEntry = array (
            "resourceid"=> $_POST['resource_id'],
            'url' => $_POST['url']
        );

        $userId = get_current_user_id();
        if ($userId)
            $logEntry["userid"] = $userId;

        $ipAddress = get_client_ip();
        if ($ipAddress != "UNKNOWN")
            $logEntry["ipv4"] = ip2long($ipAddress);

        global $wpdb;
        $wpdb->insert( 'resource_content_link_logs', $logEntry);
    }
    exit();
}

add_action('wp_ajax_nopriv_delete_resource_collection', 'ajax_delete_resource_collection');
add_action('wp_ajax_delete_resource_collection', 'ajax_delete_resource_collection');
function ajax_delete_resource_collection() {    
    global $wpdb;
    $r = $wpdb->delete( 'collectionelements', array( 'resourceid' => $_POST['resourceid'] , 'collectionid' => $_POST['collectionid'] ), array( '%d' , '%d' ) );
    if($r)    
    {
        echo "1";
    }  else {
        echo "0";
    }
    wp_die(); 
}


add_action('wp_ajax_nopriv_cur_widget_search_input_presist', 'ajax_cur_widget_search_input_presist');
add_action('wp_ajax_cur_widget_search_input_presist', 'ajax_cur_widget_search_input_presist');
function ajax_cur_widget_search_input_presist() {    
    global $wpdb;
    //echo $_POST["searchInputWdg"];
    $_SESSION["wdg_sr_subject"] = $_POST["subject"];
    $_SESSION["wdg_sr_subjectarea"] = $_POST["subjectarea"];
    $_SESSION["wdg_sr_education_level"] = $_POST["education_level"];
    $_SESSION["wdg_sr_type"] = $_POST["type"];    
    $_SESSION["wdg_sr_rating"] = $_POST["rating"];    
    //echo "SESSION DATA = ";
    //var_dump( $_POST["rating"] );
    wp_die();    
}




function curr_post_save_activity( $post_id ) {

    $post =  get_post( $post_id );    
    
    
    if($post->post_status == "publish" && $post->post_type == "post")
    {
        $post_content =  strip_tags($post->post_content);
        $post_content = strlen( $post_content ) > 550 ? (substr($post_content,0,550)." ......") : ($post_content);
        $post_content = 'Blog Update:'.' '. $post_content;
                
        $post_title = $post->post_title;
        
        $bpActAction = '<a href="'. site_url().'/'.$post->post_name.'">' . $post_title . '</a>';                
        $component = "postactivity";        
        $bpActType = "postsave";
         $activity_id = bp_activity_add(array(
            'action' => $bpActAction,
            'content' => $post_content,
            'component' => $component,
            'type' => $bpActType,
        ));
    }        
}
add_action( 'save_post', 'curr_post_save_activity' );



function new_topic_redirect_to( $redirect_url = '', $redirect_to = '', $topic_id = 0 ) {                
                global $bp;                               
		if ( bp_is_group() ) {                    
                    $redirect_url = bp_get_root_domain(). "/" .bp_get_groups_root_slug() . "/". $bp->groups->current_group->slug . "/forum";
                    wp_redirect($redirect_url);
                    // Redirect back to new topic
                    wp_safe_redirect( $redirect_url );
                    // For good measure
                    exit();
		}                      		
	}
add_filter( 'bbp_new_topic_redirect_to', 'new_topic_redirect_to' , 10, 3 );        




// Create hook funktion that alters the sql. It takes two args. $where (the sql string) and $wp_query (which is the actual query and holds the args for the function, $beinDate and $endDate).
function cur_posts_where_hook( $where = '', $wp_query )
{    
    
            global $wpdb , $bp;
            
            $src = null;
            
            //var_dump( $bp->unfiltered_uri );
            
            if( is_array($bp->unfiltered_uri) && in_array('forum', $bp->unfiltered_uri) && !in_array('topic', $bp->unfiltered_uri))
            {
                $src = "forum";
            }
            if( is_array($bp->unfiltered_uri) && in_array('forum', $bp->unfiltered_uri) && in_array('topic', $bp->unfiltered_uri))
            {
                $src = "topic";
            }
                        
            // ================= FILTER FORUM TOPIC'S REPLIES ===============
            if($src == "topic" && isset($wp_query->query['post_type']) && ( is_array($wp_query->query['post_type']) && in_array("reply", $wp_query->query['post_type']) && in_array("topic", $wp_query->query['post_type']) ) )
            {                                
                $post_parent = $wp_query->query_vars['post_parent'];
                if(isset($post_parent) && $post_parent > 0)
                {                                                                     
                    $where = "AND {$wpdb->prefix}posts.post_parent = $post_parent";                    
                }
            } 
            
            // ================= FILTER FORUM TOPICS ===============                                                
            if($src == "forum" && isset($wp_query->query['post_type']) &&  ( isset($wp_query->query['post_type']) && !is_array($wp_query->query['post_type']) && $wp_query->query['post_type'] =="topic") )
            {                                                     
                $post_parent = $wp_query->query_vars['post_parent'];                                
                if(isset($post_parent) && $post_parent > 0)
                {                    
                    $where = "AND {$wpdb->prefix}posts.post_parent = $post_parent AND {$wpdb->prefix}posts.post_status = 'publish' AND {$wpdb->prefix}posts.post_type = 'topic'";                                        
                }                
            }
            
	return $where;
}
add_filter( 'posts_where', 'cur_posts_where_hook', 10, 2 );


if(isset($_GET['allq']) and $_GET['allq'] == 1)
{
    add_action( 'shutdown', function(){
        foreach( $GLOBALS['wp_actions'] as $action => $count )
        {
            var_dump($count );
            echo "  ***********   ";
            var_dump($action );
            echo " <br /> =====================================================  <br /> ";
        }
        wp_die();
    });
    
    
/*    
    function cur_bp_pre_user_query( $instance ) 
    {
    
        echo "<pre>************";
        var_dump( $instance );
    }
    add_action( 'pre_user_query', 'cur_bp_pre_user_query', 10, 1 );
 * 
 */
     
}



    
    
function cur_bp_pre_user_query( $vars = '', $a = '') {
    if(!is_admin() && bp_current_component() == 'following')
    {                        
        global $wpdb , $members_template;        
                         
        $u_ids = explode(',', $vars->query_vars_raw["include"]);
        foreach($u_ids as $uid)
        {
            $u_rcd_count = $wpdb->get_var("SELECT count(u.user_id) id FROM {$wpdb->prefix}bp_activity AS u WHERE u.component = 'members' AND u.type = 'last_activity' AND u.user_id IN ($uid)");                                
            if($u_rcd_count == 0)
            {
                $as_tbl = $wpdb->prefix."bp_activity";
                $wpdb->insert( $as_tbl , array(
                    "user_id"=> $uid , 
                    'component' => "members",
                    'type' => "last_activity",
                    "date_recorded" => date("Y-m-d H:i:s")
                 ));
            }
        }
                     
        $vars->uid_clauses['select'] = "SELECT {$wpdb->prefix}users.ID as id FROM {$wpdb->prefix}bp_activity u , {$wpdb->prefix}users";            
        $vars->uid_clauses['where'] = $vars->uid_clauses['where']. " AND {$wpdb->prefix}users.ID IN ({$vars->query_vars_raw["include"]}) group by {$wpdb->prefix}users.ID";
        $vars->uid_clauses['limit'] = "";                                        
        
    }
    return $vars;
  }
 add_filter( 'bp_pre_user_query', 'cur_bp_pre_user_query' );     
 

function curr_contacts_bp_get_members_pagination_count() {
        
/*
        if ( empty( $members_template->type ) )
                $members_template->type = '';
*/
        if(!is_admin() && bp_current_component() == 'following')
        {
            global $members_template;
            $start_num = intval( ( $members_template->pag_page - 1 ) * $members_template->pag_num ) + 1;
            $from_num  = bp_core_number_format( $start_num );
            $to_num    = bp_core_number_format( ( $start_num + ( $members_template->pag_num - 1 ) > $members_template->member_count ) ? $members_template->member_count : $start_num + ( $members_template->pag_num - 1 ) );
            $total     = bp_core_number_format( $members_template->member_count );
                //echo "<pre>";
                //var_dump($members_template->member_count);
                /*
                if ( 'active' == $members_template->type )
                        $pag = sprintf( _n( 'Viewing 1 active member', 'Viewing %1$s - %2$s of %3$s active members', $members_template->member_count, 'buddypress' ), $from_num, $to_num, $total );
                else if ( 'popular' == $members_template->type )
                        $pag = sprintf( _n( 'Viewing 1 member with friends', 'Viewing %1$s - %2$s of %3$s members with friends', $members_template->total_member_count, 'buddypress' ), $from_num, $to_num, $total );
                else if ( 'online' == $members_template->type )
                        $pag = sprintf( _n( 'Viewing 1 online member', 'Viewing %1$s - %2$s of %3$s online members', $members_template->total_member_count, 'buddypress' ), $from_num, $to_num, $total );
                else
                        $pag = sprintf( _n( 'Viewing 1 member', 'Viewing %1$s - %2$s of %3$s members', $members_template->total_member_count, 'buddypress' ), $from_num, $to_num, $total );     
                */
            return __("Viewing","curriki")." $total ".__("member(s)","curriki");
        }
}
add_filter( 'bp_members_pagination_count', "curr_contacts_bp_get_members_pagination_count" );


function cur_sm_addsitemap($gsg)
{ 
    
    //============= [start] Build Sitemap Index for Resouces =================
    global $wpdb;
    $resources_query = "select count(resourceid)
            from resources
            where active = 'T'
            and ifnull(access, 'public') <> 'private'
            and title <> 'Favorites' order by resourceid asc";        
    $resources_count = $wpdb->get_var($resources_query);            
    //var_dump( $resources_count );    
    $items_per_page = 10000;
    $total_pages = (int) ceil( $resources_count / $items_per_page );
    //var_dump($total_pages);
    
    $page = 1;        
    // build query
    for($i=0; $i<$total_pages; $i++)
    {
        $page_no = $i+1;
        $offset = ($page_no - 1) * $items_per_page + 1;
        
        $from = $offset;
        $to = $from + ($items_per_page-1);
        //echo "( $page_no [from:$from - to:$to] )";        
        $gsg->AddSitemap("resources-$from-$to", null, time());
    }
    //============= [end] Build Sitemap Index for Resouces =================
    

    //============= [start] Build Sitemap Index for Groups =================
    $groups_query = "select count(id) from {$wpdb->prefix}bp_groups where status = 'public' order by id asc";
    $groups_count = $wpdb->get_var($groups_query);
    //var_dump( $groups_count );
    $items_per_page_g = 10000;
    $total_pages_g = (int) ceil( $groups_count / $items_per_page_g );
    //var_dump($total_pages_g);
    
    $page_g = 1;        
    // build query
    for($i=0; $i<$total_pages_g; $i++)
    {
        $page_no_g = $i+1;
        $offset_g = ($page_no_g - 1) * $items_per_page_g + 1;
        
        $from_g = $offset_g;
        $to_g = $from_g + ($items_per_page_g-1);
        //echo "( $page_no_g [from_g:$from_g - to:$to_g] )";        
        $gsg->AddSitemap("groups-$from_g-$to_g", null, time());
    }
    //============= [end] Build Sitemap Index for Groups =================   
}
add_action("sm_build_index", "cur_sm_addsitemap" , 10, 1);


function cur_sm_build_content($gsg, $type, $params)
{
    
    $params = explode("-", $params);
    $limit = 10000;
    $offset = ((int)$params[0] - 1);
   
    if($type == "resources" && count($params) > 0)
    {
        //var_dump($params);
        global $wpdb;
        $resources_query = "SELECT resourceid,pageurl
                FROM resources
                WHERE active = 'T'
                AND ifnull(access, 'public') <> 'private'
                AND title <> 'Favorites' ORDER BY resourceid asc LIMIT $limit OFFSET $offset";        
        $resources = $wpdb->get_results($resources_query, OBJECT);          
        //var_dump($resources);        
        foreach ($resources as $resource)
        {
            $gsg->AddUrl( site_url()."/oer/". urlencode($resource->pageurl) ,time(),"daily",0.5);
        }
    }
    
    if($type == "groups" && count($params) > 0)
    {
        //var_dump($params);
        global $wpdb;
        $groups_query = "SELECT slug FROM {$wpdb->prefix}bp_groups WHERE status = 'public' ORDER BY id asc LIMIT $limit OFFSET $offset";
        $groups = $wpdb->get_results($groups_query, OBJECT);          
        //var_dump($groups);        
        foreach ($groups as $group)
        {
            $gsg->AddUrl( site_url()."/groups/".urlencode($group->slug)."/" ,time(),"daily",0.5);
        }
    }
}
add_action("sm_build_content", "cur_sm_build_content" , 10, 3);



function curr_get_avatar( $avatar, $id_or_email, $size ) {
    $id = $id = (int) $id_or_email;
    if($id > 0)
    {
        //$avatar = '<img src="http://www.google.comxx'.$id.'" alt="' . get_the_author() . '" width="' . $size . 'px" height="' . $size . 'px" />';
        global $wpdb;                                 
        $q_userinfo = "select * from users where userid = '".$id."'";        
        $userinfo = $wpdb->get_row($q_userinfo);                                                                               
        if(!isset($userinfo)){
            $profile = get_user_meta($id,"profile",true);    
            $profile = isset($profile) ? json_decode($profile) : null; 
            $gender_img = isset($profile) ? "-".$profile->gender : "";
            $avatar = '<img alt="" class="avatar user-'.  $id.'-avatar avatar-'.'user-icon'.' photo" src="'.get_stylesheet_directory_uri().'/images/user-icon-sample'.$gender_img.'.png">';
        }elseif( !isset($userinfo->uniqueavatarfile) ){
            $profile = get_user_meta($id,"profile",true);    
            $profile = isset($profile) ? json_decode($profile) : null; 
            $gender_img = isset($profile) ? "-".$profile->gender : "";
            $avatar = '<img alt="" class="avatar user-'.  $id.'-avatar avatar-'.'user-icon'.' photo" src="'.get_stylesheet_directory_uri().'/images/user-icon-sample'.$gender_img.'.png">';
        }else{
            $avatar = '<img alt="" class="avatar user-'.$userinfo->uniqueavatarfile.'-avatar avatar-'.$userinfo->uniqueavatarfile.' photo" src="https://' . DBI_AWS_S3_CDN_URL . '//avatars/'.$userinfo->uniqueavatarfile.'">';
        }
    }
    return $avatar;
}
add_filter( 'get_avatar', 'curr_get_avatar', 10, 3 );


add_action('genesis_before','cur_search_widget_footer_setting');
function cur_search_widget_footer_setting(){
    global $post,$bp;        
    if(!is_admin() && $post->post_name == "search-widget-page" || $bp->unfiltered_uri[0] == "oer-widget")
    {
        remove_action( 'genesis_before_footer', 'genesis_footer_widget_areas' );
        remove_action('genesis_footer', 'genesis_do_footer');
        remove_action('genesis_footer', 'genesis_footer_markup_open', 5);
        remove_action('genesis_footer', 'genesis_footer_markup_close', 15);
    }
    
    if(!is_admin() && $bp->unfiltered_uri[0] == "oer-widget")
    {
        //remove header
        remove_action( 'genesis_header', 'genesis_header_markup_open', 5 );
        remove_action( 'genesis_header', 'genesis_do_header' );
        remove_action( 'genesis_header', 'genesis_header_markup_close', 15 );
        //remove navigation
        remove_action( 'genesis_after_header', 'genesis_do_nav' );
        remove_action( 'genesis_after_header', 'genesis_do_subnav' );


    }
}

add_filter( 'body_class', 'curr_body_class' );
function curr_body_class( $classes ) {
    global $post,$bp;        
    if(!is_admin() && $bp->unfiltered_uri[0] == "oer-widget")
    {
        $classes[] = 'oer-widget-page';        
    }
    return $classes;
}

function curr_dequeue_script() {
   //wp_dequeue_script( 'jquery-ui-core' );
    global $post,$bp;
    if(!is_admin() && $bp->unfiltered_uri[0] == "search-widget-page")
    {
        wp_dequeue_script( 'curriki-custom-script-alpha' );
        wp_dequeue_script( 'ext-base-js' );
        wp_dequeue_script( 'curriki-ext-js' );
        wp_dequeue_script( 'xwiki-js' );
    }
}
add_action( 'wp_print_scripts', 'curr_dequeue_script', 100 );

function curr_dequeue_style() {
   //wp_dequeue_script( 'jquery-ui-core' );
    global $post,$bp;
    if(!is_admin() && $bp->unfiltered_uri[0] == "search-widget-page") 
    {
        wp_dequeue_style( 'curriki-new-styles' );
        wp_dequeue_style( 'curriki-custom-style-alpha' );
        wp_dequeue_style( 'gconnect-bp' );
        wp_dequeue_style( 'bbp-default' );
        wp_dequeue_style( 'misc' );
        wp_dequeue_style( 'tablepress-default' );
    }
}
add_action( 'wp_print_styles', 'curr_dequeue_style', 100 );

add_action( 'genesis_meta', 'curr_meta_tags' );
function curr_meta_tags() {
        
    global $post,$bp;        
    if(is_array($bp->unfiltered_uri)  && $bp->unfiltered_uri[0] == "oer")
    {            
         if (isset($_GET['rid']) || isset($_GET['pageurl'])) {
             
            global $resourceUserGlobal;             
            //$res = new CurrikiResources();
            //$resourceUser = $res->getResourceUserById((int) $_GET['rid'], rtrim($_GET['pageurl'], '/'));                
            $resourceUser = $resourceUserGlobal;
            echo '<meta name="description" content="'. strip_tags(htmlentities(trim($resourceUser["description"]))).'" />';
            echo '<meta name="keywords" content="'. htmlentities($resourceUser["keywords"]). ( strlen($resourceUser["keywords"]) > 0 ? ', ' : '' ) .htmlentities($resourceUser["generatedkeywords"]) . '" />';
     
            
            $current_language = "eng";
            $current_language_slug = "";
            if( defined('ICL_LANGUAGE_CODE') )
            {
                $current_language = cur_get_current_language(ICL_LANGUAGE_CODE); 
                $current_language_slug = ( ICL_LANGUAGE_CODE == "en" ? "":"/".ICL_LANGUAGE_CODE );
            }
  
            if( isset($_GET["pageurl"]) && strlen($_GET["pageurl"]) > 0 )
            {
                echo '<link rel="canonical" href="'.site_url().$current_language_slug.'/oer/'.$_GET['pageurl'].'" />';
            }
            if( isset($_GET["rid"]) && strlen($_GET["rid"]) > 0 )
            {
                echo '<link rel="canonical" href="'.site_url().$current_language_slug.'/oer/'.$resourceUser["pageurl"].'" />';
            }                        
          }            
    }    
}



function cur_resource_title(){
    global $post,$bp;       
//    var_dump($bp->unfiltered_uri);
//    die();
    if(is_array($bp->unfiltered_uri) && $bp->unfiltered_uri[0] == "oer")
    {                
        if (isset($_GET['rid']) || isset($_GET['pageurl'])) {            
            
            global $resourceUserGlobal;                                    
            //$res = new CurrikiResources();                
            //$resourceUser = $res->getResourceUserById((int) $_GET['rid'], rtrim($_GET['pageurl'], '/'));            
            $resourceUser = $resourceUserGlobal;
            $title = $resourceUser['title'];   
            $post->post_title = $title;
        }
    }
    
    if(is_array($bp->unfiltered_uri)  && $bp->unfiltered_uri[0] == "groups" && bp_current_component() == "groups")
    {                                   
        if( gettype($bp) === "object" && gettype($bp->groups) === "object" && property_exists($bp->groups, "current_group") && is_object($bp->groups->current_group) && property_exists($post, "post_title") && property_exists($bp->groups->current_group, "name") )
        {            
            $post->post_title = $bp->groups->current_group->name;
        }
    }
    
}
add_filter('genesis_title','cur_resource_title' , 2000);


if(isset($_GET["avatar"]) and $_GET["avatar"]=='1')
{
    /*
    function cur_bp_get_activity_avatar( $avatar, $user, $size, $default, $alt = '' ) {       
        global $bp;
        var_dump( $avatar );die;
    }
    add_filter( 'bp_get_activity_avatar', 'cur_bp_get_activity_avatar');
     * 
     */
}
 

function cur_bp_activity_register_activity_actions() {
        global $bp;

        bp_activity_set_action(
                $bp->activity->id,
                'resource_review_insert',
                __( 'Posted a resource review', 'buddypress' ),
                'bp_activity_format_activity_action_activity_update'
        );

        bp_activity_set_action(
                $bp->activity->id,
                'resource_insert',
                __( 'Posted a resource', 'buddypress' ),
                'bp_activity_format_activity_action_activity_update'
        );
        
        bp_activity_set_action(
                $bp->activity->id,
                'resource_udpate',
                __( 'Updated a resource', 'buddypress' ),
                'bp_activity_format_activity_action_activity_update'
        );
        
        bp_activity_set_action(
                $bp->activity->id,
                'resource_udpate',
                __( 'Updated a resource', 'buddypress' ),
                'bp_activity_format_activity_action_activity_update'
        );
        
        bp_activity_set_action(
                $bp->activity->id,
                'postsave',
                __( 'Updated a blog post', 'buddypress' ),
                'bp_activity_format_activity_action_activity_update'
        );

        do_action( 'bp_activity_register_activity_actions' );

        // Backpat. Don't use this.
        //do_action( 'updates_register_activity_actions' );
}
add_action( 'bp_register_activity_actions', 'cur_bp_activity_register_activity_actions' );



//=== Handel donation modal ============
add_action('wp_ajax_nopriv_dn_modal_handel', 'ajax_dn_modal_handel');
add_action('wp_ajax_dn_modal_handel', 'dn_modal_handel');
function dn_modal_handel() {
    $act = $_POST["act"];
    
    if(!isset( $_SESSION["opendn"] ))
    {
        //echo date("h:i:s");
        $_SESSION["opendn"] = 1;
        $_SESSION["opendntime"] = date("h:i:s");
    }    
    
    
    
    //== seceonds spends
    //echo  strtotime( date("h:i:s") ) - strtotime( $_SESSION["opendntime"] );
    $seceonds_spends = strtotime( date("h:i:s") ) - strtotime( $_SESSION["opendntime"] );
    $minutes_spends = $seceonds_spends / 60;
    
    $output = array(
                    "seceonds_spends" =>$seceonds_spends,
                    "minutes_spends" =>$minutes_spends,
                    "act" =>$act,
                   );
    echo json_encode($output);    
    wp_die();   
}

//=== Handel donation modal close ============
add_action('wp_ajax_nopriv_dn_modal_handel_close', 'ajax_dn_modal_handel_close');
add_action('wp_ajax_dn_modal_handel_close', 'dn_modal_handel_close');

function dn_modal_handel_close() {
    
    if(isset($_SESSION["opendntime"]))
    {
        $act = $_POST["act"];            
        $_SESSION["opendntime"] = date("h:i:s");

        //== seceonds spends
        //echo  strtotime( date("h:i:s") ) - strtotime( $_SESSION["opendntime"] );
        $seceonds_spends = strtotime( date("h:i:s") ) - strtotime( $_SESSION["opendntime"] );
        $minutes_spends = $seceonds_spends / 60;

        $output = array(
                        "seceonds_spends" =>$seceonds_spends,
                        "minutes_spends" =>$minutes_spends,
                        "act" =>$act,
                       );
        echo json_encode($output);    
    }  else {
        $output = array(                        
                        "act" =>"error",
                       );
        echo json_encode($output);    
    }
    wp_die();   
}

add_action('wp_head', 'add_content_to_head');
function add_content_to_head() {
    /*
        <link href="http://www.yoursite.com/apple-touch-icon.png" rel="apple-touch-icon" />
        <link href="http://www.yoursite.com/apple-touch-icon-76x76.png" rel="apple-touch-icon" sizes="76x76" />
        <link href="http://www.yoursite.com/apple-touch-icon-120x120.png" rel="apple-touch-icon" sizes="120x120" />
        <link href="http://www.yoursite.com/apple-touch-icon-152x152.png" rel="apple-touch-icon" sizes="152x152" />
        <link href="http://www.yoursite.com/apple-touch-icon-180x180.png" rel="apple-touch-icon" sizes="180x180" />
        <link href="http://www.yoursite.com/icon-hires.png" rel="icon" sizes="192x192" />
        <link href="http://www.yoursite.com/icon-normal.png" rel="icon" sizes="128x128" /> 
     */
    echo '        
        <link href="'.get_stylesheet_directory_uri().'/images/device-icons/ios/curriki-01_76.png" rel="apple-touch-icon" sizes="76x76" />
        <link href="'.get_stylesheet_directory_uri().'/images/device-icons/ios/curriki-01_120.png" rel="apple-touch-icon" sizes="120x120" />
        <link href="'.get_stylesheet_directory_uri().'/images/device-icons/ios/curriki-01_152.png" rel="apple-touch-icon" sizes="152x152" />
        <link href="'.get_stylesheet_directory_uri().'/images/device-icons/ios/curriki-01_180.png" rel="apple-touch-icon" sizes="180x180" />
        <link href="'.get_stylesheet_directory_uri().'/images/device-icons/android/curriki-01_192.png" rel="icon" sizes="192x192" />
        <link href="'.get_stylesheet_directory_uri().'/images/device-icons/android/curriki-01_128.png" rel="icon" sizes="128x128" />
    ';
}



add_action('bp_activity_add', 'cur_bp_activity_add' , 10 , 1);
function cur_bp_activity_add($args) {
    
    global $bp,$wpdb;   
    $activity_id = $wpdb->insert_id;    
    $activity = new BP_Activity_Activity( $activity_id );	

    $content_val = strtolower($activity->content);
    $action_val = strtolower($activity->action);
    $cnsr_arr  = $wpdb->get_results("SELECT phrase FROM censorphrases",ARRAY_N);
    $censorphrases  = count($cnsr_arr) > 0 ? $cnsr_arr[0] : array();
    if( striposa($content_val, $censorphrases, 1) || striposa($action_val, $censorphrases, 1)){
        $activity->is_spam = 1;
    }
    if ( ! $activity->save() ) {
            return false;
    }
    return $activity->id;
}



function striposa($haystack, $needles=array(), $offset=0) {
    $chr = array();
    if(is_array($needles))
      foreach($needles as $needle) {            
        if (stripos(strtolower($haystack), $needle) !== false) {                            
            $chr[$needle] = 1;
        }
      }
    if(empty($chr)) 
        return false;
    else
        return true;    
}


add_action('wp_ajax_nopriv_get_group_record', 'ajax_get_group_record');
add_action('wp_ajax_get_group_record', 'get_group_record');

function get_group_record() {    
    global $wpdb;    
    $groupid = $_POST["groupid"];
    $query_rcd = "SELECT slug FROM cur_bp_groups where id = $groupid";
    $r = $wpdb->get_row($query_rcd,OBJECT);
    echo json_encode($r);
    wp_die();    
}

include("custom-functions-a.php");