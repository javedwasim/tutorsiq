<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class email_template extends Model
{

    protected $fillable = array('title','subject','body','is_active','created_at','updated_ar');

}
