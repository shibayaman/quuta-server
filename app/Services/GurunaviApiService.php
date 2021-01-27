<?php

namespace App\Services;

use App\Exceptions\RestaurantApiException;
use GuzzleHttp\Client;
use function GuzzleHttp\Psr7\build_query;

class GurunaviApiService
{
    public function searchRestaurants($params)
    {
        $restSearchUrl = config('gurunavi.restSearchUrl');
        return $this->getApiData($restSearchUrl, $params);
    }

    public function getRestaurant($id)
    {
        $res = $this->searchRestaurants([
            'id' => $id
        ]);
        return $res['rest'][0];
    }

    public function getAreaMaster()
    {
        $gAreaSmallSearchUrl = config('gurunavi.gAreaSmallSearchUrl');

        $params = [
            'lang' => 'ja',
        ];

        return $this->getApiData($gAreaSmallSearchUrl, $params);
    }

    public function getCategoryMaster()
    {
        $gCategoryLargeUrl = config('gurunavi.categoryLargeSearchUrl');

        $params = [
            'lang' => 'ja',
        ];

        return $this->getApidata($gCategoryLargeUrl, $params);
    }

    private function getApiData($path, $params = [])
    {
        $baseUrl = config('gurunavi.baseUrl');
        $apiKey = config('gurunavi.key');
        $params['keyid'] = $apiKey;
        $requestUrl = $baseUrl . $path . '?' . $this->buildQUery($params);

        $client = new Client();
        $res = $client->request("GET", $requestUrl, [
            'http_errors' => false
        ]);

        $this->validateResponse($res);
        return json_decode($res->getBody(), true);
    }

    private function buildQuery($params)
    {
        $query = build_query($params);
        $query = str_replace('%2C', ',', $query);
        return $query;
    }

    private function validateResponse($res)
    {
        $status = $res->getStatusCode();

        if ($status / 100 !== 2) {
            $message = json_decode($res->getBody(), false)->error->message;
            throw new RestaurantApiException($message);
        }
    }
}
