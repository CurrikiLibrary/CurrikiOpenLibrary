<?php
namespace CurrikiSite\Core;

/**
 * Description of Singleton
 *
 * @author waqarmuneer
 */

trait Singleton
{
    protected static $instance = null; 
    
    public static function getInstance(){
        if(self::$instance === null){            
            self::$instance = new self();            
        }                
        return self::$instance;
    }    
}