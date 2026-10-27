@extends('templates.layout')
@section('title', 'Tài sản')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        @include('templates.header-action')
        <div class="clearfix"></div>
        <div style="border: 1px solid #ccc;margin-top: 10px;padding: 5px;">
            <form action="" method="get">
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <div class="form-group">
                            <input type="text" name="search_ten_tai_san" class="form-control" placeholder="Tên tài sản"
                                   value="@isset($extParams['search_ten_tai_san']){{$extParams['search_ten_tai_san']}}@endisset">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-xs-12" style="text-align:center;">
                        <div class="form-group">
                            <button type="submit" name="btnSearch" class="btn btn-primary btn-sm "><i
                                        class="fa fa-search" style="color:white;"></i> Search
                            </button>
                            <a href="{{ url('/taisan') }}" class="btn btn-default btn-sm "><i class="fa fa-remove"></i>
                                Clear </a>
                            <a href="{{ route('route_BackEnd_TaiSan_Add') }}" class="btn btn-info btn-sm"><i class="fa fa-plus" style="color:white;"></i>
                                Add new</a>
                        </div>
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
                    <th class="text-center">Mã tài sản</th>
                    <th class="text-center">Tên tài sản</th>
                    <th class="text-center">Danh mục</th>
                    <th class="text-center">Trạng thái</th>
                    <th width="50px" class="text-center">Công cụ</th>
                </tr>
                @php($i=1)
                @foreach($list as $item)
                    <tr>
                        <td class="text-center">{{$i++}}</td>
                        <td class="text-center">{{$item->ma_tai_san}}</td>
                        <td class="text-center">{{$item->ten_tai_san}}</td>
                        <td class="text-center">
                            @isset($arrDanhMuc[$item->danh_muc_tai_san_id]){{$arrDanhMuc[$item->danh_muc_tai_san_id]}}@endisset
                        </td>
                        <td class="text-center" style="width: 180px;background-color:
                        @if($item->trang_thai == 0) red @else green @endif; color: white">
                            @if($item->trang_thai == 0) Tạm Dừng Hoạt Động @else Hoạt Động @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('route_BackEnd_TaiSan_Detail',['id'=> $item->id]) }}" title="Sửa"><i class="fa fa-edit"></i></a>
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