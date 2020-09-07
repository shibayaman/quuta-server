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

    public function storePost(StorePost $request)
    {
        $attributes = $request->validated();
        unset($attributes['image_ids']);

        $attributes['user_id'] = Auth::id();

        $images = $this->verifyUserCanLinkImages($request->image_ids);

        $restaurant = $this->restaurantApiService->getRestaurant($request->restaurant_id);
        $attributes['restaurant_name'] = $restaurant['name'];
        $attributes['restaurant_address'] = $restaurant['address'];

        return Post::createAndLinkImage($attributes, $images);
    }

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
