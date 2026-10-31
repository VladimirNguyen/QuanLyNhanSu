# QuanLyNhanSu

Ứng dụng Laravel quản lý nhân viên, phòng ban, chấm công, lương và tài sản.

## Chạy local

Dùng PHP 8.1 hoặc 8.2 và Composer cho bộ thư viện hiện tại. Bật mbstring, XML, PDO/MySQL, cURL, fileinfo.

```sh
composer install
cp .env.example .env
php artisan key:generate
```

Tạo database MySQL, cấu hình DB_* trong .env, sau đó import `quan_ly_nhan_su.sql` **vào database local trống**. Không import đè dữ liệu đang sử dụng.

```sh
php artisan storage:link
php artisan serve
```

Quản trị viên có `level=1`, nhân viên có `level=2`; tài khoản hoạt động có `status=1`. Tài khoản tự đăng ký cần được quản trị viên liên kết với hồ sơ nhân viên trước khi đăng nhập. Khi duyệt hồ sơ, hệ thống tái sử dụng tài khoản nhân viên có cùng email nếu chưa liên kết với hồ sơ khác.

## Kiểm thử

```sh
php artisan test
```

Các test AccessAndAttendanceTest dùng SQLite trong bộ nhớ, không đụng tới database nghiệp vụ. Cần bật pdo_sqlite. Tạo APP_KEY trong .env trước khi chạy toàn bộ bộ test.

Các kiểm tra gồm: nhân viên không truy cập quản trị; không đổi mật khẩu hoặc chấm công cho người khác; đổi mật khẩu của chính mình; chấm công trùng; GET không ghi công; đăng ký chưa có hồ sơ; tách route biên bản và phụ cấp.

Bản sửa giữ nguyên mật khẩu mặc định khi tạo tài khoản nhân viên theo yêu cầu. Test đã chạy với PHP 8.3.6 và SQLite (8 tests / 22 assertions); môi trường kiểm thử phải bỏ kiểm tra giới hạn PHP phía trên của lock file để cài các dependency cũ. Khi chạy thông thường, dùng PHP 8.1/8.2 thay vì bỏ kiểm tra tương thích. Chưa kiểm thử toàn bộ nghiệp vụ bằng MySQL hoặc trình duyệt.
