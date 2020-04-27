<?php
namespace CurrikiLti\Core\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use CurrikiLti\Core\Entity\LtiTypeConfig;
use CurrikiLti\Core\Entity\Lti;

/**
 * @Entity(repositoryClass="CurrikiLti\Core\Repository\LtiTypeRepository")
 * @Table(name="lti_types")
 **/

class LtiType
{

    /** @Id @Column(type="integer") @GeneratedValue **/
    public $id;
    /** @Column(type="string") **/
    public $name = '';
    /** @Column(type="string") **/
    public $baseurl = '';
    /** @Column(type="string") **/
    public $icon = '';
    /** @Column(type="string") **/
    public $secureicon = '';
    /** @Column(type="string") **/
    public $tooldomain = '';
    /** @Column(type="integer") **/
    public $state = 0;
    /** @Column(type="integer") **/
    public $course = 0;
    /** @Column(type="integer") **/
    public $coursevisible = 0;
    /** @Column(type="string") **/
    public $ltiversion = 'LTI-1p0';
    /** @Column(type="string") **/
    public $clientid = '';
    /** @Column(type="integer") **/
    public $toolproxyid = null;
    /** @Column(type="string") **/
    public $enabledcapability = '';
    /** @Column(type="string") **/
    public $parameter = '';
    /** @Column(type="integer") **/
    public $createdby = 0;
    /** @Column(type="integer") **/
    public $timecreated = 0;
    /** @Column(type="integer") **/
    public $timemodified = 0;
    /** @Column(type="string") **/
    public $description = '';
    /**     
     * @OneToOne(targetEntity="Lti", mappedBy="lti_type", cascade={"persist"})
     */
    protected $lti;    
    /** 
     * @OneToMany(targetEntity="LtiTypeConfig", mappedBy="lti_type", cascade={"persist", "remove"})     
     */
    protected $lti_type_config_list;

    public function __construct()
    {
        $this->lti_type_config_list = new ArrayCollection();
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
    
    public function setBaseUrl($baseurl)
    {
        $this->baseurl = $baseurl;
    }

    public function getBaseUrl()
    {
        return $this->baseurl;
    }

    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function setSecureIcon($secureicon)
    {
        $this->secureicon = $secureicon;
    }

    public function getSecureIcon()
    {
        return $this->secureicon;
    }

    public function setLtiVersion($ltiversion){
        $this->ltiversion = $ltiversion;
    }

    public function getLtiVersion()
    {
        return $this->ltiversion;
    }
    
    public function getLtiTypeConfigList()
    {
        return $this->lti_type_config_list;
    }
    
    public function addToTypeConfigList(LtiTypeConfig $ltiTypeConfig)
    {
        $this->lti_type_config_list->add($ltiTypeConfig);
    }

    public function setLti(Lti $lti)
    {
        $this->lti = $lti;
    }

    public function getLti()
    {
        return $this->lti;
    }
}