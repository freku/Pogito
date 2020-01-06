<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Post;
use App\Comment;
use App\Like;
use Carbon\Carbon;

class DoesntHaveCooldown implements Rule
{
    protected $user_id;
    protected $cdInMinutes;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($userid)
    {
        $this->user_id = $userid;
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
        $ifs = [
            "500" => '0',
            "450" => '1',
            "400" => '2',
            "350" => '3',
            "300" => '4',
            "250" => '5',
            "200" => '6',
            "150" => '7',
            "100" => '8',
            "50" => '9',
            "0" => '10',
        ];

        // $coms_num = Comment::where('user_id', $this->user_id)->count();
        $coms_likes = Comment::where('user_id', $this->user_id)->sum('likes');
        $last_com = Comment::where('user_id', $this->user_id)->max('created_at');

        $last_com_minutes = Carbon::now()->diffInMinutes(Carbon::parse($last_com));
        $ifs_index = 0;

        foreach ($ifs as $key => $value) {
            if ($coms_likes >= (int)$key) {
                if ($ifs_index == 0) 
                    $ifs_index = $key;

                if ($last_com_minutes >= (int)$value) {
                    return true;
                }
            }
        }

        $this->cdInMinutes = $ifs[$ifs_index] - $last_com_minutes;

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Nie możesz wysyłać jeszcze tyle wiadomości naraz! Odczekaj ' . $this->cdInMinutes . ' minuty.';
    }
}
