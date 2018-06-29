<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Budget_List extends Model
{
    //Table Name
    protected $table = 'budget_list';
    //Primary Key
    public $primaryKey = 'id';
    //Disable Timestamp
    public $timestamps = false;
}
