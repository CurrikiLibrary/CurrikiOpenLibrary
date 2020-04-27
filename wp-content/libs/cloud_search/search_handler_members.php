<?php

require '../../../wp-load.php';
//print_r(json_encode($_REQUEST));exit;
$endpoint = AWS_CLOUDSEARCH_END_POINT . '/2013-01-01/search?q.parser=structured&q=(';
require_once dirname(dirname(__FILE__)) . '/config.php';

//*************Making Type**************//
$type = 'Member';
$query = urlencode("and type:'$type' ");
$query .= urlencode(" active:'T' ");
//$query .= urlencode(" access:'public' ");
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
    $query .= "&start=" . intval(($_REQUEST['pageNumber'] - 1) * 15);
}
// setting limit
$query .= "&size=15";

if (isset($_REQUEST['test']) AND $_REQUEST['test'])
    echo urldecode($endpoint . $query);
$arr = clean_json_result(json_decode(file_get_contents($endpoint . $query), 1));

$memberIDs = array();
if ($arr['hits']['found'] > 0) {
    $arr['hits']['found'] = $arr['hits']['found'] > 9999 ? 10000 : $arr['hits']['found'];

//Collecting ids and triming titles
    foreach ($arr['hits']['hit'] as $ind => $hit) {
        $memberIDs[] = $hit['fields']['id'];

        $arr['hits']['hit'][$ind]['fields']['resources_count'] = cur_get_resource_total_from_member($hit['fields']['id']);
        $arr['hits']['hit'][$ind]['fields']['groups_total'] = groups_total_groups_for_user($hit['fields']['id']);

        $follow_count = $db->select("SELECT COUNT(id) f_total FROM cur_bp_follow WHERE follower_id = '" . $hit['fields']['id'] . "'");
        $arr['hits']['hit'][$ind]['fields']['friends_total'] = $follow_count[0]['f_total'];

        $total_topics = $db->select("select count(*) total_topics from cur_posts where post_type = 'topic' and post_author = '" . $hit['fields']['id'] . "';");
        $arr['hits']['hit'][$ind]['fields']['topics_count'] = $total_topics[0]['total_topics'];

        $arr['hits']['hit'][$ind]['fields']['resources_count'] = ($arr['hits']['hit'][$ind]['fields']['resources_count'] > 0) ? $arr['hits']['hit'][$ind]['fields']['resources_count'] : '0';
        $arr['hits']['hit'][$ind]['fields']['friends_total'] = ($arr['hits']['hit'][$ind]['fields']['friends_total'] > 0) ? $arr['hits']['hit'][$ind]['fields']['friends_total'] : '0';
        $arr['hits']['hit'][$ind]['fields']['groups_total'] = ($arr['hits']['hit'][$ind]['fields']['groups_total'] > 0) ? $arr['hits']['hit'][$ind]['fields']['groups_total'] : '0';
        $arr['hits']['hit'][$ind]['fields']['topics_count'] = ($arr['hits']['hit'][$ind]['fields']['topics_count'] > 0) ? $arr['hits']['hit'][$ind]['fields']['topics_count'] : '0';

        $arr['hits']['hit'][$ind]['fields']['uniquavatarfile'] = '';
    }

    //Getting users from database based on collected ids
    if (is_array($memberIDs) && count($memberIDs)) {
        $members = $db->select("SELECT * FROM users where userid in (" . implode(',', $memberIDs) . ")");
        $membersArr = array();
        if ($members) {
            foreach ($members as $mem) {
                $membersArr[$mem['userid']] = $ma['uniqueavatarfile'];
            }

            //Setting avatars
            foreach ($arr['hits']['hit'] as $ind => $hit) {
                $arr['hits']['hit'][$ind]['fields']['uniquavatarfile'] = $membersArr[$hit['fields']['id']];
            }
        }
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
