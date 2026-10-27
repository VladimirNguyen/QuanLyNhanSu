@extends('templates.layout')
@section('title', 'Theo dõi chấm công')
@section('css')
<style>
    .badge-vao  { background-color: #1B2A4A; color:#fff; padding:3px 8px; border-radius:4px; font-size:13px; }
    .badge-ra   { background-color: #A8D8C8; color:#1C1C2E; padding:3px 8px; border-radius:4px; font-size:13px; }
    .badge-chua { background-color: #B0BEC5; color:#fff; padding:3px 8px; border-radius:4px; font-size:13px; }
    .badge-chuacham { background-color: #C0392B; color:#fff; padding:3px 8px; border-radius:4px; font-size:13px; }
    .stat-box { border-radius:8px; padding:16px 20px; color:#fff; margin-bottom:10px; }
    .stat-box .num { font-size:32px; font-weight:700; }
    .stat-box .lbl { font-size:13px; opacity:0.85; }
    .filter-bar { background:#f4f6f9; border:1px solid #e0e6ed; border-radius:6px; padding:12px 16px; margin-bottom:16px; }
</style>
@endsection
@section('content')
<section class="content-header">
    <h1><i class="fa fa-clock-o"></i> Theo Dõi Chấm Công <small>Ngày: {{ date('d/m/Y', strtotime($ngay)) }}</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ url('/user') }}"><i class="fa fa-home"></i> Trang chủ</a></li>
        <li class="active">Theo dõi chấm công</li>
    </ol>
</section>

<section class="content">
    {{-- Thông báo --}}
    @if(Session::has('success'))
        <div class="alert alert-success alert-dismissible">
            <strong>{{ Session::get('success') }}</strong>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif
    @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible">
            <strong>{{ Session::get('error') }}</strong>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    {{-- Bộ lọc ngày --}}
    <div class="filter-bar">
        <form method="GET" action="{{ route('route_BackEnd_Admin_ChamCong') }}" class="form-inline">
            <label><i class="fa fa-calendar"></i> Chọn ngày:</label>
            <input type="date" name="ngay" value="{{ $ngay }}" class="form-control" style="margin:0 10px;">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Xem</button>
            <a href="{{ route('route_BackEnd_Admin_ChamCong') }}" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Hôm nay</a>
        </form>
    </div>

    {{-- Thống kê tổng quan --}}
    @php
        $tongDaCham   = $list->count();
        $tongRaVe     = $list->where('gio_ra', '!=', '00:00:00')->where('gio_ra', '!=', null)->count();
        $tongChuaRa   = $tongDaCham - $tongRaVe;
        $tongChuaCham = $chuaCham->count();
    @endphp
    <div class="row">
        <div class="col-sm-3">
            <div class="stat-box" style="background:#1B2A4A;">
                <div class="num">{{ $tongDaCham }}</div>
                <div class="lbl"><i class="fa fa-check-circle"></i> Đã chấm vào ca</div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="stat-box" style="background:#7EC8B0;">
                <div class="num">{{ $tongRaVe }}</div>
                <div class="lbl"><i class="fa fa-sign-out"></i> Đã kết ca</div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="stat-box" style="background:#78909C;">
                <div class="num">{{ $tongChuaRa }}</div>
                <div class="lbl"><i class="fa fa-clock-o"></i> Đang làm việc</div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="stat-box" style="background:#C0392B;">
                <div class="num">{{ $tongChuaCham }}</div>
                <div class="lbl"><i class="fa fa-times-circle"></i> Chưa chấm công</div>
            </div>
        </div>
    </div>

    {{-- Bảng đã chấm công --}}
    <div class="box">
        <div class="box-header">
            <h3 class="box-title"><i class="fa fa-list"></i> Danh sách chấm công ngày {{ date('d/m/Y', strtotime($ngay)) }}</h3>
        </div>
        <div class="box-body table-responsive">
            @if($list->count() == 0)
                <p class="alert alert-warning">Chưa có nhân viên nào chấm công ngày này.</p>
            @else
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center" width="40">STT</th>
                        <th>Họ và tên</th>
                        <th class="text-center">Chức vụ</th>
                        <th class="text-center">Cửa hàng</th>
                        <th class="text-center">Giờ vào ca</th>
                        <th class="text-center">Giờ kết ca</th>
                        <th class="text-center">Số giờ làm</th>
                        <th class="text-center">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                @php $stt = 1; @endphp
                @foreach($list as $item)
                    @php
                        $gioRa = ($item->gio_ra && $item->gio_ra != '00:00:00') ? $item->gio_ra : null;
                        $soGio = '';
                        if ($gioRa) {
                            $phut = (strtotime($item->gio_ra) - strtotime($item->gio_vao)) / 60;
                            $soGio = floor($phut/60) . 'h ' . ($phut%60) . 'p';
                        }
                    @endphp
                    <tr>
                        <td class="text-center">{{ $stt++ }}</td>
                        <td><strong>{{ $item->ho_ten }}</strong></td>
                        <td class="text-center">{{ $item->ten_chuc_vu }}</td>
                        <td class="text-center">{{ $item->ten_phong_ban }}</td>
                        <td class="text-center">
                            <span class="badge-vao">{{ date('H:i', strtotime($item->gio_vao)) }}</span>
                        </td>
                        <td class="text-center">
                            @if($gioRa)
                                <span class="badge-ra">{{ date('H:i', strtotime($gioRa)) }}</span>
                            @else
                                <span class="badge-chua">Chưa kết ca</span>
                            @endif
                        </td>
                        <td class="text-center">
                            {{ $soGio ?: '—' }}
                        </td>
                        <td class="text-center">
                            @if($gioRa)
                                <span style="color:#7EC8B0;font-weight:600;"><i class="fa fa-check-circle"></i> Hoàn thành</span>
                            @else
                                <span style="color:#1B2A4A;font-weight:600;"><i class="fa fa-circle-o"></i> Đang làm</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>

    {{-- Bảng chưa chấm công --}}
    @if($chuaCham->count() > 0)
    <div class="box">
        <div class="box-header" style="background:#C0392B !important;">
            <h3 class="box-title" style="color:#fff;"><i class="fa fa-exclamation-triangle"></i> Nhân viên chưa chấm công ({{ $chuaCham->count() }} người)</h3>
        </div>
        <div class="box-body table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" width="40">STT</th>
                        <th>Họ và tên</th>
                        <th class="text-center">Chức vụ</th>
                        <th class="text-center">Cửa hàng</th>
                        <th class="text-center">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                @php $stt2 = 1; @endphp
                @foreach($chuaCham as $item)
                    <tr>
                        <td class="text-center">{{ $stt2++ }}</td>
                        <td><strong>{{ $item->ho_ten }}</strong></td>
                        <td class="text-center">{{ $item->ten_chuc_vu }}</td>
                        <td class="text-center">{{ $item->ten_phong_ban }}</td>
                        <td class="text-center">
                            <span class="badge-chuacham"><i class="fa fa-times"></i> Chưa chấm công</span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</section>
@endsection
