@extends('templates.layout')
@section('title', 'Chi tiết biên bản')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        @include('templates.header-action')
        <div class="clearfix"></div>
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
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Cập nhật biên bản</h3>
            </div>
            <form action="{{ route('route_BackEnd_BienBan_Update',['id'=> $objItem->id]) }}" method="post" class="form-horizontal">
                @csrf
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Ngày lập</label>
                        <div class="col-sm-6">
                            <input type="date" name="ngay_lap" class="form-control" value="{{ $objItem->ngay_lap }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Họ tên người giao</label>
                        <div class="col-sm-6">
                            <input type="text" name="ho_ten_nguoi_giao" class="form-control" value="{{ $objItem->ho_ten_nguoi_giao }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Chức danh người giao</label>
                        <div class="col-sm-6">
                            <input type="text" name="chuc_danh_nguoi_giao" class="form-control" value="{{ $objItem->chuc_danh_nguoi_giao }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Bộ phận người giao</label>
                        <div class="col-sm-6">
                            <input type="text" name="bo_phan_nguoi_giao" class="form-control" value="{{ $objItem->bo_phan_nguoi_giao }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Họ tên người nhận</label>
                        <div class="col-sm-6">
                            <input type="text" name="ho_ten_nguoi_nhan" class="form-control" value="{{ $objItem->ho_ten_nguoi_nhan }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Chức danh người nhận</label>
                        <div class="col-sm-6">
                            <input type="text" name="chuc_danh_nguoi_nhan" class="form-control" value="{{ $objItem->chuc_danh_nguoi_nhan }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Bộ phận người nhận</label>
                        <div class="col-sm-6">
                            <input type="text" name="bo_phan_nguoi_nhan" class="form-control" value="{{ $objItem->bo_phan_nguoi_nhan }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Đơn vị</label>
                        <div class="col-sm-6">
                            <select name="id_don_vi" class="form-control select2">
                                <option value="">-- Chọn đơn vị --</option>
                                @foreach($don_vi as $item)
                                    <option value="{{ $item->id }}" @if($objItem->id_don_vi == $item->id) selected @endif>{{ $item->ten_don_vi }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Số lượng</label>
                        <div class="col-sm-6">
                            <input type="text" name="so_luong_bien_ban" class="form-control" value="{{ $objItem->so_luong_bien_ban }}">
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Lưu</button>
                    <a href="{{ route('route_BackEnd_BienBan_index') }}" class="btn btn-default">Hủy</a>
                </div>
            </form>
        </div>
    </section>
@endsection