<?php
namespace CurrikiLti\Core\Services\LaaS;
use Doctrine\ORM\EntityManager;
use CurrikiLti\Core\Entity\LaaS\ExternalProgramRegistration as ProgramRegistrationEntity;

class ExternalProgramRegistration {

    protected $entityManager = null;        
    public $program_registration_entity = null;
    
    public function __construct(EntityManager $entityManager, ProgramRegistrationEntity $program_registration_entity)
    {
        $this->entityManager = $entityManager;        
        $this->program_registration_entity = $program_registration_entity;        
    }

    public function registerUser($external_user_id, $external_module_id)
    {
        $this->program_registration_entity->setExternalUserId($external_user_id);                
        $external_module = $this->entityManager->getRepository('CurrikiLti\Core\Entity\LaaS\ExternalModule')->findOneBy( array(
            'id' => $external_module_id
        ) );
        $this->program_registration_entity->setExternalModule($external_module);        
        $this->entityManager->persist($this->program_registration_entity);
        $this->entityManager->flush();        
    }

    public function getUserRegistration($external_user_id, $external_module_id){
        $program_registration = $this->entityManager->getRepository('CurrikiLti\Core\Entity\LaaS\ExternalProgramRegistration')->findOneBy( array(
            'external_user_id' => $external_user_id, 'external_module_id' => $external_module_id
        ) );
        return $program_registration;
    }

    public function getUserAllRegistration($external_user_id){
        $programs_registration = $this->entityManager->getRepository('CurrikiLti\Core\Entity\LaaS\ExternalProgramRegistration')->findBy( array(
            'external_user_id' => $external_user_id
        ) );
        return $programs_registration;
    }

}