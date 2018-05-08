<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    //Functions return the different views => tell the website what page shoul be showed

    public function index(){
        $title = 'Welcome to Laravel!';
        //return view('pages.index', compact('title'));
        return view('pages.index')->with('title', $title);
    }

    public function about(){
        $title = 'about us';
        return view('pages.about')->with('title', $title);
    }

    public function budget(){
        $title = 'Budget';
        return view('pages.budget')->with('title', $title);
    }

    public function lager(){
        $title = 'Lager';
        return view('pages.lager')->with('title', $title);
    }

    public function services(){
        $data = array(
            'title' => 'Services',
            'services' => ['Web design', 'Programming', 'SEO']
        );
        return view('pages.services')->with($data);
    }
}
