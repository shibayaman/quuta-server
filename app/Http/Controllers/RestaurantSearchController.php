<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\GurunaviApiService;
use Illuminate\Http\Request;

class RestaurantSearchController extends Controller
{
    private $restaurantService;

    public function __construct(GurunaviApiService $restaurantService)
    {
        $this->restaurantService = $restaurantService;
    }

    public function search(Request $request)
    {
        $result = $this->restaurantService->searchRestaurant($request->all());
        return $result;
    }
}
