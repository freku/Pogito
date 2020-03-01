<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Post;
use App\Comment;
use App\Like;
use App\Twitch\Helpers;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use App\Traits\UploadTrait;
use App\Twitch\TH;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    use UploadTrait;

    public function __construct()
    {
        $this->middleware('auth')->except(['show']);
    }

    public function show(Request $request)
    {
        // ustawienia profilu
        // tak ma byc bo kazdy ma moc ogladac profile
        $user = User::where('name', $request->name)->first();

        if ($user) {
            $post_num = Post::where('user_id', $user->id)->count();
            $comment_num = Comment::where('user_id', $user->id)->count();
            $post_likes = Post::where('user_id', $user->id)->sum('likes');
            $comment_likes = Comment::where('user_id', $user->id)->sum('likes');
            $created_at = $user->created_at;
            $last_activity = $this->last_activity($user, $created_at) == '0 godz. temu' ? 'hmm...' : $this->last_activity($user, $created_at);
            $is_his_profile = $request->name == (Auth::check() ? Auth::user()->name : '');

            return view('profile.view', ['user' => $user,
             'post_num' => $post_num,
              'com_num' => $comment_num,
               'post_likes' => $post_likes,
                'com_likes' => $comment_likes,
                 'created_at' => Carbon::parse($created_at)->format('d-m-Y'),
                  'last_activity' => $last_activity,
                   'is_his_profile' => $is_his_profile]);
        } else {
            return view('profile.view');
        }
    }

    public function last_activity($user, $user_created_at)
    {
        $last_com = Comment::where('user_id', $user->id)->orderBy('created_at', 'desc')->first();
        $last_like = Like::where('user_id', $user->id)->orderBy('created_at', 'desc')->first();
        $last_post = Post::where('user_id', $user->id)->orderBy('created_at', 'desc')->first();

        $last_date = max([$last_com ? $last_com->created_at : Carbon::now(),
         $last_like ? $last_like->created_at : Carbon::now(),
          $last_post ? $last_post->created_at : Carbon::now()
        ]);
        
        return Helpers::getPostDate($last_date == Carbon::now() ? $user_created_at : $last_date);
    }

    public function settings(Request $request)
    {
        $is_his_profile = $request->name == (Auth::check() ? Auth::user()->name : '');
        $is_twitch_connected = Auth::check() ? Helpers::isTwitchConnected(Auth::user()->id) : false;
        
        if ($is_his_profile) {
            $is_password_set = User::find(Auth::user()->id)->password;
            return view('profile.settings', [
                'user' => $request->name,
                'is_pw_set' => $is_password_set,
                'is_twitch_connected' => $is_twitch_connected
            ]);
        } else {
            return redirect("/x/$request->name");
        }
    }

    public function settings_post(Request $request)
    {
        $type = (int)$request->type;

        if ($type == 1) { // pierwsze ustawienie hasła 
            $val = Validator::make($request->all(), [
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ], [
                'required' => 'Podaj hasła',
                'string' => 'Pola muszą być tekstem',
                'min' => 'Hasło musi mieć minimum :min znaków',
                'confirmed' => 'Hasła nie są takie same!'
            ])->validate();

            // $usr = User::where('name', $request->name)->first();
            $usr = User::find(Auth::user()->id);

            if ($usr) {
                $usr->password = Hash::make($request->password);
                $usr->save();

                return Redirect::back();           
            } else {
                return redirect('/');
            }
        } else if ($type == 2) { // usuniecie polaczonego konta twitch
            // $usr = User::where('name', $request->name)->first();
            $usr = User::find(Auth::user()->id);

            if ($usr) {
                if ($request->acc_name != Auth::user()->name) {
                    return Redirect::back()->withErrors(['wrong_nick' => 'Podany nick nie jest poprawny. Twój nick to: ' . Auth::user()->name . '.']);
                }

                $has_password = $usr->password != null;

                if (Helpers::isTwitchConnected($usr)) {
                    if (!$has_password) {
                        $usr->name_tw = '';
                        $usr->twitch_id = '';
                        $usr->email = 'KEK-' . $usr->name;
                        $usr->name = '.';
                        $usr->password = '';
                        $usr->save();
                        Auth::logout();

                        return redirect('/');
                    } else {
                        $usr->name_tw = null;
                        $usr->twitch_id = null;
                        $usr->save();

                        return Redirect::back();
                    }
                } else {
                    return Redirect::back()->withErrors(['no_tw_connected' => 'Żadne konto twitch nie jest aktualnie połączone!']);
                }
            } else {
                return redirect('/');
            }
        } else if ($type == 3) { // zmiana avatara
            $usr = User::find(Auth::user()->id);
            
            if ($request->avk_from_twitch) {
                if (!Helpers::isTwitchConnected($usr)) {
                    return Redirect::back()->withErrors(['avatar' => 'Konto twitch nie jest połączone.']);
                }

                $json = TH::getJsonFromTwitchUser(Auth::user()->twitch_id);
                $json = $json['data'][0];

                $usr->avatar = $json['profile_image_url'];
                $usr->save();
                
                return Redirect::back()->withErrors(['success' => 'Pomyślnie zmieniono avatar profilowy.']);
            }

            $val = Validator::make($request->all(), [
                'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048'
            ], [
                'required' => 'Nie wybrałeś pliku.',
                'image' => 'Podany plik musi być zdjęciem',
                'mimes' => 'Wybrałeś plik z niedozwolonym rozszerzeniem. Dozowalone rozszerzenia: .jpeg, .jpg, .png',
                'max' => 'Plik może mieć maksymalnie 2048 KB'
            ])->validate();

            if ($usr) {
                if ($request->has('avatar')) {
                    $image = $request->file('avatar');
                    $roz = $image->getClientOriginalExtension();
                    $image = Image::make($image->getRealPath());
                    
                    if ($image->width() > 300 || $image->height() > 300) {
                        $image->resize(300, 300);
                    }

                    $name = Auth::user()->name . '_' . Auth::user()->id;

                    $folder = '/images/avatars/';
                    $path = Storage::disk('public')->path($folder . $name . '.' . $roz);
                    $image->save($path);

                    $usr->avatar = URL('storage' . $folder . $name . '.' . $roz);
                    $usr->save();
                    
                    return Redirect::back()->withErrors(['success' => 'Pomyślnie zmieniono avatar profilowy.']);
                } else {
                    return Redirect::back();
                }
            } else {
                return redirect('/');
            }
        } else {
            return Redirect::back();
        }
    }
}