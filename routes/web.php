<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::middleware(['auth'])->group(function () {
// Sửa đường dẫn trang chủ mặc định

Route::get('/', 'HocsinhController@index');
Route::get('/home', 'HocsinhController@index');

Route::get('/user', 'UserController@index')->name('route_BackEnd_NguoiDung_index');
Route::match(['get', 'post'], '/user/add', 'UserController@add')->name('route_BackEnd_NguoiDung_Add');

Route::get('/user/detail/{id}', 'UserController@detail')
    ->where('id', '[0-9]+')
    ->name('route_BackEnd_NguoiDung_Detail');

Route::post('/user/update/{id}', 'UserController@update')
    ->where('id', '[0-9]+')
    ->name('route_BackEnd_NguoiDung_Update');
});
//    ->middleware(['can:BackEnd_QuanLyDaoTao_taoDanhSachThi']);
//Route::match(['get', 'post'], '/user/add', 'BackEnd\BoDeThiController@add')->name('route_BackEnd_DeThi_Add');
//    ->middleware(['can:BackEnd_QuanLyDaoTao_taoDanhSachThi']);
// Đăng ký thành viên
Route::get('register', 'Auth\RegisterController@getRegister');
Route::post('register', 'Auth\RegisterController@postRegister');



// Đăng nhập và xử lý đăng nhập
Route::get('login', [ 'as' => 'login', 'uses' => 'Auth\LoginController@getLogin']);
Route::post('login', [ 'as' => 'login', 'uses' => 'Auth\LoginController@postLogin']);

// Đăng xuất
Route::get('logout', [ 'as' => 'logout', 'uses' => 'Auth\LogoutController@getLogout']);




// danh mục tài sản
Route::middleware(['auth'])->group(function () {
    Route::get('/chucvu/detail/{id}', 'ChucVuController@chitietChucVu')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_ChucVu_Detail');
    Route::post('/chucvu/update/{id}', 'ChucVuController@updateChucVu')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_ChucVu_Update');
Route::get('/chucvu-list', 'ChucVuController@chucVu')->name('route_BackEnd_ChucVu_index');
Route::match(['get', 'post'], '/chucvu-add', 'ChucVuController@themChucVu')->name('route_BackEnd_ChucVU_Add');
Route::get('/taisan-category', 'TaiSanController@danhMucTaiSan')->name('route_BackEnd_DanhMucTaiSan_index');

Route::get('/phongban-list', 'PhongBanController@listsPhongBan')->name('route_BackEnd_PhongBan_index');
Route::match(['get', 'post'], '/phongban-add', 'PhongBanController@themPhongBan')->name('route_BackEnd_PhongBan_Add');
    Route::post('/phongban/update/{id}', 'PhongBanController@updatePhongBan')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_PhongBan_Update');

Route::get('/khoahoc-category','DanhMucKhoaHocController@danhMucKhoaHoc')->name('route_BackEnd_DanhMucKhoa_index');
    Route::get('/phu-cap-list','ChucVuController@PhuCap')->name('route_BackEnd_PhuCap_index');


Route::get('/khoahoc-list.html','KhoaHocController@khoaHoc')->name('route_BackEnd_KhoaHoc_index');
Route::match(['get', 'post'], '/taisan-category/add', 'TaiSanController@themDanhMucTaiSan')->name('route_BackEnd_DanhMucTaiSan_Add');
Route::match(['get', 'post'], '/khoa-hoc-category/add', 'DanhMucKhoaHocController@themDanhMucKhoaHoc')->name('route_BackEnd_DanhMucKhoaHoc_Add');
Route::post('/cham-cong-nhan-vien/{id}', 'PhongBanController@chamCongNhanVien')
    ->where('id', '[0-9]+')
    ->name('route_BackEnd_ChamCong_Add');


Route::match(['get', 'post'], '/khoa-hoc/add', 'KhoaHocController@themKhoaHoc')->name('route_BackEnd_KhoaHoc_Add');
Route::get('/khoahoc-khoa-hoc/detail/{id}', 'KhoaHocController@chiTietKhoaHoc')
    ->where('id', '[0-9]+')
    ->name('route_BackEnd_DanhMucKhoaHoc_Detail');
Route::get('/nhanvien-chamcong/{id}', 'KhoaHocController@chiTietKhoaHoc')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_DanhMucKhoaHoc_Detail');
Route::get('/phongban/detail/{id}', 'PhongBanController@chiTietPhongBan')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_PhongBan_Detail');
    Route::get('/nhanvien/print/{id}', 'NhanVienController@inDanhSachNhanVien')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_NhanVien_Print');
Route::get('/nhan-vien/detail/{id}', 'PhongBanController@themCongNhanVien')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_NhanVien_Detail');
    Route::get('/thong-tin-chi-tiet-nhan-vien/{id}', 'NhanVienController@chitietNhanVien')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_ThongTinNhanVien_Detail');
    Route::post( '/nhan-vien-moi/update/{id}', 'NhanVienController@updateNhanVien')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_NhanVienMoi_Update');
Route::get('/taisan-category/detail/{id}', 'TaiSanController@chiTietDanhMucTaiSan')
    ->where('id', '[0-9]+')
    ->name('route_BackEnd_DanhMucTaiSan_Detail');

Route::post('/taisan-category/update/{id}', 'TaiSanController@updateChiTietDanhMucTaiSan')
    ->where('id', '[0-9]+')
    ->name('route_BackEnd_DanhMucTaiSan_Update');
// tài sản
    Route::get('/taisan', 'TaiSanController@TaiSan')->name('route_BackEnd_TaiSan_index');
    Route::get('/gmail', 'NhanVienController@mailNV')->name('route_BackEnd_Test_index');

    Route::match(['get', 'post'], '/taisan/add', 'TaiSanController@themTaiSan')->name('route_BackEnd_TaiSan_Add');

    Route::get('/taisan/detail/{id}', 'TaiSanController@chiTietTaiSan')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_TaiSan_Detail');

    Route::post('/taisan/update/{id}', 'TaiSanController@updateChiTietTaiSan')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_TaiSan_Update');

    // đơn vị
    Route::get('/donvi', 'DonViController@donVi')->name('route_BackEnd_DonVi_index');
    Route::get('/danh-sach-nhan-vien', 'NhanVienController@danhSachNhanVien')->name('route_BackEnd_DanhSachNhanVien_index');
    Route::get('/danh-sach-nhan-vien-moi', 'NhanVienController@danhSachNhanVienMoi')->name('route_BackEnd_DanhSachNhanVienMoi_index');
    Route::get('/delete-nhan-vien-moi/{id}', 'NhanVienController@deleteNhanVienMoi')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_DeleteNhanVienMoi_index');
    Route::get('/user-nhan-vien', 'NhanVienController@frNhanVien')->name('route_BackEnd_UserNhanVien_index');
    Route::match(['get', 'post'], '/nhan-vien-moi/add', 'NhanVienController@themNhanVienMoi')->name('route_BackEnd_NhanVienMoi_Add');

    Route::get('/cong-nhan-vien/add/{id}', 'ChamCongController@themCongNgay')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_CongNhanVien_Add');
    Route::get('/cong-nhan-vien/update/{id}', 'ChamCongController@chamRaVe')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_CongNhanVien_Update');
    Route::match(['get', 'post'], '/donvi/add', 'DonViController@themDonVi')->name('route_BackEnd_DonVi_Add');

    Route::get('/donvi/detail/{id}', 'DonViController@chiTietDonVi')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_DonVi_Detail');

    Route::post('/donvi/update/{id}', 'DonViController@updateChiTietDonVi')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_DonVi_Update');

    // thêm tài sản con
    Route::match(['get', 'post'], '/taisancon/add', 'TaiSanConController@themTaiSanCon')->name('route_BackEnd_TaiSanCon_Add');
    Route::match(['get', 'post'], '/soluongtaisan/add', 'TaiSanController@themSoLuongTaiSan')->name('route_BackEnd_addSoLuongTaiSan_Add');
    Route::get('/taisancon/detail/{id}/{idTaiSan}', 'TaiSanConController@chiTietTaiSanCon')
//        ->where('id', '[0-9]+')
        ->name('route_BackEnd_TaiSanCon_Detail');
    Route::post('/taisancon-category/update/{id}', 'TaiSanConController@updateChiTietTaiSanCon')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_TaiSanCon_Update');

    Route::get('/taisancon/print/{id}', 'TaiSanConController@inNhanTaiSanCon')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_TaiSanCon_InNhanTaiSan_Update');
    Route::get('/taisancon/delete/{id}', 'TaiSanConController@deleteTaiSanCon')
        ->name('route_BackEnd_TaiSanCon_Delete');
    //bienbanbangiao
    Route::get('/bienbanbangiao/print/{id}', 'TaiSanConController@inBienBanBanGiao')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_TaiSanCon_InBienBanBanGiao_Update');

    Route::get('/bienbankiemke/print/{id}', 'TaiSanConController@inBienBanKiemKe')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_TaiSanCon_InBienBanKiemKe_Update');

    // thêm lịch sử sửa chữa
        Route::match(['get', 'post'], '/lichsusuachua/add', 'LichSuSuaChuaController@themLichSuSuaChua')->name('route_BackEnd_LichSuSuaChua_Add');

    // thêm biên bản
    Route::get('/bienban', 'BienBanController@bienBan')->name('route_BackEnd_BienBan_index');
    Route::match(['get', 'post'], '/bienban/add', 'BienBanController@themBienBan')->name('route_BackEnd_BienBan_Add');
    Route::get('/bienban/detail/{id}', 'BienBanController@chiTietBienBan')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_BienBan_Detail');
    Route::post('/bienban/update/{id}', 'BienBanController@updateChiTietBienBan')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_BienBan_Update');
    Route::get('/bienban/delete/{id}', 'BienBanController@deleteBienBan')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_BienBan_Delete');
    // biên bản kiểm kê
    Route::get('/bienbankiemke', 'BienBanController@bienBanKiemKe')->name('route_BackEnd_BienBanKiemKe_index');
    // biên bản thanh lí
    Route::get('/bienbanthanhli', 'BienBanController@bienBanThanhLi')->name('route_BackEnd_BienBanThanhLi_index');
    Route::get('/bienbanthanhli/print', 'TaiSanConController@inBienBanThanhLi')
//        ->where('id', '[0-9]+')
        ->name('route_BackEnd_TaiSanCon_InBienBanThanhLi_Update');
    //Phụ Cấp
    Route::get('/danh-sach-phu-cap/lists', 'PhuCapController@danhSachPhuCap')->name('route_BackEnd_PhuCap_List');
    Route::get('/danh-sach-phu-cap-nhan-vien/{id}', 'PhuCapController@frdanhSachPhuCap')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_FRPhuCap_List');
    Route::match(['get', 'post'], '/phuc-cap/add', 'PhuCapController@themPhuCap')
        ->name('route_BackEnd_PhuCap_Add');
    Route::get('/phu-cap/detail/{id}', 'PhuCapController@chitetPhuCap')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_PhuCap_Detail');
    Route::post('/bienban/update/{id}', 'PhuCapController@updatePhuCap')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_PhuCap_Update');
    //nhân viên
    Route::get('/danh-sach-nhan-vien/detail/{id}', 'NhanVienController@chiTietNV')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_AdminNhanVien_Detail');
    Route::post('/nhan-vien/update/{id}', 'NhanVienController@updateNV')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_AdminNhanVien_Update');
    Route::get('/danh-sach-nhan-vien/delete/{id}', 'NhanVienController@deleteNhanVien')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_AdminNhanVien_Detele');
    //Hệ Số Lương
    Route::get('/danh-sach-he-so-luong/lists', 'HeSoLuongController@danhSachHeSoLuong')->name('route_BackEnd_HeSoLuong_List');
    Route::match(['get', 'post'], '/he-sao-luong/add', 'HeSoLuongController@themHeSoLuong')->name('route_BackEnd_HeSoLuong_Add');
    Route::get('/danh-sach-cong-nhan-vien/list/{id}', 'NhanVienController@danhSachCong')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_AdminCongNhanVien_List');
});
$middleware = ['checknhanvien.expired'];
//Phân quyền nhân viên


Route::middleware(['checknhanvien.expired','auth'])->group(function () {
    //Chấm công nhân viên
    Route::get('/cong-nhan-vien/list/{id}', 'NhanVienController@frChamCongNhanVien')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_CongNhanVien_List');
    Route::get('/chitiet-nhanvien/detail/{id}', 'NhanVienController@chiTietThongTinNV')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_FrNhanVien_List');
    Route::get('/chitiet-taikhoan-nhanvien/detail/{id}', 'NhanVienController@taiKhoanNhanVien')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_FrTaiKhoanNhanVien_List');
    Route::get('/nhanvien-lichsuluong/lists/{id}', 'HeSoLuongController@lichSuLuong')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_FrLichSuNhanVien_List');
    Route::post('/nhan-vien-tai-khoan/update/{id}', 'NhanVienController@updateMatKhau')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_AdminNhanVienTaiKhoan_Update');

});