<?php

/*
 * Template Name: API 1.0 Template
 *
 * Child Theme Name: Curriki Child Theme for Genesis 2.1
 * Author: Muhammad Furqan Aziz
 * Url: http://orangeblossommedia.com/
 */

//**************Setting Default Values**************//
$version = '1.0';
$returns = array('xml', 'json'); //csv,html, etc
$types = array('resource');
$request = array();
$response = array();
$status = array(
    'datetime' => date('Y-m-d H:i:s'),
    'found' => 0,
    'start' => 0,
    'returned' => 0,
    'error' => ''); //Setting Default Response
require_once "./wp-content/libs/functions.php";
$endpoint = AWS_CLOUDSEARCH_END_POINT . '/2013-01-01/search?q.parser=structured&q=(';


//Fetching Partner ID
global $wpdb;
$partner = $wpdb->get_row(sprintf("SELECT * from partners WHERE partnerid = '%s'", $_GET['partnerid']), ARRAY_A);


//Validating Partner
if (empty($partner))
  $status['error'] = 'Please specify valid partner ID.';
else {
  if ($partner['active'] != 'T')
    $status['error'] = 'Specified partner is not active, Please contact admin.';
  elseif ($partner['apiversion'] != $version)
    $status['error'] = 'Specified partner is not having access to this API version, You only can access api-' . $partner['apiversion'];
  else {
    //Fetching partner request response fields
    $request_params = $wpdb->get_results(sprintf("SELECT * from apiparams WHERE partnerid = '%s' AND type = 'request' AND active = 'T' AND apiversion = '%s'", $_GET['partnerid'], $version), ARRAY_A);
    foreach ($request_params as $p) {
      //Filtering Request Fields
      $request[$p['name']] = isset($_GET[$p['name']]) ? $_GET[$p['name']] : $p['value'];

      //Required Request Fields
      if ($p['required'] == 'T' && !isset($_GET[$p['name']])) {
        $status['error'] = $p['error'];
        break;
      }
    }

    //Prepare AWS Query
    if (empty($status['error'])) {
      //**************Setting education Levels **************//
      if (!isset($request['level']) OR empty($request['level']))
        $request['level'] = array();
      else if (is_array($request['level']))
        $request['level'] = array_unique(array_filter($request['level']));
      else
        $request['level'] = array($request['level']);

      //**************Setting education Levels **************//
      if (!isset($request['subject']) OR empty($request['subject']))
        $request['subject'] = array();
      else if (is_array($request['subject']))
        $request['subject'] = array_unique(array_filter($request['subject']));
      else
        $request['subject'] = array($request['subject']);

      //**************Setting education Levels **************//
      if (!isset($request['subjectarea']) OR empty($request['subjectarea']))
        $request['subjectarea'] = array();
      else if (is_array($request['subjectarea']))
        $request['subjectarea'] = array_unique(array_filter($request['subjectarea']));
      else
        $request['subjectarea'] = array($request['subjectarea']);

      //**************Preparing Query**************//
      $query = urlencode("and type:'" . ucwords($request['type']) . "' ");
      $query .= urlencode(" active:'T' ");
      if ((isset($request['partnerid']) AND ! empty($request['partnerid'])) AND ( !isset($request['search_all']) OR $request['search_all'] != 'T'))
        $query .= urlencode("(or resourcegroups:'" . $request['partnerid'] . "' )");
      $query .= urlencode(" ( not access:'private' )");
      $query .= urlencode(" ( or currikilicense:'T' license:'CC0' )");

      //*************Rating Partners***************//
      if (isset($request['reviewrating']) && $request['reviewrating'] == 'T') {
        $query .= urlencode(" (range field=reviewrating [2.0,}) ");
      }

      if (isset($request['memberrating']) && $request['reviewrating'] == 'T') {
        $query .= urlencode(" (range field=memberrating [4.0,}) ");
      }

      //*************Query making***************//
      if (!empty($request['query'])) {
        $query .= "(or+";
        if (is_numeric(trim($request['query']))) {
          $query .= urlencode(" id:'" . trim($request['query']) . "'");
        } else {
          $req_query = explode(',', str_replace(array('"', "'"), array('', ''), $request['query']));
          foreach ($req_query as $q) {
            $query .= "(";
            $query .= urlencode("phrase '" . trim($q) . "'");
            $query .= ")";
          }
        }
        $query .= ")";
      }

      //*************Checking Subjets**************//
      if (!empty($request['subject'])) {
        $query .= "(or";
        foreach ($request['subject'] as $sub) {
          $query .= urlencode(" subject:'$sub'");
        }
        $query .= ")";
      }

      //*************Checking Subjets Areas**************//
      if (!empty($request['subjectarea'])) {
        $query .= "(or";
        foreach ($request['subjectarea'] as $sub) {
          $query .= urlencode(" subjectarea:'$sub'");
        }
        $query .= ")";
      }

      //*************Checking Education levels**************//
      if (!empty($request['level'])) {
        $query .= "(or";
        foreach ($request['level'] as $level) {
          $level = explode('|', $level);
          foreach ($level as $l) {
            $query .= urlencode(" educationlevel:'$l'");
          }
        }
        $query .= ")";
      }

      //*************Checking Language**************//
      if (isset($request['language']) && trim($request['language']) != '') {
        $query .= urlencode(" language:'" . $request['language'] . "'");
      }

      $query .= ")";

      //*************Sort By Feature**************//
      if (empty($request['sortby'])) {
        $request['sortby'] = '';
      }
      switch ($request['sortby']) {
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
      if (!empty($request['start']))
        $query .= "&start=" . intval($request['start']);
      if (!empty($request['size']))
        $query .= "&size=" . intval($request['size']);

      //**************Sending Query************//
      if ($_GET['test']) {
        echo $endpoint . $query;
        echo '<br/>';
        echo urldecode($endpoint . $query);
      }
      try {
        $arr = json_decode(strip_tags(htmlspecialchars_decode(file_get_contents($endpoint . $query))), true);

        $status = array_merge($arr['status'], $status);
        $status['found'] = $arr['hits']['found'];
        $status['start'] = $arr['hits']['start'];
        $status['returned'] = count($arr['hits']['hit']);

        $response_params = $wpdb->get_results(sprintf("SELECT * from apiparams WHERE partnerid = '%s' AND type = 'response' AND active = 'T' AND apiversion = '%s'", $_GET['partnerid'], $version), ARRAY_A);

        foreach ($arr['hits']['hit'] as $key => $value) {
          if (isset($request['embed']) && $request['embed'] == 'T')
            $value['fields']['url'] .= '?viewer=embed';
          $row = array();
          foreach ($response_params as $p) //Filtering Response Fields
            $row[$p['name']] = isset($value['fields'][$p['name']]) ? $value['fields'][$p['name']] : $p['value'];
          $response[] = $row;
        }
      } catch (Exception $ex) {
        print_r($ex);
      }
    }
  }
}

//Making Output
$return = array(
    'status' => $status,
    'request' => $request,
    'response' => $response
);

//Responding Output
//if ($_GET['test']) {
//  print_array($return, 1);
//} else 
if ($request['return'] == 'json') {
  header('Content-Type: application/json');
  echo json_encode($return);
} else if ($request['return'] == 'xml') {
  header("Content-type: text/xml; charset=utf-8");
  // creating object of SimpleXMLElement
  $xml = new SimpleXMLElement('<?xml version="1.0"?><' . $request['type'] . 's></' . $request['type'] . 's>');
  // function call to convert array to xml
  array_to_xml($return, $xml, $level = 0);
  //saving generated xml file; 
  echo $result = $xml->asXML();
}

// function defination to convert array to xml
function array_to_xml($data, &$xml_data, $level) {
  global $request;
  foreach ($data as $key => $value) {
    if (is_array($value)) {
      if (is_numeric($key) AND $level == 1) {
        $key = $request['type']; //dealing with <0/>..<n/> issues
      }
      if (in_array($key, array('educationlevel', 'level', 'instructiontype', 'instructiontype', 'subject', 'subjectarea', 'standard', 'statementid')))
        foreach ($value as $v)
          $xml_data->addChild("$key", htmlspecialchars("$v"));
      else {
        $subnode = $xml_data->addChild($key);
        array_to_xml($value, $subnode, $level + 1);
      }
    } else {
      $xml_data->addChild("$key", htmlspecialchars("$value"));
    }
  }
}
