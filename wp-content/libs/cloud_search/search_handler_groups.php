<?php

require '../../../wp-load.php';
//print_r(json_encode($_REQUEST));exit;
$endpoint = AWS_CLOUDSEARCH_END_POINT . '/2013-01-01/search?q.parser=structured&q=(';
require_once dirname(dirname(__FILE__)) . '/config.php';

//*************Making Type**************//
$type = 'Group';
$query = urlencode("and type:'$type' ");
$query .= urlencode(" active:'T' ");
$query .= urlencode(" access:'public' ");


//*************Query making***************//
if (!empty($_REQUEST['query'])) {
    $req_query = explode(',', $_REQUEST['query']);
    $query .= "(or+";
    foreach ($req_query as $q) {
        $query .= "(";
        $query .= urlencode("phrase '" . trim($q) . "'");
        $query .= ")";
    }
    $query .= ")";
}

//*************Checking Subjets**************//
if (isset($_REQUEST['subject'])) {
    $query .= "(or";
    foreach ($_REQUEST['subject'] as $sub) {
        $query .= urlencode(" subject:'$sub'");
    }
    $query .= ")";
}

//*************Checking Subjets Areas**************//
if (isset($_REQUEST['subjectarea'])) {
    $query .= "(or";
    foreach ($_REQUEST['subjectarea'] as $sub) {
        $query .= urlencode(" subjectarea:'$sub'");
    }
    $query .= ")";
}

//*************Checking Education levels**************//
if (isset($_REQUEST['education_level'])) {
    $query .= "(or";
    foreach ($_REQUEST['education_level'] as $level) {
        $level = explode('|', $level);
        foreach ($level as $l) {
            $query .= urlencode(" educationlevel:'$l'");
        }
    }
    $query .= ")";
}

if (isset($_REQUEST['language']) && trim($_REQUEST['language']) != '') {
    $query .= urlencode(" language:'" . $_REQUEST['language'] . "'");
}

$query .= ")";

//*************Sort By Feature**************//
if (!empty($_REQUEST['sort_by'])) {
    switch ($_REQUEST['sort_by']) {
        case 'title_a_z':
            $query .= "&sort=title+asc";
            break;
        case 'title_z_a':
            $query .= "&sort=title+desc";
            break;
        case 'newest':
            $query .= "&sort=createdate+desc";
            break;
        case 'oldest':
            $query .= "&sort=createdate+asc";
            break;
        case 'member_rating':
            $query .= "&sort=memberrating+desc";
            $query .= ",title+asc";
            break;
        case 'curriki_rating':
            $query .= "&sort=reviewrating+desc";
            $query .= ",title+asc";
            break;
        case 'aligned':
            $query .= "&sort=aligned+desc";
            $query .= ",title+asc";
            break;
        default://ifnull(topofsearch, 'F') desc, ifnull(partner, 'F') desc, ifnull(reviewrating, 0) desc, ifnull(memberrating, 0) desc
            $query .= "&sort=topofsearch+desc";
            $query .= ",partner+desc";
            $query .= ",reviewrating+desc";
            $query .= ",memberrating+desc";
            break;
    }
}

//**************pagination************//
if (!empty($_REQUEST['pageNumber'])) {
    $query .= "&start=" . intval(($_REQUEST['pageNumber'] - 1) * 12);
}
// setting limit
$query .= "&size=12";

//$query = 'q=(and%20educationlevel:%271%27%20subject:%27Mathematics%27)';
if (isset($_REQUEST['test']) AND $_REQUEST['test'])
    echo urldecode($endpoint . $query);
$arr = clean_json_result(json_decode(file_get_contents($endpoint . $query), 1));


if ($arr['hits']['found'] > 0) {
    $arr['hits']['found'] = $arr['hits']['found'] > 9999 ? 10000 : $arr['hits']['found'];
    foreach ($arr['hits']['hit'] as $ind => $hit) {

        if (strlen($hit['fields']['title']) > 50)
            $arr['hits']['hit'][$ind]['fields']['title'] = substr($hit['fields']['title'], 0, 50) . ' ...';
        if (strlen($hit['fields']['description']) > 150)
            $arr['hits']['hit'][$ind]['fields']['description'] = substr($hit['fields']['description'], 0, 150) . ' ...';

        $avatar_options = array("item_id" => $hit['fields']['id'], "object" => "group", "type" => "full", "avatar_dir" => "group-avatars", "alt" => "Group avatar", "css_id" => 1234, "class" => "avatar", "width" => 50, "height" => 50, "html" => false);
        $arr['hits']['hit'][$ind]['fields']['image'] = bp_core_fetch_avatar($avatar_options);

        $group = groups_get_group(array('group_id' => $hit['fields']['id']));
        $arr['hits']['hit'][$ind]['fields']['groups_users_count'] = groups_get_total_member_count($hit['fields']['id']);
        $arr['hits']['hit'][$ind]['fields']['groups_resources_count'] = cur_get_resource_total_from_group($hit['fields']['id']);
        $arr['hits']['hit'][$ind]['fields']['slug'] = $group->slug;

        $forum_id = 0;
        $forum_ids = groups_get_groupmeta($hit['fields']['id'], 'forum_id', true);
        if (is_array($forum_ids) && count($forum_ids) > 0)
            $forum_id = $forum_ids[0];

        $arr['hits']['hit'][$ind]['fields']['forum_id'] = intval($forum_id);
        $forum_count = $wpdb->get_var("SELECT count(ID) FROM {$wpdb->prefix}posts where post_type = 'topic' AND post_status = 'publish' AND post_parent = $forum_id");
        $arr['hits']['hit'][$ind]['fields']['groups_comments_count'] = ($forum_count > 0) ? $forum_count : '0';
    }
}
echo json_encode($arr);

function clean_json_result(&$array) {
    foreach ($array as $key => $ar) {
        if (is_array($ar)) {
            $array[$key] = clean_json_result($ar);
        } else {
            $array[$key] = strip_tags(htmlspecialchars_decode($ar));
        }
    }
    return $array;
}

exit;
