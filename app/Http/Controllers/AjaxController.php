<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Post;
use App\Comment;
use App\Like;
use App\Ban;
use App\Report;
use App\Twitch\Helpers;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use App\Rules\DoesntHaveCooldown;

class AjaxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['like', 'getComments', 'getPosts']);
    }

    public function getPosts(Request $request)
    {
        // sortowanie wedlug:
        //   - popularnosci
        //   - nowości
        
        //   - tagow ( obecnie niepotrzebne )
        //   - streamerow ( obecnie niepotrzebne )
        //      - normalne, tagi, streamerzy (strony)

        $num = (int)$request->page_num;
        $sort_type = (int)$request->sort_type;
        $posts_amount = 3;
        $user_id = Helpers::getUserID();

        if ($sort_type == 1) { // sort by popularity
            $posts = Post::all()->sortByDesc('popularity')->forPage($num, $posts_amount);

        } else if ($sort_type == 2) { // sort by date (fresh posts)
            $posts = Post::all()->sortByDesc('created_at')->forPage($num, $posts_amount);

        } else {
            return response()->json(array('status' => 'error'), 404);
        }
        
        $res_array = [];

        foreach ($posts as $key => $value) {
            $tags = $value->tagLists;
            $temp = [];

            foreach ($tags as $key2 => $value2) {
                $temp[] = [
                    'id' => $value2->tag->id,
                    'name' => $value2->tag->name,
                ];
            }

            $value['post_url'] = URL("/post/$value->id-" . Str::slug($value->title));
            $value['time_passed'] = Helpers::getPostDate($value->created_at);
            $value['title'] = e($value['title']);
            $value['is_liked'] = Helpers::isLiked($user_id, 'post_id', $value->id);

            $res_array[] = [
                'post' => $value,
                'author' => $value->user->name,
                'tags' => $temp
            ];
        }

        $response = array(
            'status' => 'success',
            'posts' => $res_array,
            'num' => $posts->count()
        );

        return response()->json($response, 200);
    }

    public function getComments(Request $request)
    {
        $post = Post::find($request->post_id);
        $user_id = Helpers::getUserID();
        // $post = Post::find(1);
        if (!$post) {
            return response()->json(array(
                'status' => 'error'
            ), 404);
        }
        // $comments = $post->get_comments->where('sub_of', '0');
        $num = (int)$request->page_num;
        $coms_amount = 5;
        
        // $comments = $post->get_comments->where('sub_of', '0')->sortByDesc('likes')->offset($num == 1 ? 1 : $num * $coms_amount)->limit($coms_amount);
        $comments = $post->get_comments->where('sub_of', '0')->sortByDesc('likes')->forPage($num, $coms_amount);

        $coms = [];
        $sub_coms = [];
        
        foreach ($comments as $key => $value) {
            $coms[$key] = [
                'id' => $value->id,
                'sub_of' => $value->sub_of,
                'author_name' => $value->user->name,
                'author_id' => $value->user->id,
                'author_avatar' => $value->user->avatar,
                'message' => $value->isRemoved == '1' ? '!' : $value->comment,
                'likes' => $value->likes,
                'is_liked' => Helpers::isLiked($user_id, 'comment_id', $value->id),
                'isRemoved' => $value->isRemoved == '1',
                'tw_author_name' => $value->user->name_tw,
                'rank_name' => $value->user->rank ? $value->user->rank->name : 'null'
            ];

            // $subs_tmp = $value->sub_coms;
            // $subs_tmp = Comment::find($value->id)->sub_coms->sortByDesc('likes');
            $subs_tmp = Comment::find($value->id)->sub_coms->sortBy('likes');

            if ($subs_tmp->count() > 0) {
                $sub_coms[$key] = [];
                
                foreach ($subs_tmp as $key2 => $value2) {
                    $sub_coms[$key][] = [
                        'sub_of' => $value2->sub_of,
                        'com_id' => $value2->id,
                        'author_name' => $value2->user->name,
                        'author_id' => $value2->user->id,
                        'author_avatar' => $value2->user->avatar,
                        'message' => $value2->isRemoved == '1' ? '!' : $value2->comment,
                        'likes' => $value2->likes,
                        'is_liked' => Helpers::isLiked($user_id, 'comment_id', $value2->id),
                        'isRemoved' => $value2->isRemoved == '1',
                        'tw_author_name' => $value2->user->name_tw,
                        'rank_name' => $value2->user->rank ? $value2->user->rank->name : 'null'
                    ];
                }
            }
        }

        $response = array(
            'status' => 'success',
            'comments' => $coms,
            'sub_comments' => $sub_coms,
            'num' => $comments->count()
        );

        return response()->json($response, 200);

    }

    public function comment(Request $request)
    {
        $request->merge(['message' => e($request->message)]);
        $user_id = Helpers::getUserID();

        $msgs = [
            'message.required' => 'Musisz coś wpisać!',
            'message.max' => 'Dozwolone jest tylko 255 znaków!'
        ];

        $rules = ['message' => 'bail|required|max:455|min:2'];
        $rules = ['message' => ['bail', 'required', 'max:455', 'min:2', new DoesntHaveCooldown($user_id)]];

        $validator = Validator::make($request->all(), $rules, $msgs);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ], 422);
        }

        $new_com =  $this->addComment($request);
        $new_com->post->comments += 1;
        $new_com->post->save();
        // $is_author = false;
        
        // if (Post::where('user_id', '=', Auth::user()->id)->exists()) {
        //     $is_author = true;
        // }

        $response = array(
            'status' => 'success',
            'name' => Auth::user()->name,
            'message' => $request->message,
            'avatar' => Auth::user()->avatar,
            'user_id' => Auth::user()->id,
            'com_id' => $new_com->id,
            'sub_of' => $request->sub_of
        );

        return response()->json($response, 200);
    }

    public function addComment($req)
    {
        // poczatkowo mozesz wysylac wiadomosci cos jakis czas, np 10 min
        // aby cd zostal usuniety musisz posiadasz ustalona ilosc lajkow / postow
        $post_id = (int)$req->post_id;
        $user_id = Auth::user()->id;
        $content = $req->message;
        $sub_of = $req->sub_of;
        $record_sub_of = 0;

        if ($sub_of) {
            // nie ma parenta = dodaje komentarz jako jego child, jesli ma to dodaje go jako child parenta tego komenatarza
            $com = Comment::find($sub_of);
            $parent = $com->parent;

            if ($com->count() > 0) {
                if ($parent) {
                    $record_sub_of = $parent->id;
                } else {
                    $record_sub_of = $com->id;
                }
            }
        }
        
        return Comment::create([
            'user_id' => $user_id,
            'post_id' => $post_id,
            'comment' => $content,
            'sub_of' => $record_sub_of
        ]);
    }

    public function like(Request $request)
    {
        // ADD 1 to comments column after adding new comment
        $id = 0;
        $user_id = Helpers::getUserID();
        $column = '';

        if (!$user_id) {
            return response()->json('no login', 422);
        }

        if ($request->com_id) {
            $id = $request->com_id;
            $column = 'comment_id';
        } else if ($request->post_id) {
            $id = $request->post_id;
            $column = 'post_id';
        } else {
            return response()->json('no type', 400);
        }

        $like = Like::firstOrNew(
            [$column => $id, 'user_id' => $user_id]
        );

        if ($column == 'comment_id') {
            if ($like->exists) {
                $like->delete();
                $like->comment->likes -= 1;
            } else {
                $like->type = 'comment';
                $like->comment->likes += 1;
                $like->save();
            }
            $like->comment->save();
        } else {
            if (!$like->exists) {
                $a_ = 0.0000002; // r = 315
                // $a_ = 0.00000002; // r = 30 
                // 0.0000002 na 2010 > 2326.04, 0.002 na 2010.29 > 9999999, 0.0002 na 2010 > 317757.63
                $like->type = 'post';
                $like->post->likes += 1;
                $like->post->popularity = ($a_ * Carbon::now()->timestamp) + ((1 - $a_) * $like->post->popularity);
                $like->post->save();
                $like->save();
            }
        }

        return response()->json('all good', 200);
    }

    public function test(Request $req)
    {
        return response()->json('ALL GOOD', 200);
    }

    public function report(Request $req)
    {
        $id = $req->id;
        $user_id = Helpers::getUserID();

        if (!$user_id) {
            return response()->json('no login', 422);
        }

        $report = Report::firstOrNew([
            'comment_id' => $id,
            'checked' => '0'
        ]);

        if($report->exists) {
            $report->amount += 1;
        }
        
        $report->save();
        
        return response()->json('good', 200);
    }

    public function report_action(Request $request)
    {
        $id = $request->id;
        $action = $request->action;
        $user_id = Helpers::getUserID();

        if (!$user_id || !Helpers::hasRank($user_id, ['Mod', 'Admin'])) {
            return response()->json('foff', 422);
        }

        $report = Report::find($id);

        if ($report) {
            $report->checked = '1';
            $report->save();

            if ($action == 1) { // 1 - zostaw komentarz w spokoju

            } else if ($action == 2) { // 2 - usun tylko komentarz
                $report->comment->isRemoved = '1';
                $report->comment->save();
            }
        } else {
            return response()->json('bad', 404);
        }
        
        return response()->json('good', 200);
    }

    public function remove_comment(Request $request)
    {
        $user_id = Helpers::getUserID();

        if (!$user_id || !Helpers::hasRank($user_id, ['Mod', 'Admin'])) {
            return response()->json('foff', 422);
        }

        $com_id = $request->id;

        $com = Comment::find($com_id);
        
        if ($com) {
            $com->isRemoved = '1';
            $com->save();
            return response()->json('git gut', 200);
        } else {
            return response()->json('noff', 404);
        }
    }

    public function ban_account(Request $request)
    {
        $user_id = Helpers::getUserID();

        if (!$user_id || !Helpers::hasRank($user_id, ['Mod', 'Admin'])) {
            return response()->json('foff', 422);
        }
        $banned_user = Comment::find($request->id);
        
        if (!$banned_user) return response()->json('mo', 404);

        $banned_user = $banned_user->user->id;

        if ($banned_user) {
            $not_banned = Ban::where('user_id', $banned_user)->first();

            if (!$not_banned) {
                $ban = Ban::firstOrCreate([
                    'user_id' => $banned_user,
                    'by_user_id' => $user_id
                ]);
                return response()->json('ye', 200);
            } else {
                return response()->json('banned already', 404); 
            }
        } else {
            return response()->json('noff', 404);
        }
    }
}