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

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return TH::clipExists($this->json);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Klip nie istnieje!';
    }
}
