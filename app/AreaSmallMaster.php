<?php

namespace App;

use \GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class AreaSmallMaster
{
    private $restaurantService;

    //テーブルにデータを追加
    public function insertTable()
    {
        DB::transaction(function () {
            $restaurantService = $this->resolveRestaurantService();
            $area_data = $restaurantService->getAreaMaster();
            $area_smalls = $area_data['garea_small'];
            foreach ($area_smalls as $area_small) {
                $insert_data = [];
                $insert_data['areacode_s'] = $area_small['areacode_s'];
                $insert_data['areaname_s'] = $area_small['areaname_s'];
                $insert_data['areacode_m'] = $area_small['garea_middle']['areacode_m'];
                $insert_data['areaname_m'] = $area_small['garea_middle']['areaname_m'];
                $insert_data['areacode_l'] = $area_small['garea_large']['areacode_l'];
                $insert_data['areaname_l'] = $area_small['garea_large']['areaname_l'];
                $insert_data['pref_code'] = $area_small['pref']['pref_code'];
                $insert_data['pref_name'] = $area_small['pref']['pref_name'];

                DB::table('area_small_master')->insert($insert_data);
            }
        });
    }

    public function truncateTable()
    {
        DB::table('area_small_master')->truncate();
    }

    public function resolveRestaurantService()
    {
        return resolve('App\Services\GurunaviApiService');
    }
}
