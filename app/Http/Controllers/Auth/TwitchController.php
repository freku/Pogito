<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Twitch\Helpers;
use Socialite;
use App\User;
use Auth;
use Illuminate\Support\Facades\Validator;
use Exception;
use Redirect;

class TwitchController extends Controller
{
    public function __construct()
    {
        // $this->middleware('guest');
    }

    public function redirectToProvider()
    {
        return Socialite::with('twitch')->redirect(); 
    }

    public function handleProviderCallback(Request $request)
    {
        try {
            $tw_user = Socialite::driver('twitch')->user();
        } catch(Exception $e) {
            report($e);
            return redirect('/');
        }

        $redirect = $this->processTwitchAuth($tw_user);

        if ($redirect == 'TWITCH_NEW_ACC') {
            return redirect('/login/twitch/new-account');
        } else if ($redirect == 'LOGIN_ERROR') {
            return Redirect::back()->withErrors(['To twtich konto jest już połaczone z innym kontem.']);
        } else {
            if(session()->has('url.intended')) {
                return redirect(session('url.intended'));
            }
            return redirect('/');
        }
    }
// Zalogowany:
//     Do normalnego konta zostaje ustawione połączone konto Twitch ( jeśli jakies jest juz dolaczone to zostaje zastapione nowym jesli user chce)
// Niezalogowany:
//     Czy konto jest juz polaczone z jakims? = Jesli tak to zalogowac?
//     Konto nie zostało jeszcze stworzone, wyskakuje box proszący o nick nowego normalnego konta, z którym zostanie połaczone konto twitch z którego się loguje
    public function processTwitchAuth($twUser)
    {
        $user_id = Helpers::getUserID();

        if ($user_id) { // zalogowany
            $user = User::find($user_id);
            $current_holder = User::where('twitch_id', $twUser->id)->first();

            if ($current_holder) { // twitch account is currently used by someone elese
                return 'LOGIN_ERROR'; // to nizej usuwa info twitch z aktualnego konta ( odlacza konto twitch )
                // $current_holder->name_tw = null;
                // $current_holder->twitch_id = null;
                // $current_holder->save();
            }
            // if ($user->twitch_id == null || $user->twitch_id == '') { // is any twitch account already connected
            if ($user) {
                $user->name_tw = $twUser->name;
                $user->twitch_id = $twUser->id; // chyba ['id]
                $user->save();

                $user->isTwitch = true;
                Auth::login($user);
            }
        } else { // niezalogowany
            $user = User::where('twitch_id', $twUser->id)->where('name_tw', $twUser->name)->first();

            if ($user) { // konto twitch jest juz polaczone
                $user->isTwitch = true;

                Auth::login($user);
            } else { // konto twitch nie jest polaczone, spytaj o nick nowego normalnego konta
                $email = User::where('email', $twUser->email)->first();

                $data = [
                    'name' => $twUser->name,
                    'id' => $twUser->id,
                    'avatar' => $twUser->avatar,
                    'email' => $email ? null : $twUser->email
                ];

                session(['tw_data' => $data]);
                return 'TWITCH_NEW_ACC';
            }
        }
    }
    
    public function newAccountByTwitchAuth(Request $request)
    {
        if (session()->has('tw_data')) {
            return view('auth.tw-new-acc');
        } else {
            return redirect('/');
        }
    }

    public function newAccountByTwitchAuthCheck(Request $request)
    {
        $vals = [];
        $vals['name'] = ['required', 'string', 'between:4,20', 'unique:users', 'alpha_num', 'bail'];

        if (session('tw_data')['email'] == null) {
            $vals['email'] = ['required', 'string', 'email', 'max:255', 'unique:users']; 
        }

        $validatedData = Validator::make($request->all(), $vals, [
            'name.required' => 'Nick jest wymagany.',
            'email.required' => 'Email jest wymagany',
            'email' => 'Podaj email.',
            'max' => 'Pole nie może być większe niż :max znaków',
            'string' => 'Pole musi być tekstem.',
            'between' => 'Nick musi byc pomiędzi 4 a 20 znakami.',
            'unique' => "':input' jest już zajęte!",
            'alpha_num' => 'Nick musi się składać tylko z liter i cyfr.',
        ])->validate();
        
        // $email = User::where('email', session('tw_data')['email'])->first();
        // SQLSTATE[42S22]: Column not found: 1054 Unknown column 'twitch_id' in 'where clause' 
        // (SQL: select * from `users` where `email` = xfreku@gmail.com and `twitch_id` is null limit 1)
        $user = User::create([
            'name' => $request->name,
            'name_tw' => session('tw_data')['name'],
            // 'email' => $email ? 'KEK-' . $request->name : session('tw_data')['email'],
            'email' => session('tw_data')['email'] == null ? $request->email : session('tw_data')['email'],
            'password' => '',
            'avatar' => URL('images/avatars/basic.jpg'),
            'twitch_id' => session('tw_data')['id']
        ]);

        // $user->email = 'KEK-' . $user->id;
        // $user->save();
        $user->isTwitch = true;

        Auth::login($user);
        
        if(session()->has('url.intended')) {
            return redirect(session('url.intended'));
        }
        
        return redirect('/');
    }
}