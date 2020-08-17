<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParentComment;
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
}
