<?php
require_once 'OAuthBody.php';

function lti_parse_grade_replace_message($xml) {
    $node = $xml->imsx_POXBody->replaceResultRequest->resultRecord->sourcedGUID->sourcedId;
    $resultjson = json_decode((string)$node);
    if ( is_null($resultjson) ) {
        throw new Exception('Invalid sourcedId in result message');
    }
    $node = $xml->imsx_POXBody->replaceResultRequest->resultRecord->result->resultScore->textString;

    $score = (string) $node;
    if ( ! is_numeric($score) ) {
        throw new Exception('Score must be numeric');
    }
    $grade = floatval($score);
    if ( $grade < 0.0 || $grade > 1.0 ) {
        throw new Exception('Score not between 0.0 and 1.0');
    }

    $parsed = new stdClass();
    $parsed->gradeval = $grade;

    $parsed->instanceid = $resultjson->data->instanceid;
    $parsed->userid = $resultjson->data->userid;
    $parsed->launchid = $resultjson->data->launchid;
    $parsed->typeid = $resultjson->data->typeid;
    $parsed->sourcedidhash = $resultjson->hash;

    $parsed->messageid = lti_parse_message_id($xml);

    return $parsed;
}

function lti_parse_message_id($xml) {
    if (empty($xml->imsx_POXHeader)) {
        return '';
    }

    $node = $xml->imsx_POXHeader->imsx_POXRequestHeaderInfo->imsx_messageIdentifier;
    $messageid = (string)$node;

    return $messageid;
}

function lti_get_response_xml($codemajor, $description, $messageref, $messagetype) {
    $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><imsx_POXEnvelopeResponse />');
    $xml->addAttribute('xmlns', 'http://www.imsglobal.org/services/ltiv1p1/xsd/imsoms_v1p0');

    $headerinfo = $xml->addChild('imsx_POXHeader')->addChild('imsx_POXResponseHeaderInfo');

    $headerinfo->addChild('imsx_version', 'V1.0');
    $headerinfo->addChild('imsx_messageIdentifier', (string)mt_rand());

    $statusinfo = $headerinfo->addChild('imsx_statusInfo');
    $statusinfo->addchild('imsx_codeMajor', $codemajor);
    $statusinfo->addChild('imsx_severity', 'status');
    $statusinfo->addChild('imsx_description', $description);
    $statusinfo->addChild('imsx_messageRefIdentifier', $messageref);
    $incomingtype = str_replace('Response', 'Request', $messagetype);
    $statusinfo->addChild('imsx_operationRefIdentifier', $incomingtype);

    $xml->addChild('imsx_POXBody')->addChild($messagetype);

    return $xml;
}

function lti_parse_grade_read_message($xml) {
    $node = $xml->imsx_POXBody->readResultRequest->resultRecord->sourcedGUID->sourcedId;
    $resultjson = json_decode((string)$node);
    if ( is_null($resultjson) ) {
        throw new Exception('Invalid sourcedId in result message');
    }

    $parsed = new stdClass();
    $parsed->instanceid = $resultjson->data->instanceid;
    $parsed->userid = $resultjson->data->userid;
    $parsed->launchid = $resultjson->data->launchid;
    $parsed->typeid = $resultjson->data->typeid;
    $parsed->sourcedidhash = $resultjson->hash;

    $parsed->messageid = lti_parse_message_id($xml);

    return $parsed;
}

function lti_parse_grade_delete_message($xml) {
    $node = $xml->imsx_POXBody->deleteResultRequest->resultRecord->sourcedGUID->sourcedId;
    $resultjson = json_decode((string)$node);
    if ( is_null($resultjson) ) {
        throw new Exception('Invalid sourcedId in result message');
    }

    $parsed = new stdClass();
    $parsed->instanceid = $resultjson->data->instanceid;
    $parsed->userid = $resultjson->data->userid;
    $parsed->launchid = $resultjson->data->launchid;
    $parsed->typeid = $resultjson->data->typeid;
    $parsed->sourcedidhash = $resultjson->hash;

    $parsed->messageid = lti_parse_message_id($xml);

    return $parsed;
}

function lti_verify_message($key, $sharedsecrets, $body, $headers = null) {
    foreach ($sharedsecrets as $secret) {
        $signaturefailed = false;

        try {
            // TODO: Switch to core oauthlib once implemented - MDL-30149.
            handle_oauth_body_post($key, $secret, $body, $headers);
        } catch (Exception $e) {
            //throw new Exception('LTI message verification failed: '.$e->getMessage());
            $signaturefailed = true;
        }

        if (!$signaturefailed) {
            return $secret; // Return the secret used to sign the message).
        }
    }

    return false;
}