<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class application_status extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'application_status';
    protected $guarded = array('id');
}
