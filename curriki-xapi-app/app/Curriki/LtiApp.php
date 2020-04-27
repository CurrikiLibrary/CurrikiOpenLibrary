<?php
namespace App\Curriki;

class LtiApp
{
    public static function getLauncher()
    {
        require 'curriki-lti/bootstrap.php';               
        $user = new \CurrikiLti\Core\Entity\User(); 
        $data = new \stdClass();     
        // use `auth()->user()` to get laravel user's attributes to assign `$user`
        if(isset($_GET['u']) && $_GET['u'] !='[[lti_user_data]]')  {
            $u = $_GET['u'];
            $data = \json_decode(\urldecode($u));
            $user->id = $data->id;
            $user->name = $data->name;           
            $user->firstname = $data->firstname;
            $user->lastname = $data->lastname;
            $user->email = $data->email;
            $user->username = $data->username;             
        }else{
            $user->id = 1;
            $user->name = "Curriki User";           
            $user->firstname = "Alpha";
            $user->lastname = "User";
            $user->email = 'alpha_user@curriki.org';
            $user->username = 'alpha_user'; 
        }
               
        $lti_launcher = $curriki_lti_instance->get('CurrikiLti\Core\Services\Lti\LtiLauncher');
        $lti_launcher->setUser($user);

        $course = new \stdClass();//$PAGE->course;
        $course->id = 101;
        $course->shortname = "course-101 short name";
        $course->fullname = "course-101 full name";
        $course->format = null;
        $lti_launcher->setCourse($course);

        if( property_exists($data,"resourceid") && intval($data->resourceid) > 0 ){
            $lti_launcher->setResourceParams(array('resourceid' => $data->resourceid, 'component' => 'curriki-resource'));
        }        
        
        return $lti_launcher;
    }

    public static function getInstance()
    {
        require 'curriki-lti/bootstrap.php';
        return $curriki_lti_instance; 
    }
}
