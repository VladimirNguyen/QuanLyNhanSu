@extends('templates.layout')
@section('title', $_title)
@section('content')
    <section class="content-header">
        @include('templates.header-action')
    </section>

    <!-- Main content -->
    <section class="content appTuyenSinh">
        <link rel="stylesheet" href="{{ asset('default/bower_components/select2/dist/css/select2.min.css')}} ">
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
            .select2-container--default .select2-selection--multiple{
                margin-top:10px;
                border-radius: 0;
            }
            .select2-container--default .select2-results__group{
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
        <form class="form-horizontal " action="" method="post" enctype="multipart/form-data">
            @csrf
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ho_ten" class="col-md-3 col-sm-4 control-label">Tên Nhân Viên <span class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="ho_ten" id="ho_ten" class="form-control" value="@isset($request['ho_ten'])  {{ $request['ho_ten'] }} @endisset" >
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ngay_sinh" class="col-md-3 col-sm-4 control-label">Ngày Sinh<span class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="date" name="ngay_sinh" id="ngay_sinh" class="form-control" value="@isset($request['ngay_sinh'])  {{ $request['ngay_sinh'] }}  @endisset">
                                <span id="mes_sdt"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="gioi_tinh" class="col-md-3 col-sm-4 control-label">Giới Tính <span class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="gioi_tinh" id="gioi_tinh" class="form-control" value="@isset($request['gioi_tinh'])  {{ $request['gioi_tinh'] }} @endisset" >
                                <span id="mes_sdt"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="dia_chi" class="col-md-3 col-sm-4 control-label">Địa Chỉ <span class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="dia_chi" id="dia_chi" class="form-control" value="@isset($request['dia_chi'])  {{ $request['dia_chi'] }} @endisset" >
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="so_dien_thoai " class="col-md-3 col-sm-4 control-label">Số Điện Thoại <span class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="so_dien_thoai" id="so_dien_thoai" class="form-control" value="@isset($request['so_dien_thoai'])  {{ $request['so_dien_thoai'] }}  @endisset">
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="so_chung_minh" class="col-md-3 col-sm-4 control-label">Số Chứng Minh Thư <span class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="so_chung_minh" id="so_chung_minh" class="form-control" value="@isset($request['so_chung_minh'])  {{ $request['so_chung_minh'] }}  @endisset" >
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-sm-4 control-label">Ảnh CMND/CCCD</label>
                            <div class="col-md-9 col-sm-8">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <img id="mat_truoc_preview" src="http://placehold.it/100x100" alt="your image"
                                             style="max-width: 200px; height:100px; margin-bottom: 10px;" class="img-fluid"/>
                                        <input type="file" name="anh_cmt_truoc" accept="image/*"
                                               class="form-control-file @error('cmt_mat_truoc') is-invalid @enderror" id="cmt_truoc">
                                        <label for="cmt_truoc">Mặt trước</label><br/>
                                    </div>
                                    <div class="col-xs-6">
                                        <img id="mat_sau_preview" src="http://placehold.it/100x100" alt="your image"
                                             style="max-width: 200px; height:100px; margin-bottom: 10px;" class="img-fluid"/>
                                        <input type="file" name="anh_cmt_sau" accept="image/*"
                                               class="form-control-file @error('cmt_mat_sau') is-invalid @enderror" id="cmt_sau">
                                        <label for="cmt_sau">Mặt sau</label><br/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-md-3 col-sm-4 control-label">Email nhân viên<span class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="email" id="email" class="form-control" value="@isset($request['email'])  {{ $request['email'] }} @endisset"  >
                                <span id="mes_sdt">Lưu ý khi sửa Email nhân viên cũng sẽ sửa Email tài khoản đăng nhập </span>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary"> Save</button>
                            <a href="{{ route('route_BackEnd_NguoiDung_index') }}" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>

        </form>

    </section>
@endsection
@section('script')
    <script src="{{ asset('default/plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('default/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
    {{--    <script src="public/default/plugins/input-mask/jquery.inputmask.extensions.js"></script>--}}
    <script src="{{ asset('js/nhanvien.js')}}"></script>

@endsection

