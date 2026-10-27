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
              action="" method="post"
              enctype="multipart/form-data">
            @csrf
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ten_khoa_hoc" class="col-md-3 col-sm-4 control-label">Tên Khoá Học <span
                                        class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="ten_khoa_hoc" id="ten_khoa_hoc" class="form-control"
{{--                                       value="@isset($request['ten_phong_ban '])  {{ $request['ten_phong_ban '] }} @else {{ $objItem->ten_phong_ban  }} @endisset" @if($objItem->ten_phong_ban  != '')  @endif>--}}
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
{{--                                    @foreach($trang_thai as $index => $item)--}}
{{--                                        <option value="{{ $index }}"--}}
{{--                                                @isset($request['trang_thai']) @if($request['trang_thai'] == $index) selected--}}
{{--                                                @endif @else @if($objItem->trang_thai == $index) selected @endif @endisset>--}}
{{--                                            {{ $item }}--}}
{{--                                        </option>--}}
{{--                                    @endforeach--}}
                                </select>
                            </div>
                        </div>

                    </div>
                    {{--                    tab2--}}
                    <div class="col-md-6">

                    </div>
                </div>

            </div>
            <!-- /.box-body -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary"> Save</button>
                <a href="{{ route('route_BackEnd_KhoaHoc_index') }}" class="btn btn-default">Cancel</a>
            </div>
            <!-- /.box-footer -->
        </form>

        <br>

            <div class="box box-info box-solid" style="max-width: 510px;margin: 0 auto;">
                <div class="box-header">
                    <div class="box-title">
                        <h4 style="padding: 0;margin: 0;">Tool import danh sách công tháng nhân viên</h4>
                    </div>
                    <a href="" class="btn btn-success btn-sm pull-right" style="border: 1px solid #8bc34a;border-radius: 5px;"><i class="fa fa-file-excel-o" style="color: white;"></i> Tải file mẫu</a>
                </div>
                <div class="box-body text-center">
                    <form action="{{ route('route_BackEnd_ChamCong_Add',['id'=>request()->route('id')]) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group" style="width: 470px; margin-left:10px ">
                            <label for="file">Chọn file import</label>
                            <input type="file" class="form-control" name="file">
                            <input type="hidden" value="{{ request()->route('id') }}" name="idNhanVien" />
                        </div>
                        <button class="btn btn-primary" ><i class="fa fa-upload" style="color:white;" ></i> Import</button>
                    </form>
                </div>
            </div>
            <!-- /.box-footer -->



        <div class="box box-primary" style="margin-top: 50px">
            <div class="box-header with-border">
                <div class="box-title">
                    Danh Sách Công Tháng Nhân Viên
                </div>
                <div class="box-body">
                    <a href="{{ route('route_BackEnd_TaiSanCon_InNhanTaiSan_Update',['id'=>request()->route('id')]) }}" target="_blank" class="btn btn-info"><i class="fa fa-print" style="color:white;"></i>
                        In Nhãn Tài Sản</a>
                   <div style="border: 1px solid #ccc;margin-top: 10px;padding: 5px;">
                        <form action="" method="get">
                            <div class="row">
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <input type="text" name="search_ngay_cong" class="form-control daterangepicker-click" placeholder="Ngày khai giảng"
                                               autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            {{--                    @endif--}}
                            <div class="clearfix"></div>
                            <div class="col-xs-12" style="text-align:center;">
                                <div class="form-group">
                                    <button type="submit" name="btnSearch" class="btn btn-primary btn-sm "><i
                                                class="fa fa-search" style="color:white;"></i> Search
                                    </button>
                                    <a href="{{ route('route_BackEnd_TaiSan_Detail',['id'=>request()->route('id')]) }}" class="btn btn-default btn-sm "><i class="fa fa-remove"></i>
                                        Clear </a>
                                </div>
                            </div>
                        </form>
                        <div class="clearfix"></div>
                    </div>
            </div>
                <div class="clearfix"></div>
                <div v-if="list_hoa_dons.length>0" class="table-responsive">
                    <table class="table table-bordered" style="margin-top:20px;">
                        <tbody>
                        <tr>
                            <th>STT</th>
                            <th>Ngày Làm</th>
                            <th>Giờ Vào</th>
                            <th>Giờ Ra</th>
                            <th>Trạng thái</th>
                            <th>Công cụ</th>
                        </tr>
                        <?php
                        $i=1;
                        ?>
                        @foreach($objCongNhanVien as $key => $item)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $item->ngay_lam }}</td>
                                <td>{{ $item->gio_vao }}</td>
                                <td>{{ $item->gio_ra }}</td>

                                <td>
                                    @if($item->trang_thai == 0)
                                        Đóng
                                    @elseif($item->trang_thai == 1)
                                        Mở
                                @endif
                                <td class="text-center">
                                    <a href="{{ route('route_BackEnd_CongNhanVien_Update',['id'=> $item->id]) }}" title="Chấm ra" ><i class="fa fa-edit"></i></a>
                                    <a href="{{ route('route_BackEnd_TaiSanCon_Delete',['id'=> $item->id]) }}" title="Xóa" ><i class="fa fa-trash"></i></a>
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
                {{  $objCongNhanVien->appends($extParams)->links() }}
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


    <script src="{{ asset('js/taisan.js') }} "></script>
    <script src="{{ asset('js/khoahoc.js') }} "></script>


@endsection

