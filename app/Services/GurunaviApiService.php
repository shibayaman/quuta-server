<?php

//ぐるなびからjsonを取得して検索、処理するファイル

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;

class GurunaviApiService
{
    public function searchRestaurants($params)
    {
        $restSearchUrl = config('gurunavi.restSearchUrl');
        return $this->getApiData($restSearchUrl, $params);
    }

    public function getRestaurant($id)
    {
        $response = $this->searchRestaurants([
            'id' => $id
        ]);
        return $response['rest'][0];
    }

    public function getAreaMaster()
    {
        $gAreaSmallSearchUrl = config('gurunavi.gAreaSmallSearchUrl');

        $params = [
            'lang' => 'ja',
        ];

        return $this->getApiData($gAreaSmallSearchUrl, $params);
    }

    private function getApiData($path, $params = [])
    {
        $baseUrl = config('gurunavi.baseUrl');
        $apiKey = config('gurunavi.key');
        $params['keyid'] = $apiKey;
        $requestUrl = $baseUrl . $path;
        $client = new Client();
        $json = $client->request("GET", $requestUrl, ['query' => $params]);
        return json_decode($json->getBody(), true);
    }

    private function resolveQueryString($params)
    {
        $queryString = '';

        foreach ($params as $key => $value) {
            $queryString .= '&' . $key . '=' . $value;
        }

        $queryString = substr($queryString, 1);

        return $queryString;
    }
}
