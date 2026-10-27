<?php

namespace App\Http\Controllers;

use App\ChucVu;
use App\PhuCap;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\ChucVuRequet;
use Illuminate\Support\Facades\Session;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;

//require_once __DIR__ . '/../../SLib/functions.php';

class ChucVuController extends Controller
{
    private $v;

    public function __construct()
    {
        $this->v = [];
    }

    public function chucVu(Request $request){
        $this->v['_title'] = 'Chức vụ';
        $this->v['routeIndexText'] = 'Chức vụ';
        $objChucVu = new ChucVu();
        //Nhận dữ liệu lọc từ view
        $this->v['extParams'] = $request->all();
        $this->v['list'] = $objChucVu->loadListWithPager($this->v['extParams']);

        return view('chucvu.chuc-vu-list', $this->v);
    }
    public function PhuCap(Request $request){
        $this->v['_title'] = 'Phụ Cấp';
        $this->v['routeIndexText'] = 'Phụ Cấp';
        $objPhuCap = new PhuCap();
        $this->v['extParams'] = $request->all();
        $this->v['list'] = $objPhuCap->loadListWithPager($this->v['extParams']);
        return view('phucap.phu-cap', $this->v);
    }
    public function themChucVu(ChucVuRequet $request){
        $this->v['routeIndexText'] = 'Chức vụ';
        $method_route = 'route_BackEnd_ChucVu_Add';
        $this->v['_action'] = 'Add';
        $this->v['_title'] = 'Thêm chức vụ';
        $this->v['trang_thai'] = config('app.status_chuc_vu');
        $this->v['extParams'] = $request->all();
        if($request->isMethod('post')){
            $params = [
                'user_add' => Auth::user()->id
            ];
            $params['cols'] = array_map(function ($item) {
                if ($item == '')
                    $item = null;
                if (is_string($item))
                    $item = trim($item);
                return $item;
            }, $request->post());
            unset($params['cols']['_token']);
            $objChucVu = new ChucVu();
            $itemChucVu = $objChucVu->loadListWithPager($this->v['extParams']);
            foreach ($itemChucVu as $value){
                if ($value->ten_chuc_vu == $request->ten_chuc_vu){
                    $item = 1;
                    break;
                }
            }
            if (isset($item)){
                Session::flash('success', 'Chức vụ này đã có. Vui lòng nhập lại');
                return redirect()->route('route_BackEnd_ChucVU_Add');
            }else {
                $res = $objChucVu->saveNew($params);

                if ($res == null) {
                    Session::push('post_form_data', $this->v['request']);
                    return redirect()->route($method_route);
                } elseif ($res > 0) {
                    $this->v['request'] = [];
                    $request->session()->forget('post_from_data');
                    Session::flash('success', 'Thêm mới thành công chức vụ');
                    return redirect()->route('route_BackEnd_ChucVu_index');
                } else {
                    Session::push('errors', 'Lỗi thêm mới' . $res);
                    Session::push('post_form_data', $this->v['request']);
                    return redirect()->route($method_route);
                }
            }
        }

        return view('chucvu.them-chuc-vu', $this->v);
    }
    public function chitietChucVu($id, Request $request){
        $this->v['routeIndexText'] = 'Chi tiết chức vụ';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi tiết chức vụ';
        $objChucVu = new ChucVu();
        $objItem = $objChucVu->loadOne($id);
        $this->v['extParams'] = $request->all();
        $this->v['trang_thai'] = config('app.status_chuc_vu');
        $this->v['objItem'] = $objItem;
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại chức vụ này ' . $id);
            return redirect()->back();
        }
        return view('chucvu.update-chuc-vu', $this->v);
    }
    public function updateChucVu($id,ChucVuRequet $request){
        $method_route = 'route_BackEnd_ChucVu_Detail';
        $this->v['extParams'] = $request->all();
        $modelChucVu  = new ChucVu();
        $params = [
            'chucvu_edit' => Auth::user()->id
        ];
        $params['cols'] = array_map(function ($item) {
            if($item == '')
                $item = null;
            if(is_string($item))
                $item = trim($item);
            return $item;
        }, $request->post());
        unset($params['cols']['_token']);
        $objItem = $modelChucVu->loadOne($id);
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại chức vụ này ' . $id);
            return redirect()->route('route_BackEnd_ChucVu_index');
        }
        $params['cols']['id'] = $id;
        $itemChucVu = $modelChucVu->loadListWithPager($this->v['extParams']);
        foreach ($itemChucVu as $value){
            if ($value->ten_chuc_vu == $request->ten_chuc_vu){
                $item = 1;
                break;
            }
        }
        if (isset($item)){
            return redirect()->route('route_BackEnd_ChucVu_Detail',['id'=>$id]);
        }else {
            $res = $modelChucVu->saveUpdate($params);
            if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
            {
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route, ['id' => $id]);
            } elseif ($res == 1) {
                $request->session()->forget('post_form_data'); // xóa data post
                Session::flash('success', 'Cập nhật chức vụ thành công!');
                return redirect()->route('route_BackEnd_ChucVu_index');
            } else {
                Session::push('errors', 'Lỗi cập nhật cho chức vụ: ' . $res);
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route, ['id' => $id]);
            }
        }
    }

}
//