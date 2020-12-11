<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetRestaurantTimeline extends GetTimeline
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'restaurant_id' => 'required'
        ];

        return array_merge(parent::rules(), $rules);
    }
}
