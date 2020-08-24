<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChildComment;
use App\Http\Requests\StoreParentComment;
use App\Comment;
use App\Thread;
use Auth;
use DB;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $request->validate(['post_id' => 'required|integer']);

        $commentIds = Thread::where('post_id', $request->post_id)->pluck('comment_id');

        return Comment::with('user')->whereIn('comment_id', $commentIds)->paginate(20);
    }

    public function show(Request $request)
    {
        $request->validate(['thread_id' => 'required|integer']);

        return Comment::with('user')->where('thread_id', $request->thread_id)->paginate(20);
    }

    public function storeParentComment(storeParentComment $request)
    {
        return DB::transaction(function () {
            $thread = Thread::create(['post_id' => request('post_id')]);

            return $thread->createParentComment([
                'comment' => request('comment'),
                'user_id' => Auth::id()
            ]);
        });
    }

    public function storeChildComment(storeChildComment $request)
    {
        $attributes = array_merge($request->validated(), ['user_id' => Auth::id()]);
        return Comment::create($attributes);
    }
    
    public function delete(Comment $comment)
    {
        $this->authorize('delete-comment', $comment);

        $thread = Thread::where('comment_id', $comment->comment_id)->first();

        if ($thread) {
            $thread->delete();
        } else {
            $comment->delete();
        }

        return response()->json(['message' => 'OK'], 200);
    }
}
