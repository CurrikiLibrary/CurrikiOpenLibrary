<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Lesson;

class LessonAdminController extends Controller
{
    public function index(){
        $lessons = Lesson::all();
    	return view('lessons.admin.index', compact('lessons'));
    }

    public function create(){
        return view('lessons.admin.create');
    }

    public function edit($id){
        $lesson = Lesson::find($id);
        if(empty($lesson))
            return back()->withErrors(['error' => 'Lesson not found']);

        return view('lessons.admin.create', compact('lesson'));
    }

    public function save(Request $request){
        $request->validate([
            'title' => 'required|min:1|max:255',
            'author' => 'required|min:1|max:255',
            'description' => 'required|min:1|max:1024',
            'thumb' => 'file',
        ]);

        if($request->filled('lesson_id'))
            $lesson = Lesson::find($request->input('lesson_id'));
        else
            $lesson = new Lesson;

        if($request->hasFile('thumb'))
            $lesson->thumb = $request->file('thumb')->store('lesson_thumbnails', 'public');

        $lesson->title = $request->input('title');
        $lesson->author = $request->input('author');
        $lesson->description = $request->input('description');
        $lesson->save();
        Session::flash('msg', 'Lesson saved.');
        return redirect('/admin/lessons');
    }

    public function delete($id){
        $lesson = Lesson::find($id);
        foreach($lesson->activities as $activity){
            $activity->delete();
        }
        $lesson->delete();
        Session::flash('msg', 'Lesson deleted.');
        return redirect('/admin/lessons');
    }
}
