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

    //creates a model relationship with the user-model
    public function user(){
        return $this->belongsTo('App\User');
    }
}
