<?php

define('ACC_ID', '414788');
define('CLIENT_FD_ID', '126204');
define('LIST_ID', '212584');

require_once('lib/iContactApi.php');

iContactApi::getInstance()->setConfig(array(
    'appId' => 'boEs5tf2sDbXEty1JT3Uwx7QHTwvp7cq',
    'apiPassword' => 'Te mp/123',
    'apiUsername' => 'cur riki-dev'
));

echo '<pre>';
$oiContact = iContactApi::getInstance();
$oiContact->setAccountId(ACC_ID);
$oiContact->setClientFolderId(CLIENT_FD_ID);
$oiContact->useSandbox(true);

$statuses = array();

try {
    //*********************Add**********************//
    $response = $oiContact->addContact(
            $sEmail = 'joe@shmoe.com', 
            $sStatus = 'normal', 
            $sPrefix = null, 
            $sFirstName = 'Joe', 
            $sLastName = 'Shmoe', 
            $sSuffix = null, 
            $sStreet = '123 Somewhere Ln', 
            $sStreet2 = 'Apt 12', 
            $sCity = 'Somewhere', 
            $sState = 'NW', 
            $sPostalCode = '12345', 
            $sPhone = '123-456-7890', 
            $sFax = '123-456-7890', 
            $sBusiness = null
        );
    $response2 = $oiContact->subscribeContactToList($response->contactId, LIST_ID, 'normal');

    //*********************Delete********************** //
    $contacts = $oiContact->getContactBy('email','joe@shmoe.com');
    $contact = $contacts->contacts[0];
    if (!empty($contact->contactId))
        $response = $oiContact->deleteContact($contact->contactId);
    print_r($response);
    print_r($response2);
    $statuses[] = '1';
} catch (Exception $oException) { 
    $error = 'ERROR:'
    .print_r($oiContact->getErrors(),1)
    .print_r($oiContact->getLastRequest(),1)
    .print_r($oiContact->getLastResponse(),1);
    $statuses[] = '0';
}