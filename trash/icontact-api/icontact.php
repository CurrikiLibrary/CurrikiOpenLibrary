<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.

  Accept: text/xml Note: Values for this are text/xml or application/json
  Content-Type: text/xml Note: Values for this are text/xml or application/json
  Api-Version: 2.0
  Api-AppId: 4eOFlFJabcdenVoljgPv9av59C54alz4
  Api-Username: myusername
  API-Password: my_password
stdClass Object
(
    [contactId] => 67937396
    [prefix] => 
    [firstName] => Joe
    [lastName] => Shmoe
    [suffix] => 
    [street] => 123 Somewhere Ln
    [street2] => Apt 12
    [city] => Somewhere
    [state] => NW
    [postalCode] => 12345
    [phone] => 123-456-7890
    [fax] => 123-456-7890
    [business] => 
    [email] => joe@shmoe.com
    [createDate] => 2014-12-12 10:47:13
    [bounceCount] => 
    [status] => normal
)
'joe@shmoe.com', null, null, 'Joe', 'Shmoe', null, '123 Somewhere Ln', 'Apt 12', 'Somewhere', 'NW', '12345', '123-456-7890', '123-456-7890', null


 */

$url = 'https://app.sandbox.icontact.com/icp/a/414788/c/126204/';
$headers = array(
    'Accept: text/xml',
    'Content-Type: text/xml',
    'Api-Version: 2.0',
    'Api-AppId: boEs5tf2sDbXEty1JT3Uwx7QHTwvp7cq',
    'Api-Username: curriki-dev',
    'API-Password: Temp/123',
);
// create a new cURL resource
$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

// grab URL and pass it to the browser
$resp = curl_exec($ch);

echo '<pre>';   
echo htmlentities($resp);
echo '</pre>';
// close cURL resource, and free up system resources
curl_close($ch);
?>
