<?php
namespace App\Http\Controllers;

use App\CongNhanVien;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ChamCongController extends Controller
{
    private $v;

    public function __construct()
    {
        $this->v = [];
    }

    public function themCongNgay($id, Request $request){
        $exists = DB::table('cham_cong')
            ->where('id_nhan_vien', $id)
            ->where('ngay_lam', date('Y-m-d'))
            ->exists();

        if ($exists) {
            Session::flash('error', 'Bạn đã chấm công hôm nay rồi!');
            return redirect()->route('route_BackEnd_CongNhanVien_List', ['id' => $id]);
        }

        DB::table('cham_cong')->insert([
            'id_nhan_vien' => $id,
            'ngay_lam'     => date('Y-m-d'),
            'gio_vao'      => date('H:i:s'),
            'gio_ra'       => '00:00:00',
            'trang_thai'   => 1,
            'created_at'   => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);

        Session::flash('success', 'Chấm công vào ca thành công!');
        return redirect()->route('route_BackEnd_CongNhanVien_List', ['id' => $id]);
    }

    public function chamRaVe($id, Request $request){
        $objCongNhanVien = new CongNhanVien();
        $objIDNV = $objCongNhanVien->loadOneID($id);

        if (!$objIDNV) {
            Session::flash('error', 'Không tìm thấy bản ghi!');
            return redirect()->back();
        }

        $udateGioVe = [];
        $udateGioVe['id']     = $id;
        $udateGioVe['gio_ra'] = date('H:i:s');

        $update = $objCongNhanVien->saveUpdateRaVe($udateGioVe);

        if (!$update) {
            return response()->json(['errors' => 'Lỗi cập nhật'], 500);
        }

        Session::flash('success', 'Chấm công ra về thành công!');
        return redirect()->route('route_BackEnd_CongNhanVien_List', ['id' => $objIDNV->id_nhan_vien]);
    }

    public function updateGioKetCa($id, Request $request){
        $objCongNhanVien = new CongNhanVien();
        $objCC = $objCongNhanVien->loadOneID($id);

        if (!$objCC) {
            Session::flash('error', 'Không tìm thấy bản ghi chấm công!');
            return redirect()->back();
        }

        $gioVao = strtotime($objCC->gio_vao);
        $gioRa  = strtotime($request->gio_ra . ':00');

        if ($gioRa <= $gioVao) {
            Session::flash('error', 'Giờ kết ca phải sau giờ vào ca (' . date('H:i', $gioVao) . ')!');
            return redirect()->route('route_BackEnd_CongNhanVien_List', ['id' => $objCC->id_nhan_vien]);
        }

        DB::table('cham_cong')
            ->where('id', $id)
            ->update([
                'gio_ra'     => $request->gio_ra . ':00',
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        Session::flash('success', 'Kết ca thành công lúc ' . $request->gio_ra . '!');
        return redirect()->route('route_BackEnd_CongNhanVien_List', ['id' => $objCC->id_nhan_vien]);
    }

    public function adminChamCong(Request $request){
        $extParams = $request->all();
        $ngay = isset($extParams['ngay']) ? $extParams['ngay'] : date('Y-m-d');

        $list = DB::table('cham_cong as tb1')
            ->select('tb1.id', 'tb1.id_nhan_vien', 'tb1.ngay_lam', 'tb1.gio_vao', 'tb1.gio_ra', 'tb1.trang_thai',
                     'tb2.ho_ten', 'tb3.ten_chuc_vu', 'tb4.ten_phong_ban')
            ->leftJoin('nhan_vien as tb2', 'tb2.id', '=', 'tb1.id_nhan_vien')
            ->leftJoin('chuc_vu as tb3', 'tb3.id', '=', 'tb2.id_chuc_vu')
            ->leftJoin('phong_ban as tb4', 'tb4.id', '=', 'tb2.id_phong_ban')
            ->where('tb1.ngay_lam', $ngay)
            ->orderBy('tb4.ten_phong_ban')
            ->orderBy('tb3.ten_chuc_vu')
            ->orderBy('tb1.gio_vao')
            ->get();

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
}
