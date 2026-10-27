@extends('templates.layout')
@section('title', 'Biên Bản Kiểm Kê')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        @include('templates.header-action')
        <div class="clearfix"></div>
    </section>

    <!-- Main content -->
    <section class="content">
        @if(count($list)<=0)
            <p class="alert alert-warning">Không có dữ liệu phù hợp</p>
        @endif
        <div class="box-body table-responsive no-padding">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 50px" class="text-center">STT</th>
                    <th class="text-center">Ngày lập</th>
                    <th class="text-center">Người giao</th>
                    <th class="text-center">Người nhận</th>
                    <th class="text-center">Đơn vị</th>
                    <th class="text-center">Số lượng</th>
                    <th class="text-center">In biên bản</th>
                </tr>
                @php($i=1)
                @foreach($list as $item)
                    <tr>
                        <td class="text-center">{{$i++}}</td>
                        <td class="text-center">{{$item->ngay_lap}}</td>
                        <td class="text-center">{{$item->ho_ten_nguoi_giao}}</td>
                        <td class="text-center">{{$item->ho_ten_nguoi_nhan}}</td>
                        <td class="text-center">
                            @isset($arrDonVi[$item->id_don_vi]){{$arrDonVi[$item->id_don_vi]}}@endisset
                        </td>
                        <td class="text-center">{{$item->so_luong_bien_ban}}</td>
                        <td class="text-center">
                            <a href="{{ route('route_BackEnd_TaiSanCon_InBienBanKiemKe_Update',['id'=> $item->id]) }}" class="btn btn-xs btn-primary" target="_blank"><i class="fa fa-print"></i> In</a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <br>
        <div class="text-center">
            {{ $list->appends($extParams)->links() }}
        </div>
    </section>
@endsection