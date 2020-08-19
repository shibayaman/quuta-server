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

    public function deleteParentComment(Comment $comment)
    {
        $this->authorize('delete-comment', $comment);

        $thread = Thread::where('comment_id', $comment->comment_id)->first();
        abort_unless($thread, 422, 'specified comment is not a parent comment');

        $thread->delete();
        return response()->json(['message' => 'OK'], 200);
    }

    public function storeChildComment(storeChildComment $request)
    {
        $attributes = array_merge($request->validated(), ['user_id' => Auth::id()]);
        return Comment::create($attributes);
    }
}
