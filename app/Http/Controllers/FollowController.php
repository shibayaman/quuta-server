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

    public function store(StoreFollow $request)
    {
        Follow::create([
            'user_id' => Auth::id(),
            'follow_user_id' => $request->user_id,
            'subscription_flag' => $request->subscription_flag ?? false
        ]);

        return response()->json(['message' => 'Created'], 201);
    }

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
