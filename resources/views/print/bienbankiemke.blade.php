<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Biên bản kiểm kê tài sản</title>
    <style>
        html,body{
            height:297mm;
            width:210mm;
            margin: auto;
            font-family: DejaVu Sans;
            font-size:14px;
            padding: 20px;
        }
        .center{
            text-align: center;
        }
        .main{
            font-size: 12px;
            margin-top: 30px;
        }
        p{
            margin: 0;
        }
    </style>
</head>
<body>
<div id="wrapper">
    <table class="table1">
        <tr >
            <th style="padding-top:20px ; padding-right:50px; width: 300px">Công Ty TNHH PH</th>
            <th style="padding-top:30px">CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM
                <br>
                Độc Lập - Tự Do - Hạnh Phúc
                <br>
                <hr style="width: 50%">
            </th>
        </tr>
        <tr>
            <th colspan="2" style="padding:10px ; font-size: 20px;">
                BIÊN BẢN KIỂM KÊ TÀI SẢN
                <br>
                @isset($donVi->ten_don_vi) Đơn vị: {{ $donVi->ten_don_vi }} @endisset
            </th>
        </tr>
    </table>
    <div class="main">
        <table border="1" cellpadding="5" cellspacing="0" width="95%">
            <tr>
                <th>STT</th>
                <th>Mã tài sản</th>
                <th>Tên tài sản</th>
                <th>Năm sử dụng</th>
                <th>Thông số kỹ thuật</th>
                <th>Xuất xứ</th>
                <th>Tình trạng kiểm kê</th>
            </tr>
            @php($i = 1)
            @foreach($detailTaiSan as $value)
                <tr>
                    <td class="center">{{$i++}}</td>
                    <td class="center">{{ $value->ma_tai_san }}</td>
                    <td class="center">{{ $value->ten_tai_san }}</td>
                    <td class="center">{{ $value->nam_su_dung }}</td>
                    <td class="center">{{ $value->thong_so_ky_thuat }}</td>
                    <td class="center">{{ $value->xuat_xu }}</td>
                    <td class="center">{{ $arrKiemKe[$value->tinh_trang_kk] ?? '' }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
</body>
</html>