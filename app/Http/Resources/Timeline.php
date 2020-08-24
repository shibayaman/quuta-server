<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Timeline extends JsonResource
{
    public function toArray($request)
    {
        return [
            'post_id' => $this->post_id,
            'content' => $this->content,
            'user' => new User($this->user),
            'like_flag' => $this->like_flag,
            'good_count' => $this->good_count,
            'comment_count' => $this->comment_count,
            'good_flag' => $this->goods->isNotEmpty(),
            'images' => Image::collection($this->images),
            'restaurant' => [
                'restaurant_id' => $this->restaurant_id,
                'restaurant_name' => $this->restaurant_name,
                'restaurant_address' => $this->restaurant_address,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
