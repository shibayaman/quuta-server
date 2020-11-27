<?php

namespace App\Http\Controllers;

use App\Http\Resources\User as UserResource;
use App\User;
use Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/user/{id}",
     *  summary="ユーザ情報取得",
     *  description="ユーザの登録情報を取得する",
     *  operationId="getUserInfo",
     *  tags={"user"},
     *  security={{"bearer": {}}},
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      description="取得したいユーザのID",
     *  ),
     *  @OA\Response(
     *      response=404,
     *      description="指定されたユーザが存在しない",
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(mediaType="application/json")
     *  ),
     * )
     */
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
