<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Activity;

class ActivityController extends Controller
{

    public function index2($id){
        $activity = Activity::find($id);
        if(empty($activity))
            return redirect('/')->withErrors(['error' => 'Activity not found']);

        $lti = \App\Curriki\LtiApp::getLauncher();
        $lti_content = $lti->launch($activity->lti_id); 

        return view('activities.view', compact('activity', 'lti_content'));
    }
    public function index($lesson_id, $activity_id){
        if(isset($_GET['tool_id'])){
            $lti = \App\Curriki\LtiApp::getLauncher();
            $lti_tool_id = trim(intval($_GET['tool_id']));
            $lti_content = $lti->launch($lti_tool_id); 
            $course_name = "LTI Activity";           
            dd($lti_content);
            return \View::make('lti.content', compact('lti_content','course_name'));
        }        
        
        if($lesson_id == 1){
            if($activity_id == 1)
                return view('activities.atom.elements_and_atoms');
            if($activity_id == 2)
                return view('activities.atom.reading_atoms');
            if($activity_id == 3)
                return view('activities.atom.activity_atom_1');
            if($activity_id == 4)
                return view('activities.atom.activity_atom_2');
            if($activity_id == 5)
                return view('activities.atom.activity_atom_3');
            if($activity_id == 6)
                return view('activities.atom.activity_atom_4');
            if($activity_id == 7)
                return view('activities.atom.activity_atom_5');
            if($activity_id == 8)
                return view('activities.atom.activity_atom_success');            
        }

        if($lesson_id == 2){
            if($activity_id == 1)
                return view('activities.geometry.video');
            if($activity_id == 2)
                return view('activities.geometry.reading_1');           
            if($activity_id == 3)
                return view('activities.geometry.external_1');
            if($activity_id == 4)
                return view('activities.geometry.flipcard_1');
            if($activity_id == 5)
                return view('activities.geometry.flipcard_2');
            if($activity_id == 6)
                return view('activities.geometry.flipcard_3');
            if($activity_id == 7)
                return view('activities.geometry.flipcard_4');
            if($activity_id == 8)
                return view('activities.geometry.flipcard_5');
            if($activity_id == 9)
                return view('activities.geometry.dnd_1');
            if($activity_id == 10)
                return view('activities.geometry.match_1');            
            if($activity_id == 11)
                return view('activities.geometry.multiple_1');            
            if($activity_id == 12)
                return view('activities.geometry.answer_1');            
            if($activity_id == 13)
                return view('activities.geometry.measure_1');            
            if($activity_id == 14)
                return view('activities.geometry.success');
        }

        if($lesson_id == 3){
            if($activity_id == 1)
                return view('activities.civics.video');
            if($activity_id == 2)
                return view('activities.civics.reading_1');           
            if($activity_id == 3)
                return view('activities.civics.reading_2');           
            if($activity_id == 4)
                return view('activities.civics.external_1');
            if($activity_id == 5)
                return view('activities.civics.flipcard_1');
            if($activity_id == 6)
                return view('activities.civics.flipcard_2');
            if($activity_id == 7)
                return view('activities.civics.flipcard_3');
            if($activity_id == 8)
                return view('activities.civics.flipcard_4');
            if($activity_id == 9)
                return view('activities.civics.dnd_1');
            if($activity_id == 10)
                return view('activities.civics.multiple_1');
            if($activity_id == 11)
                return view('activities.civics.answer_1');            
            if($activity_id == 12)
                return view('activities.civics.success');
        }
        if($lesson_id == 4){
            if($activity_id == 1)
                return view('activities.electronics.video');
            if($activity_id == 2)
                return view('activities.electronics.reading_1');           
            if($activity_id == 3)
                return view('activities.electronics.reading_2');           
            if($activity_id == 4)
                return view('activities.electronics.external_1');
            if($activity_id == 5)
                return view('activities.electronics.flipcard_1');
            if($activity_id == 6)
                return view('activities.electronics.flipcard_2');
            if($activity_id == 7)
                return view('activities.electronics.flipcard_3');
            if($activity_id == 8)
                return view('activities.electronics.flipcard_4');
            if($activity_id == 9)
                return view('activities.electronics.dnd_1');
            if($activity_id == 10)
                return view('activities.electronics.multiple_1');
            if($activity_id == 11)
                return view('activities.electronics.answer_1');            
            if($activity_id == 12)
                return view('activities.electronics.success');
        }

    	return redirect('/');
    }
}
