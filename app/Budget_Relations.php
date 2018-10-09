<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Budget_Relations extends Model
{
    
    //Table Name
    protected $table = 'budget_relations';
    //Primary Key
    public $primaryKey = 'id';
    //Disable Timestamp
    public $timestamps = false;

    //Erzeugt eine Beziehung zwischen den Models
    
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function budget_content(){
        return $this->belongsTo('App\Budget_Content');
    }

    public function budget_list(){
        return $this->belongsTo('App\Budget_List');
    }
}
