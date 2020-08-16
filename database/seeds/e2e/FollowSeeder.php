<?php

use App\Follow;
use App\User;
use Illuminate\Database\Seeder;

class FollowSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        $users->each(function ($user) use ($users) {
            $followings = $users->except([$user->user_id])->random(4);

            $followings->each(function ($following) use ($user) {
                $user->followings()->save(
                    factory(Follow::class)->make(
                        [
                            'follow_user_id' => $following->user_id
                        ]
                    )
                );
            });
        });
    }
}
