<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ToolConsumerModel
 *
 * @author waqarmuneer
 */
class ToolConsumerModel {
    //put your code here
    public $db = null;
    public $consumer_pk = null;
    public $userid = null;
    
    public function __construct()
    {        
    }
    
    public function getConsumerUser()
    {
        $stmt = $this->db->prepare("select * from lti2_consumer c
                                            inner join lti_consumer_user cu 
                                            on c.consumer_pk = cu.consumer_pk
                                            where c.consumer_pk = {$this->consumer_pk}"); 
        $stmt->execute(); 
        return $stmt->fetch(PDO::FETCH_OBJ);                    
    }
    public function getUser()
    {
        $stmt = $this->db->prepare("select * from users where userid = {$this->userid}"); 
        $stmt->execute(); 
        return $stmt->fetch(PDO::FETCH_OBJ);                    
    }
    
    public function updateUserSource($source_val)
    {
        $return = null;
        
        if($this->userid)
        {
            $qry = "UPDATE users SET source = '{$source_val}' WHERE userid = {$this->userid}";            
            $stmt = $this->db->prepare($qry); 
            $stmt->execute();            
        }
        
        return $return;
    }
    
}
