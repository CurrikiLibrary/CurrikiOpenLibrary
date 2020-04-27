<?php

$t1 = time();
//print_r(json_encode($_REQUEST));exit;
$endpoint = AWS_CLOUDSEARCH_END_POINT . '/2013-01-01/search?q.parser=structured&q=(';
require_once dirname(dirname(__FILE__)) . '/config.php';

//*************Making Type**************//
$type = 'Resource';
if (isset($_REQUEST['search_type']) && $_REQUEST['search_type'] == 'groups') {
    $type = 'Group';
} elseif (isset($_REQUEST['search_type']) && $_REQUEST['search_type'] == 'members') {
    $type = 'Member';
}
$query = urlencode("and type:'$type' ");
$query .= urlencode(" active:'T' ");
$query .= urlencode(" ( or access:'public' ) ");
$query .= urlencode(" (or currikilicense:'T' license:'CC0' ) ");



if (isset($_REQUEST['partnerid']) AND ! empty($_REQUEST['partnerid'])) {
    $query .= urlencode(" resourcegroups:'" . $_REQUEST['partnerid'] . "' ");
}

//*************Rating Partners***************//
if (isset($_REQUEST['partners'])) {
    $query .= urlencode(" partner:'T' ");
}

if (isset($_REQUEST['reviewrating'])) {
    $query .= urlencode(" (range field=reviewrating [2.0,}) ");
}

if (isset($_REQUEST['memberrating'])) {
    $query .= urlencode(" (range field=memberrating [4.0,}) ");
}


//*************Query making***************//
if (!empty($_REQUEST['query'])) {
    $query .= "(or+";
    if (is_numeric(trim($_REQUEST['query']))) {
        $query .= urlencode(" id:'" . trim($_REQUEST['query']) . "'");
    } else {
        $query .= "(";
        $query .= urlencode("phrase '" .addslashes($_REQUEST['query']) . "'");
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

//*************Checking Subjets Areas**************//
if (isset($_REQUEST['type'])) {
    $query .= "(or";
    foreach ($_REQUEST['type'] as $type) {
        $query .= urlencode(" instructiontype:'$type'");
    }
    $query .= ")";
}

//*************Checking Education levels**************//

if (isset($_REQUEST['statementid']) && is_array($_REQUEST['statementid'])) {
    $standards = $db->select("select distinct st.standardid,st.statementid ,concat(s.title, '|', ifnull(s.jurisdictioncode, '-'), '|', st.notation) as standard
                    FROM standards s
                    INNER JOIN statements st on s.standardid = st.standardid
                    where st.statementid in( " . implode(',', $_REQUEST['statementid']) . ")");

    if ($standards) {
        $query .= "(or";
        foreach ($standards as $st) {
            $query .= urlencode(" statementid:'$st[statementid]'");
        }
        $query .= ")";
    }
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

if (isset($_REQUEST['slanguage']) && trim($_REQUEST['slanguage']) != '') {
    $query .= urlencode(" language:'" . $_REQUEST['slanguage'] . "'");
}

$query .= ")";

//*************Sort By Feature**************//
if (empty($_REQUEST['sort_by'])) {
    $_REQUEST['sort_by'] = '';
}
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


//**************pagination************//
if (!empty($_REQUEST['pageNumber'])) {
    $query .= "&start=" . intval(($_REQUEST['pageNumber'] - 1) * 10);
}

$_REQUEST['test'] = isset($_REQUEST['test']) ? $_REQUEST['test'] : false;
//$query = 'q=(and%20educationlevel:%271%27%20subject:%27Mathematics%27)';
if ($_REQUEST['test'])
    echo urldecode($endpoint . $query);
//$arr = strip_tags(htmlspecialchars_decode(file_get_contents($endpoint . $query)));
//echo $json = strip_tags(html_entity_decode(preg_replace('/[[:^print:]]/', '',file_get_contents($endpoint . $query))));

$json = clean_json_result(json_decode(file_get_contents($endpoint . $query), 1));
$json['hits']['found'] = $json['hits']['found'] > 9999 ? 10000 : $json['hits']['found'];

foreach ($json['hits']['hit'] as $ind => $hit)
    if (strlen($hit['fields']['description']) > 300)
        $json['hits']['hit'][$ind]['fields']['description'] = substr($hit['fields']['description'], 0, 300) . ' <a href="http://' . $_SERVER['HTTP_HOST'] . '/' . $hit['fields']['url'] . '" target="_blank"> ... [More]</a>';

echo json_encode($json);

$t2 = time();

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

//echo $t1 . '<br/>';
//echo $t2 . '<br/>';
//echo intval($t2 - $t1) . '<br/>';

exit;
