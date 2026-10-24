# Cập nhật gộp đăng nhập – Universal Tea

## Trang đăng nhập mới
Cả Admin và Khách truy cập: **http://127.0.0.1:8000/login**.

Sau khi đăng nhập, website tự kiểm tra vai trò trong database:
- Admin: `admin@universaltea.test` / `password` → `http://127.0.0.1:8000/admin/foods`.
- Khách: `test@example.com` / `password` → `http://127.0.0.1:8000/thuc-pham`.

Lưu ý: Mật khẩu trên chỉ dành cho demo máy cá nhân. Khi triển khai công khai phải đổi mật khẩu.

## Nếu website đã chạy và đã có dữ liệu, ảnh thật
**Nên dùng ZIP bản vá:** sao lưu thư mục source, mở file ZIP bản vá, sao chép toàn bộ file bên trong vào thư mục đang chạy (chứa `artisan`) và chấp nhận ghi đè những file trùng tên.
Không ghi đè `.env`, SQLite (`database/database.sqlite`) hay ảnh đã tải.
Sau đó chạy CMD tại thư mục source:

```cmd
php artisan optimize:clear
php artisan serve
```

Nếu web đã đang chạy, dừng bằng Ctrl+C trước khi bật lại. Không cần chạy `migrate:fresh`, `composer install`, `npm install`, hoặc `npm run build` khi dùng bản vá này.

## Nếu cài mới từ ZIP đầy đủ
Giải nén vào thư mục riêng, chạy `CAI_DAT_LAN_DAU.cmd` một lần, sau đó chạy `CHAY_WEB.cmd`.
Có thể chạy `TAI_ANH_THUC_TE.cmd` để lấy ảnh chụp thật nếu muốn.

## Kiểm tra phân quyền
1. Vào `/login`, đăng nhập Admin → được vào `/admin/foods` và có nút Thêm/Sửa/Xóa.
2. Đăng xuất, vào **cùng** `/login`, đăng nhập Khách → được vào `/thuc-pham`.
3. Khi còn đăng nhập Khách, nhập trực tiếp `/admin/foods` → HTTP 403.
4. Đường dẫn `/adminlogin` cũ tự chuyển đến `/login`.

Kiểm thử tự động (khi đã cài vendor):
```cmd
php artisan test tests/Feature/Auth/UnifiedLoginTest.php
```
