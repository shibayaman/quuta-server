<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChildComment;
use App\Http\Requests\StoreParentComment;
use App\Http\Resources\Comment as CommentResource;
use App\Http\Resources\Thread as ThreadResource;
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

    /**
     * @OA\Get(
     *  path="/api/thread",
     *  summary="スレッド取得",
     *  description="投稿についているスレッドをスレッドの一番最初のコメントを含めて一覧で取得する",
     *  operationId="getParentComment",
     *  tags={"comment"},
     *  security={{"bearer": {}}},
     *  @OA\Parameter(ref="#/components/parameters/comment_get_parent_post_id"),
     *  @OA\Response(
     *      response=401,
     *      description="認証されていない",
     *  ),
     *  @OA\Response(
     *      response=422,
     *      description="クエリパラメタに誤りがある",
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(mediaType="application/json")
     *  ),
     * )
     */
    public function index(Request $request)
    {
        $request->validate(['post_id' => 'required|integer']);

        $threads = Thread::where('post_id', $request->post_id)
            ->withChildCount()
            ->with('parent_comment.user')
            ->orderBy('thread_id', 'desc')
            ->paginate(20);

        return ThreadResource::collection($threads);
    }

    /**
     * @OA\Get(
     *  path="/api/comment",
     *  summary="コメント取得",
     *  description="スレッドについている子コメントを一覧取得する",
     *  operationId="getChildComment",
     *  tags={"comment"},
     *  security={{"bearer": {}}},
     *  @OA\Parameter(ref="#/components/parameters/comment_get_child_thread_id"),
     *  @OA\Response(
     *      response=401,
     *      description="認証されていない",
     *  ),
     *  @OA\Response(
     *      response=422,
     *      description="クエリパラメタに誤りがある",
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(mediaType="application/json")
     *  ),
     * )
     */
    public function show(Request $request)
    {
        $request->validate(['thread_id' => 'required|integer']);

        $thread = Thread::find($request->thread_id);
        abort_unless($thread, 422, 'thread_id is invalid');

        $comments = Comment::with('user')
            ->where('thread_id', $request->thread_id)
            ->where('comment_id', '>', $thread->comment_id)
            ->orderBy('thread_id')
            ->paginate(20);
        return CommentResource::collection($comments);
    }

    /**
     * @OA\Post(
     *  path="/api/comment/parent",
     *  summary="スレッド作成",
     *  description="新しいスレッドを作成しコメントを投稿する",
     *  operationId="storeParentComment",
     *  tags={"comment"},
     *  security={{"bearer": {}}},
     *  @OA\RequestBody(ref="#/components/requestBodies/comment_store_parent"),
     *  @OA\Response(
     *      response=401,
     *      description="認証されていない",
     *  ),
     *  @OA\Response(
     *      response=422,
     *      description="リクエストボディに誤りがある",
     *  ),
     *  @OA\Response(
     *      response=201,
     *      description="新しいスレッドでコメントが作成された",
     *      @OA\MediaType(mediaType="application/json")
     *  ),
     * )
     */
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

    /**
     * @OA\Post(
     *  path="/api/comment/child",
     *  summary="コメント投稿",
     *  description="新しいコメントを投稿する",
     *  operationId="storeChildComment",
     *  tags={"comment"},
     *  security={{"bearer": {}}},
     *  @OA\RequestBody(ref="#/components/requestBodies/comment_store_child"),
     *  @OA\Response(
     *      response=401,
     *      description="認証されていない",
     *  ),
     *  @OA\Response(
     *      response=422,
     *      description="リクエストボディに誤りがある",
     *  ),
     *  @OA\Response(
     *      response=201,
     *      description="新しいコメントが作成された",
     *      @OA\MediaType(mediaType="application/json")
     *  ),
     * )
     */
    public function storeChildComment(storeChildComment $request)
    {
        $attributes = array_merge($request->validated(), ['user_id' => Auth::id()]);
        return Comment::create($attributes);
    }
    
    /**
     * @OA\Delete(
     *  path="/api/comment/{id}",
     *  summary="コメント投稿",
     *  description="コメントを削除する。スレッドの一番上のコメントが削除された場合、そのスレッドについているコメントは全て削除される",
     *  operationId="destroyComment",
     *  tags={"comment"},
     *  security={{"bearer": {}}},
     *  @OA\Parameter(ref="#/components/parameters/comment_destroy_id"),
     *  @OA\Response(
     *      response=401,
     *      description="認証されていない",
     *  ),
     *  @OA\Response(
     *      response=403,
     *      description="指定したコメントを削除する権限がない",
     *  ),
     *  @OA\Response(
     *      response=404,
     *      description="指定されたコメントが存在しない",
     *  ),
     *  @OA\Response(
     *      response=204,
     *      description="コメントが削除された",
     *      @OA\MediaType(mediaType="application/json")
     *  ),
     * )
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete-comment', $comment);

        $thread = Thread::where('comment_id', $comment->comment_id)->first();

        if ($thread) {
            $thread->delete();
        } else {
            $comment->delete();
        }

        return response()->json('', 204);
    }
}
