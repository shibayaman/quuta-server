<?php

namespace App\Http\Controllers\Auth;

use App\Http\Resources\User as UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['login']);
    }

    /**
     * @OA\Post(
     *  path="/api/auth/login",
     *  summary="ログイン",
     *  description="メールアドレスとパスワードでログイン",
     *  operationId="authLogin",
     *  tags={"auth"},
     *  @OA\RequestBody(
     *      required=true,
     *      description="認証情報をいれる",
     *      @OA\JsonContent(
     *          required={"email","password"},
     *          @OA\Property(property="email", type="string", example="quuta@quuta.com"),
     *          @OA\Property(property="password", type="string", format="password", example="quuta12345"),
     *      ),
     *  ),
     *  @OA\Response(
     *      response=401,
     *      description="認証情報が間違っている",
     *      @OA\JsonContent(
     *          @OA\Property(property="error", type="string", example="Unauthorized"),
     *      ),
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="Success",
     *  ),
     * )
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function user()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => new UserResource(auth()->user())
        ]);
    }
}
