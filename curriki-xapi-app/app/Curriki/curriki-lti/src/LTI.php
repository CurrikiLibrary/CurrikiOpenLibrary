<?php
namespace CurrikiLti;
use Doctrine\ORM\EntityManager;

class LTI{

    private static $lti = null;
    private $entityManager = null;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getInstance($entityManager){
        if(is_null(self::$lti)){
            self::$lti = new LTI($entityManager);
        }
        return self::$lti;
    }
    
}
