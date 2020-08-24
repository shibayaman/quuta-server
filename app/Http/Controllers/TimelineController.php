<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetTimeline;
use App\Http\Resources\Timeline as TimelineResource;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimelineController extends Controller
{
    public function homeTimeline(GetTimeline $request)
    {
        [$sinceId, $untilId, $count] = $this->destructLimitOptions($request);

        //temporarily using first user
        $user = \App\User::first();

        $posts = $user->homeTimeline($sinceId, $untilId, $count);
        return TimelineResource::collection($posts);
    }

    public function userTimeline(GetTimeLine $request)
    {
        [$sinceId, $untilId, $count] = $this->destructLimitOptions($request);
        
        //temporarily using first user
        $user = \App\User::first();
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
