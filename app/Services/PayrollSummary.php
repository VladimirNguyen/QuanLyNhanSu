<?php
namespace App\Services;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
class PayrollSummary
{
    public function forMonth(string $month, ?int $employeeId = null): array
    {
        $start = CarbonImmutable::createFromFormat('!Y-m', $month);
        $end = $start->endOfMonth();
        $employees = DB::table('nhan_vien')->when($employeeId !== null, function ($q) use ($employeeId) { $q->where('id', $employeeId); })->orderBy('ho_ten')->get();
        $ids = $employees->pluck('id');
        // Earliest valid check-in wins if historical data contains duplicate days.
        $attendance = DB::table('cham_cong')->whereIn('id_nhan_vien', $ids)->where('trang_thai', 1)
            ->whereBetween('ngay_lam', [$start->toDateString(), $end->toDateString()])->whereNotNull('gio_vao')
            ->orderBy('ngay_lam')->orderBy('gio_vao')->get()->groupBy('id_nhan_vien');
        $rates = DB::table('nhan_vien_he_so as n')->join('he_so_luong as h', 'h.id', '=', 'n.id_he_so')
            ->whereIn('n.id_nhan_vien', $ids)->where('n.trang_thai', 1)->where('h.trang_thai', 1)
            ->select('n.id_nhan_vien','h.luong','h.ten_he_so')->orderByDesc('n.id')->get()->unique('id_nhan_vien')->keyBy('id_nhan_vien');
        $allowances = DB::table('phu_cap_nhan_vien as n')->join('phu_cap as p','p.id','=','n.id_phu_cap')
            ->whereIn('n.id_nhan_vien',$ids)->where('n.trang_thai',1)->where('p.trang_thai',1)
            ->select('n.id_nhan_vien','p.id','p.gia_tien')->get()->unique(function ($r) { return $r->id_nhan_vien.':'.$r->id; })->groupBy('id_nhan_vien');
        $cutoff = CarbonImmutable::parse(config('payroll.shift_start'))->addMinutes(config('payroll.late_minutes'))->format('H:i:s');
        $rows = [];
        foreach ($employees as $employee) {
            $days = ($attendance->get($employee->id) ?? collect())->filter(function ($day) { return preg_match('/^\d{2}:\d{2}:\d{2}$/', $day->gio_vao); })->unique('ngay_lam');
            $late = $days->filter(function ($day) use ($cutoff) { return $day->gio_vao > $cutoff; })->count();
            $units = $days->count() - 0.5 * $late;
            $rate = $rates->get($employee->id);
            $daily = $rate ? $rate->luong / config('payroll.standard_days') : null;
            $extra = ($allowances->get($employee->id) ?? collect())->sum('gia_tien');
            $rows[] = ['employee'=>$employee, 'days'=>$days->count(), 'late'=>$late, 'units'=>$units,
                'rate'=>$rate, 'daily'=>$daily, 'allowance'=>$extra, 'base'=>$daily === null ? null : round($units*$daily),
                'total'=>$daily === null ? null : round($units*$daily+$extra)];
        }
        return $rows;
    }
}
