<?php

namespace App\Http\Controllers;

use App\Image;
use App\Post;
use App\User;
use App\Http\Requests\StorePost;
use App\Http\Requests\StoreImage;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function storePost(StorePost $request)
    {
        $attributes = $request->validated();
        unset($attributes['image_ids']);

        $attributes['user_id'] = Auth::id();

        //restaurant_name & restaurant_addressをぐるなびAPIから取ってくる
        $attributes['restaurant_name'] = '鳥貴族';
        $attributes['restaurant_address'] = '〒589-0011 大阪府';

        $images = $this->verifyUserCanLinkImages($request->image_ids);

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
