<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/** ##################################################
*   Ganzes Model wird nicht mehr gebraucht,
*   ist ein Überbleibsel aus einem Tutorial,
*   wurde der Vollständigkeit halber nicht entfernt.
*   ##################################################
*/

class Post extends Model
{
    //Table Name
    protected $table = 'posts';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;

    //Erzeugt eine Beziehung zwischen den Models

    public function user(){
        return $this->belongsTo('App\User');
    }
}
