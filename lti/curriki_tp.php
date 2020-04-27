<?php
/**
 * Curriki LTI tool provider
 *
 * @author  Waqar Muneer 
 */

/*
 * This page processes a launch request from an LTI tool consumer.
 */

  use IMSGlobal\LTI\Profile;
  use IMSGlobal\LTI\ToolProvider;
  use IMSGlobal\LTI\ToolProvider\Service;

  require_once('lib.php');
  require_once('app/lti_roles.php');
  require_once('app/tp_launch_helper.php');
  require_once('app/tp_grading_helper.php');
  require_once('app/tp_ui_helper.php');


  class CurrikiToolProvider extends ToolProvider\ToolProvider {
          
    private static $CUR_LTI_VERSION = "LTI-1p0";

    function __construct($data_connector) {

      parent::__construct($data_connector);
        
        $this->baseUrl = getAppUrl();
        $this->authenticate_additional_checks();
        
        
        $this->vendor = new Profile\Item('curriki', 'Curriki', 'Inspiring Learning Everywhere', 'https://www.curriki.org/');
        $this->product = new Profile\Item('d751f24f-140e-470f-944c-2d92b114db40', 'Curriki', 'Curriki LTI tool provider.',
                                      'http://www.curriki.org/lti/', VERSION);                  
        
        
        $requiredMessages = array(new Profile\Message('basic-lti-launch-request', 'connect.php', array('User.id', 'Membership.role')));
        $optionalMessages = array(new Profile\Message('ContentItemSelectionRequest', 'connect.php', array('User.id', 'Membership.role')),
                                  new Profile\Message('DashboardRequest', 'connect.php', array('User.id'), array('a' => 'User.id'), array('b' => 'User.id')));

        $this->resourceHandlers[] = new Profile\ResourceHandler(
          new Profile\Item('curriki-learning-tool', 'Curriki Tool Provider', 'Tool provider which provide educational resources.'), 'images/icon50.png',
          $requiredMessages, $optionalMessages);
        
        $this->requiredServices[] = new Profile\ServiceDefinition(array('application/vnd.ims.lti.v2.toolconsumerprofile+json'), array('POST'));              
    }
    
    private function authenticate_additional_checks()
    {                
        $lti_message_type_validation = isset($_POST['lti_message_type']) && array_key_exists($_POST['lti_message_type'], TPLaunchHelper::$LTI_MESSAGE_TYPES) ;
        if($lti_message_type_validation)
        {
            $missing_lti_version =  !( isset($_POST['lti_version']) );
            $invalid_lti_version = !( isset($_POST['lti_version']) && in_array($_POST['lti_version'], array(parent::LTI_VERSION1,  parent::LTI_VERSION2)) );
            $wrong_lti_version = !( isset($_POST['lti_version']) && in_array($_POST['lti_version'], array(parent::LTI_VERSION1,  parent::LTI_VERSION2)) && $_POST['lti_version'] === self::$CUR_LTI_VERSION );       

            if ( $missing_lti_version ) {
                $this->ok = false;
                $this->reason = 'Missing LTI version.';
            }elseif ( $invalid_lti_version ) {
                $this->ok = false;
                $this->reason = 'Invalid LTI version provided by consumer';            
            }elseif ( $wrong_lti_version ) {
                $this->ok = false;
                $this->reason = 'Wrong LTI version provided by consumer';
            }
        }
        
        if( isset($_POST['lti_message_type']) && $_POST['lti_message_type'] === 'ContentItemSelectionRequest' && isset($_POST['accept_presentation_document_targets']) && strlen($_POST['accept_presentation_document_targets']) === 0 )
        {
            $_POST['accept_presentation_document_targets'] = "popup";            
        }        
    }
    
    
    function onLaunch() {

        global $db;           
        $this->user->userDisplayName = TPLaunchHelper::makeUserDisplayName($this);
        TPLaunchHelper::prepareLaunchRequest($_POST,$this);
        
        // Initialise the user session
        $_SESSION['custom_search_term'] = $_REQUEST["custom_search_term"];                
        $_SESSION['consumer_pk'] = $this->consumer->getRecordId();
        $_SESSION['resource_pk'] = $this->resourceLink->getRecordId();
        $_SESSION['user_consumer_pk'] = $this->user->getResourceLink()->getConsumer()->getRecordId();
        $_SESSION['user_resource_pk'] = $this->user->getResourceLink()->getRecordId();
        $_SESSION['user_pk'] = $this->user->getRecordId();
        $_SESSION['isAdmin'] = $this->user->isAdmin();
        $_SESSION['isStudent'] = $this->user->isLearner();
        $_SESSION['isLearner'] = $this->user->isLearner();
        $_SESSION['isContentItem'] = FALSE;               
        $_SESSION['unserInfo'] = TPLaunchHelper::prepareUserInfo($this->user);                
        $isInstructor = $this->user->isStaff() && in_array(TPLaunchHelper::$USER_ROLE_NAME_PREFIX."Instructor", $this->user->roles);
        $_SESSION['isInstructor'] = $isInstructor;                
        
        //**** Test: 4.6: Launch as a user with an institution role but no context role ****
        $_SESSION["isRoleInstrole"] = $_SESSION['isInstructor']===false && $_SESSION['isLearner']===false && TPLaunchHelper::isRoleInstroleExist($this);
        
        //[start]******* Test 4.7: Launch as a user with an institution role which has no corresponding context role ********                
        $instrole_status = TPLaunchHelper::getInstroleRelatedToContextRole($this);
        $_SESSION["isInstroleRelatedToContextRole"] = $instrole_status->isInstroleRelatedToContextRole;
        $_SESSION["instroleRelatedToContextRole"] = $instrole_status->instroleRelatedToContextRole;
        //[end]**************************************************************************************************************                
        
        //[start]********** Test 4.8: Launch as a user with an unrecognised role *****************************        
        $_SESSION["isUnrecognisedRole"] = TPLaunchHelper::isUnrecognisedRole($this);                
        //[end]**************************************************************************************************************                
                
        //[start]*************** Test 5.4: Launch as an instructor with no personal information ***************
        TPLaunchHelper::ifInstructorWithNoPersonalInfo($this);
        //[end]**************************************************************************************************************                
        
        //[start]*************** Test 5.5: Launch as an instructor with no context or personal information apart from the context ID ***************
        TPLaunchHelper::ifInstructorWithNoContextData($this);
        //[end]**************************************************************************************************************                
        
        //[start]*************** Test 5.6: Launch as Instructor with no context information ***************
        TPLaunchHelper::ifInstructorWithNoContext($this);
        //[end]**************************************************************************************************************                
        
        if( isset($_POST["context_title"]) )
        {
            $_SESSION['context_title'] = $_POST["context_title"];
        }
        if( isset($_POST["resource_link_title"]) )
        {
            $_SESSION['resource_link_title'] = $_POST["resource_link_title"];
        }               
                
        // Redirect the user to display the list of items for the resource link
        $this->redirectUrl = getAppUrl()."index.php?t=".time();
    }
   
    function onContentItem() 
    {
                
      // Check that the Tool Consumer is allowing the return of an LTI link
      $this->ok = in_array(ToolProvider\ContentItem::LTI_LINK_MEDIA_TYPE, $this->mediaTypes) || in_array('*/*', $this->mediaTypes);
      if (!$this->ok) {
        $this->reason = 'Return of an LTI link not offered';
      } else {
        $this->ok = !in_array('none', $this->documentTargets) || (count($this->documentTargets) > 1);
        if (!$this->ok) {
          $this->reason = 'No visible document target offered';
        }
      }      
      if ($this->ok) {          
        
        if( isset($_REQUEST["tool_consumer_info_product_family_code"]) && $_REQUEST["tool_consumer_info_product_family_code"] === "desire2learn" )
        {   
            if(is_array($this->user->roles) && count($this->user->roles)===0)
            {
                $this->user->roles = array("urn:lti:role:ims/lis/Instructor");
            }
            $_GET['action'] = "resource_selection";
        }
            
        $this->user->userDisplayName = TPLaunchHelper::makeUserDisplayName($this);                
        TPLaunchHelper::prepareLaunchRequest($_POST,$this);
        
        // Initialise the user session
        $_SESSION['custom_search_term'] = $_REQUEST["custom_search_term"];
        $_SESSION['consumer_pk'] = $this->consumer->getRecordId();
        $_SESSION['resource_id'] = getGuid();
        $_SESSION['resource_pk'] = NULL;
        $_SESSION['user_consumer_pk'] = $_SESSION['consumer_pk'];
        $_SESSION['user_pk'] = NULL;        
        $_SESSION['isContentItem'] = TRUE;
        $_SESSION['lti_version'] = $_POST['lti_version'];
        $_SESSION['return_url'] = $this->returnUrl;
        $_SESSION['title'] = postValue('title');
        $_SESSION['text'] = postValue('text');
        $_SESSION['data'] = postValue('data');
        $_SESSION['request_action'] = $_GET['action'];
        $_SESSION['document_targets'] = $this->documentTargets;                        
        $_SESSION['isAdmin'] = $this->user->isAdmin();
        $_SESSION['isStudent'] = $this->user->isLearner();
        $_SESSION['isLearner'] = $this->user->isLearner();       
        $_SESSION['unserInfo'] = TPLaunchHelper::prepareUserInfo($this->user);                        
        $isInstructor = $this->user->isStaff() && in_array(TPLaunchHelper::$USER_ROLE_NAME_PREFIX."Instructor", $this->user->roles);
        
        $_SESSION['isInstructor'] = $isInstructor;                        
        $_SESSION['context_title'] = $_POST["context_title"];
        
        //**** Test: 4.6: Launch as a user with an institution role but no context role ****
        $_SESSION["isRoleInstrole"] = $_SESSION['isInstructor']===false && $_SESSION['isLearner']===false && TPLaunchHelper::isRoleInstroleExist($this);
        
        //[start]******* Test 4.7: Launch as a user with an institution role which has no corresponding context role ********                
        $instrole_status = TPLaunchHelper::getInstroleRelatedToContextRole($this);
        $_SESSION["isInstroleRelatedToContextRole"] = $instrole_status->isInstroleRelatedToContextRole;
        $_SESSION["instroleRelatedToContextRole"] = $instrole_status->instroleRelatedToContextRole;
        //[end]**************************************************************************************************************                
        
        //[start]********** Test 4.8: Launch as a user with an unrecognised role *****************************        
        $_SESSION["isUnrecognisedRole"] = TPLaunchHelper::isUnrecognisedRole($this);                
        //[end]**************************************************************************************************************                
                
        //[start]*************** Test 5.4: Launch as an instructor with no personal information ***************
        TPLaunchHelper::ifInstructorWithNoPersonalInfo($this);
        //[end]**************************************************************************************************************                
        
        //[start]*************** Test 5.5: Launch as an instructor with no context or personal information apart from the context ID ***************
        TPLaunchHelper::ifInstructorWithNoContextData($this);
        //[end]**************************************************************************************************************                
        
        //[start]*************** Test 5.6: Launch as Instructor with no context information ***************
        TPLaunchHelper::ifInstructorWithNoContext($this);
        //[end]**************************************************************************************************************                
        
        if( isset($_POST["context_title"]) )
        {
            $_SESSION['context_title'] = $_POST["context_title"];
        }
        if( isset($_POST["resource_link_title"]) )
        {
            $_SESSION['resource_link_title'] = $_POST["resource_link_title"];
        }         
        // Redirect the user to display the list of items for the resource link                
        $this->redirectUrl = getAppUrl()."index.php?t=".time();
      }

    }

    function onRegister() {

      // Initialise the user session
      $_SESSION['consumer_pk'] = $this->consumer->getRecordId();
      $_SESSION['tc_profile_url'] = $_POST['tc_profile_url'];
      $_SESSION['tc_profile'] = $this->consumer->profile;
      $_SESSION['return_url'] = $_POST['launch_presentation_return_url'];

      // Redirect the user to process the registration
      $this->redirectUrl = getAppUrl() . 'register.php';

    }

    function onError() {
        
    if( isset($this->reason) && strlen($this->reason) > 0 )
    {
        $this->debugMode = true;
    }

    $msg = $this->message;
    if ($this->debugMode && !empty($this->reason)) {
      $msg = $this->reason;
    }
        
    
    $title = APP_NAME;
    $data["page_title"] = $title;
    $head_html = TpUiHelper::headHtml($data);
    $body_start_html = TpUiHelper::bodyStartHtml();
    $body_end_html = TpUiHelper::bodyEndHtml();
    $this->errorOutput = <<< EOD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
{$head_html}            
{$body_start_html}            
   <h2>Error</h2>
    <p class='error-label'>{$msg}</p>
{$body_end_html}            
</html>            
EOD;
 
    }

  }

?>
