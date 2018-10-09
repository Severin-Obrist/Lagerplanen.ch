<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    //Funktionen geben die Views zu den zugehörigen Seiten zurück 
    //zusammen mit dem Titel, der in der Funktion spezifiziert wurde

    public function index(){
        $title = 'Welcome to Laravel!';
        return view('pages.index')->with('title', $title);
    }

    public function about(){
        $title = 'Über mich';
        return view('pages.about')->with('title', $title);
    }

    //Wird nicht mehr gebraucht
    public function budget(){
        $title = 'Budget';
        return view('pages.budget')->with('title', $title);
    }

    //Wird nicht mehr gebraucht
    public function lager(){
        $title = 'Lager';
        return view('pages.lager')->with('title', $title);
    }

    //Wird nicht mehr gebraucht
    public function services(){
        $data = array(
            'title' => 'Services',
            'services' => ['Web design', 'Programming', 'SEO']
        );
        return view('pages.services')->with($data);
    }
}
