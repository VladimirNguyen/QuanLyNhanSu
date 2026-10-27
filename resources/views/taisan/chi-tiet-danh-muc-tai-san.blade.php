@extends('templates.layout')
@section('title', 'Chi tiết danh mục tài sản')
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
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Cập nhật danh mục tài sản</h3>
            </div>
            <form action="{{ route('route_BackEnd_DanhMucTaiSan_Update',['id'=> $objItem->id]) }}" method="post" class="form-horizontal">
                @csrf
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Tên danh mục</label>
                        <div class="col-sm-6">
                            <input type="text" name="ten_danh_muc" class="form-control" value="{{ $objItem->ten_danh_muc }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Trạng thái</label>
                        <div class="col-sm-6">
                            <select name="trang_thai" class="form-control">
                                @foreach($trang_thai as $key => $value)
                                    <option value="{{ $key }}" @if($objItem->trang_thai == $key) selected @endif>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Lưu</button>
                    <a href="{{ route('route_BackEnd_DanhMucTaiSan_index') }}" class="btn btn-default">Hủy</a>
                </div>
            </form>
        </div>
    </section>
@endsection