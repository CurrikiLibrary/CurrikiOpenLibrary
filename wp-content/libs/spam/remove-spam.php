<?php
/* 
 * 
 */

require_once( '../../../wp-load.php' );

// get the rejected resources
$size = isset($_REQUEST['size']) ? $_REQUEST['size'] : 1;
$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'resources';
$spamResources = new SpamCron($size, $type);
$spamResources->delete();

 /**
* SpamCron
* 
* 
* @author     Ali Mehdi <ali.curriki@nxvt.com>
*/
class SpamCron {
    
    
    private $size;
    private $type;
    private $approvalStatus;
    private $approvalStatusDate;
    private $spam;
    
    public function __construct($size, $type) {
        $this->size = $size;
        $this->type = $type;
        $this->approvalStatus = 'rejected';
        $this->approvalStatusDate = date('Y-m-d H:i:s');;
        $this->spam = 'T';
    }
    
    
    
    /**
    * delete
    * 
    * Delete Rejected Resources
    *
    * @return string response of deleted 
    */
    public function delete(){
        
        global $wpdb;

        switch ($this->type) {
            case 'resources':
                $return = $wpdb->get_results(
                        $wpdb->prepare (
                            "SELECT * FROM resources where approvalStatus = %s and remove = 'T' and indexrequired = 'F' LIMIT %d",
                            $this->approvalStatus, $this->size
                        )
                    );
                
                foreach($return as $rid){
                    if($rid->spamUser == 'T'){ // if user to be spammed
                        self::spamUserAndAssociatedResources($rid->resourceid, $this->approvalStatus, $this->approvalStatusDate);

                    } 
                }
                
                $deleted = $wpdb->query(
                        $wpdb->prepare (
                            "DELETE FROM resources where approvalStatus = %s and remove = 'T' and indexrequired = 'F' LIMIT %d",
                            $this->approvalStatus, $this->size
                        )
                    );

                break;
            case 'groups':
                $return = $wpdb->get_results(
                        $wpdb->prepare (
                            "SELECT * FROM groups where spam = %s and remove = 'T' and indexrequired = 'F' LIMIT %d",
                            $this->spam, $this->size
                        )
                    );
                
                self::spamGroupResources($return);
                foreach($return as $group){
                    if($group->spamCreator == 'T'){ // if user to be spammed
                        self::spamGroupCreators($group->groupid);

                    } 
                    $deleted = $wpdb->query(
                        $wpdb->prepare (
                            "DELETE FROM groups where url = %s",
                            $group->url
                        )
                    );
                    
                    $deleted = $wpdb->query(
                        $wpdb->prepare (
                            "DELETE FROM {$wpdb->prefix}bp_groups where slug = %s",
                            $group->url
                        )
                    );
                }


                break;
            case 'members':
//                file_get_contents("https://www.curriki.org/cron/synchCron.php?upload=true&type=members&limit=100&test=true");
//                file_get_contents("https://www.curriki.org/cron/synchCron.php?upload=true&type=resources&limit=30&test=true");
                $return = $wpdb->get_results(
                        $wpdb->prepare (
                            "SELECT * FROM users where spam = %s and indexrequired = 'F' LIMIT %d",
                            $this->spam, $this->size
                        )
                    );
                

              
                foreach($return as $user){
                    
                    $resources_exist = $wpdb->get_results(
                        $wpdb->prepare (
                            "SELECT * FROM resources where (contributorid = %d or lasteditorid = %d) and (remove = 'F' or spam = 'F')",
                            $user->userid, $user->userid
                        )
                    );
                    
                    if(count($resources_exist) > 0){
                        
                        $wpdb->query(
                            $wpdb->prepare (
                                "UPDATE resources SET indexrequired='T', remove = 'T', spam = 'T' where contributorid = %d or lasteditorid = %d ",
                                $user->userid, $user->userid
                            )
                        );
                        
                        
                    } else {
                        
                        // deleting resources belonging to user
                        $deleted = $wpdb->query(
                            $wpdb->prepare (
                                "DELETE FROM resources where (contributorid = %d or lasteditorid = %d) and remove = 'T' and indexrequired = 'F'",
                                $user->userid, $user->userid
                            )
                        );

                        // deleting activity belonging to user
                        $deleted = $wpdb->query(
                            $wpdb->prepare (
                                "DELETE FROM {$wpdb->prefix}bp_activity where user_id = %d",
                                $user->userid
                            )
                        );

                        // Deleting user
                        $deleted = $wpdb->query(
                            $wpdb->prepare (
                                "DELETE FROM users where userid = %d",
                                $user->userid
                            )
                        );

                        $deleted = $wpdb->query(
                            $wpdb->prepare (
                                "DELETE FROM {$wpdb->prefix}users where ID = %d",
                                $user->userid
                            )
                        );

                    }
                    
                    
                }


                break;

            default:
                break;
        }
        
        echo "<pre>";
            print_r($return);
            echo "</pre>";
            echo "<br />";
            echo "<br />";
            echo "<br />";
            var_dump($deleted);
            echo '<meta http-equiv="Refresh" content="0">';
    }
    
    /**
    * spamUserAndAssociatedResources
    *
    * Spam resources and also user belonged to those resources
    *
    *
    * @param integer $resourceid resourceid
    * @return void
    */
    public static function spamUserAndAssociatedResources($resourceid, $approvalStatus, $approvalStatusDate){
        global $wpdb;
        
        $resource = $wpdb->get_row( 'SELECT resourceid, contributorid FROM resources WHERE resourceid =  '.$resourceid.' ');
                        
        if($resource){
            $is_admin = user_can( $resource->contributorid, 'manage_options' );

            if(!$is_admin){
                //spam the contributor
                $wpdb->query( 
                    $wpdb->prepare( 
                        "UPDATE resources
                         SET `approvalStatus` = %s, `indexrequired`='T', `remove`='T', `active` = 'F', `approvalStatusDate` = %s
                         WHERE contributorid = %d and remove <> 'T'
                         ",
                         $approvalStatus, $approvalStatusDate, $resource->contributorid
                    )
                );

                $wpdb->update( $wpdb->prefix.'users', 
                    ['user_status'=>1],
                    ['ID'=>$resource->contributorid],
                    array( '%s' ),
                    array( '%d' ) );

                $wpdb->update( 'users', 
                    ['spam'=>'T','indexrequired'=>'T', 'remove'=>'T'],
                    ['userid'=>$resource->contributorid],
                    array( '%s' ),
                    array( '%d' ) );

                $msg = 'User and resources are spammed';
            } else {
                $msg = 'Admin Cannot be spammed';
            }
        }
    }
    
    
    /**
    * spamGroupCreators
    *
    * Makes the spam group creators
    *
    *
    * @param array $groupid Array of groupids need to be spammed
    * @return void
    */
    public static function spamGroupCreators($groupid){
        global $wpdb; 
        
        
        

        $wpdb->query( 
            $wpdb->prepare( 
                "UPDATE {$wpdb->prefix}users u
                     SET u.`user_status` = 1
                     WHERE u.ID IN (SELECT g.creatorid FROM groups g WHERE g.groupid = %d)
                     ", $groupid
            )
        );
        

        $wpdb->query( 
            $wpdb->prepare( 
                "UPDATE users u
                     SET u.`spam` = 'T', u.`remove` = 'T', u.`indexrequired` = 'T'
                     WHERE u.userid IN (SELECT g.creatorid FROM groups g WHERE g.groupid =%d)
                     ", $groupid
            )
        );
    }
    
    /**
    * spamGroupResources
    *
    * Spamming group resources
    *
    *
    * @param string $groups Array of groupids need to be spammed
    * @return void
    */
    public static function spamGroupResources($groups){
        global $wpdb; 
        $gids = array_column($groups, 'groupid');
        
        $total_gids = count($gids);
        
        $placeholders = array_fill(0, $total_gids, '%d'); // group ids must be integers
        $format = implode(', ', $placeholders);
        $query = "SELECT * FROM group_resources WHERE groupid IN( '.$format.' )
                     ";
        
        $group_resources = $wpdb->get_results( 
                $wpdb->prepare( 
                    $query, $gids
                )
            );
        
        foreach($group_resources as $group_resource){
            $children = self::hierarchialChildren($group_resource->resourceid);

            
            foreach($children as $child){
                $childs = $child['children'];
                $childs[] = $group_resource->resourceid;
                
                $resourceids = implode(",", $childs);
                
                $wpdb->query( 
                            $wpdb->prepare( 
                                "UPDATE resources
                                 SET `approvalStatus` = %s, indexrequired = 'T', remove = 'T'
                                 WHERE resourceid IN ($resourceids)
                                 ",
                                 'rejected'
                            )
                        );
            }
        }
        
    }
    /**
    * hierarchialChildren
    *
    * Finding the children of resourceid
    *
    *
    * @param integer $resourceid Resourceid 
    * @return array $children Children of resources
    */
    public static function hierarchialChildren($resourceid) {
        global $wpdb;
        return $children = self::findChildren($resourceid);
        
    }

    /**
    * findChildren
    *
    * Finding the children of resources iteratively
    *
    *
    * @param integer $collectionid Resourceid 
    * @param array $return Array of previous children
    * @param int $count Counter of previous iterations
    * @param array $leafarr Array of leaf resources
    * @param array $temp_arr Temporary array
    * @return array $children Children of resources
    */
    public static function findChildren($collectionid, &$return = array(), &$count = -1, &$leafarr = array(), &$temp_arr = array()) {
        global $wpdb; // this is how you get access to the database
        $temp_parentresourceid = null;


        // Perform queries 
        // First time search from pageurl and next time search from collectionids
        
        $children_res = $wpdb->get_results($wpdb->prepare(
                        "SELECT collectionelements.collectionid as parentresourceid, 
            collectionelements.resourceid as resourceid,
            resources.pageurl as parentpageurl
            FROM collectionelements
            INNER JOIN resources ON collectionelements.collectionid=resources.resourceid
            WHERE collectionelements.collectionid = %d", $collectionid
        ));


        if ($wpdb->num_rows == 0) {
            if (!in_array($collectionid, $leafarr)) {
                $leafarr[] = $collectionid;
            }
        }

        // $c is the counter
        $c = 0;
        $cou = 0;
        foreach ($children_res as $child) {
            // Get Page Views Count
            
            $res_page_views = $wpdb->get_row($wpdb->prepare(
                            "SELECT COUNT(*) as pageviews FROM resourceviews WHERE resourceid = %d", $child->resourceid
            ));

            // Select child resources to get their details
            $child_res_data = $wpdb->get_row($wpdb->prepare(
                            "SELECT * FROM resources 
                                WHERE resourceid = %d
                                ", $child->resourceid
            ));




            // Logic 
            if ($temp_parentresourceid != $child->parentresourceid):
                $count++;
            endif;

            $parentresourceid = $child->parentresourceid;
            $resourceid = $child->resourceid;
            $parentpageurl = $child->parentpageurl;

            $return[$count]['counter'] = $count + 1;

           

            $return[$count]['children'][$c] = $child->resourceid;

            $temp_parentresourceid = $child->parentresourceid;

            // $temp_arr to be loop through resource ids
            $temp_arr[$c++]['resourceid'] = $child->resourceid;
        }
        foreach ($temp_arr as $ar):
            self::findChildren($ar['resourceid'], $return, $count, $leafarr);
        endforeach;

        return $return;
    }
}



//collectionelements