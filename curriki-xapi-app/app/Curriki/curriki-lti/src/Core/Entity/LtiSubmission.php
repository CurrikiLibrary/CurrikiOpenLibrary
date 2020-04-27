<?php
namespace CurrikiLti\Core\Entity;
use CurrikiLti\Core\Entity\LtiType;

/**
 * @Entity @Table(name="lti_submission")
 **/

class LtiSubmission
{
    /** @Id @Column(type="integer") @GeneratedValue **/
    public $id;    
    /** @Column(type="integer") * */
    public $ltiid = 0;
    /** @Column(type="integer") * */
    public $userid = 0;    
    /** @Column(type="integer") * */
    public $datesubmitted = 0;    
    /** @Column(type="integer") * */
    public $dateupdated = 0;    
    /** @Column(type="decimal") * */
    public $gradepercent = 0;    
    /** @Column(type="decimal") * */
    public $originalgrade = 0;    
    /** @Column(type="integer") * */
    public $launchid = 0;
    /** @Column(type="integer") * */
    public $state = 0;
    
    public function __construct()
    {
        $this->datesubmitted = time();
        $this->dateupdated = time(); 
        $this->gradepercent = 0.0;
        $this->originalgrade = 0.0;    
    }

    public function getId()
    {
        return $this->id;
    }

    public function setLtiId($ltiid)
    {
        return $this->ltiid = $ltiid;
    }

    public function getLtiId()
    {
        return $this->ltiid;
    }

    public function setUserId($userid)
    {
        return $this->userid = $userid;
    }

    public function getUserId()
    {
        return $this->userid;
    }
    
    public function setDateSubmitted($datesubmitted)
    {
        return $this->datesubmitted = $datesubmitted;
    }

    public function getDateSubmitted()
    {
        return $this->datesubmitted;
    }
    
    public function setDateUpdated($dateupdated)
    {
        return $this->dateupdated = $dateupdated;
    }

    public function getDateUpdated()
    {
        return $this->dateupdated;
    }
    
    public function setGradePercent($gradepercent)
    {
        return $this->gradepercent = $gradepercent;
    }

    public function getGradePercent()
    {
        return $this->gradepercent;
    }
    
    public function setOriginalGrade($originalgrade)
    {
        return $this->originalgrade = $originalgrade;
    }

    public function getOriginalGrade()
    {
        return $this->originalgrade;
    }

    public function setLaunchId($launchid)
    {
        $this->launchid = $launchid;
    }
    
    public function getLaunchId()
    {
        return $this->launchid;
    }

    public function setState($state)
    {
        $this->state = $state; 
    }

    public function getState()
    {
        return $this->state;
    }
    
}