<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;
class PhuCapRequest extends FormRequest
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
                    case 'themPhuCap':
                        $rules = [
                            "ten_phu_cap" => "required",
                            'gia_tien' => "required"

                        ];
                        break;
                    case 'updatePhuCap':
                        $rules = [
                            "ten_phu_cap" => "required",
                            'gia_tien' => "required"
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
            'ten_phu_cap.required' => 'Bắt buộc phải nhập tên phụ cấp',
            'gia_tien.required' => 'Bắt buộc phải nhập mức cấp',
        ];
    }
}