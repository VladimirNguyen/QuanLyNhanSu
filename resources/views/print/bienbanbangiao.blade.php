<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Biên bản bàn giao tài sản</title>
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
                BIÊN BẢN BÀN GIAO TÀI SẢN
                <br>
                @isset($bienbanDetail->ngay_lap) Ngày lập: {{ date('d/m/Y', strtotime($bienbanDetail->ngay_lap)) }} @endisset
            </th>
        </tr>
    </table>
    <div class="main">
        <p><b>Người giao:</b> {{ $bienbanDetail->ho_ten_nguoi_giao ?? '' }} - {{ $bienbanDetail->chuc_danh_nguoi_giao ?? '' }} - {{ $bienbanDetail->bo_phan_nguoi_giao ?? '' }}</p>
        <p><b>Người nhận:</b> {{ $bienbanDetail->ho_ten_nguoi_nhan ?? '' }} - {{ $bienbanDetail->chuc_danh_nguoi_nhan ?? '' }} - {{ $bienbanDetail->bo_phan_nguoi_nhan ?? '' }}</p>
        <p><b>Đơn vị:</b> {{ $arrDonVi[$bienbanDetail->id_don_vi] ?? '' }}</p>
        <table border="1" cellpadding="5" cellspacing="0" width="95%" style="margin-top: 20px;">
            <tr>
                <th>STT</th>
                <th>Mã tài sản</th>
                <th>Tên tài sản</th>
                <th>Số lượng</th>
                <th>Tình trạng kiểm kê</th>
            </tr>
            @php($i = 1)
            @foreach($detailTaiSan as $value)
                <tr>
                    <td class="center">{{$i++}}</td>
                    <td class="center">{{ $value->ma_tai_san }}</td>
                    <td class="center">{{ $value->ten_tai_san }}</td>
                    <td class="center">{{ $value->so_luong }}</td>
                    <td class="center">{{ $arrTrangThaiKK[$value->tinh_trang_kk] ?? '' }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
</body>
</html>