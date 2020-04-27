<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Lesson;

class HomeController extends Controller
{

    public function index(){
        $lessons = Lesson::all();
        return view('home', compact('lessons'));
    }

    public function dashboard2(){
        return view(
        	'dashboard2', 
        	[]
        );
    }
}
