<?php

class Scrape
{
    public $_url = null;
    public $_ch = null;
    public $_scrap_app_main_dir = null;
    public $_result = null;
    
    public function __construct($init_param = null)
    {        
        $this->_ch = curl_init();
        if(is_array($init_param))
        {
            if( isset($init_param["scrap_app_main_dir"]) )
            {
                $this->_scrap_app_main_dir = $init_param["scrap_app_main_dir"];
            }
        }        
    }
    
    public function init_simple_request_setting() 
    {        
        curl_setopt ($this->_ch, CURLOPT_POST, 0); 
        curl_setopt($this->_ch, CURLOPT_URL, $this->_url);
        curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, true);        
        curl_setopt($this->_ch, CURLOPT_HEADER, false);        
        curl_setopt($this->_ch, CURLOPT_FOLLOWLOCATION, true);        
        curl_setopt($this->_ch, CURLOPT_ENCODING, "");        
        curl_setopt($this->_ch, CURLOPT_AUTOREFERER, true);        
        curl_setopt($this->_ch, CURLOPT_CONNECTTIMEOUT, 120);        
        curl_setopt($this->_ch, CURLOPT_TIMEOUT, 120);        
        curl_setopt($this->_ch, CURLOPT_MAXREDIRS, 10);        
        curl_setopt($this->_ch, CURLOPT_SSL_VERIFYPEER, false);        
    }
    
    public function init_login_request_setting($post_fields_string) 
    {
        curl_setopt($this->_ch, CURLOPT_URL, $this->_url); 
        $cookie = $this->_scrap_app_main_dir.'tmp/'.'cookies.txt';
        $timeout = 30;
        curl_setopt($this->_ch, CURLOPT_TIMEOUT,         10); 
        curl_setopt($this->_ch, CURLOPT_CONNECTTIMEOUT,  $timeout );
        curl_setopt($this->_ch, CURLOPT_COOKIEJAR,       $cookie);
        curl_setopt($this->_ch, CURLOPT_COOKIEFILE,      $cookie);

        curl_setopt ($this->_ch, CURLOPT_POST, 1);
        curl_setopt ($this->_ch,CURLOPT_POSTFIELDS,$post_fields_string);

        curl_setopt($this->_ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($this->_ch, CURLOPT_VERBOSE, 1);
//        curl_setopt($this->_ch, CURLOPT_HEADER, 1);
        $agent = 'Mozilla/5.0 (X11; U; Linux i686; pl-PL; rv:1.9.0.2) Gecko/20121223 Ubuntu/9.25 (jaunty) Firefox/3.8';
        curl_setopt($this->_ch, CURLOPT_USERAGENT,$agent);
        
    }
    public function init_simple_request_with_param_setting($post_fields_string) 
    {
        curl_setopt($this->_ch, CURLOPT_URL, $this->_url); 
        $cookie = $this->_scrap_app_main_dir.'tmp/'.'cookies.txt';
        $timeout = 30;
        curl_setopt($this->_ch, CURLOPT_TIMEOUT,         10); 
        curl_setopt($this->_ch, CURLOPT_CONNECTTIMEOUT,  $timeout );
        curl_setopt($this->_ch, CURLOPT_COOKIEJAR,       $cookie);
        curl_setopt($this->_ch, CURLOPT_COOKIEFILE,      $cookie);

        curl_setopt ($this->_ch, CURLOPT_POST, 1);
        curl_setopt ($this->_ch,CURLOPT_POSTFIELDS,$post_fields_string);

        curl_setopt($this->_ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($this->_ch, CURLOPT_VERBOSE, 1);
//        curl_setopt($this->_ch, CURLOPT_HEADER, 1);
        $agent = 'Mozilla/5.0 (X11; U; Linux i686; pl-PL; rv:1.9.0.2) Gecko/20121223 Ubuntu/9.25 (jaunty) Firefox/3.8';
        curl_setopt($this->_ch, CURLOPT_USERAGENT,$agent);
        
    }
    
    public function exe_requrest() 
    {
        $this->_result = curl_exec($this->_ch);
    }
    
    public function save_file($file_full_path,$content) 
    {
        $fp = fopen( $file_full_path ,"w");
        fwrite($fp,$content);
        fclose($fp);
        chmod($file_full_path, 0777);
    }
    
    public function __destruct() 
    {        
        curl_close($this->_ch); 
    }
}

?>