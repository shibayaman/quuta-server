<?php

namespace App;

use \GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class CategoryLargeMaster
{
    private $restaurantService;

    //テーブルにデータを追加
    public function insertTable()
    {
        DB::transaction(function () {
            $restaurantService = $this->resolveRestaurantService();
            $category_data = $restaurantService->getCategoryMaster();
            $category_larges = $category_data['category_l'];
            foreach ($category_larges as $category_large) {
                $insert_data = [];
                $insert_data['category_l_code'] = $category_large['category_l_code'];
                $insert_data['category_l_name'] = $category_large['category_l_name'];
                
                DB::table('category_large_master')->insert($insert_data);
            }
        });
    }

    public function truncateTable()
    {
        DB::table('category_large_master')->truncate();
    }

    public function resolveRestaurantService()
    {
        return resolve('App\Services\GurunaviApiService');
    }
}
