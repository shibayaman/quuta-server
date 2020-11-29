<?php

/**
* @OA\Parameter(
*   parameter="user_get_info",
*   name="id",
*   in="path",
*   required=true,
*   description="取得するユーザのid",
* ),
*/

/**
* @OA\Parameter(
*   parameter="user_get_follower_user_id",
*   name="user_id",
*   in="query",
*   required=true,
*   description="対象ユーザのid、このユーザのフォロワーを取得する。",
* ),
*/

/**
* @OA\Parameter(
*   parameter="user_get_following_user_id",
*   name="user_id",
*   in="query",
*   required=true,
*   description="対象ユーザのid、このユーザがフォローしているユーザ取得する。",
* ),
*/

/**
* @OA\Parameter(
*   parameter="user_destroy_follow",
*   name="user_id",
*   in="query",
*   required=true,
*   description="削除するユーザのid",
* ),
*/

/**
* @OA\RequestBody(
*   request="user_store_follow",
*   required=true,
*   @OA\JsonContent(
*       required={"user_id"},
*       @OA\Property(
*           property="user_id",
*           type="string",
*           description="フォローするユーザのid"
*       ),
*       @OA\Property(
*           property="subscription_flag",
*           type="boolean",
*           description="ユーザのアクションを購読するか"
*       ),
*   ),
* ),
*/
