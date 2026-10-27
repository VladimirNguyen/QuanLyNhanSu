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
        <form class="form-horizontal " action="{{ route('route_BackEnd_AdminNhanVien_Update',['id'=>request()->route('id')]) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ho_ten" class="col-md-3 col-sm-4 control-label">Tên nhân viên <span class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="ho_ten" id="ho_ten" class="form-control" value="@isset($request['ho_ten'])  {{ $request['ho_ten'] }} @else {{ $objItem->ho_ten }} @endisset" @if($objItem->ho_ten != '')  @endif disabled>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ngay_sinh" class="col-md-3 col-sm-4 control-label">Ngày sinh<span class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <?php
                                $date = date('Y-m-d');
                                $newdate = strtotime ( '-18 year' , strtotime ( $date ) ) ;
                                $maxdate = date ( 'Y-m-d' , $newdate );
                                $olddate = strtotime ( '-40 year' , strtotime ( $date ) ) ;
                                $mindate = date ( 'Y-m-d' , $olddate );
                                ?>
                                <input type="date" name="ngay_sinh" id="ngay_sinh" min="{{$mindate}}" max="{{$maxdate}}" class="form-control" value="@isset($request['ngay_sinh']){{$request['ngay_sinh']}}@else{{$objItem->ngay_sinh}}@endisset"@if($objItem->ngay_sinh != '')@endif disabled>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="gioi_tinh" class="col-md-3 col-sm-4 control-label">Giới tính <span class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="gioi_tinh" id="gioi_tinh" class="form-control" value="@isset($request['gioi_tinh'])  {{ $request['gioi_tinh'] }} @else {{ $objItem->gioi_tinh }} @endisset" @if($objItem->gioi_tinh != '')  @endif disabled>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="dia_chi" class="col-md-3 col-sm-4 control-label">Địa chỉ <span class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="dia_chi" id="dia_chi" class="form-control" value="@isset($request['dia_chi'])  {{ $request['dia_chi'] }} @else {{ $objItem->dia_chi }} @endisset" @if($objItem->dia_chi != '')  @endif disabled>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="so_dien_thoai " class="col-md-3 col-sm-4 control-label">Số điện thoại <span class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="so_dien_thoai" id="so_dien_thoai" class="form-control" value="@isset($request['so_dien_thoai'])  {{ $request['so_dien_thoai'] }} @else {{ $objItem->so_dien_thoai }} @endisset" @if($objItem->so_dien_thoai != '')  @endif disabled>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="so_chung_minh" class="col-md-3 col-sm-4 control-label">Số chứng minh thư <span class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="so_chung_minh" id="so_chung_minh" class="form-control" value="@isset($request['so_chung_minh'])  {{ $request['so_chung_minh'] }} @else {{ $objItem->so_chung_minh  }} @endisset" @if($objItem->so_chung_minh  != '')  @endif disabled>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-sm-4 control-label">Ảnh CMND/CCCD</label>
                            <div class="col-md-9 col-sm-8">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <img id="mat_truoc_preview" src="{{ $objItem->anh_cmt_truoc? Storage::url($objItem->anh_cmt_truoc):'http://placehold.it/100x100' }}" alt="your image"
                                             style="max-width: 200px; height:100px; margin-bottom: 10px;" class="img-fluid"/>
                                        <input type="file" name="anh_cmt_truoc" accept="image/*"
                                               class="form-control-file @error('cmt_mat_truoc') is-invalid @enderror" id="cmt_truoc" disabled>
                                        <label for="cmt_truoc">Mặt trước</label><br/>
                                    </div>
                                    <div class="col-xs-6">
                                        <img id="mat_sau_preview" src="{{ $objItem->anh_cmt_sau? Storage::url($objItem->anh_cmt_sau):'http://placehold.it/100x100' }}" alt="your image"
                                             style="max-width: 200px; height:100px; margin-bottom: 10px;" class="img-fluid"/>
                                        <input type="file" name="anh_cmt_sau" accept="image/*"
                                               class="form-control-file @error('cmt_mat_sau') is-invalid @enderror" id="cmt_sau" disabled>
                                        <label for="cmt_sau">Mặt sau</label><br/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-md-3 col-sm-4 control-label">Email nhân viên<span class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="email" id="email" class="form-control" value="@isset($request['email'])  {{ $request['email'] }} @else {{ $objItem->email  }} @endisset" @if($objItem->email  != '')  @endif disabled>
                                <span id="mes_sdt">Lưu ý khi sửa Email nhân viên cũng sẽ sửa Email tài khoản đăng nhập </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="id_phong_ban" class="col-md-3 col-sm-4 control-label">Phòng ban</label>
                            <div class="col-md-9 col-sm-8">
                                <select name="id_phong_ban" id="id_phong_ban" class="form-control select2"
                                        data-placeholder="Chọn danh mục khoá học">
                                    <option value="">== Chọn phòng ban ==</option>
                                    @foreach($objPhongBan as $item)
                                        <option value="{{ $item->id }}"
                                                @isset($request['id_phong_ban']) @if($request['id_phong_ban'] == $item->id) selected
                                                @endif @else @if($objItem->id_phong_ban == $item->id) selected @endif @endisset>
                                            {{ $item->ten_phong_ban }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="id_chuc_vu" class="col-md-3 col-sm-4 control-label">Chức vụ</label>
                            <div class="col-md-9 col-sm-8">
                                <select name="id_chuc_vu" id="id_chuc_vu" class="form-control select2"
                                        data-placeholder="Chọn danh mục khoá học">
                                    <option value="">== Chọn chức vụ ==</option>
                                    @foreach($objChucVu as $item)
                                        <option value="{{ $item->id }}"@isset($request['id_chuc_vu']) @if($request['id_chuc_vu'] == $item->id) selected
                                                @endif @else @if($objItem->id_chuc_vu == $item->id) selected @endif @endisset>
                                            {{ $item->ten_chuc_vu }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="id_he_so_luong" class="col-md-3 col-sm-4 control-label">Hệ số lương</label>
                            <div class="col-md-9 col-sm-8">
                                <select name="id_he_so_luong" id="id_he_so_luong" class="form-control select2"
                                        data-placeholder="Chọn hệ số lương">
                                    <option value="">== Chọn hệ số lương ==</option>
                                    @foreach($objHeSoLuong as $item)
                                        <option value="{{ $item->id }}" @if($objHeSoNV->id_he_so == $item->id) selected @endif>
                                            {{ $item->ten_he_so }}( {{$item->luong}})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="id_phu_cap" class="col-md-3 col-sm-4 control-label">Phụ cấp</label>
                            <div class="col-md-9 col-sm-8">
                                <select name="id_phu_cap[]" id="id_phu_cap[]" class="form-control select2" data-placeholder="Chọn nguồn" multiple>
                                    <option value="" >== Chọn phụ cấp ==</option>
                                    @foreach($objPhuCap as $item)
                                        <option value="{{ $item->id }}" @foreach($objPhuCapNV as $values) @if($values->id_phu_cap == $item->id) selected @endif @endforeach>{{ $item->ten_phu_cap }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary"> Save</button>
                            <a href="{{ route('route_BackEnd_DanhSachNhanVien_index') }}" class="btn btn-default">Cancel</a>
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
    <script src="{{ asset('js/nhanvien.js') }}"></script>

@endsection

