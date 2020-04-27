<?php
namespace CurrikiLti\Core\Entity;
use CurrikiLti\Core\Entity\LtiType;

/**
 * @Entity
 * @Table(name="lti_types_config")
 **/

class LtiTypeConfig
{
    /** @Id @Column(type="integer") @GeneratedValue **/
    public $id;
    /** @Column(type="integer") **/
    public $typeid;
    /** @Column(type="string") **/
    public $name;
    /** @Column(type="string") **/
    public $value;
    /**
     * @ManyToOne(targetEntity="LtiType", inversedBy="lti_type_config_list")
     * @JoinColumn(name="typeid", referencedColumnName="id")     
     */
    public $lti_type;

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }  
    
    public function setTypeId($typeid)
    {
        $this->typeid = $typeid;
    }
    
    public function getTypeId()
    {
        return $this->typeid;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setLtiType(LtiType $lti_type)
    {
        $this->lti_type = $lti_type;
    }
    
    public function getLtiType()
    {
        return $this->lti_type;
    }
    
}