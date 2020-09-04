<?php

use App\Image;
use App\Post;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    public function run()
    {
        $posts = Post::all();
        $posts->each(function ($post) {
            $post->images()->createMany(
                factory(Image::class, 2)->make(['user_id' => $post->user_id])->toArray()
            );
        });
    }
}
