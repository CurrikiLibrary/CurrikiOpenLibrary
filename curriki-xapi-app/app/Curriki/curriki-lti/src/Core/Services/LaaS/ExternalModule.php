<?php
namespace CurrikiLti\Core\Services\LaaS;
use Doctrine\ORM\EntityManager;
use CurrikiLti\Core\Entity\LaaS\ExternalModule as ExternalModuleEntity;

class ExternalModule {

    protected $entityManager = null;        
    public $external_module_entity = null;
    
    public function __construct(EntityManager $entityManager, ExternalModuleEntity $external_module_entity)
    {
        $this->entityManager = $entityManager;        
        $this->external_module_entity = $external_module_entity;        
    }

    public function getExternalModule($type, $external_type, $external_id, $site)
    {
        $external_module_entity = $this->entityManager->getRepository('CurrikiLti\Core\Entity\LaaS\ExternalModule')->findOneBy( array(
            'type' => $type, 'external_type' => $external_type, 'external_id' => $external_id, 'site' => $site
        ) );

        if(is_null($external_module_entity)){
            return null;
        }else{
            return array(
                "id" => $external_module_entity->getId(),
                "type" => $external_module_entity->getType(),
                "external_type" => $external_module_entity->getExternalType(),
                "external_id" => $external_module_entity->getExternalId(),
                "enable_user_registration" => $external_module_entity->getEnableUserRegistration(),
                "site" => $external_module_entity->getSite()
            );
        }

    }

    public function getExternalModuleEnabled($type, $external_type, $external_id, $site)
    {
        $external_module_entity = $this->entityManager->getRepository('CurrikiLti\Core\Entity\LaaS\ExternalModule')->findOneBy( array(
            'type' => $type, 'external_type' => $external_type, 'external_id' => $external_id, 'site' => $site, 'enable_user_registration' => 1
        ) );

        if(is_null($external_module_entity)){
            return null;
        }else{
            return array(
                "id" => $external_module_entity->getId(),
                "type" => $external_module_entity->getType(),
                "external_type" => $external_module_entity->getExternalType(),
                "external_id" => $external_module_entity->getExternalId(),
                "enable_user_registration" => $external_module_entity->getEnableUserRegistration(),
                "site" => $external_module_entity->getSite()
            );
        }

    }


    public function setExternalModule($type, $external_type, $external_id, $enable_user_registration, $site)
    {
        $external_module_entity = $this->entityManager->getRepository('CurrikiLti\Core\Entity\LaaS\ExternalModule')->findOneBy( array(
                'type' => $type, 'external_type' => $external_type, 'external_id' => $external_id, 'site' => $site
            ) );
        if(is_null($external_module_entity)){            
            $this->external_module_entity->setType($type);
            $this->external_module_entity->setExternalType($external_type);
            $this->external_module_entity->setExternalId($external_id);
            $this->external_module_entity->setEnableUserRegistration($enable_user_registration);            
            $this->external_module_entity->setSite($site);            
            $this->entityManager->persist($this->external_module_entity);
            $this->entityManager->flush();
        }else{
            $external_module_entity->setEnableUserRegistration($enable_user_registration);
            $this->entityManager->flush();
        }
    }
}