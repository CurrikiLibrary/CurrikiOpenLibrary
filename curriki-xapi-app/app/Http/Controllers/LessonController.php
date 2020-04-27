<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Lesson;

class LessonController extends Controller
{
    public function index($id){
    	if($id == 1)
    		return view('lessons.lesson_chemistry_of_life');
    	if($id == 2)
    		return view('lessons.lesson_geometry');
    	if($id == 3)
    		return view('lessons.lesson_being_american');
    	if($id == 4)
    		return view('lessons.concepts_of_music');

    	return redirect('/');
    }

    public function tempindex($id){
        $lesson = Lesson::find($id);
        if(empty($lesson))
            return redirect('/')->withErrors(['error' => 'Lesson not found']);

        return view('lessons.lesson_index', compact('lesson'));
    }
}
