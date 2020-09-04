<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Comment extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'comment_id' => $this->comment_id,
            'thread_id' => $this->thread_id,
            'comment' => $this->comment,
            'user' => new User($this->user),
        ];
    }
}
