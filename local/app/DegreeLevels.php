<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DegreeLevels extends Model
{
    protected $table = 'teacher_degree_level';
    protected $guarded = array('id');
}
