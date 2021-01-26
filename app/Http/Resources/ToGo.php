<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ToGo extends JsonResource
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
            'to_go_id' => $this->to_go_id,
            'restaurant_id' => $this->restaurant_id,
            'latitude' => $this->location->getLat(),
            'longitude' => $this->location->getLng()
        ];
    }
}
