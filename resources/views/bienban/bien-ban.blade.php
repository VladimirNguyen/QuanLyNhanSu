@extends('templates.layout')
@section('title', 'Biên Bản')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        @include('templates.header-action')
        <div class="clearfix"></div>
        <div style="border: 1px solid #ccc;margin-top: 10px;padding: 5px;">
            <form action="" method="post">
                @csrf
                <div class="col-xs-12" style="text-align:center;">
                    <div class="form-group">
                        <a href="{{ route('route_BackEnd_BienBan_Add') }}" class="btn btn-info btn-sm"><i class="fa fa-plus" style="color:white;"></i>
                            Thêm biên bản</a>
                    </div>
                </div>
            </form>
            <div class="clearfix"></div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div id="msg-box">
            @if ( Session::has('success') )
                <div class="alert alert-success alert-dismissible" role="alert">
                    <strong>{{ Session::get('success') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                </div>
            @endif
            @if ( Session::has('error') )
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <strong>{{ Session::get('error') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                </div>
            @endif
        </div>
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
                    <th width="100px" class="text-center">Công cụ</th>
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
                            <a href="{{ route('route_BackEnd_BienBan_Detail',['id'=> $item->id]) }}" title="Sửa"><i class="fa fa-edit"></i></a>
                            <form method="POST" action="{{ route('route_BackEnd_BienBan_Delete',['id'=> $item->id]) }}" style="display:inline">@csrf<button type="submit"   title="Xóa" onclick="return confirm('Bạn chắc chắn muốn xóa?');"><i class="fa fa-trash"></i></button></form>
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