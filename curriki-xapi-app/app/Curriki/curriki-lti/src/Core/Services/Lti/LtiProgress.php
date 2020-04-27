<?php
namespace CurrikiLti\Core\Services\Lti;
use Doctrine\ORM\EntityManager;
use CurrikiLti\Core\Entity\LtiResource as LtiResourceEntity;
use CurrikiLti\Core\Entity\LtiSubmission;

class LtiProgress {

    protected $entityManager = null;
    protected $lti_resource = null;
    public $userid = null;
    public $resourceid = null;
    public $lti_resource_component = null;
    
    public function __construct(EntityManager $entityManager, LtiResourceEntity $lti_resource)
    {
        $this->entityManager = $entityManager;
        $this->lti_resource = $lti_resource;
    }

    public function getForResource()
    {
        if(is_null($this->userid)){
            return null;
        }

        $lti_resource = $this->entityManager->getRepository('CurrikiLti\Core\Entity\LtiResource')->findOneBy(array('resourceid' => $this->resourceid, 'component' => $this->lti_resource_component));
        if( is_null($lti_resource) || ( is_object($lti_resource) && !is_object($lti_resource->getLti()) )){
            return null;
        }

        $progress = array(
            "is_completed" => false,
            "gradepercent" => null,
            "originalgrade" => null,
            "datesubmitted" => null
        );

        $lti_submission = $this->entityManager->getRepository('CurrikiLti\Core\Entity\LtiSubmission')->findOneBy(array('ltiid' => $lti_resource->getLtiId(), 'userid' => $this->userid));        
        if( is_object($lti_submission) ){
            $progress["is_completed"] = true;
            $progress["gradepercent"] = intval($lti_submission->getGradePercent());
            $progress["originalgrade"] = number_format((float)$lti_submission->getOriginalGrade(), 2, '.', '');
            $progress["datesubmitted"] = date('m/d/Y H:i:s', $lti_submission->getDateSubmitted());
        }
        return $progress;
    }
}