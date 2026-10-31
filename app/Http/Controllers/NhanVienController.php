<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;

use App\ChucVu;
use App\CongNhanVien;
use App\HeSoLuong;
use App\HeSoLuongNhanVien;
use App\Http\Requests\NhanVienRequest;
use App\Mail\EmaiLuong;
use App\Mail\TaiKhoan;
use App\NguoiDung;
use App\NhanVien;
use App\PhongBan;
use App\PhuCap;
use App\PhuCapNhanVien;
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
require_once __DIR__ . '/../../SLib/functions.php';
class NhanVienController extends Controller
{
    private $v;
    public function __construct()
    {
        $this->v = [];
    }

    public function danhSachNhanVien(Request $request){
        $this->v['_title'] = 'Danh sách nhân viên';
        $this->v['routeIndexText'] = 'Danh sách nhân viên';
        $objNhanVien = new NhanVien();
        //Nhận dữ liệu lọc từ view
        $this->v['extParams'] = $request->all();
        $this->v['list'] = $objNhanVien->loadListDSWithPager($this->v['extParams']);
        $this->v['countNV'] = $objNhanVien->loadCountNVCT();
        $objPhongBan = new PhongBan();
        $itemPhongBan = $objPhongBan->loadListIdAndName(['trang_thai',1]);
        $this->v['itemPhongBan'] = $itemPhongBan;
        $objChucVu = new ChucVu();
        $itemChucVu = $objChucVu->loadListIdAndName(['trang_thai',1]);
        $this->v['itemChucVu'] = $itemChucVu;
        $arrPhongBan = [];
        foreach ($itemPhongBan as $key => $value)
        {
            $arrPhongBan[$value->id]= $value->ten_phong_ban;
        }
        $this->v['arrPhongBan'] = $arrPhongBan ;
        $arrChucVu = [];
        foreach ($itemChucVu as $key => $values)
        {
            $arrChucVu[$values->id]= $values->ten_chuc_vu;
        }
        $this->v['arrChucVu'] = $arrChucVu ;
        return view('nhanvien.danh-sach-nhan-vien', $this->v);
    }
    public function chitietNhanVien($id, Request $request){
        $this->v['routeIndexText'] = 'Chi tiết thông tin nhân viên';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi tiết thông tin nhân viên';
        $this->v['extParams'] = $request->all();
        $objNhanVien = new  NhanVien();
        $objItem = $objNhanVien->loadOne($id);
        $this->v['objItem'] = $objItem;
        $objChucVu = new ChucVu();
        $this->v['objChucVu'] = $objChucVu->loadListIdAndName(['trang_thai',1]);
        $objPhongBan = new PhongBan();
        $this->v['objPhongBan'] = $objPhongBan->loadListIdAndName(['trang_thai', 1]);
        $objPhuCap = new PhuCap();
        $this->v['objPhuCap'] = $objPhuCap->loadListWithPager($this->v['extParams']);
        $objHeSoLuong = new HeSoLuong();
        $this->v['objHeSoLuong'] = $objHeSoLuong->loadListWithPager($this->v['extParams']);
        return view('nhanvien.chi-tiet-thong-tin-nhan-vien',$this->v);
    }
    public function chiTietNV($id, Request $request){
        $this->v['routeIndexText'] = 'Chi tiết thông tin nhân viên';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi tiết thông tin nhân viên';
        $this->v['extParams'] = $request->all();
        $objNhanVien = new  NhanVien();
        $objItem = $objNhanVien->loadOne($id);
        $this->v['objItem'] = $objItem;
        $objChucVu = new ChucVu();
        $this->v['objChucVu'] = $objChucVu->loadListIdAndName(['trang_thai',1]);
        $objPhongBan = new PhongBan();
        $this->v['objPhongBan'] = $objPhongBan->loadListIdAndName(['trang_thai', 1]);
        $objPhuCap = new PhuCap();
        $this->v['objPhuCap'] = $objPhuCap->loadListWithPager($this->v['extParams']);
        $objPhuCapNV = new PhuCapNhanVien();
        $this->v['objPhuCapNV']= $objPhuCapNV->loadPhuCapNV($id);
        $objHeSoLuong = new HeSoLuong();
        $this->v['objHeSoLuong'] = $objHeSoLuong->loadListWithPager($this->v['extParams']);
        $objHeSoNV = new HeSoLuongNhanVien();
        $this->v['objHeSoNV']= $objHeSoNV->loadHeSoNV($id);
        return view('nhanvien.chi-tiet-nhan-vien',$this->v);
    }
    public function chiTietThongTinNV($id, Request $request){
        $this->v['routeIndexText'] = 'Chi tiết thông tin nhân viên';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi tiết thông tin nhân viên';
        $this->v['extParams'] = $request->all();
        $objNhanVien = new  NhanVien();
        $objItem = $objNhanVien->loadOne($id);
        $this->v['objItem'] = $objItem;
        $objChucVu = new ChucVu();
        $this->v['objChucVu'] = $objChucVu->loadOne($objItem->id_chuc_vu);
        $objPhongBan = new PhongBan();
        $this->v['objPhongBan'] = $objPhongBan->loadOne($objItem->id_phong_ban);
        return view('nhanvien.fr-chi-tiet-thong-tin-nhan-vien',$this->v);
    }
    public function taiKhoanNhanVien($id, Request $request){
        $this->v['routeIndexText'] = 'Chi tiết thông tin tài khoản nhân viên';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi tiết thông tin tài khoản nhân viên';
        $this->v['extParams'] = $request->all();
        $objNhanVien = new  NhanVien();
        $objItem = $objNhanVien->loadOne($id);
        $objNguoiDung = new NguoiDung();
        $this->v['objItem'] = $objNguoiDung->loadOne($objItem->id_user);
        return view('nhanvien.fr-tai-khoan-nhan-vien',$this->v);
    }
    public function updateMatKhau($id, Request $request){
        $request->validate(['name'=>'required|string|max:255', 'password'=>'required|string|min:6', 'password1'=>'required|same:password']);
        $objNhanVien = new  NhanVien();
        $objItem = $objNhanVien->loadOne($id);
        if($request->password == $request->password1 ){
            $updateTT= DB::table('users')->where('id',$objItem->id_user)->limit(1)->update(['name'=>$request->name,'password' => Hash::make($request->password)]);
            if ($updateTT){
                Session::flash('success', 'Cập nhật thành công mật khẩu!');
                return redirect()->route('route_BackEnd_FrTaiKhoanNhanVien_List',['id'=>$id]);
            }
        }else{
            Session::flash('error', 'Mật khẩu chưa trùng khớp');
            return redirect()->route('route_BackEnd_FrTaiKhoanNhanVien_List',['id'=>$id]);
        }
    }
    private function uploadFile($file)
    {
        $fileName = time().'_'.$file->getClientOriginalName();
        return $file->storeAs('cmnd_cccd', $fileName, 'public');
    }
    public function updateNhanVien($id, NhanVienRequest $request){
        $method_route = 'route_BackEnd_ThongTinNhanVien_Detail';
        $modelNhanVien = new NhanVien();
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
        if (!preg_match("/^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i",$request->email)) {
            Session::flash('success', 'Email không chính xác');
            return redirect()->route('route_BackEnd_ThongTinNhanVien_Detail',['id'=>$id]);
        }elseif (!preg_match("/(84|0[3|5|7|8|9])+([0-9]{8})\b/",$request->so_dien_thoai)){

            Session::flash('success', 'Số điện thoại không chính xác');
            return redirect()->route('route_BackEnd_ThongTinNhanVien_Detail',['id'=>$id]);
        }else {
            $objNhanVien = new NhanVien();
            $itemcheck = $objNhanVien->loadOne($id);
            if($itemcheck->so_dien_thoai == $request->so_dien_thoai && $itemcheck->so_chung_minh == $request->so_chung_minh && $itemcheck->email == $request->email){
                $check = 4;
            }else{

                $itemNV = $objNhanVien->loadListWithPagerNV();
                foreach ($itemNV as $itemnv){
                    if ($itemnv->so_chung_minh == $request->so_chung_minh){
                        $check = 1;
                        break;
                    }elseif ($itemnv->so_dien_thoai == $request->so_dien_thoai){
                        $check = 2;
                        break;
                    }elseif ($itemnv->email == $request->email){
                        $check = 3;
                        break;
                    }else{
                        $check = 4;
                    }
                }
            }
            if (isset($check) && $check == 1){
                Session::flash('error', 'Số chứng minh thư này đã tồn tại. Vui lòng nhập số khác');
                return redirect()->route('route_BackEnd_ThongTinNhanVien_Detail',['id'=>$id]);
            }elseif (isset($check) && $check == 2){
                Session::flash('error', 'Số điện thoại này đã tồn tại. Vui lòng nhập số khác');
                return redirect()->route('route_BackEnd_ThongTinNhanVien_Detail',['id'=>$id]);
            }elseif (isset($check) && $check == 3){
                Session::flash('error', 'Email này đã tồn tại. Vui lòng nhập lại khác');
                return redirect()->route('route_BackEnd_ThongTinNhanVien_Detail',['id'=>$id]);
            }elseif (isset($check) && $check == 4) {
                if ($request->hasFile('anh_cmt_sau') && $request->file('anh_cmt_sau')->isValid()) {
                    $params['cols']['anh_cmt_sau'] = $this->uploadFile($request->file('anh_cmt_sau'));
                }
                if ($request->hasFile('anh_cmt_truoc') && $request->file('anh_cmt_truoc')->isValid()) {
                    $params['cols']['anh_cmt_truoc'] = $this->uploadFile($request->file('anh_cmt_truoc'));
                }

                unset($params['cols']['_token']);
                $taiKhoan = [];
                $taiKhoan['name'] = $request->ho_ten;
                $taiKhoan['email'] = $request->email;
                $taiKhoan['password'] = '123456';
                $objUser = new NguoiDung();
                $existingAccount = DB::table('users')->where('email', $request->email)->first();
                if ($existingAccount) {
                    abort_unless((int)$existingAccount->level === 2 && !DB::table('nhan_vien')->where('id_user', $existingAccount->id)->where('id', '!=', $id)->exists(), 422);
                    $taoTaiKhoan = $existingAccount->id;
                } else {
                    $taoTaiKhoan = $objUser->saveNVNew($taiKhoan);
                    $Guiemail = $objUser->loadOne($taoTaiKhoan);
                    Mail::to($request->email)->send(new TaiKhoan($Guiemail));
                }
                $params['cols']['id_user'] = $taoTaiKhoan;

                $objItem = $modelNhanVien->loadOne($id);
                if (empty($objItem)) {
                    Session::push('errors', 'Không tồn tại người dùng này ' . $id);
                    return redirect()->route('route_BackEnd_NguoiDung_index');
                }
                $params['cols']['id'] = $id;

                $res = $modelNhanVien->saveUpdate($params);
                $arrPhuCap = [];
                $i = 0;
                foreach ($request->id_phu_cap as $item) {
                    $arrPhuCap['id'] = null;
                    $arrPhuCap['id_nhan_vien'] = $id;
                    $arrPhuCap['id_phu_cap'] = $item;
                    $arrPhuCap['trang_thai'] = 1;
                    $result = DB::table('phu_cap_nhan_vien')->insert($arrPhuCap);
                    $i++;
                }

                $arrHeSoNV = [];
                $arrHeSoNV['id'] = null;
                $arrHeSoNV['id_he_so'] = $request->id_he_so_luong;
                $arrHeSoNV['id_nhan_vien'] = $id;
                $arrHeSoNV['ghi_chu'] = null;
                $arrHeSoNV['trang_thai'] = 1;
                $arrHeSoNV['created_at'] = date('Y-m-d H:i:s');
                $HSLNV = DB::table('nhan_vien_he_so')->insert($arrHeSoNV);
                if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
                {
//            Session::push('post_form_data', $this->v['request']);
                    return redirect()->route($method_route, ['id' => $id]);
                } elseif ($res == 1 || $result == 1 || $HSLNV == 1) {
                    $request->session()->forget('post_form_data'); // xóa data post
                    Session::flash('success', 'Cập nhật thành công nhân viên!');
                    return redirect()->route('route_BackEnd_DanhSachNhanVien_index');
                } else {

                    Session::push('errors', 'Lỗi cập nhật cho bản ghi: ' . $res);
                    Session::push('post_form_data', $this->v['request']);
                    return redirect()->route($method_route, ['id' => $id]);
                }
            }
        }
    }
    public function updateNV($id, NhanVienRequest $request){
        $method_route = 'route_BackEnd_AdminNhanVien_Detail';
        $modelNhanVien = new NhanVien();
        $res = DB::table('nhan_vien')->where('id',$id)->limit(1)->update(['id_phong_ban'=>$request->id_phong_ban,'id_chuc_vu'=>$request->id_chuc_vu]);
        $objPCNV= DB::table('phu_cap_nhan_vien')->where('id_nhan_vien',$id)->delete();
        $arrPhuCap = [];
        $i=0;
        foreach ($request->id_phu_cap as $item)
        {
            $arrPhuCap['id'] = null;
            $arrPhuCap['id_nhan_vien'] = $id;
            $arrPhuCap['id_phu_cap'] = $item;
            $arrPhuCap['trang_thai'] = 1;
            $result = DB::table('phu_cap_nhan_vien')->insert($arrPhuCap);
            $i++;
        }
        $objHeSoNV = new HeSoLuongNhanVien();
        $check = $objHeSoNV->loadOne($id);
        if($check->id_he_so != $request->id_he_so_luong){

            $updateHSLNV = DB::table('nhan_vien_he_so')->where('id',$check->id)->limit(1)->update(['trang_thai'=>0,'updated_at'=>date('Y-m-d H:i:s')]);
            $arrHeSoNV = [];
            $arrHeSoNV['id'] = null;
            $arrHeSoNV['id_he_so'] = $request->id_he_so_luong;
            $arrHeSoNV['id_nhan_vien'] = $id;
            $arrHeSoNV['ghi_chu'] = null;
            $arrHeSoNV['trang_thai'] = 1;
            $arrHeSoNV['created_at']= date('Y-m-d H:i:s');
            $HSLNV = DB::table('nhan_vien_he_so')->insertGetId($arrHeSoNV);
        }
        if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
        {
//            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        } elseif ($res == 1) {
            $request->session()->forget('post_form_data'); // xóa data post
            Session::flash('success', 'Cập nhật thành công thông tin nhân viên thành công!');
            return redirect()->route('route_BackEnd_DanhSachNhanVien_index');
        } else {

            Session::push('errors', 'Lỗi cập nhật cho bản ghi: ' . $res);
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        }
    }

    public function themNhanVienMoi(NhanVienRequest $request){
        $this->v['routeIndexText'] = 'Thêm Nhân Viên Mới';
        $method_route = 'route_BackEnd_DanhSachNhanVienMoi_index';
        $this->v['_action'] = 'Add';
        $this->v['_title'] = 'Thêm Nhân Viên Mới';
        if($request->isMethod('post')) {
            $params = [
                'nhanvienmoi_add' => Auth::user()->id
            ];
            $params['cols'] = array_map(function ($item) {
                if ($item == '')
                    $item = null;
                if (is_string($item))
                    $item = trim($item);
                return $item;
            }, $request->post());
            if (!preg_match("/^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i", $request->email)) {
                Session::flash('error', 'Email không chính xác');
                return redirect()->route('route_BackEnd_NhanVienMoi_Add');
            } elseif (!preg_match("/(84|0[3|5|7|8|9])+([0-9]{8})\b/", $request->so_dien_thoai)) {
                Session::flash('error', 'Số điện thoại không chính xác');
                return redirect()->route('route_BackEnd_NhanVienMoi_Add');
            }else {
                $objNhanVien = new NhanVien();
                $itemNV = $objNhanVien->loadListWithPagerNV();
                foreach ($itemNV as $itemnv){
                    if ($itemnv->so_chung_minh == $request->so_chung_minh){
                            $check = 1;
                            break;
                    }elseif ($itemnv->so_dien_thoai == $request->so_dien_thoai){
                            $check = 2;
                            break;
                    }elseif ($itemnv->email == $request->email){
                            $check = 3;
                            break;
                    }
                }
                if (isset($check) && $check == 1){
                    Session::flash('error', 'Số chứng minh thư này đã tồn tại. Vui lòng nhập số khác');
                    return redirect()->route('route_BackEnd_NhanVienMoi_Add');
                }elseif (isset($check) && $check == 2){
                    Session::flash('error', 'Số điện thoại này đã tồn tại. Vui lòng nhập số khác');
                    return redirect()->route('route_BackEnd_NhanVienMoi_Add');
                }elseif (isset($check) && $check == 3){
                    Session::flash('error', 'Email này đã tồn tại. Vui lòng nhập lại khác');
                    return redirect()->route('route_BackEnd_NhanVienMoi_Add');
                }else{
                    if ($request->hasFile('anh_cmt_sau') && $request->file('anh_cmt_sau')->isValid()) {
                        $params['cols']['anh_cmt_sau'] = $this->uploadFile($request->file('anh_cmt_sau'));
                    }
                    if ($request->hasFile('anh_cmt_truoc') && $request->file('anh_cmt_truoc')->isValid()) {
                        $params['cols']['anh_cmt_truoc'] = $this->uploadFile($request->file('anh_cmt_truoc'));
                    }
                    unset($params['cols']['_token']);

                    $res = $objNhanVien->saveNVNew($params);

                    if ($res == null) {
                        Session::push('post_form_data', $this->v['request']);
                        return redirect()->route($method_route);
                    } elseif ($res > 0) {
                        $this->v['request'] = [];
                        $request->session()->forget('post_from_data');
                        Session::flash('success', 'Thêm mới thành công nhân viên mới');
                        return redirect()->route('route_BackEnd_DanhSachNhanVienMoi_index');
                    } else {
                        Session::push('errors', 'Lỗi thêm mới' . $res);
                        Session::push('post_form_data', $this->v['request']);
                        return redirect()->route($method_route);
                    }
                }
            }
        }

        return view('nhanvien.them-nhan-vien-moi', $this->v);
    }
    public function deleteNhanVien($id,Request $request){
        $objNhanVien = new NhanVien();
        $list = $objNhanVien->loadOne($id);
        $updateNhanVien = $objNhanVien->deleteNV($id);
        $objHSLNV = new HeSoLuongNhanVien();
        $HSLNV = $objHSLNV->deleteHSLNV($id);
        $objPCNV = new PhuCapNhanVien();
        $PCNV = $objPCNV->deletePCNV($id);
        $objNguoiDung = new NguoiDung();
        $updateNguoiDung = $objNguoiDung->deleteTKNV($list->id_user);
        if ($updateNhanVien >0){
            $this->v['request'] = [];
            $request->session()->forget('post_from_data');
            Session::flash('success', 'Xoá thành công nhân viên');
            return redirect()->route('route_BackEnd_DanhSachNhanVien_index');
        }
    }

    public function danhSachNhanVienMoi(Request $request){
        $this->v['_title'] = 'Danh sách nhân viên mới';
        $this->v['routeIndexText'] = 'Danh sách nhân viên mới';
        $objNhanVien = new NhanVien();
        $this->v['countNV'] = $objNhanVien->loadCountNVM();
        //Nhận dữ liệu lọc từ view
        $this->v['extParams'] = $request->all();
        $this->v['list'] = $objNhanVien->loadListDSMWithPager($this->v['extParams']);

        return view('nhanvien.danh-sach-nhan-vien-moi', $this->v);
    }
    public function inDanhSachNhanVien($id)
    {
        $objPB = new PhongBan();
        $checkPB = $objPB->loadListWithPager();
        foreach ($checkPB as $item) {
            if ($item->id == $id) {
                $flag =1;
                break;
            }
        }
        if(isset($flag)){
            $dataNhans = DB::table('nhan_vien as NV')
                ->select('NV.id', 'NV.ho_ten', 'NV.ngay_sinh', 'NV.gioi_tinh', 'NV.so_dien_thoai', 'NV.email', 'NV.dia_chi', 'CV.ten_chuc_vu', 'NV.ngay_gia_nhap')
                ->leftJoin('chuc_vu as CV', 'CV.id', '=', 'NV.id_chuc_vu')
                ->where('NV.id_phong_ban', $id)->where('NV.trang_thai', '=', 1)->get();
            $tenPhong = DB::table('phong_ban as PB')
                ->select('PB.id', 'PB.ten_phong_ban')
                ->where('PB.id', $id)->first();
            $pdf = PDF::setOptions([
                'logOutputFile' => storage_path('logs/log.htm'),
                'tempDir' => storage_path('logs/')
            ])
                ->loadView('print.danhsachnhanvien', compact('dataNhans', 'tenPhong'))->setPaper('a4');
            return $pdf->stream();
        }else{
            Session::flash('error', 'In danh sách nhân viên không hợp lệ');
            return redirect()->route('route_BackEnd_PhongBan_index');
        }



    }
    public function frNhanVien(Request $request){
        $this->v['_title'] = 'Danh sách nhân viên mới';
        $this->v['routeIndexText'] = 'Danh sách nhân viên mới';
        $objNhanVien = new NhanVien();
        //Nhận dữ liệu lọc từ view
        $this->v['extParams'] = $request->all();
        $this->v['list'] = $objNhanVien->loadListDSMWithPager($this->v['extParams']);

        return view('nhanvien.fr-nhan-vien', $this->v);
    }
    public function deleteNhanVienMoi($id, Request $request){
        $deleteData = DB::table('nhan_vien')->where('id','=',$id)->delete();
        if ($deleteData){
            Session::flash('success', 'Xóa dữ liệu thành công');
            return redirect(route('route_BackEnd_DanhSachNhanVienMoi_index'));
        }
    }
    public function frChamCongNhanVien($id, Request $request){
        $this->routeIndex = 'route_BackEnd_CongNhanVien_List';
        $this->v['_title'] = 'Danh sách công của nhân viên';
        $this->v['routeIndexText'] = 'Danh sách công của nhân viên';
        $objCongNV = new CongNhanVien();
        $this->v['extParams'] = $request->all();
        if (isset($this->v['extParams']['search_ngay_tinh_luong'])) {
            $ngaythem = explode(' - ', $this->v['extParams']['search_ngay_tinh_luong']);
            if (count($ngaythem) != 2) {
                Session::flash('error', 'Ngày tính luong không hợp lệ');
                return redirect()->route($this->routeIndex,['id'=>$id]);
            }
            $datetime = array_map('convertDateToSql', $ngaythem);
            $datetime[0] = $datetime[0] . ' 00:00:00';
            $datetime[1] = $datetime[1] . ' 23:59:59';
            $this->v['extParams']['search_ngay_tinh_luong_array'] = $datetime;
        }
        $congAll = $objCongNV->loadListWithPager($id,$this->v['extParams']);
        $this->v['list'] = $congAll;
        $this->v['maxngay'] = $objCongNV->loadOne($id);
        return view('nhanvien.cham-cong-nhan-vien', $this->v);
    }
    public function danhSachCong($id, Request $request){
        $this->v['_title'] = 'Danh sách công của nhân viên';
        $this->v['routeIndexText'] = 'Danh sách công của nhân viên';
        $objCongNV = new CongNhanVien();
        $this->v['extParams'] = $request->all();
        if (isset($this->v['extParams']['search_ngay_tinh_luong'])) {
            $ngaythem = explode(' - ', $this->v['extParams']['search_ngay_tinh_luong']);
            if (count($ngaythem) != 2) {
                Session::flash('error', 'Ngày tính luong không hợp lệ');
                return redirect()->route($this->routeIndex);
            }
            $datetime = array_map('convertDateToSql', $ngaythem);
            $datetime[0] = $datetime[0] . ' 00:00:00';
            $datetime[1] = $datetime[1] . ' 23:59:59';
            $this->v['extParams']['search_ngay_tinh_luong_array'] = $datetime;
        }
        $this->v['list'] = $objCongNV->loadListWithPager($id,$this->v['extParams']);
        if (isset($_GET['btnXemTruoc'])){

            $dataNhans = DB::table('nhan_vien as tb1')
                ->select('tb1.ho_ten', 'tb1.so_dien_thoai', 'tb1.so_chung_minh','tb1.email','tb2.ten_chuc_vu','tb3.ten_phong_ban')
                ->leftJoin('chuc_vu as tb2', 'tb2.id', '=', 'tb1.id_chuc_vu')
                ->leftJoin('phong_ban as tb3', 'tb3.id', '=', 'tb1.id_phong_ban')
                ->where('tb1.id',$id)->first();

            $luong = DB::table('nhan_vien_he_so as tb1')
                ->select('tb1.trang_thai','tb1.id', 'tb1.id_he_so','tb1.id_nhan_vien','tb2.ten_he_so','tb2.luong')
                ->leftJoin('he_so_luong as tb2', 'tb2.id', '=', 'tb1.id_he_so')
                ->where('tb1.trang_thai', '=', 1)
                ->where('tb1.id_nhan_vien',$id)->first();
            $objPhuCap = new PhuCap();
            $itemPhuCap = $objPhuCap->loadOneID($id);
            $ngayCong = $this->v['list'];
            $pdf = PDF::setOptions([
                'logOutputFile' => storage_path('logs/log.htm'),
                'tempDir' => storage_path('logs/')
            ])
                ->loadView('print.luong_nhan_vien', compact('dataNhans','luong', 'ngayCong','itemPhuCap'))->setPaper('a4');
            return $pdf->stream();

        }
        if (isset($_GET['btnGuiGmail'])){
            $thongTinNhanVien = DB::table('nhan_vien as tb1')
                ->select('tb1.ho_ten','tb1.dia_chi as phuCap' , 'tb1.so_dien_thoai as tenHeSo', 'tb1.so_chung_minh as ngayDau','tb1.email','tb1.id_user as tangCa','tb1.gioi_tinh as ngayCuoi','tb2.id as ngayLam','tb3.id as ngayNua','tb1.id_chuc_vu as heSoLuong','tb1.trang_thai as tienPhuCap','tb2.ten_chuc_vu','tb3.ten_phong_ban')
                ->leftJoin('chuc_vu as tb2', 'tb2.id', '=', 'tb1.id_chuc_vu')
                ->leftJoin('phong_ban as tb3', 'tb3.id', '=', 'tb1.id_phong_ban')
                ->where('tb1.id',$id)->first();

            $email =$thongTinNhanVien->email;

            $luong = DB::table('nhan_vien_he_so as tb1')
                ->select('tb1.trang_thai','tb1.id', 'tb1.id_he_so','tb1.id_nhan_vien','tb2.ten_he_so','tb2.luong')
                ->leftJoin('he_so_luong as tb2', 'tb2.id', '=', 'tb1.id_he_so')
                ->where('tb1.trang_thai', '=', 1)
                ->where('tb1.id_nhan_vien',$id)->first();

            $itemPhuCap = $query = DB::table('phu_cap as tb1')
                ->select( 'tb1.id', 'tb1.ten_phu_cap','tb1.gia_tien', 'tb1.trang_thai')
                ->leftJoin('phu_cap_nhan_vien as tb2','tb2.id_phu_cap','=','tb1.id')
                ->where('tb2.id_nhan_vien', '=', $id)->get();
            $ngayCong = $this->v['list'];
            $id=0;
            $ngaylam=0;
            $tangca=0;
            $nuangay =1;
            $arrNgay= [];
            foreach ($ngayCong as  $item){
                $arrNgay[$id] = $item->ngay_lam;
                if((gmdate("H", ((strtotime($item->gio_ra) - strtotime('17:00:00')))) * 60) + gmdate("i", ((strtotime($item->gio_ra) - strtotime('17:00:00'))))>0){
                    $tangca+=((gmdate("H", ((strtotime($item->gio_ra) - strtotime('17:00:00')))) * 60) + gmdate("i", ((strtotime($item->gio_ra) - strtotime('17:00:00')))));
                }
                if (strtotime('09:00:00')< strtotime($item->gio_vao)){
                    $nuangay++;
                }
                $ngaylam++;
                $id++;
            }
            $tienPhuCap = 0;
            foreach ($itemPhuCap as $item){
                $tienPhuCap+= $item->gia_tien;
            }
            $thongTinNhanVien->ngayLam=$ngaylam;
            $thongTinNhanVien->ngayNua = $nuangay;
            $thongTinNhanVien->heSoLuong = $luong->luong;
            $thongTinNhanVien->tenHeSo = $luong->ten_he_so;
            $thongTinNhanVien->ngayDau= date("d/m/Y", strtotime($arrNgay[$id-1]));
            $thongTinNhanVien->ngayCuoi= date("d/m/Y", strtotime($arrNgay[0]));
            $thongTinNhanVien->phuCap = $itemPhuCap;
            $thongTinNhanVien->tangCa = $tangca;
            $thongTinNhanVien->tienPhuCap = $tienPhuCap;
            Mail::to($email)->send(new EmaiLuong($thongTinNhanVien));
        }
        return view('nhanvien.tinh-luong-nhan-vien', $this->v);
    }
}