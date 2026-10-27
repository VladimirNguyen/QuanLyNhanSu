<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class HeSoLuongNhanVien extends Model{
    protected $table = 'nhan_vien_he_so';
    protected $fillable = ['tb1.id', 'tb1.id_he_so','tb1.id_nhan_vien','tb1.ghi_chu', 'tb1.trang_thai', 'tb1.created_at', 'tb1.updated_at'];
    public $timestamps = false;

    public function createStdClass()
    {
        $objItem = new \stdClass();
        foreach ($this->fillable as $field) {
            $field = substr($field, 4);
            $objItem->$field = null;
        }
        return $objItem;
    }

    /** Hàm lấy danh sách có phân trang
     * @param array $params
     * @return mixed
     */
    public function loadOne($id, $params = null){
        $query = DB::table($this->table.' as tb1')
            ->select($this->fillable)
            ->where('tb1.id_nhan_vien', '=', $id)
            ->where('tb1.trang_thai', '=', 1);
        $obj = $query->first();
        return $obj;
    }
    public function loadOneID($id, $params = null){
        $query = DB::table($this->table.' as tb1')
            ->select('tb1.id', 'tb1.id_he_so','tb1.id_nhan_vien','tb1.ghi_chu', 'tb1.trang_thai', 'tb1.created_at', 'tb1.updated_at','tb2.ten_he_so', 'tb2.luong')
            ->leftJoin('he_so_luong as tb2','tb2.id','=','tb1.id_he_so')
            ->where('tb1.id_nhan_vien', '=', $id)
            ->orderBy('tb1.created_at', 'DESC');
        $obj = $query->paginate(10, ['tb1.id']);
        return $obj;
    }
    public function loadHeSoNV($id, $params = null){
        $query = DB::table($this->table.' as tb1')
            ->select( 'tb1.id', 'tb1.id_he_so','tb1.id_nhan_vien','tb1.ghi_chu', 'tb1.trang_thai')
            ->where('tb1.id_nhan_vien', '=', $id)
            ->where('tb1.trang_thai', '=',1)
        ;

        $obj = $query->first();
        return $obj;
    }
    public function deleteHSLNV($id)
    {
        $updateHSLNV = DB::table('nhan_vien_he_so')->where('id_nhan_vien',$id)->update(['trang_thai'=>0]);
        return $updateHSLNV;
    }
}
