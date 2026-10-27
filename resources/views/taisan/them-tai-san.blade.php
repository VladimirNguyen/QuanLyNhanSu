@extends('templates.layout')
@section('title', 'Thêm tài sản')
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
                <h3 class="box-title">Thông tin tài sản</h3>
            </div>
            <form action="{{ route('route_BackEnd_TaiSan_Add') }}" method="post" class="form-horizontal">
                @csrf
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Mã tài sản</label>
                        <div class="col-sm-6">
                            <input type="text" name="ma_tai_san" class="form-control" value="{{ old('ma_tai_san') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Tên tài sản</label>
                        <div class="col-sm-6">
                            <input type="text" name="ten_tai_san" class="form-control" value="{{ old('ten_tai_san') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Danh mục</label>
                        <div class="col-sm-6">
                            <select name="danh_muc_tai_san_id" class="form-control select2">
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($danh_muc_tai_san as $item)
                                    <option value="{{ $item->id }}" @if(old('danh_muc_tai_san_id') == $item->id) selected @endif>{{ $item->ten_danh_muc }}</option>
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
                    <a href="{{ route('route_BackEnd_TaiSan_index') }}" class="btn btn-default">Hủy</a>
                </div>
            </form>
        </div>
    </section>
@endsection