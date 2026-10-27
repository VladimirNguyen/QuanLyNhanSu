<?php

namespace App\Http\Controllers;

use App\Mail\EmaiLuong;
use App\PhongBan;
use App\NhanVien;
use App\CongNhanVien;
use App\PhuCap;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\PhongBanRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;
use PHPExcel_IOFactory;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

require_once __DIR__ . '/../../SLib/functions.php';

class PhongBanController extends Controller
{
    private $v;

    public function __construct()
    {
        $this->v = [];
    }

    public function listsPhongBan(Request $request){
        $this->v['_title'] = 'Phòng ban';
        $this->v['routeIndexText'] = 'Phòng ban';
        $objPhongBan = new PhongBan();
        //Nhận dữ liệu lọc từ view
        $this->v['extParams'] = $request->all();
        $this->v['list'] = $objPhongBan->loadListWithPager($this->v['extParams']);

        return view('phongban.phong-ban-list', $this->v);
    }
    public function themPhongBan(PhongBanRequest $request){
        $this->v['routeIndexText'] = 'Phòng ban';
        $method_route = 'route_BackEnd_PhongBan_Add';
        $this->v['_action'] = 'Add';
        $this->v['_title'] = 'Thêm Phòng Ban';
        $this->v['extParams'] = $request->all();
        $this->v['trang_thai'] = config('app.status_phong_ban');
        if($request->isMethod('post')){
            $params = [
                'phongban_add' => Auth::user()->id
            ];
            $params['cols'] = array_map(function ($item) {
                if ($item == '')
                    $item = null;
                if (is_string($item))
                    $item = trim($item);
                return $item;
            }, $request->post());
            unset($params['cols']['_token']);
            $objPhongBan = new PhongBan();
            $itemPB = $objPhongBan->loadListWithPager($this->v['extParams']);
            foreach ($itemPB as $value){
                if ($value->ten_phong_ban == $request->ten_phong_ban){
                    $item =1;
                    break;
                }
            }
            if (isset($item)){
                Session::flash('error', 'Tên phòng ban trùng nhau. Vui lòng nhập lại');
                return redirect()->route('route_BackEnd_PhongBan_Add');
            }else{
                $res = $objPhongBan->saveNew($params);

                if($res == null){
                    Session::push('post_form_data', $this->v['request']);
                    return redirect()->route($method_route);
                }elseif ($res >0){
                    $this->v['request'] = [];
                    $request->session()->forget('post_from_data');
                    Session::flash('success', 'Thêm mới thành công phòng ban');
                    return redirect()->route('route_BackEnd_PhongBan_index');
                }else{
                    Session::push('errors', 'Lỗi thêm mới' .$res);
                    Session::push('post_form_data', $this->v['request']);
                    return redirect()->route($method_route);
                }
            }

        }

        return view('phongban.them-phong-ban', $this->v);
    }

    public function chiTietPhongBan($id, Request $request){
        $this->routeIndex = 'route_BackEnd_PhongBan_Detail';
        $this->v['routeIndexText'] = 'Chi Tiết Phòng Ban';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi Tiết Phòng Ban';
        $objPhongBan = new PhongBan();
        $objItem = $objPhongBan->loadOne($id);

        $this->v['trang_thai'] = config('app.status_user');
        $this->v['objItem'] = $objItem;
        $this->v['extParams'] = $request->all();
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại mục này ' . $id);
            return redirect()->back();
        }
        if (isset($this->v['extParams']['search_ngay_tinh_luong'])) {
            $ngaythem = explode(' - ', $this->v['extParams']['search_ngay_tinh_luong']);
            if (count($ngaythem) != 2) {
                Session::flash('error', 'Ngày tính lương không hợp lệ');
                return redirect()->route($this->routeIndex, ['id'=>$id]);
            }
            $datetime = array_map('convertDateToSql', $ngaythem);
            $datetime[0] = $datetime[0] . ' 00:00:00';
            $datetime[1] = $datetime[1] . ' 23:59:59';
            $this->v['extParams']['search_ngay_tinh_luong_array'] = $datetime;
        }
        $objNhanVien = new NhanVien();
        $nhanVien = $objNhanVien->loadListWithPager($this->v['extParams'], $id);

        $this->v['lists'] = $nhanVien;
        $objCongNV = new CongNhanVien();
        if (isset($_GET['btnXemTruoc'])){
            if (isset($datetime)){
                $dataNhans = DB::table('nhan_vien as tb1')
                    ->select('tb1.ho_ten', 'tb1.so_dien_thoai','tb3.ten_phong_ban', 'tb1.so_chung_minh','tb1.email','tb2.ten_chuc_vu','tb3.ten_phong_ban','tb1.id','tb5.ten_he_so','tb5.luong')
                    ->leftJoin('chuc_vu as tb2', 'tb2.id', '=', 'tb1.id_chuc_vu')
                    ->leftJoin('phong_ban as tb3', 'tb3.id', '=', 'tb1.id_phong_ban')
                    ->leftJoin('nhan_vien_he_so as tb4','tb4.id_nhan_vien','=', 'tb1.id')
                    ->leftJoin('he_so_luong as tb5', 'tb5.id','=','tb4.id_he_so')
                    ->where('tb4.trang_thai','=',1)
                    ->where('tb3.id',$id)->get();
                $pdf = PDF::setOptions([
                    'logOutputFile' => storage_path('logs/log.htm'),
                    'tempDir' => storage_path('logs/')
                ])
                    ->loadView('print.luong_phong_ban', compact('dataNhans','datetime'))->setPaper('a4');
                return $pdf->stream();
            }else{
                Session::flash('error', 'Vui lòng chọn ngày tính lương');
                return redirect()->route($this->routeIndex, ['id'=>$id]);
            }

        }
        if (isset($_GET['btnGuiGmail'])) {
            if (isset($datetime)) {
                foreach ($nhanVien as $value) {

                    $this->v['list'] = $objCongNV->loadListWithPager($value->id, $this->v['extParams']);
                    $thongTinNhanVien = DB::table('nhan_vien as tb1')
                        ->select('tb1.ho_ten', 'tb1.dia_chi as phuCap', 'tb1.so_dien_thoai as tenHeSo', 'tb1.so_chung_minh as ngayDau', 'tb1.email', 'tb1.id_user as tangCa', 'tb1.gioi_tinh as ngayCuoi', 'tb2.id as ngayLam', 'tb3.id as ngayNua', 'tb1.id_chuc_vu as heSoLuong', 'tb1.trang_thai as tienPhuCap', 'tb2.ten_chuc_vu', 'tb3.ten_phong_ban')
                        ->leftJoin('chuc_vu as tb2', 'tb2.id', '=', 'tb1.id_chuc_vu')
                        ->leftJoin('phong_ban as tb3', 'tb3.id', '=', 'tb1.id_phong_ban')
                        ->where('tb1.id', $value->id)->first();

                    $email = $thongTinNhanVien->email;

                    $luong = DB::table('nhan_vien_he_so as tb1')
                        ->select('tb1.trang_thai', 'tb1.id', 'tb1.id_he_so', 'tb1.id_nhan_vien', 'tb2.ten_he_so', 'tb2.luong')
                        ->leftJoin('he_so_luong as tb2', 'tb2.id', '=', 'tb1.id_he_so')
                        ->where('tb1.trang_thai', '=', 1)
                        ->where('tb1.id_nhan_vien', $value->id)->first();

                    $itemPhuCap = $query = DB::table('phu_cap as tb1')
                        ->select('tb1.id', 'tb1.ten_phu_cap', 'tb1.gia_tien', 'tb1.trang_thai')
                        ->leftJoin('phu_cap_nhan_vien as tb2', 'tb2.id_phu_cap', '=', 'tb1.id')
                        ->where('tb2.id_nhan_vien', '=', $value->id)->get();
                    $ngayCong = $this->v['list'];
                    $i = 0;
                    $ngaylam = 0;
                    $tangca = 0;
                    $nuangay = 1;
                    $arrNgay = [];
                    foreach ($ngayCong as $item) {
                        $arrNgay[$i] = $item->ngay_lam;
                        if ((gmdate("H", ((strtotime($item->gio_ra) - strtotime('17:00:00')))) * 60) + gmdate("i", ((strtotime($item->gio_ra) - strtotime('17:00:00')))) > 0) {
                            $tangca += ((gmdate("H", ((strtotime($item->gio_ra) - strtotime('17:00:00')))) * 60) + gmdate("i", ((strtotime($item->gio_ra) - strtotime('17:00:00')))));
                        }
                        if (strtotime('09:00:00') < strtotime($item->gio_vao)) {
                            $nuangay++;
                        }
                        $ngaylam++;
                        $i++;
                    }
                    $tienPhuCap = 0;
                    foreach ($itemPhuCap as $item) {
                        $tienPhuCap += $item->gia_tien;
                    }
                    if ($i > 0) {
                        $thongTinNhanVien->ngayLam = $ngaylam;
                        $thongTinNhanVien->ngayNua = $nuangay;
                        $thongTinNhanVien->heSoLuong = $luong->luong;
                        $thongTinNhanVien->tenHeSo = $luong->ten_he_so;
                        $thongTinNhanVien->ngayDau = date("d/m/Y", strtotime($arrNgay[$i - 1]));
                        $thongTinNhanVien->ngayCuoi = date("d/m/Y", strtotime($arrNgay[0]));
                        echo date("d/m/Y", strtotime($arrNgay[$i - 1]));
                        $thongTinNhanVien->phuCap = $itemPhuCap;
                        $thongTinNhanVien->tangCa = $tangca;
                        $thongTinNhanVien->tienPhuCap = $tienPhuCap;
                         Mail::to($email)->send(new EmaiLuong($thongTinNhanVien));

                    }if($i < 0){
                        Session::flash('error', 'Vui lòng kiểm tra lại bảng chấm công của nhân viên: '.$thongTinNhanVien->ho_ten);
                        return redirect()->route($this->routeIndex, ['id'=>$id]);
                    }

                }
            }else{
                Session::flash('error', 'Vui lòng chọn ngày tính lương');
                return redirect()->route($this->routeIndex, ['id'=>$id]);
            }
        }
        return view('phongban.chi-tiet-phong-ban',$this->v);
    }

    public function updatePhongBan($id, PhongBanRequest $request){
        $method_route = 'route_BackEnd_PhongBan_Detail';
        $modelPhongBan = new PhongBan();
        $this->v['extParams'] = $request->all();
        $params = [
            'phongban_edit' => Auth::user()->id
        ];
        $params['cols'] = array_map(function ($item) {
            if($item == '')
                $item = null;
            if(is_string($item))
                $item = trim($item);
            return $item;
        }, $request->post());
        unset($params['cols']['_token']);
        $objItem = $modelPhongBan->loadOne($id);
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại phòng ban này ' . $id);
            return redirect()->route('route_BackEnd_PhongBan_index');
        }
        $params['cols']['id'] = $id;
        $itemPB = $modelPhongBan->loadListWithPager($this->v['extParams']);
        if($objItem->ten_phong_ban == $request->ten_phong_ban){
            $res = $modelPhongBan->saveUpdate($params);
            if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
            {
//                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route, ['id' => $id]);
            } elseif ($res == 1) {
                $request->session()->forget('post_form_data'); // xóa data post
                Session::flash('success', 'Cập nhật phòng ban thành công!');
                return redirect()->route($method_route, ['id' => $id]);
            } else {

                Session::push('errors', 'Lỗi cập nhật cho bản ghi: ' . $res);
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route, ['id' => $id]);
            }
        }else{
            foreach ($itemPB as $value){
                if ($value->ten_phong_ban == $request->ten_phong_ban){
                    $item =1;
                    break;
                }
            }
            if (isset($item)){
                Session::flash('error', 'Tên phòng ban trùng nhau. Vui lòng nhập lại');
                return redirect()->route('route_BackEnd_PhongBan_Detail', ['id' => $id]);
            }else {
                $res = $modelPhongBan->saveUpdate($params);
                if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
                {
                    Session::push('post_form_data', $this->v['request']);
                    return redirect()->route($method_route, ['id' => $id]);
                } elseif ($res == 1) {
                    $request->session()->forget('post_form_data'); // xóa data post
                    Session::flash('success', 'Cập nhật phòng ban thành công!');
                    return redirect()->route($method_route, ['id' => $id]);
                } else {

                    Session::push('errors', 'Lỗi cập nhật cho bản ghi: ' . $res);
                    Session::push('post_form_data', $this->v['request']);
                    return redirect()->route($method_route, ['id' => $id]);
                }
            }
        }

    }


    public function themCongNhanVien($id,Request $request){
        $this->v['_title'] = 'Chi Tiết Thông Tin Nhân Viên';
        $this->v['extParams'] = $request->all();
        $objCongNhanVien = new CongNhanVien();
        $this->v['objCongNhanVien'] = $objCongNhanVien->loadListWithPager($id);
        return view('phongban.them-cong-nhan-vien',$this->v);
    }
    public function chamCongNhanVien($id,Request $request){
        $method_route = 'route_BackEnd_NhanVien_Detail';
        $requestData = $request->all();
        set_time_limit ( 30);
        $this->routeIndex = 'route_BackEnd_QuanLyDaoTao_danhSachBaiThiLai';
        if (!$request->hasFile('file')) {
            Session::push('errors', 'Vui lòng chọn tệp tải lên');
            return redirect()->route($this->routeIndex,['id'=>$requestData['idLichThi']]);
        }
        $file = $request->file('file');
        $endfile = $file->getClientOriginalExtension();
        if (!in_array($endfile, ['xls','xlsx'])) {
            Session::push('errors', 'Vui lòng chỉ chọn định dạng xls hoặc xlsx');
            return redirect()->route($this->routeIndex,['id'=>$requestData['idLichThi']]);
        }
        //save file
        $fileName = Auth::id().'_'.date('Y_m_d_H_i_s_'). $file->getClientOriginalName();
        $filePath = $file->storeAs('import_cham_cong/'.Auth::id(), $fileName, 'public');
        //import
        $reader = new Xlsx();
        $spreadsheet = $reader->load(__DIR__.'/../../../storage/app/public/'.$filePath);
        $lastRow =  $spreadsheet->getActiveSheet()->getHighestRow();
        if($lastRow > 1000)
        {
            Session::push('errors', 'File import bị lỗi');
            return redirect()->route($this->routeIndex);
        }
        $list = $spreadsheet->getActiveSheet()->rangeToArray('A2:D'.$lastRow);
        $arr = [];
        foreach ($list as $key=>$value){
            $arr[$key]['id'] = null;
            $arr[$key]['ngay_lam']= $value[0];
            $arr[$key]['gio_vao']= $value[1];
            $arr[$key]['gio_ra']= $value[2];
            if ($value[3]=='x'){
                $arr[$key]['trang_thai']= 1;
            }elseif ($value[3]=='o'){
                $arr[$key]['trang_thai']= 0;
            }
            $arr[$key]['id_nhan_vien']= $id;
            $arr[$key]['created_at'] = date('Y-m-d H:i:s');
            $arr[$key]['updated_at'] = date('Y-m-d H:i:s');
        }
        $res = DB::table('cham_cong')->insert($arr);
        if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
        {
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        } elseif ($res == 1) {
            $request->session()->forget('post_form_data'); // xóa data post
            Session::flash('success', 'Thêm lương thành công!');
            return redirect()->route($method_route, ['id' => $id]);
        } else {

            Session::push('errors', 'Lỗi cập nhật cho bản ghi: ' . $res);
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        }
    }


}
//