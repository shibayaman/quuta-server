<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Follow extends JsonResource
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
            'followed_user' => new User($this->whenLoaded('follower')),
            'following_user' => new User($this->whenLoaded('target_user')),
            'subscription_flag' => $this->subscription_flag
        ];
    }
}
