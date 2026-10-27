<?php

namespace App\Http\Controllers;

use App\KhoaHoc;
use App\DanhMucKhoaHoc;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\KhoaHocRequest;
use Illuminate\Support\Facades\Session;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;

require_once __DIR__ . '/../../SLib/functions.php';

class KhoaHocController extends Controller
{
    private $v;

    public function __construct()
    {
        $this->v = [];
    }

    public function khoaHoc(Request $request)
    {
        $this->v['_title'] = 'Khoá học';
        $this->v['routeIndexText'] = 'Khoá học';
        $objKhoaHoc = new KhoaHoc();
        //Nhận dữ liệu lọc từ view
        $this->v['extParams'] = $request->all();
        $this->v['list'] = $objKhoaHoc->loadListWithPager($this->v['extParams']);
        $objDanhMucKhoaHoc = new DanhMucKhoaHoc();
        $this->v['danh_muc_khoa_hoc'] = $objDanhMucKhoaHoc->loadListIdAndName(['trang_thai', 1]);
        $danhMucs = $this->v['danh_muc_khoa_hoc'];
        $arrDanhMuc = [];
        foreach ($danhMucs as $index => $item) {
            $arrDanhMuc[$item->id] = $item->ten_danh_muc;
        }
        $this->v['arrDanhMuc'] = $arrDanhMuc;
        return view('khoahoc.khoa-hoc', $this->v);
    }

    public function themKhoaHoc(KhoaHocRequest $request)
    {
        $this->v['_title'] = 'Khoá học';
        $method_route = 'route_BackEnd_KhoaHoc_Add';
        $this->v['_action'] = 'Add';
        $this->v['_title'] = 'Thêm khoá học';
        $this->v['trang_thai'] = config('app.status_user');
        if ($request->isMethod('post')) {

            if (Session::has($method_route)) {
                return redirect()->route($method_route); // không cho F5, chỉ có thể post 1 lần
            } else
                Session::push($method_route, 1); // bỏ vào session để chống F5

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
            if($request->hasFile('hinh_anh_khoa_hoc') && $request->file('hinh_anh_khoa_hoc')->isValid()){
                $params['cols']['hinh_anh'] = $this->uploadFile($request->file('hinh_anh_khoa_hoc'));
            }

            unset($params['cols']['_token']);
            $objKhoaHoc = new KhoaHoc();
            $res = $objKhoaHoc->saveNew($params);
            if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
            {
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            } elseif ($res > 0) {
                $this->v['request'] = [];
                $request->session()->forget('post_form_data'); // xóa data post
                Session::flash('success', 'Thêm mới thành công khoá học !');
                return redirect()->route('route_BackEnd_KhoaHoc_index');

            } else {
                Session::push('errors', 'Lỗi thêm mới: ' . $res);
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            }

        } else {
            // không phải post
            $request->session()->forget($method_route); // hủy session nếu vào bằng sự kiện get
        }

        $objDanhMucKhoaHoc = new  DanhMucKhoaHoc();
        $this->v['danh_muc_khoa_hoc'] = $objDanhMucKhoaHoc->loadListIdAndName(['trang_thai', 1]);
        return view('khoahoc.them-khoa-hoc', $this->v);

    }

    public function chiTietKhoaHoc($id, Request $request){
        $this->v['routeIndexText'] = 'Chi tiết khoá học';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi tiết khoá học';
        $objKhoaHoc = new KhoaHoc();
        $objItem = $objKhoaHoc->loadOne($id);
        $this->v['objItem'] = $objItem;
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại khóa học này ' . $id);
            return redirect()->back();
        }
        $this->v['extParams'] = $request->all();
        $this->v['trang_thai'] = config('app.status_user');
        $objDanhMucKhoaHoc = new  DanhMucKhoaHoc();
        $this->v['danh_muc_khoa_hoc'] = $objDanhMucKhoaHoc->loadListIdAndName(['trang_thai', 1]);

        return view('khoahoc.chi-tiet-khoa-hoc',$this->v);
    }
    private function uploadFile($file)
    {
        $fileName = time().'_'.$file->getClientOriginalName();
        return $file->storeAs('cmnd_cccd', $fileName, 'public');
    }

    public function update($id, KhoaHocRequest $request){

        $method_route = 'route_BackEnd_KhoaHoc_Detail';
        $modelKhoaHoc = new KhoaHoc();
        $params = [
            'user_edit' => Auth::user()->id
        ];
        $params['cols'] = array_map(function ($item) {
            if($item == '')
                $item = null;
            if(is_string($item))
                $item = trim($item);
            return $item;
        }, $request->post());
        if($request->hasFile('hinh_anh_khoa_hoc') && $request->file('hinh_anh_khoa_hoc')->isValid()){
            $params['cols']['hinh_anh'] = $this->uploadFile($request->file('hinh_anh_khoa_hoc'));
        }
        unset($params['cols']['_token']);

        $objItem = $modelKhoaHoc->loadOne($id);
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại người dùng này ' . $id);
            return redirect()->route('route_BackEnd_NguoiDung_index');
        }
        $params['cols']['id'] = $id;
        $res = $modelKhoaHoc->saveUpdate($params);
        if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
        {
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        } elseif ($res == 1) {
//            SpxLogUserActivity(Auth::user()->id, 'edit', $primary_table, $id, 'edit');
            $request->session()->forget('post_form_data'); // xóa data post
            Session::flash('success', 'Cập nhật bản ghi: ' . $objItem->id . ' thành công!');

            return redirect()->route($method_route, ['id' => $id]);
        } else {

            Session::push('errors', 'Lỗi cập nhật cho bản ghi: ' . $res);
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        }
    }
    public function fontendDanhSachKhoaHoc($id, Request $request){
        $this->v['extParams'] = $request->all();
        $objKhoaHoc = new KhoaHoc();
        $this->v['lists'] = $objKhoaHoc->loadListIdWithPager($this->v['extParams'], $id);
        return view('khoahoc.fr-khoa-hoc', $this->v);
    }

}
//