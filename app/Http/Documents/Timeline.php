<?php

/**
* @OA\Parameter(
*   parameter="timeline_get_since_id",
*   name="since_id",
*   example="1",
*   in="query",
*   description="投稿のid、これ以降に投稿された投稿を取得する",
* ),
*/

/**
* @OA\Parameter(
*   parameter="timeline_get_until_id",
*   name="until_id",
*   example="30",
*   in="query",
*   description="投稿のid、これ以前に投稿された投稿を取得する",
* ),
*/

/**
* @OA\Parameter(
*   parameter="timeline_get_count",
*   name="count",
*   example="20",
*   in="query",
*   description="取得する投稿の数、 デフォルト10、最大50",
* ),
*/

/**
* @OA\Parameter(
*   parameter="timeline_get_user_id",
*   name="user_id",
*   in="query",
*   required=true,
*   description="取得するユーザのid",
* ),
*/

/**
* @OA\Parameter(
*   parameter="timeline_get_restaurant_id",
*   name="restaurant_id",
*   in="query",
*   required=true,
*   description="取得するレストランのid",
* ),
*/
