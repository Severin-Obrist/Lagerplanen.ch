<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Budget_Contents extends Model
{
    //Table Name
    protected $table = 'budget_contents';
    //Primary Key
    public $primaryKey = 'id';
    //Disable Timestamp
    public $timestamps = false;

    //Bestimmt, Attribute bearbeitbar sind
    protected $fillable = ['pid', 'bid', 'budgetPosten', 'content', 'notes'];

    //Erzeugt eine Beziehung zwischen den Models

    public function user(){
        return $this->belongsTo('App\User');
    }
    public function budget_relations(){
        return $this->belongsTo('App\Budget_Relations');
    }
}
