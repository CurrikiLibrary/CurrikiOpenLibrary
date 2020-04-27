<?php
namespace CurrikiLti\Core\Services\Lti;
use Doctrine\ORM\EntityManager;

class Service {
    public $entityManager;
    public function execute()
    {
        global $entityManager;
        $entityManager = $this->entityManager;                
        require_once base_path('app/Curriki/curriki-lti/lti/service.php');
        die();      
    }
}