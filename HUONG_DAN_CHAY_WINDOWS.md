# UNIVERSAL TEA – BẢN ĐỒ ÁN QUẢN LÝ SẢN PHẨM

## 1. Giới thiệu
Website Laravel 11 / PHP 8.2+, SQLite, Blade + Bootstrap, có 2 vai trò: **admin** và **customer**.
Admin quản lý danh mục và thực phẩm: xem, thêm, sửa, xóa, tìm kiếm, lọc, cập nhật giá / tồn kho / ảnh / trạng thái.
Khách hàng xem thực đơn, tìm kiếm, lọc theo danh mục, xem chi tiết; **không được truy cập CRUD**.
Bản đồ án này không cung cấp giao diện hoặc endpoint đặt hàng, giỏ hàng và chat.

## 2. Cài đặt lần đầu (Windows CMD)
Mở CMD trong **thư mục chứa artisan và composer.json**:

```cmd
composer install
npm install
copy .env.example .env
if not exist database\database.sqlite type nul > database\database.sqlite
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan serve
```

**Đăng nhập chung cả Admin lẫn Khách:** `http://127.0.0.1:8000/login`. Hệ thống tự điều hướng dựa trên quyền. `/adminlogin` cũ sẽ chuyển sang `/login`.

| Vai trò | Email demo | Mật khẩu demo |
| --- | --- | --- |
| Admin | admin@universaltea.test | password |
| Khách | test@example.com | password |

**Lưu ý:** Các tài khoản này chỉ dành cho bài demo trên máy cá nhân, hãy đổi mật khẩu nếu đưa lên máy chủ công khai. Không chia sẻ file `.env`.

## 2b. Thêm ảnh chụp thực tế cho 30 món
Nhấp đúp `TAI_ANH_THUC_TE.cmd` khi máy có Internet. Script lấy ảnh chụp thật từ Pexels về `public/images/menu/real` và tự thay ảnh SVG cũ khi trang được tải lại. Không phải sửa database hay chạy lại seed. Nếu download thất bại, ảnh SVG dự phòng vẫn hiển thị. Nguồn và ghi chú ảnh nằm trong `HUONG_DAN_ANH_THUC_TE.md`.

## 3. Các lần chạy tiếp theo
```cmd
cd /d C:\Users\Admin\Downloads\BTB-3-PHP-main
php artisan serve
```
Nếu thư mục được đặt tên khác, sửa lại lệnh `cd` cho phù hợp.
Truy cập: `http://127.0.0.1:8000`.

## 4. Nếu đã có bản source cũ và database riêng
- **Không chạy `php artisan migrate:fresh --seed`** nếu muốn giữ dữ liệu cũ, vì nó xóa toàn bộ các bảng.
- Sao lưu file `.env`, `database/database.sqlite`, và thư mục `storage/app/public` của source cũ.
- Giải nén bản ZIP mới sang **thư mục khác**, sao chép các tệp dữ liệu cũ vào bản mới (nếu cần), rồi chạy `php artisan migrate --seed`.
- `migrate --seed` sẽ thêm các sản phẩm demo còn thiếu, không ghi đè thông tin món bạn đã chỉnh tay. Nếu món demo cũ thiếu ảnh thì bổ sung ảnh vector.
- Ảnh tự upload nằm trong `storage/app/public/food-images`, cần chạy `php artisan storage:link` để hiện ảnh.

## 5. Nội dung bổ sung
- 5 danh mục, 30 sản phẩm mẫu với giá, tồn kho, mô tả và **30 ảnh minh họa SVG cục bộ**.
- Lọc theo tên/danh mục/trạng thái; xem chi tiết, hiển thị ảnh, xác nhận trước khi xóa.
- Tên sản phẩm trùng vẫn tạo được slug riêng (thêm hậu tố -2, -3...).
- Chặn xóa danh mục đang chứa sản phẩm; chặn xóa sản phẩm gắn với đơn hàng cũ, trả về thông báo dễ hiểu.
- Account đăng ký mới được cấp vai trò customer, không thể tự nhận admin.

## 6. Test quyền và CRUD
Admin đăng nhập `/login` → tự chuyển đến `/admin/foods` → tạo/sửa/xóa món.
Khách đăng nhập cùng `/login` → tự chuyển đến `/thuc-pham` → thử mở `/admin/foods`: server phải trả lỗi **403**.

Sau khi cài dependencies, có thể chạy kiểm thử bằng:
```cmd
php artisan test tests/Feature/ProductManagementTest.php
```

**Giới hạn kiểm chứng:** source đã được kiểm tra cú pháp PHP và đối chiếu tệp, nhưng chưa có môi trường Laravel đầy đủ để chạy test end-to-end ở nơi đóng gói. Vui lòng chạy kiểm thử trên máy Windows của bạn.

## 7. Cấu trúc cơ sở dữ liệu (trọng tâm)
`users`, `roles`, `model_has_roles`, `foods`, `food_categories` (và các bảng hệ thống hỗ trợ Laravel).
Các bảng order/chat của source gốc vẫn có migration/model để không phá dữ liệu cũ nhưng route đặt hàng/chat đã bị gỡ trong bản demo QLSP.
Ảnh trong `public/images/menu` là **hình minh họa vector tự thiết kế**, không phải ảnh chụp sản phẩm thật.

## 8. Sơ đồ 5 bảng cho phần báo cáo
Xem tệp `SO_DO_CSDL_QLSP.md` để trình bày 5 bảng tập trung đúng chủ đề.

## 9. Cập nhật: Gộp đăng nhập chung (bản mới)
- Chỉ có **một form đăng nhập** tại `/login` cho cả Admin lẫn Khách.
- Đăng nhập đúng tài khoản `admin@universaltea.test` được đưa tới `/admin/foods`.
- Đăng nhập đúng tài khoản `test@example.com` được đưa tới `/thuc-pham`.
- Phân quyền thực thi ở server bằng middleware `admin`, nên Khách truy cập trực tiếp `/admin/foods` sẽ nhận **403**.
- Các đường dẫn cũ `/adminlogin` được chuyển sang `/login` để không hỏng bookmark.
- **Không cần** chạy lại `migrate`, `migrate:fresh`, `migrate --seed` hoặc `npm install` chỉ vì thay đổi đăng nhập.
- Nếu cập nhật bằng bản vá ZIP: chép đè các file trong gói vá vào thư mục chứa `artisan`, sau đó chạy `php artisan optimize:clear`, tắt/bật lại `CHAY_WEB.cmd`. Dữ liệu cũ, ảnh đã tải và file `.env` được giữ nguyên.
- Chạy kiểm thử: `php artisan test tests/Feature/Auth/UnifiedLoginTest.php`.
