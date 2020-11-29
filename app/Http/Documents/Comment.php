<?php

/**
* @OA\Parameter(
*   parameter="comment_get_parent_post_id",
*   required=true,
*   name="post_id",
*   in="query",
*   description="スレッドを取得する投稿のid",
* ),
*/

/**
* @OA\Parameter(
*   parameter="comment_get_child_thread_id",
*   required=true,
*   name="thread_id",
*   in="query",
*   description="コメントを取得するスレッドのid",
* ),
*/

/**
* @OA\Parameter(
*   parameter="comment_destroy_id",
*   required=true,
*   name="id",
*   in="path",
*   description="削除するコメントのid",
* ),
*/

/**
* @OA\RequestBody(
*   request="comment_store_parent",
*   required=true,
*   @OA\JsonContent(
*       required={"post_id", "comment"},
*       @OA\Property(
*           property="post_id",
*           type="integer",
*           description="スレッドを作成する対象の投稿のid"
*       ),
*       @OA\Property(
*           property="comment",
*           type="string",
*           description="投稿するコメントの内容"
*       ),
*   ),
* ),
*/

/**
* @OA\RequestBody(
*   request="comment_store_child",
*   required=true,
*   @OA\JsonContent(
*       required={"thread_id", "comment"},
*       @OA\Property(
*           property="thread_id",
*           type="integer",
*           description="コメントを投稿する対象のスレッドのid"
*       ),
*       @OA\Property(
*           property="comment",
*           type="string",
*           description="投稿するコメントの内容"
*       ),
*   ),
* ),
*/
