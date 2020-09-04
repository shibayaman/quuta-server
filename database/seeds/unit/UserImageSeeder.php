<?php

use App\User;
use App\Image;
use Illuminate\Database\Seeder;

class UserImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 2)->create()->each(function ($user) {
            $user->images()->saveMany(factory(Image::class, 2)->make());
        });
    }
}
