<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // protected $fillable = [
    //     'user_id', 'link', 'likes', 'comments', 'popularity'
    // ];
    protected $guarded = [];

    protected $attributes = [
        'likes' => 0,
        'comments' => 0
    ];

    public function get_comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function get_likes()
    {
        return $this->hasMany('App\Like');
    }
    
    public function tagLists()
    {
        return $this->hasMany('App\TagList');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
