<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TagList extends Model
{
    protected $guarded = [];

    public function post()
    {
        return $this->belongsTo('App\Post');
    }

    public function tag()
    {
        return $this->belongsTo('App\Tag');
    }
}
