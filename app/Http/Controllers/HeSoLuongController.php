<?php

namespace App\Http\Controllers;

use App\HeSoLuong;
use App\HeSoLuongNhanVien;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\HeSoLuongRequest;
use Illuminate\Support\Facades\Session;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;

//require_once __DIR__ . '/../../SLib/functions.php';

class HeSoLuongController extends Controller
{
    private $v;

    public function __construct()
    {
        $this->v = [];
    }

    public function danhSachHeSoLuong(Request $request)
    {
        $this->v['_title'] = 'Danh Sách Hệ Số Lương';
        $this->v['routeIndexText'] = 'Danh Sách Hệ Số Lương';
        $objHeSoLuong = new HeSoLuong();
        //Nhận dữ liệu lọc từ view
        $this->v['extParams'] = $request->all();
        $this->v['list'] = $objHeSoLuong->loadListWithPager($this->v['extParams']);

        return view('hesoluong.danh-sach-he-so-luong', $this->v);
    }
    public function themHeSoLuong(HeSoLuongRequest $request){
        $this->v['routeIndexText'] = 'Thêm Phụ Cấp';
        $method_route = 'route_BackEnd_PhuCap_Add';
        $this->v['_action'] = 'Add';
        $this->v['_title'] = 'Thêm Phụ Cấp';
        if($request->isMethod('post')){
            $params = [
                'hesoluong_add' => Auth::user()->id
            ];
            $params['cols'] = array_map(function ($item) {
                if ($item == '')
                    $item = null;
                if (is_string($item))
                    $item = trim($item);
                return $item;
            }, $request->post());
            unset($params['cols']['_token']);
            $objHeSoLuong = new HeSoLuong();
            $res = $objHeSoLuong->saveNew($params);

            if($res == null){
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            }elseif ($res >0){
                $this->v['request'] = [];
                $request->session()->forget('post_from_data');
                Session::flash('success', 'Thêm mới thành công hệ số lương');
                return redirect()->route('route_BackEnd_HeSoLuong_List');
            }else{
                Session::push('errors', 'Lỗi thêm mới' .$res);
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            }
        }

        return view('hesoluong.them-he-so-luong', $this->v);
    }
    public function  chitetPhuCap($id, Request $request){
        $this->v['routeIndexText'] = 'Chi tiết Phụ Cấp';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi tiết Phụ Cấp';
        $objPhuCap = new PhuCap();
        $objItem = $objPhuCap->loadOne($id);
        $this->v['extParams'] = $request->all();
        $this->v['objItem'] = $objItem;
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại danh mục này ' . $id);
            return redirect()->back();
        }
        return view('hesoluong.sua-phu-cap', $this->v);

    }

    public function updatePhuCap($id, PhuCapRequest $request){

        $method_route = 'route_BackEnd_PhuCap_Detail';
        $modelPhuCap = new PhuCap();
        $params = [
            'phucap_edit' => Auth::user()->id
        ];
        $params['cols'] = array_map(function ($item) {
            if($item == '')
                $item = null;
            if(is_string($item))
                $item = trim($item);
            return $item;
        }, $request->post());

        unset($params['cols']['_token']);
        $objItem = $modelPhuCap->loadOne($id);
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại người dùng này ' . $id);
            return redirect()->route('route_BackEnd_NguoiDung_index');
        }
        $params['cols']['id'] = $id;
        $res = $modelPhuCap->saveUpdate($params);
        if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
        {
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        } elseif ($res == 1) {
//            SpxLogUserActivity(Auth::user()->id, 'edit', $primary_table, $id, 'edit');
            $request->session()->forget('post_form_data'); // xóa data post
            Session::flash('success', 'Cập nhật thành công phụ câp');

            return redirect()->route('route_BackEnd_PhuCap_List');
        } else {

            Session::push('errors', 'Lỗi cập nhật cho bản ghi: ' . $res);
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        }
    }
    public function lichSuLuong($id, Request $request){
        $this->v['routeIndexText'] = 'Chi tiết lịch sử lương';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi tiết lịch sử lương';
        $this->v['extParams'] = $request->all();
        $objHSLNV = new HeSoLuongNhanVien();
        $this->v['list'] = $objHSLNV->loadOneID($id);
        return view('hesoluong.lich-su-luong-nhan-vien', $this->v);
    }
}