<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Post;
use App\Comment;
use Illuminate\Support\Str;

class AjaxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getComments(Request $request)
    {
        // $post = new Post::where
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
