<?php
namespace CurrikiLti\Core\Entity;
use CurrikiLti\Core\Entity\Lti;

/**
 * @Entity @Table(name="lti_resource")
 */

class LtiResource {
    
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;

    /** @Column(type="integer") **/
    protected $ltiid;

    /** 
     * @OneToOne(targetEntity="Lti", inversedBy="lti_resource") 
     * @JoinColumn(name="ltiid", referencedColumnName="id")
     */
    protected $lti_tool;

    /** @Column(type="integer") **/
    protected $resourceid;

    /** @Column(type="string") **/
    protected $component;

    /** @Column(type="string") */
    protected $status;

    public function getId()
    {
        return $this->id;
    }

    public function setLtiId($ltiid)
    {
        $this->ltiid = $ltiid;
    }

    public function getLtiId()
    {
        return $this->ltiid;
    }

    public function setLti(Lti $lti_tool)
    {
        $this->lti_tool = $lti_tool;
    }

    public function getLti()
    {
        return $this->lti_tool;
    }

    public function setResourceId($resourceid)
    {
        return $this->resourceid = $resourceid;
    }

    public function getResourceId()
    {
        return $this->resourceid;
    }

    public function setComponent($component)
    {
        return $this->component = $component;
    }
    
    public function getComponent()
    {
        return $this->component;
    }

    public function setStatus($status)
    {
        return $this->status = $status;
    }
    
    public function getStatus()
    {
        return $this->status;
    }
    
}