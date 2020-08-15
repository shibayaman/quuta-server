<?php

namespace App\Http\Controllers;

use App\Image;
use App\Post;
use App\User;
use App\Http\Requests\StorePost;
use App\Http\Requests\StoreImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function storePost(StorePost $request)
    {
        $attributes = $request->validated();
        unset($attributes['image_ids']);

        //認証済みユーザーをとる
        $attributes['user_id'] = User::first()->user_id;

        //restaurant_name & restaurant_addressをぐるなびAPIから取ってくる
        $attributes['restaurant_name'] = '鳥貴族';
        $attributes['restaurant_address'] = '〒589-0011 大阪府';

        $images = $this->verifyUserCanLinkImages($request->image_ids);

        return Post::createAndLinkImage($attributes, $images);
    }

    public function storeImage(StoreImage $request)
    {
        //認証済みユーザーをとる
        $user = User::first();

        $path = $request->image->store('public');

        $image = Image::create([
            'user_id' => $user->user_id,
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
            //認証済みユーザーで認可する
            $this->authorizeForUser(User::first(), 'link_image', $image);
        });
        return $images;
    }
}
