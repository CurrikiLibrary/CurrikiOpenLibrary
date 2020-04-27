<?php
/**
 * init.php
 *
 * After user submits the requests to spam resources / groups
 *
 * @author     Ali Mehdi
 * @version    0.0.1
 */
ob_start();

/**
 * class ModerateTerms
 *
 * Will be spamming resources / groups
 *
 */
class ModerateTerms{
    /**
    * Constructor
    *
    * Initializes and register ajax
    *
    * @return void
    */
    public function __construct(){
        add_action( 'wp_ajax_resource_bulkaction', array( $this, 'resource_bulkaction' ) ); 
        add_action( 'wp_ajax_nopriv_resource_bulkaction', array( $this, 'resource_bulkaction' ) );
        add_action( 'wp_ajax_group_bulkaction', array( $this, 'group_bulkaction' ) ); 
        add_action( 'wp_ajax_nopriv_group_bulkaction', array( $this, 'group_bulkaction' ) );


    }
    /**
    * Action taken on resources
    *
    * @return string
    */
    public function resource_bulkaction() {
        // global wpdb get
        global $wpdb;
        // check user is admin
        $is_admin = self::checkCurrentUserIsAdmin();
        if(!$is_admin){
            echo json_encode(['msg'=>'You are not authorized to perform this action']);
            exit;
        }
        
        
        
        //initializes variables
        $moderates = array();
        $spamuser = false; //default spam user
        $approvalStatusDate = date('Y-m-d H:i:s'); //approvalStatus date is current timestamp
        $approvalStatus = 'pending'; //default status
        
        
        if(!empty($_REQUEST['resourceids'])){  // check if resourcids array is not empty
            $msg = '';
            switch ($_REQUEST['bulkaction']){
                case '-1':
                    $approvalStatus = '';
                    $msg = 'Please select an action';
                    break;
                case 'Pending':
                    $approvalStatus = 'pending';
                    $msg = 'Action Successful. Refreshing page ...';
                    break;
                case 'Approve':
                    $approvalStatus = 'approved';
                    $msg = 'Action Successful. Refreshing page ...';
                    break;
                case 'Reject':
                    if(isset($_REQUEST['spamuser']) && $_REQUEST['spamuser'] == 'true'){
                        $spamuser = true;
                    }
                    $approvalStatus = 'rejected';
                    $msg = 'Action Successful. Refreshing page ...';
                    break;
                default:
                    $approvalStatus = 'pending';
                    $msg = 'Action Successful. Refreshing page ...';
                    break;
            }

            $rids = [];
            foreach($_REQUEST['resourceids'] as $rid){
                $rids[] = addslashes($rid);
            }
            $resourceids = implode(",", $rids);

            $update_data = [
                'approvalStatus'=>$approvalStatus,
                'approvalStatusDate'=>$approvalStatus,
                'indexrequired'=>'T',
                'active'=>'F'
            ];
            if($approvalStatus == 'rejected'){ // if resources to be rejected
                self::spamResources($rids, $approvalStatus, $approvalStatusDate, $spamuser);
            } else if($approvalStatus != ''){ // if resources to be pending, approved
                self::changeApprovalStatusResources($resourceids, $approvalStatus, $approvalStatusDate);
            }
        }
        echo json_encode(['msg'=>$msg]);
        exit;
        

    }
    /**
    * Action taken on resources
    *
    * @return string
    */
    public function resource_bulkaction_old() {
        // global wpdb get
        global $wpdb;
        // check user is admin
        $is_admin = self::checkCurrentUserIsAdmin();
        if(!$is_admin){
            echo json_encode(['msg'=>'You are not authorized to perform this action']);
            exit;
        }
        
        
        
        //initializes variables
        $moderates = array();
        $spamuser = false; //default spam user
        $approvalStatusDate = date('Y-m-d H:i:s'); //approvalStatus date is current timestamp
        $approvalStatus = 'pending'; //default status
        
        
        if(!empty($_REQUEST['resourceids'])){  // check if resourcids array is not empty
            $msg = '';
            switch ($_REQUEST['bulkaction']){
                case '-1':
                    $approvalStatus = '';
                    $msg = 'Please select an action';
                    break;
                case 'Pending':
                    $approvalStatus = 'pending';
                    $msg = 'Action Successful. Refreshing page ...';
                    break;
                case 'Approve':
                    $approvalStatus = 'approved';
                    $msg = 'Action Successful. Refreshing page ...';
                    break;
                case 'Reject':
                    if(isset($_REQUEST['spamuser']) && $_REQUEST['spamuser'] == 'true'){
                        $spamuser = true;
                    }
                    $approvalStatus = 'rejected';
                    $msg = 'Action Successful. Refreshing page ...';
                    break;
                default:
                    $approvalStatus = 'pending';
                    $msg = 'Action Successful. Refreshing page ...';
                    break;
            }

            $rids = [];
            foreach($_REQUEST['resourceids'] as $rid){
                $rids[] = addslashes($rid);
            }
            $resourceids = implode(",", $rids);

            $update_data = [
                'approvalStatus'=>$approvalStatus,
                'approvalStatusDate'=>$approvalStatus,
                'indexrequired'=>'T',
                'active'=>'F'
            ];
            if($approvalStatus == 'rejected'){ // if resources to be rejected

                if($spamuser){ // if user to be spammed
                    self::spamUserAndResources($resourceids, $approvalStatus, $approvalStatusDate);

                } else { // if user not to be spammed
                    self::spamResources($rids, $approvalStatus, $approvalStatusDate);

                }


            } else if($approvalStatus != ''){ // if resources to be pending, approved
                self::changeApprovalStatusResources($resourceids, $approvalStatus);
            }
            

        }
        echo json_encode(['msg'=>$msg]);
        exit;
        

    }
    /**
    * group_bulkaction
    *
    * Action performing checks on groups
    *
    * @return string
    */
    public function group_bulkaction() {
        $is_admin = user_can(get_current_user_id(), 'manage_options' );
        if(!$is_admin){
            echo json_encode(['msg'=>'You are not authorized to perform this action']);
            exit;
        }
        global $wpdb;
        $moderates = array();
        
        if($_REQUEST['action'] == 'group_bulkaction') {
            $spam = $remove = 'F';
            if(!empty($_REQUEST['groupids'])){
                $msg = '';
                switch ($_REQUEST['bulkaction']){
                    case '-1':
                        $spam = $remove = 'F';
                        $msg = 'Please select an action';
                        break;
                    case 'Spam':
                        $spam = $remove = 'T';
                        $msg = 'Successfully spammed... Refreshing...';
                        break;
                    case 'NotSpam':
                        $spam = $remove = 'F';
                        $msg = 'Successfully removed from spammed... Refreshing...';
                        break;
                    default:
                        $spam = $remove = 'F';
                        $msg = 'Successfully removed from spammed... Refreshing...';
                        break;
                }
                
                $gids = [];
                foreach($_REQUEST['groupids'] as $gid){
                    $gids[] = addslashes($gid);
                }
                
                $groupids = implode(",", $gids);
                
                
                
                self::changeSpamGroups($spam, $remove, $groupids, $spam);
                
                
            }
            echo json_encode(['msg'=>$msg]);
            exit;
        }

    }
    /**
    * group_bulkaction
    *
    * Action performing checks on groups
    *
    * @return string
    */
    public function group_bulkaction_old() {
        $is_admin = user_can(get_current_user_id(), 'manage_options' );
        if(!$is_admin){
            echo json_encode(['msg'=>'You are not authorized to perform this action']);
            exit;
        }
        global $wpdb;
        $moderates = array();
        
        if($_REQUEST['action'] == 'group_bulkaction') {
            $spam = $remove = 'F';
            if(!empty($_REQUEST['groupids'])){
                $msg = '';
                switch ($_REQUEST['bulkaction']){
                    case '-1':
                        $spam = $remove = 'F';
                        $msg = 'Please select an action';
                        break;
                    case 'Spam':
                        $spam = $remove = 'T';
                        $msg = 'Successfully spammed';
                        break;
                    case 'NotSpam':
                        $spam = $remove = 'F';
                        $msg = 'Successfully removed from spammed';
                        break;
                    default:
                        $spam = $remove = 'F';
                        $msg = 'Successfully removed from spammed';
                        break;
                }
                
                $gids = [];
                foreach($_REQUEST['groupids'] as $gid){
                    $gids[] = addslashes($gid);
                }
                
                $groupids = implode(",", $gids);
                
                
                
                
                if($spam != ''){
                    self::changeSpamGroups($spam, $remove, $groupids);
                }
                if($spam == 'T'){
                    self::spamGroupCreators($gids);
//                    self::spamGroupResources($gids);
                }
                
                
            }
            echo json_encode(['msg'=>$msg]);
            exit;
        }

    }
    /**
    * spamGroupCreators
    *
    * Makes the spam group creators
    *
    *
    * @param array $gids Array of groupids need to be spammed
    * @return void
    */
    public static function spamGroupCreators($gids){
        global $wpdb; 
        
        $total_gids = count($gids);
        $placeholders = array_fill(0, $total_gids, '%d'); // group ids must be integers
        $format = implode(', ', $placeholders);
        $query = "UPDATE {$wpdb->prefix}users u
                     SET u.`user_status` = 1
                     WHERE u.ID IN (SELECT g.creatorid FROM groups g WHERE g.groupid IN ($format))
                     ";

        $wpdb->query( 
            $wpdb->prepare( 
                $query, $gids
            )
        );
        
        $query = "UPDATE users u
                     SET u.`spam` = 'T', u.`remove` = 'T', u.`indexrequired` = 'T'
                     WHERE u.userid IN (SELECT g.creatorid FROM groups g WHERE g.groupid IN ($format))
                     ";

        $wpdb->query( 
            $wpdb->prepare( 
                $query, $gids
            )
        );
    }
    
    /**
    * changeSpamGroups
    *
    * Changing spam status of groups
    *
    *
    * @param string $spam T,F
    * @param string $groupids Array of groupids need to be spammed/un-spammned
    * @return void
    */
    
    public static function changeSpamGroups($spam = 'F', $remove = 'F', $groupids){
        global $wpdb;
        if($spam == 'T'){
            $spamCreator = 'T';
        } else {
            $spamCreator = 'F';
        }
        return $wpdb->get_row( 
                $wpdb->prepare( 
                    "UPDATE groups
                    SET `spam` = %s, `indexrequired` = 'T', `remove` = %s, `spamCreator` = %s
                    WHERE groupid IN ($groupids)
                     ",
                    $spam, $remove, $spamCreator
                )
            );
        
    }
    
    /**
    * spamGroupResources
    *
    * Spamming group resources
    *
    *
    * @param string $gids Array of groupids need to be spammed
    * @return void
    */
    public static function spamGroupResources($gids){
        global $wpdb; 
        
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
    
    /**
    * checkCurrentUserIsAdmin
    *
    * Checks current user is admin or not
    *
    *
    * @return integer $is_admin if 1 then admin 
    */
    public static function checkCurrentUserIsAdmin(){
        return $is_admin = user_can(get_current_user_id(), 'manage_options' );
    }
    
    /**
    * spamUserAndResources
    *
    * Spam resources and also user belonged to those resources
    *
    *
    * @param array $resourceids Array of resourceids
    * @return void
    */
    public static function spamUserAndResources($resourceids, $approvalStatus, $approvalStatusDate){
        global $wpdb;
        
        $resources = $wpdb->get_results( 'SELECT resourceid, contributorid FROM resources WHERE resourceid IN( '.$resourceids.' )');
                        
        if(count($resources) > 0){
            foreach($resources as $resource){
                $is_admin = user_can( $resource->contributorid, 'manage_options' );

                if(!$is_admin){
                    //spam the contributor
                    $wpdb->query( 
                        $wpdb->prepare( 
                            "UPDATE resources
                             SET `approvalStatus` = %s, `indexrequired`='T', `active` = 'F', `approvalStatusDate` = %s
                             WHERE contributorid = %d
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
    }
    
    /**
    * spamResources
    *
    * Spam resources only
    *
    *
    * @param array $rids Array of resourceids
    * @return void
    */
    public static function spamResources($rids, $approvalStatus, $approvalStatusDate, $spamuser){
        global $wpdb;
        
        if(count($rids) > 0){
            $resourceids = implode(',', $rids);
            if($spamuser){
                $spamUser = 'T';
            } else {
                $spamUser = 'F';
            }
            $wpdb->query( 
                $wpdb->prepare( 
                    "UPDATE resources
                     SET `spam`= 'T', `remove`='T', `approvalStatus` = %s, `approvalStatusDate`= %s, `indexrequired`='T', `active` = 'F', `spamUser` = %s
                     WHERE resourceid IN ($resourceids)
                     ",
                     $approvalStatus, $approvalStatusDate, $spamUser
                )
            );

            $msg = 'Resources are rejected Successfully';
        }
    }
    /**
    * spamResources
    *
    * Spam resources only
    *
    *
    * @param array $rids Array of resourceids
    * @return void
    */
    public static function spamResources_old($rids, $approvalStatus, $approvalStatusDate){
        global $wpdb;
        
        if(count($rids) > 0){
            foreach($rids as $resourceid){
                $resource = $wpdb->get_row($wpdb->prepare( 'SELECT * FROM resources WHERE resourceid = %d',$resourceid));
                
                $is_admin = user_can( $resource->contributorid, 'manage_options' );
                if(!$is_admin){
                    $wpdb->query( 
                        $wpdb->prepare( 
                            "UPDATE resources
                             SET `approvalStatus` = %s, `approvalStatusDate`= %s, `indexrequired`='T', `active` = 'F'
                             WHERE resourceid = %d
                             ",
                             $approvalStatus, $approvalStatusDate, $resource->resourceid
                        )
                    );

                    $msg = 'Resources are rejected Successfully';
                } else {
                    $msg = 'Admin Cannot be spammed/rejected';
                }
            }
        }
    }
    
    /**
    * changeApprovalStatusResources
    *
    * Changes the approval status of resources
    *
    *
    * @param array $resourceids Array of resourceids
    * @param string $approvalStatus pending, rejected, approved
    * @return void
    */
    public static function changeApprovalStatusResources($resourceids, $approvalStatus, $approvalStatusDate){
        global $wpdb;
        $wpdb->query( 
            $wpdb->prepare( 
                "UPDATE resources
                 SET `approvalStatus` = %s, indexrequired = 'T', `spam` = 'F', `active` = 'T', `spamUser` = 'F', `approvalStatusDate` = %s
                 WHERE resourceid IN ($resourceids)
                 ",
                 $approvalStatus, $approvalStatusDate
            )
        );
    }
}

// instantiate the class
$moderateTerms = new ModerateTerms();