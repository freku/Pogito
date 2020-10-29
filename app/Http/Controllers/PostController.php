<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Rules\LinkExists;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Twitch\TH;
use App\Twitch\Helpers;

use App\TagList;
use App\Tag;
use App\Post;
use App\Like;
use App\Rank;

use Illuminate\Support\Facades\View;
use Illuminate\Http\Response;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show', 'sort_new']);
    }

    public function index()
    {
        // $todayMinusOneWeekAgo = \Carbon\Carbon::today()->subWeek();
        $todayMinusOneWeekAgo = \Carbon\Carbon::today()->subYear();

        $posts = Post::all()->where('created_at', '>=', $todayMinusOneWeekAgo)->sortByDesc('likes')->forPage(1, 5);

        return view('home', ['posts' => $posts, 'sort_type' => 1]);
    }

    public function sort_new()
    {
        $todayMinusOneWeekAgo = \Carbon\Carbon::today()->subYear();

        $posts = Post::all()->where('created_at', '>=', $todayMinusOneWeekAgo)->sortByDesc('likes')->forPage(1, 5);
        return view('home', ['posts' => $posts, 'sort_type' => 2]);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        // make tags an array
        $tag_array = preg_split('/\s+/', trim($request->input('tags')));
        $request->merge(['tags' => $tag_array]);
        // set id of the clip
        $id = TH::getLinkID($request->input('link'));
        $json = TH::getJsonFromTwitch($id);
        
        // validate
        $validator = $this->validatePostData($request, $json)->validate();
        
        $json = $json['data'][0];
        
        $post = $this->addPost($request, $json);
        dd('yes3');
        $this->processTags($request->input('tags'), $post->id);
        dd('yes4');

        // redirect to new added post
        return redirect("/post/$post->id-" . Str::slug($post->title));
    }
    
    public function validatePostData(Request $request, $json)
    {
        $messages = [
            'tytul.required' => 'Pole :attribute jest wymagane!',
            'link.required' => 'Pole :attribute jest wymagane!',
            'tytul.max' => 'Pole :attribute nie może być dłuższe niż 255 znaków!',
            'link.max' => 'Pole :attribute nie może być dłuższe niż 255 znaków!',
            'tags.*.max' => 'Tagi mogą mieć maksymalnie 12 znaków!',
            'tags.*.starts_with' => 'Tagi muszą zaczynać się z #. np. #Fail',
            'tags.*.min' => 'Bledy tag, przyklad: #Fail',
            'tags.*.distinct' => 'Tagi nie mogą się powtarzać!',
            'tags.*.regex' => 'Niepoprawny format tagu! Przyklad: #nazwa gdzie nazwa może składać się tylko z liter.',
            'tags.max' => 'Dozwolone jest maksymalnie 5 tagów!',
        ];
        $rules = [
            'tytul' => ['required', 'max:255'],
            'link' => ['required', 'max:255', new LinkExists($json)],
            'tags' => ['array', 'max:5'], // maksymalnie 5 tagów ***
            'tags.*' => ['max:12', 'starts_with:#', 'min:2', 'distinct', 'regex:/^[#][a-zA-Z]+$/u']
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    public function addPost($req, $json)
    {
        $posts_num = Post::all()->count();
        $popularity = $posts_num > 0 ? (int)Post::avg('popularity') : 10;
        // $popularity = $posts_num > 0 ? (int)Post::avg('popularity') * 0.75  : 10; // 75% średniej popularności
        dd($json);
        return Post::create([
            'user_id' => Auth::user()->id,
            'thumbnail_url' => $json['thumbnail_url'],
            'clip_url' => $json['embed_url'] . '&parent=localhost',
            // 'popularity' => $posts_num > 0 ? Post::avg('popularity') : 10, // POPULARITY
            'popularity' => $popularity, // POPULARITY
            'title' => $req->input('tytul'),
            'streamer_name' => $json['broadcaster_name']
        ]);
    }

    public function processTags($tags, $post_id)
    {
        if (count($tags) == 1 && $tags[0] == '') {
            return;
        }

        foreach ($tags as $key => $value) {
            $newTag = Tag::firstOrCreate(['name' => $value]);

            TagList::create([
                'post_id' => $post_id,
                'tag_id' => $newTag->id
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $url_array = explode('-', $id);
        $post_id = $url_array[0];

        $post = Post::find($post_id);

        // post istnieje
        if ($post) {
            // ustawic poprawne url ( z tytulem postu )
            if (count($url_array) == 1) {
                return redirect("/post/$post_id-" . Str::slug($post->title));
            }
            else if ($id != $post_id . '-' . Str::slug($post->title)) {
                return redirect("/post/$post_id-" . Str::slug($post->title));
            }

            $user_id = Helpers::getUserID();

            $view = view('posts.view', [
                'tytul' => $post->title,
                'autor' => $post->user->name,
                'avatar' => $post->user->avatar,
                'autor_id' => $post->user->id,
                'clip_url' => $post->clip_url,
                'likes' => $post->likes,
                'tags' => $post->tagLists,
                // 'time' => $this->getPostDate($post->created_at),
                'time' => Helpers::getPostDate($post->created_at),
                'post_id' => $post->id,
                'is_liked' => Helpers::isLiked($user_id, 'post_id', $post->id),
                'is_rank' => Helpers::hasRank($user_id, ['Mod', 'Admin'])
            ]);

            // $response = ;
            return response($view, 200);

        } else { // post nie istnieje
            // view 404 page
            return redirect('/');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
