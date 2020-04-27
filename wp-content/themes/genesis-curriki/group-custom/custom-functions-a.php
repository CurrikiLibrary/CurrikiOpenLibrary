<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
function cur_is_admin_found($group_admins , $user_id)
{
    $rt_arr = array();
    foreach ($group_admins as $admin_rcd) {     
        if($admin_rcd->user_id == $user_id)
        {
            $rt_arr[] = $user_id;
        }
    }   
    if(count($rt_arr) > 0)
        return true;
    else
        return false;    
}

$actions = array(
                    'post_update' => 'cur_bp_dtheme_post_update'
                );
foreach( $actions as $name => $function ) 
{
    add_action( 'wp_ajax_'        . $name, $function );
    add_action( 'wp_ajax_nopriv_' . $name, $function );
}
function cur_bp_dtheme_post_update() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	// Check the nonce
	check_admin_referer( 'post_update', '_wpnonce_post_update' );

	if ( ! is_user_logged_in() )
		exit( '-1' );

	if ( empty( $_POST['content'] ) )
		exit( '-1<div id="message" class="error"><p>' . __( 'Please enter some content to post.', 'buddypress' ) . '</p></div>' );

	$activity_id = 0;
	if ( empty( $_POST['object'] ) && bp_is_active( 'activity' ) ) {
		$activity_id = bp_activity_post_update( array( 'content' => $_POST['content'] ) );

	} elseif ( $_POST['object'] == 'groups' ) {
		if ( ! empty( $_POST['item_id'] ) && bp_is_active( 'groups' ) )
			$activity_id = groups_post_update( array( 'content' => $_POST['content'], 'group_id' => $_POST['item_id'] ) );

	} else {
		$activity_id = apply_filters( 'bp_activity_custom_update', $_POST['object'], $_POST['item_id'], $_POST['content'] );
	}

	if ( empty( $activity_id ) )
		exit( '-1<div id="message" class="error"><p>' . __( 'There was a problem posting your update, please try again.', 'buddypress' ) . '</p></div>' );

        if(isset($activity_id) && $activity_id > 0)
        {
            $activity = new BP_Activity_Activity( $activity_id );	
            //var_dump( $activity->is_spam );
            if(  $activity->is_spam  == 1)
            {
                exit( '-1<div id="message" class="error"><p>' . __( 'We think you have posted "spam", please try again.', 'buddypress' ) . '</p></div>' );
            }            
        }
        
	if ( bp_has_activities ( 'include=' . $activity_id ) ) {
		while ( bp_activities() ) {
			bp_the_activity();
			locate_template( array( 'activity/entry.php' ), true );
		}
	}

	exit;
}


add_filter( 'template_include', 'cur_member_resource_template', 99 );
function cur_member_resource_template( $template ) {
    
    global $post;
    if(isset($_GET["memberpage"]) && $_GET["memberpage"] == 1)
    {    
        
        $new_template = locate_template( array( 'members/single/resources.php' ) );        
        if ( '' != $new_template ) {
            return $new_template ;
        }
    }
    
    if(isset($_GET["error-m"]))
    {
        $new_template = locate_template( array( 'page-error.php' ) );        
        if ( '' != $new_template ) {
            return $new_template ;
        }
    }
    return $template;
}

function cur_dynamic_content() {
    
    global $wp_query,$wpdb;
    
    if(isset($_GET["error-m"]) && $wp_query->is_404 && ($_GET["error-m"] == "group-spam" || $_GET["error-m"] == "rate-comment-spam") )
    {
        $title = "Error";
        switch ($_GET["error-m"])
        {
            case "group-spam":
                $title = "Spam Group";
                break;
            case "rate-comment-spam":
                $title = "Spam Comments";
                break;
        }
        
        $id=-1; // need an id
            $post = new stdClass();
                $post->ID= $id;
                $post->post_category= array('uncategorized'); //Add some categories. an array()???
                $post->post_content='<div class="error">This group is identified as spam<div>'; //The full text of the post.
                $post->post_excerpt= ''; //For all your post excerpt needs.
                $post->post_status='publish'; //Set the status of the new post.
                $post->post_title= $title; //The title of your post.
                $post->post_type='post'; //Sometimes you might want to post a page.
                $post->post_name=''; //Sometimes you might want to post a page.
                $post->post_author=''; //Sometimes you might want to post a page.
                $post->post_date=  current_time('mysql'); //Sometimes you might want to post a page.
                
            $wp_query->queried_object=$post;
            $wp_query->post=$post;
            $wp_query->found_posts = 1;
            $wp_query->post_count = 1;
            $wp_query->max_num_pages = 1;
            $wp_query->is_single = 1;
            $wp_query->is_404 = false;
            $wp_query->is_posts_page = 1;
            $wp_query->posts = array($post);
            $wp_query->page=false;
            $wp_query->is_post=true;
            $wp_query->page=false;            
    }
    
    
    if(isset($_GET["memberpage"]) && $_GET["memberpage"] == 1)
    {        
        if($wp_query->is_404 ) {            
            
            $user_id = bp_displayed_user_id();
            $user = $wpdb->get_row("SELECT cu.*,u.* FROM cur_users cu
                                        LEFT JOIN users u on u.userid = cu.ID
                                    WHERE ID = {$user_id}");
            
            /*echo "<pre>";
            var_dump($user);
            die;
            */
            $id=-1; // need an id
            $post = new stdClass();
                $post->ID= $id;
                //$post->post_category= array('uncategorized'); //Add some categories. an array()???
                //$post->post_content='hey here we are a real post'; //The full text of the post.
                //$post->post_excerpt= 'hey here we are a real post'; //For all your post excerpt needs.
                $post->post_status='publish'; //Set the status of the new post.
                $post->post_title= "{$user->firstname} {$user->lastname} | Resources"; //The title of your post.
                $post->post_type='post'; //Sometimes you might want to post a page.
            $wp_query->queried_object=$post;
            $wp_query->post=$post;
            $wp_query->found_posts = 1;
            $wp_query->post_count = 1;
            $wp_query->max_num_pages = 1;
            $wp_query->is_single = 1;
            $wp_query->is_404 = false;
            $wp_query->is_posts_page = 1;
            $wp_query->posts = array($post);
            $wp_query->page=false;
            $wp_query->is_post=true;
            $wp_query->page=false;
        }
    }    
}
add_action('wp', 'cur_dynamic_content');


function cur_wp_loaded() 
{
    global $bp,$wpdb,$post;        
    // ============ if group spam redirect to error page ================
    if($bp->current_component == "groups" && is_object( $bp->groups->current_group ))
    {
        $group_record = $wpdb->get_row("select cgr.*,gr.* from cur_bp_groups cgr
                            left join groups gr on gr.groupid = cgr.id
                           where cgr.id=".$bp->groups->current_group->id."",OBJECT);                        
        if($group_record->spam == 'T')
        {        
            wp_redirect(get_bloginfo('url').'/message/?error-m=group-spam');
            die();
        }
    }
    
    //============= ROUTING Application Code ===========
    if(property_exists($bp, "unfiltered_uri") && is_array($bp->unfiltered_uri) && count($bp->unfiltered_uri) > 0)
    {
        $page = $bp->unfiltered_uri[0];
        $code_dir = get_stylesheet_directory()."/group-custom/site-app/";        
        //======== Loading controllers =========
        if(file_exists($code_dir.$page.".php"))
        {
            require_once $code_dir.$page.".php";
        }
    } 
    
    //=========== Redirect to home if open normal register page,  to avoid spam registration ============
    if(is_array($bp->unfiltered_uri) && in_array("register",$bp->unfiltered_uri) && !(isset($_GET["iaaction"]) && $_GET["iaaction"] === "accept-invitation") )
    {
        wp_redirect(site_url());
        die();
    }

}
add_action('wp_loaded', 'cur_wp_loaded');


function curr_query_clauses($clauses)
{
    //** To deal with  cur_icl_translations ***
    /*
    global $wp_query;
    if($_GET["kill"] == 1)
    {        
        echo "<pre>";        
        var_dump($clauses);
        echo "<br />***********<br />";
        var_dump($wp_query);
        echo "<br />============================================================<br />";
        echo "</pre>";
        //die;
    }
     * 
     */
    
    global $wpdb , $bp;
            
    $src = null;   

    if( is_array($bp->unfiltered_uri) && in_array('forum', $bp->unfiltered_uri) && !in_array('topic', $bp->unfiltered_uri))
    {
        $src = "forum";
    }
    if( is_array($bp->unfiltered_uri) && in_array('forum', $bp->unfiltered_uri) && in_array('topic', $bp->unfiltered_uri))
    {
        $src = "topic";
    }
    
    if( bp_current_component() == "groups" && $src =="forum" )
    {    
        $clauses["orderby"] = "cur_posts.post_date DESC";
    }    
    return $clauses;
}
add_filter( 'posts_clauses', 'curr_query_clauses', 20, 1 ); 


function cur_bp_get_member_name() 
{
    global $members_template;
    return apply_filters( 'bp_get_member_name', $members_template->member->display_name );
}
add_filter( 'bp_member_name', 'cur_bp_get_member_name', 20, 1 ); 


function cur_bp_the_activity($args) 
{   
	global $activities_template;                    
        $r = wp_parse_args( $args, array(
			'no_timestamp' => false,
		) );
        
        
        $activities_template->activity->action = stripslashes($activities_template->activity->action);        
        
	//var_dump($activities_template->activity->action);
        if($activities_template->activity->user_id == get_current_user_id())
        {
                    
            if( strpos($activities_template->activity->action, $activities_template->activity->display_name) !== false)
            {                                            
                $action_new = str_replace($activities_template->activity->display_name, __("I",'curriki') ,  $activities_template->activity->action );                        
                $activities_template->activity->action = $action_new;                        
                
            }  else {
                $actions_to_look = array("created","joined","rated","posted","topic");
                foreach($actions_to_look as $act)
                {                                                                
                    if( strpos($activities_template->activity->action, $act) !== false)
                    {
                        $detected_old_name = false;
                        //$txt='<a title="mark boucher" href="http://cg.curriki.org/curriki/members/mark-boucher/">mark boucher</a>';                                    
                        $txt=$activities_template->activity->action;                                    
                        $re1='(<)';	$re2='(a)'; $re3='( )';	$re4='(title)';	$re5='(=)'; $re6='(".*?")'; $re7='( )';     $re8='(href)';  $re9='(=)';	$re10='(".*?")';    $re11='(>)';    $re12='((?:[a-z][a-z]+))';  $re13='(.*?)';  $re14='(<\\/a>)';
                        if ($c=preg_match_all ("/".$re1.$re2.$re3.$re8.$re9.$re10.$re11.$re13.$re14."/is", $txt, $matches))
                        {
                            /*$c1=$matches[1][0];   $c2=$matches[2][0]; $c3=$matches[3][0]; $word1=$matches[4][0];  $c4=$matches[5][0]; $string1=$matches[6][0];    $c5=$matches[7][0];*/
                            $word2=$matches[8][0];
                            /*$c6=$matches[9][0];   $string2=$matches[10][0];   $c7=$matches[11][0];    $word3=$matches[12][0]; $tag1=$matches[13][0];*/
                            //echo  "($c1) ($c2) ($c3) ($word1) ($c4) ($string1) ($c5) ($word2) ($c6) ($string2) ($c7) ($word3) ($tag1) \n";
                            $detected_old_name = $word2;
                        }
                        if($detected_old_name !== false)
                        {
                            $action_new = str_replace($detected_old_name, __("I","curriki") ,  $activities_template->activity->action );                        
                            $activities_template->activity->action = $action_new;                                                    
                        }
                    }                                
                }
            }                        
        }
                
        if(defined('ICL_LANGUAGE_CODE') && ICL_LANGUAGE_CODE != "en")
        {
            //========== [start] Re formating I if it is not with language constant ==========
            $user = get_userdata(bp_get_activity_user_id());                    
            $user_link_without_lang_1 = site_url()."/members/".$user->data->user_nicename."/";
            $user_link_without_lang_2 = site_url()."/members/".$user->data->user_nicename;
            if( strpos($activities_template->activity->action, $user_link_without_lang_1) !== false )
            {                
                $user_link_with_lang = site_url()."/".ICL_LANGUAGE_CODE."/members/".$user->data->user_nicename."/";
                $action_alt = str_replace($user_link_without_lang_1,$user_link_with_lang,$activities_template->activity->action);
                $activities_template->activity->action = $action_alt;
            }elseif(strpos($activities_template->activity->action, $user_link_without_lang_2) !== false){                
                $user_link_with_lang = site_url()."/".ICL_LANGUAGE_CODE."/members/".$user->data->user_nicename."/";
                $action_alt = str_replace($user_link_without_lang_2,$user_link_with_lang,$activities_template->activity->action);
                $activities_template->activity->action = $action_alt;
            }            
            //============= [ent] Re formating I if it is not with language constant ==========
            
            //==================== [START] SETING ACTIONS ===========================================
            $actions_translation = array("created" , "updated", "posted an update" , "rated","posted","joined the group","started the topic","in the forum","topic","joined");
            foreach($actions_translation as $act)
            {
                if( strpos($activities_template->activity->action, $act) !== false)
                {
                    $act_translated = __($act,'curriki');
                    $action_alt = str_replace($act,$act_translated,$activities_template->activity->action);
                    $activities_template->activity->action = $action_alt;
                }
            }                            
            //==================== [END] SETING ACTIONS ===========================================
            
            //================= [start] 'I' translation code for language =============
            $user = get_userdata(bp_get_activity_user_id());        
            $user_link = site_url()."/".ICL_LANGUAGE_CODE."/members/".$user->data->user_nicename."/";

            $I_link_1 = '<a href="'.$user_link.'">I</a>';
            $I_link_2 = '<a href="'.$user_link.'" rel="nofollow">I</a>';
            $I_link_3 = '<a title="I" href="'.$user_link.'">I</a>';
            $I_link_4 = '<a href="'.$user_link.'" title="I">I</a>';                    
            
            if( strpos($activities_template->activity->action, $I_link_1) !== false )
            {   

                $I_link_1_tr = '<a href="'.$user_link.'">'.__('I','curriki').'</a>';
                $activities_template->activity->action = str_replace($I_link_1,$I_link_1_tr,$activities_template->activity->action);

            }elseif (strpos($activities_template->activity->action, $I_link_2) !== false) {

                $I_link_2_tr = '<a href="'.$user_link.'" rel="nofollow">'.__("I","curriki").'</a>';
                $activities_template->activity->action = str_replace($I_link_2,$I_link_2_tr,$activities_template->activity->action);            

            }elseif (strpos($activities_template->activity->action, $I_link_3) !== false) {

                $I_link_3_tr = '<a title="I" href="'.$user_link.'">'.__('I','curriki').'</a>';
                $activities_template->activity->action = str_replace($I_link_3,$I_link_3_tr,$activities_template->activity->action);            

            }elseif (strpos($activities_template->activity->action, $I_link_4) !== false) {
                $I_link_4_tr = '<a href="'.$user_link.'" title="I">I</a>';
                $activities_template->activity->action = str_replace($I_link_4,$I_link_4_tr,$activities_template->activity->action);            
            }
            //================= [end] 'I' translation code for language =============
            
        }                
        return $activities_template->activity->action;
}

add_action( 'bp_get_activity_action', 'cur_bp_the_activity' , 20, 1 );

function cur_bp_get_activity_content_body($c)
{
    $c = str_replace("Blog Update:", __("Blog Update:","curriki"), $c);
    return $c;
}
add_action( 'bp_get_activity_content_body', 'cur_bp_get_activity_content_body' , 20, 1 );


add_action( 'wp_ajax_nopriv_cur_oer_page_count', 'ajax_cur_oer_page_count' ); 
add_action( 'wp_ajax_cur_oer_page_count', 'ajax_cur_oer_page_count'); 

function ajax_cur_oer_page_count() 
{
    //**** setting 'resourceviews' for resource *** 
    $r_views_data = $_POST["set_resource_views_data"];
    $rid = isset($r_views_data["rid"]) && strlen($r_views_data["rid"]) > 0 ? $r_views_data["rid"] : NULL;
    $pageurl = isset($r_views_data["pageurl"]) && strlen($r_views_data["pageurl"]) > 0 ? $r_views_data["pageurl"] : NULL;
    $visitid = isset($r_views_data["lvid"]) && strlen($r_views_data["lvid"]) > 0 ? $r_views_data["lvid"] : NULL;
    
    //**** page views counts to redirect *****
    //======== Reverse DNS lookup ========    
    $ip_client = $_SERVER["REMOTE_ADDR"];    
    $client_ip_domain_name = gethostbyaddr($ip_client);    
    //array("nextbridge.pk","d1.nextbridge.pk")
    //$google_bots_set = array("nextbridge.pk","d1.nextbridge.pk");
    $google_bots_set = array("googlebot.com","google.com");
    $msn_bots_set = array("search.msn.com");    
    $found_google_bots = get_matched_bots($google_bots_set, $client_ip_domain_name);
    $found_msn_bots = get_matched_bots($msn_bots_set, $client_ip_domain_name);    
    $all_found_bots = array_merge($found_google_bots,$found_msn_bots);            
    if(count($all_found_bots) > 0)
    {
        $msg = array("is_redirect" => 0,"bt"=>"1");
        echo json_encode( $msg );
        die();
    }
    
    //Dont redirect if SUBDOMAIN = students, studentsearch
    if(SUBDOMAIN == 'studentsearch' || SUBDOMAIN == 'students'){
        $msg = array("is_redirect" => 0);
        echo json_encode( $msg );
        wp_die();
    }
    
    if (get_current_user_id() == 0) 
    {        
        //===== [start] Manage User view  =============
        if (isset($_COOKIE["visit_counter"])) {
          //setcookie('visit_ip', null, -1, SITECOOKIEPATH);
          //setcookie('visit_counter', null, -1, SITECOOKIEPATH);
          //die;    

          $ck_val = $_COOKIE["visit_counter"];
          $visit_counter_new = $ck_val + 1;
          $secure = ( 'https' === parse_url(site_url(), PHP_URL_SCHEME) );
          setcookie('visit_ip', get_client_ip_address_for_oer(), time() + YEAR_IN_SECONDS, SITECOOKIEPATH, null, $secure);
          setcookie('visit_counter', $visit_counter_new, time() + YEAR_IN_SECONDS, SITECOOKIEPATH, null, $secure);
          //var_dump($visit_counter_new);
//          if ($visit_counter_new > 2) {
          if (0) {
            //=== redirct to login ===    
            $r_url = site_url()."/oer/".$pageurl;            
            $rtnurl = "&fwdreq=". urlencode($r_url);
            $redirect_url = bp_get_root_domain() . "/?a=login".$rtnurl;
            //wp_redirect($redirect_url);
            //wp_safe_redirect($redirect_url);
            $msg = array("is_redirect" => 1 , "redirect_url" => $redirect_url);
            echo json_encode( $msg );
            die();
          }
        } else {     
          $secure = ( 'https' === parse_url(site_url(), PHP_URL_SCHEME) );        
          $ts1 = setcookie('visit_ip', get_client_ip_address_for_oer(), time() + YEAR_IN_SECONDS, SITECOOKIEPATH, null);
          $ts2 = setcookie('visit_counter', 1, time() + YEAR_IN_SECONDS, SITECOOKIEPATH, null);
          
          $msg = array("is_redirect" => 0);
          echo json_encode( $msg );
          die();
        }
    }

}

function set_resource_views_on_resource_load($rid,$pageurl,$visitid)
{            
    $res = new CurrikiResources();
    if ( ((int) $rid) > 0 ) {                
        $res->setResourceViews((int) $rid , intval($visitid));        
    }elseif(isset($pageurl) && strlen($pageurl)>0){                
        $resourceUser = $res->getResourceUserByIdForResourceViews((int) $rid, rtrim($pageurl, '/'));
        $res->setResourceViews((int) $resourceUser['resourceid'] , intval($visitid));    
    }
}

function get_matched_bots($bots_set,$client_domain_name) {
    $bots_found = array();
    foreach ($bots_set as $bot)
    {        
        if(stripos($client_domain_name, $bot) !== false)
        { 
            $bots_found[] = $bot;
        }
    }
    return $bots_found;
}

function cur_groups_leave_group($group_id, $user_id)
{    
    $group_meta = groups_get_groupmeta( $group_id );
    $members_visited = array();
    if( $group_meta["members_visited"][0] === "" )  
        $members_visited = array();
    else
        $members_visited = explode(",",  $group_meta["members_visited"][0] );
    
    $pos = array_search(get_current_user_id(), $members_visited);
    if($pos !== null)
    {
        unset($members_visited[$pos]);
    }    
    groups_update_groupmeta($group_id, "members_visited", implode(",", $members_visited) );    
}
add_action( 'groups_leave_group', 'cur_groups_leave_group' , 20, 2 );




function curr_oa_social_login_do_before_user_redirect ($user_data, $identity, $redirect_to)
{
    
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    include_once( realpath( __DIR__."/../../../libs/functions.php" ) );
    
    global $wpdb, $vars;
    
    $q = $wpdb->prepare("select * from users where userid = %d" , $user_data->ID);
    $user = $wpdb->get_row($q);
    //echo "<pre>";    
    //var_dump( $user );        
    //var_dump( $user_data );        
    //var_dump($identity);
    //var_dump($redirect_to);
    //die;
         
    //========== UPLOADING PROFILE PICTURE ============
    if( property_exists($identity, "pictureUrl") && ( !isset($user->uniqueavatarfile) || $user->uniqueavatarfile == "" ) )
    {

        //======== [start] Reading profile picture for FB and saving temp =====        
        $pic_url = $identity->pictureUrl;
        $upload_dir_arr = wp_upload_dir();
        $upload_dir = $upload_dir_arr["basedir"]."/";                       
        $ch = curl_init($pic_url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        $data = curl_exec($ch);
        curl_close ($ch);            
        curl_close($ch);
        $fileName = 'profile-pic-'.$user_data->ID.'.jpg';
        $file_path = $upload_dir.$fileName;            

        if(file_exists($file_path))
        {                
            ob_start();
            unlink($file_path);
            ob_end_clean();
        }                        

        $file = fopen($file_path, 'w+');                          
        ob_start();
        fputs($file, $data);
        ob_end_clean();
        fclose($file);
        //==== [start] Upload Profile piccture to cloud and update user ========                 
        $aws = $vars['aws'];
        $s3_client = $aws->get('S3');
        $bucket = DBI_AWS_S3_BUCKET;
        $targetFile = $file_path;
        $ext = "jpg";

        if (file_exists($targetFile)) {
            $pic = uniqid();
            $upload = $s3_client->putObject(array(
                        'ACL' => 'public-read',
                        'Bucket' => $bucket,
                        'CacheControl' => 'max-age=172800',
                        'Key' => 'avatars/' . $pic . '.' . $ext,
                        'Body' => fopen($targetFile, 'r+')
                    ))->toArray();

            $wpdb->update(
                'users', array(
                    'uniqueavatarfile' => $pic . '.' . $ext,
                ), array('userid' => $user->userid), array('%s'), array('%d')
            );


        } 

        if(file_exists($file_path))
        {                
            ob_start();
            unlink($file_path);
            ob_end_clean();
        }

        //==== [start] Upload Profile piccture to cloud and update user ========                                    
    }
    
    //===== SETTIN GENDER =======
    if( property_exists($identity, "gender") )
    {            

        $profile = get_user_meta(get_current_user_id(),"profile",true);    
        $profile = isset($profile) ? json_decode($profile) : null;            
               
        if(!isset($profile))        
        {
            $profile = new stdClass();
            $profile->gender = $identity->gender;                                            
            add_user_meta($user->userid, "profile", json_encode($profile));
        }
    }
    
    // ========== Inserting Logins Log ===========
    $wpdb->insert(
                    'logins', array(
                    'sitename' => 'curriki',
                    'userid' => $user_data->ID,      
                    'logindate' => date("Y-m-d H:i:s")
        ), 
         array('%s', '%d', '%s')
    );
}
add_action ('oa_social_login_action_before_user_redirect', 'curr_oa_social_login_do_before_user_redirect', 10, 3);

function cur_groups_group_details_edited($group_id)
{     
    if(!is_admin())
    {
        global $wpdb;
        
        if (is_array($_POST['subjectarea']) && count($_POST['subjectarea']) > 0) 
        {
            $wpdb->delete('group_subjectareas', array('groupid' => $group_id));

            $subjectarea_arr = $_POST['subjectarea'];
            foreach ($subjectarea_arr as $subject_area_id) {
                $groupid = $group_id;
                $wpdb->insert('group_subjectareas', array("groupid" => $groupid, 'subjectareaid' => $subject_area_id));
            }
        }

        if (is_array($_POST['education_levels']) && count($_POST['education_levels']) > 0) 
        {
            $wpdb->delete('group_educationlevels', array('groupid' => $group_id));

            $subjectarea_arr = $_POST['education_levels'];
            $groupid = $group_id;
            foreach ($subjectarea_arr as $education_level_id) {
                $el_id_arr = explode('|', $education_level_id);
                foreach ($el_id_arr as $id) {
                    $groupid = $group_id;
                    $wpdb->insert('group_educationlevels', array("groupid" => $groupid, 'educationlevelid' => $id));
                }
            }
        }
        
        $group_table = $wpdb->prefix . "bp_groups";
        $query_group = "SELECT * FROM $group_table WHERE id=" . $group_id;
        $group_record = $wpdb->get_row($query_group, OBJECT);
        $wpdb->update('groups', array(
                                    'name' => $group_record->name,
                                    'description' => $group_record->description,
                                    'displaytitle' => $group_record->name,
                                    'indexed' => 'F',
                                    'indexrequired' => 'T',
                                    'indexrequireddate' => current_time('mysql')
                                ), 
                                array(
                                    "groupid" => $group_record->id,
                                ), 
                                array("%s", "%s", "%s","%s", "%s"), 
                                array("%d")
                    );
        
    }
}
add_action('groups_group_details_edited','cur_groups_group_details_edited',20,1);


add_action( 'wp_ajax_nopriv_cur_resend_email_invites', 'ajax_cur_resend_email_invites' ); 
add_action( 'wp_ajax_cur_resend_email_invites', 'ajax_cur_resend_email_invites'); 
function ajax_cur_resend_email_invites()
{
    global $bp;
    $emails = $_POST["selected_emails"];    
    $emails = array_unique($emails);    
    
    $subject = invite_anyone_invitation_subject();
    $message = invite_anyone_invitation_message();
    do_action( 'invite_anyone_process_addl_fields' );
    foreach( $emails as $email ) 
    {
        $subject = stripslashes( strip_tags( $subject ) );
        $message = stripslashes( strip_tags( $message ) );

        $footer = invite_anyone_process_footer( $email );
        $footer = invite_anyone_wildcard_replace( $footer, $email );

        $message .= '

================
';
        $message .= $footer;

        $inviter_name = $bp->loggedin_user->userdata->display_name;
        $inviter_url  = bp_loggedin_user_domain();
        
        $message = str_replace( '%INVITERNAME%', $inviter_name, $message );
	$message = str_replace( '%INVITERURL%', $inviter_url, $message );
        
        $to = apply_filters( 'invite_anyone_invitee_email', $email );
        $subject = apply_filters( 'invite_anyone_invitation_subject', $subject );
        $message = apply_filters( 'invite_anyone_invitation_message', $message );
        
        $group_id = $_POST["group_id"];
        $groups[] = $group_id;        
        wp_mail( $to, $subject, $message );
        $is_cloudsponge = false;
        invite_anyone_record_invitation( $bp->loggedin_user->id, $email, $message, $groups, $subject, $is_cloudsponge );
        do_action( 'sent_email_invite', $bp->loggedin_user->id, $email, $groups );               
    }
}

add_action( 'wp_ajax_nopriv_cur_resend_invites', 'ajax_cur_resend_invites' ); 
add_action( 'wp_ajax_cur_resend_invites', 'ajax_cur_resend_invites'); 
function ajax_cur_resend_invites()
{
    
    $selected_users = $_REQUEST["selected_users"];
    foreach($selected_users as $member_id)
    {
        // Send the actual invite        
        $group = groups_get_group(  array("group_id"=> bp_get_current_group_id())    );        
        $member = new BP_Groups_Member( $member_id, $group->id );
        $user_id = bp_loggedin_user_id();        
        //groups_notification_group_invites( $group, $member, $user_id );
        $inviter_user_id = $user_id;
        // Setup the ID for the invited user
	$invited_user_id = $member->user_id;

        $group_link   = bp_get_group_permalink( $group );
        $inviter_link = bp_core_get_user_domain( $inviter_user_id );
        $inviter_name = bp_core_get_userlink( $inviter_user_id, true, false, true );
        $invited_ud    = bp_core_get_core_userdata( $invited_user_id );
	$settings_slug = function_exists( 'bp_get_settings_slug' ) ? bp_get_settings_slug() : 'settings';
	$settings_link = bp_core_get_user_domain( $invited_user_id ) . $settings_slug . '/notifications/';
	$invited_link  = bp_core_get_user_domain( $invited_user_id );
	$invites_link  = trailingslashit( $invited_link . bp_get_groups_slug() . '/invites' );

        // Trigger a BuddyPress Notification
	if ( bp_is_active( 'notifications' ) ) {
		bp_notifications_add_notification( array(
			'user_id'          => $invited_user_id,
			'item_id'          => $group->id,
			'component_name'   => buddypress()->groups->id,
			'component_action' => 'group_invite'
		) );
	}
        
	// Set up and send the message
	$to       = $invited_ud->user_email;
	$subject  = bp_get_email_subject( array( 'text' => sprintf( __( 'You have an invitation to the group: "%s"', 'buddypress' ), $group->name ) ) );
	$message  = sprintf( __(
'One of your friends %1$s has invited you to the group: "%2$s".

To view your group invites visit: %3$s

To view the group visit: %4$s

To view %5$s\'s profile visit: %6$s

---------------------
', 'buddypress' ), $inviter_name, $group->name, $invites_link, $group_link, $inviter_name, $inviter_link );

	// Only show the disable notifications line if the settings component is enabled
	if ( bp_is_active( 'settings' ) ) {
		$message .= sprintf( __( 'To disable these notifications please log in and go to: %s', 'buddypress' ), $settings_link );
	} 
                                
        if( wp_mail( $to, $subject, $message ) )
        {            
        }else{            
        }
    }
    die;
}

function cur_on_logout() 
{
    /*if( isset($_SESSION["last_visitsid"]) )
        unset ( $_SESSION["last_visitsid"] );*/
    
    if( isset($_SESSION["last_visit_user_id"]) )
        unset ( $_SESSION["last_visit_user_id"] );
    if( isset($_SESSION["complete_porfile_displayed"]) )
        unset ( $_SESSION["complete_porfile_displayed"] );
}
add_action('wp_logout', 'cur_on_logout');


function cur_bp_get_avatar_admin_step() {    
        $bp   = buddypress();
        $step = isset( $bp->avatar_admin->step )
                ? $step = $bp->avatar_admin->step
                : 'upload-image';

        var_dump($step);
        die;
        /**
         * Filters the current avatar upload step.
         *
         * @since BuddyPress (1.1.0)
         *
         * @param string $step The current avatar upload step.
         */
        //return apply_filters( 'bp_get_avatar_admin_step', $step );
}
//add_filter("wp_head","cur_bp_get_avatar_admin_step",20);
//add_filter("bp_get_avatar_admin_step","cur_bp_get_avatar_admin_step",20);


function get_client_ip_address_for_oer() {
  $ipaddress = '';
  if ( isset($_SERVER['HTTP_CLIENT_IP']) )
    $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
  else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
    $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
  else if (isset($_SERVER['HTTP_X_FORWARDED']))
    $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
  else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
    $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
  else if (isset($_SERVER['HTTP_FORWARDED']))
    $ipaddress = $_SERVER['HTTP_FORWARDED'];
  else if (isset($_SERVER['REMOTE_ADDR']))
    $ipaddress = $_SERVER['REMOTE_ADDR'];
  else
    $ipaddress = 'UNKNOWN';

  return $ipaddress;
}

add_filter( 'wp_title', 'curr_wp_title', 99, 1 );
function curr_wp_title($title)
{   
    global $wp_page_title;
    $wp_page_title = $title;             
    return $title;
}

add_filter( 'genesis_title', 'curr_wp_gen_title', 100, 1 );
function curr_wp_gen_title($title)
{       
    global $bp;
    global $wp_page_title;
        
    //*-*-*-*-* setting topic title *-*-*-*-*
    if( is_array($bp->unfiltered_uri) && count($bp->unfiltered_uri) === 5 && in_array("topic", $bp->unfiltered_uri) && bp_current_component() === "groups" )
    {                
        if( $wp_page_title && strlen($wp_page_title)>0 )
        {                           
            $title = $wp_page_title;
        }
    }   
    return $title;
}


add_filter( 'wpml_url_converter_get_abs_home', 'cur_wpml_url_converter_get_abs_home',100,1);
function cur_wpml_url_converter_get_abs_home( $dabsolute_home ) {    
    $absolute_home = site_url();
  return $absolute_home;
}
  
 