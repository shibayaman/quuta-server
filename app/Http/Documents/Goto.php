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
