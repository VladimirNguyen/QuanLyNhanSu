<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Biên bản thanh lý tài sản</title>
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
                DANH SÁCH TÀI SẢN THANH LÝ
            </th>
        </tr>
    </table>
    <div class="main">
        <table border="1" cellpadding="5" cellspacing="0" width="95%">
            <tr>
                <th>STT</th>
                <th>Mã tài sản con</th>
                <th>Năm sử dụng</th>
                <th>Tài sản</th>
                <th>Đơn vị</th>
                <th>Nguyên giá</th>
                <th>Giá trị còn lại</th>
                <th>Nguồn kinh phí</th>
            </tr>
            @php($i = 1)
            @foreach($dataTLs as $value)
                <tr>
                    <td class="center">{{$i++}}</td>
                    <td class="center">{{ $value->ma_tai_san_con }}</td>
                    <td class="center">{{ $value->nam_su_dung }}</td>
                    <td class="center">{{ $arrTaiSan[$value->id_tai_san] ?? '' }}</td>
                    <td class="center">{{ $arrDonVi[$value->id_don_vi] ?? '' }}</td>
                    <td class="center">{{ $value->nguyen_gia }}</td>
                    <td class="center">{{ $value->gia_tri_con_lai }}</td>
                    <td class="center">{{ $arrNguonKP[$value->nguon_kinh_phi] ?? $value->nguon_kinh_phi }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
</body>
</html>