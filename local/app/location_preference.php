<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class location_preference extends Model
{
    protected $table = 'teacher_location_preferences';
    protected $guarded = array('id');
}
