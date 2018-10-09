<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * Diese Attribute sind bearbeitbar
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'pfadiname',
    ];

    /**
     * Diese Attribute sind versteckt, werden
     * also nicht in Arrays gesteckt, wenn Daten 
     * herausgefiltert werden
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //Erzeugt eine Beziehung zwischen den Models

    public function posts(){ //Nicht mehr gebraucht
        return $this->hasMany('App\Post');
    }

    public function budget_contents(){
        return $this->hasMany('App\Budget_Contents');
    }

    public function budget_relations(){
        return $this->hasMany('App\Budget_Relations');
    }
}
