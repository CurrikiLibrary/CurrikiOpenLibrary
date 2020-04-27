<?php

date_default_timezone_set('UTC');
require_once dirname(__FILE__) . '/../config.php';

$aws = $vars['aws'];
$srcbucket = DBI_AWS_S3_BUCKET;
$destination = $vars['upload_path'] . 'furqan_zips/';

$s3_client = $aws->get('S3');

$files = $db->select("select rf.*
    from resources r
    inner join resourcefiles rf on r.resourceid = rf.resourceid
    where content like '%button-link-start%'
    and filename like '%.zip' ;");

echo '<pre>';
foreach ($files as $file) {
  if (file_exists($destination . $file['uniquename']))
    continue;

  $downloadResult = $s3_client->getObject(array(
      'Bucket' => $srcbucket,
      'Key' => $file['folder'] . $file['uniquename'],
      'SaveAs' => $destination . $file['uniquename']
  ));

  print_r($downloadResult);
}

exit;

$domain = 'currikiarchive';
$amazon_end_point = AWS_CLOUDSEARCH_END_POINT;


$curriki_CSD = $aws->get('CloudSearchDomain', array('base_url' => $amazon_end_point));
$s3_client = $aws->get('S3');

$srcbucket = DBI_AWS_S3_BUCKET;
$srcPrefix = 'resourcefiles/';
$destbucket = 'currikiwork';
$destPrefix = 'SDF';

//$s3_client->deleteMatchingObjects($destbucket, '', '/^.*\.(json)$/i');
//print_array($s3_client->listBuckets());
if (isset($_REQUEST['ext_mime'])) {
  $files = $db->select("SELECT * FROM resourcefiles where uniquename is not null and (s3path is null or s3path = '' )");
  foreach ($files as $file) {
    try {
      echo $file['fileid'] . ' | ';
      $objExists = $s3_client->doesObjectExist($srcbucket, $file['folder'] . $file['uniquename']);
      if ($objExists) {
        echo $plainUrl = $s3_client->getObjectUrl($srcbucket, $file['folder'] . $file['uniquename']);
        $db->update('resourcefiles'
                , array('s3path' => $db->mySQLSafe($plainUrl), 'ext' => $db->mySQLSafe(strtolower(pathinfo($plainUrl, PATHINFO_EXTENSION))))
                , 'fileid = ' . $file['fileid']);
      } else
        echo 'Not Exists';
    } catch (Exception $ex) {
      print_array($ex);
    }
    echo '<br/>';
    continue;

    if (file_exists('../../../../media/rfiles/' . $file['uniquename'])) {
      //echo $file['fileid'] . '../../../../media/rfiles/' . $file['uniquename'];
      continue;
    } else if (file_exists('../../uploads/currikicdn/' . $file['folder'] . $file['uniquename'])) {
      echo $file['fileid'] . '../../uploads/currikicdn/' . $file['folder'] . $file['uniquename'];
    } else
      echo $file['fileid'] . '../../uploads/currikicdn/' . $file['folder'] . $file['uniquename'] . ' No';
    echo '<br/>';
    continue;
    $mime_type = mime_content_type('../media/rfiles/' . $file['uniquename']);
    $ext = strtolower(pathinfo('../media/rfiles/' . $file['uniquename'], PATHINFO_EXTENSION));
    $db->update('resourcefiles', array('ext' => $db->mySQLSafe($ext)),'fileid = ' . $file['fileid']);
//echo $db->query;
  }
}

if (isset($_REQUEST['listObjects'])) {
  /* $listObjects = $s3_client->listObjects(array('Bucket' => $srcbucket, 'Prefix' => $srcPrefix));
    print_array($listObjects); */

  $listObjects = $s3_client->listObjects(array('Bucket' => $destbucket));
  print_array($listObjects);
}

//***************** Preparing XML file for uploading ******************//
if (isset($_REQUEST['converSDFfiles'])) {
  $resources = $db->select("SELECT * from resourcefiles where s3_currikiwork_SDF_url is null and uniquename is not null");

  foreach ($resources as $res) {
    $return = system('./cs_import_document.sh  s3://' . $srcbucket . '/' . $srcPrefix . $res['uniquename'] . ' s3://' . $destbucket . '/' . $destPrefix . $res['uniquename'] . ' 2>&1');
    $db->update('resourcefiles', array('s3_currikiwork_SDF_url' => $db->mySQLSafe($return)), 'fileid = ' . $res['fileid']);
    print_array($return);
    echo '<hr/>';
  }
}

//***************** Preparing XML file for uploading ******************//
if (isset($_REQUEST['start'])) {
  $_REQUEST['start'] = intval($_REQUEST['start']);
  $_REQUEST['limit'] = intval($_REQUEST['limit'] + $_REQUEST['start']);
  for ($i = $_REQUEST['start']; $i < $_REQUEST['limit']; $i++) {
    $xml_file = aws_prepare_xml('resources', $i, 1);


    $upload_result = $curriki_CSD->uploadDocuments(
            array('documents' => file_get_contents($xml_file), 'contentType' => 'application/xml'));

    echo fileSizeM($xml_file);
    echo '-><br/>';
    print_array($upload_result);
    echo '</pre>';
  }
}
//***************** Uploading XML file******************//
else if (isset($_REQUEST['delete'])) {
  $xml_file = aws_prepare_xml_del();
  $upload_result = $curriki_CSD->uploadDocuments(array('documents' => file_get_contents($xml_file), 'contentType' => 'application/xml'));
  print_array($upload_result);
}

//**************Helper Functions ***************//
//http://search-currikiarchive-7hcxrbvv6xawgvs3fva4cpucju.us-west-2.cloudsearch.amazonaws.com/2013-01-01/search?q.parser=structured&q=%28and%20matchall%29&size=1000
function aws_prepare_xml_del() {
  $file_name = '/tmp/' . time() . rand() . '.xml';
  file_put_contents($file_name, '<?xml version="1.0" encoding="UTF-8"?><batch>');
  $contents = file_get_contents(AWS_CLOUDSEARCH_END_POINT . '/2013-01-01/search?q.parser=structured&size=1003&q=' . urlencode('(and matchall)'));
  $resources = json_decode($contents);
  foreach ($resources->hits->hit as $res) {
    $res_xml = "<delete id='$res->id'></delete>";
    file_put_contents($file_name, $res_xml, FILE_APPEND);
  }
  file_put_contents($file_name, '</batch>', FILE_APPEND);
  return $file_name;
}

function aws_prepare_xml($type = 'resources', $start = 0, $limit = 10) {
  global $db;
  $file_name = '/tmp/' . time() . rand() . '.xml';
  file_put_contents($file_name, '<?xml version="1.0" encoding="UTF-8"?><batch>');

  switch ($type) {
    case 'resources':
      $resources = $db->select("SELECT 'resource' as type, 
                res.resourceid, 'http://curriki.com' as url ,res.access,res.title,res.description,res.keywords,res.generatedkeywords,
                res.content, res.language, res.currikilicense,res.resourcechecked,res.studentfacing,res.reviewrating,res.memberrating,
                res.type as resourcetype,res.active,res.mediatype, 'curriki' as site,res.partner, resf.filename, l.name as license , 
                res.contributiondate,res.aligned,'' as filecontent
                FROM resources as res 
                JOIN resourcefiles as resf on resf.resourceid = res.resourceid
                JOIN licenses as l on l.licenseid = res.licenseid
                limit $start , $limit");
      foreach ($resources as $res) {
        $res_xml = '';
        $resourceid = $res['resourceid'];
        $education_levels = $db->select("SELECT el.identifier
                    FROM resource_educationlevels rel 
                    JOIN educationlevels el on el.levelid = rel.educationlevelid
                    WHERE rel.resourceid = $resourceid");
//print_array($education_levels);

        $instruction_types = $db->select("SELECT inst.name
                    FROM resource_instructiontypes as rin
                    JOIN instructiontypes as inst on rin.instructiontypeid = inst.instructiontypeid
                    WHERE rin.resourceid = $resourceid");
//print_array($instruction_types);

        $subjects = $db->select("SELECT sb.subject, sub.subjectarea
                    FROM resource_subjectareas as rsa
                    JOIN subjectareas as sub on sub.subjectareaid = rsa.subjectareaid
                    JOIN subjects as sb on sub.subjectid = sb.subjectid
                    WHERE rsa.resourceid = $resourceid");

        $standards = $db->select("select distinct concat(s.title, '|', ifnull(s.jurisdictioncode, '-'), '|', st.notation) as standard
                    FROM standards s
                    INNER JOIN statements st on s.standardid = st.standardid
                    INNER JOIN resource_statements rs on rs.statementid = st.statementid
                    WHERE rs.resourceid = $resourceid");

//print_array($subjects);

        $res_xml = "<add id='$resourceid'>" .
                '<field name="resourceid">' . utf8_encode(htmlspecialchars($res['resourceid'])) . '</field>' .
                '<field name="url">' . utf8_encode(htmlspecialchars($res['url'])) . '</field>' .
                '<field name="access">' . utf8_encode(htmlspecialchars($res['access'])) . '</field>' .
                '<field name="title">' . utf8_encode(htmlspecialchars($res['title'])) . '</field>' .
                '<field name="description">' . utf8_encode(htmlspecialchars($res['description'])) . '</field>' .
                '<field name="keywords">' . utf8_encode(htmlspecialchars($res['keywords'])) . '</field>' .
                '<field name="generatedkeywords">' . utf8_encode(htmlspecialchars($res['generatedkeywords'])) . '</field>' .
                '<field name="content">' . utf8_encode(htmlspecialchars($res['content'])) . '</field>' .
                '<field name="language">' . utf8_encode(htmlspecialchars($res['language'])) . '</field>' .
                '<field name="currikilicense">' . utf8_encode(htmlspecialchars($res['currikilicense'])) . '</field>' .
                '<field name="resourcechecked">' . utf8_encode(htmlspecialchars($res['resourcechecked'])) . '</field>' .
                '<field name="studentfacing">' . utf8_encode(htmlspecialchars($res['studentfacing'])) . '</field>' .
                '<field name="reviewrating">' . utf8_encode(htmlspecialchars($res['reviewrating'])) . '</field>' .
                '<field name="memberrating">' . utf8_encode(htmlspecialchars($res['memberrating'])) . '</field>' .
                '<field name="contributiondate">' . date('Y-m-d\TH:i:s.u\Z', strtotime($res['contributiondate'])) . '</field>' .
                '<field name="aligned">' . utf8_encode(htmlspecialchars($res['aligned'])) . '</field>' .
                '<field name="resourcetype">' . utf8_encode(htmlspecialchars($res['resourcetype'])) . '</field>' .
                '<field name="active">' . utf8_encode(htmlspecialchars($res['active'])) . '</field>' .
                '<field name="mediatype">' . utf8_encode(htmlspecialchars($res['mediatype'])) . '</field>' .
                '<field name="site">' . utf8_encode(htmlspecialchars($res['site'])) . '</field>' .
                '<field name="partner">' . utf8_encode(htmlspecialchars($res['partner'])) . '</field>' .
                '<field name="license">' . utf8_encode(htmlspecialchars($res['license'])) . '</field>' .
                '<field name="type">Group</field>' .
                '<field name="filecontent">' . utf8_encode(htmlspecialchars(get_file_text($res['resourceid']))) . '</field>';

        if ($education_levels)
          foreach ($education_levels as $level) {
            $res_xml .= '<field name="educationlevel">' . utf8_encode(htmlspecialchars($level['identifier'])) . '</field>';
          }
        if ($instruction_types)
          foreach ($instruction_types as $type) {
            $res_xml .= '<field name="instructiontype">' . utf8_encode(htmlspecialchars($type['name'])) . '</field>';
          }
        if ($subjects)
          foreach ($subjects as $subject) {
            $res_xml .= '<field name="subject">' . utf8_encode(htmlspecialchars($subject['subject'])) . '</field>' .
                    '<field name="subjectarea">' . utf8_encode(htmlspecialchars($subject['subjectarea'])) . '</field>';
          }
        if ($standards) {
          foreach ($standards as $standard) {
            $res_xml .= '<field name="standard">' . utf8_encode(htmlspecialchars($standard['standard'])) . '</field>';
          }
        }
        $res_xml .= '</add>';
        file_put_contents($file_name, $res_xml, FILE_APPEND);
      }
      break;
    case 'users':
      break;
    case 'groups':
      break;
  }
  file_put_contents($file_name, '</batch>', FILE_APPEND);

  return $file_name;
}

function get_file_text($resourceid) {
  global $db, $s3_client, $destbucket, $destPrefix;
  if (empty($resourceid))
    return;
  $contents = '';
  $files = $db->select("SELECT * FROM resourcefiles where resourceid = " . intval($resourceid));
  foreach ($files as $file) {
    if (in_array($file['ext'], array('xml', 'json'))) {
      $contents .= file_get_contents('../media/rfiles/' . $file['uniquename']);
    } else if (strpos(' ' . $file['s3_currikiwork_SDF_url'], 'Converted field name')) {
      $result = $s3_client->getObject(array(
          // Bucket is required
          'Bucket' => $destbucket,
          'Key' => $destPrefix . $file['uniquename'] . '1.json',
      ));
      $json = json_decode($result['Body']);
      $contents .= $json[0]->fields->content;
    }
  }
  return substr(trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9 ]/', ' ', urldecode(html_entity_decode(strip_tags($contents)))))), 0, 950000);
}

function fileSizeM($path) {
  $bytes = filesize($path);
  if ($bytes > 0) {
    $unit = intval(log($bytes, 1024));
    $units = array('B', 'KB', 'MB', 'GB');

    if (array_key_exists($unit, $units) === true) {
      return sprintf('%d %s', $bytes / pow(1024, $unit), $units[$unit]);
    }
  }

  return $bytes;
}

?>