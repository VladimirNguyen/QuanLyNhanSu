<?php
namespace App\Http\Controllers;

use App\CongNhanVien;
use App\DanhMucKhoaHoc;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\TaiSanRequest;
use Illuminate\Support\Facades\Session;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;
class ChamCongController extends Controller
{
    private $v;

    public function __construct()
    {
        $this->v = [];
    }
    public function themCongNgay($id,Request $request){
        $arrChamCong = [];
        $arrChamCong['id'] = null;
        $arrChamCong['id_nhan_vien'] = $request->id;
        $arrChamCong['ngay_lam'] = date('Y-m-d');
        $arrChamCong['gio_vao'] = date('H:i:s');
        $arrChamCong['gio_ra'] = 'null';
        $arrChamCong['trang_thai'] = 1;
        $arrChamCong['created_at'] = date('Y-m-d H:i:s');
        $arrChamCong['updated_at'] = date('Y-m-d H:i:s');
        $result = DB::table('cham_cong')->insert($arrChamCong) ;
        if(!$result){
            return response()->json(['errors'=>Session::exists('errors')?Session::pull('errors'):'Lỗi thêm mới'],500);
        }else{

                Session::flash('success', 'Chấm công thành công!');
                return redirect()->route('route_BackEnd_CongNhanVien_List', ['id' => $id]);

        }
    }
    public function chamRaVe($id, Request $request){
        $objCongNhanVien = new CongNhanVien();
        $objIDNV = $objCongNhanVien->loadOneID($id);
        $udateGioVe =[];
        $udateGioVe['id'] = $request->id;
        $udateGioVe['gio_ra'] =  date('H:i:s');
        $objCongNhanVien = new CongNhanVien();
        $update = $objCongNhanVien->saveUpdateRaVe($udateGioVe);
        if(!$update){
            return response()->json(['errors'=>Session::exists('errors')?Session::pull('errors'):'Lỗi thêm mới'],500);
        }else{

            Session::flash('success', 'Châm công ra về thành công!');
            return redirect()->route('route_BackEnd_CongNhanVien_List', ['id' => $objIDNV->id_nhan_vien]);

        }
    }

}