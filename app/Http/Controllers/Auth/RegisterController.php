<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Session;

class RegisterController extends Controller
{
    public function getRegister() {
        return view('auth.register');
    }

    public function postRegister(Request $request) {
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password'
        ];
        $messages = [
            'name.required' => 'Họ tên là trường bắt buộc',
            'email.required' => 'Email là trường bắt buộc',
            'email.email' => 'Email không đúng định dạng',
            'password.required' => 'Mật khẩu là trường bắt buộc',
            'password.min' => 'Mật khẩu phải chứa ít nhất 8 ký tự',
            'password_confirmation.required' => 'Xác nhận mật khẩu là trường bắt buộc',
            'password_confirmation.same' => 'Xác nhận mật khẩu không khớp với mật khẩu',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('register')->withErrors($validator)->withInput();
        }

        $query = DB::table('users')->where('email', $request->input('email'))->first();
        if (!empty($query)) {
            Session::flash('error', 'Email này đã được đăng ký. Vui lòng dùng email khác');
            return redirect('register')->withInput();
        }

        DB::table('users')->insert([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'level' => 2,
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        Session::flash('success', 'Đăng ký tài khoản thành công. Vui lòng đăng nhập');
        return redirect('login');
    }
}