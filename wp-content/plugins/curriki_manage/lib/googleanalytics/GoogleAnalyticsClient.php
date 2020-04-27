<?php
// Load the Google API PHP Client Library.
require_once __DIR__ . '/vendor/autoload.php';

function fetch_ga_records($startdate, $enddate , $current_paging){
    $ga_records = array();
    try{
        $analytics = initializeAnalytics();
        $profile = getFirstProfileId($analytics);
        $results = getResults($analytics, $profile , $startdate, $enddate, $current_paging);  
                
        $ga_records['ga_records'] = getRows($results);           
        $ga_records['paging_info'] = getPaginationInfo($results);        
        
    } catch (Exception $ex) {
        throw $ex;
    }
    
    return $ga_records;
}



function initializeAnalytics()
{
    
  $KEY_FILE_LOCATION = __DIR__ . '/Google Analytics API-48ba70eafcfe.json';    

  // Create and configure a new client object.
  $client = new Google_Client();
  $client->setApplicationName("Hello Analytics Reporting");
  $client->setAuthConfig($KEY_FILE_LOCATION);
  $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
  $analytics = new Google_Service_Analytics($client);

  return $analytics;
}

function getFirstProfileId($analytics) {
  // Get the user's first view (profile) ID.

  // Get the list of accounts for the authorized user.
  $accounts = $analytics->management_accounts->listManagementAccounts();

  if (count($accounts->getItems()) > 0) {
    $items = $accounts->getItems();
    $firstAccountId = $items[0]->getId();

    // Get the list of properties for the authorized user.
    $properties = $analytics->management_webproperties
        ->listManagementWebproperties($firstAccountId);

    if (count($properties->getItems()) > 0) {
      $items = $properties->getItems();
      $firstPropertyId = $items[0]->getId();

      // Get the list of views (profiles) for the authorized user.
      $profiles = $analytics->management_profiles
          ->listManagementProfiles($firstAccountId, $firstPropertyId);

      if (count($profiles->getItems()) > 0) {
        $items = $profiles->getItems();

        // Return the first view (profile) ID.
        return $items[0]->getId();

      } else {
        throw new Exception('No views (profiles) found for this user.');
      }
    } else {
      throw new Exception('No properties found for this user.');
    }
  } else {
    throw new Exception('No accounts found for this user.');
  }
}

function getResults($analytics, $profileId , $startdate, $enddate, $current_paging) {
    
    $an = new stdClass();
    try{
        $optParams = array(
            'dimensions'=>'ga:pageTitle,ga:pagePath,ga:country',
            'start-index' => $current_paging['start-index'],
            'max-results'=>1000
            );
        
        $an = $analytics->data_ga->get(
            'ga:' . $profileId,
            $startdate,
            $enddate,
            'ga:pageviews',
            $optParams
        );
    }catch(Exception $ex) {
        throw $ex;
    }
   return $an;
}

function getRows($results) {
  $rows = array();
  if (count($results->getRows()) > 0) {
    // Get the profile name.
    //$profileName = $results->getProfileInfo()->getProfileName();   
    $rows = $results->getRows();                
  } 
  return $rows;
}


function getPaginationInfo(&$results) {
    $ga_next_page_url_str = $results->getNextLink();
    $ga_next_page_url_arr = array();
    parse_str($ga_next_page_url_str,$ga_next_page_url_arr);
    return array(
        "itemsPerPage" => $results->getItemsPerPage(),
        "totalResults" => $results->getTotalResults(),
        "previousLink" => $results->getPreviousLink(),
        "nextLink" => $results->getNextLink(),
        "nextLinkVars" => $ga_next_page_url_arr,      
    );
}
