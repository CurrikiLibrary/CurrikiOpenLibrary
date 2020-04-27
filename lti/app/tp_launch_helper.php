<?php

/* 
 * Dev: Waqar Muneer
 */
//**** TP = Tool Proiver *******
class TPLaunchHelper
{
    public static $LTI_MESSAGE_TYPES = array('basic-lti-launch-request' => 'onLaunch',
                                            'ContentItemSelectionRequest' => 'onContentItem',
                                            'ToolProxyRegistrationRequest' => 'register');
    public static $USER_ROLE_NAME_PREFIX = 'urn:lti:role:ims/lis/';
    public static $LTI_CONTEXT_ROLES = array('urn:lti:role:ims/lis/Learner','urn:lti:role:ims/lis/Instructor','urn:lti:role:ims/lis/ContentDeveloper','urn:lti:role:ims/lis/Member','urn:lti:role:ims/lis/Manager','urn:lti:role:ims/lis/Mentor','urn:lti:role:ims/lis/Administrator','urn:lti:sysrole:ims/lis/Administrator','urn:lti:instrole:ims/lis/Administrator','urn:lti:role:ims/lis/TeachingAssistant');
    
    public static function getInstroleRelatedToContextRole($tp)
    {
        $isInstroleRelatedToContextRoleList = array();
                
        if($_SESSION["isRoleInstrole"])
        {            
            foreach($tp->user->roles as $role)
            {
                //$role_name = explode("", $role);                
                $role_full_name_arr =  explode("/", $role);
                $role_name = count($role_full_name_arr) > 0 ? $role_full_name_arr[count($role_full_name_arr)-1] : "";
                
                if(self::hasRoleInContext($role_name) )
                {
                    $isInstroleRelatedToContextRoleList[]=$role_name;
                }                
            }            
        }             
        $instrole_status = new stdClass();
        $instrole_status->isInstroleRelatedToContextRole = count($isInstroleRelatedToContextRoleList) > 0 ? true:false;
        $instrole_status->instroleRelatedToContextRole = serialize($isInstroleRelatedToContextRoleList);
        return $instrole_status;
    }
    
    private static function hasRoleInContext($role) 
    {
        $role = self::$USER_ROLE_NAME_PREFIX . $role;                
        return in_array($role, self::$LTI_CONTEXT_ROLES);
    }
    
    public static function isUnrecognisedRole($tp)
    {        
        $isInInstitutionRoles = count(array_intersect(LtiRoles::getInstitutionRole(), $tp->user->roles)) > 0 ? true:false;
        $isSystemRoles = count(array_intersect(LtiRoles::getSystemRoles(), $tp->user->roles)) > 0 ? true:false;        
        return !$tp->user->isLearner() && !$tp->user->isStaff() && !$isInInstitutionRoles && !$isSystemRoles;
    }
    
    public static function isRoleInstroleExist($tp) 
    {
        $instrole_found = false;
        
        foreach ($tp->user->roles as $role)
        {            
            $instrole_found = (substr($role, 8, 9) === 'instrole:' ? true:false);
        }
        return $instrole_found;
    }
    
    public static function prepareUserInfo($user)
    {
        
        $u = new stdClass();        
        $u->firstname = $user->firstname;        
        $u->lastname = $user->lastname;
        $u->fullname = $user->fullname;
        $u->email = $user->email;
        $u->ltiUserId = $user->ltiUserId;
        $u->userDisplayName = $user->userDisplayName;        
        
        return serialize($u);
    }
    public static function makeUserDisplayName($tp)
    {
        
        $display_name = $tp->user->fullname;
        if( (isset($tp->user->email) && strlen($tp->user->email) > 0) && ($tp->user->fullname === "User {$tp->user->ltiUserId}") )
        {
            $display_name = $tp->user->email;
        }
        return $display_name;
    }
    public static function ifInstructorWithNoPersonalInfo($tp)
    {
        $rtn = false;
        if( $tp->context !== null && (isset($_SESSION['isInstructor']) && $_SESSION['isInstructor']===true) && ($tp->user->fullname === "User {$tp->user->ltiUserId}") && ($tp->context->title !== "" && $tp->context->label !== "" && $tp->context->type !== ""))
        {
            $tp->ok = false;
            $tp->reason = 'Instructor has no personal information.';
            $rtn = true;
        }
        return $rtn;
    }
    public static function ifInstructorWithNoContextData($tp)
    {        
        $rtn = false;
        if( $tp->context !== null && (isset($_SESSION['isInstructor']) && $_SESSION['isInstructor']===true) && ($tp->context->title === "Course {$tp->context->getId()}" && $tp->context->label === "" && $tp->context->type === "") )
        {            
            $tp->ok = false;
            $tp->reason = 'Instructor has no complete context information.';
            $rtn = true;
        }
        return $rtn;
    }
    
    public static function prepareLaunchRequest($post,$tp)
    {  
               
        if($tp->context)
        {
            $tp->context->label = isset($post["context_label"]) && strlen($post["context_label"]) > 0 ? $post["context_label"] : "";
            $tp->context->type = isset($post["context_type"]) && strlen($post["context_type"]) > 0 ? $post["context_type"] : "";                
        }        
    }
    
    public static function ifInstructorWithNoContext($tp)
    {        
        $rtn = false;
        if( $tp->context === null )
        {            
            $tp->ok = false;
            $tp->reason = 'Instructor has no context ';
            $rtn = true;
        }
        return $rtn;
    }
    
    public static function getScriptOutput($path, $print = FALSE)
    {
        ob_start();
        
        if( is_readable($path) && $path )
        {
            require_once $path;
        }
        else
        {
            return FALSE;
        }

        if( $print == FALSE )
            return ob_get_clean();
        else
            echo ob_get_clean();
    }
}