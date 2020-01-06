<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ban extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function banner()
    {
        return $this->belongsTo('App\User', 'by_user_id');
    }
}
