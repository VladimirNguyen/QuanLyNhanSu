<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class PhuCapNhanVien extends Model
{
    protected $table = 'phu_cap_nhan_vien';
    protected $fillable = ['tb1.id', 'tb1.id_nhan_vien','tb1.id_phu_cap', 'tb1.trang_thai', 'tb1.created_at', 'tb1.updated_at'];
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
    public function loadPhuCapNV($id, $params = null){
        $query = DB::table($this->table.' as tb1')
            ->select( 'tb1.id', 'tb1.id_nhan_vien','tb1.id_phu_cap', 'tb1.trang_thai','tb2.ten_phu_cap')
            ->where('tb1.id_nhan_vien', '=', $id)
            ->leftJoin('phu_cap as tb2','tb2.id','=','tb1.id_phu_cap');
        $obj = $query->get();
        return $obj;
    }
    public function deletePCNV($id)
    {
        $updatePCNV = DB::table('phu_cap_nhan_vien')->where('id_nhan_vien',$id)->update(['trang_thai'=>0]);
        return $updatePCNV;
    }
}
