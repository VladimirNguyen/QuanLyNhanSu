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

## Bảng lương và phụ cấp nhân viên

- Admin: mục **Bảng lương tổng hợp** (`/bang-luong`), chọn tháng.
- Nhân viên: **Phụ cấp của tôi** và **Bảng lương của tôi**, chỉ đọc hồ sơ thuộc tài khoản đang đăng nhập.
- Công tính từ ngày có chấm vào đang hoạt động, không bắt buộc đã chấm ra. Một ngày chỉ tính một lần, dùng giờ vào sớm nhất nếu dữ liệu cũ bị trùng.
- Cấu hình tại `config/payroll.php`: bắt đầu ca 08:00, cho phép muộn 60 phút. Đúng 09:00 đủ công, sau 09:00 trừ 0,5 công mỗi ngày.
- Dữ liệu `he_so_luong.luong` đang là mức tiền lương tháng. Đơn giá ngày = mức tháng / 26; tổng = công được hưởng × đơn giá ngày + phụ cấp hoạt động (cộng một lần/tháng, không cộng trùng cùng loại). Phụ cấp cố định vẫn cộng khi chưa có công trong tháng.
- Đây là bảng **tạm tính theo mức lương/phụ cấp hiện tại**, không lưu chốt lịch sử, không tính tăng ca hoặc khấu trừ khác. Chưa gán mức lương sẽ báo rõ, không tự coi là lương 0.
- Không cần migration mới. Kiểm thử sau bổ sung: 12 test / 45 assertions đạt trên PHP 8.3.6 + SQLite; Blade biên dịch thành công. Chưa kiểm thử toàn bộ MySQL/trình duyệt.

## Sửa giờ chấm công

- Nhân viên chấm vào/kết ca bằng nút phía trên; bảng lịch sử không còn cột Công cụ.
- Quản lý (`level=1`, tài khoản hoạt động) mở **Theo dõi chấm công**, chọn ngày, bấm **Sửa giờ** ở dòng nhân viên, nhập giờ vào/ra và bấm **Lưu giờ**.
- Giờ vào bắt buộc; giờ ra có thể để trống nếu chưa kết ca, nếu nhập phải sau giờ vào trong cùng ngày. Có thể sửa bản ghi ngày trước; ngày làm và nhân viên của bản ghi được giữ nguyên.
- Kiểm thử AccessAndAttendanceTest: 14 tests / 75 assertions đạt với PHP 8.3.6 + SQLite, gồm phân quyền, kiểm tra giờ và biểu mẫu. Chưa kiểm thử trực tiếp trên trình duyệt/MySQL.
