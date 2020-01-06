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

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        // ustawienia profilu
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
        
        if ($is_his_profile) {
            $is_password_set = User::find(Auth::user()->id)->password;
            return view('profile.settings', [
                'user' => $request->name,
                'is_pw_set' => $is_password_set
            ]);
        } else {
            return redirect("/x/$request->name");
        }
    }
}