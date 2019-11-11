<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    // protected $fillable = [
    //     'type', 'commnet_id', 'post_id', 'user_id'
    // ];
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function comment()
    {
        return $this->belongsTo('App\Comment');
    }

    public function post()
    {
        return $this->belongsTo('App\Post');
    }
}