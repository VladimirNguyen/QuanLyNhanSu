<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
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
        .col1,.col2,.col3{
            text-align: center;
            line-height: 10px;
            font-size: 12px;
        }
        .col4,.col5,.col6{
            text-align: left;
            line-height: 12px;
            font-size: 12px;
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

<p>Xin chào nhân viên, <b>{{$email->ho_ten}}</b></p>
<p>Chức vụ: {{$email->ten_chuc_vu}}</p>
<p>Kế toán trưởng Công ty TNHH PH</p>
<p>Xin thông báo, bảng lương chi tiết từ ngày {{$email->ngayDau}} - {{$email->ngayCuoi}}</p>
<p>Hệ số lương của bạn là: {{$email->tenHeSo}}: ({{number_format($email->heSoLuong, 0, ',', '.')}} VND)</p>
<?php
$luongNgay = $email->heSoLuong/26;
$luongGio = $luongNgay/8;
$tienTangCa = $email->tangCa/60 * 1.5 * $luongGio;
$luongThucTe = ($email->ngayLam - $email->ngayNua)*$luongNgay + ($email->ngayNua*$luongNgay)/2;
$tienBaoHiem = $luongThucTe*0.07;
$luongNhan = $luongThucTe+$email->tienPhuCap+$tienTangCa-$tienBaoHiem;
?>
<p>Lương/ngày của bạn là: {{number_format($email->heSoLuong, 0, ',', '.')}} / 26 = {{number_format($luongNgay, 0, ',', '.')}} VND</p>
<p>Lương/giờ của bạn là: {{number_format($luongNgay, 0, ',', '.')}} / 8 = {{number_format($luongGio, 0, ',', '.')}} VND</p>
<p>Phụ cấp của bạn là: @foreach($email->phuCap as $item)
        {{$item->ten_phu_cap}} ({{number_format($item->gia_tien, 0, ',', '.')}} VND),
    @endforeach</p>
<div id="wrapper">
    <div class="main">
        <table border="1" cellpadding="5" cellspacing="0" width="95%">
            <tr>
                <th>Tổng số công</th>
                <td class="center">{{$email->ngayLam}}</td>
            </tr>
            <tr>
                <th>Số ngày được tính công cả ngày</th>
                <td class="center">{{$email->ngayLam - $email->ngayNua}}</td>
            </tr>
            <tr>
                <th>Số ngày được tính công nửa ngày</th>
                <td class="center">{{$email->ngayNua}}</td>
            </tr>
            <tr>
                <th>Lương thực tế</th>
                <td class="center">{{number_format($luongThucTe, 0, ',', '.')}} VND</td>
            </tr>
            <tr>
                <th>Số giờ tăng ca</th>
                <td class="center">{{number_format($email->tangCa/60 ,2)}} tiếng</td>
            </tr>
            <tr>
                <th>Lương tăng ca</th>
                <td class="center">{{number_format($tienTangCa, 0, ',', '.')}} VND</td>
            </tr>
            <tr>
                <th>Số tiền bảo y tế</th>
                <td class="center">{{number_format($luongThucTe*0.02, 0, ',', '.')}} VND</td>
            </tr>
            <tr>
                <th>Số tiền bảo xã hội</th>
                <td class="center">{{number_format($luongThucTe*0.05, 0, ',', '.')}} VND</td>
            </tr>
            <tr>
                <th>Lương thực nhận</th>
                <td class="center">{{number_format($luongNhan, 0, ',', '.')}} VND</td>
            </tr>
        </table>
    </div>
</div>
<p>Công thức tính lương của công ty</p>
<p>Lương thực tế = Số công đi làm công cả ngày * lương/ngày + Số công đi làm công cả ngày * lương/ngày/2 </p>
<p>Lương tăng ca = Số giờ tăng ca * 1.5 * lương/giờ</p>
<p>Các loại bảo hiểm bạn phải đóng là: Bảo hiểm y tế + Bảo hiểm xã hội </p>
<p>Bản hiểm xã hội: Lương thực tế * 5%</p>
<p>Bản hiểm y tế: Lương thực tế * 2%</p>
<p>Lương thực nhận = Lương thực tế + Lương tăng ca + Phụ cấp - Bảo hiểm </p>
<p><strong>Nếu có vấn đề về thông tin về lương vui lòng liên hệ  Mr. Nguyễn Thành Trung -Số điện thoại: 0898555917</strong></p>
<p><strong>Bộ phận kế toán Công ty TNHH PH xin trân trọng cảm ơn!</strong></p>
</table>

</body>
</html>