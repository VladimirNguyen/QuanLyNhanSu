
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Danh sách nhân viên</title>
    <link rel="stylesheet" href="">
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
        /*.table1:after {*/
        /*    clear: both;*/
        /*}*/
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
<div id="wrapper">


    <div class="main">
{{--        <p>Ngày tính công:  {{date("d/m/Y", strtotime($arrNgay[$id-1]))}} - {{date("d/m/Y", strtotime($arrNgay[0]))}}  </p>--}}
        <br>
        <?php
        use App\PhuCap;
        use App\CongNhanVien;
        $this->v['extParams']['search_ngay_tinh_luong_array'] = $datetime;
        foreach($dataNhans as $item){

            $id=0;
            $ngaylam=0;
            $tangca=0;
            $nuangay =1;
            $arrNgay= [];
            $objPhuCap = new PhuCap();
            $itemPhuCap = $objPhuCap->loadOneID($item->id);
            $objCongNV = new CongNhanVien();
            $ngayCong = $objCongNV->loadListWithPager($item->id, $this->v['extParams']);

            foreach ($ngayCong as  $items){
                $arrNgay[$id] = $items->ngay_lam;
                if((gmdate("H", ((strtotime($items->gio_ra) - strtotime('17:00:00')))) * 60) + gmdate("i", ((strtotime($items->gio_ra) - strtotime('17:00:00'))))>0)
                {
                    $tangca+=((gmdate("H", ((strtotime($items->gio_ra) - strtotime('17:00:00')))) * 60) + gmdate("i", ((strtotime($items->gio_ra) - strtotime('17:00:00')))));
                }
                if (strtotime('09:00:00')< strtotime($items->gio_vao))
                {
                    $nuangay++;
                }
                $ngaylam++;
                $id++;
            }
        $tienPhuCap = 0;
        foreach ($itemPhuCap as $itemz){
            $tienPhuCap+= $itemz->gia_tien;
        }
        $luongNgay = ($item->luong)/26;
        $luongGio = $luongNgay/8;
        $tienTangCa = $tangca/60 * 1.5 * $luongGio;
        $luongThucTe = ($ngaylam-$nuangay)*$luongNgay + ($nuangay*$luongNgay)/2;
        $tienBaoHiem = $luongThucTe*0.07;
        $luongNhan = $luongThucTe+$tienPhuCap+$tienTangCa-$tienBaoHiem;
        if($ngaylam > 0 ){

        ?>
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
                    {{--                <img src="{{ asset('img/img.png') }}" alt="" width="10%">--}}
                    {{--                <br>--}}
                    BẢNG LƯƠNG NHÂN VIÊN
                    <br>
                    PHÒNG: {{$item->ten_phong_ban}}


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
        <br>
        <br>
        <p>Ngày tính công:  {{date("d/m/Y", strtotime($arrNgay[$id-1]))}} - {{date("d/m/Y", strtotime($arrNgay[0]))}}  </p>
        <br>
        <table border="1" cellpadding="5" cellspacing="0" width="95%">

            <tr>
                <th>Tên nhân viên</th>
                <td class="center">{{$item->ho_ten}}</td>
            </tr>
            <tr>
                <th>Số chứng minh thư</th>
                <td class="center">{{$item->so_chung_minh}}</td>
            </tr>
            <tr>
                <th>Số điện thoại</th>
                <td class="center">{{$item->so_dien_thoai}}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td class="center">{{$item->email}}</td>
            </tr>
            <tr>
                <th>Chức vụ</th>
                <td class="center">{{$item->ten_chuc_vu}}</td>
            </tr>
            <tr>
                <th>Hệ Số Lương</th>
                <td class="center">{{$item->ten_he_so}}: ({{number_format($item->luong, 0, ',', '.')}} VND)</td>
            </tr>
            <tr>
                <th>Lương/Ngày</th>
                <td class="center">{{number_format($luongNgay, 0, ',', '.')}} VND</td>
            </tr>
            <tr>
                <th>Lương/Giờ</th>
                <td class="center">{{number_format($luongGio, 0, ',', '.')}} VND</td>
            </tr>
            <tr>
                <th>Phụ cấp</th>
                <td class="center">@foreach($itemPhuCap as $item)
                        {{$item->ten_phu_cap}} ({{number_format($item->gia_tien, 0, ',', '.')}} VND) <br>
                    @endforeach</td>
            </tr>
            <tr>
                <th>Số ngày được tính công cả ngày</th>
                <td class="center">{{$ngaylam-$nuangay}}</td>
            </tr>
            <tr>
                <th>Số ngày được tính công nửa ngày</th>
                <td class="center">{{$nuangay}}</td>
            </tr>
            <tr>
                <th>Lương thực tế</th>
                <td class="center">{{number_format($luongThucTe, 0, ',', '.')}} VND</td>
            </tr>
            <tr>
                <th>Số giờ tăng ca</th>
                <td class="center">{{number_format($tangca/60 ,2)}} tiếng</td>
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
        <div class="page-break"></div>

        <?php
        }else{
        ?>
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
                    {{--                <img src="{{ asset('img/img.png') }}" alt="" width="10%">--}}
                    {{--                <br>--}}
                    BẢNG LƯƠNG NHÂN VIÊN
                    <br>
                    PHÒNG: {{$item->ten_phong_ban}}


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
        <br>
        <br>
        <table border="1" cellpadding="5" cellspacing="0" width="95%">

            <tr>
                <th>Tên nhân viên</th>
                <td class="center">{{$item->ho_ten}}</td>
            </tr>
            <tr>
                <th>Ngày công</th>
                <td class="center">Chưa có</td>
            </tr>
        </table>
        <div class="page-break"></div>
        <?php
        }
    }
    ?>
    </div>

</div>

</body>
</html>
