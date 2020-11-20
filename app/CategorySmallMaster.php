<?php

namespace App;

use \GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class CategorySmallMaster
{
    private $restaurantService;

    //テーブルにデータを追加
    public function insertTable()
    {
        DB::transaction(function () {
            $restaurantService = $this->resolveRestaurantService();
            $category_data = $restaurantService->getCategoryMaster();
            $category_smalls = $category_data['category_s'];
            foreach ($category_smalls as $category_small) {
                $insert_data = [];
                $insert_data['category_s_code'] = $category_small['category_s_code'];
                $insert_data['category_s_name'] = $category_small['category_s_name'];
                
                DB::table('category_small_master')->insert($insert_data);
            }
        });
    }

    public function truncateTable()
    {
        DB::table('category_small_master')->truncate();
    }

    public function resolveRestaurantService()
    {
        return resolve('App\Services\GurunaviApiService');
    }
}
