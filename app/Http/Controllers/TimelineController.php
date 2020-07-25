<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetTimeline;
use App\Http\Resources\TimelineCollection;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimelineController extends Controller
{
    public function homeTimeline(GetTimeline $request)
    {
        [$since_id, $until_id, $count] = $this->destructLimitOptions($request);

        //temporarily using first user
        $user = \App\User::first();

        $follow_user_ids = $user->following()->pluck('follow_user_id');
        $postQuery = Post::wherein('user_id', $follow_user_ids)
            ->with(['good' => function ($query) use ($user) {
                $query->where('user_id', $user->user_id);
            }]);
        
        $posts = $this->limitPosts($postQuery, $since_id, $until_id, $count)
            ->get();

        return new TimelineCollection($posts);
    }

    public function userTimeline(GetTimeLine $request)
    {
        [$since_id, $until_id, $count] = $this->destructLimitOptions($request);
        
        //temporarily using first user
        $user = \App\User::first();
        $target_user_id = $request->input('user_id', $user->user_id);

        $postQuery = Post::where('user_id', $target_user_id)
            ->with(['good' => function ($query) use ($user) {
                $query->where('user_id', $user->user_id);
            }]);

        $posts = $this->limitPosts($postQuery, $since_id, $until_id, $count)
            ->get();

        return new TimelineCollection($posts);
    }

    private function destructLimitOptions()
    {
        $request = request();
        $since_id = $request->input('since_id');
        $until_id = $request->input('until_id');
        $count = $request->input('count', 10);
        $count = min($count, 50);

        return [$since_id, $until_id, $count];
    }

    private function limitPosts($query, $since_id, $until_id, $count)
    {
        if ($since_id) {
            $query->where('posts.post_id', '>', $since_id);
        }

        if ($until_id) {
            $query->where('posts.post_id', '<=', $until_id);
        }

        if ($count) {
            $query->limit($count);
        }

        $query->orderby("posts.post_id");

        return $query;
    }
}
