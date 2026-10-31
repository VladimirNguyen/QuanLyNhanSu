<?php
namespace Tests\Feature;
use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
class AccessAndAttendanceTest extends TestCase {
 protected function setUp(): void {
  parent::setUp();
  config(['database.default'=>'sqlite','database.connections.sqlite.database'=>':memory:','app.key'=>'base64:'.base64_encode(str_repeat('x',32))]);
  DB::purge('sqlite');
  foreach (['2014_10_12_000000_create_users_table.php','2022_05_15_130242_create_nhan_vien_table.php','2022_05_15_132420_create_cham_cong_table.php'] as $file) require_once database_path('migrations/'.$file);
  (new \CreateUsersTable)->up(); (new \CreateNhanVienTable)->up(); (new \CreateChamCongTable)->up();
 }
 private function employee($id) {
  DB::table('users')->insert(['id'=>$id,'name'=>'Test','email'=>"u$id@example.test",'password'=>Hash::make('secret123'),'phone'=>'','role'=>0,'level'=>2,'status'=>1]);
  DB::table('nhan_vien')->insert(['id'=>$id,'id_user'=>$id,'ho_ten'=>'Test','ngay_sinh'=>'2000-01-01','gioi_tinh'=>'Nam','dia_chi'=>'Test','so_dien_thoai'=>"phone$id",'so_chung_minh'=>"card$id",'anh_cmt_truoc'=>'','anh_cmt_sau'=>'','email'=>"u$id@example.test",'ngay_gia_nhap'=>'2026-01-01']);
  return User::find($id);
 }
 public function test_employee_cannot_open_admin_or_change_another_password() {
  $a=$this->employee(1); $this->employee(2); $old=User::find(2)->password;
  $this->actingAs($a)->get('/user')->assertForbidden();
  $this->post('/nhan-vien-tai-khoan/update/2',['name'=>'Other','password'=>'newsecret','password1'=>'newsecret'])->assertForbidden();
  $this->assertSame($old,User::find(2)->password);
 }
 public function test_employee_can_change_own_password() {
  $this->actingAs($this->employee(1))->post('/nhan-vien-tai-khoan/update/1',['name'=>'Test','password'=>'newsecret','password1'=>'newsecret'])->assertRedirect();
  $this->assertTrue(Hash::check('newsecret',User::find(1)->password));
 }
 public function test_checkin_is_idempotent_and_get_does_not_write() {
  $this->actingAs($this->employee(1));
  $this->get('/cong-nhan-vien/add/1')->assertStatus(405);
  $this->post('/cong-nhan-vien/add/1')->assertRedirect();
  $this->post('/cong-nhan-vien/add/1')->assertRedirect();
  $this->assertSame(1,DB::table('cham_cong')->count());
  $this->assertNull(DB::table('cham_cong')->first()->gio_ra);
 }
 public function test_employee_cannot_checkin_for_another_employee() {
  $a=$this->employee(1);$this->employee(2);
  $this->actingAs($a)->post('/cong-nhan-vien/add/2')->assertForbidden();
  $this->assertSame(0,DB::table('cham_cong')->count());
 }
 public function test_registration_without_employee_has_controlled_login_response() {
  $this->post('/register',['name'=>'New','email'=>'new@example.test','password'=>'secret123','password_confirmation'=>'secret123'])->assertRedirect('login');
  $this->post('/login',['email'=>'new@example.test','password'=>'secret123'])->assertRedirect('login');
  $this->assertGuest();
 }
 public function test_document_and_allowance_routes_are_distinct() {
  $routes=app('router')->getRoutes();
  $this->assertSame('bienban/update/{id}',$routes->getByName('route_BackEnd_BienBan_Update')->uri());
  $this->assertSame('phu-cap/update/{id}',$routes->getByName('route_BackEnd_PhuCap_Update')->uri());
 }
}
