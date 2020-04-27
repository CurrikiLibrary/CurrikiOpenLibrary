<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Curriki_controller
 *
 * @author waqarmuneer
 */
class Curriki_controller 
{
    public $request = array();
    public $action = null;
    //Constructor
    public function __construct()
    {            
        $this->request = array_merge($this->request, $_GET);        
        $this->execute_route();
    }
    public function execute_route()
    {           
        $this->request = array_merge($this->request, $_GET);        
        if(array_key_exists("action", $this->request))
        {
            $this->action = $this->request["action"];
        }
        if(array_key_exists("action", $this->request) && method_exists($this, $this->request["action"]) )
        {                        
            call_user_func( array($this, $this->request["action"]) );                                    
        }        
    }
    
    
    public function loadclass($filename) {
        return get_template_part('modules/community-pages/classes/'.$filename);
    }
    public function loadHelper($filename) {
        $this->loadclass($filename);         
    }
}
