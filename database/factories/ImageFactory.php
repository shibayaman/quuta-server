<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Image;
use Faker\Generator as Faker;

$factory->define(Image::class, function (Faker $faker) {
    return [
        'image_url' => 'http://placecorgi.com/100/100',
        'dish_name' => $faker->word
    ];
});
