<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Lesson;
use App\Activity;

class ActivityAdminController extends Controller
{

    public function __construct(){
        $this->default_lti_data = [
            "lti_typename"=> "", "typeid"=> null, "toolproxyid"=> null, "lti_toolurl"=> "",
            "lti_ltiversion"=> "", "lti_clientid"=> time(), "lti_clientid_disabled"=> "",
            "lti_description"=> "", "lti_parameters"=> "", "lti_icon"=> "", "lti_secureicon"=> "",
            "lti_resourcekey"=> substr(md5(rand()), 0, 10), "lti_password"=> "", "lti_publickey"=> "",
            "lti_initiatelogin"=> "", "lti_redirectionuris"=> "", "lti_sendname"=> "", "lti_sendemailaddr"=> "",
            "lti_acceptgrades"=> "", "lti_customparameters"=> "", "lti_forcessl"=> "", "lti_organizationid"=> "",
            "lti_organizationurl"=> "", "lti_launchcontainer"=> "", "lti_coursevisible"=> "", "lti_contentitem"=> "",
            "ltiservice_gradesynchronization"=> "", "ltiservice_memberships"=> "", "ltiservice_toolsettings"=> ""
        ];
    }

    public function index($id){
        $lesson = Lesson::find($id);
        if(empty($lesson))
            return back()->withErrors(['error' => 'Lesson not found']);

        return view('lessons.admin.activities_index', compact('lesson'));
    }

    public function create(Request $request){
        $lesson = Lesson::find($request->input('lesson_id'));
        if(empty($lesson))
            return back()->withErrors(['error' => 'Lesson not found']);
        $lti_data = $this->default_lti_data;
        return view('lessons.admin.activities_create', compact('lesson', 'lti_data'));
    }

    public function edit($id){
        $activity = Activity::find($id);
        if(empty($activity))
            return back()->withErrors(['error' => 'Activity not found']);
        $lesson = $activity->lesson;

        $_GET['id'] = $activity->get_lti_type_id();
        if(empty($_GET['id']))
            return back()->withErrors(['error' => 'LTI Activity not found']);

        $lti_instance = \App\Curriki\LtiApp::getInstance();
        $entityManager = $lti_instance->get('Doctrine\ORM\EntityManager');
        $tool_settings = $lti_instance->get('CurrikiLti\Core\Controllers\Toolsettings');            
        $tool_settings->entityManager = $entityManager;
        $tool_settings->render_view = false;
        $stored_lti_data = $tool_settings->tool_edit();
        if(empty($stored_lti_data))
            return back()->withErrors(['error' => 'LTI Activity not found']);

        $lti_data = array_merge($this->default_lti_data, (array) $stored_lti_data);
        return view('lessons.admin.activities_create', compact('lesson', 'activity', 'lti_data'));return view('lessons.admin.activities_create', compact('lesson', 'activity', 'lti_data'));
    }

    public function save(Request $request){
//        return '<iframe id="contentframe" height="600" width="100%" frameborder="0" scrolling="auto" src="https://demo.curriki.me/lti-launch?id=45" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
        $request->validate([
//            'title' => 'required|min:1|max:255',
            'type' => 'required|integer',
            'lti_toolurl' => 'required',
            'lti_resourcekey' => 'required',
            'lti_password' => 'required',
//            'thumb' => 'file',
        ]);
//        $lesson = Lesson::find($request->input('lesson_id'));//resourceid
//        if(empty($lesson))
//            return 'Lesson not found';
//            return redirect('/admin/lessons')->withErrors(['error' => 'Lesson not found']);

//        if($request->filled('activity_id')){
//            $action = 'tool_edit';
//            $activity = Activity::find($request->input('activity_id'));
//            if(empty($activity))
//                return redirect('/admin/lessons/'.$lesson->id)->withErrors(['error' => 'Activity not found']);
//            $_GET['id'] = $activity->get_lti_type_id();
//            if(empty($_GET['id']))
//                return back()->withErrors(['error' => 'LTI Activity not found']);
//        } else {
            $action = 'tool_add';
//            $activity = new Activity;
//        }

//        if($request->hasFile('thumb'))
//            $activity->thumb = $request->file('thumb')->store('lesson_thumbnails', 'public');
//        $activity->thumb = 'lesson_thumbnails/3ZaqGnFDlNfqzmhFyzNU69n1ErevDP5IIXbJ3rjj.png';
            
            $type_id = $request->input('type');
        
//        $activity->lesson_id = $lesson->id;
//        $activity->type_id = $request->input('type');
//        $activity->title = 'Test activity';

        // The LTI part
        $lti_instance = \App\Curriki\LtiApp::getInstance();
        $entityManager = $lti_instance->get('Doctrine\ORM\EntityManager');
        $tool_settings = $lti_instance->get('CurrikiLti\Core\Controllers\Toolsettings');            
        $tool_settings->entityManager = $entityManager;
        $tool_settings->render_view = false;
        $lti_id = $tool_settings->{$action}();
        
//        if(!is_int($lti_id) && $action == 'tool_add')
//            return redirect('/admin/lessons/view/'.$lesson->id)->withErrors(['error' => 'LTI save failed. Transaction aborted.']);

//        if($action == 'tool_add')
//            $activity->lti_id = $lti_id;
//        $activity->save();
        
        $lti_launcher = \App\Curriki\LtiApp::getLauncher();
        $iframe_src = secure_url('/').$lti_launcher->getUrl($lti_id)."&u=[[lti_user_data]]"; 
        $lti_content = '<div class="tinymce-content"><input type="hidden" name="type_id" value="'.$type_id.'" /><input type="hidden" name="lti_id" value="'.$lti_id.'" /><iframe id="contentframe" height="600" width="100%" frameborder="0" scrolling="auto" src="'.$iframe_src.'" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>';
        return view('lti.tinymce', compact('lti_content'));

//        Session::flash('msg', 'Activity saved.');
//        return redirect('/admin/lessons/view/'.$lesson->id);
    }

    public function delete($id){
        $activity = Activity::find($id);
        $lesson_id = $activity->lesson_id;
        $activity->delete();
        Session::flash('msg', 'Activity deleted.');
        return redirect('/admin/lessons/view/'.$lesson_id);
    }
}
