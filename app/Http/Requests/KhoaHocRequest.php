<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KhoaHocRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'ten_khoa_hoc' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'ten_khoa_hoc.required' => 'Tên khóa học là trường bắt buộc',
        ];
    }
}