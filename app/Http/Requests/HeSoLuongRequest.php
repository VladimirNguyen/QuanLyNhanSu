<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;
class HeSoLuongRequest extends FormRequest
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
        $rules = [];

        $dataRequest = $this->request->all();

        Session::push('post_form_data', $dataRequest);

        $currentAction = $this->route()->getActionMethod();
//        dd($currentAction);
        switch ($this->method()):
            case 'POST':
                switch ($currentAction) {
                    case 'themHeSoLuong':
                        $rules = [
                            "ten_he_so" => "required",
                            'luong' => "required",
                            'ghi_chu' => "required",

                        ];
                        break;
                    case 'updatePhuCap':
                        $rules = [
                            "ten_he_so" => "required",
                            'luong' => "required",
                            'ghi_chu' => "required",
                        ];
                        break;

                    default:
                        break;
                }
                break;
            default:
                break;
        endswitch;

        return $rules;
    }

    public function messages()
    {
        return [
            'ten_he_so.required' => 'Bắt buộc phải nhập tên hệ số lương',
            'luong.required' => 'Bắt buộc phải nhập mức lương',
            'ghi_chu.required' => 'Bắt buộc phải nhập ghi chú',
        ];
    }
}