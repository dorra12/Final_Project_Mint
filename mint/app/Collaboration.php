<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collaboration extends Model
{
    public $table = 'collaboration';
    public function mentor()
    {
        return $this->belongsTo('App\User', 'mentor_id', 'mentor_id');
    }
    public function mentee()
    {
        return $this->belongsTo('App\User', 'mentee_id');
    }

}


