<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoanThanhTraRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'loai_hinh_id' => 'required',
        ];
    }
}
