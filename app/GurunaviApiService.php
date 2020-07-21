<?php

//ぐるなびからjsonを取得して検索、処理するファイル

namespace App;

use Illuminate\Database\Eloquent\Model;
use \GuzzleHttp\Client;

class GurunaviApiService
{
    private $apiBaseUrl = 'https://api.gnavi.co.jp/RestSearchAPI/v3/';
    
    public function Gurunabvi_Search()
    {
        $apiKey = env('GURUNAVI_API_KEY', '');
        $requestUrl = $this->apiBaseUrl . '?keyid=' . $apiKey;

        //店舗の情報を取得
        $id = "&id=kacx601";

        //ぐるなびにパラメータ追加
        $this->api_url .= $id;

        //ぐるなびにリクエストを送信
        $client = new Client();
        $response = $client->request("GET", $requestUrl);
        echo $response->getBody();
    }
}
