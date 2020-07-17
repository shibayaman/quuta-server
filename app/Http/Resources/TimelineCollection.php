<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TimelineCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return Timeline::collection($this->collection);
    }
}
