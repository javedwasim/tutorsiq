<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Teacher;

class teacher_application extends Model
{
    protected $guarded = array('id');

    public function teacher(){

        return $this->belongsTo('App\Teacher');
    }

}
