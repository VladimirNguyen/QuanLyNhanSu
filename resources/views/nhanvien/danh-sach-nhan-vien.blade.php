@extends('templates.layout')
@section('title', '1233')
@section('css')
    <style>
        body {
            /*-webkit-touch-callout: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            -o-user-select: none;*/
            user-select: none;
        }

        .toolbar-box form .btn {
            /*margin-top: -3px!important;*/
        }

        .select2-container {
            margin-top: 0;
        }

        .select2-container--default .select2-selection--multiple {
            border-radius: 0;
        }

        .select2-container .select2-selection--multiple {
            min-height: 30px;
        }

        .select2-container .select2-search--inline .select2-search__field {
            margin-top: 3px;
        }

        .table > tbody > tr.success > td {
            background-color: #009688;
            color: white !important;
        }

        .table > tbody > tr.success > td span {
            color: white !important;
        }

        .table > tbody > tr.success > td span.button__csentity {
            color: #333 !important;
        }

        /*.table>tbody>tr.success>td i{*/
        /*    color: white !important;*/
        /*}*/
        .text-silver {
            color: #f4f4f4;
        }

        .btn-silver {
            background-color: #f4f4f4;
            color: #333;
        }

        .select2-container--default .select2-results__group {
            background-color: #eeeeee;
        }
    </style>
@endsection
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
                            <input type="text" name="search_ten_nhan_vien" class="form-control" placeholder="Tên nhân viên/Số chứng minh thư/Số điện thoại"
                                   value="@isset($extParams['search_ten_nhan_vien']){{$extParams['search_ten_nhan_vien']}}@endisset">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="form-group">
                            <select name="search_phong_ban" class="form-control select1"
                                    data-placeholder="Chọn Phòng Ban">
                                <option value=""> == Chọn Phòng Ban ==</option>
                                @if(count($itemPhongBan)>0)
                                    @foreach($itemPhongBan as $key => $item)
                                        <option value="{{ $item->id }}"
                                                @isset($extParams['search_phong_ban']) @if($extParams['search_phong_ban'] == $item->id) selected  @endif @endisset>{{$item->ten_phong_ban}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="form-group">
                            <select name="search_chuc_vu" class="form-control select1"
                                    data-placeholder="Chọn Chức Vụ">
                                <option value=""> == Chọn Chức Vụ ==</option>
                                @if(count($itemChucVu)>0)
                                    @foreach($itemChucVu as $key => $item)
                                        <option value="{{ $item->id }}"
                                                @isset($extParams['search_chuc_vu']) @if($extParams['search_chuc_vu'] == $item->id) selected  @endif @endisset>{{$item->ten_chuc_vu}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    {{--                    @endif--}}
                    <div class="clearfix"></div>
                    <div class="col-xs-12" style="text-align:center;">
                        <div class="form-group">
                            <button type="submit" name="btnSearch" class="btn btn-primary btn-sm "><i
                                        class="fa fa-search" style="color:white;"></i> Search
                            </button>
                            <a href="{{ url('/danh-sach-nhan-vien') }}" class="btn btn-default btn-sm "><i class="fa fa-remove"></i>
                                Clear </a>
                        </div>
                    </div>
                </div>

            </form>
            <div class="clearfix"></div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content appTuyenSinh">
        <div id="msg-box">
            <?php //Hiển thị thông báo thành công?>
            @if ( Session::has('success') )
                <div class="alert alert-success alert-dismissible" role="alert">
                    <strong>{{ Session::get('success') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                </div>
            @endif
            <?php //Hiển thị thông báo lỗi?>
            @if ( Session::has('error') )
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <strong>{{ Session::get('error') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                </div>
            @endif
        </div>
        @if(count($list)<=0)
            <p class="alert alert-warning">
                Không có dữ liệu phù hợp
            </p>
        @endif
        <div class="box-body table-responsive no-padding">
            <form action="" method="post">
                @csrf
                <span class="pull-right">Tổng số bản ghi tìm thấy: <span
                            style="font-size: 15px;font-weight: bold;">{{ $list->count() }}</span></span>
                <div class="clearfix"></div>
                <div class="double-scroll">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 50px" class="text-center">
                                STT
                            </th>
                            <th class="text-center">Họ và tên</th>
                            <th class="text-center">Ngày sinh</th>
                            <th class="text-center">Giới tính</th>
                            <th class="text-center">Địa chỉ</th>
                            <th class="text-center">Số điện thoại</th>
                            <th class="text-center">Số chứng minh thư</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Phòng ban</th>
                            <th class="text-center">Chức vụ</th>
                            <th class="text-center">Trạng thái</th>
                            <th width="50px" class="text-center">Công cụ</th>
                        </tr>
                            @php($i=1)
                        @foreach($list as  $item)

                            <tr>
                                {{--                                <td><input type="checkbox" name="chk_hv[]" class="chk_hv" id="chk_hv_{{$item->id}}" value="{{$item->id}}"> </td>--}}
                                <td class="text-center">{{$i++}}</td>
                                <td class="text-center">{{$item->ho_ten}}</td>
                                <td class="text-center">{{date("d/m/Y", strtotime($item->ngay_sinh))}}</td>
                                <td class="text-center">{{$item->gioi_tinh}}</td>
                                <td class="text-center">{{$item->dia_chi}}</td>
                                <td class="text-center">{{$item->so_dien_thoai}}</td>
                                <td class="text-center">{{$item->so_chung_minh}}</td>
                                <td class="text-center">{{$item->email}}</td>
                                <td class="text-center">{{$arrPhongBan[$item->id_phong_ban]}}</td>
                                <td class="text-center">{{$arrChucVu[$item->id_chuc_vu]}}</td>
                                <td class="text-center" width="100px" style="background-color:
                                @if($item->trang_thai == 0)
                                        red
                                @else
                                        green
                                @endif;
                                        color: white">
                                    @if($item->trang_thai == 0)a
                                        Tạm Dừng Hoạt Động
                                    @else
                                        Hoạt động
                                    @endif
                                </td>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('route_BackEnd_AdminNhanVien_Detail',['id'=> $item->id]) }}" title="Sửa"><i class="fa fa-edit"></i></a>
                                    <form method="POST" action="{{ route('route_BackEnd_AdminNhanVien_Detele',['id'=> $item->id]) }}" style="display:inline">@csrf<button type="submit"   title="Xóa" ><i class="fa fa-trash"></i></button></form>
                                </td>

                            </tr>
                        @endforeach

                    </table>
                </div>
            </form>
        </div>
        <br>
        <div class="text-center">
            {{  $list->appends($extParams)->links() }}
        </div>
        <div class="text-left">
            Tổng số nhân viên hiện tại: {{$countNV}}
        </div>
        <index-cs ref="index_cs"></index-cs>
    </section>

@endsection


