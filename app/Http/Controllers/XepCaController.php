<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

class XepCaController extends Controller
{
    private $v;

    public function __construct()
    {
        $this->v = [];
    }

    /**
     * Tự tạo 2 bảng dang_ky_ca + lich_ca nếu chưa tồn tại (DB-first, không dùng migration).
     * Giống CaLamController::ensureTables() (FEAT-001): CREATE TABLE IF NOT EXISTS + guard Schema::hasTable.
     * Lich_ca được bổ sung cột id_xep_boi (INT UNSIGNED NULL) để ghi admin nào xếp ca.
     * Với DB đã có lich_ca từ FEAT-001 (chưa có cột này) thì ALTER TABLE ADD COLUMN cho idempotent.
     */
    private function ensureTables()
    {
        if (!Schema::hasTable('dang_ky_ca')) {
            DB::statement("CREATE TABLE IF NOT EXISTS dang_ky_ca (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                id_nhan_vien INT UNSIGNED NOT NULL,
                ngay_lam DATE NOT NULL,
                ca VARCHAR(10) NOT NULL,
                ghi_chu VARCHAR(255) NULL,
                trang_thai TINYINT NOT NULL DEFAULT 1,
                created_at TIMESTAMP NULL DEFAULT NULL,
                updated_at TIMESTAMP NULL DEFAULT NULL,
                PRIMARY KEY (id),
                KEY idx_dang_ky_ca_nv (id_nhan_vien, ngay_lam, ca)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        }
        if (!Schema::hasTable('lich_ca')) {
            DB::statement("CREATE TABLE IF NOT EXISTS lich_ca (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                id_nhan_vien INT UNSIGNED NOT NULL,
                ngay_lam DATE NOT NULL,
                ca VARCHAR(10) NOT NULL,
                id_xep_boi INT UNSIGNED NULL DEFAULT NULL,
                created_at TIMESTAMP NULL DEFAULT NULL,
                updated_at TIMESTAMP NULL DEFAULT NULL,
                PRIMARY KEY (id),
                UNIQUE KEY unique_lich (id_nhan_vien, ngay_lam, ca)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        }
        // DB đã tạo lich_ca từ FEAT-001 (không có id_xep_boi) -> thêm cột để xếp ca thủ công ghi được ai xếp.
        if (Schema::hasTable('lich_ca') && !Schema::hasColumn('lich_ca', 'id_xep_boi')) {
            DB::statement("ALTER TABLE lich_ca ADD COLUMN id_xep_boi INT UNSIGNED NULL DEFAULT NULL AFTER ca");
        }
    }

    /**
     * Trang Xếp Ca (level=1): danh sách đăng ký chờ duyệt + form xếp ca thủ công + lịch ca tổng hợp tất cả cửa hàng.
     * Dùng date()/strtotime(), KHÔNG dùng Carbon.
     */
    public function xepCa(Request $request)
    {
        $this->ensureTables();
        $params = $request->all();
        $ngay = $params['ngay'] ?? date('Y-m-d');
        $idPhongBan = $params['id_phong_ban'] ?? null;

        // (b) Danh sách đăng ký ca chờ duyệt (trang_thai = 1), lọc theo cửa hàng + ngày.
        $query = DB::table('dang_ky_ca as dk')
            ->join('nhan_vien as nv', 'nv.id', '=', 'dk.id_nhan_vien')
            ->leftJoin('chuc_vu as cv', 'cv.id', '=', 'nv.id_chuc_vu')
            ->leftJoin('phong_ban as pb', 'pb.id', '=', 'nv.id_phong_ban')
            ->where('dk.trang_thai', 1);
        if ($idPhongBan) {
            $query->where('nv.id_phong_ban', $idPhongBan);
        }
        if (!empty($ngay)) {
            $query->where('dk.ngay_lam', $ngay);
        }
        $pending = $query->select(
                'dk.id', 'dk.id_nhan_vien', 'nv.ho_ten', 'cv.ten_chuc_vu',
                'pb.ten_phong_ban', 'pb.id as pb_id', 'dk.ngay_lam', 'dk.ca', 'dk.ghi_chu'
            )
            ->orderBy('dk.ngay_lam')
            ->orderBy('pb.ten_phong_ban')
            ->orderBy('cv.ten_chuc_vu')
            ->get();

        // (c) Danh sách nhân viên đang làm việc (để xếp ca thủ công).
        $employees = DB::table('nhan_vien')
            ->join('chuc_vu', 'chuc_vu.id', '=', 'nhan_vien.id_chuc_vu')
            ->join('phong_ban', 'phong_ban.id', '=', 'nhan_vien.id_phong_ban')
            ->where('nhan_vien.trang_thai', 1)
            ->select('nhan_vien.id', 'nhan_vien.ho_ten', 'chuc_vu.ten_chuc_vu', 'phong_ban.ten_phong_ban', 'phong_ban.id as pb_id')
            ->orderBy('phong_ban.ten_phong_ban')
            ->orderBy('chuc_vu.ten_chuc_vu')
            ->orderBy('nhan_vien.ho_ten')
            ->get();

        // Danh sách cửa hàng (cho bộ lọc).
        $stores = DB::table('phong_ban')->orderBy('id')->get();

        // (d) Lịch ca tổng hợp tất cả cửa hàng theo tuần.
        $start = $params['tu_ngay'] ?? date('Y-m-d');
        $dates = [];
        for ($i = 0; $i < 7; $i++) {
            $dates[] = date('Y-m-d', strtotime("$start +$i days"));
        }

        $rows = DB::table('lich_ca as lc')
            ->join('nhan_vien as nv', 'nv.id', '=', 'lc.id_nhan_vien')
            ->join('phong_ban as pb', 'pb.id', '=', 'nv.id_phong_ban')
            ->leftJoin('chuc_vu as cv', 'cv.id', '=', 'nv.id_chuc_vu')
            ->whereIn('lc.ngay_lam', $dates)
            ->select('lc.id', 'lc.ngay_lam', 'lc.ca', 'nv.ho_ten', 'cv.ten_chuc_vu', 'pb.ten_phong_ban')
            ->orderBy('lc.ngay_lam')
            ->orderBy('pb.ten_phong_ban')
            ->orderBy('cv.ten_chuc_vu')
            ->orderBy('nv.ho_ten')
            ->get();

        $grid = [];
        foreach ($dates as $d) {
            $grid[$d] = ['sang' => [], 'chieu' => []];
        }
        foreach ($rows as $row) {
            if (!isset($grid[$row->ngay_lam])) {
                continue;
            }
            $label = $row->ho_ten . ' @ ' . $row->ten_phong_ban . ($row->ten_chuc_vu ? ' (' . $row->ten_chuc_vu . ')' : '');
            $grid[$row->ngay_lam][$row->ca][] = (object) ['label' => $label, 'id' => $row->id];
        }

        return view('nhanvien.admin-xep-ca', compact('pending', 'employees', 'stores', 'grid', 'dates', 'params', 'start'));
    }

    /**
     * Duyệt (trang_thai=2) hoặc từ chối (trang_thai=3) một đăng ký ca.
     * Tách riêng khỏi xếp ca thủ công: đây là bước 2 của spec (đổi trạng thái dang_ky_ca).
     */
    public function duyetCa($id, Request $request)
    {
        $this->ensureTables();
        $data = $request->validate([
            'trang_thai' => ['required', 'in:2,3'],
        ], [
            'trang_thai.required' => 'Thiếu trạng thái duyệt.',
            'trang_thai.in' => 'Trạng thái duyệt không hợp lệ.',
        ]);

        $reg = DB::table('dang_ky_ca')->where('id', $id)->first();
        abort_unless($reg, 404);

        DB::table('dang_ky_ca')->where('id', $id)->update([
            'trang_thai' => $data['trang_thai'],
            'updated_at' => now(),
        ]);

        $msg = (int)$data['trang_thai'] === 2 ? 'Đã duyệt đăng ký ca.' : 'Đã từ chối đăng ký ca.';

        return redirect()->route('route_Admin_XepCa', $request->only('ngay', 'id_phong_ban', 'tu_ngay'))
            ->with('success', $msg);
    }

    /**
     * Xếp ca thủ công vào lich_ca (bước 3 của spec).
     * Ghi id_xep_boi = id user admin đang đăng nhập.
     */
    public function themLichCa(Request $request)
    {
        $this->ensureTables();
        $data = $request->validate([
            'id_nhan_vien' => ['required', 'integer', 'exists:nhan_vien,id'],
            'ngay_lam' => ['required', 'date'],
            'ca' => ['required', 'in:sang,chieu'],
        ], [
            'id_nhan_vien.required' => 'Vui lòng chọn nhân viên.',
            'id_nhan_vien.integer' => 'Nhân viên không hợp lệ.',
            'id_nhan_vien.exists' => 'Nhân viên không tồn tại.',
            'ngay_lam.required' => 'Vui lòng chọn ngày làm.',
            'ngay_lam.date' => 'Ngày làm không hợp lệ.',
            'ca.required' => 'Vui lòng chọn ca làm.',
            'ca.in' => 'Ca làm chỉ có thể là sáng hoặc chiều.',
        ]);

        $trung = DB::table('lich_ca')
            ->where('id_nhan_vien', $data['id_nhan_vien'])
            ->where('ngay_lam', $data['ngay_lam'])
            ->where('ca', $data['ca'])
            ->exists();

        if ($trung) {
            return redirect()->back()
                ->with('error', 'Nhân viên đã được xếp ca này.')
                ->withInput();
        }

        DB::table('lich_ca')->insert([
            'id_nhan_vien' => $data['id_nhan_vien'],
            'ngay_lam' => $data['ngay_lam'],
            'ca' => $data['ca'],
            'id_xep_boi' => $request->user()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('route_Admin_XepCa', $request->only('tu_ngay'))
            ->with('success', 'Đã xếp ca cho nhân viên.');
    }

    /**
     * Xóa một dòng lich_ca.
     */
    public function xoaLichCa($id, Request $request)
    {
        $this->ensureTables();
        $rec = DB::table('lich_ca')->where('id', $id)->first();
        abort_unless($rec, 404);

        DB::table('lich_ca')->where('id', $id)->delete();

        return redirect()->route('route_Admin_XepCa', $request->only('tu_ngay'))
            ->with('success', 'Đã xóa lịch ca.');
    }
}
