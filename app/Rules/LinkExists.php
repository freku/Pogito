<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Twitch\TH;

class LinkExists implements Rule
{
    protected $json;

    public function __construct($json)
    {
        $this->json = $json;
    }

    public function passes($attribute, $value)
    {
        return TH::clipExists($this->json);
    }

    public function message()
    {
        return 'Klip nie istnieje!';
    }
}
