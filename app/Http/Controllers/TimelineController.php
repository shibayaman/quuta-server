<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetTimeline;
use App\Http\Resources\Timeline as TimelineResource;
use App\Post;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimelineController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @OA\Get(
     *  path="/api/home_timeline",
     *  summary="ホームタイムライン取得",
     *  description="ログインユーザ用のタイムラインを取得する",
     *  operationId="getHomeTimeline",
     *  tags={"post"},
     *  security={{"bearer": {}}},
     *  @OA\Parameter(ref="#/components/parameters/timeline_get_since_id"),
     *  @OA\Parameter(ref="#/components/parameters/timeline_get_until_id"),
     *  @OA\Parameter(ref="#/components/parameters/timeline_get_count"),
     *  @OA\Response(
     *      response=401,
     *      description="認証されていない",
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(mediaType="application/json")
     *  ),
     * )
     */
    public function homeTimeline(GetTimeline $request)
    {
        [$sinceId, $untilId, $count] = $this->destructLimitOptions($request);

        $posts = Auth::user()->homeTimeline($sinceId, $untilId, $count);
        return TimelineResource::collection($posts);
    }

    /**
     * @OA\Get(
     *  path="/api/user_timeline",
     *  summary="ユーザタイムライン取得",
     *  description="特定のユーザごとの投稿を取得する",
     *  operationId="getUserTimeline",
     *  tags={"post"},
     *  security={{"bearer": {}}},
     *  @OA\Parameter(ref="#/components/parameters/timeline_get_user_id"),
     *  @OA\Parameter(ref="#/components/parameters/timeline_get_since_id"),
     *  @OA\Parameter(ref="#/components/parameters/timeline_get_until_id"),
     *  @OA\Parameter(ref="#/components/parameters/timeline_get_count"),
     *  @OA\Response(
     *      response=401,
     *      description="認証されていない",
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(mediaType="application/json")
     *  ),
     * )
     */
    public function userTimeline(GetTimeLine $request)
    {
        [$sinceId, $untilId, $count] = $this->destructLimitOptions($request);
        
        $user = Auth::user();
        $targetUserId = $request->input('user_id') ?? $user->user_id;

        $posts = $user->userTimeline($targetUserId, $sinceId, $untilId, $count);
        return TimelineResource::collection($posts);
    }

    private function destructLimitOptions()
    {
        $request = request();
        $sinceId = $request->input('since_id');
        $untilId = $request->input('until_id');
        $count = $request->input('count', 10);
        $count = min($count, 50);

        return [$sinceId, $untilId, $count];
    }
}
