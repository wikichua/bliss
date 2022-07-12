<?php

namespace Wikichua\Bliss\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProfileSaveAvatarRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'avatar' => [
                'required',
                'mimes:jpeg,jpg,png',
                'max:1024',
                'dimensions:min_width=250,min_height=250',
            ],
        ];
    }
}
