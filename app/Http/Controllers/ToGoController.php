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

    /**
     * @OA\Post(
     *  path="/api/goto",
     *  summary="Gotoリストに追加",
     *  description="新しいレストランをGotoリストに追加する",
     *  operationId="storeNewPost",
     *  tags={"goto"},
     *  security={{"bearer": {}}},
     *  @OA\RequestBody(ref="#/components/requestBodies/goto_store_request_body"),
     *  @OA\Response(
     *      response=401,
     *      description="認証されていない",
     *  ),
     *  @OA\Response(
     *      response=422,
     *      description="リクエストボディに誤りがある",
     *  ),
     *  @OA\Response(
     *      response=201,
     *      description="Gotoリストに追加された",
     *      @OA\MediaType(mediaType="application/json")
     *  ),
     * )
     */
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
