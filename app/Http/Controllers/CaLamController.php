<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

class CaLamController extends Controller
{
    private $v;

    public function __construct()
    {
        $this->v = [];
    }

    /**
     * Tự tạo 2 bảng dang_ky_ca + lich_ca nếu chưa tồn tại (DB-first, không dùng migration).
     * DDL giống hệt XepCaController::ensureTables() (FEAT-002): lich_ca có cột id_xep_boi
     * (do admin xếp ca thủ công ghi), kèm ALTER TABLE ADD COLUMN id_xep_boi (guard
     * Schema::hasColumn) cho DB đã tạo lich_ca trước đó thiếu cột -> idempotent.
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
        // DB đã tạo lich_ca trước đó (không có id_xep_boi) -> thêm cột để cùng schema.
        if (Schema::hasTable('lich_ca') && !Schema::hasColumn('lich_ca', 'id_xep_boi')) {
            DB::statement("ALTER TABLE lich_ca ADD COLUMN id_xep_boi INT UNSIGNED NULL DEFAULT NULL AFTER ca");
        }
    }

    /**
     * Danh sách đăng ký ca của nhân viên (level=2).
     */
    public function dangKyCaList($id, Request $request)
    {
        $this->ensureTables();
        $list = DB::table('dang_ky_ca')
            ->where('id_nhan_vien', $id)
            ->orderBy('ngay_lam', 'desc')
            ->get();
        return view('nhanvien.dang-ky-ca', compact('list', 'id'));
    }

    /**
     * Lưu đăng ký ca mới (chỉ từ hôm nay trở đi, không trùng ca đã đăng ký/đã xếp).
     */
    public function storeDangKyCa($id, Request $request)
    {
        $this->ensureTables();
        $data = $request->validate([
            'ngay_lam' => ['required', 'date', 'after_or_equal:today'],
            'ca' => ['required', 'in:sang,chieu'],
            'ghi_chu' => ['nullable', 'string', 'max:255'],
        ], [
            'ngay_lam.required' => 'Vui lòng chọn ngày làm.',
            'ngay_lam.date' => 'Ngày làm không hợp lệ.',
            'ngay_lam.after_or_equal' => 'Chỉ được đăng ký ca từ hôm nay trở đi.',
            'ca.required' => 'Vui lòng chọn ca làm.',
            'ca.in' => 'Ca làm chỉ có thể là sáng hoặc chiều.',
            'ghi_chu.max' => 'Ghi chú không vượt quá 255 ký tự.',
        ]);

        $trungDangKy = DB::table('dang_ky_ca')
            ->where('id_nhan_vien', $id)
            ->where('ngay_lam', $data['ngay_lam'])
            ->where('ca', $data['ca'])
            ->exists();
        $trungLichCa = DB::table('lich_ca')
            ->where('id_nhan_vien', $id)
            ->where('ngay_lam', $data['ngay_lam'])
            ->where('ca', $data['ca'])
            ->exists();

        if ($trungDangKy || $trungLichCa) {
            return redirect()->back()
                ->with('error', 'Bạn đã đăng ký hoặc đã được xếp ca này trong ngày này.')
                ->withInput();
        }

        DB::table('dang_ky_ca')->insert([
            'id_nhan_vien' => $id,
            'ngay_lam' => $data['ngay_lam'],
            'ca' => $data['ca'],
            'ghi_chu' => $data['ghi_chu'] ?? null,
            'trang_thai' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('route_DangKyCa_List', ['id' => $id])
            ->with('success', 'Đã gửi đăng ký ca làm.');
    }

    /**
     * Xóa đăng ký ca khi còn chờ duyệt (trang_thai=1).
     * Route {id} là id của bản ghi dang_ky_ca (giống chamRaVe dùng id cham_cong).
     */
    public function deleteDangKyCa($id, Request $request)
    {
        $this->ensureTables();
        $reg = DB::table('dang_ky_ca')->where('id', $id)->first();
        abort_unless($reg, 404);
        if ((int)$reg->trang_thai !== 1) {
            return redirect()->route('route_DangKyCa_List', ['id' => $reg->id_nhan_vien])
                ->with('error', 'Chỉ xóa được đăng ký đang chờ duyệt.');
        }
        DB::table('dang_ky_ca')->where('id', $id)->delete();
        return redirect()->route('route_DangKyCa_List', ['id' => $reg->id_nhan_vien])
            ->with('success', 'Đã xóa đăng ký ca.');
    }

    /**
     * Xem lịch ca 7 ngày của cửa hàng (theo id_phong_ban của người xem).
     * Dùng date()/strtotime(), KHÔNG dùng Carbon.
     */
    public function lichCaView($id, Request $request)
    {
        $this->ensureTables();
        $emp = DB::table('nhan_vien')->where('id', $id)->first();
        abort_unless($emp, 404);
        $idPhongBan = $emp->id_phong_ban;

        $start = $request->input('tu_ngay', date('Y-m-d'));
        $dates = [];
        for ($i = 0; $i < 7; $i++) {
            $dates[] = date('Y-m-d', strtotime("$start +$i days"));
        }

        $rows = DB::table('lich_ca as tb1')
            ->join('nhan_vien as tb2', 'tb2.id', '=', 'tb1.id_nhan_vien')
            ->leftJoin('chuc_vu as tb3', 'tb3.id', '=', 'tb2.id_chuc_vu')
            ->select('tb1.ngay_lam', 'tb1.ca', 'tb2.ho_ten', 'tb3.ten_chuc_vu')
            ->where('tb2.id_phong_ban', $idPhongBan)
            ->whereIn('tb1.ngay_lam', $dates)
            ->orderBy('tb3.ten_chuc_vu')
            ->orderBy('tb2.ho_ten')
            ->get();

        $grid = [];
        foreach ($dates as $d) {
            $grid[$d] = ['sang' => [], 'chieu' => []];
        }
        foreach ($rows as $row) {
            if (!isset($grid[$row->ngay_lam])) {
                continue;
            }
            $label = $row->ho_ten . ($row->ten_chuc_vu ? ' (' . $row->ten_chuc_vu . ')' : '');
            $grid[$row->ngay_lam][$row->ca][] = $label;
        }

        return view('nhanvien.lich-ca', compact('grid', 'dates', 'id', 'start', 'emp'));
    }
}
