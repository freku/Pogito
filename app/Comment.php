<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // protected $fillable = [
    //     'user_id', 'post_id', 'message', 'likes'
    // ];
    protected $guarded = [];
    
    protected $attributes = [
        'likes' => 0,
        'isRemoved' => 0
    ];

    public function likes()
    {
        return $this->hasMany('App\Like');
    }

    public function sub_coms()
    {
        return $this->hasMany('App\Comment', 'sub_of');
    }

    public function parent()
    {
        return $this->belongsTo('App\Comment', 'sub_of', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function post()
    {
        return $this->belongsTo('App\Post');
    }

    public function reports()
    {
        return $this->hasMany('App\Report');
    }
}
