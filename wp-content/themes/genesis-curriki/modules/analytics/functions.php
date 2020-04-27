<?php

/*
  Created on : Mar 21, 2016, 8:58:41 PM
  Author     : waqar-muneer
  Purpose    : to manage all advance analytics
 */

function curr_log_visits() {

    global $wpdb;

    $ip_client = $_SERVER["HTTP_X_FORWARDED_FOR"];
    $host_name = gethostbyaddr($ip_client);

    $host_name_arr = explode(".", $host_name);
    $index = count($host_name_arr) - 2;
    $host_name_main = $index >= 0 ? $host_name_arr[$index] : "";

    $bot_names = cur_bot_names_to_block();
    
    if (!isset($_SERVER["HTTP_USER_AGENT"]))
        return false;

    $user_aget_words_deny = cur_browser_user_agent_words_to_deny();    
    $do_return = false;
    foreach ($user_aget_words_deny as $ag_w) {
        if (strstr($_SERVER["HTTP_USER_AGENT"], $ag_w) !== false) {
            $do_return = true;
            return;
        }
    }
    if ($do_return)
        return false;


    if (in_array($host_name_main, $bot_names))
        return false;




    if (!isset($_SERVER["HTTP_REFERER"]) && (!isset($_SESSION["last_visit_user_id"]) || (isset($_SESSION["last_visit_user_id"]) && $_SESSION["last_visit_user_id"] == 0) )) {
        //visit from external source with NO HTTP_REFERER
        $last_visitsid = record_visit();
        $_SESSION["last_visitsid"] = $last_visitsid;
        $_SESSION["last_visit_user_id"] = get_current_user_id();
    } elseif (isset($_SERVER["HTTP_REFERER"])) {

        $referer_url = parse_url($_SERVER["HTTP_REFERER"]);
        $host = $_SERVER["HTTP_HOST"];

        if ($referer_url["host"] != $host) {
            //visit from external source with HTTP_REFERER
            $data["referrer"] = $_SERVER["HTTP_REFERER"];
            $last_visitsid = record_visit($data);
            $_SESSION["last_visitsid"] = $last_visitsid;
            $_SESSION["last_visit_user_id"] = get_current_user_id();
        } elseif ($referer_url["host"] == $host) {
            //record visit when user return after login for first time after visiting session
            if (isset($_SESSION["last_visitsid"]) && intval($_SESSION["last_visit_user_id"]) == 0 && get_current_user_id() > 0) {

                $visitsid = (int) $_SESSION["last_visitsid"];

                $lv_q = $wpdb->prepare("select * from visits where visitsid = %d", $visitsid);
                $last_visit_record = $wpdb->get_row($lv_q, OBJECT);

                //============= After login if user blongs to recent session then UPDATE visit record
                if ($last_visit_record->userid == get_current_user_id() || !isset($last_visit_record->userid)) {
                    $userid = get_current_user_id();
                    update_visit_record($visitsid, $userid);
                    $_SESSION["last_visit_user_id"] = get_current_user_id();
                } elseif ($last_visit_record->userid != get_current_user_id()) {
                    //============= After login if user does not blongs to recent session then ADD visit record                    
                    $last_visitsid = record_visit();
                    $_SESSION["last_visitsid"] = $last_visitsid;
                    $_SESSION["last_visit_user_id"] = get_current_user_id();
                }
            }
        }
    }
    
    $pages_to_load_script = array("oer");
    $pagename = get_query_var('pagename');
    if ( isset($pagename) && $pagename != null && in_array($pagename, $pages_to_load_script) ) {
            if( isset($_SESSION['last_visitsid']) && isset($_SESSION['resourceid_val']) && isset($_SESSION['pageurl_val']) )
            {                           
                set_resource_views_on_resource_load( intval($_SESSION['resourceid_val']), $_SESSION['pageurl_val'], intval($_SESSION['last_visitsid']) );
            }
    }
    echo '<input type="hidden" value="'.(isset($_SESSION["last_visitsid"]) ? $_SESSION["last_visitsid"] : '0').'" name="lvid" id="lvid" />';
}

add_action('genesis_after', 'curr_log_visits', 25);

function record_visit($data = array()) {
    $user_id = get_current_user_id() > 0 ? get_current_user_id() : null;
    $referrer = isset($data["referrer"]) ? $data["referrer"] : null;
    global $wpdb;        
    
    $ip_value = ip2long($_SERVER['HTTP_X_FORWARDED_FOR']);
    $ip_value_length = strlen($ip_value);    
    $query = "SELECT * FROM geodb 
                WHERE LENGTH(ip_from) = $ip_value_length                
                and ip_from <= $ip_value and ip_to >= $ip_value";                       
    $geo_record = $wpdb->get_row($query);        
    
    $visits_data = array(
        'visitdate' => current_time("mysql"),
        'referrer' => $referrer,
        'agent' => $_SERVER["HTTP_USER_AGENT"],
        'ip' => $_SERVER['HTTP_X_FORWARDED_FOR'],
        'ip_value' => $ip_value
    );    
    $visits_data_formats = array('%s', '%s', '%s', '%s', '%d');

    if ($user_id) {
        $visits_data["userid"] = $user_id;
        $visits_data_formats[] = '%d';
    }
    
    if($geo_record){
        $visits_data["country"] = $geo_record->country_code;
        $visits_data["region"] = $geo_record->region_name;
        $visits_data["city"] = $geo_record->city_name;
        $visits_data_formats[] = '%s';
        $visits_data_formats[] = '%s';
        $visits_data_formats[] = '%s';
    }
    
    $wpdb->insert('visits', $visits_data, $visits_data_formats);
    return $wpdb->insert_id;
}

function update_visit_record($visitsid, $userid) {
    
    global $wpdb;
    $ip_value = ip2long($_SERVER['HTTP_X_FORWARDED_FOR']);
    $ip_value_length = strlen($ip_value);
        
    $query = "SELECT * FROM geodb 
                WHERE LENGTH(ip_from) = $ip_value_length                
                and ip_from <= $ip_value and ip_to >= $ip_value";                       
    $geo_record = $wpdb->get_row($query);    
    
    $visits_data = array( 'visitdate' => current_time("mysql"), 'userid' => $userid );
    $visits_data_formats = array('%s', '%d');
    
    if($geo_record){
        $visits_data["country"] = $geo_record->country_code;
        $visits_data["region"] = $geo_record->region_name;
        $visits_data["city"] = $geo_record->city_name;
        $visits_data_formats[] = '%s';
        $visits_data_formats[] = '%s';
        $visits_data_formats[] = '%s';
    }
        
    $wpdb->update( 'visits', $visits_data , array('visitsid' => $visitsid), $visits_data_formats , array('%d') );
    
}
