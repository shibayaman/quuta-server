<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Post;
use App\User;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'content' => $faker->sentence(10),
        'like_flag' => $faker->boolean,
        'good_count' => $faker->numberBetween(0, 1000),
        'comment_count' => $faker->numberBetween(0, 1000),
        'restaurant_id' => 'kd1j800',
        'restaurant_name' => '焼鳥屋 鳥貴族 金剛店',
        'restaurant_address' => '〒589-0011 大阪府大阪狭山市半田1-224-178 2F'
    ];
});
