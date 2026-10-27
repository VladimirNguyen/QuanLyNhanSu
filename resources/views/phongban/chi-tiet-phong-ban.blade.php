@extends('templates.layout')
@section('title', $_title)
@section('content')
    <section class="content-header">
        @include('templates.header-action')
    </section>

    <!-- Main content -->
    <section class="content appTuyenSinh">
        <link rel="stylesheet" href="{{ asset('default/bower_components/select2/dist/css/select2.min.css')}} ">
        <link rel="stylesheet" href="{{ asset('default/bower_components/bootstrap-daterangepicker/daterangepicker.css')}} ">

        <style>
            .select2-container--default .select2-selection--single, .select2-selection .select2-selection--single {
                padding: 3px 0px;
                height: 30px;
            }

            .select2-container {
                margin-top: -5px;
            }

            option {
                white-space: nowrap;
            }

            .select2-container--default .select2-selection--single {
                background-color: #fff;
                border: 1px solid #aaa;
                border-radius: 0px;
            }

            .select2-container--default .select2-selection--multiple .select2-selection__choice {
                color: #216992;
            }

            .select2-container--default .select2-selection--multiple {
                margin-top: 10px;
                border-radius: 0;
            }

            .select2-container--default .select2-results__group {
                background-color: #eeeeee;
            }
        </style>

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

    <!-- Phần nội dung riêng của action  -->
        <form class="form-horizontal "
              action="{{ route('route_BackEnd_PhongBan_Update',['id'=>request()->route('id')]) }}" method="post"
              enctype="multipart/form-data">
            @csrf
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ten_khoa_hoc" class="col-md-3 col-sm-4 control-label">Tên Phòng Ban <span
                                        class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="ten_phong_ban" id="ten_phong_ban" class="form-control"
                                       value="@isset($request['ten_phong_ban '])  {{ $request['ten_phong_ban '] }} @else {{ $objItem->ten_phong_ban  }} @endisset" @if($objItem->ten_phong_ban  != '')  @endif>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nguon" class="col-md-3 col-sm-4 control-label">Trạng thái <span
                                        class="text-danger">(*)</span></label>
                            <div class="col-md-9 col-sm-8">
                                <select name="trang_thai" id="trang_thai" class="form-control select2"
                                        data-placeholder="Chọn trạng thái">
                                    <option value="">== Chọn trạng thái ==</option>
                                    @foreach($trang_thai as $index => $item)
                                        <option value="{{ $index }}"
                                                @isset($request['trang_thai']) @if($request['trang_thai'] == $index) selected
                                                @endif @else @if($objItem->trang_thai == $index) selected @endif @endisset>
                                            {{ $item }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-6">

                    </div>
                </div>

            </div>
            <!-- /.box-body -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary"> Save</button>
                <a href="{{ route('route_BackEnd_PhongBan_index') }}" class="btn btn-default">Quay lại</a>
            </div>
            <!-- /.box-footer -->
        </form>


        <div class="box box-primary" style="margin-top: 50px">
            <div class="box-header with-border">
                <div class="box-title">
                    Danh Sách Nhân Viên: Phòng {{$objItem->ten_phong_ban}}
                </div>
            </div>
            <div class="box-body">
                <a href="{{ route('route_BackEnd_NhanVien_Print',['id'=>request()->route('id')]) }}" target="_blank" class="btn btn-info"><i class="fa fa-print" style="color:white;"></i>
                    In Danh Sách Nhân Viên</a>
                <div style="border: 1px solid #ccc;margin-top: 10px;padding: 5px;">
                    <form action="" method="get">
                        <div class="row">
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <input type="text" name="search_ten_nhan_vien" class="form-control" placeholder="Tên Nhân Viên"
                                           value="@isset($extParams['search_ten_nhan_vien']){{$extParams['search_ten_nhan_vien']}}@endisset">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <input type="text" name="search_so" class="form-control" placeholder="Số Chứng Minh/Số Điện Thoại"
                                           value="@isset($extParams['search_so']){{$extParams['search_so']}}@endisset">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <?php
                                    $date = date('Y-m-d');
                                    ?>
                                    <input type="text" name="search_ngay_tinh_luong" class="form-control daterangepicker-click" placeholder="Ngày Tính Công"
                                           value="@isset($extParams['search_ngay_tinh_luong']){{$extParams['search_ngay_tinh_luong']}}@endisset" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-xs-12" style="text-align:center;">
                            <div class="form-group">
                                <button type="submit" name="btnSearch" class="btn btn-primary btn-sm "><i
                                            class="fa fa-search" style="color:white;"></i> Search
                                </button>
                                <button type="submit" name="btnXemTruoc" class="btn btn-primary btn-sm "> Xem trước
                                </button>
                                <button type="submit" name="btnGuiGmail" class="btn btn-primary btn-sm "> Gửi Gmail
                                </button>
                                <a href="{{ route('route_BackEnd_PhongBan_Detail',['id'=>request()->route('id')]) }}" class="btn btn-default btn-sm "><i class="fa fa-remove"></i>
                                    Clear </a>
                            </div>
                        </div>
                    </form>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
                <div v-if="list_hoa_dons.length>0" class="table-responsive">
                    <table class="table table-bordered" style="margin-top:20px;">
                        <tbody>
                        <tr>
                            <th>STT</th>
                            <th>Tên Nhân Viên</th>
                            <th>Ngày Sinh</th>
                            <th>Giới Tính</th>
                            <th>Địa Chỉ</th>
                            <th>Số Điện Thoại</th>
                            <th>Số Chứng Minh Thư</th>
                            <th>Email</th>
                            <th>Tên Chức Vụ</th>
                            <th>Ngày Gia Nhập</th>
                            <th>Trạng Thái</th>
                            <th>Công Cụ</th>
                        </tr>
                        @php($id = 1)
                        @foreach($lists as $key => $item)
                            <tr>

                                <td>{{ $id++}}</td>
                                <td>{{ $item->ho_ten }}</td>
                                <td>{{ date("d/m/Y", strtotime($item->ngay_sinh)) }}</td>
                                <td>{{ $item->gioi_tinh }}</td>
                                <td>{{ $item->dia_chi }}</td>
                                <td>{{ $item->so_dien_thoai}}</td>
                                <td>{{ $item->so_chung_minh }}</td>
                                <td>{{ $item->email}}</td>
                                <td>{{ $item->ten_chuc_vu}}</td>
                                <td>{{ date("d/m/Y", strtotime($item->ngay_gia_nhap))}}</td>

                                <td>
                                    @if($item->trang_thai == 0)
                                        Không làm việc
                                    @elseif($item->trang_thai == 1)
                                        Đang làm việc
                                @endif
                                <td class="text-center">
                                    <a href="{{ route('route_BackEnd_AdminCongNhanVien_List',['id'=> $item->id]) }}" title="Danh Sách Công" ><i class="fa fa-edit"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
                {{--                <div v-else class="alert alert-warning" style="margin-top:20px;">Đẹp zai lỗi tại ai :)))))</div>--}}

            </div>
            <br>
            <div class="text-center">
                {{--                {{  $lists->appends($extParams)->links() }}--}}
            </div>
        </div>

    </section>
@endsection
@section('script')
    <script src="{{ asset('default/plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('default/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
    {{--    <script src="public/default/plugins/input-mask/jquery.inputmask.extensions.js"></script>--}}
    <script src="{{ asset('default/bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('default/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>


    <script src="{{ asset('js/tinhluong.js') }} "></script>
    <script src="{{ asset('js/khoahoc.js') }} "></script>


@endsection

