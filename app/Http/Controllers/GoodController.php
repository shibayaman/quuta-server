<?php

namespace App\Http\Controllers;

use App\Good;
use App\Http\Requests\StoreGood;
use Auth;
use DB;
use Illuminate\Http\Request;

class GoodController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @OA\Post(
     *  path="/api/good",
     *  summary="いいね",
     *  description="投稿に対していいねをつける",
     *  operationId="storeGood",
     *  tags={"good"},
     *  security={{"bearer": {}}},
     *  @OA\RequestBody(ref="#/components/requestBodies/good_store"),
     *  @OA\Response(
     *      response=401,
     *      description="認証されていない",
     *  ),
     *  @OA\Response(
     *      response=422,
     *      description="リクエストボディに誤りがある",
     *  ),
     *  @OA\Response(
     *      response=204,
     *      description="いいねが解除された",
     *      @OA\MediaType(mediaType="application/json")
     *  ),
     * )
     */
    public function store(StoreGood $request)
    {
        DB::transaction(function () {
            Good::create([
                'post_id' => request('post_id'),
                'user_id' => Auth::id()
            ]);
        });

        return response()->json(['message' => 'Created'], 201);
    }

    /**
     * @OA\Delete(
     *  path="/api/good",
     *  summary="いいね解除",
     *  description="投稿に対していいねを解除する",
     *  operationId="destroyGood",
     *  tags={"good"},
     *  security={{"bearer": {}}},
     *  @OA\Parameter(ref="#/components/parameters/good_destroy_post_id"),
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
     *      description="いいねが登録された",
     *      @OA\MediaType(mediaType="application/json")
     *  ),
     * )
     */
    public function destroy(Request $request)
    {
        $request->validate(['post_id' => 'required|integer|exists:posts']);

        $good = Good::where([
            'post_id' => $request->post_id,
            'user_id' => Auth::id()
        ])->first();

        abort_unless($good, 422, 'good does not exists for givven post_id');

        DB::transaction(function () use ($good) {
            $good->delete();
        });

        return response()->json('', 204);
    }
}
