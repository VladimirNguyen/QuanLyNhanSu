@extends('templates.layout')
@section('title', 'Thêm khóa học')
@section('content')
    <section class="content-header">
        @include('templates.header-action')
        <div class="clearfix"></div>
    </section>

    <!-- Main content -->
    <section class="content">
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
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Thông tin khóa học</h3>
            </div>
            <form action="{{ route('route_BackEnd_KhoaHoc_Add') }}" method="post" enctype="multipart/form-data" class="form-horizontal">
                @csrf
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Tên khóa học</label>
                        <div class="col-sm-6">
                            <input type="text" name="ten_khoa_hoc" class="form-control" value="{{ old('ten_khoa_hoc') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Thời gian</label>
                        <div class="col-sm-6">
                            <input type="text" name="thoi_gian" class="form-control" value="{{ old('thoi_gian') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Thông tin khóa học</label>
                        <div class="col-sm-6">
                            <textarea name="thong_tin_khoa_hoc" class="form-control" rows="3">{{ old('thong_tin_khoa_hoc') }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Kế hoạch học tập</label>
                        <div class="col-sm-6">
                            <textarea name="ke_hoach_hoc_tap" class="form-control" rows="3">{{ old('ke_hoach_hoc_tap') }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Học phí</label>
                        <div class="col-sm-6">
                            <input type="text" name="hoc_phi" class="form-control" value="{{ old('hoc_phi') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Hình ảnh</label>
                        <div class="col-sm-6">
                            <input type="file" name="hinh_anh_khoa_hoc" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Danh mục</label>
                        <div class="col-sm-6">
                            <select name="id_danh_muc" class="form-control select2">
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($danh_muc_khoa_hoc as $item)
                                    <option value="{{ $item->id }}" @if(old('id_danh_muc') == $item->id) selected @endif>{{ $item->ten_danh_muc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Trạng thái</label>
                        <div class="col-sm-6">
                            <select name="trang_thai" class="form-control">
                                @foreach($trang_thai as $key => $value)
                                    <option value="{{ $key }}" @if(old('trang_thai') == $key) selected @endif>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Lưu</button>
                    <a href="{{ route('route_BackEnd_KhoaHoc_index') }}" class="btn btn-default">Hủy</a>
                </div>
            </form>
        </div>
    </section>
@endsection