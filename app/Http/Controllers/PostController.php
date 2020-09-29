<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * @var
     */
    protected $user;

    /**
     * TaskController constructor.
     */
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }
    public function index()
    {
        $posts = $this->user->posts()->get()->toArray();
        for ($i=0;$i<count($posts);$i++){
            $posts[$i]['user_id'] = $this->user->user_id;
        }

        return $posts;
    }
    public function show($id)
    {
        $post = $this->user->posts()->where('post_id','=',$id)->first();

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, task with id ' . $id . ' cannot be found.'
            ], 400);
        }
        $post->user_id = $this->user->user_id;
        return $post;
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->description = $request->description;

        if ($this->user->posts()->save($post))
        {
            $post->user_id = $this->user->user_id;
            return response()->json([
                'success' => true,
                'post' => $post
            ]);
        }else
            return response()->json([
                'success' => false,
                'message' => 'Sorry, task could not be added.'
            ], 500);
    }
    public function update(Request $request, $id)
    {
        $post = $this->user->posts()->where('post_id','=',$id)->first();

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, task with id ' . $id . ' cannot be found.'
            ], 400);
        }

        $updated = $post->fill($request->all())->save();

        if ($updated) {
            $post->user_id = $this->user->user_id;
            return response()->json([
                'success' => true,
                'post' => $post
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, task could not be updated.'
            ], 500);
        }
    }
    public function destroy($id)
    {
        $post = $this->user->posts()->where('post_id','=',$id)->first();

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, task with id ' . $id . ' cannot be found.'
            ], 400);
        }

        if ($post->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Task could not be deleted.'
            ], 500);
        }
    }
}
