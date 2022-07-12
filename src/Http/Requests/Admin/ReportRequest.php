<?php

namespace Wikichua\Bliss\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Wikichua\Bliss\Rules\AllFilledNoEmpty;
use Wikichua\Bliss\Rules\AtLeastNotEmpty;

class ReportRequest extends FormRequest
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
            'status' => 'required',
            'queries' => [
                new AtLeastNotEmpty(1),
                new AllFilledNoEmpty(),
            ],
        ];
    }
}
