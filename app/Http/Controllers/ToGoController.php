<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreToGo;
use App\ToGo;
use App\Services\GurunaviApiService;
use Auth;
use Grimzy\LaravelMysqlSpatial\Types\Point;
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
            'location' => new Point($restaurant['latitude'], $restaurant['longitude'], 4326),
            'user_id' => Auth::id()
        ]);
    }

    /**
     * @OA\Delete(
     *  path="/api/goto/{restaurant_id}",
     *  summary="Gotoリストから削除",
     *  description="レストレンをGotoリストから削除する",
     *  operationId="destroyGoto",
     *  tags={"goto"},
     *  security={{"bearer": {}}},
     *  @OA\Parameter(ref="#/components/parameters/goto_destroy_restaurant_id"),
     *  @OA\Response(
     *      response=401,
     *      description="認証されていない",
     *  ),
     *  @OA\Response(
     *      response=404,
     *      description="ユーザのGotoリストに指定されたレストランが存在しない",
     *  ),
     *  @OA\Response(
     *      response=204,
     *      description="レストランがGotoリストから削除された",
     *      @OA\MediaType(mediaType="application/json")
     *  ),
     * )
     */
    public function destroy(ToGO $toGo)
    {
        $toGo->delete();
        return response()->json('', 204);
    }
}
