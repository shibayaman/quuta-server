<?php

namespace App\Http\Controllers;

use App\Image;
use App\User;
use App\Http\Requests\StoreImage;
use Illuminate\Http\Request;

class PostController extends Controller
{
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
}
