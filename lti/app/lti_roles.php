<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class LtiRoles
{
    public static function getSystemRoles()
    {
        return array(
                        'urn:lti:sysrole:ims/lis/SysAdmin',
                        'urn:lti:sysrole:ims/lis/SysSupport',
                        'urn:lti:sysrole:ims/lis/Creator',
                        'urn:lti:sysrole:ims/lis/AccountAdmin',
                        'urn:lti:sysrole:ims/lis/User',
                        'urn:lti:sysrole:ims/lis/Administrator',
                        'urn:lti:sysrole:ims/lis/None'
                    );
    }
    
    public static function getInstitutionRole()
    {
        return array(
                        'urn:lti:instrole:ims/lis/Student',
                        'urn:lti:instrole:ims/lis/Faculty',
                        'urn:lti:instrole:ims/lis/Member',
                        'urn:lti:instrole:ims/lis/Learner',
                        'urn:lti:instrole:ims/lis/Instructor',
                        'urn:lti:instrole:ims/lis/Mentor',
                        'urn:lti:instrole:ims/lis/Staff',
                        'urn:lti:instrole:ims/lis/Alumni',
                        'urn:lti:instrole:ims/lis/ProspectiveStudent',
                        'urn:lti:instrole:ims/lis/Guest',
                        'urn:lti:instrole:ims/lis/Other',
                        'urn:lti:instrole:ims/lis/Administrator',
                        'urn:lti:instrole:ims/lis/Observer',
                        'urn:lti:instrole:ims/lis/None'
                    );
    }
}
