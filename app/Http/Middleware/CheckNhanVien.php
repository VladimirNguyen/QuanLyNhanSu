<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\DB;
class CheckNhanVien {
 public function handle($request, Closure $next) {
  $user = $request->user();
  abort_unless($user && (int)$user->status === 1, 403);
  if ((int)$user->level === 1) return $next($request);
  $id = $request->route('id');
  $action = $request->route()->getActionMethod();
  if ($action === 'chamRaVe' || $action === 'updateGioKetCa') {
   $record = DB::table('cham_cong')->where('id', $id)->first();
   abort_unless($record, 404);
   $id = $record->id_nhan_vien;
  }
  abort_unless(DB::table('nhan_vien')->where('id', $id)->where('id_user', $user->id)->where('trang_thai', 1)->exists(), 403);
  return $next($request);
 }
}
