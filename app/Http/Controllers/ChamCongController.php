<?php
namespace App\Http\Controllers;

use App\CongNhanVien;
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
class ChamCongController extends Controller
{
    private $v;

    public function __construct()
    {
        $this->v = [];
    }
    public function themCongNgay($id,Request $request){
        $arrChamCong = [];
        $arrChamCong['id'] = null;
        $arrChamCong['id_nhan_vien'] = $request->id;
        $arrChamCong['ngay_lam'] = date('Y-m-d');
        $arrChamCong['gio_vao'] = date('H:i:s');
        $arrChamCong['gio_ra'] = 'null';
        $arrChamCong['trang_thai'] = 1;
        $arrChamCong['created_at'] = date('Y-m-d H:i:s');
        $arrChamCong['updated_at'] = date('Y-m-d H:i:s');
        $result = DB::table('cham_cong')->insert($arrChamCong) ;
        if(!$result){
            return response()->json(['errors'=>Session::exists('errors')?Session::pull('errors'):'Lỗi thêm mới'],500);
        }else{

                Session::flash('success', 'Chấm công thành công!');
                return redirect()->route('route_BackEnd_CongNhanVien_List', ['id' => $id]);

        }
    }
    public function adminChamCong(Request $request){
        $extParams = $request->all();

        // Lọc theo ngày, mặc định hôm nay
        $ngay = isset($extParams['ngay']) ? $extParams['ngay'] : date('Y-m-d');

        $list = DB::table('cham_cong as tb1')
            ->select(
                'tb1.id', 'tb1.id_nhan_vien', 'tb1.ngay_lam',
                'tb1.gio_vao', 'tb1.gio_ra', 'tb1.trang_thai',
                'tb2.ho_ten', 'tb3.ten_chuc_vu', 'tb4.ten_phong_ban'
            )
            ->leftJoin('nhan_vien as tb2', 'tb2.id', '=', 'tb1.id_nhan_vien')
            ->leftJoin('chuc_vu as tb3', 'tb3.id', '=', 'tb2.id_chuc_vu')
            ->leftJoin('phong_ban as tb4', 'tb4.id', '=', 'tb2.id_phong_ban')
            ->where('tb1.ngay_lam', $ngay)
            ->orderBy('tb4.ten_phong_ban')
            ->orderBy('tb3.ten_chuc_vu')
            ->orderBy('tb1.gio_vao')
            ->get();

        // Lấy danh sách nhân viên chưa chấm công hôm nay
        $daChamIds = $list->pluck('id_nhan_vien')->toArray();
        $chuaCham = DB::table('nhan_vien as tb1')
            ->select('tb1.id', 'tb1.ho_ten', 'tb2.ten_chuc_vu', 'tb3.ten_phong_ban')
            ->leftJoin('chuc_vu as tb2', 'tb2.id', '=', 'tb1.id_chuc_vu')
            ->leftJoin('phong_ban as tb3', 'tb3.id', '=', 'tb1.id_phong_ban')
            ->where('tb1.trang_thai', 1)
            ->whereNotIn('tb1.id', $daChamIds)
            ->orderBy('tb3.ten_phong_ban')
            ->get();

        return view('nhanvien.admin-cham-cong', compact('list', 'ngay', 'extParams', 'chuaCham'));
    }

    public function chamRaVe($id, Request $request){
        $objCongNhanVien = new CongNhanVien();
        $objIDNV = $objCongNhanVien->loadOneID($id);
        $udateGioVe =[];
        $udateGioVe['id'] = $request->id;
        $udateGioVe['gio_ra'] =  date('H:i:s');
        $objCongNhanVien = new CongNhanVien();
        $update = $objCongNhanVien->saveUpdateRaVe($udateGioVe);
        if(!$update){
            return response()->json(['errors'=>Session::exists('errors')?Session::pull('errors'):'Lỗi thêm mới'],500);
        }else{

            Session::flash('success', 'Châm công ra về thành công!');
            return redirect()->route('route_BackEnd_CongNhanVien_List', ['id' => $objIDNV->id_nhan_vien]);

        }
    }

}