<?php
namespace CurrikiLti\Core\Services\Lti;
use Doctrine\ORM\EntityManager;
use CurrikiLti\Core\Entity\LaaS\Progress as ProgressEntity;

class Progress {

    protected $entityManager = null;    
    public $userid = null;
    public $resourceid = null;
    public $lti_resource_component = null;
    
    public function __construct(EntityManager $entityManager, ProgressEntity $progress_entity)
    {
        $this->entityManager = $entityManager;        
    }

    public function setExternalModule($module_id, $module_type, $site)
    {
        
    }
}