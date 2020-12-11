<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetTimeline;
use App\Http\Requests\GetRestaurantTimeline;
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

    public function homeTimeline(GetTimeline $request)
    {
        [$sinceId, $untilId, $count] = $this->destructLimitOptions($request);

        $posts = Auth::user()->homeTimeline($sinceId, $untilId, $count);
        return TimelineResource::collection($posts);
    }

    public function userTimeline(GetTimeLine $request)
    {
        [$sinceId, $untilId, $count] = $this->destructLimitOptions($request);
        
        $user = Auth::user();
        $targetUserId = $request->input('user_id') ?? $user->user_id;

        $posts = $user->userTimeline($targetUserId, $sinceId, $untilId, $count);
        return TimelineResource::collection($posts);
    }

    public function restaurantTimeline(GetRestaurantTimeline $request)
    {
        [$sinceId, $untilId, $count] = $this->destructLimitOptions($request);

        $posts = Auth::user()->restaurantTimeline($request->restaurant_id, $sinceId, $untilId, $count);
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
