@extends('templates.layout')
@section('content')
<section class="content">
<h2>Bảng lương tổng hợp tháng {{ $month }}</h2>
<form method="GET"><label for="month">Tháng</label> <input id="month" name="month" type="month" value="{{ $month }}" required> <button class="btn btn-primary" type="submit">Xem bảng lương</button></form>
<p style="margin-top:15px">Ca bắt đầu {{ config('payroll.shift_start') }}. Vào muộn quá {{ config('payroll.late_minutes') }} phút trừ 0,5 công/ngày. Một ngày có nhiều lần chấm chỉ tính một công theo giờ vào sớm nhất.</p>
<p>Lương tạm tính = công được hưởng × (mức lương tháng / {{ config('payroll.standard_days') }}) + phụ cấp. Phụ cấp cộng một lần/tháng theo phân công đang hoạt động, kể cả tháng chưa có công. Chưa tính tăng ca và các khoản khấu trừ khác.</p>
<p>Bảng dùng mức lương và phụ cấp hiện tại, chưa phải bảng lương đã chốt lịch sử. Chưa có mức lương sẽ hiển thị “Chưa thiết lập”.</p>
<div class="table-responsive"><table class="table table-bordered table-striped">
<thead><tr><th>Nhân viên</th><th>Ngày chấm công</th><th>Ngày muộn quá 1h</th><th>Công bị trừ</th><th>Công được hưởng</th><th>Mức lương tháng</th><th>Đơn giá ngày</th><th>Lương theo công</th><th>Phụ cấp</th><th>Tổng tạm tính (VNĐ)</th></tr></thead>
<tbody>@forelse($rows as $row)<tr>
<td>{{ $row['employee']->ho_ten }}</td><td>{{ $row['days'] }}</td><td>{{ $row['late'] }}</td><td>{{ $row['late'] * 0.5 }}</td><td>{{ $row['units'] }}</td>
<td>{{ $row['rate'] ? number_format($row['rate']->luong) : 'Chưa thiết lập' }}</td>
<td>{{ $row['daily'] === null ? '—' : number_format($row['daily'], 2) }}</td>
<td>{{ $row['base'] === null ? '—' : number_format($row['base']) }}</td><td>{{ number_format($row['allowance']) }}</td><td>{{ $row['total'] === null ? 'Chưa thiết lập' : number_format($row['total']) }}</td>
</tr>@empty<tr><td colspan="10">Không có nhân viên.</td></tr>@endforelse</tbody>
<tfoot><tr><th colspan="7">Tổng ({{ count(array_filter($rows, function ($r) { return $r['total'] === null; })) }} nhân viên chưa có mức lương)</th><th>{{ number_format(array_sum(array_column($rows, 'base'))) }}</th><th>{{ number_format(array_sum(array_column($rows, 'allowance'))) }}</th><th>{{ number_format(array_sum(array_column($rows, 'total'))) }}</th></tr></tfoot>
</table></div></section>
@endsection
