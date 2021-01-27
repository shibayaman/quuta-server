<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetToGo extends FormRequest
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
            'latitude' => 'numeric|required_with:longitude',
            'longitude' => 'numeric|required_with:latitude'
        ];
    }
}
