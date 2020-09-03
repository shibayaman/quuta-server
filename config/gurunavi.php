<?php

return [

    /*
    GurunaviのApikey環境設定の取得
    */
    'key' => env('GURUNAVI_API_KEY', 'no'),

    'baseUrl' => 'https://api.gnavi.co.jp',
    //レストラン検索Url
    'restSearchUrl' => '/RestSearchAPI/v3/',
    //エリアSマスタ検索Url
    'gAreaSmallSearchUrl' => '/master/GAreaSmallSearchAPI/v3/',
    //大業態マスタ取得Url
    'categoryLargeSearchUrl' => '/master/CategoryLargeSearchAPI/v3/',
    //小業態マスタ取得Url
    'categorySmallSearchUrl'=> '/master/CategorySmallSearchAPI/v3/',

];
