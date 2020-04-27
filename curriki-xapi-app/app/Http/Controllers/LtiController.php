<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LtiController extends Controller
{

    public function launch(){        
        $lti = \App\Curriki\LtiApp::getLauncher();
        $lti_launchcontent = $lti->launchContent();        
        return \View::make('lti.launchcontent', compact('lti_launchcontent'));
    }

    public function manage()
    {
        $lti_instance = \App\Curriki\LtiApp::getInstance();
        $entityManager = $lti_instance->get('Doctrine\ORM\EntityManager');
        $action = 'tools_list';
        if(isset($_GET['action'])){
            $action = $_GET['action'];
            $tool_settings = $lti_instance->get('CurrikiLti\Core\Controllers\Toolsettings');            
            $tool_settings->entityManager = $entityManager;
            $tool_settings->render_view = false;
            $data = $tool_settings->{$_GET['action']}();
            return \View::make('lti.views.'.$action, compact('data'));
        }else{
            return \View::make('lti.views.'.$action, compact('entityManager'));
        }                
    }

    public function lti1p1Service()
    {
        $lti_instance = \App\Curriki\LtiApp::getInstance();
        $lti_service = $lti_instance->get('CurrikiLti\Core\Services\Lti\Service');
        $entityManager = $lti_instance->get('Doctrine\ORM\EntityManager');
        $lti_service->entityManager = $entityManager;
        $lti_service->execute();        
    }

    public function test()
    {
        $lti_instance = \App\Curriki\LtiApp::getInstance();
        $lti_resource = $lti_instance->get('CurrikiLti\Core\Services\Lti\LtiResource');

        $data = array(
            "ltiid" => 10,
            "resourceid" => 2,
            "component" => "curriki-resource",
            "status" => "unread"
        );

        $rtn = $lti_resource->save($data);
        dd($rtn);
    }
    
}
