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
        $since_id = $request->input('since_id');
        $until_id = $request->input('until_id');
        $count = $request->input('count', 10);
        $count = min($count, 50);

        //temporarily using first user
        $user = \App\User::first();

        $postQuery = DB::table('posts')
            ->join('follows', 'posts.user_id', 'follows.follow_user_id')
            ->join('users', 'follows.follow_user_id', 'users.user_id')
            ->where('follows.user_id', $user->user_id)
            ->limit($count)
            ->orderby("posts.post_id");
            
        $posts = $this->addPostIdConstraint($postQuery, $since_id, $until_id, $count)
            ->get();

        return new TimelineCollection($posts);
    }

    private function addPostIdConstraint($query, $since_id, $until_id, $count)
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

        return $query;
    }
}
