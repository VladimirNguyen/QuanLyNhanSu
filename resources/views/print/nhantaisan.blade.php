<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Phiếu nhận tài sản</title>
    <style>
        html,body{
            height:297mm;
            width:210mm;
            margin: auto;
            font-family: DejaVu Sans;
            font-size:14px;
            padding: 20px;
        }
        #wrapper{
            padding-top: 30px;
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
                PHIẾU NHẬN TÀI SẢN
            </th>
        </tr>
        <tr>
            <td>
            </td>
            <td >
                <p style="float: right ; padding: 5px">Ngày in phiếu: {{ date('d/m/Y') }}</p>
            </td>
        </tr>
    </table>
    <div class="main">
        <table border="1" cellpadding="5" cellspacing="0" width="95%">
            <tr>
                <th>STT</th>
                <th>Mã tài sản con</th>
                <th>Năm sử dụng</th>
                <th>Tên tài sản</th>
            </tr>
            <?php
            $i = 1;
            ?>
            @forelse($dataNhans as $key=>$value)
                <tr>
                    <td class="center">{{$i++}}</td>
                    <td class="center">{{ $value->ma_tai_san_con }}</td>
                    <td class="center">{{ $value->nam_su_dung }}</td>
                    <td class="center">{{ $value->ten_tai_san }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="center">Không có dữ liệu tài sản</td>
                </tr>
            @endforelse
        </table>
    </div>
</div>

</body>
</html>