<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ToGo;
use Faker\Generator as Faker;

$factory->define(ToGo::class, function (Faker $faker) {
    return [
        'restaurant_id' => 'kd1j800',
        'latitude' => 1.1,
        'longitude' => 2.2,
    ];
});
