<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFollow extends FormRequest
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
            'user_id' => [
                'required',
                'exists:users',
                Rule::unique('follows', 'follow_user_id')->where(function ($query) {
                    $query->where('user_id', $this->user()->user_id);
                }),
                function ($attribute, $value, $fail) {
                    if ($value === $this->user()->user_id) {
                        $fail('cannot follow yourself');
                    }
                }
            ],
            'subscription_flag' => 'boolean'
        ];
    }
}
