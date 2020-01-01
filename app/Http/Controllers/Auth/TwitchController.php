<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Socialite;
use App\User;
use Auth;

class TwitchController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function redirectToProvider()
    {
        return Socialite::with('twitch')->redirect(); 
    }

    public function handleProviderCallback()
    {
        $tw_user = Socialite::driver('twitch')->user();

        $this->processTwitchAuth($tw_user);

        if(session()->has('url.intended')) {
            return redirect(session('url.intended'));
        }
        return redirect('/');
    }

    public function processTwitchAuth($twUser)
    {
        $user = User::firstOrNew(
            ['twitch_id' => $twUser['id']]
        );

        // Twitch acc exists - update avatar, else insert record
        // Finally set auth login
        if ($user->exists) {
            $user->avatar = $twUser->avatar;
            $user->save();
        } else {
            $user->name = $twUser->name;
            // $user->email = $twUser->email;
            $user->avatar = $twUser->avatar;

            User::create([
                'name' => $twUser->name,
                // 'email' => $twUser->email,
                'email' => $twUser['id'],
                'password' => '',
                'avatar' => $twUser->avatar,
                'twitch_id' => $twUser['id']
            ]);
        }

        $user->isTwitch = true;

        Auth::login($user);
    }
}