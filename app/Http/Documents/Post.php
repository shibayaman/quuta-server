<?php

/**
* @OA\RequestBody(
*   request="post_store_request_body",
*   required=true,
*   @OA\JsonContent(
*       required={"content", "restaurant_id", "image_ids"},
*       @OA\Property(
*           property="content",
*           type="string",
*           description="投稿の本文"
*       ),
*       @OA\Property(
*           property="restaurant_id",
*           type="string",
*           description="投稿が紐づく店舗を表すid"
*       ),
*       @OA\Property(
*           property="image_ids",
*           type="array",
*           description="投稿される写真のid。idは事前に画像をアップロードして取得する。",
*           @OA\Items(
*               type="integer",
*           ),
*       ),
*       @OA\Property(
*           property="like_flag",
*           type="boolean",
*           description="店舗にいいねをつけるかのフラグ"
*       ),
*   ),
* ),
*/

/**
* @OA\RequestBody(
*   request="post_store_image_request_body",
*   required=true,
*   @OA\MediaType(
*       mediaType="multipart/form-data",
*       @OA\Schema(
*           required={"image"},
*           @OA\Property(
*               property="image",
*               description="アップロードする画像データ",
*               type="string",
*               format="binary",
*           ),
*           @OA\Property(
*               property="dish_name",
*               description="画像の料理名",
*               type="string",
*           ),
*       ),
*   ),
*   description="投稿のid、これ以降に投稿された投稿を取得する",
* ),
*/

/**
* @OA\Parameter(
*   parameter="post_destory_post_id",
*   name="id",
*   in="path",
*   required=true,
*   description="削除する投稿のid",
* ),
*/
