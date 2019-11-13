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
        'likes' => 0
    ];

    public function likes()
    {
        return $this->hasMany('App\Like');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function post()
    {
        return $this->belongsTo('App\Post');
    }
}
