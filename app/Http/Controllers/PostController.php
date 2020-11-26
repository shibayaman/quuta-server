<?php

namespace App\Http\Controllers;

use App\Image;
use App\Post;
use App\Services\GurunaviApiService;
use App\User;
use App\Http\Requests\StorePost;
use App\Http\Requests\StoreImage;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    private $restaurantApiService;
    public function __construct(GurunaviApiService $restaurantApiService)
    {
        $this->middleware('auth');
        $this->restaurantApiService = $restaurantApiService;
    }

    /**
     * @OA\Post(
     *  path="/api/post",
     *  summary="投稿",
     *  description="新しい投稿をする",
     *  operationId="storeNewPost",
     *  tags={"post"},
     *  security={{"bearer": {}}},
     *  @OA\RequestBody(ref="#/components/requestBodies/post_store_request_body"),
     *  @OA\Response(
     *      response=401,
     *      description="認証されていない",
     *  ),
     *  @OA\Response(
     *      response=422,
     *      description="リクエストボディに誤りがある",
     *  ),
     *  @OA\Response(
     *      response=201,
     *      description="投稿が作成された",
     *      @OA\MediaType(mediaType="application/json")
     *  ),
     * )
     */
    public function storePost(StorePost $request)
    {
        $attributes = $request->validated();
        unset($attributes['image_ids']);

        $attributes['user_id'] = Auth::id();

        $images = $this->verifyUserCanLinkImages($request->image_ids);

        $restaurant = $this->restaurantApiService->getRestaurant($request->restaurant_id);
        $attributes['restaurant_name'] = $restaurant['name'];
        $attributes['restaurant_address'] = $restaurant['address'];

        return DB::transaction(function () use ($attributes, $images) {
            return Post::createAndLinkImage($attributes, $images);
        });
    }

    /**
     * @OA\Post(
     *  path="/api/image",
     *  summary="投稿画像アップロード",
     *  description="投稿時に使う画像をアップロードする。ここで返却される画像idを使って投稿する。",
     *  operationId="storeNewImage",
     *  tags={"post"},
     *  security={{"bearer": {}}},
     *  @OA\RequestBody(ref="#/components/requestBodies/post_store_image_request_body"),
     *  @OA\Response(
     *      response=401,
     *      description="認証されていない",
     *  ),
     *  @OA\Response(
     *      response=422,
     *      description="リクエストボディに誤りがある",
     *  ),
     *  @OA\Response(
     *      response=201,
     *      description="投稿が作成された",
     *      @OA\MediaType(mediaType="application/json")
     *  ),
     * )
     */
    public function storeImage(StoreImage $request)
    {
        $path = $request->image->store('public');

        $image = Image::create([
            'user_id' => Auth::id(),
            'image_url' => basename($path),
            'dish_name' => $request->dish_name ?? ''
        ]);

        return response()->json(['image_id' => $image->image_id], 201);
    }

    /**
     * @OA\Delete(
     *  path="/api/post/{id}",
     *  summary="投稿削除",
     *  description="投稿を削除する",
     *  operationId="destoryPost",
     *  tags={"post"},
     *  security={{"bearer": {}}},
     *  @OA\Parameter(ref="#/components/parameters/post_destory_post_id"),
     *  @OA\Response(
     *      response=401,
     *      description="認証されていない",
     *  ),
     *  @OA\Response(
     *      response=403,
     *      description="ログインユーザに削除できない投稿が指定された",
     *  ),
     *  @OA\Response(
     *      response=422,
     *      description="リクエストボディに誤りがある",
     *  ),
     *  @OA\Response(
     *      response=204,
     *      description="投稿が削除された",
     *      @OA\MediaType(mediaType="application/json"),
     *  ),
     * )
     */
    public function destory(Post $post)
    {
        $this->authorize('delete-post', $post);
        $post->delete();

        return response()->noContent();
    }

    private function verifyUserCanLinkImages(array $image_ids)
    {
        $images = Image::find($image_ids);
        abort_if($images->count() !== count($image_ids), 422, 'image_ids are invalid');

        $images->each(function ($image) {
            $this->authorize('link_image', $image);
        });
        return $images;
    }
}
