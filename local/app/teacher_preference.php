<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class teacher_preference extends Model
{
    protected $table = 'teacher_subject_preferences';
    protected $guarded = array('id');
}
