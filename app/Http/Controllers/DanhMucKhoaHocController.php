<?php

namespace App\Http\Controllers;

use App\DanhMucKhoaHoc;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\TaiSanRequest;
use Illuminate\Support\Facades\Session;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;

//require_once __DIR__ . '/../../SLib/functions.php';

class DanhMucKhoaHocController extends Controller
{
    private $v;
    
    public function __construct()
    {
        $this->v = [];
    }
    
    public function danhMucKhoaHoc(Request $request){
        $this->v['_title'] = 'Danh mục khoá học';
        $this->v['routeIndexText'] = 'Danh mục khoá học';
        $objDanhMucKhoaHoc = new DanhMucKhoaHoc();
        //Nhận dữ liệu lọc từ view
        $this->v['extParams'] = $request->all();
        $this->v['list'] = $objDanhMucKhoaHoc->loadListWithPager($this->v['extParams']);

        return view('khoahoc.danh-muc', $this->v);
    }
    public function themDanhMucKhoaHoc(Request $request){
        $this->v['routeIndexText'] = 'Danh mục khoa học';
        $method_route = 'route_BackEnd_DanhMucKhoaHoc_Add';
        $this->v['_action'] = 'Add';
        $this->v['_title'] = 'Thêm danh mục khoá học';
        $this->v['trang_thai'] = config('app.status_user');
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
            $objDanhMucKhoaHoc = new DanhMucKhoaHoc();
            $res = $objDanhMucKhoaHoc->saveNew($params);

            if($res == null){
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            }elseif ($res >0){
                $this->v['request'] = [];
                $request->session()->forget('post_from_data');
                Session::flash('success', 'Thêm mới thành công danh mục khoá học');
                return redirect()->route('route_BackEnd_DanhMucKhoa_index');
            }else{
                Session::push('errors', 'Lỗi thêm mới' .$res);
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            }
        }

        return view('khoahoc.them-danh-muc', $this->v);
    }


}
//