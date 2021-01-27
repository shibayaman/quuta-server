<?php

/**
* @OA\RequestBody(
*   request="goto_store_request_body",
*   required=true,
*   @OA\JsonContent(
*       required={"restaurant_id"},
*       @OA\Property(
*           property="restaurant_id",
*           type="string",
*           description="リストに追加するレストランのid"
*       ),
*   ),
* ),
*/

/**
* @OA\Parameter(
*   parameter="goto_destroy_restaurant_id",
*   name="restaurant_id",
*   in="path",
*   required=true,
*   description="Gotoリストから削除するレストランのid",
* ),
*/

/**
* @OA\Parameter(
*   parameter="goto_get_latitude",
*   name="latitude",
*   in="query",
*   description="取得したい位置の緯度(指定時はlongitudeも必須)",
* ),
*/

/**
* @OA\Parameter(
*   parameter="goto_get_longitude",
*   name="longitude",
*   in="query",
*   description="取得したい位置の経度(指定時はlatitudeも必須)",
* ),
*/
