<?php

/**
 * Plugin Name:       Curriki Manage Groups
 * Plugin URI:        http://curriki.org
 * Description:       Plugin to manage Curriki Groups
 * Version:           1.0
 * Author:            Waqar Muneer
 * Author URI:        http://curriki.org
 */
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

class BP_Curr_Manage {

    /**
     * Constructor
     */
    public $group_id = null;

    public function __construct() {
        $this->setup_filters();
    }

    public function get_group_subjects($current_language=null) {
        if (isset($this->group_id)) {
            global $wpdb;

            $query = "SELECT *,subjectareas_ml.displayname AS subjectarea_displayname,subjects_ml.displayname as subject_displayname  
                        FROM group_subjectareas
                        LEFT JOIN subjectareas ON group_subjectareas.subjectareaid = subjectareas.subjectareaid                        
                        INNER JOIN subjectareas_ml
                        ON subjectareas.subjectareaid = subjectareas_ml.subjectareaid                        
                        LEFT JOIN subjects ON subjectareas.subjectid = subjects.subjectid
                        INNER JOIN subjects_ml ON subjects.subjectid = subjects_ml.subjectid
                        WHERE groupid = {$this->group_id}
                        AND subjectareas_ml.language = '$current_language'
                        AND subjects_ml.language = '$current_language'
                        ";                        
            $records = $wpdb->get_results($query, OBJECT);
            /*
              $subjects_arr = array();
              foreach($records as $g_sb){
              $subjects_arr[] = $g_sb->displayname;
              }
              $subjects = array_unique($subjects_arr);
             */
            return $records;
            //return $subjects;
        } else {
            return null;
        }
    }

    public function get_group_education_levels($current_language=null) {
        if (isset($this->group_id)) {
            global $wpdb;

            $query = "SELECT 
                        educationlevels.displayname AS displayname_orignal,
                        educationlevels_ml.displayname AS displayname
                     FROM group_educationlevels
                     LEFT JOIN educationlevels ON group_educationlevels.educationlevelid = educationlevels.levelid
                     INNER JOIN educationlevels_ml ON
                     educationlevels.levelid = educationlevels_ml.levelid
                     WHERE groupid = {$this->group_id}
                        AND educationlevels_ml.language = '$current_language'
                     ";                     
            $records = $wpdb->get_results($query, OBJECT);            
            return $records;            
        } else {
            return null;
        }
    }

    public function get_group_languages($current_language=null) {
        if (isset($this->group_id)) 
        {
            global $wpdb;
            $query = "SELECT 
                        languages.displayname AS displayname_orignal,
                        languages_ml.displayname AS displayname
                        FROM groups 
                        LEFT JOIN languages ON groups.language = languages.language
                        INNER JOIN languages_ml ON languages.language = languages_ml.language
                        WHERE groupid = {$this->group_id}
                         AND languages_ml.displaylanguage = '$current_language'
                        ";                        
            $records = $wpdb->get_results($query, OBJECT);            
            return $records;            
        } else {
            return null;
        }
    }

    /**
     * Filters
     */
    private function setup_filters() {
        add_filter('bp_ajax_querystring', array($this, 'activity_querystring_filter'), 12, 2);
        add_filter('bp_has_groups', array($this, 'filter_groups_for_each_group'), 10, 2);
        add_filter('bp_get_groups_pagination_count', array($this, 'curr_bp_get_groups_pagination_count'), 10, 0);

        add_filter('template_notices', array($this, 'curr_template_notices'), 10, 2);
        
        
        add_action('groups_delete_group', array($this, 'curr_groups_delete_group') , 20,1 );
        
        add_action('groups_created_group', array($this, 'curr_group_on_create'));
        //add_action( 'groups_group_create_complete',  array($this , 'curr_group_on_create_complete') );                
        add_action('bp_has_activities', array($this, 'curr_activity_filter'), 10, 2);
        add_action('wp_ajax_invite_anyone_autocomplete_ajax_handler', array($this, 'invite_anyone_ajax_autocomplete_results'));
        
        add_filter('icl_post_languages_options_after', array($this, 'curr_icl_post_languages_options_after'), 20, 2);
    }

    
    
    public function curr_icl_post_languages_options_after( $a = null, $b = null) {
        
        if( defined('ICL_LANGUAGE_CODE') && ICL_LANGUAGE_CODE != 'en')
        {
            wp_enqueue_script('oer-custom-script', plugins_url() . '/curriki-manage-groups/script/cur-ml.js', array('jquery'), false, true);        
            echo '
                  <p><strong>Google API Translation:</strong>
                  <br />                    
                        <a href="#" id="cur-translate-with-google">Fetch translated Page/Post content</a>
                  </p>';
        }                      
    }
     
    public function curr_template_notices($tag = '', $arg = '') {
        global $bp;
        //var_dump($bp->template_message);
        $message_parts = explode(':', $bp->template_message);

        if ($bp->template_message == "Group invites sent." && in_array("invite-anyone", $bp->unfiltered_uri)) {
            $bp->template_message = 'Group invites sent. <a href="' . site_url() . '/groups/' . bp_get_current_group_slug() . '">Back to Group</a>';
        } elseif (count($message_parts) > 0 && in_array("Invitations were sent successfully to the following email addresses", $message_parts)) {
            /*
              $back_url = null;
              $action_variables = ( key_exists("action_variables", $bp->canonical_stack) ) ? $bp->canonical_stack['action_variables'] : array();
              if(count($action_variables) == 2 && in_array('group-invites', $action_variables))
              {
              $group_id = $action_variables[1];
              $group = groups_get_group( array( 'group_id' => $group_id ) );
              $back_url = get_site_url()."/".$bp->groups->slug."/".$group->slug."/invite-anyone/";
              }
              if($back_url != null) {
              $bp->template_message .= ' <a href="'.$back_url.'">Back to Group</a>';
              }else{
              $bp->template_message .= ' <a href="'.site_url().'/members/'.$bp->unfiltered_uri[1].'/invite-anyone/">Back</a>';
              }
             * 
             */
        }
    }

    public function invite_anyone_ajax_autocomplete_results() {

        global $bp;

        $return = array(
            'query' => $_REQUEST['query'],
            'data' => array(),
            'suggestions' => array()
        );

        $users = $this->invite_anyone_invite_query_curr($bp->groups->current_group->id, $_REQUEST['query']);

        if ($users) {
            $suggestions = array();
            $data = array();

            foreach ($users as $user) {
                //$suggestions[] 	= $user->display_name . ' (' . $user->user_login . ')';
                //$data[] 	= $user->ID;
                $suggestions[] = $user->suggestions . ' (' . $user->user_login . ')';
                $data[] = $user->data;
            }

            $return['suggestions'] = $suggestions;
            $return['data'] = $data;
        }

        $return['msg'] = "from custom plugin";
        die(json_encode($return));
    }

    public function invite_anyone_invite_query_curr($group_id = false, $search_terms = false, $fields = 'all') {
        // Get a list of group members to be excluded from the main query
        $group_members = array();
        $args = array(
            'group_id' => $group_id,
            'exclude_admins_mods' => false
        );
        if ($search_terms)
            $args['search'] = $search_terms;

        if (bp_group_has_members($args)) {
            while (bp_group_members()) {
                bp_group_the_member();
                $group_members[] = bp_get_group_member_id();
            }
        }

        // Don't include the logged-in user, either
        $group_members[] = bp_loggedin_user_id();

        $fields = 'ID' == $fields ? 'ID' : 'all';

        // Now do a user query
        // Pass a null blog id so that the capabilities check is skipped. For BP blog_id doesn't
        // matter anyway
        /*
          $user_query = new Invite_Anyone_User_Query( array(
          'blog_id' => NULL,
          'exclude' => $group_members,
          'search' => $search_terms,
          'fields' => $fields,
          ) );
          return $user_query->results;
         */
        $group_members_ids = implode(',', array_unique($group_members));
        global $wpdb;
        $bd_user_table = $wpdb->prefix . "users";
        $query = "SELECT $bd_user_table.display_name as suggestions , $bd_user_table.ID as data , $bd_user_table.user_login FROM " . $bd_user_table;
        $query .= " LEFT JOIN users ON $bd_user_table.ID = users.userid";
        $query .= " WHERE user_status = 0";
        $query .= ' AND display_name like "%' . $search_terms . '%"';
        $query .= ' AND ID NOT IN (' . $group_members_ids . ')';
        $query .= ' AND users.active=\'T\'';
        $records = $wpdb->get_results($query, OBJECT);
        //var_dump($wpdb->last_query);die;
        //die( json_encode( $records ) );                
        return $records;
    }

    public function curr_activity_filter($a, $activities) {

        //======= setting logged in user Name to I =============
        global $wpdb;
        $pagename = get_query_var('pagename');
        $pagename = isset($pagename) ? $pagename : "";


        if ((is_user_logged_in() && !is_admin()) && (bp_current_component() == 'groups' || bp_current_component() == 'activity' || $pagename == 'dashboard')) {

            foreach ($activities->activities as $key => $activity) {
                // if current activity's user is equal to current loggedin user
                if ($activity->user_id == get_current_user_id()) {
                    if (strpos($activity->action, $activity->display_name) !== false) {
                        $action_new = str_replace($activity->display_name, "I", $activity->action);
                        $activity->action = $action_new;
                        $activities->activities[$key] = $activity;
                    } else {
                        $actions_to_look = array("created", "joined", "rated", "posted", "topic");
                        foreach ($actions_to_look as $act) {
                            if (strpos($activity->action, $act) !== false) {
                                $detected_old_name = false;
                                //$txt='<a title="mark boucher" href="http://cg.curriki.org/curriki/members/mark-boucher/">mark boucher</a>';                                    
                                $txt = $activity->action;
                                $re1 = '(<)';
                                $re2 = '(a)';
                                $re3 = '( )';
                                $re4 = '(title)';
                                $re5 = '(=)';
                                $re6 = '(".*?")';
                                $re7 = '( )';
                                $re8 = '(href)';
                                $re9 = '(=)';
                                $re10 = '(".*?")';
                                $re11 = '(>)';
                                $re12 = '((?:[a-z][a-z]+))';
                                $re13 = '(.*?)';
                                $re14 = '(<\\/a>)';
                                if ($c = preg_match_all("/" . $re1 . $re2 . $re3 . $re8 . $re9 . $re10 . $re11 . $re13 . $re14 . "/is", $txt, $matches)) {
                                    /* $c1=$matches[1][0];   $c2=$matches[2][0]; $c3=$matches[3][0]; $word1=$matches[4][0];  $c4=$matches[5][0]; $string1=$matches[6][0];    $c5=$matches[7][0]; */
                                    $word2 = $matches[8][0];
                                    /* $c6=$matches[9][0];   $string2=$matches[10][0];   $c7=$matches[11][0];    $word3=$matches[12][0]; $tag1=$matches[13][0]; */
                                    //echo  "($c1) ($c2) ($c3) ($word1) ($c4) ($string1) ($c5) ($word2) ($c6) ($string2) ($c7) ($word3) ($tag1) \n";
                                    $detected_old_name = $word2;
                                }
                                if ($detected_old_name !== false) {
                                    $action_new = str_replace($detected_old_name, "I", $activity->action);
                                    $activity->action = $action_new;
                                    $activities->activities[$key] = $activity;
                                }
                            }
                        }
                    }
                }
                //======= [start]censorphrases filtering =================
                /* $content_val = strtolower($activity->content);
                  $cnsr_arr  = $wpdb->get_results("SELECT phrase FROM censorphrases",ARRAY_N);
                  $censorphrases  = count($cnsr_arr) > 0 ? $cnsr_arr[0] : array();
                  if($this->striposa($content_val, $censorphrases, 1)){
                  unset( $activities->activities[$key] );
                  } */
                //======= [end]censorphrases filtering ====================                    
            }
        } elseif (!is_admin()) {
            /*
              foreach( $activities->activities as $key => $activity)
              {
              //======= [start]censorphrases filtering =================
              $content_val = strtolower($activity->content);
              $cnsr_arr  = $wpdb->get_results("SELECT phrase FROM censorphrases",ARRAY_N);
              $censorphrases  = count($cnsr_arr) > 0 ? $cnsr_arr[0] : array();
              if($this->striposa($content_val, $censorphrases, 1)){
              unset( $activities->activities[$key] );
              }
              //======= [end]censorphrases filtering ====================
              }
             * 
             */
        }

        $activities->activities = array_values($activities->activities);
        return $activities;
    }

    public function striposa($haystack, $needles = array(), $offset = 0) {
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

    public function curr_groups_delete_group($group_id) {
        
        global $wpdb;
        $wpdb->update('groups', array(                                
                                'remove' => 'T',
                                'indexrequired' => 'T',
                                'indexrequireddate' => current_time('mysql')
                            ), array(
                                    "groupid" => $group_id,
                            ), array("%s", "%s", "%s"), array("%d")
                    );
    }
    
    public function curr_group_on_create() {
        global $bp, $wpdb;

        if (!session_id()) {
            session_start();
        }


        if (isset($_POST['save_fld']) && isset($_POST['group-name']) && !is_admin()) {

            $groupmeta_id = $wpdb->insert_id;
            $groupmeta_table = $wpdb->prefix . "bp_groups_groupmeta";
            $query_gm = "SELECT * FROM $groupmeta_table WHERE id=" . $groupmeta_id;
            $groupmeta_record = $wpdb->get_row($query_gm, OBJECT);

            if (isset($groupmeta_record) && $_POST["group_id"] == 0) {
                $group_table = $wpdb->prefix . "bp_groups";
                $query_group = "SELECT * FROM $group_table WHERE id=" . $groupmeta_record->group_id;
                ;
                $group_record = $wpdb->get_row($query_group, OBJECT);

                $wpdb->insert('groups', array(
                    "groupid" => $group_record->id,
                    'url' => $group_record->slug,
                    'name' => $group_record->name,
                    'description' => $group_record->description,
                    'creatorid' => $group_record->creator_id,
                    'displaytitle' => $group_record->name,
                    'createdate' => date("Y-m-d H:i:s"),
                    'indexed' => "T",
                    'lastindexdate' => date("Y-m-d H:i:s"),
                    'indexrequired' => "F",
                ));

                $cnsr_arr = $wpdb->get_results("SELECT phrase FROM censorphrases");
                $censorphrases = count($cnsr_arr) > 0 ? $cnsr_arr : array();

                if ($this->striposa($group_record->name, $censorphrases, 1) || $this->striposa($group_record->description, $censorphrases, 1)) {
                    //groups_update_groupmeta($group_record->id, "is_spam", 1);
                    $wpdb->update('groups', array(
                        'spam' => 'T',
                        'remove' => 'T',
                        'indexrequired' => 'T',
                        'indexrequireddate' => current_time('mysql')
                            ), array(
                        "groupid" => $group_record->id,
                            ), array("%s", "%s", "%s", "%s"), array("%d")
                    );
                } else {
                    //groups_update_groupmeta($group_record->id, "is_spam", 0);
                    $wpdb->update('groups', array(
                        'spam' => 'F',
                        'remove' => 'F',
                        'indexrequired' => 'T',
                        'indexrequireddate' => current_time('mysql')
                            ), array(
                        "groupid" => $group_record->id,
                            ), array("%s", "%s", "%s"), array("%d")
                    );
                }



                if (is_array($_POST['subjectarea']) && count($_POST['subjectarea']) > 0) {
                    $subjectarea_arr = $_POST['subjectarea'];
                    foreach ($subjectarea_arr as $subject_area_id) {
                        $groupid = $group_record->id;
                        $wpdb->insert('group_subjectareas', array("groupid" => $groupid, 'subjectareaid' => $subject_area_id));
                    }
                }

                if (is_array($_POST['education_levels']) && count($_POST['education_levels']) > 0) {
                    $subjectarea_arr = $_POST['education_levels'];
                    $groupid = $group_record->id;
                    foreach ($subjectarea_arr as $education_level_id) {
                        $el_id_arr = explode('|', $education_level_id);
                        foreach ($el_id_arr as $id) {
                            $groupid = $group_record->id;
                            $wpdb->insert('group_educationlevels', array("groupid" => $groupid, 'educationlevelid' => $id));
                        }
                    }
                }
            } elseif (isset($_POST["group_id"]) && $_POST["group_id"] > 0) {


                if (is_array($_POST['subjectarea']) && count($_POST['subjectarea']) > 0) {

                    $wpdb->delete('group_subjectareas', array('groupid' => $_POST["group_id"]));

                    $subjectarea_arr = $_POST['subjectarea'];
                    foreach ($subjectarea_arr as $subject_area_id) {
                        $groupid = $_POST["group_id"];
                        $wpdb->insert('group_subjectareas', array("groupid" => $groupid, 'subjectareaid' => $subject_area_id));
                    }
                }

                if (is_array($_POST['education_levels']) && count($_POST['education_levels']) > 0) {
                    $wpdb->delete('group_educationlevels', array('groupid' => $_POST["group_id"]));

                    $subjectarea_arr = $_POST['education_levels'];
                    $groupid = $_POST["group_id"];
                    foreach ($subjectarea_arr as $education_level_id) {
                        $el_id_arr = explode('|', $education_level_id);
                        foreach ($el_id_arr as $id) {
                            $groupid = $_POST["group_id"];
                            $wpdb->insert('group_educationlevels', array("groupid" => $groupid, 'educationlevelid' => $id));
                        }
                    }
                }


                $group_table = $wpdb->prefix . "bp_groups";
                $query_group = "SELECT * FROM $group_table WHERE id=" . $_POST["group_id"];
                $group_record = $wpdb->get_row($query_group, OBJECT);

                $wpdb->update('groups', array(
                    'name' => $group_record->name,
                    'description' => $group_record->description,
                    'displaytitle' => $group_record->name
                        ), array(
                    "groupid" => $group_record->id,
                        ), array("%s", "%s", "%s"), array("%d")
                );

                $cnsr_arr = $wpdb->get_results("SELECT phrase FROM censorphrases");
                $censorphrases = count($cnsr_arr) > 0 ? $cnsr_arr : array();
                if ($this->striposa($group_record->name, $censorphrases, 1) || $this->striposa($group_record->description, $censorphrases, 1)) {
                    //groups_update_groupmeta($group_record->id, "is_spam", 1);                        
                    $wpdb->update('groups', array(
                        'spam' => 'T',
                        'remove' => 'T',
                        'indexrequired' => 'T',
                        'indexrequireddate' => current_time('mysql')
                            ), array(
                        "groupid" => $group_record->id,
                            ), array("%s", "%s", "%s", "%s"), array("%d")
                    );
                } else {
                    //groups_update_groupmeta($group_record->id, "is_spam", 0);
                    $wpdb->update('groups', array(
                        'spam' => 'F',
                        'remove' => 'F',
                        'indexrequired' => 'T',
                        'indexrequireddate' => current_time('mysql')
                            ), array(
                        "groupid" => $group_record->id,
                            ), array("%s", "%s", "%s", "%s"), array("%d")
                    );
                }
            }
        }
    }

    public function activity_querystring_filter($query_string = '', $object = '') {

        global $post;

        if ($post && $post->post_name == 'groups' && get_current_user_id() > 0 && !is_admin()) {
            $args = wp_parse_args($query_string, array(
                'action' => false,
                'type' => false,
                'user_id' => false,
                'page' => 1
            ));

            if (!isset($_GET["groups_search_submit"])) {
                $args['user_id'] = get_current_user_id();
            }

            $query_string = empty($args) ? $query_string : $args;
            return apply_filters('bp_plugin_activity_querystring_filter', $query_string, $object);
        } else {
            return $query_string;
        }
    }

    public function filter_groups_for_each_group($a, $b) {

        global $post;

        //if ($post->post_name == 'groups' && is_user_logged_in() && !is_admin()) 
        //if (is_user_logged_in() && !is_admin() ) 
        //{               
        $groups = $b->groups;

        //==== Setting the length of each group
        foreach ($groups as $key => $group) {
            if (strlen($group->name) > 45) {
                $group->name = substr($group->name, 0, 45) . "....";
            }
        }

        $groups = array_values($groups);
        $b->groups = $groups;
        //}        
        return $b;
    }

    public function curr_bp_get_groups_pagination_count() {
        global $groups_template;

        $start_num = intval(( $groups_template->pag_page - 1 ) * $groups_template->pag_num) + 1;
        $from_num = bp_core_number_format($start_num);
        $to_num = bp_core_number_format(( $start_num + ( $groups_template->pag_num - 1 ) > $groups_template->total_group_count ) ? $groups_template->total_group_count : $start_num + ( $groups_template->pag_num - 1 ) );
        $total = bp_core_number_format($groups_template->total_group_count);
        if ($total > 0) {
            return sprintf(_n('Viewing 1', 'Viewing %1$s - %2$s of %3$s groups', $total, 'buddypress'), $from_num, $to_num, $total);
        } else {
            return "You do not currently belong to any groups, please search our Groups to find something that might be interesting to you.";
        }
    }

}

function BP_Curr_Manage() {
    return new BP_Curr_Manage();
}

add_action('bp_include', 'BP_Curr_Manage');

function CurrStartSession() {
    if (!session_id()) {
        session_start();
    }
}

add_action('init', 'CurrStartSession', 1);

if (isset($_GET["action"]) && $_GET["action"] == 'ham' && isset($_GET["user"]) && isset($_GET["_wpnonce"]) && is_admin()) {
    global $wpdb;
    $wpdb->update('cur_users', array(
        'user_status' => 0
            ), array(
        "ID" => $_GET["user"],
            ), array("%d"), array("%d")
    );

    $wpdb->update('users', array(
        'spam' => 'F',
        'indexrequired' => 'T',
        'indexrequireddate' => current_time('mysql'),
        'active' => 'T',
            ), array(
        "userid" => $_GET["user"],
            ), array("%s", "%s", "%s", "%s"), array("%d")
    );
}
//======================================
/*
  function curr_group_custom_init() {
  if (!class_exists( 'BP_Group_Extension' ))
  return;

  class Crurriki_group_custom extends BP_Group_Extension {

  public function __construct() {
  $args = array(
  'slug' => 'by-email',
  'name' => 'By Email',
  );
  parent::init( $args );

  $this->name = 'My Group Extension';
  $this->slug = 'by-email-';

  $this->create_step_position = 21;
  $this->nav_item_position = 31;
  }

  public function display( $group_id = NULL ) {
  bp_core_load_template("groups/single/by-email/by-email");
  }
  public function enable_create_step() {
  return true;
  }

  }
  bp_register_group_extension( 'Crurriki_group_custom' );
  }
  add_action('bp_init', 'curr_group_custom_init'); */


if (!defined('WP_CONTENT_DIR'))
    define('WP_CONTENT_DIR', ABSPATH . 'wp-content');

//Admin menu
add_action('admin_menu', 'cur_init_admin_menus');

function cur_init_admin_menus() {
    add_options_page('Curriki Censor Phrases', 'Curriki Censor Phrases', 'manage_options', 'curriki-censor-phrases', 'curriki_res_censor_phrases');
}

function curriki_res_censor_phrases() {

    global $wpdb;
    $view = 'curriki_res_censor_phrases';
    $data = array();
    cur_load_views($view, $data);
}

function cur_load_views($view = '', $data = array()) {
    $dir = __DIR__;
    @include_once($dir . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $view . '.php');
}


add_action( 'wp_ajax_cur_fetch_google_translate', 'cur_fetch_google_translate' );

function cur_fetch_google_translate() {

    ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

	$trid = $_REQUEST["trid"];
	$lang = $_REQUEST["lang"];
        global $wpdb;
        $post_id = $wpdb->get_var(
                $wpdb->prepare( "SELECT element_id FROM {$wpdb->prefix}icl_translations WHERE trid=%d AND language_code=%s",
                                $trid,
                                $lang ) );               
                
        $post    = get_post( $post_id );
        
        $is_error = false;
        $response = new stdClass();        
        
        $output_content;
        if(!empty($post))
        {            
            require_once WP_CONTENT_DIR.'/libs/google/translate-functions.php';
            $target_lang = "es";
            $source_lang = "en";    
            if(isset($client))
            {                
                $post_content = $post->post_content;
                $post_title = $post->post_title;
                $is_error = (strlen($post_content) >= 5000) ? true : false;
                if(!$is_error)
                {                    
                    $response->is_error = $is_error;
                    $post_title = $post->post_title;
                    $post_content = $post->post_content;
                    $post_content = nl2br(preg_replace("/\n+/", "\n", $post_content));                        
                    $output_content = get_google_translated_text($client,$post_content,$target_lang,$source_lang);                                 
                    $output_title = get_google_translated_text($client,$post_title,$target_lang,$source_lang);
                    $response->output_content = $output_content;
                    $response->output_title = $output_title;
                    
                }else{
                    $post_title = $post->post_title;
                    $post_content = $post->post_content;
                    $post_content = nl2br(preg_replace("/\n+/", "\n", $post_content));                                                                    
                    
                    $content_splited = cur_content_split_for_translation($post_content);
                    $output_content = "";
                    foreach ($content_splited as $content_part) {
                        $output_content .= get_google_translated_text($client,$content_part,$target_lang,$source_lang);;
                    }
                    $response->output_content = $output_content;
                    
                    $output_title = get_google_translated_text($client,$post_title,$target_lang,$source_lang);
                    $response->output_title = $output_title;
                    
                    /*
                    if(strlen($post_content) >= 5000)
                    {
                                                
                                                
                        $post_content = nl2br(preg_replace("/\n+/", "\n", $post_content));                                                
                        $text = $post_content;
                        
                        $middle = strrpos(substr($text, 0, floor(strlen($text) / 2)), ' ') + 1;
                        $string1 = substr($text, 0, $middle);  // "The Quick : Brown Fox "
                        $string2 = substr($text, $middle);  // "Jumped Over The Lazy / Dog"                        
                        
                        $string1_tr = get_google_translated_text($client,$string1,$target_lang,$source_lang);             
                        $string2_tr = get_google_translated_text($client,$string2,$target_lang,$source_lang);                                     
                        $output_content = $string1_tr.$string2_tr;                        
                        
                        $output_title = get_google_translated_text($client,$post_title,$target_lang,$source_lang);
                        
                        $response->output_content = $output_content;
                        $response->output_title = $output_title;
                    }
                    */
                }
            }
        }
        //echo $output_content;
        echo json_encode($response);
	wp_die(); // this is required to terminate immediately and return a proper response
}



function cur_content_split_for_translation($text)
{
    $rs = array();
    if(strlen($text) >= 5000)
    {
        $middle = strrpos(substr($text, 0, floor(strlen($text) / 2)), ' ') + 1;
        $string1 = substr($text, 0, $middle);  // "The Quick : Brown Fox "        
        $r = cur_content_split_for_translation($string1);
        if(is_array($r))
        {
            foreach ($r as $str) {
                $rs[] = $str;
            }
        }  else {
            $rs[] = $r;
        }        
        
        $string2 = substr($text, $middle);  // "Jumped Over The Lazy / Dog"                        
        $r = cur_content_split_for_translation($string2);
        if(is_array($r))
        {
            foreach ($r as $str) {
                $rs[] = $str;
            }
        }  else {
            $rs[] = $r;
        }
        
    }else{
        $rs[] = $text;        
    }
    return $rs;
}
