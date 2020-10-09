<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Thread extends JsonResource
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
            'thread_id' => $this->thread_id,
            'post_id' => $this->post_id,
            'comment_id' => $this->when(
                !$this->relationLoaded('parent_comment'),
                $this->comment_id
            ),
            'comment' => new Comment($this->whenLoaded('parent_comment')),
            'child_count' => $this->when(isset($this->child_count), $this->child_count)
        ];
    }
}
