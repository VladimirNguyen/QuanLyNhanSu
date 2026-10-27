@extends('templates.layout')
@section('title', 'Biên Bản Thanh Lí')
@section('content')
    <section class="content-header">
        @include('templates.header-action')
        <div class="clearfix"></div>
    </section>

    <!-- Main content -->
    <section class="content">
        @if(count($lists)<=0)
            <p class="alert alert-warning">Không có dữ liệu phù hợp</p>
        @endif
        <div class="box-body table-responsive no-padding">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 50px" class="text-center">STT</th>
                    <th class="text-center">Mã tài sản con</th>
                    <th class="text-center">Năm sử dụng</th>
                    <th class="text-center">Đơn vị</th>
                    <th class="text-center">Tài sản</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center">In biên bản</th>
                </tr>
                @php($i=1)
                @foreach($lists as $item)
                    <tr>
                        <td class="text-center">{{$i++}}</td>
                        <td class="text-center">{{$item->ma_tai_san_con}}</td>
                        <td class="text-center">{{$item->nam_su_dung}}</td>
                        <td class="text-center">
                            @isset($arrDonVi[$item->id_don_vi]){{$arrDonVi[$item->id_don_vi]}}@endisset
                        </td>
                        <td class="text-center">
                            @isset($arrTaiSan[$item->id_tai_san]){{$arrTaiSan[$item->id_tai_san]}}@endisset
                        </td>
                        <td class="text-center">
                            @isset($trang_thais[$item->trang_thai]){{$trang_thais[$item->trang_thai]}}@endisset
                        </td>
                        <td class="text-center">
                            <a href="{{ route('route_BackEnd_TaiSanCon_InBienBanThanhLi_Update') }}" class="btn btn-xs btn-primary" target="_blank"><i class="fa fa-print"></i> In</a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <br>
        <div class="text-center">
            {{ $lists->appends($extParams)->links() }}
        </div>
    </section>
@endsection