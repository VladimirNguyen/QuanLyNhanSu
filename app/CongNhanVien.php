<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
class CongNhanVien extends Model{
    protected $table = 'cham_cong';
    protected $fillable = ['tb1.id', 'tb1.id_nhan_vien', 'tb1.ngay_lam', 'tb1.gio_vao', 'tb1.gio_ra', 'tb1.trang_thai','tb1.created_at', 'tb1.updated_at'];
    public function createStdClass(){
        $objItem = new \stdClass();
        foreach ($this->fillable as $field){
            $field = substr($field,4);
            $objItem->$field = null;
        }
        return $objItem;
    }
    /** Hàm lấy danh sách có phân trang
     * @param array $params
     * @return mixed
     */
    public function loadListWithPager($id,$params = null){
        $query = DB::table($this->table.' as tb1')
            ->select($this->fillable)
            ->where('tb1.id_nhan_vien',$id)
           ->orderBy('tb1.ngay_lam','DESC');
        if (isset($params['search_ngay_tinh_luong_array']) && count($params['search_ngay_tinh_luong_array']) == 2) {
            $query->whereBetween('tb1.ngay_lam', $params['search_ngay_tinh_luong_array']);
        }
        $list = $query->paginate(31, ['tb1.id']);
        return $list;
    }
    public function loadOne($id, $params = null){
        $query = DB::table($this->table.' as tb1')
            ->select($this->fillable)
            ->where('tb1.id_nhan_vien', '=', $id);

        $obj = $query->max('ngay_lam');

        return $obj;
    }
    public function loadOneID($id, $params = null){
        $query = DB::table($this->table.' as tb1')
            ->select($this->fillable)
            ->where('tb1.id', '=', $id);

        $obj = $query->first();

        return $obj;
    }
    public function saveUpdateRaVe($udateGioVe)
    {

        $res = DB::table($this->table)
            ->where('id', $udateGioVe['id'])
            ->limit(1)
            ->update(['gio_ra'=>$udateGioVe['gio_ra']]);
        return $res;
    }
    public function saveUpdateRaVeKCham($id)
    {
        $time= date('H:i:s', strtotime('17:00:00'));
        $updateCC = DB::table($this->table)->where('id',$id)->update(['gio_ra'=> $time ]);
        return $updateCC;
    }
}