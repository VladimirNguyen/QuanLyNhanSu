@extends('templates.layout')
@section('title', 'Đăng Ký Ca')
@section('content')
    <section class="content-header">
        <h1>Đăng Ký Ca Làm</h1>
    </section>

    <section class="content">
        <div id="msg-box">
            @if (Session::has('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <strong>{{ Session::get('success') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if (Session::has('error'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <strong>{{ Session::get('error') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <ul style="margin:0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="box">
            <div class="box-header with-border" style="background:#1B2A4A;color:#fff;">
                <h3 class="box-title"><i class="fa fa-calendar-plus-o"></i> Gửi đăng ký ca</h3>
            </div>
            <div class="box-body">
                <form method="POST" action="{{ route('route_DangKyCa_Add', ['id'=>$id]) }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Ngày làm <span style="color:#C0392B;">*</span></label>
                            <input type="date" name="ngay_lam" class="form-control"
                                   min="{{ date('Y-m-d') }}"
                                   value="{{ old('ngay_lam') }}" autocomplete="off" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Ca làm <span style="color:#C0392B;">*</span></label>
                            <select name="ca" class="form-control" required>
                                <option value="">-- Chọn ca --</option>
                                <option value="sang" {{ old('ca') == 'sang' ? 'selected' : '' }}>Sáng (9:00 - 15:30)</option>
                                <option value="chieu" {{ old('ca') == 'chieu' ? 'selected' : '' }}>Chiều (15:30 - 22:00)</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Ghi chú</label>
                            <textarea name="ghi_chu" class="form-control" rows="1" maxlength="255">{{ old('ghi_chu') }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"
                                style="background:#1B2A4A;border-color:#1B2A4A;">
                            <i class="fa fa-paper-plane"></i> Gửi đăng ký
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="box">
            <div class="box-header with-border" style="background:#1B2A4A;color:#fff;">
                <h3 class="box-title"><i class="fa fa-list"></i> Danh sách đăng ký của tôi</h3>
            </div>
            <div class="box-body table-responsive no-padding">
                @if(count($list) <= 0)
                    <p class="alert alert-warning" style="margin:10px;">Chưa có đăng ký ca nào.</p>
                @else
                    <table class="table table-bordered">
                        <thead>
                            <tr style="background:#1B2A4A;color:#fff;">
                                <th class="text-center">Ngày</th>
                                <th class="text-center">Ca</th>
                                <th class="text-center">Trạng thái</th>
                                <th>Ghi chú</th>
                                <th class="text-center">Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $item)
                                <tr>
                                    <td class="text-center">{{ date('d/m/Y', strtotime($item->ngay_lam)) }}</td>
                                    <td class="text-center">
                                        @if($item->ca == 'sang')
                                            Sáng (9:00-15:30)
                                        @else
                                            Chiều (15:30-22:00)
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($item->trang_thai == 1)
                                            <span class="badge" style="background:#B0BEC5;color:#1B2A4A;">Chờ duyệt</span>
                                        @elseif($item->trang_thai == 2)
                                            <span class="badge" style="background:#A8D8C8;color:#1B2A4A;">Đã duyệt</span>
                                        @else
                                            <span class="badge" style="background:#C0392B;color:#fff;">Từ chối</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->ghi_chu ?: '—' }}</td>
                                    <td class="text-center">
                                        @if($item->trang_thai == 1)
                                            <a href="{{ route('route_DangKyCa_Delete', ['id'=>$item->id]) }}"
                                               class="btn btn-xs"
                                               style="background:#C0392B;color:#fff;border-color:#C0392B;"
                                               onclick="return confirm('Xóa đăng ký ca này?')">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </section>
@endsection
