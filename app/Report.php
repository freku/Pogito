<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $guarded = [];

    protected $attributes = [
        'amount' => 0,
        'checked' => 0
    ];

    public function comment()
    {
        return $this->belongsTo('App\Comment');
    }
}
