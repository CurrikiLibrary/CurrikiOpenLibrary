<?php
/*  
  Author     : Waqar Muneer  
 */
require_once 'Scrape.php';

class search {

    //Misc Variables
    public $db = null;
    public $request = array();
    public $partnerid = 1;
    public $current_language = "eng";
    public $response = null;
    public $search_term = "";
    public $OER_page_url = "https://www.curriki.org/";
    public $site_url = "https://www.curriki.org/";
    public $searchURL = "https://www.curriki.org/search";
    public $groupTabURL = "https://www.curriki.org/search?partnerid=1";
    public $memberTabURL = "https://www.curriki.org/search?partnerid=1";
    public $searchRequestURL = "";
    public $newSearchURL = "";
    public $resourcesTabURL = "";        
    public $languages = array();        
    public $subjects = array();        
    public $subjectareas = array();        
    public $instructiontypes = array();        
    public $educationlevels = array();        
    
    //Constructor
    public function search() {        
    }
    public function execute() {                
        //$this->site_redirect_url = $req_url = "https://www.curriki.org/search/?type=Resource&phrase={$this->search_term}&language=&start=0&partnerid=1&searchall=&viewer=&branding=common&sort=rank1+desc";
        //type=Resource&phrase=Computer
        $this->search_form_url = "https://www.curriki.org/search"."/?language=&start=0&partnerid=1&searchall=&viewer=&branding=common&sort=rank1+desc&output=json";        
        
        $client = new Scrape();        
        $client->_url = "{$this->searchRequestURL}&tm=".  time();
        //$this->searchRequestURL = $client->_url;        
        $client->init_simple_request_setting();
        $client->exe_requrest();
        $josn_data = $client->_result;
        
        $response = json_decode($josn_data);
        if(is_object($response)){
            $this->response = $response->response;
            $this->languages = $response->languages;
            $this->subjects = $response->subjects;
            $this->subjectareas = $response->subjectareas;
            $this->instructiontypes = $response->instructiontypes;
            $this->educationlevels = $response->educationlevels;
            $this->standardtitles = $response->standardtitles;        
            $this->request = (array)$response->request;        
            $this->request['phrase'] = urldecode($this->search_term);
            $this->status = (array)$response->status;
        }else{
            $this->response = null;
        }        
    }
    
}
