<?php

use App\Sex;
use Illuminate\Database\Seeder;

class SexSeeder extends Seeder
{
    public function run()
    {
        Sex::insert([
            [ 'sex' => 'male'],
            [ 'sex' => 'female']
        ]);
    }
}
