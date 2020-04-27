<?php
class ToolConsumer
{

/**
 * Local name of tool consumer.
 *
 * @var string $name
 */
    public $name = null;
/**
 * Shared secret.
 *
 * @var string $secret
 */
    public $secret = null;
/**
 * LTI version (as reported by last tool consumer connection).
 *
 * @var string $ltiVersion
 */
    public $ltiVersion = null;
/**
 * Name of tool consumer (as reported by last tool consumer connection).
 *
 * @var string $consumerName
 */
    public $consumerName = null;
/**
 * Tool consumer version (as reported by last tool consumer connection).
 *
 * @var string $consumerVersion
 */
    public $consumerVersion = null;
/**
 * Tool consumer GUID (as reported by first tool consumer connection).
 *
 * @var string $consumerGuid
 */
    public $consumerGuid = null;
/**
 * Optional CSS path (as reported by last tool consumer connection).
 *
 * @var string $cssPath
 */
    public $cssPath = null;
/**
 * Whether the tool consumer instance is protected by matching the consumer_guid value in incoming requests.
 *
 * @var boolean $protected
 */
    public $protected = false;
/**
 * Whether the tool consumer instance is enabled to accept incoming connection requests.
 *
 * @var boolean $enabled
 */
    public $enabled = false;
/**
 * Date/time from which the the tool consumer instance is enabled to accept incoming connection requests.
 *
 * @var datetime $enableFrom
 */
    public $enableFrom = null;
/**
 * Date/time until which the tool consumer instance is enabled to accept incoming connection requests.
 *
 * @var datetime $enableUntil
 */
    public $enableUntil = null;
/**
 * Date of last connection from this tool consumer.
 *
 * @var datetime $lastAccess
 */
    public $lastAccess = null;
/**
 * Default scope to use when generating an Id value for a user.
 *
 * @var int $idScope
 */
    public $idScope = 0;
/**
 * Default email address (or email domain) to use when no email address is provided for a user.
 *
 * @var string $defaultEmail
 */
    public $defaultEmail = '';
/**
 * Setting values (LTI parameters, custom parameters and local parameters).
 *
 * @var array $settings
 */
    public $settings = null;
/**
 * Date/time when the object was created.
 *
 * @var datetime $created
 */
    public $created = null;
/**
 * Date/time when the object was last updated.
 *
 * @var datetime $updated
 */
    public $updated = null;

/**
 * Consumer ID value.
 *
 * @var int $id
 */
    private $id = null;
/**
 * Consumer key value.
 *
 * @var string $key
 */
    private $key = null;
/**
 * Whether the settings value have changed since last saved.
 *
 * @var boolean $settingsChanged
 */
    private $settingsChanged = false;
/**
 * Data connector object or string.
 *
 * @var mixed $dataConnector
 */
    private $dataConnector = null;

/**
 * Class constructor.
 *
 * @param string  $key             Consumer key
 * @param mixed   $dataConnector   A data connector object
 * @param boolean $autoEnable      true if the tool consumers is to be enabled automatically (optional, default is false)
 */
    public function __construct($key = null, $dataConnector = null, $autoEnable = false)
    {
        
        
        $this->initialize_consumer();
        
        /*
        if (empty($dataConnector)) {
            $dataConnector = DataConnector\DataConnector::getDataConnector();
        }
        $this->dataConnector = $dataConnector;
        if (!empty($key)) {
            $this->load($key, $autoEnable);
        } else {
            $this->secret = DataConnector\DataConnector::getRandomString(32);
        }
         * 
         */

    }

/**
 * Initialise the tool consumer.
 */
    public function initialize_consumer()
    {

        $this->id = null;
        $this->key = null;
        $this->name = null;
        $this->secret = null;
        $this->ltiVersion = null;
        $this->consumerName = null;
        $this->consumerVersion = null;
        $this->consumerGuid = null;
        $this->profile = null;
        $this->toolProxy = null;
        $this->settings = array();
        $this->protected = false;
        $this->enabled = false;
        $this->enableFrom = null;
        $this->enableUntil = null;
        $this->lastAccess = null;
        $this->idScope = 0;
        $this->defaultEmail = '';
        $this->created = null;
        $this->updated = null;

    }
    
    public function getKey()
    {

        return $this->key;
    }
    
     public function getRecordId()
    {

        return $this->id;

    }
    
    public function setKey($key)
    {

        $this->key = $key;

    }


    public function setRecordId($id)
    {

        $this->id = $id;

    }

    
    public function save()
    {
                
        global $wpdb;
        
        $now = date("YYYY-MM-DD");        
        
        $ok = false;
                    
        if(!$this->getRecordId())
        {
            $wpdb->insert( 
                    'lti2_consumer', 
            array( 
                    'name' => $this->name, 
                    'consumer_key256' => $this->getKey(), 
                    'secret' => $this->secret, 
                    'lti_version' => $this->ltiVersion, 
                    'enabled' => $this->enabled, 
                    'created' => $now, 
                    'updated' => $now
                ), 
                array('%s', '%s', '%s', '%s', '%d', '%s', '%s')
            );
            
            $consumer_pk = $wpdb->insert_id;
            $wpdb->insert( 
                    'lti_consumer_user', 
            array( 
                    'consumer_pk' => $consumer_pk, 
                    'userid' => get_current_user_id(),                    
                    'lms' => "$this->lms"                    
                ), 
                array('%d', '%d', '%s')
            );
            
            $ok = true;
        }else if($this->getRecordId())
        { 
        
            $wpdb->update( 
                    'lti2_consumer', 
            array( 
                    'name' => $this->name, 
                    'consumer_key256' => $this->getKey(), 
                    'secret' => $this->secret,                     
                    'updated' => $now
                ), 
                array( 'consumer_pk' => $this->getRecordId() ),
                array('%s','%s', '%s', '%s',),
                array( '%d' ) 
            );
            $ok = true;
        }
        
        return $ok;
    }
    
    public function getAllCredentialsByUserId($userid)
    {
        global $wpdb;       
        $u_c_q = "select * from lti2_consumer c
                    inner join lti_consumer_user cu 
                    on c.consumer_pk = cu.consumer_pk
                    where cu.userid = ".$userid.
                    " group by cu.lms"
                    ;
                    return $wpdb->get_results($u_c_q);        
    }
    
    public function getByIdAndCurrentUserAndLMS()
    {       
        global $wpdb;
        /*
        $q_me = "SELECT * FROM users where userid = '" . get_current_user_id() . "'";
        $me = $wpdb->get_row($q_me);
        */
        $u_c_q = "select 
        c.consumer_pk,
        c.name,
        c.consumer_key256,
        c.secret
        from lti2_consumer c
        inner join lti_consumer_user cu 
        on c.consumer_pk = cu.consumer_pk
        where c.consumer_pk = ".$this->getRecordId().
        " and cu.userid = ".get_current_user_id().
        " and cu.lms = '".  $this->lms."'"        
        ;
        
        return $wpdb->get_row($u_c_q);
    }
    
    public function getByUserId()
    {
        global $wpdb;
        /*
        $q_me = "SELECT * FROM users where userid = '" . get_current_user_id() . "'";
        $me = $wpdb->get_row($q_me);
        */
        $u_c_q = "select 
        c.consumer_pk,
        c.name,
        c.consumer_key256,
        c.secret
        from lti2_consumer c
        inner join lti_consumer_user cu 
        on c.consumer_pk = cu.consumer_pk
        where cu.userid = ".get_current_user_id();
        return $wpdb->get_row($u_c_q);
    }
    public function getByKey()
    {
        global $wpdb;
        /*
        $q_me = "SELECT * FROM users where userid = '" . get_current_user_id() . "'";
        $me = $wpdb->get_row($q_me);
        */
        $rs = null;
        if($this->getKey())
        {
           $u_c_q = "
                        select 
                        c.consumer_pk,
                        c.name,
                        c.consumer_key256,
                        c.secret,
                        cu.lms
                        from lti2_consumer c
                        inner join lti_consumer_user cu 
                        on c.consumer_pk = cu.consumer_pk
                        where c.consumer_key256 = '{$this->getKey()}'
                    ";        
                    $rs = $wpdb->get_row($u_c_q); 
        }
        return $rs;
    }
    public function getByUserIdAndLMS($lms)
    {
        global $wpdb;
        /*
        $q_me = "SELECT * FROM users where userid = '" . get_current_user_id() . "'";
        $me = $wpdb->get_row($q_me);
        */
        $u_c_q = "select 
        c.consumer_pk,
        c.name,
        c.consumer_key256,
        c.secret,
        cu.lms
        from lti2_consumer c
        inner join lti_consumer_user cu 
        on c.consumer_pk = cu.consumer_pk
        where cu.userid = ".get_current_user_id()." and cu.lms = '$lms'"
        ;        
        return $wpdb->get_row($u_c_q);
    }
    public function getAllByUserIdAndLMS($lms)
    {
        global $wpdb;
        /*
        $q_me = "SELECT * FROM users where userid = '" . get_current_user_id() . "'";
        $me = $wpdb->get_row($q_me);
        */
        $u_c_q = "select 
        c.consumer_pk,
        c.name,
        c.consumer_key256,
        c.secret,
        cu.lms
        from lti2_consumer c
        inner join lti_consumer_user cu 
        on c.consumer_pk = cu.consumer_pk
        where cu.userid = ".get_current_user_id()." and cu.lms = '$lms'"
        ;        
        return $wpdb->get_results($u_c_q);
    }
    
    /*

    public function initialise()
    {

        $this->initialize();

    }

    public function save()
    {
        $ok = null;

        $ok = $this->dataConnector->saveToolConsumer($this);
        if ($ok) {
            $this->settingsChanged = false;
        }

        return $ok;

    }

    public function delete()
    {
        
        //return $this->dataConnector->deleteToolConsumer($this);
        return null;

    }


    public function getDataConnector()
    {

        return $this->dataConnector;

    }


    public function getIsAvailable()
    {

        $ok = $this->enabled;

        $now = time();
        if ($ok && !is_null($this->enableFrom)) {
            $ok = $this->enableFrom <= $now;
        }
        if ($ok && !is_null($this->enableUntil)) {
            $ok = $this->enableUntil > $now;
        }

        return $ok;

    }


    public function getSetting($name, $default = '')
    {

        if (array_key_exists($name, $this->settings)) {
            $value = $this->settings[$name];
        } else {
            $value = $default;
        }

        return $value;

    }


    public function setSetting($name, $value = null)
    {

        $old_value = $this->getSetting($name);
        if ($value !== $old_value) {
            if (!empty($value)) {
                $this->settings[$name] = $value;
            } else {
                unset($this->settings[$name]);
            }
            $this->settingsChanged = true;
        }

    }


    public function getSettings()
    {

        return $this->settings;

    }


    public function setSettings($settings)
    {

        $this->settings = $settings;

    }


    public function saveSettings()
    {

        if ($this->settingsChanged) {
            $ok = $this->save();
        } else {
            $ok = true;
        }

        return $ok;

    }

    public function hasToolSettingsService()
    {

        $url = $this->getSetting('custom_system_setting_url');

        return !empty($url);

    }

    public function getToolSettings($simple = true)
    {
        
        
        $url = $this->getSetting('custom_system_setting_url');
        $service = new Service\ToolSettings($this, $url, $simple);
        $response = $service->get();
       
        //return $response;
        return null;

    }


    public function setToolSettings($settings = array())
    {
        
        $url = $this->getSetting('custom_system_setting_url');
        $service = new Service\ToolSettings($this, $url);
        $response = $service->set($settings);
        
        //return $response;
        return null;
    }


   
    public function signParameters($url, $type, $version, $params)
    {

        if (!empty($url)) {
// Check for query parameters which need to be included in the signature
            $queryParams = array();
            $queryString = parse_url($url, PHP_URL_QUERY);
            if (!is_null($queryString)) {
                $queryItems = explode('&', $queryString);
                foreach ($queryItems as $item) {
                    if (strpos($item, '=') !== false) {
                        list($name, $value) = explode('=', $item);
                        $queryParams[urldecode($name)] = urldecode($value);
                    } else {
                        $queryParams[urldecode($item)] = '';
                    }
                }
            }
            $params = $params + $queryParams;
// Add standard parameters
            $params['lti_version'] = $version;
            $params['lti_message_type'] = $type;
            $params['oauth_callback'] = 'about:blank';
// Add OAuth signature
            $hmacMethod = new OAuth\OAuthSignatureMethod_HMAC_SHA1();
            $consumer = new OAuth\OAuthConsumer($this->getKey(), $this->secret, null);
            $req = OAuth\OAuthRequest::from_consumer_and_token($consumer, null, 'POST', $url, $params);
            $req->sign_request($hmacMethod, $consumer, null);
            $params = $req->get_parameters();
// Remove parameters being passed on the query string
            foreach (array_keys($queryParams) as $name) {
                unset($params[$name]);
            }
        }

        return $params;

    }
    

    public static function addSignature($endpoint, $consumerKey, $consumerSecret, $data, $method = 'POST', $type = null)
    {

        $params = array();
        if (is_array($data)) {
            $params = $data;
        }
// Check for query parameters which need to be included in the signature
        $queryParams = array();
        $queryString = parse_url($endpoint, PHP_URL_QUERY);
        if (!is_null($queryString)) {
            $queryItems = explode('&', $queryString);
            foreach ($queryItems as $item) {
                if (strpos($item, '=') !== false) {
                    list($name, $value) = explode('=', $item);
                    $queryParams[urldecode($name)] = urldecode($value);
                } else {
                    $queryParams[urldecode($item)] = '';
                }
            }
            $params = $params + $queryParams;
        }

        if (!is_array($data)) {
// Calculate body hash
            $hash = base64_encode(sha1($data, true));
            $params['oauth_body_hash'] = $hash;
        }

// Add OAuth signature
        $hmacMethod = new OAuth\OAuthSignatureMethod_HMAC_SHA1();
        $oauthConsumer = new OAuth\OAuthConsumer($consumerKey, $consumerSecret, null);
        $oauthReq = OAuth\OAuthRequest::from_consumer_and_token($oauthConsumer, null, $method, $endpoint, $params);
        $oauthReq->sign_request($hmacMethod, $oauthConsumer, null);
        $params = $oauthReq->get_parameters();
// Remove parameters being passed on the query string
        foreach (array_keys($queryParams) as $name) {
            unset($params[$name]);
        }

        if (!is_array($data)) {
            $header = $oauthReq->to_header();
            if (empty($data)) {
                if (!empty($type)) {
                    $header .= "\nAccept: {$type}";
                }
            } else if (isset($type)) {
                $header .= "\nContent-Type: {$type}";
                $header .= "\nContent-Length: " . strlen($data);
            }
            return $header;
        } else {
            return $params;
        }

    }
 

   
    public function doServiceRequest($service, $method, $format, $data)
    {

        $header = ToolConsumer::addSignature($service->endpoint, $this->getKey(), $this->secret, $data, $method, $format);

// Connect to tool consumer
        $http = new HTTPMessage($service->endpoint, $method, $data, $header);
// Parse JSON response
        if ($http->send() && !empty($http->response)) {
            $http->responseJson = json_decode($http->response);
            $http->ok = !is_null($http->responseJson);
        }

        return $http;

    }


    public static function fromRecordId($id, $dataConnector)
    {

        
        $toolConsumer = new ToolConsumer(null, $dataConnector);

        $toolConsumer->initialize();
        $toolConsumer->setRecordId($id);
        if (!$dataConnector->loadToolConsumer($toolConsumer)) {
            $toolConsumer->initialize();
        }
        
        $toolConsumer = null;
        return $toolConsumer;

    }


    private function load($key, $autoEnable = false)
    {
        $ok = null;
        
        $this->key = $key;
        $ok = $this->dataConnector->loadToolConsumer($this);
        if (!$ok) {
            $this->enabled = $autoEnable;
        }
        
        return $ok;

    }
    */
}
