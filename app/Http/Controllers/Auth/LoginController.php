<?php

namespace App\Http\Controllers\Auth;

use App\NhanVien;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;

class LoginController extends Controller
{
    public function getLogin() {
        return view('auth/login');
    }
    public function postLogin(Request $request) {
        // Kiểm tra dữ liệu nhập vào
        $rules = [
            'email' =>'required|email',
            'password' => 'required|min:6'
        ];
        $messages = [
            'email.required' => 'Email là trường bắt buộc',
            'email.email' => 'Email không đúng định dạng',
            'password.required' => 'Mật khẩu là trường bắt buộc',
            'password.min' => 'Mật khẩu phải chứa ít nhất 8 ký tự',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
//        dd(Auth::id());
        if ($validator->fails()) {
        //    dd(22222);
            // Điều kiện dữ liệu không hợp lệ sẽ chuyển về trang đăng nhập và thông báo lỗi
            return redirect('login')->withErrors($validator)->withInput();
        } else {
            $email = $request->input('email');
            $password = $request->input('password');
            $query = DB::table('users as tb1')
                ->select('tb1.id', 'tb1.level', 'tb1.email','tb1.status')
                ->where('tb1.email', $request->input('email'))->first();
            $flag = 0;
            if (!$query) {
                Session::flash('error', 'Tài khoản không tồn tại');
                return redirect('login')->withInput();
            }
            if($query->status == 1){
                if ($query->level == 1){
                    $flag  = 1;
                   // return redirect('user');
                }elseif ($query->level == 2 ) {
                    $flag = 2;
                }
            }else{
                Session::flash('error', 'Tài khoản không tồn tại');
                return redirect('login');
            }


            if( Auth::attempt(['email' => $email, 'password' =>$password])) {
                $request->session()->regenerate();
                switch ($flag) {
                    case 1:
                        return redirect('user');
                        break;
                    case 2:

                        $objNhanVien = new NhanVien();
                        $id = $query->id;
                        $idNhanVien = $objNhanVien->loadOneIdUser($id);
                        if (!$idNhanVien) {
                            Auth::logout();
                            $request->session()->invalidate();
                            $request->session()->regenerateToken();
                            return redirect('login')->with('error', 'Tài khoản chưa được liên kết hồ sơ nhân viên. Vui lòng liên hệ quản trị viên.');
                        }

                        return redirect()->route('route_BackEnd_CongNhanVien_List', ['id' => $idNhanVien->id]);
                        break;
                }

            } else {
                // Kiểm tra không đúng sẽ hiển thị thông báo lỗi
                Session::flash('error', 'Email hoặc mật khẩu không đúng!');
                return redirect('login');
            }
        }
    }
}
