<?php

namespace App\Http\Controllers;

use App\Http\Resources\User as UserResource;
use App\User;
use Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(User $user)
    {
        if (Auth::check()) {
            $user->load([
                'followers' => function ($query) {
                    $query->where('user_id', Auth::id());
                },
                'followings' => function ($query) {
                    $query->where('follow_user_id', Auth::id());
                }
            ]);
        }

        return new UserResource($user);
    }
}
