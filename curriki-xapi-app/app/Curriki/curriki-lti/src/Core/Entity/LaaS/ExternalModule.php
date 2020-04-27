<?php
namespace CurrikiLti\Core\Entity\LaaS;

/**
 * @Entity @Table(name="external_module")
 */

class ExternalModule {
    
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;

    /** @Column(type="string") **/
    protected $type;

    /** @Column(type="string") **/
    protected $external_type;
    
    /** @Column(type="integer") **/
    protected $external_id;    

    /** @Column(type="integer") */
    protected $enable_user_registration;

    /** @Column(type="string") **/
    protected $site;

    /** 
     * @OneToMany(targetEntity="ExternalProgramRegistration", mappedBy="external_module", cascade={"persist", "remove"})     
     */
    protected $external_program_registrations;    

    public function getId()
    {
        return $this->id;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setExternalType($external_type)
    {
        $this->external_type = $external_type;
    }

    public function getExternalType()
    {
        return $this->external_type;
    }

    public function setExternalId($external_id)
    {
        $this->external_id = $external_id;
    }

    public function getExternalId()
    {
        return $this->external_id;
    }

    public function setEnableUserRegistration($enable_user_registration)
    {
        $this->enable_user_registration = $enable_user_registration;
    }

    public function getEnableUserRegistration()
    {
        return $this->enable_user_registration;
    }

    public function setSite($site)
    {
        $this->site = $site;
    }

    public function getSite()
    {
        return $this->site;
    }

    public function getExternalProgramRegistrations()
    {
        return $this->external_program_registrations;
    }
    
}