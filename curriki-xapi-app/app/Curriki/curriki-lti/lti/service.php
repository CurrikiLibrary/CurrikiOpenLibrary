<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * LTI web service endpoints
 *
 * @package mod_lti
 * @copyright  Copyright (c) 2011 Moodlerooms Inc. (http://www.moodlerooms.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Chris Scribner
 */

// define('NO_DEBUG_DISPLAY', true);
// define('NO_MOODLE_COOKIES', true);

// require_once(__DIR__ . "/../../config.php");
require_once('locallib.php');
require_once('servicelib.php');

// TODO: Switch to core oauthlib once implemented - MDL-30149.
//use mod_lti\service_exception_handler;
//use moodle\mod\lti as lti;
require_once 'OAuthBody.php';
//use ltiservice_basicoutcomes\local\service\basicoutcomes;
require_once('service/basicoutcomes/classes/local/service/basicoutcomes.php');

$rawbody = file_get_contents("php://input");

// $logrequests  = lti_should_log_request($rawbody);
// $errorhandler = new service_exception_handler($logrequests);

// Register our own error handler so we can always send valid XML response.
// set_exception_handler(array($errorhandler, 'handle'));

// if ($logrequests) {
//     lti_log_request($rawbody);
// }

$ok = true;
$type = null;
$toolproxy = false;

$consumerkey = get_oauth_key_from_headers(null, array(basicoutcomes::SCOPE_BASIC_OUTCOMES));

if ($consumerkey === false) {
    throw new Exception('Missing or invalid consumer key or access token.');
} else if (is_string($consumerkey)) {
    //$toolproxy = lti_get_tool_proxy_from_guid($consumerkey);
    // $toolproxy = lti_get_tool_proxy_from_guid($consumerkey);
    // if ($toolproxy !== false) {
    //     $secrets = array($toolproxy->secret);
    // } else if (!empty($tool)) {
    //     $secrets = array($typeconfig['password']);
    // } else {
    //     $secrets = lti_get_shared_secrets_by_key($consumerkey);
    // }
    /*
    array(1) {
    ["lnD6I5COsr7Hwaud4kSxLzHNQOBKKDQf"]=>
    string(32) "lnD6I5COsr7Hwaud4kSxLzHNQOBKKDQf"
    }
    */
    $password = null;
    global $entityManager;    
    $config = $entityManager->getRepository('CurrikiLti\Core\Entity\LtiTypeConfig')->findOneBy(array('name' => 'resourcekey', 'value' => $consumerkey));
    if(is_null($config)){
        throw new Exception('consumerkey not valid');
    }else{
        $config_password = $entityManager->getRepository('CurrikiLti\Core\Entity\LtiTypeConfig')->findOneBy(array('typeid' => $config->getTypeId(),'name' => 'password'));
        if(is_null($config_password)){
            throw new Exception('consumer password not valid');
        }else{
            $password = $config_password->getValue();
        }        
    }    
    //$secrets = lti_get_shared_secrets_by_key($consumerkey);
    $secrets = array($password => $password);    
    // $sharedsecret = lti_verify_message($consumerkey, $secrets, $rawbody);
    // if ($sharedsecret === false) {
    //     throw new Exception('Message signature not valid');
    // }
}

// TODO MDL-46023 Replace this code with a call to the new library.
$origentity = libxml_disable_entity_loader(true);
$xml = \simplexml_load_string($rawbody);
if (!$xml) {
    libxml_disable_entity_loader($origentity);
    throw new Exception('Invalid XML content');
}
libxml_disable_entity_loader($origentity);

$body = $xml->imsx_POXBody;
foreach ($body->children() as $child) {
    $messagetype = $child->getName();
}

// We know more about the message, update error handler to send better errors.
// $errorhandler->set_message_id(lti_parse_message_id($xml));
// $errorhandler->set_message_type($messagetype);

switch ($messagetype) {
    case 'replaceResultRequest':        
        $parsed = lti_parse_grade_replace_message($xml);               
        $lti_submission = $entityManager->getRepository('CurrikiLti\Core\Entity\LtiSubmission')->findOneBy( array('ltiid' => $parsed->instanceid, 'userid' => $parsed->userid, 'launchid' => $parsed->launchid) );
        if(is_null($lti_submission)){
            $lti_submission = new CurrikiLti\Core\Entity\LtiSubmission();
            $lti_submission->setLtiId($parsed->instanceid);
            $lti_submission->setUserId($parsed->userid);
            $lti_submission->setDateSubmitted(time());
            $lti_submission->setDateUpdated(time());
            $gradeval = $parsed->gradeval * floatval(100);
            $lti_submission->setGradePercent($gradeval);
            $lti_submission->setOriginalGrade($parsed->gradeval);
            $lti_submission->setLaunchId($parsed->launchid);
            $lti_submission->setState(1);
            $entityManager->persist($lti_submission);
            $entityManager->flush();
        }else{
            $lti_submission->setDateUpdated(time());
            $gradeval = $parsed->gradeval * floatval(100);
            $lti_submission->setGradePercent($gradeval);
            $lti_submission->setOriginalGrade($parsed->gradeval);
            $lti_submission->setState(2);
            $entityManager->flush();
        }        
                        
        $responsexml = lti_get_response_xml(
                'success',
                'Grade replace response',
                $parsed->messageid,
                'replaceResultResponse'
        );

        echo $responsexml->asXML();

        break;

    case 'readResultRequest':
        $parsed = lti_parse_grade_read_message($xml);

        // $ltiinstance = $DB->get_record('lti', array('id' => $parsed->instanceid));

        // if (!lti_accepts_grades($ltiinstance)) {
        //     throw new Exception('Tool does not accept grades');
        // }

        // // Getting the grade requires the context is set.
        // $context = context_course::instance($ltiinstance->course);
        // $PAGE->set_context($context);

        // lti_verify_sourcedid($ltiinstance, $parsed);

        // $grade = lti_read_grade($ltiinstance, $parsed->userid);

        $responsexml = lti_get_response_xml(
                'success',  // Empty grade is also 'success'.
                'Result read',
                $parsed->messageid,
                'readResultResponse'
        );

        $node = $responsexml->imsx_POXBody->readResultResponse;
        $node = $node->addChild('result')->addChild('resultScore');
        $node->addChild('language', 'en');
        $node->addChild('textString', isset($grade) ? $grade : '');

        echo $responsexml->asXML();

        break;

    case 'deleteResultRequest':        
        /*
        $ltiinstance = $DB->get_record('lti', array('id' => $parsed->instanceid));

        if (!lti_accepts_grades($ltiinstance)) {
            throw new Exception('Tool does not accept grades');
        }

        lti_verify_sourcedid($ltiinstance, $parsed);
        lti_set_session_user($parsed->userid);

        $gradestatus = lti_delete_grade($ltiinstance, $parsed->userid);

        if (!$gradestatus) {
            throw new Exception('Grade delete request');
        }
        */
        $responsexml = lti_get_response_xml(
                'success',
                'Grade delete request',
                $parsed->messageid,
                'deleteResultResponse'
        );

        echo $responsexml->asXML();

        break;

    default:
        // Fire an event if we get a web service request which we don't support directly.
        // This will allow others to extend the LTI services, which I expect to be a common
        // use case, at least until the spec matures.
        /*$data = new stdClass();
        $data->body = $rawbody;
        $data->xml = $xml;
        $data->messageid = lti_parse_message_id($xml);
        $data->messagetype = $messagetype;
        $data->consumerkey = $consumerkey;
        $data->sharedsecret = $sharedsecret;
        $eventdata = array();
        $eventdata['other'] = array();
        $eventdata['other']['messageid'] = $data->messageid;
        $eventdata['other']['messagetype'] = $messagetype;
        $eventdata['other']['consumerkey'] = $consumerkey;*/

        // Before firing the event, allow subplugins a chance to handle.
        // if (lti_extend_lti_services($data)) {
        //     break;
        // }

        // If an event handler handles the web service, it should set this global to true
        // So this code knows whether to send an "operation not supported" or not.
        global $ltiwebservicehandled;
        $ltiwebservicehandled = false;

        /*try {
            $event = \mod_lti\event\unknown_service_api_called::create($eventdata);
            $event->set_message_data($data);
            $event->trigger();
        } catch (Exception $e) {
            $ltiwebservicehandled = false;
        }*/

        if (!$ltiwebservicehandled) {
            $responsexml = lti_get_response_xml(
                'unsupported',
                'unsupported',
                 lti_parse_message_id($xml),
                 $messagetype
            );

            echo $responsexml->asXML();
        }

        break;
}
