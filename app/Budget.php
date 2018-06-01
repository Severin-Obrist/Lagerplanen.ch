<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    //Table Name
    protected $table = 'budgets';
    //Primary Key
    public $primaryKey = 'id';
    //Disable Timestamp
    public $timestamps = false;

    //creates a model relationship with the user-model
    public function user(){
        return $this->belongsTo('App\User');
    }
}
