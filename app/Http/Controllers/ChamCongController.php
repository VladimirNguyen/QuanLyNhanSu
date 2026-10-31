<?php
namespace App\Http\Controllers;

use App\CongNhanVien;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
class ChamCongController extends Controller
{
    private $v;

    public function __construct()
    {
        $this->v = [];
    }
    public function themCongNgay($id,Request $request){
        DB::transaction(function () use ($id) {
            $employee = DB::table('nhan_vien')->where('id', $id)->lockForUpdate()->first();
            abort_unless($employee, 404);
            if (DB::table('cham_cong')->where('id_nhan_vien', $id)->where('ngay_lam', date('Y-m-d'))->exists()) return;
            DB::table('cham_cong')->insert(['id_nhan_vien'=>$id, 'ngay_lam'=>date('Y-m-d'), 'gio_vao'=>date('H:i:s'), 'gio_ra'=>null, 'trang_thai'=>1, 'created_at'=>now(), 'updated_at'=>now()]);
        });
        return redirect()->route('route_BackEnd_CongNhanVien_List', ['id'=>$id])->with('success', 'Đã ghi nhận chấm công hôm nay.');
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

    public function updateAttendance($id, Request $request)
    {
        $record = DB::table('cham_cong')->where('id', $id)->first();
        abort_unless($record, 404);

        foreach (['gio_vao', 'gio_ra'] as $field) {
            $value = $request->input($field);
            if (is_string($value) && preg_match('/^\d{2}:\d{2}$/D', $value)) {
                $request->merge([$field => $value . ':00']);
            }
        }

        $data = $request->validate([
            'gio_vao' => ['required', 'date_format:H:i:s'],
            'gio_ra' => ['nullable', 'date_format:H:i:s', 'after:gio_vao'],
        ], [
            'gio_vao.required' => 'Vui lòng nhập giờ vào ca.',
            'gio_vao.date_format' => 'Giờ vào phải có định dạng HH:mm:ss.',
            'gio_ra.date_format' => 'Giờ ra phải có định dạng HH:mm:ss.',
            'gio_ra.after' => 'Giờ ra phải sau giờ vào ca.',
        ]);

        DB::table('cham_cong')->where('id', $id)->update([
            'gio_vao' => $data['gio_vao'],
            'gio_ra' => $data['gio_ra'] ?? null,
            'updated_at' => now(),
        ]);

        return redirect()->route('route_BackEnd_Admin_ChamCong', ['ngay' => $record->ngay_lam])
            ->with('success', 'Đã cập nhật giờ chấm công.');
    }

    public function chamRaVe($id, Request $request){
        $objCongNhanVien = new CongNhanVien();
        $objIDNV = $objCongNhanVien->loadOneID($id);
        abort_unless($objIDNV && $objIDNV->ngay_lam === date('Y-m-d'), 422);
        if ($objIDNV->gio_ra && $objIDNV->gio_ra !== '00:00:00') return redirect()->back()->with('error', 'Đã chấm công ra về.');
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