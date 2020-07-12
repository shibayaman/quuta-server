<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Sex;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

$factory->define(User::class, function (Faker $faker) {
    $sex = Sex::all();

    return [
        'user_id' => Str::random(7),
        'username' => $faker->username,
        'email' => $faker->unique()->email,
        'password' => Hash::make(Str::random(10)),
        'password_updated_at' => null,
        'private_flag' => false,
        'birthday_date' => $faker->date('Y-m-d', 'now'),
        'sex_id' => $sex->isNotEmpty() ? $sex->random()->sex_id : null,
        'icon_url' => 'http://placecorgi.com/100/100',
        'password_reset_token' => null,
        'token_expires_at' => null,
        'description' => $faker->sentence(3),
    ];
});
