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

    public function followedIndex(Request $request)
    {
        $request->validate(['user_id' => 'required|exists:users']);

        $follows = Follow::with('user')->where('follow_user_id', $request->user_id)->paginate(20);
        return FollowResource::collection($follows);
    }

    public function followingIndex(Request $request)
    {
        $request->validate(['user_id' => 'required|exists:users']);

        $follows = Follow::with('follow_user')->where('user_id', $request->user_id)->paginate(20);
        return FollowResource::collection($follows);
    }

    public function store(StoreFollow $request)
    {
        Follow::create([
            'user_id' => Auth::id(),
            'follow_user_id' => $request->user_id,
            'subscription_flag' => $request->subscription_flag ?? false
        ]);

        return response()->json(['message' => 'OK'], 200);
    }
}
