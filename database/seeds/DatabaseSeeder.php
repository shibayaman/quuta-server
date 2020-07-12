<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(SexSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PostSeeder::class);
        $this->call(ImageSeeder::class);
        $this->call(FollowSeeder::class);
        $this->call(GoodSeeder::class);
        $this->call(ThreadSeeder::class);
        $this->call(CommentSeeder::class);
    }
}
