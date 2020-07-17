<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetTimeline extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'since_id' => 'integer|min:0',
            'until_id' => 'integer|min:0',
            'count' => 'integer|min:1'
        ];
    }
}
