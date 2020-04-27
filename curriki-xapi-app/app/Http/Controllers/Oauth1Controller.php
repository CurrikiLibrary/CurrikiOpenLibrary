<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\LTIOAuthToken;
use Risan\OAuth1\Credentials\TokenCredentials;
use \DB;

class Oauth1Controller extends Controller {

    public function __construct() {
        
    }

    public function index() {
        return "Test oauth";
    }

    public function connect(Request $request) {
        // Create an instance of Risan\OAuth1\OAuth1 class.
        $oauth1 = \Risan\OAuth1\OAuth1Factory::create([
            'client_credentials_identifier' => '14e049915adf4e079df716f602796ab8',
            'client_credentials_secret' => 'UH8f1B92U33df9LU',
            'temporary_credentials_uri' => 'http://lrs.curriki.org:8000/xapi/OAuth/initiate',
            'authorization_uri' => 'http://lrs.curriki.org:8000/xapi/OAuth/authorize',
            'token_credentials_uri' => 'http://lrs.curriki.org:8000/xapi/OAuth/token',
            'callback_uri' => 'https://www.curriki.org/curriki-xapi-app/public/oauth1/connect',
        ]);
        $token = LTIOAuthToken::where('id',1)->first();
//        dd($token['key']);

//        if ($request->session()->get('token_credentials')) {
        if ($token['key'] != null && $token['secret'] != null) {

            // Get back the previosuly obtain token credentials (step 3).
//            $tokenCredentials = unserialize($request->session()->get('token_credentials'));
            $tokenCredentials = new TokenCredentials($token['key'], $token['secret']);
//            dd($tokenCredentials);
            $oauth1->setTokenCredentials($tokenCredentials);

            // STEP 4: Retrieve the user's tweets.
            // It will return the Psr\Http\Message\ResponseInterface instance.
            // $oauth1->setHeader('X-Experience-API-Version', '1.0');
            $response = $oauth1->request('GET', 'http://lrs.curriki.org:8000/xapi/statements');

            // Convert the response to array and display it.
            echo "<pre>";
            print_r(json_decode($response->getBody()->getContents(), true));
        } elseif (isset($_GET['oauth_token']) && isset($_GET['oauth_verifier'])) {
//            dd($request->session()->get('temporary_credentials'));
            // Get back the previosuly generated temporary credentials (step 1).
            $temporaryCredentials = unserialize($request->session()->get('temporary_credentials'));
            // unset(session('temporary_credentials'));
            // STEP 3: Obtain the token credentials (also known as access token).
            $tokenCredentials = $oauth1->requestTokenCredentials($temporaryCredentials, $_GET['oauth_token'], $_GET['oauth_verifier']);
            
            $lti_oauth_token = new LTIOAuthToken;

            $lti_oauth_token->key = $tokenCredentials->getIdentifier();
            $lti_oauth_token->secret = $tokenCredentials->getSecret();

            $lti_oauth_token->save();
            
            
            // Store the token credentials in session for later use.
            $request->session()->put('token_credentials', serialize($tokenCredentials));

            // this basically just redirecting to the current page so that the query string is removed.
            return redirect()->to((string) $oauth1->getConfig()->getCallbackUri());
        } else {
            // STEP 1: Obtain a temporary credentials (also known as the request token)
            $temporaryCredentials = $oauth1->requestTemporaryCredentials();

            // Store the temporary credentials in session so we can use it on step 3.
            $request->session()->put('temporary_credentials', serialize($temporaryCredentials));
            
            // STEP 2: Generate and redirect user to authorization URI.
            $authorizationUri = $oauth1->buildAuthorizationUri($temporaryCredentials);
            return redirect()->to($authorizationUri);
        }
    }

    public function page() {
        return "<a href=\"" . url('/oauth1/connect') . "\">Connect to LRS</a>";
    }
    
    public static function saveLTIResourceToLRS($req_data = []) {
        
    $oauth1 = \Risan\OAuth1\OAuth1Factory::create([
        'client_credentials_identifier' => '14e049915adf4e079df716f602796ab8',
        'client_credentials_secret' => 'UH8f1B92U33df9LU',
        'temporary_credentials_uri' => 'http://lrs.curriki.org:8000/xapi/OAuth/initiate',
        'authorization_uri' => 'http://lrs.curriki.org:8000/xapi/OAuth/authorize',
        'token_credentials_uri' => 'http://lrs.curriki.org:8000/xapi/OAuth/token',
        'callback_uri' => 'https://www.curriki.org/curriki-xapi-app/public/oauth1/connect',
    ]);
    
    $token = LTIOAuthToken::where('id',1)->first();
    
    if ($token['key'] != null && $token['secret'] != null) {
            $tokenCredentials = new TokenCredentials($token['key'], $token['secret']);
    }


    // Get back the previosuly obtain token credentials (step 3).
//    $tokenCredentials = unserialize($request->session()->get('token_credentials'));
    //print_r($tokenCredentials);
    $oauth1->setTokenCredentials($tokenCredentials);

    
    $data = array();
//    $data['studentName'] = 'Ali Yahoo Mehdi';
//    $data['studentEmail'] = 'imcoder001@gmail.com';
//    $data['verbId'] = 'http://adlnet.gov/expapi/verbs/scored';
//    $data['verbDescription'] = 'attained grade for';
//    $data['assignmentName'] = 'Test assignment';
//    $data['assignmentType'] = 'http://adlnet.gov/expapi/activities/assessment';
//    $data['assignmentId'] = 'http://localhost/curriki-moodle/mod/assign/view.php?id=90';
//    $data['rawScore'] = '0';
//    $data['maxScore'] = '100';
//    $data['minScore'] = '0';
//    $data['platform'] = 'Moodle';
//    $data['instructorEmail'] = 'lcunha@curriki.org';
//    $data['instructorName'] = 'LEONARDO CUNHA';
//    $data['mainSiteId'] = 'http://localhost/curriki-moodle';
//    $data['mainSiteName'] = 'Curriki Learning as a Service';
//    $data['mainSiteType'] = 'http://id.tincanapi.com/activitytype/lms';
//    $data['courseType'] = 'http://id.tincanapi.com/activitytype/lms/course';
//    $data['courseName'] = 'New course 1';
//    $data['courseId'] = 'http://localhost/curriki-moodle/course/view.php?id=48';

    $data['studentName'] = $req_data['studentName'];
    $data['studentEmail'] = $req_data['studentEmail'];
    $data['verbId'] = $req_data['verbId'];
    $data['verbDescription'] = $req_data['verbDescription'];
    $data['assignmentName'] = $req_data['assignmentName'];
    $data['assignmentType'] = $req_data['assignmentType'];
    $data['assignmentId'] = $req_data['assignmentId'];
    $data['rawScore'] = $req_data['rawScore'];
    $data['maxScore'] = $req_data['maxScore'];
    $data['minScore'] = $req_data['minScore'];
    $data['scaledScore'] = $req_data['scaledScore'];
    $data['platform'] = $req_data['platform'];
    $data['instructorEmail'] = $req_data['instructorEmail'];
    $data['instructorName'] = $req_data['instructorName'];
    $data['mainSiteId'] = $req_data['mainSiteId'];
    $data['mainSiteName'] = $req_data['mainSiteName'];
    $data['mainSiteType'] = $req_data['mainSiteType'];
    $data['courseType'] = $req_data['courseType'];
    $data['courseName'] = $req_data['courseName'];
    $data['courseId'] = $req_data['courseId'];
    $data['datesubmitted'] = $req_data['datesubmitted'];


    
    $dt = date('Y-m-d\TH:i:s.u\Z');

//    $body =<<<EOD
//    {
//        "verb": {
//            "id": "{$data['verbId']}",
//            "display": {
//                "en": "{$data['verbDescription']}"
//            }
//        },
//        "version": "1.0.0",
//        "timestamp": "{$dt}",
//        "object": {
//            "definition": {
//                "type": "{$data['assignmentType']}",
//                "name": {
//                    "en": "{$data['assignmentName']}"
//                }
//            },
//            "id": "{$data['assignmentId']}",
//            "objectType": "Activity"
//        },
//        "actor": {
//            "mbox": "mailto:{$data['studentEmail']}",
//            "name": "{$data['studentName']}",
//            "objectType": "Agent"
//        },
//        "stored": "{$dt}",
//        "result": {
//            "completion": true,
//            "score": {
//                "raw": {$data['rawScore']},
//                "max": {$data['maxScore']},
//                "scaled": {$data['scaledScore']},
//                "min": {$data['minScore']}
//            },
//            "success": true
//        },
//        "context": {
//            "platform": "{$data['platform']}",
//            "instructor": {
//                "mbox": "mailto:{$data['instructorEmail']}",
//                "name": "{$data['instructorName']}",
//                "objectType": "Agent"
//            },
//            "extensions": {
//                "http://lrs.learninglocker.net/define/extensions/info": {
//                    "event_name": "\\\\mod_assign\\\\event\\\\submission_graded",
//                    "https://github.com/xAPI-vle/moodle-logstore_xapi": "v4.4.0",
//                    "event_function": "\\\\src\\\\transformer\\\\events\\\\mod_assign\\\\assignment_graded",
//                    "http://moodle.org": "3.7.1+ (Build: 20190801)"
//                }
//            },
//            "language": "en",
//            "contextActivities": {
//                "category": [
//                    {
//                        "definition": {
//                            "type": "http://id.tincanapi.com/activitytype/source",
//                            "name": {
//                                "en": "{$data['platform']}"
//                            }
//                        },
//                        "id": "http://moodle.org"
//                    }
//                ],
//                "grouping": [
//                    {
//                        "definition": {
//                            "type": "{$data['mainSiteType']}",
//                            "name": {
//                                "en": "{$data['mainSiteName']}"
//                            }
//                        },
//                        "id": "{$data['mainSiteId']}"
//                    },
//                    {
//                        "definition": {
//                            "extensions": {
//                                "https://w3id.org/learning-analytics/learning-management-system/external-id": "",
//                                "https://w3id.org/learning-analytics/learning-management-system/short-id": "New course 1"
//                            },
//                            "type": "{$data['courseType']}",
//                            "name": {
//                                "en": "{$data['courseName']}"
//                            }
//                        },
//                        "id": "{$data['courseId']}"
//                    }
//                ]
//            }
//        },
//
//        "authority": {
//            "mbox": "mailto:{$data['studentEmail']}",
//            "name": "{$data['studentName']}",
//            "objectType": "Agent"
//        }
//    }
//EOD;
            
            
$body =<<<EOD
        {
        "verb": {
            "id": "{$data['verbId']}",
            "display": {
                "en": "{$data['verbDescription']}"
            }
        },
        "version": "1.0.0",
        "timestamp": "{$data['datesubmitted']}",
        "object": {
            "definition": {
                "type": "{$data['assignmentType']}",
                "name": {
                    "en": "{$data['assignmentName']}"
                }
            },
            "id": "{$data['assignmentId']}",
            "objectType": "Activity"
        },
        "actor": {
            "mbox": "mailto:{$data['studentEmail']}",
            "name": "{$data['studentName']}",
            "objectType": "Agent"
        },
        "stored": "{$dt}",
        "result": {
            "completion": true,
            "score": {
                "raw": {$data['rawScore']},
                "max": {$data['maxScore']},
                "scaled": {$data['scaledScore']},
                "min": {$data['minScore']}
            },
            "success": true
        },
        "context": {
            "platform": "{$data['platform']}",
            "instructor": {
                "mbox": "mailto:{$data['instructorEmail']}",
                "name": "{$data['instructorName']}",
                "objectType": "Agent"
            },
            "language": "en",
            "contextActivities": {
                "category": [
                    {
                        "definition": {
                            "type": "http://id.tincanapi.com/activitytype/source",
                            "name": {
                                "en": "{$data['platform']}"
                            }
                        },
                        "id": "{$data['mainSiteId']}"
                    }
                ],
                "other": [
                    {
                        "definition": {
                            "type": "http://adlnet.gov/expapi/activities/attempt",
                            "name": {
                                "en": "Attempt"
                            }
                        },
                        "id": "{$data['assignmentId']}"
                    }
                ],
                "grouping": [
                    {
                        "definition": {
                            "type": "{$data['mainSiteType']}",
                            "name": {
                                "en": "{$data['mainSiteName']}"
                            }
                        },
                        "id": "{$data['mainSiteId']}"
                    },
                    {
                        "definition": {
                            "type": "{$data['courseType']}",
                            "name": {
                                "en": "{$data['courseName']}"
                            }
                        },
                        "id": "{$data['courseId']}"
                    }
                ]
            }
        },
        "authority": {
            "mbox": "mailto:{$data['studentEmail']}",
            "name": "{$data['studentName']}",
            "objectType": "Agent"
        }
    }
EOD;
    //        echo $body;
    echo "<pre>";
    //$body = preg_replace( "/\r|\n/", "", $body );
    $data = json_decode($body, true);
    //echo "<pre>";
    //var_dump($data);
    //die();
    $postdata = json_encode($data);
    //print_r($body);
    $arr = ['body'=>$body];

    // STEP 4: Retrieve the user's tweets.
    // It will return the Psr\Http\Message\ResponseInterface instance.

    $response = $oauth1->post( 'http://lrs.curriki.org:8000/xapi/statements', [
        'body'=> $postdata
        ]);


    //$response = $oauth1->request('GET', 'http://lrs.curriki.org:8000/xapi/statements');

    // Convert the response to array and display it.

    print_r(json_decode($response->getBody()->getContents(), true));
    }
    
    public function save(Request $request){
        $lti_data = DB::table('wcl_lti_submission')
            ->join('lti_resources', 'wcl_lti_submission.ltiid', '=', 'lti_resources.lti_id')
            ->join('cur_users as cu1', 'wcl_lti_submission.userid', '=', 'cu1.ID')
            ->join('resources', 'resources.resourceid', '=', 'lti_resources.resourceid')
            ->join('cur_users as cu2', 'resources.lasteditorid', '=', 'cu2.ID')
            ->select('wcl_lti_submission.id as wcl_lti_submission_id', 'wcl_lti_submission.datesubmitted', 'gradepercent', 'originalgrade', 'cu1.display_name', 'cu1.user_email', 'resources.title', 'resources.resourceid', 'cu2.user_email as instructor_email', 'cu2.display_name as instructorName' )
            ->where('wcl_lti_submission.lrs_submitted', 0)
            ->get();
//        echo date('Y-m-d\TH:i:s.u\Z', $c);
//        dd($lti_data);
        foreach ($lti_data as $lti){
            $data = array();
            $data['studentName'] = $lti->display_name;
            $data['studentEmail'] = $lti->user_email;
            $data['verbId'] = "http://id.tincanapi.com/verb/completed";
            $data['verbDescription'] = "completed";
            $data['assignmentName'] = $lti->title;
            $data['assignmentType'] = "http://adlnet.gov/expapi/activities/assessment";
            $data['assignmentId'] = "https://www.curriki.org/oer/?rid=".$lti->resourceid;
            $data['rawScore'] = $lti->gradepercent;
            $data['scaledScore'] = $lti->originalgrade;
            $data['maxScore'] = 100;
            $data['minScore'] = 0;
            $data['platform'] = 'Curriki';
            $data['instructorEmail'] = $lti->instructor_email;
            $data['instructorName'] = $lti->instructorName;
            $data['mainSiteId'] = 'https://www.curriki.org';
            $data['mainSiteName'] = "Curriki Learning as a Service";
            $data['mainSiteType'] = "http://id.tincanapi.com/activitytype/lms";
            $data['courseType'] = "https://www.curriki.org/xapi/collection";    //get collection Name
            $data['courseName'] = "Collection Name";
            $data['courseId'] = "https://www.curriki.org/oer/?rid=".$lti->resourceid;
            $data['datesubmitted'] = date('Y-m-d\TH:i:s.u\Z', $lti->datesubmitted);
//            dd($lti);
            $this->saveLTIResourceToLRS($data);
            
            DB::table('wcl_lti_submission')->where('id',$lti->wcl_lti_submission_id)->update(['lrs_submitted'=>1]);
        }
        dd($lti_data);
        return 'test';
    }

}
