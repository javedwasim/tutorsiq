<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    protected $table = 'tlabels';
    protected $guarded = array('id');
}
