<?php

namespace App\Http\Controllers;

use App\Follow;
use Auth;
use App\Http\Requests\StoreFollow;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
