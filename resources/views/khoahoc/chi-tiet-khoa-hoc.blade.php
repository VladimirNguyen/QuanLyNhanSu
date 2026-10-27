@extends('templates.layout')
@section('title', 'Chi tiết khóa học')
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
                <h3 class="box-title">Thông tin khóa học</h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Tên khóa học</label>
                    <div class="col-sm-6">
                        <p class="form-control-static">{{ $objItem->ten_khoa_hoc }}</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Thời gian</label>
                    <div class="col-sm-6">
                        <p class="form-control-static">{{ $objItem->thoi_gian }}</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Thông tin khóa học</label>
                    <div class="col-sm-6">
                        <p class="form-control-static">{{ $objItem->thong_tin_khoa_hoc }}</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Kế hoạch học tập</label>
                    <div class="col-sm-6">
                        <p class="form-control-static">{{ $objItem->ke_hoach_hoc_tap }}</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Học phí</label>
                    <div class="col-sm-6">
                        <p class="form-control-static">{{ $objItem->hoc_phi }}</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Danh mục</label>
                    <div class="col-sm-6">
                        <p class="form-control-static">
                            @foreach($danh_muc_khoa_hoc as $item)
                                @if($objItem->id_danh_muc == $item->id){{ $item->ten_danh_muc }}@endif
                            @endforeach
                        </p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Trạng thái</label>
                    <div class="col-sm-6">
                        <p class="form-control-static">
                            @foreach($trang_thai as $key => $value)
                                @if($objItem->trang_thai == $key){{ $value }}@endif
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <a href="{{ route('route_BackEnd_KhoaHoc_index') }}" class="btn btn-default">Quay lại</a>
            </div>
        </div>
    </section>
@endsection