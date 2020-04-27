<?php

class LR {

    public static function curlrSync($resource) {
        $resourceid = $resource['resourceid'];
        $_id = $resource['_id'];
        $content = $resource['content'];
        $url = $resource['url'];
        $page_url = $url;

        $content = json_decode($content, true);


        $resource_data_type = isset($content['record']['resource_data']['resource_data_type']) ? $content['record']['resource_data']['resource_data_type'] : '';
        $resource_data_exists = isset($content['record']['resource_data']['resource_data']) ? isset($content['record']['resource_data']['resource_data']) : false;


        if ($resource_data_type != 'metadata') {
//          echo "\nSkipping Paradata ... _id = $_id \n";
            return false;
        }
        if (!$resource_data_exists) {
//          echo "\nresource_data doesnt exist _id = $_id ... \n";
            return false;
        }



        $document = $content['record']['resource_data'];


        // Declaring Variables

        $description = null;
        $title = null;
        $keywords = null;
        $language = null;
        $externalurl = null;
        $studentfacing = null;

        // Fixed
        $licenseid = 0;
        $contributorid = 10000;
        $contributiondate = null;
        $lasteditorid = 10000;
        $lasteditdate = null;
        $currikilicense = 'F';
        $r_content = null;
        $source = 'LearningRegistry-' . $_id;
        $partner = 'T';
        $createdate = null;
        $type = 'resource';
        $public = 'T';
        $mediatype = 'external';
        $access = 'public';
        $pageurl = null;
        $resource_type = null;
        $typicalAgeRange = null;
        $learningResourceType = null;



        if (is_array($document['resource_data'])) {

            if (isset($document['resource_data']['items'])) {
//                print("\nSkipping items: $_id\n");
                return false;
//                        continue;
                // Case 3
                // http://node01.public.learningregistry.net/obtain?request_id=415967511e1f4592a80d5436428f0255&by_doc_ID=T
                if (isset($document['resource_data']['items']['properties'])) {
                    $properties = $document['resource_data']['items']['properties'];
                    $data = self::case3($properties, $document);
                    $data['resource_type'] = 'json_properties';
                    extract($data);
                }
            } elseif (isset($document['resource_data']['@graph'])) {
//                print("\nSkipping graph: $_id\n");
                return false;
//                        continue;
                // case 4 
                // http://node01.public.learningregistry.net/obtain?request_id=e617ac5833044e30bcb95dcc3231b1da&by_doc_ID=T
                $graph = $document['resource_data']['@graph'];


                $data = self::case4($graph, $document);
                $data['resource_type'] = 'json_graph';
                extract($data);

//                        $description = 'graph';
//                        $title = 'graph';
//                        $keywords = 'graph';
//                        $language = 'graph';
//                        $externalurl = 'graph';
//                        $studentfacing = 'graph';
            } elseif (isset($document['resource_data']['name'])) {
                return false;
//                        continue;
                // Case 1
                // resource_data is json
                // http://node01.public.learningregistry.net/obtain?request_id=2814a125460f4b2e91bf6da951be8616&by_doc_ID=T
//                        continue;
                $data['resource_type'] = 'json';
                $data = self::case1n2($document);
                extract($data);
            }


//                    echo "<pre>";
//                    print_r($document);
//                    die();
        } elseif (self::isValidXml($document['resource_data'])) {
//            print("\nSkipping string: $_id\n");

            $lastchar = substr($document['resource_data'], -1);
            if ($lastchar == '>') {
                $dt = rtrim($document['resource_data'], ">");
                $document['resource_data'] = $dt . '>';
            }
            if (self::isValidXml($document['resource_data'])) {
                $data['resource_type'] = 'xml';
                $data['resource_foreign_id'] = $resourceid;
                $data['_id'] = $_id;


                $main_doc = new DOMDocument();
                libxml_use_internal_errors(TRUE); //disable libxml errors
//                    echo $document['resource_data'];
//                    die();


                $main_doc->loadHTML($document['resource_data']);
                libxml_clear_errors();
                $main_xpath = new DOMXPath($main_doc);
//                    var_dump($main_xpath);
//                    die();



                $metadata = $main_xpath->query('//metadata');
                    
                if ($metadata->length > 0) {
                    $lom = $main_xpath->query('//metadata//lom');
                    if ($lom->length > 0) {
                        $languages_arr = array();
//                        echo "LOM \t\t$resourceid\n";
//                        return false;
//                        $sixth_metadata++;
                        $languages = $main_xpath->query("//metadata//lom//general//title//string[@language]");
                        $lang = '';
                        $c = 0;
                        foreach ($languages as $language) {
                            if($c++ == 0){
                                $lang = $language->getAttribute('language');
                                $data['language'] = $lang;
                                break;
                            }
                            
                        }
                        
//                        if(count($languages_arr) > 0){
//                            $lang = $languages_arr[0];
//                            $data['language'] = $lang;
//                        }
//                        echo "<pre>";
//                        print_r($languages_arr);
//                        die();
//                        var_dump($lang);
//                        die();
                        
                        $titles = $main_xpath->query("//metadata//lom//general//title//string[contains(@language, '$lang')]");
                        foreach ($titles as $title) {
//                            echo $title->getAttribute('language'). "<br />";
                            $data['title'] = $title->nodeValue;
//                            die();
                            
                        }
//                        die();
//                        die();
                        $descriptions = $main_xpath->query("//metadata//lom//general//description//string[contains(@language, '$lang')]");
                        foreach ($descriptions as $description) {
                            $data['description'] = $description->nodeValue;
                        }

                        $typicalAgeRanges = $main_xpath->query("//metadata//lom//educational//typicalagerange//string[contains(@language, '$lang')]");

                        foreach ($typicalAgeRanges as $typicalAgeRange) {
                            $data['typicalAgeRange'][] = $typicalAgeRange->nodeValue;
                        }

                        $learningResourceTypes = $main_xpath->query("//metadata//lom//educational//learningresourcetype//value");
                        foreach ($learningResourceTypes as $learningResourceType) {
                            $data['learningResourceType'] = $learningResourceType->nodeValue;
                        }
                    }
                    else {
                        return false;
                    }

                    $data['source'] = 'LearningRegistryXML-' . $_id;


                    extract($data);
//                    die();
//                        echo "METADETA ". $metadata->length;
//                        echo "\n\n";
                }
                
//                else {
//                    
//                    echo "Not Metadata \t". $resourceid. "\n";
//                        return false;
//                    $main_doc = new DOMDocument();
//                    $main_doc->loadXML($document['resource_data']);
//                    libxml_clear_errors();
//                    $main_xpath = new DOMXPath($main_doc);
//                    $oai_dc = $main_xpath->query('//oai_dc:dc');
//                    $lom_lom = $main_xpath->query('//lom:lom');
//                    $nsdl_dc = $main_xpath->query('//nsdl_dc:nsdl_dc');
//                    if ($oai_dc || $lom_lom || $nsdl_dc) {
//                        if ($oai_dc) {
//                            $sixth_oai_dc++;
////                                echo "OAI_DC ". $oai_dc->length;
////                                echo "\n\n";
//                        }
//
//
//                        if ($lom_lom) {
//                            $sixth_lom++;
////                                echo "LOM:LOM ". $lom_lom->length;
////                                echo "\n\n";
//                        }
//
//
//                        if ($nsdl_dc) {
//                            $sixth_nsdl_dc++;
////                                echo "nsdl_dc:nsdl_dc ". $nsdl_dc->length;
////                                echo "\n\n";
//                        }
//                    } else {
//                        $sixth++;
//                    }
//                }
//                    die();
//                    $xml=simplexml_load_string($s);
//                    var_dump(($cat_row));
//                    foreach ($metadata as $a):
//                        var_dump($a);
////                        echo $a->nodeValue;
//                    endforeach;
//                    var_dump($xml);
//                    echo "\n\n";
//                    print_r($xml);
//                    echo $xml->children('oai_dc', true);
//                    echo $xml->children('oai_dc', true)->dc->identifier;
//                    echo $xml->oai_dc;

                $data['resource_type'] = 'string_xml';
                extract($data);
            } else {
                print("\nSkipping string 2: $_id\n");
                return false;
                $data['description'] = $document['resource_data'];
                $data['resource_type'] = 'string';
                extract($data);
            }
        } else{
            //not xml
            return false;
        }
//                elseif (is_object($document['resource_data'])) {
//                    echo "Object";
//                    die();
//                }
//        return false;
        
        if (isset($document['resource_locator'])) {
            $externalurl = $document['resource_locator'];
            if (is_array($externalurl)) {
                if (count($externalurl > 0)) {
                    $externalurl = $externalurl[0];
                }
            }
//            die($externalurl);
            $u = 'http://cg.curriki.org/curriki/wp-content/libs/tinymce/plugins/oembed/generate_thumbnail.php?url=' . $externalurl;
//            $page = file_get_contents($u);
//            die();
            $page = self::fetchPage($u);
//            $page = self::fetchPage('http://cg.curriki.org/curriki/wp-content/libs/tinymce/plugins/oembed/generate_thumbnail.php?url='.$externalurl);

            $r_content = '<div class="description">' . $description . '</div>';
            if ($page) {
                $p = json_decode($page, true);
                if (isset($p['error'])) {
                    if ($p['error'] == '') {
                        if (isset($p['html'])) {
                            $r_content .= $p['html'];
                        }
                    } else {
                        echo "\nError generating thumb\t\t$resourceid\n";
                        return false;
                    }
                }
            }
            


//            var_dump($p);
//            die();
//            print_r($p);
//            var_dump($page);
//            die();
        }

        if (isset($document['keys'])) {
            $keywords = $document['keys'];

            if (is_array($keywords)) {
                if (count($keywords > 0)) {
                    $keywords = implode(",", $keywords);
                } else {
                    $keywords = '';
                }
            }
        }

        if (isset($document['TOS']['submission_TOS'])) {
            if (strpos($document['TOS']['submission_TOS'], 'http://creativecommons.org') !== false) {
                $licenseid = 2;
            }
            if (strpos($document['TOS']['submission_TOS'], 'http://www.learningregistry.org') !== false) {
                $licenseid = 3;
            }
            if (strpos($document['TOS']['submission_TOS'], 'http://nsdl.org/help/terms-of-use') !== false) {
                $licenseid = 7;
            }
        }


        $pageurl = $title ? $title : substr($description, 1, 30);
        $pageurl = substr($pageurl = str_replace(array(' ', ',', ':', '.', '(', ')', '\'', '?', '/', '+', '\\', '--', '&', '#', '"'), array('-', '-', '-', '', '-', '-', '-', '-', '-', '-', '-', '-', 'and', '-', '-'), $pageurl), 0, 499);


        if (is_null($title) || $title == '') {
            print("\nSkipping title: $_id\n");
            return false;
        }
        if (is_null($description) || $description == '') {
            print("\nSkipping description: $_id\n");
            return false;
        }



        $arr = array();


        $single_url = 'http://node01.public.learningregistry.net/obtain?request_id=' . $_id . '&by_doc_ID=T';
        $single_url_tbl = '<a href="' . $single_url . '">url</a>';






        $data1 = [
            'resource_foreign_id' => $resourceid,
            '_id' => $_id,
            'description' => $description,
            'title' => $title,
            'keywords' => $keywords,
            'language' => $language,
            'externalurl' => $externalurl,
            'single_url' => $single_url,
            'page_url' => $page_url,
            'resource_type' => $resource_type,
            //fixed
            'licenseid' => $licenseid,
            'contributorid' => $contributorid,
            'contributiondate' => $contributiondate,
            'lasteditorid' => $lasteditorid,
            'lasteditdate' => $lasteditdate,
            'currikilicense' => $currikilicense,
            'content' => $r_content,
            'studentfacing' => $studentfacing,
            'source' => $source,
            'partner' => $partner,
            'createdate' => $createdate,
            'type' => $type,
            'public' => $public,
            'mediatype' => $mediatype,
            'access' => $access,
            'pageurl' => $pageurl,
            'learningResourceType' => $learningResourceType,
            'typicalAgeRange' => $typicalAgeRange
        ];


        $return = $data1;
        return $return;
    }

    private static function case1n2($document) {
        if (isset($document['resource_data']['description'])) {
            $description = $document['resource_data']['description'];

            if (is_array($description)) {

                if (count($description > 0)) {

                    $description = $description[0];
                } else {
                    $description = '';
                }
            }
        }
        if (isset($document['resource_data']['name'])) {
            $title = $document['resource_data']['name'];
            if (is_array($title)) {
                if (count($title > 0)) {
                    $title = $title[0];
                } else {
                    $title = '';
                }
            }
        }

        if (isset($document['resource_data']['inLanguage'])) {
            $language = $document['resource_data']['inLanguage'];
            $engExists = false;
            if (is_array($language)) {
                if (count($language > 0)) {
                    foreach ($language as $lang) {
                        if (strtolower(substr($lang, 0, 3)) == 'eng' || strtolower(substr($lang, 0, 3)) == 'en') {
                            $engExists = true;
                            break;
                        }
                    }
                    if ($engExists) {
                        $language = 'eng';
                    } else {
                        $language = strtolower(substr($language[0], 0, 3));
                    }
                } else {
                    $language = '';
                }
            } else {
                $language = strtolower(substr($language, 0, 3));
            }
        }

        if (isset($document['resource_data']['learningResourceType'])) {
            if (is_array($document['resource_data']['learningResourceType'])) {
                $learningResourceType = json_encode($document['resource_data']['learningResourceType']);
            } else {
                $learningResourceType = $document['resource_data']['learningResourceType'];
            }
        }

        if (isset($document['resource_data']['typicalAgeRange'])) {
            if (is_array($document['resource_data']['typicalAgeRange'])) {
                $typicalAgeRange = $document['resource_data']['typicalAgeRange'];
            } else {
                $typicalAgeRange = $document['resource_data']['typicalAgeRange'];
            }
        }

        if (isset($document['resource_data']['audience']['educationalRole'])) {
            $studentfacing = 'F';
            if ($document['resource_data']['audience']['educationalRole'] == 'student') {
                $studentfacing = 'T';
            }
        }

        return compact('description', 'title', 'keywords', 'language', 'externalurl', 'learningResourceType', 'typicalAgeRange', 'studentfacing');
    }

    private static function case3($properties, $document) {
        if (isset($properties['description'])) {
            $description = $properties['description'];
            if (is_array($description)) {
                if (count($description > 0)) {
                    $description = $description[0];
                } else {
                    $description = '';
                }
            }
        }
        if (isset($properties['name'])) {
            $title = $properties['name'];
            if (is_array($title)) {
                if (count($title > 0)) {
                    $title = $title[0];
                } else {
                    $title = '';
                }
            }
        }

        if (isset($properties['inLanguage'])) {
            $language = $properties['inLanguage'];
            if (is_array($language)) {
                if (count($language > 0)) {
                    $language = implode(",", $language);
                } else {
                    $language = '';
                }
            }
        }

        if (isset($document['resource_data']['learningResourceType'])) {
            if (is_array($document['resource_data']['learningResourceType'])) {
                $learningResourceType = json_encode($document['resource_data']['learningResourceType']);
            } else {
                $learningResourceType = $document['resource_data']['learningResourceType'];
            }
        }
        if (isset($document['resource_data']['typicalAgeRange'])) {
            if (is_array($document['resource_data']['typicalAgeRange'])) {
                $typicalAgeRange = $document['resource_data']['typicalAgeRange'];
            } else {
                $typicalAgeRange = $document['resource_data']['typicalAgeRange'];
            }
        }


        return compact('description', 'title', 'keywords', 'language', 'externalurl', 'learningResourceType', 'typicalAgeRange');
    }

    private static function case4($graph, $document) {
        if (is_array($graph)) {
            if (count($graph) > 0) {
                $graph = $graph[0];
            }
        }
        if (isset($graph['description'])) {
            $description = $graph['description'];
            if (is_array($description)) {
                if (count($description > 0)) {
                    $description = $description[0];
                } else {
                    $description = '';
                }
            }
        }
        if (isset($graph['name'])) {
            $title = $graph['name'];
            if (is_array($title)) {
                if (count($title > 0)) {
                    $title = $title[0];
                } else {
                    $title = '';
                }
            }
        }



        if (isset($graph['inLanguage'])) {
            $language = $graph['inLanguage'];
            if (is_array($language)) {
                if (count($language > 0)) {
                    $language = implode(",", $language);
                } else {
                    $language = '';
                }
            }
        }

        if (isset($graph['learningResourceType'])) {
            if (is_array($graph['learningResourceType'])) {
                $learningResourceType = json_encode($graph['learningResourceType']);
            } else {
                $learningResourceType = $graph['learningResourceType'];
            }
        }
        if (isset($graph['typicalAgeRange'])) {
            if (is_array($graph['typicalAgeRange'])) {
                $typicalAgeRange = json_encode($graph['typicalAgeRange']);
            } else {
                $typicalAgeRange = $graph['typicalAgeRange'];
            }
        }


        return compact('description', 'title', 'keywords', 'language', 'externalurl', 'learningResourceType', 'typicalAgeRange');
    }

    private static function get_words($sentence, $count = 4) {
        preg_match("/(?:\w+(?:\W+|$)){0,$count}/", $sentence, $matches);
        return $matches[0];
    }

    public static function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    private static function isValidXml($content) {
        $content = trim($content);
        if (empty($content)) {
            return false;
        }
        //html go to hell!
        if (stripos($content, '<!DOCTYPE html>') !== false) {
            return false;
        }

        libxml_use_internal_errors(true);
        simplexml_load_string($content);
        $errors = libxml_get_errors();
        libxml_clear_errors();

        return empty($errors);
    }

    public static function fetchPage($page) {
        if (!isset($page) || $page == '') {
            return false;
        }

        date_default_timezone_set('UTC');

        $url = $page;

        $request = $url;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        curl_setopt($ch, CURLOPT_URL, $request);
//        curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS4A);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2000);
        curl_setopt($ch, CURLOPT_TIMEOUT, 4500);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/' . rand(1, 5) . '.0 (X11; CrOS x86_64 ' . rand(1000, 8000) . '.0.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.' .
                rand(300, 900) . '.3 Safari/' . rand(100, 800) . '.' . rand(25, 76));

//        curl_setopt($ch, CURLOPT_PROXY, 'localhost:9051');
///Send the complete request to the API
        if (!$result = curl_exec($ch)) {
//		echo "Bad Data\n";
//                echo curl_error($ch);
            return false;
        } else {
//		echo "Good...\n";
        }

        if ($result === false) {
//		echo "Got a bad page $page.... Switching Tors\n";
//            	self::tor_new_identity();
//            	self::fetchPage($page);
            // throw new Exception(curl_error($ch), curl_errno($ch));
            return false;
        }
        //echo $result;
        return $result;
    }

    private static function urlExists($url = NULL) {
        if ($url == NULL)
            return false;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpcode >= 200 && $httpcode < 300) {
            return true;
        } else {
            return false;
        }
    }

}
