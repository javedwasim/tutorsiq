<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\teacher_application;

class Teacher extends Model
{

    protected $guarded = array('id', 'password');

    public function applications(){

        return $this->hasMany('App\teacher_application');
    }
}
