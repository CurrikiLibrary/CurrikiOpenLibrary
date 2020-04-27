<?php
/*
  Created on : Mar 21, 2016, 8:58:41 PM
  Author     : furqanaziz
  Purpose    : to manage search module functionality
 */

class search {

    //Misc Variables
    public $wpdb;
    public $request = array();
    public $partnerid;
    public $current_language = "eng";

    //Constructor
    public function search() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->request = array_merge($this->request, $_GET);
        $this->partnerid = (isset($this->request['partnerid']) AND ! empty($this->request['partnerid'])) ? intval($this->request['partnerid']) : 0;
        
        $this->current_language = "eng";
        if( defined('ICL_LANGUAGE_CODE') )
            $this->current_language = cur_get_current_language(ICL_LANGUAGE_CODE);         
    }

    //Search API Functions
    public function curriki_search_api20_init() {
        
        
        $this->version = '2.0';
        $request = array('type' => 'Resource', 'query' => '', 'start' => '0', 'size' => '10', 'sort' => 'rank1 desc');
        
        $this->partner = $this->wpdb->get_row(sprintf("SELECT * from partners WHERE partnerid = '%s'", $this->partnerid), ARRAY_A);
        foreach ($this->wpdb->get_results(sprintf("SELECT * from apiparams WHERE partnerid = '%s' AND array = 'request' AND type = '%s' AND active = 'T' AND apiversion = '%s'", $this->partnerid, (isset($this->request['type']) ? $this->request['type'] : $request['type']), $this->version), ARRAY_A) as $apiparam)
            $request[$apiparam['name']] = $apiparam['value'];
        
        $this->request = array_merge($request, $this->request);
        $this->request['type'] == ucwords(strtolower($this->request['type']));
        
        if(substr( $this->request['type'] , 0, 8 ) == "Resource"){
            $this->request['type'] = 'Resource';
        } elseif(substr( $this->request['type'] , 0, 5 ) == 'Group'){
            $this->request['type'] = 'Group';
        } elseif(substr( $this->request['type'] , 0, 6 ) == 'Member'){
            $this->request['type'] = 'Member';
        }
        
        $this->response = array();
        $this->status = array(
            'error' => '',
            'datetime' => date('Y-m-d H:i:s'),
            'found' => 0,
            'start' => 0,
            'returned' => 0,
        ); //Setting Default Response

        $this->awsSearchEndPoint = AWS_CLOUDSEARCH_END_POINT . '/2013-01-01/search?q.parser=lucene&q.options={defaultOperator: \'or\'}&q=';
        search::searches_table_entry($this->request['phrase']);
    }

    public function curriki_search_api20_auth() {
        if (empty($this->partner))
            $this->status['error'] = 'Please specify valid partner ID.';
        elseif ($this->partner['active'] != 'T')
            $this->status['error'] = 'Specified partner is not active, Please contact admin.';
        elseif ($this->partner['apiversion'] != $this->version)
            $this->status['error'] = 'Specified partner is not having access to this API version, You only can access api-' . $this->partner['apiversion'];
    }

    public function curriki_search_api20_make_query() {
        if (!empty($this->status['error']))
            return false;

        //Query Parameters
        $this->query = $this->awsSearchEndPoint;
        $this->query .= "(";
        $this->query .= " (active:\"T\")";
        $this->query .= " AND type:\"" . $this->request['type'] . "\" ";

        if ($this->request['type'] != 'Member')
            $this->query .= " NOT access:\"private\" ";

        if (isset($this->request['query']) && !empty($this->request['query']))
            $this->query .= " AND " . stripslashes($this->request['query']);

        if (!isset($this->request['searchall']))
            $this->query .= " AND ( resourcegroups:\"" . $this->partnerid . "\" )";

        if ($this->request['type'] == 'Resource')
            $this->query .= " AND ( currikilicense:\"T\" OR license:\"CC0\" )";

        $this->query .= ")";
        //Extra Parameters
        if (!empty($this->request['start']))
            $this->query .= '&start=' . $this->request['start'];
        if (!empty($this->request['size']))
            $this->query .= '&size=' . $this->request['size'];
        if (!empty($this->request['sort']))
            $this->query .= '&sort=' . $this->request['sort'];

        $this->query = str_replace(' ', '%20', $this->query);
        if (isset($_GET['getquery'])) {
            //echo urldecode($this->query) . '
            echo $this->query . '
          ';
            exit;
        }

        $output = file_get_contents($this->query, false, stream_context_create(array('http' => array('ignore_errors' => true))));

        $output = json_decode($output, true);
        if (isset($output['error'])) {
            $this->status['error'] = (isset($output['message']) ? $output['message'] : 'There is something wrong with your query.');
            return false;
        }

        $this->status = array_merge($output['status'], $this->status);
        $this->status['found'] = $output['hits']['found'];
        $this->status['start'] = $output['hits']['start'];
        $this->status['returned'] = count($output['hits']['hit']);

        $output['hits']['hit'] = call_user_func(array($this, "curriki_search_api20_extra_fields_" . strtolower($this->request['type'])), array($output['hits']['hit']));
        $response_params = $this->wpdb->get_results(sprintf("SELECT * from apiparams WHERE partnerid = '%s' AND array = 'response' AND type = '%s' AND active = 'T' AND apiversion = '%s'", $this->partnerid, (isset($this->request['type']) ? $this->request['type'] : 'Resource'), $this->version), ARRAY_A);

        foreach ($output['hits']['hit'] as $key => $value) {
            if (isset($this->request['viewer']) && $this->request['viewer'] == 'embed')
                $value['fields']['url'] .= '?viewer=embed';
            $row = array();
            foreach ($response_params as $p) { //Filtering Response Fields
                $row[$p['name']] = isset($value['fields'][$p['name']]) ? $value['fields'][$p['name']] : $p['value'];
                if (is_array($row[$p['name']]))
                    $row[$p['name']] = array_unique($row[$p['name']]);
            }
            $this->response[] = $row;
        }
    }

    public function curriki_search_api20_make_topofsearch_query() {
        if (!empty($this->status['error']))
            return false;

        //Query Parameters
//        $this->awsSearchEndPoint = AWS_CLOUDSEARCH_END_POINT . '/2013-01-01/search?q.parser=lucene&q.options={fields: [\'title^8\',\'keywords^2\', \'site^0.5\', \'subjectarea^0.5\', \'instructiontype^0.5\', \'subject^0.5\', \'license^0.5\', \'educationlevel^0.5\', \'access^0.5\', \'reviewstatus^0.5\', \'subsubjectarea^0.5\', \'type^0.5\', \'currikilicense^0.5\', \'description^0.5\', \'firstname^0.5\', \'lastname^0.5\', \'mediatype^0.5\', \'standard^0.5\', \'active^0.5\', \'generatedkeywords^0.5\', \'aligned^0.5\', \'language^0.5\', \'url^0.5\', \'topofsearch^0.5\', \'fullname^0.5\', \'filecontent^0.5\',\'content^0.5\'],defaultOperator: \'or\'}&q=';
        $this->awsSearchEndPoint = AWS_CLOUDSEARCH_END_POINT . '/2013-01-01/search?q.parser=lucene&q.options={fields: [\'title^4\', \'description^2.5\',\'keywords^3\', \'filecontent^1\',\'content^1\'],defaultOperator: \'or\'}&q=';
        $this->query = $this->awsSearchEndPoint;
        $this->query .= "(";
//        $this->query .= " (active:\"T\")";
        if($this->request['type'] == 'Resource'){
            $this->query .= '(active:"T"  ';
            if (isset($this->current_user->caps['administrator'])) { // if admin
                $this->query .= '  OR active:"F" ';
            }
            $this->query .= ' ) ';

            if($this->request['resourcetype'] == "resource"){
                $this->query .= ' AND (resourcetype:"resource") ';
            } elseif($this->request['resourcetype'] == "collection") {
                $this->query .= ' AND (((resourcetype:"collection") AND NOT title:"Favorites")) ';
            } else {
                $this->query .= ' AND (((resourcetype:"collection") AND NOT title:"Favorites") OR resourcetype:"resource") ';
            }

            $this->query .= ' AND (((resourcetype:"collection") AND NOT title:"Favorites") OR resourcetype:"resource") ';
//            if (isset($this->current_user->caps['administrator']) || $this->request['partnerid']== 40001 ) {
//                if(isset($this->request['approvalstatus']) && $this->request['approvalstatus'] != ""){
//                    $this->query .= " AND ( approvalstatus:\"" . $this->request['approvalstatus'] . "\" )";
//                }
//            } else {
                $this->query .= " AND ( approvalstatus:\"approved\" )";
//            }
            if (isset($this->request['query']) && !empty($this->request['query'])){
//                $sql = "SELECT * FROM searchterms WHERE term = '".$this->request['partnerid']."' and partnerid = ".$this->request['partnerid'];
                
//                $term_results = $this->wpdb->get_results( 
//                    $this->wpdb->prepare("
//                                SELECT * FROM searchterms
//                                WHERE searchterms.term = %s",
//                            $this->request['phrase']) 
//                 );
                $term_results = $this->wpdb->get_results( 
                    $this->wpdb->prepare("
                                SELECT * FROM partners
                                INNER JOIN searchterms
                                ON partners.partnerid = searchterms.partnerid
                                WHERE searchterms.term = %s
                                AND partners.active = 'T'
                                AND searchterms.active = 'T'
                                AND NOW() BETWEEN searchterms.termstartdate AND DATE_ADD(searchterms.termenddate, INTERVAL 1 DAY)
                                AND NOW() BETWEEN partners.searchstartdate AND DATE_ADD(partners.searchenddate, INTERVAL 1 DAY)
                                ",
                            $this->request['phrase']) 
                 );
                if(is_array($term_results) && count($term_results) > 0){
                    if($_REQUEST['start'] == 0){
                        $this->ad_query = $this->query . ' AND contributorid: "'.$term_results[0]->contributorid.'" ';
                    }
                }
//                echo "<pre>";
                
//                print_r($this->request['partnerid']);
//                print_r($this->request['query']);
//                die();
//                if($this->request['query'] == "(\"Tragedy of the Commons\")"){
//                    if($_REQUEST['start'] == 0){
//                        $this->ad_query = $this->query . ' AND contributorid : "534974" ';
//                    }
//                }
            }
        } else {
            $this->query .= " (active:\"T\")";
            if (isset($this->current_user->caps['administrator']) || $this->request['partnerid']== 40001) {
                if(isset($this->request['groupspam']) && $this->request['groupspam'] != ""){
                    $this->query .= " AND ( groupspam:\"" . $this->request['groupspam'] . "\" )";
                } 
            } else {
                $this->query .= " AND ( groupspam:\"F\" )";
            }
            $this->query .= " AND ( type:\"" . $this->request['type'] . "\" )";
        }
        // Only show studentfacing = T when subdomain is studentsearch
         if(isset($this->request['studentfacing']) && $this->request['studentfacing'] != ""):
            $this->query .= " AND studentfacing:\"".$this->request['studentfacing']."\"";
//            if(isset($this->ad_query)){
//                $this->ad_query .= " AND studentfacing:\"T\"";
//            }
        endif;
        
        
//        $this->query .= " NOT title:\"Favorites\"";
//        $this->query .= " (topofsearch:\"T\")";
//        $this->query .= " AND ( type:\"" . $this->request['type'] . "\" )";

        if ($this->request['type'] != 'Member'){
            $this->query .= " NOT access:\"private\" ";
            if(isset($this->ad_query)){
                $this->ad_query .= " NOT access:\"private\" ";
            }
        }

        if (isset($this->request['query']) && !empty($this->request['query'])){
            $this->query .= " AND " . stripslashes($this->request['query']);
            if(isset($this->ad_query)){
                $this->ad_query .= " AND " . stripslashes($this->request['query']);
            }
        }
            

        if (!isset($this->request['searchall'])){
            $this->query .= " AND ( resourcegroups:\"" . $this->partnerid . "\" )";
            if(isset($this->ad_query)){
                $this->ad_query .= " AND ( resourcegroups:\"" . $this->partnerid . "\" )";
            }
        }

//        if ($this->request['type'] == 'Resource')
//            $this->query .= " AND ( currikilicense:\"T\" OR license:\"CC0\" )";


//        $this->query .= ")&expr.rank1=(topofsearchint%2B_score)";
        
        $expression = '0.7*_score+0.3*(reviewrating+2.75*topofsearchint+2.0*partnerint+0.5*memberrating)';
        $expression = str_replace('+', '%2B', $expression);
        $this->query .= ")&expr.rank1=($expression)";
        if(isset($this->ad_query)){
            $this->ad_query .= ")&expr.rank1=($expression)";
        }
        //Extra Parameters
        if (!empty($this->request['start']))
            $this->query .= '&start=' . $this->request['start'];
        if (!empty($this->request['size']))
            $this->query .= '&size=' . $this->request['size'];
        if (!empty($this->request['sort']))
            $this->query .= '&sort='.$this->request['sort'];
        else
            $this->query .= '&sort=rank1 desc';
        
        if(isset($this->ad_query)){
            $this->ad_query .= "&start=0&size=3&sort=rank1 desc";
        }

        $this->query = str_replace(' ', '%20', $this->query);
        if(isset($this->ad_query)){
            $this->ad_query = str_replace(' ', '%20', $this->ad_query);
        }
        $return_val = '&return=rank1,';
        if($this->request['return']){
            $return_val .= $this->request['return'];
        } else {
            $return_val .= 'title,_score,resourcechecked,resourcetype,site,subjectarea,studentfacing,filecontent,instructiontype,keywords,partner,id,subsubjectarea,content,educationlevel,access,subject,type,currikilicense,description,firstname,lastname,createdate,mediatype,language,active,contributorid,aligned,memberrating,license,reviewstatus,url,topofsearch,reviewrating,fullname,generatedkeywords,topofsearchint,partnerint,avatarfile,approvalstatus,groupspam,thumb_image';
        }
        
        $this->query .=$return_val;
//        if($_REQUEST['testx']){
//            echo $this->query;
//            die();
//            
//        }
        if(isset($this->ad_query)){
            $this->ad_query .=$return_val;
        }
        
        $output = file_get_contents($this->query, false, stream_context_create(array('http' => array('ignore_errors' => true))));
        
        if(isset($this->ad_query)){
            $output_ads = file_get_contents($this->ad_query, false, stream_context_create(array('http' => array('ignore_errors' => true))));
        }
        
        $output = json_decode($output, true);
        if(isset($this->ad_query)){
            $output_ads = json_decode($output_ads, true);
        }
        
        if (isset($output['error'])) {
            $this->status['error'] = (isset($output['message']) ? $output['message'] : 'There is something wrong with your query.');
            return false;
        }
        if (isset($_GET['getquery'])) {
            //echo urldecode($this->query) . '
            echo $this->query . '
          ';
            exit;
        }
        if (isset($_GET['getadquery'])) {
            //echo urldecode($this->query) . '
            echo $this->ad_query . '
          ';
            exit;
        }
        if (isset($_GET['getadquery'])) {
            //echo urldecode($this->query) . '
            echo $this->ad_query . '
          ';
            exit;
        }
        
        
        if(isset($output['status']) && is_array($output['status'])){
            $this->status = array_merge($output['status'], $this->status);
        }
        
        $this->status['found'] = $output['hits']['found'];
        $this->status['start'] = $output['hits']['start'];
        $this->status['returned'] = count($output['hits']['hit']);
        
        $output['hits']['hit'] = call_user_func(array($this, "curriki_search_api20_extra_fields_" . strtolower($this->request['type'])), array($output['hits']['hit']));
        if(isset($this->ad_query)){
            $output_ads['hits']['hit'] = call_user_func(array($this, "curriki_search_api20_extra_fields_" . strtolower($this->request['type'])), array($output_ads['hits']['hit']));
        }
        $response_params = $this->wpdb->get_results(sprintf("SELECT * from apiparams WHERE partnerid = '%s' AND array = 'response' AND type = '%s' AND active = 'T' AND apiversion = '%s'", $this->partnerid, (isset($this->request['type']) ? $this->request['type'] : 'Resource'), $this->version), ARRAY_A);
        
        if (isset($this->current_user->caps['administrator']) ||  $this->request['partnerid']== 40001) { // if admin then add approvalstatus to the response params
            $response_params[] = ['paramid' => 28,
            'partnerid' => 1,
            'array' => 'response',
            'type' => 'Resource',
            'name' => 'currentApprovalStatus',
            'value' => '',
            'required' => 'F',
            'error' => '',
            'active' => 'T',
            'apiversion' => '2.0'];
            $response_params[] = ['paramid' => 29,
            'partnerid' => 1,
            'array' => 'response',
            'type' => 'Resource',
            'name' => 'approvalstatus',
            'value' => '',
            'required' => 'F',
            'error' => '',
            'active' => 'T',
            'apiversion' => '2.0'];
            $response_params[] = ['paramid' => 30,
            'partnerid' => 1,
            'array' => 'response',
            'type' => 'Resource',
            'name' => 'currentGroupSpam',
            'value' => '',
            'required' => 'F',
            'error' => '',
            'active' => 'T',
            'apiversion' => '2.0'];
            $response_params[] = ['paramid' => 31,
            'partnerid' => 1,
            'array' => 'response',
            'type' => 'Resource',
            'name' => 'groupspam',
            'value' => '',
            'required' => 'F',
            'error' => '',
            'active' => 'T',
            'apiversion' => '2.0'];
        $response_params[] = ['paramid' => 32,
            'partnerid' => 1,
            'array' => 'response',
            'type' => 'Resource',
            'name' => 'standard',
            'value' => '',
            'required' => 'F',
            'error' => '',
            'active' => 'T',
            'apiversion' => '2.0'];
        $response_params[] = ['paramid' => 32,
            'partnerid' => 1,
            'array' => 'response',
            'type' => 'Resource',
            'name' => 'id',
            'value' => '',
            'required' => 'F',
            'error' => '',
            'active' => 'T',
            'apiversion' => '2.0'];
        $response_params[] = ['paramid' => 33,
            'partnerid' => 1,
            'array' => 'response',
            'type' => 'Resource',
            'name' => 'userlocation',
            'value' => '',
            'required' => 'F',
            'error' => '',
            'active' => 'T',
            'apiversion' => '2.0'];
        $response_params[] = ['paramid' => 34,
            'partnerid' => 1,
            'array' => 'response',
            'type' => 'Resource',
            'name' => 'usermembertype',
            'value' => '',
            'required' => 'F',
            'error' => '',
            'active' => 'T',
            'apiversion' => '2.0'];
        }
        
        if(isset($this->ad_query)){
            foreach ($output_ads['hits']['hit'] as $key => $value) {
                if (isset($this->request['viewer']) && $this->request['viewer'] == 'embed')
                    $value['fields']['url'] .= '?viewer=embed';
                $row = array();
                foreach ($response_params as $p) { //Filtering Response Fields
                    $row[$p['name']] = isset($value['fields'][$p['name']]) ? $value['fields'][$p['name']] : $p['value'];
                    if (is_array($row[$p['name']]))
                        $row[$p['name']] = array_unique($row[$p['name']]);
                }
                if(isset($_GET['score'])){
                    $row['_score'] = $value['fields']['_score'];
                }
                $row['rank1'] = $value['exprs']['rank1'];
                $row['is_ad'] = true;
                $this->response[] = $row;
            }
        }
        if(isset($output['hits']['hit']) && is_array($output['hits']['hit'])){
            foreach ($output['hits']['hit'] as $key => $value) {
                if (isset($this->request['viewer']) && $this->request['viewer'] == 'embed')
                    $value['fields']['url'] .= '?viewer=embed';
                $row = array();

                foreach ($response_params as $p) { //Filtering Response Fields
                    
                    if((isset($value['fields'][$p['name']])) && !(is_array(($value['fields'][$p['name']])))){
                        $row[$p['name']] = isset($value['fields'][$p['name']]) ? $value['fields'][$p['name']] : $p['value'];
                        if (is_array($row[$p['name']]))
                            $row[$p['name']] = array_unique($row[$p['name']]);
                    } elseif(count($value['fields'][$p['name']]) > 0) {
                        $row[$p['name']] = isset($value['fields'][$p['name']]) ? $value['fields'][$p['name']] : $p['value'];
                        if (is_array($row[$p['name']]))
                            $row[$p['name']] = array_unique($row[$p['name']]);
                    }
                    
                }
                if(isset($_GET['score'])){
                    $row['_score'] = $value['fields']['_score'];
                }
                if(isset($value['fields']['thumb_image'])){
                    $row['thumb_image'] = $value['fields']['thumb_image'];
                }
                
                $row['rank1'] = $value['exprs']['rank1'];
                $this->response[] = $row;
            }
        }
        if(isset($_REQUEST['output']) && $_REQUEST['output'] === 'json'){
            header('Content-Type: application/json');
            $response = new stdClass();
            $response->response = $this->response;
            $response->languages = $this->languages;
            $response->subjects = $this->subjects;
            $response->subjectareas = $this->subjectareas;
            $response->instructiontypes = $this->instructiontypes;
            $response->educationlevels = $this->educationlevels;
            $response->jurisdictioncode = $this->jurisdictioncode;
            $response->standardtitles = $this->standardtitles;            
            $response->request = $this->request;            
            $response->status = $this->status;            
            echo json_encode($response);
            //echo json_encode($this->response);
            die();
        }
    }

    public function curriki_search_api20_print_output() {
        $this->return = $this->curriki_search_api20_clean_json(
                array(
                    'status' => $this->status,
                    'request' => $this->request,
                    'response' => $this->response
                )
        );

        if ($this->request['format'] == 'xml') {
            header("Content-type: text/xml; charset=utf-8");
            // creating object of SimpleXMLElement
            $xml = new SimpleXMLElement('<?xml version="1.0"?><' . $this->request['type'] . 's></' . $this->request['type'] . 's>');
            // function call to convert array to xml
            $xml->addChild("title", 'Search API 2.0');
            $this->curriki_search_api20_array_to_xml($this->return, $xml, $level = 0);
            //saving generated xml file; 
            echo $xml->asXML();
        } else {
            header('Content-Type: application/json');
            echo json_encode($this->return);
        }
    }

    public function curriki_search_api20_clean_json($array) {
        foreach ($array as $key => $ar) {
            if (is_array($ar)) {
                $array[$key] = $this->curriki_search_api20_clean_json($ar);
            } else {
                $array[$key] = strip_tags(htmlspecialchars_decode($ar));
            }
        }
        return $array;
    }

    public function curriki_search_api20_array_to_xml($data, &$xml_data, $level) {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if (is_numeric($key) AND $level == 1) {
                    $key = $this->request['type']; //dealing with <0/>..<n/> issues
                }
                if (in_array($key, array('educationlevel', 'level', 'instructiontype', 'subject', 'subjectarea', 'subsubjectarea', 'resourcegroups', 'standard', 'statementid', 'standardidentifier', 'statement', 'collectionelement')))
                    foreach ($value as $v)
                        $xml_data->addChild("$key", htmlspecialchars("$v"));
                else {
                    $subnode = $xml_data->addChild($key);
                    $this->curriki_search_api20_array_to_xml($value, $subnode, $level + 1);
                }
            } else {
                $xml_data->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }

    public function curriki_search_api20_extra_fields_resource($output) {
        $output = $output[0];
        if(isset($output) && is_array($output)){
            foreach ($output as $ind => $row) {
                $output[$ind]['fields']['currentApprovalStatus'] = $this->wpdb->get_var("select approvalStatus from resources where resourceid = " . $row['fields']['id']);
                $output[$ind]['fields']['resourceviews'] = $this->wpdb->get_var("select count(*) from resourceviews where resourceid = " . $row['fields']['id']);
                $output[$ind]['fields']['collections'] = $this->wpdb->get_var("select count(*) from collectionelements where resourceid = " . $row['fields']['id']);
                if (isset($output[$ind]['fields']['license'])) {
                    $output[$ind]['fields']['licensename'] = $output[$ind]['fields']['license'];
                    $output[$ind]['fields']['licenseurl'] = $this->wpdb->get_var("select url FROM licenses WHERE name = '" . $row['fields']['license'] . "'");
                }
                $output[$ind]['fields']['usernicename'] = $this->wpdb->get_var("select user_nicename from cur_users WHERE ID = " . $row['fields']['contributorid']);

                $output[$ind]['fields']['subsubjectarea'] = array();
                $subsubjectarea = $this->wpdb->get_results("SELECT CONCAT(s.displayname, ' > ' ,sa.displayname) AS subsubjectarea FROM resource_subjectareas as rsa JOIN subjectareas as sa on sa.subjectareaid = rsa.subjectareaid JOIN subjects as s on sa.subjectid = s.subjectid WHERE rsa.resourceid = " . $row['fields']['id'], ARRAY_A);

                foreach ($subsubjectarea as $s)
                    $output[$ind]['fields']['subsubjectarea'][] = $s['subsubjectarea'];

                $output[$ind]['fields']['statement'] = array();
                $alignment = $this->wpdb->get_results("SELECT notation as statement from statements s inner join resource_statements rs on rs.statementid = s.statementid where rs.resourceid = " . $row['fields']['id'], ARRAY_A);
                foreach ($alignment as $a)
                    $output[$ind]['fields']['statement'][] = $a['statement'];

                $output[$ind]['fields']['collectionelement'] = array();
                $alignment = $this->wpdb->get_results("select title as collectionelement from resources r inner join collectionelements ce on ce.resourceid = r.resourceid where ce.collectionid = " . $row['fields']['id'], ARRAY_A);
                foreach ($alignment as $a)
                    $output[$ind]['fields']['collectionelement'][] = $a['collectionelement'];

                $usersData = $this->wpdb->get_results("SELECT membertype, CONCAT(UCASE(LEFT(city, 1)), SUBSTRING(city, 2)) AS city, CONCAT(UCASE(LEFT(state, 1)), SUBSTRING(state, 2)) AS state, UPPER(country) AS country FROM users WHERE userid = " . $row['fields']['contributorid'], ARRAY_A);
                foreach ($usersData as $userData) {
                    $output[$ind]['fields']['usermembertype'] = ucwords($userData['membertype']);

                    unset($userData['membertype']);

                    if (!empty($userData['city'].$userData['state'].$userData['country']))
                        $output[$ind]['fields']['userlocation'] = implode(', ', $userData);
                }
            }
        }
        return $output;
    }

    public function curriki_search_api20_extra_fields_group($output) {
        $output = $output[0];
        if(isset($output) && is_array($output)){
            foreach ($output as $ind => $row) {
                $output[$ind]['fields']['currentGroupSpam'] = $this->wpdb->get_var("select spam from groups where groupid = " . $row['fields']['id']);
                if (strlen($row['fields']['title']) > 50)
                    $output[$ind]['fields']['title'] = substr($row['fields']['title'], 0, 50) . ' ...';

                if (isset($row['fields']['description']) && strlen($row['fields']['description']) > 150)
                    $output[$ind]['fields']['description'] = substr($row['fields']['description'], 0, 150) . ' ...';

                $avatarOptions = array("item_id" => $row['fields']['id'], "object" => "group", "type" => "full", "avatar_dir" => "group-avatars", "alt" => "Group avatar", "css_id" => 1234, "class" => "avatar", "width" => 50, "height" => 50, "html" => false);
                $output[$ind]['fields']['image'] = bp_core_fetch_avatar($avatarOptions);
                $output[$ind]['fields']['slug'] = groups_get_group(array('group_id' => $row['fields']['id']))->slug;

                $groupid = $row['fields']['id'];

                if($row['fields']["language"]!=="eng")
                {
                    $g_url = explode("/",$row['fields']['url'])[1];
                    $group_rs = $this->wpdb->get_row(sprintf("SELECT * from cur_bp_groups WHERE slug = '%s'", $g_url));            
                    $groupid = isset($group_rs) ? $group_rs->id : $groupid;                                        
                }                

                $output[$ind]['fields']['groups_users_count'] = intval(groups_get_total_member_count($groupid));

                $output[$ind]['fields']['groups_resources_count'] = intval(cur_get_resource_total_from_group($row['fields']['id']));

                $forum_id = 0;
                $forum_ids = groups_get_groupmeta($row['fields']['id'], 'forum_id', true);
                if (is_array($forum_ids) && count($forum_ids) > 0)
                    $forum_id = $forum_ids[0];

                $forum_count = $this->wpdb->get_var("SELECT count(ID) FROM {$this->wpdb->prefix}posts where post_type = 'topic' AND post_status = 'publish' AND post_parent = $forum_id");
                $output[$ind]['fields']['forum_id'] = intval($forum_id);
                $output[$ind]['fields']['groups_comments_count'] = ($forum_count > 0) ? $forum_count : '0';
            }
        }
        return $output;
    }

    public function curriki_search_api20_extra_fields_member($output) {
        $output = $output[0];
        if(isset($output) && is_array($output)){
            foreach ($output as $ind => $row) {
                $output[$ind]['fields']['members_groups_count'] = intval(groups_total_groups_for_user($row['fields']['id']));
                $output[$ind]['fields']['members_resources_count'] = intval(cur_get_resource_total_from_member($row['fields']['id']));
                $output[$ind]['fields']['members_followers_count'] = intval($this->wpdb->get_var("SELECT COUNT(id) f_total FROM cur_bp_follow WHERE follower_id = '" . $row['fields']['id'] . "'"));
                $output[$ind]['fields']['members_topics_count'] = intval($this->wpdb->get_var("select count(*) total_topics from cur_posts where post_type = 'topic' and post_author = '" . $row['fields']['id'] . "';"));
            }
        }
        return $output;
    }

    //Search Page Functions
    public function curriki_search_page_init() {

        $this->request = array_merge(array('educationlevel' => array(), 'subject' => array(), 'subsubjectarea' => array(), 'instructiontype' => array()), $this->request);
        $this->subdomain = SUBDOMAIN;
        $this->branding = SUBDOMAIN ? SUBDOMAIN : (isset($this->request['branding']) ? $this->request['branding'] : 'common');
        $this->page_name = get_query_var('name');
        $this->page_name = get_the_ID();
        $this->page_type = 'search';
        $this->search_page_url = get_permalink();
        $this->OER_page_url = get_bloginfo('url') . '/';
        $this->current_user = wp_get_current_user();
        $this->partnerid = $this->partnerid ? $this->partnerid : 1;
/*
        $this->resourcs_count = $this->wpdb->get_var("select count(*) as CNT from resources where ((type = 'collection' and title <> 'Favorites') or type = 'resource') and active = 'T'; ");
        $this->groups_count = $this->wpdb->get_var("select count(*) as CNT from cur_bp_groups;  ");
        $this->members_count = $this->wpdb->get_var("select count(*) as CNT from users where active = 'T'; ");
*/
        
        $site_data = $this->wpdb->get_row("select searchresources, groups, members from sites where sitename = 'curriki'");
        
        $this->resourcs_count = $site_data->searchresources;
        $this->groups_count = $site_data->groups;
        $this->members_count = $site_data->members;
        //$this->subjects = $this->wpdb->get_results("SELECT * FROM subjects order by displayname", ARRAY_A);
        $q_subjects = cur_subjects_query($this->current_language);            
        $this->subjects = $this->wpdb->get_results($q_subjects,ARRAY_A);                    
        
        
        
        
        //$this->subjectareas = $this->wpdb->get_results("SELECT sb.subjectid,sub.subjectareaid, CONCAT(sb.subject,':',sub.subjectarea) subsubjectarea ,sub.displayname FROM subjectareas as sub JOIN subjects as sb on sub.subjectid = sb.subjectid", ARRAY_A);                
        $q_subjectareas = cur_subjectareas_for_search_query($this->current_language,null);              
        $this->subjectareas = $this->wpdb->get_results($q_subjectareas,ARRAY_A);        
        
        
        //$this->instructiontypes = $this->wpdb->get_results("SELECT instructiontypeid,name,displayname from instructiontypes order by displayname", ARRAY_A);        
        $q_instructiontypes = cur_instructiontypes_for_search_query($this->current_language);
        $this->instructiontypes = $this->wpdb->get_results($q_instructiontypes, ARRAY_A);                    
        
//        $this->languages = $this->wpdb->get_results("select distinct l.language,l.displayname from resources r inner join languages l on r.language = l.language", ARRAY_A);

        //hardcoded languages
        $this->languages = [
            [
                'language'=> 'afr',
                'displayname'=> 'Afrikaans'
            ],
            [
                'language'=> 'ara',
                'displayname'=> 'Arabic'
            ],
            [
                'language'=> 'chi',
                'displayname'=> 'Chinese'
            ],
            [
                'language'=> 'dut',
                'displayname'=> 'Dutch; Flemish'
            ],
            [
                'language'=> 'eng',
                'displayname'=> 'English'
            ],
            [
                'language'=> 'fat',
                'displayname'=> 'Fanti'
            ],
            [
                'language'=> 'fin',
                'displayname'=> 'Finnish'
            ],
            [
                'language'=> 'fre',
                'displayname'=> 'French'
            ],
            [
                'language'=> 'ger',
                'displayname'=> 'German'
            ],
            [
                'language'=> 'hin',
                'displayname'=> 'Hindi'
            ],
            [
                'language'=> 'ind',
                'displayname'=> 'Indonesian'
            ],
            [
                'language'=> 'ita',
                'displayname'=> 'Italian'
            ],
            [
                'language'=> 'jpn',
                'displayname'=> 'Japanese'
            ],
            [
                'language'=> 'kor',
                'displayname'=> 'Korean'
            ],
            [
                'language'=> 'lat',
                'displayname'=> 'Latin'
            ],
            [
                'language'=> 'mul',
                'displayname'=> 'Multiple languages'
            ],
            [
                'language'=> 'nep',
                'displayname'=> 'Nepali'
            ],
            [
                'language'=> 'por',
                'displayname'=> 'Portuguese'
            ],
            [
                'language'=> 'sin',
                'displayname'=> 'Sinhala; Sinhalese'
            ],
            [
                'language'=> 'spa',
                'displayname'=> 'Spanish; Castilian'
            ],
            [
                'language'=> 'swe',
                'displayname'=> 'Swedish'
            ],
            [
                'language'=> 'tam',
                'displayname'=> 'Tamil'
            ],
            [
                'language'=> 'tur',
                'displayname'=> 'Turkish'
            ],
           
        ];
     
   
   
        $this->jurisdictioncode = $this->wpdb->get_results("select distinct jurisdictioncode from `standards` where active = 'T' order by 1 ", ARRAY_A);
        $this->standardtitles = $this->wpdb->get_results("select standardid,title,jurisdictioncode from `standards` where active = 'T' order by 2 ", ARRAY_A);

        $this->educationlevels = array(
            array('title' => __('Preschool (Ages 0-4) ','curriki'), 'levelids' => '8|9', 'levelidentifiers' => 'K|Pre-K'),
            array('title' => __('Kindergarten-Grade 2 (Ages 5-7) ','curriki'), 'levelids' => '3|4', 'levelidentifiers' => '1|2'),
            array('title' => __('Grades 3-5 (Ages 8-10)','curriki'), 'levelids' => '5|6|7', 'levelidentifiers' => '3|4|5'),
            array('title' => __('Grades 6-8 (Ages 11-13)','curriki'), 'levelids' => '11|12|13', 'levelidentifiers' => '6|7|8'),
            array('title' => __('Grades 9-10 (Ages 14-16)','curriki'), 'levelids' => '15|16', 'levelidentifiers' => '9|10'),
            array('title' => __('Grades 11-12 (Ages 16-18)','curriki'), 'levelids' => '17|18', 'levelidentifiers' => '11|12'),
            array('title' => __('College & Beyond','curriki'), 'levelids' => '23|24|25', 'levelidentifiers' => 'Graduate|Undergraduate-UpperDivision|Undergraduate-LowerDivision'),
            array('title' => __('Professional Development','curriki'), 'levelids' => '19|20', 'levelidentifiers' => 'ProfessionalEducation-Development|Vocational Training'),
            array('title' => __('Special Education','curriki'), 'levelids' => '26|21', 'levelidentifiers' => 'SpecialEducation|LifelongLearning'),
        );
        
        if (isset($this->request['phrase'])) {
            $this->request['suggestedPhraseURL'] = preg_replace('/\s\s+/', ' ', get_bloginfo('url') . $_SERVER['REQUEST_URI']);
            $this->request['suggestedPhraseURL'] = str_replace('phrase=' . urlencode(stripslashes($this->request['phrase'])), "", $this->request['suggestedPhraseURL']);
            $this->request['phrase'] = htmlspecialchars_decode($this->request['phrase']);
            $operators = array('"', "'", ' or ', ' and ', ' not ', ',', '+', '-', '&', '|', '!', '(', ')', '{', '}', '[', ']', '^', '~', '*', '?', ':', '\\');
            $haveOperator = $this->have_operators($this->request['phrase'], $operators, "OR");
            $haveQuotes = (strpos($this->request['phrase'], '"') == 1 AND strpos($this->request['phrase'], '"', 2) == intval(strlen($this->request['phrase']) - 1));

            $this->request['suggestedPhrase'] = trim(stripslashes($this->request['phrase']));
            $this->request['suggestedPhrase'] = str_ireplace($operators, ' ', $this->request['suggestedPhrase']);
            $this->request['suggestedPhrase'] = preg_replace('/\s\s+/', ' ', $this->request['suggestedPhrase']);
            $this->request['suggestedPhrase'] = ($haveOperator AND ! $haveQuotes) ? sprintf('"%s"', $this->request['suggestedPhrase']) : str_replace(' ', ', ', $this->request['suggestedPhrase']);
            $this->request['suggestedPhraseURL'] .= "&phrase=" . urlencode($this->request['suggestedPhrase']);

            $phrase = (!$haveOperator && !$haveQuotes) ? sprintf('"%s"', $this->request['phrase']) : $this->request['phrase'];

            //Making Query from input array
            $this->request['query'] = "";
            if (!empty($phrase)){
//                if(strpos($phrase,"-") !== false){
//                    $phrase = "\"".str_replace("-"," ",$phrase)."\"";
//                }
                $this->request['query'] .= $phrase;
            }

            if (isset($this->request['language']) && !empty($this->request['language']))
                $this->request['query'] .= " AND language:\"" . $this->request['language'] . "\"";

            if (isset($this->request['partner']) && !empty($this->request['partner']))
                $this->request['query'] .= " AND partner:\"" . $this->request['partner'] . "\"";

            if (isset($this->request['reviewrating']) && !empty($this->request['reviewrating']))
                $this->request['query'] .= " AND reviewrating:" . $this->request['reviewrating'];

            if (isset($this->request['memberrating']) && !empty($this->request['memberrating']))
                $this->request['query'] .= " AND memberrating:" . $this->request['memberrating'];

            if (count($this->request['subject'])) {
                foreach ($this->request['subject'] as $ind => $val)
                    $subject[] = "subject:\"" . $val . "\"";
                $this->request['query'] .= " AND ( " . implode(" OR ", $subject) . " ) ";
            }
            if (count($this->request['subsubjectarea'])) {
                foreach ($this->request['subsubjectarea'] as $ind => $val)
                    $subsubjectarea[] = "subsubjectarea:\"" . $val . "\"";
                $this->request['query'] .= " AND ( " . implode(" OR ", $subsubjectarea) . " ) ";
            }
            if (count($this->request['instructiontype'])) {
                foreach ($this->request['instructiontype'] as $ind => $val)
                    $instructiontype[] = "instructiontype:\"" . $val . "\"";
                $this->request['query'] .= " AND ( " . implode(" OR ", $instructiontype) . " ) ";
            }
            if (count($this->request['educationlevel'])) {
                foreach ($this->request['educationlevel'] as $ind => $val)
                    foreach (explode("|", $val) as $v)
                        $educationlevel[] = "educationlevel:\"" . $v . "\"";
                $this->request['query'] .= " AND ( " . implode(" OR ", $educationlevel) . " ) ";
            }
            if (isset($this->request['notations']) && count($this->request['notations'])) {
                foreach ($this->request['notations'] as $ind => $val)
                    $statement[] = "statementid:\"" . $val . "\"";
                $this->request['query'] .= " AND ( " . implode(" OR ", $statement) . " ) ";
            }

            $this->request['query'] = trim($this->request['query']);
            $this->request['query'] = trim($this->request['query'], "AND");
            $this->request['query'] = trim($this->request['query'], "OR");
            $this->request['query'] = trim($this->request['query']);
            if (!empty($this->request['query']))
                $this->request['query'] = "(" . $this->request['query'] . ")";
            $this->curriki_search_api20_init();
//            $this->curriki_search_api20_make_query();
            $this->curriki_search_api20_make_topofsearch_query();
        }

        $search_page_url = '';
        if (isset($this->request['viewer']) && $this->request['viewer'] == 'embed') {
            $search_page_url = current(explode("?", $_SERVER['HTTP_REFERER']));
        }

        //Setting up URLS
        $this->newSearchURL = $search_page_url . "?type=" . (isset($this->request['type']) ? $this->request['type'] : "Resource") . "&partnerid=" . $this->partnerid;
        $this->resourcesTabURL = $search_page_url . "?type=Resource&partnerid=" . $this->partnerid;
        $this->groupTabURL = $search_page_url . "?type=Group&partnerid=" . $this->partnerid;
        $this->memberTabURL = $search_page_url . "?type=Member&partnerid=" . $this->partnerid;

        //Checking target and setting related variable
        if (isset($this->request['search_target'])) {
            if ($this->request['search_target'] == 'blank') {
                $this->search_target = '_blank';
            } elseif ($this->request['search_target'] == 'curriki') {
                $this->search_target = '_blank';
                unset($this->request['viewer']);
            } else {
                $this->search_target = '_top';
                $this->search_page_url = current(explode("?", $_SERVER['HTTP_REFERER']));
                $this->OER_page_url = $this->search_page_url . '?/';
            }
        }
    }

    public function curriki_search_page_layout() {
        //* Force full-width-content layout setting
        add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');

        remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');
        remove_action('genesis_loop', 'genesis_do_loop');

        // OR

        if (($this->partnerid != 1 OR $this->branding != 'common') AND $this->branding != 'curriki') {
            //remove header
//            if($this->branding != 'students' && $this->branding != 'studentsearch' && $this->branding != 'search'){
                remove_action('genesis_header', 'genesis_header_markup_open', 5);
                remove_action('genesis_header', 'genesis_do_header');
                remove_action('genesis_header', 'genesis_header_markup_close', 15);
//            }

            //remove navigation
            remove_action('genesis_after_header', 'genesis_do_nav');
            remove_action('genesis_after_header', 'genesis_do_subnav');

            //Remove footer
            remove_action('genesis_footer', 'genesis_footer_markup_open', 5);
            remove_action('genesis_footer', 'genesis_do_footer');
            remove_action('genesis_footer', 'genesis_footer_markup_close', 15);

            //* Remove the entry footer markup (requires HTML5 theme support)
            remove_action('genesis_before_footer', 'genesis_footer_widget_areas');
            remove_action('genesis_entry_footer', 'genesis_entry_footer_markup_open', 5);
            remove_action('genesis_entry_footer', 'genesis_entry_footer_markup_close', 15);
            
            
        }
    }

    public function curriki_search_page_body_class($classes) {
        if ($this->partnerid == 1 OR $this->branding == 'curriki')
            $classes[] = 'backend search-page';
        return $classes;
    }

    public function curriki_search_page_scripts() {

        wp_enqueue_style('qtip-css', get_stylesheet_directory_uri() . '/js/qtip2_v2.2.1/jquery.qtip.min.css', null, false, 'all'); // Add the styles first, in the <head> (last parameter false, true = bottom of page!)
        wp_enqueue_script('qtip-js', get_stylesheet_directory_uri() . '/js/qtip2_v2.2.1/jquery.qtip.min.js', array('jquery'), false, true); // Not using imagesLoaded? :( Okay... then this.
        wp_enqueue_style('jquery-ui-css',  '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
        wp_enqueue_script('jquery-ui-js',  '//code.jquery.com/ui/1.12.1/jquery-ui.js');
        wp_enqueue_style('search-module-css', get_stylesheet_directory_uri() . '/modules/search/css/style.css', null, false, 'all');
        wp_enqueue_style('bootstrap-css',  get_stylesheet_directory_uri() . '/css/bootstrap.min.css');
        wp_enqueue_script('bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', 'jquery', '2.1.5');
        wp_enqueue_style('search-module-search-css', get_stylesheet_directory_uri() . '/modules/search/css/search.css', null, false, 'all');
        
        wp_register_script('search-module-script',get_stylesheet_directory_uri() . '/modules/search/js/script.js', array('jquery'), false, true);
        $translation_array = array( 'tootip_heading' => __('Search Tips & Advance Features', 'curriki') );
        wp_localize_script('search-module-script', 'tootip_ml_obj', $translation_array);
        wp_enqueue_script("search-module-script");  
        //wp_enqueue_script('search-module-script', get_stylesheet_directory_uri() . '/modules/search/js/script.js', array('jquery'), false, true);

        echo "<script>";
        echo "var ajaxurl = '" . admin_url('admin-ajax.php') . "';";
        echo "var baseurl = '" . get_bloginfo('url') . "/';";
        echo "</script>";
    }

    public function curriki_search_page_header() {
        get_template_part('modules/search/brandings/' . $this->branding . '/header');
    }

    public function curriki_search_page_body() {
        $theme_url = get_stylesheet_directory_uri();
        ?>

        <?php if (isset($this->request['phrase'])) { ?>
            <?php get_template_part('modules/search/views/search_results_header'); ?>
        <?php } ?>
        <div class="container_12 wrap">
            <div class="result-content">
                <div class="row row-no-gutters row-result">
                    <div class="grid_3">
                        <div class="left-panel">
                            <div class="panel-group panel-group-primary">
                            <form action="<?php echo $this->search_page_url; ?>" method="GET" id="search_form" target="<?php echo $this->search_target; ?>">
                                <?php if(!isset($this->request['type']) || $this->request['type'] == 'Resource') { ?>
                                    <input type="hidden" name="size" value="10" />
                                <?php } ?>
                                <?php if(!isset($this->request['type']) || $this->request['type'] == 'Group') { ?>
                                    <input type="hidden" name="size" value="16" />
                                <?php } ?>

                                <?php get_template_part('modules/search/views/search_input_widget'); ?>
                            </form>
                            </div>
                        </div>
                    </div>
                    <div class="grid_9">
                        <div class="result-wrap">
                            <?php if (isset($this->request['phrase'])) { ?>
                                <?php if ($this->request['type'] == 'Resource') get_template_part('modules/search/views/search_result_resources'); ?>
                                <?php if($this->partnerid!=3){ ?>
                                    <?php if (isset($this->request['phrase']) && $this->status['found'] > $this->request['size']) get_template_part('modules/search/views/pagination'); ?>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function curriki_search_page_footer() {
        get_template_part('modules/search/brandings/' . $this->branding . '/footer');
    }

    //Targeted Search Landing Page Functions
    public function curriki_search_targeted_init() {
        $this->curriki_search_page_init();
        $this->page_type = 'targeted';
        $this->meta = get_post_meta(get_the_ID());
        foreach ($this->meta as $key => $val)
            $this->meta[$key] = $val[0];

//        echo "<pre>";
//        print_r($this->meta);
//        die();
        $this->meta['resource1'] = explode("/", trim($this->meta['resource1'], '/'));
        $this->meta['resource2'] = explode("/", trim($this->meta['resource2'], '/'));
        $this->meta['resource3'] = explode("/", trim($this->meta['resource3'], '/'));
        $this->meta['resource4'] = explode("/", trim($this->meta['resource4'], '/'));
        $this->meta['resource5'] = explode("/", trim($this->meta['resource5'], '/'));

        $this->meta['resource1'] = end($this->meta['resource1']);
        $this->meta['resource2'] = end($this->meta['resource2']);
        $this->meta['resource3'] = end($this->meta['resource3']);
        $this->meta['resource4'] = end($this->meta['resource4']);
        $this->meta['resource5'] = end($this->meta['resource5']);

        $this->meta['search_query'] = str_replace("https://www.curriki.org/search-api-2-0/?", "", $this->meta['search_query']);
        $this->meta['search_query'] = str_replace("https://www.curriki.org/search/?", "", $this->meta['search_query']);
    }

    public function curriki_search_targeted_query() {
        //Getting results of query
        $this->curriki_search_api20_init();
        parse_str($this->meta['search_query'], $request);

        if (isset($request['format']))
            unset($request['format']);

        if (isset($request['partnerid']))
            unset($request['format']);

        if (!isset($request['size']))
            $request['size'] = 5;

        $this->request = array_merge($this->request, $request);
        $this->curriki_search_api20_make_topofsearch_query();
    }

    public function curriki_search_targeted_resources() {
        //Getting results of query
        $this->curriki_search_api20_init();
        $resourceids = $this->wpdb->get_results(sprintf("select resourceid from resources where pageurl in ('%s','%s','%s','%s','%s')", $this->meta['resource1'], $this->meta['resource2'], $this->meta['resource3'], $this->meta['resource4'], $this->meta['resource5']), ARRAY_A);
        foreach ($resourceids as $i => $r) {
            $this->meta['resource' . intval($i + 1)] = $r['resourceid'];
        }
//        $_GET['getquery']=1;
        $this->request['query'] = sprintf("( id:\"%s\" OR id:\"%s\" OR id:\"%s\" OR id:\"%s\" OR id:\"%s\" )", intval($this->meta['resource1']), intval($this->meta['resource2']), intval($this->meta['resource3']), intval($this->meta['resource4']), intval($this->meta['resource5']));
        $this->curriki_search_api20_make_topofsearch_query();
    }

    public function curriki_search_targeted_body() {
        ?>

        <div class="search-content" >
            <div class="wrap container_12" >
                <?php get_template_part('modules/search/views/search_title_widget'); ?>
                <?php $this->curriki_search_targeted_resources(); ?>
                <?php get_template_part('modules/search/views/search_result_resources'); ?>
                
                <?php 
                // Showing Sharing on bottom
                echo sharing_display();
                ?>
                <?php /*
                  <p>&nbsp;<br/>&nbsp;<br/></p>
                  <div class="grid_12 clearfix "><div class="search-term grid_8 alpha"><h4><?php echo $this->meta['search_results_header']; ?></h4></div></div>
                  <?php $this->curriki_search_targeted_query(); ?>
                  <?php get_template_part('modules/search/views/search_result_resources'); ?>
                  <p>&nbsp;<br/>&nbsp;<br/></p>
                 * */ ?>

                <div class="bottomsheet" >
                    <div class="close"></div>
                    <div class="search-content" style="padding-top: 5px;text-align: center;">
                        <h4 style="color: white;">
                            <?php echo $this->meta['bottom_popup_text'] ? $this->meta['bottom_popup_text'] : "1 Search our complete repository"; ?>
                            <a href="<?php echo "https://www.curriki.org/search/?" . $this->meta['search_query']; ?>" > 
                                <input class="gform_button button" value="Search Now" style="width:280px;margin-left: 140px;" type="submit">
                            </a>
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <?php
    }

    public function have_operators($string, $operators, $type) {
        $have = false;
        foreach ($operators as $op) {

            if ($type == 'AND') {
                if (stripos($string, $op)) {
                    $have = true;
                } else {
                    return false;
                }
            } else {
                if (stripos($string, $op)) {
                    return true;
                } else {
                    $have = false;
                }
            }
        }
        return $have;
    }
    
    public static function searches_table_entry($term = '') {
        ob_clean();
        global $wpdb;


        if (is_user_logged_in()):
            $user_id = get_current_user_id();
            $wpdb->query($wpdb->prepare(
                            "
                            INSERT INTO searches
                            ( ip, entrypoint, userid, term)
                            VALUES ( %s, %s, %d , %s)
                    ", $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_HOST'], $user_id, $term
            ));
        else:
            $wpdb->query($wpdb->prepare(
                            "
                            INSERT INTO searches
                            ( ip, entrypoint, term )
                            VALUES ( %s, %s, %s )
                    ", $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_HOST'], $term
            ));
        endif;

    }

}
