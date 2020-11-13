<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'user_id' => $this->user_id,
            'username' => $this->username,
            'user_icon' => $this->icon_url,
            'description' => $this->description,
            'private_flag' => $this->private_flag,
            'birthday_date' => $this->birthday_date,
            'follower_count' => $this->follower_count,
            'following_count' => $this->following_count,
            'good_count' => $this->good_count,
            'isFollowing' => $this->whenLoaded('followers', function () {
                return $this->followers->isNotEmpty();
            }),
            'isFollower' => $this->whenLoaded('followings', function () {
                return $this->followings->isNotEmpty();
            })
        ];
    }
}
