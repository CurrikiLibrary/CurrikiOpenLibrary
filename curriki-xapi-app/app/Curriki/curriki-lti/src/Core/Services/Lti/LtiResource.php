<?php
namespace CurrikiLti\Core\Services\Lti;
use Doctrine\ORM\EntityManager;
use CurrikiLti\Core\Entity\LtiResource as LtiResourceEntity;
use CurrikiLti\Core\Entity\LtiSubmission;

class LtiResource {
    
    protected $entityManager = null;
    protected $lti_resource = null;
    
    public function __construct(EntityManager $entityManager, LtiResourceEntity $lti_resource)
    {
        $this->entityManager = $entityManager;
        $this->lti_resource = $lti_resource;
    }

    public function save($data)
    {

        if(!isset($data['resourceid']) || !isset($data['ltiid']) ){
            return null;
        }

        $lti = $this->entityManager->getRepository('CurrikiLti\Core\Entity\Lti')->findOneBy(array('id' => $data['ltiid']));
        if(is_null($lti)){
            return null;
        }

        $where_clause = array("resourceid" => $data['resourceid']);
        $lti_resource = $this->entityManager->getRepository('CurrikiLti\Core\Entity\LtiResource')->findOneBy($where_clause);
        if(is_null($lti_resource)){            
            $this->lti_resource->setLti($lti);
            $this->lti_resource->setResourceId($data['resourceid']);
            $this->lti_resource->setComponent($data['component']);
            $this->lti_resource->setStatus($data['status']);
            $this->entityManager->persist($this->lti_resource);
            $this->entityManager->flush();
        }else{
            $lti_resource->setLti($lti);
            $lti_resource->setResourceId($data['resourceid']);
            $lti_resource->setComponent($data['component']);
            $lti_resource->setStatus($data['status']);            
            $this->entityManager->flush();
        }
        
        return array(
            "ltiid" => $data['ltiid'],
            "resourceid" => $lti_resource->getResourceId(),
            "component" => $lti_resource->getComponent(),
            "status" => $lti_resource->getStatus()
        );
    }
}