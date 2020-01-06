<?php

namespace App\Twitch;
use App\Like;
use App\Rank;
use Auth;

class Helpers
{
    public static function getPostDate($created_at)
    {
        $created_at = \Carbon\Carbon::parse($created_at); 
        $now = \Carbon\Carbon::now();

        $dif = $now->diffInHours($created_at);

        if ($dif <= 24) {
            return $dif . ' godz. temu';
        } else {
            return $created_at->format('d.m.Y');
        }
    }

    public static function getUserID()
    {
        if (Auth::check()) {
            return Auth::user()->id;
        } else {
            return false;
        }
    }

    public static function isLiked($user_id, $col, $liked_id)
    {
        if ($user_id && Like::where('user_id', '=', $user_id)->where($col, '=', $liked_id)->exists()) {
            return true;
        }

        return false;
    }

    public static function hasRank($user_id, $ranks)
    {
        if ($user_id != false) {
            $rank = Rank::where('user_id', $user_id)->first();

            if ($rank) {
                foreach ($ranks as $value) {
                    if ($rank->name == $value) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}