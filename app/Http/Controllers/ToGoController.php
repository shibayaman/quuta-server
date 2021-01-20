<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreToGo;
use App\ToGo;
use App\Services\GurunaviApiService;
use Auth;
use Illuminate\Http\Request;

class ToGoController extends Controller
{
    private $restaurantApiService;

    public function __construct(GurunaviApiService $restaurantApiService)
    {
        $this->middleware('auth');
        $this->restaurantApiService = $restaurantApiService;
    }

    public function store(StoreToGo $request)
    {
        $restaurant = $this->restaurantApiService->getRestaurant($request->restaurant_id);

        return ToGo::create([
            'restaurant_id' => $request->restaurant_id,
            'latitude' => $restaurant['latitude'],
            'longitude' => $restaurant['longitude'],
            'user_id' => Auth::id()
        ]);
    }
}
