<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreToGo;
use App\Http\Requests\GetToGo;
use App\Http\Resources\ToGoCollection;
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
     * @OA\Get(
     *  path="/api/goto",
     *  summary="Gotoリスト取得",
     *  description="指定位置の半径1キロ圏内にあるGotoリストに登録されたレストランを取得する(位置指定なしの場合、リストの全てのレストランを返す)",
     *  operationId="getGoto",
     *  tags={"goto"},
     *  security={{"bearer": {}}},
     *  @OA\Parameter(ref="#/components/parameters/goto_get_latitude"),
     *  @OA\Parameter(ref="#/components/parameters/goto_get_longitude"),
     *  @OA\Response(
     *      response=401,
     *      description="認証されていない",
     *  ),
     *  @OA\Response(
     *      response=422,
     *      description="リクエストボディに誤りがある",
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(mediaType="application/json")
     *  ),
     * )
     */
    public function index(GetToGo $request)
    {
        $query = ToGo::where('user_id', Auth::id());

        if ($request->has(['latitude', 'longitude'])) {
            $location = new Point($request->latitude, $request->longitude, 4326);
            $query->distance('location', $location, 1000);
        }

        //あとでちゃんと書く
        $toGos = $query->paginate(10);
        $this->mapRestaurantInfo($toGos->getCollection());

        return new ToGoCollection($toGos);
        return ToGoResource::collection($toGos);
    }

    /**
     * @OA\Post(
     *  path="/api/goto",
     *  summary="Gotoリストに追加",
     *  description="新しいレストランをGotoリストに追加する",
     *  operationId="storeNewGoto",
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

    /**
     * 取得したToGoのデーターでgurunaviApiをたたいてレストラン情報をマッピングする。
     * プロトタイプの完成を急ぐ必要があるので応急処置的に追加してるメソッド。
     * restaurantsテーブルを追加する等であとでちゃんとした対応をする。
     */
    public function mapRestaurantInfo($toGoCollection)
    {
        $restaurantIds = $toGoCollection->pluck('restaurant_id')->all();
        $restaurants = $this->restaurantApiService->searchRestaurants([
            'id' => implode(',', $restaurantIds)
        ])['rest'];
        $toGoCollection->each(function ($toGo) use ($restaurants) {
            if (isset($toGo->restaurant)) {
                return;
            };

            foreach ($restaurants as $restaurant) {
                if ($restaurant['id'] === $toGo->restaurant_id) {
                    $toGo->restaurant = $restaurant;
                    break;
                }
            }
        });
    }
}
