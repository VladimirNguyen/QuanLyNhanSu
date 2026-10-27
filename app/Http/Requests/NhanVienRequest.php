<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;
class NhanVienRequest extends FormRequest
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
                    case 'themNhanVienMoi':
                        $rules = [
                            "ho_ten" => "required",
                            "ngay_sinh" => "required|date",
                            "gioi_tinh" => "required",
                            "dia_chi" => "required",
                            "so_dien_thoai" => "required",
                            "so_chung_minh" => "required",
                            "email" => "required",
                        ];
                        break;
                    case 'updateNhanVien':
                        $rules = [
                            "ho_ten" => "required",
                            "ngay_sinh" => "required|date",
                            "gioi_tinh" => "required",
                            "dia_chi" => "required",
                            "so_dien_thoai" => "required",
                            "so_chung_minh" => "required",
                            "email" => "required",
                            "id_phong_ban" => "required",
                            "id_chuc_vu" => "required|integer",
                        ];
                        break;
                    case 'updateNV':
                        $rules = [
                            "id_phong_ban" => "required|integer",
                            "id_chuc_vu" => "required|integer",
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
            'ho_ten.required' => 'Bắt buộc phải nhập họ tên nhân viên',
            'ngay_sinh.required' => 'Bắt buộc phải nhập ngày sinh',
            'gioi_tinh.required' => 'Bắt buộc phải nhập giới tính',
            'dia_chi.required' => 'Bắt buộc phải nhập địa chỉ',
            'so_dien_thoai.required' => 'Bắt buộc phải nhập số điện thoại',
            'so_chung_minh.required' => 'Bắt buộc phải số chứng minh thứ',
            'email.required' => 'Bắt buộc phải nhập email',
            'id_phong_ban.required' => 'Bắt buộc phải nhập phòng ban',
            'id_chuc_vu.required' => 'Bắt buộc phải nhập chức vự',
        ];
    }
}