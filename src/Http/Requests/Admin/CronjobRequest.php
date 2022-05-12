<?php

namespace Wikichua\Bliss\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CronjobRequest extends FormRequest
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
            'name' => 'required',
            'command' => 'required',
            'timezone' => 'required',
            'frequency' => 'required',
            'status' => 'required',
            'mode' => 'required',
        ];
    }
}
