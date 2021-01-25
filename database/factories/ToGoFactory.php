<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ToGo;
use Faker\Generator as Faker;
use Grimzy\LaravelMysqlSpatial\Types\Point;

$factory->define(ToGo::class, function (Faker $faker) {
    return [
        'restaurant_id' => 'kd1j800',
        'location' => new Point(1.1, 2.2)
    ];
});
