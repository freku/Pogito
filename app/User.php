<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'twitch_id', 'name_tw', 'avatar_tw'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function bans()
    {
        return $this->hasMany('App\Ban');
    }

    public function given_bans()
    {
        return $this->hasMany('App\Ban', 'id', 'by_user_id');
    }

    public function likes()
    {
        return $this->hasMany('App\Like');
    }

    public function posts()
    {
        return $this->hasMany('App\Post');
    }
    
    public function rank()
    {
        return $this->hasOne('App\Rank');
    }
}