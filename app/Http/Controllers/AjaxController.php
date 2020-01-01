<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Post;
use App\Comment;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class AjaxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getComments(Request $request)
    {
        $post = Post::find($request->post_id);
        // $post = Post::find(1);
        $comments = $post->get_comments->where('sub_of', '0');

        $coms = [];
        $sub_coms = [];

        foreach ($comments as $key => $value) {
            $coms[$key] = [
                'id' => $value->id,
                'sub_of' => $value->sub_of,
                'author_name' => $value->user->name,
                'author_avatar' => $value->user->avatar,
                'message' => $value->comment,
                'likes' => $value->likes,
            ];

            // $subs_tmp = $value->sub_coms;
            $subs_tmp = Comment::find($value->id)->sub_coms;

            if ($subs_tmp->count() > 0) {
                $sub_coms[$key] = [];
                
                foreach ($subs_tmp as $key2 => $value2) {
                    $sub_coms[$key][] = [
                        'sub_of' => $value2->sub_of,
                        'author_name' => $value2->user->name,
                        'author_avatar' => $value2->user->avatar,
                        'message' => $value2->comment,
                        'likes' => $value2->likes,
                    ];
                }
            }
        }

        $response = array(
            'status' => 'success',
            'comments' => $coms,
            'sub_comments' => $sub_coms
        );

        return response()->json($response, 200);

    }

    public function comment(Request $request)
    {
        $request->merge(['message' => e($request->message)]);

        $msgs = [
            'message.required' => 'Musisz coś wpisać!',
            'message.max' => 'Dozwolone jest tylko 255 znaków!'
        ];

        $rules = ['message' => 'bail|required|max:455|min:2'];

        $validator = Validator::make($request->all(), $rules, $msgs);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ], 422);
        }

        $new_com =  $this->addComment($request);
        $is_author = false;
        
        if (Post::where('user_id', '=', Auth::user()->id)->exists()) {
            $is_author = true;
        }

        $response = array(
            'status' => 'success',
            'name' => Auth::user()->name,
            'message' => $request->message,
            'avatar' => Auth::user()->avatar,
            'is_author' => $is_author,
            'com_id' => $new_com->id
        );

        return response()->json($response, 200); 
        // return response()->json($response, 422); // error code 
    }

    public function addComment($req)
    {
        $post_id = (int)$req->post_id;
        $user_id = Auth::user()->id;
        $content = $req->message;
        
        return Comment::create([
            'user_id' => $user_id,
            'post_id' => $post_id,
            'comment' => $content
        ]);
    }

    public function like(Request $request)
    {

    }
}
