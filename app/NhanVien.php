<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
class NhanVien extends Model
{
    protected $table = 'nhan_vien';
    protected $fillable = ['tb1.id', 'tb1.ho_ten', 'tb1.ngay_sinh', 'tb1.gioi_tinh', 'tb1.dia_chi', 'tb1.so_dien_thoai', 'tb1.so_chung_minh', 'tb1.anh_cmt_truoc', 'tb1.anh_cmt_sau','tb1.email','tb1.ngay_gia_nhap', 'tb1.ngay_nghi_lam','tb1.id_phong_ban','tb1.id_chuc_vu','tb1.id_user','tb1.trang_thai','tb1.created_at', 'tb1.updated_at'];

    public function loadListWithPager($params = array(),$id = null)
    {
        $query = DB::table($this->table.' as tb1')
            ->select('tb1.id', 'tb1.ho_ten', 'tb1.ngay_sinh', 'tb1.gioi_tinh', 'tb1.dia_chi', 'tb1.so_dien_thoai', 'tb1.so_chung_minh','tb1.email','tb1.trang_thai','tb1.id_chuc_vu','tb2.ten_chuc_vu','tb1.ngay_gia_nhap')
            ->leftJoin('chuc_vu as tb2','tb2.id','=','tb1.id_chuc_vu')
            ->where('tb1.id_phong_ban',$id);
        if (isset($params['search_ten_nhan_vien']) && strlen($params['search_ten_nhan_vien']) > 0) {
            $query->where('tb1.ho_ten', 'like', '%' . $params['search_ten_nhan_vien'] . '%');
        }

        if (isset($params['search_so']) && $params['search_so']){
            $query->where('tb1.so_dien_thoai','=', $params['search_so'])
            ->orWhere('tb1.so_chung_minh','=', $params['search_so']);
        }
        $lists = $query->where('tb1.trang_thai', '=', 1)->paginate(10, ['tb1.id']);
        return $lists;
    }
    public function loadListWithPagerNV()
    {
        $query = DB::table($this->table.' as tb1')
            ->select($this->fillable);

        $lists = $query->get();
        return $lists;
    }
    public function loadListWithPagerNVC($cmt = null)
    {
        $query = DB::table($this->table.' as tb1')
            ->select($this->fillable);
        if (isset($cmt) && $cmt!= null){
            $query->where('tb1.so_chung_minh', '=', $cmt);
        }
        $lists = $query->first();
        return $lists;
    }
    public function loadListDSWithPager($params = array())
    {
        $query = DB::table($this->table.' as tb1')
            ->select($this->fillable)
            ->where('tb1.id_phong_ban', '!=', 0)
            ->orderBy('tb1.id_phong_ban','ASC');
        if (isset($params['search_ten_nhan_vien']) && strlen($params['search_ten_nhan_vien']) > 0) {
            $query->where('tb1.ho_ten', 'like', '%' . $params['search_ten_nhan_vien'] . '%')
            ->orWhere('tb1.so_chung_minh','like', '%'. $params['search_ten_nhan_vien']. '%')
            ->orWhere('tb1.so_dien_thoai','like', '%'. $params['search_ten_nhan_vien']. '%');
        }
        if (isset($params['search_phong_ban']) && strlen($params['search_phong_ban']) > 0) {
            $query->where('tb1.id_phong_ban', $params['search_phong_ban']);
        }
        if (isset($params['search_chuc_vu']) && strlen($params['search_chuc_vu']) > 0) {
            $query->where('tb1.id_chuc_vu', $params['search_chuc_vu']);
        }
        $lists = $query->where('tb1.trang_thai', '=', 1)->paginate(10, ['tb1.id']);
        return $lists;
    }
    public function loadCountNVCT()
    {
        $query = DB::table($this->table.' as tb1')
            ->select($this->fillable)
        ->where('tb1.id_phong_ban', '!=', 0);
        $lists = $query->where('tb1.trang_thai', '=', 1)->count();
        return $lists;
    }
    public function loadCountNVM()
    {
        $query = DB::table($this->table.' as tb1')
            ->select($this->fillable)
            ->where('tb1.id_phong_ban', '=', 0);
        $lists = $query->where('tb1.trang_thai', '=', 1)->count();
        return $lists;
    }

    public function loadListDSMWithPager($params = array())
    {
        $query = DB::table($this->table.' as tb1')
            ->select($this->fillable)
            ->where('tb1.id_phong_ban', '=', 'null');
        if (isset($params['search_ten_nhan_vien']) && strlen($params['search_ten_nhan_vien']) > 0) {
            $query->where('tb1.ho_ten', 'like', '%' . $params['search_ten_nhan_vien'] . '%')
                ->orWhere('tb1.so_chung_minh','like', '%'. $params['search_ten_nhan_vien']. '%')
                ->orWhere('tb1.so_dien_thoai','like', '%'. $params['search_ten_nhan_vien']. '%');
        }
        $lists = $query->paginate(10, ['tb1.id']);
        return $lists;
    }
    public function loadOne($id, $params = null){
        $query = DB::table($this->table.' as tb1')
            ->select($this->fillable)
            ->where('tb1.id', '=', $id);

        $obj = $query->first();
        return $obj;
    }
    public function loadOneIdUser($id, $params = null){
        $query = DB::table($this->table.' as tb1')
            ->select($this->fillable)
            ->where('tb1.id_user', '=', $id);

        $obj = $query->first();
        return $obj;
    }
    public function saveUpdate($params){
        unset($params['cols']['id_he_so_luong']);
        unset($params['cols']['id_phu_cap']);

        if (empty($params['user_edit'])) {
            Log::warning(__METHOD__ . ' Không xác định thông tin người cập nhật');
            Session::push('errors', 'Không xác định thông tin người cập nhật');
            return null;
        }
        if (empty($params['cols']['id'])) {
            Session::push('errors', 'Không xác bản ghi cần cập nhật');
            return null;
        }
        $dataUpdate = [];
        foreach ($params['cols'] as $colName => $val) {
            if ($colName == 'id') continue;

            if (in_array('tb1.'.$colName, $this->fillable))
                $dataUpdate[$colName] = (strlen($val)==0)?null:$val;
        }
        $res = DB::table($this->table)
            ->where('id', $params['cols']['id'])
            ->limit(1)
            ->update($dataUpdate);
        return $res;
    }
    public function saveUpdateNVC($id,$params){
        unset($params['cols']['id_he_so_luong']);
        unset($params['cols']['id_phu_cap']);

        if (empty($params['user_edit'])) {
            Log::warning(__METHOD__ . ' Không xác định thông tin người cập nhật');
            Session::push('errors', 'Không xác định thông tin người cập nhật');
            return null;
        }
        if (empty($params['cols']['id'])) {
            Session::push('errors', 'Không xác bản ghi cần cập nhật');
            return null;
        }
        $dataUpdate = [];
        foreach ($params['cols'] as $colName => $val) {
            if ($colName == 'id') continue;

            if (in_array('tb1.'.$colName, $this->fillable))
                $dataUpdate[$colName] = (strlen($val)==0)?null:$val;
        }
        $res = DB::table($this->table)
            ->where('id', $id)
            ->limit(1)
            ->update($dataUpdate);
        return $res;
    }
    public function saveNVNew($params){
        if (empty($params['nhanvienmoi_add'])) {
            Log::warning(__METHOD__ . ' Không xác định thông tin người cập nhật');
            Session::push('errors', 'Không xác định thông tin người cập nhật');
            return null;
        }

        $data =  array_merge($params['cols'],[
            'ho_ten' => $params['cols']['ho_ten'],
            'ngay_sinh' => $params['cols']['ngay_sinh'],
            'gioi_tinh' => $params['cols']['gioi_tinh'],
            'dia_chi' => $params['cols']['dia_chi'],
            'so_dien_thoai' => $params['cols']['so_dien_thoai'],
            'so_chung_minh' => $params['cols']['so_chung_minh'],
            'anh_cmt_truoc' => $params['cols']['anh_cmt_truoc'],
            'anh_cmt_sau' => $params['cols']['anh_cmt_sau'],
            'email' => $params['cols']['email'],
            'ngay_gia_nhap' => date('Y-m-d'),
            'ngay_nghi_lam' => 'null',
            'id_phong_ban' => 'null',
            'id_chuc_vu' => 'null',
            'id_user' => 'null',
            'trang_thai' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $res = DB::table($this->table)->insertGetId($data);

        return $res;
    }
    public function deleteNV($id)
    {
        $updateTTNV = DB::table('nhan_vien')->where('id',$id)->limit(1)->update(['trang_thai'=>0]);
        return $updateTTNV;
    }
}