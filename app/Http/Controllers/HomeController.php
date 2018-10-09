<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //Bewirkt, dass die HomeController-Funktionen nur von angemeldeten Benutzern aufgerufen werden können
        $this->middleware('auth');
    }

    /**
     * Zeigt die Home-Seite.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Findet die Bentzer-ID des Angemeldeten Benutzers
        $user_id = auth()->user()->id;
        //Findet den Eintrag zur Benutzer-ID in der users Tabelle
        $user = User::find($user_id);
        //Schickt den Benutzer zur 'home'-Seite mit den Budgets, auf die der Benutzer Zugriff hat
        return view('home')->with('budgets', $user->budget_relations);
    }
}
