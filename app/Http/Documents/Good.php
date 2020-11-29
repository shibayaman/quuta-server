<?php

/**
* @OA\Parameter(
*   parameter="good_destroy_post_id",
*   name="post_id",
*   in="query",
*   required=true,
*   description="いいねを解除する対象の投稿のid",
* ),
*/

/**
* @OA\RequestBody(
*   request="good_store",
*   required=true,
*   @OA\JsonContent(
*       required={"post_id"},
*       @OA\Property(
*           property="post_id",
*           type="integer",
*           description="いいねする対象の投稿のid"
*       ),
*   ),
* ),
*/
