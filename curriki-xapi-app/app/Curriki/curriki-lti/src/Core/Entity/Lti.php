<?php
namespace CurrikiLti\Core\Entity;
use CurrikiLti\Core\Entity\LtiType;
use CurrikiLti\Core\Entity\LtiResource;

/**
 * @Entity @Table(name="lti")
 **/

class Lti
{
    /** @Id @Column(type="integer") @GeneratedValue **/
    public $id;
    /** @Column(type="string") **/
    public $name = '';    
    /** @Column(type="integer") * */
    public $typeid = 0;
    /** @Column(type="integer") **/
    public $course = 0;
    /** @Column(type="string") **/
    public $intro = '';
    /** @Column(type="integer") **/
    public $introformat=1;
    /** @Column(type="integer") **/
    public $timecreated=0;
    /** @Column(type="integer") **/
    public $timemodified=0;
    /** @Column(type="string") **/
    public $toolurl='';
    /** @Column(type="string") **/
    public $securetoolurl='';
    /** @Column(type="integer") **/
    public $instructorchoicesendname=1;
    /** @Column(type="integer") **/
    public $instructorchoicesendemailaddr=1;
    /** @Column(type="integer") **/
    public $instructorchoiceallowroster=0;
    /** @Column(type="integer") **/
    public $instructorchoiceallowsetting=0;
    /** @Column(type="string") **/
    public $instructorcustomparameters='';
    /** @Column(type="integer") **/
    public $instructorchoiceacceptgrades=1;
    /** @Column(type="integer") **/
    public $grade=100;
    /** @Column(type="integer") **/
    public $launchcontainer=3;
    /** @Column(type="string") **/
    public $resourcekey='';
    /** @Column(type="string") **/
    public $password='';
    /** @Column(type="integer") **/
    public $debuglaunch=0;
    /** @Column(type="integer") **/
    public $showtitlelaunch=1;
    /** @Column(type="integer") **/
    public $showdescriptionlaunch=0;
    /** @Column(type="string") **/
    public $servicesalt='';
    /** @Column(type="string") **/
    public $icon='';
    /** @Column(type="string") **/
    public $secureicon='';
    /** 
     * @OneToOne(targetEntity="LtiType", inversedBy="lti") 
     * @JoinColumn(name="typeid", referencedColumnName="id")
     */
    protected $lti_type;

    /**     
     * @OneToOne(targetEntity="LtiResource", mappedBy="lti_tool", cascade={"persist"})
     */
    protected $lti_resource;

    public function __construct()
    {
        $this->timecreated = time();
        $this->timemodified = time();
        $this->servicesalt = '5d19ef85e49037.19143779';
    }

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

    public function getLtiType()
    {
        return $this->lti_type;
    }

    public function setLtiType(LtiType $ltiType)
    {
        $this->lti_type = $ltiType;
    }

    public function setLtiResource(LtiResource $lti_resource)
    {
        $this->lti_resource = $lti_resource;
    }

    public function getLtiResource()
    {
        return $this->lti_resource;
    }

}