<?php
namespace CurrikiLti\Core\Entity\LaaS;

/**
 * @Entity @Table(name="external_program_registration")
 */

class ExternalProgramRegistration {
    
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;

    /** @Column(type="integer") **/
    protected $external_user_id;
    
    /** @Column(type="integer") **/
    protected $external_module_id;    
    
    /**
     * @ManyToOne(targetEntity="ExternalModule", inversedBy="external_program_registrations")
     * @JoinColumn(name="external_module_id", referencedColumnName="id")     
     */
    protected $external_module;

    public function getId()
    {
        return $this->id;
    }

    public function setExternalUserId($external_user_id)
    {
        $this->external_user_id = $external_user_id;
    }

    public function getExternalUserId()
    {
        return $this->external_user_id;
    }

    public function setExternalModuleId($external_module_id)
    {
        $this->external_module_id = $external_module_id;
    }

    public function getExternalModuleId()
    {
        return $this->external_module_id;
    }
    
    public function getExternalModule(){
        return $this->external_module;
    }

    public function setExternalModule($external_module){
        return $this->external_module = $external_module;
    }
}