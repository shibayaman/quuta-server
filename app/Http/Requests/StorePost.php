<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'content' => 'required',
            'like_flag' => 'boolean',
            'restaurant_id' => 'required',
            'image_ids' => 'bail|required|array|min:1|max:4',
            'image_ids.*' => 'integer'
        ];
    }
}
