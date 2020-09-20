<?php

//ぐるなびからjsonを取得して検索、処理するファイル

namespace App\Services;

use App\Exceptions\RestaurantApiException;
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
        $res = $client->request("GET", $requestUrl, [
            'query' => $params,
            'http_errors' => false
        ]);

        $this->validateResponse($res);
        return json_decode($res->getBody(), true);
    }

    private function validateResponse($res)
    {
        $status = $res->getStatusCode();

        if ($status / 100 !== 2) {
            $message = json_decode($res->getBody(), false)->error[0]->message;
            throw new RestaurantApiException($message);
        }
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
