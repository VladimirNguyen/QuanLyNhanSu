<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;
class PhongBanRequest extends FormRequest
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
                    case 'themPhongBan':
                        $rules = [
                            "ten_phong_ban" => "required",
                            'trang_thai' => "required|integer"

                        ];
                        break;
                    case 'updatePhongBan':
                        $rules = [
                            "ten_phong_ban" => "required",
                            "trang_thai" => "required|integer",
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
            'ten_phong_ban.required' => 'Bắt buộc phải nhập tên phòng ban',
            'trang_thai.required' => 'Bắt buộc phải nhập trạng thái',
        ];
    }
}