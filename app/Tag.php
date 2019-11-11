<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $guarded = [];

    public function tagLists()
    {
        return $this->hasMany('App\TagList');
    }

    public function exists($name)
    {
        if ($this->where('name', '=', $name)->exists()) {
            return true;
        }

        return false;
    }

    public function add($array) 
    {
        return User::create([
            'name' => $array['name'],
        ]);
    }
}