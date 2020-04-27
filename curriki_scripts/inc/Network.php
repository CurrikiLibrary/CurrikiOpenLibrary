<?php
class Network {
    
    public static $headers = array();
    
  /**
   * Switch TOR to a new identity.
   **/
    public static function torNewIdentity($tor_ip = '127.0.0.1', $control_port = '9051', $auth_code = '') {
        $fp = fsockopen($tor_ip, $control_port, $errno, $errstr, 30);
        if (!$fp)
            return false; //can't connect to the control port

        fputs($fp, "AUTHENTICATE $auth_code\r\n");
        $response = fread($fp, 1024);
        list($code, $text) = explode(' ', $response, 2);
        if ($code != '250')
            return false; //authentication failed

        //send the request to for new identity
        fputs($fp, "signal NEWNYM\r\n");
        $response = fread($fp, 1024);
        list($code, $text) = explode(' ', $response, 2);
        if ($code != '250')
            return false; //signal failed

        fclose($fp);
        return true;
    }

  /**
   * Load the TOR's "magic cookie" from a file and encode it in hexadecimal.
   **/
    public static function torGetCookie($filename) {
        $cookie = file_get_contents($filename);
        //convert the cookie to hexadecimal
        $hex = '';
        for ($i=0;$i<strlen($cookie);$i++){
            $h = dechex(ord($cookie[$i]));
            $hex .= str_pad($h, 2, '0', STR_PAD_LEFT);
        }
        return strtoupper($hex);
    }

    public static function torGetUrl($url, $headers = array(), $method = 'GET',
            $body = array(), callable $authFunc = null, $authFuncParams = array()) {
        $valid = false;

        $content = "";

        do {
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_VERBOSE, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, true);
            curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
            curl_setopt($ch, CURLOPT_PROXY, "127.0.0.1");
            curl_setopt($ch, CURLOPT_PROXYPORT, 9050);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            
            if($method === 'POST') {
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            }
            
            $content = curl_exec($ch);

            $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);

            if($response_code == 200 && $content != ""){
                $valid = true;
            } else {
                
                //var_dump($headers);
                
                //throw new Exception("$response_code - Unable to load URL '$url'. Message: " . $content);

                Network::torNewIdentity();
                
                if($authFunc != null && is_callable($authFunc)) {
                    call_user_func_array($authFunc, $authFuncParams);
                }
            }
        } while ($valid === false);

        return $content;
    }
}
