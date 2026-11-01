@extends('templates.layout')
@section('title', 'Lịch Ca')
@section('content')
    <section class="content-header">
        <h1>Lịch Ca Cửa Hàng @if(!empty($emp->ho_ten)) — {{ $emp->ho_ten }} @endif</h1>
    </section>

    <section class="content">
        <div id="msg-box">
            @if (Session::has('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <strong>{{ Session::get('success') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if (Session::has('error'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <strong>{{ Session::get('error') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>

        <div class="box">
            <div class="box-header with-border" style="background:#1B2A4A;color:#fff;">
                <h3 class="box-title"><i class="fa fa-calendar"></i> Chọn tuần</h3>
            </div>
            <div class="box-body">
                <form method="GET" action="{{ route('route_LichCa_View', ['id'=>$id]) }}">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Từ ngày</label>
                            <input type="date" name="tu_ngay" class="form-control" value="{{ $start }}">
                        </div>
                        <div class="col-md-2 form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary form-control"
                                    style="background:#1B2A4A;border-color:#1B2A4A;">
                                <i class="fa fa-eye"></i> Xem
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="box">
            <div class="box-header with-border" style="background:#1B2A4A;color:#fff;">
                <h3 class="box-title"><i class="fa fa-calendar"></i> Lịch ca 7 ngày</h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-bordered">
                    <thead>
                        <tr style="background:#1B2A4A;color:#fff;">
                            <th class="text-center" style="width:16%;">Ngày</th>
                            <th class="text-center">Ca sáng (9:00 - 15:30)</th>
                            <th class="text-center">Ca chiều (15:30 - 22:00)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $thu = ['Chủ Nhật','Thứ 2','Thứ 3','Thứ 4','Thứ 5','Thứ 6','Thứ 7'];
                        @endphp
                        @foreach($dates as $d)
                            @php
                                $thuIdx = (int) date('w', strtotime($d));
                            @endphp
                            <tr>
                                <td class="text-center" style="background:#1B2A4A;color:#fff;font-weight:600;">
                                    {{ $thu[$thuIdx] }}<br>{{ date('d/m/Y', strtotime($d)) }}
                                </td>
                                <td style="background:#e8f4f0;">
                                    @if(!empty($grid[$d]['sang']))
                                        <ul style="margin:0;padding-left:18px;">
                                            @foreach($grid[$d]['sang'] as $nv)
                                                <li>{{ $nv }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span style="color:#999;">—</span>
                                    @endif
                                </td>
                                <td style="background:#f0f2f5;">
                                    @if(!empty($grid[$d]['chieu']))
                                        <ul style="margin:0;padding-left:18px;">
                                            @foreach($grid[$d]['chieu'] as $nv)
                                                <li>{{ $nv }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span style="color:#999;">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
