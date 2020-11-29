<?php

namespace App\Http\Controllers;

use App\Follow;
use App\Http\Resources\Follow as FollowResource;
use Auth;
use App\Http\Requests\StoreFollow;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @OA\Get(
     *  path="/api/follower",
     *  summary="フォロワー取得",
     *  description="ユーザのフォロワーを一覧取得する",
     *  operationId="getFollower",
     *  tags={"user"},
     *  security={{"bearer": {}}},
     *  @OA\Parameter(ref="#/components/parameters/user_get_follower"),
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
    public function followerIndex(Request $request)
    {
        $request->validate(['user_id' => 'required|exists:users']);

        $follows = Follow::where('follow_user_id', $request->user_id)
            ->with([
                'follower.followers' => function ($query) {
                    $query->where('user_id', Auth::id());
                },
                'follower.followings' => function ($query) {
                    $query->where('follow_user_id', Auth::id());
                }
            ])->paginate(20);

        return FollowResource::collection($follows);
    }

    /**
     * @OA\Get(
     *  path="/api/following",
     *  summary="フォロー中取得",
     *  description="ユーザがフォロー中のユーザを一覧取得する",
     *  operationId="getFollowing",
     *  tags={"user"},
     *  security={{"bearer": {}}},
     *  @OA\Parameter(ref="#/components/parameters/user_get_following"),
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
    public function followingIndex(Request $request)
    {
        $request->validate(['user_id' => 'required|exists:users']);

        $follows = Follow::where('user_id', $request->user_id)
            ->with([
                'target_user.followers' => function ($query) {
                    $query->where('user_id', Auth::id());
                },
                'target_user.followings' => function ($query) {
                    $query->where('follow_user_id', Auth::id());
                },
            ])->paginate(20);

        return FollowResource::collection($follows);
    }

    /**
     * @OA\Post(
     *  path="/api/follow",
     *  summary="フォロー",
     *  description="ユーザをフォローする",
     *  operationId="store_follow",
     *  tags={"user"},
     *  security={{"bearer": {}}},
     *  @OA\RequestBody(ref="#/components/requestBodies/user_store_follow"),
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
     *      description="フォロー情報が登録された",
     *      @OA\MediaType(mediaType="application/json")
     *  ),
     * )
     */
    public function store(StoreFollow $request)
    {
        Follow::create([
            'user_id' => Auth::id(),
            'follow_user_id' => $request->user_id,
            'subscription_flag' => $request->subscription_flag ?? false
        ]);

        return response()->json(['message' => 'Created'], 201);
    }

    /**
     * @OA\Delete(
     *  path="/api/follow",
     *  summary="フォロー解除",
     *  description="フォローを解除する",
     *  operationId="destroy_follow",
     *  tags={"user"},
     *  security={{"bearer": {}}},
     *  @OA\Parameter(ref="#/components/parameters/user_destroy_follow"),
     *  @OA\Response(
     *      response=401,
     *      description="認証されていない",
     *  ),
     *  @OA\Response(
     *      response=422,
     *      description="クエリパラメタに誤りがある",
     *  ),
     *  @OA\Response(
     *      response=201,
     *      description="フォロー情報が削除された",
     *      @OA\MediaType(mediaType="application/json")
     *  ),
     * )
     */
    public function destroy(Request $request)
    {
        $request->validate(['user_id' => 'required|exists:users']);

        $follow = Auth::user()->followings()
            ->where('follow_user_id', $request->user_id)->first();

        abort_unless($follow, 422, 'current user is not following given user');

        $follow->delete();

        return response()->json('', 204);
    }
}
