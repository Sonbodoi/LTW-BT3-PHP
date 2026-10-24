# Universal Tea – Website quản lý sản phẩm (đồ án PHP)

**Yêu cầu:** Một trang đăng nhập chung `/login`, phân quyền Admin/Khách; Admin thực hiện CRUD sản phẩm và danh mục; Khách chỉ xem, tìm kiếm và lọc sản phẩm. **Không có giỏ hàng/đặt hàng.**

- PHP 8.2+ / Laravel 11 / SQLite / Bootstrap / Vite.
- 5 danh mục, **30 sản phẩm** có minh họa SVG đi kèm. **Ảnh chụp thật từ Pexels:** chạy `TAI_ANH_THUC_TE.cmd` một lần khi có Internet để tải 30 ảnh, web tự thay ảnh SVG bằng ảnh thật.
- **Cả hai vai trò đăng nhập qua `http://127.0.0.1:8000/login`**; hệ thống tự chuyển Admin vào `/admin/foods`, Khách vào `/thuc-pham`. Đường dẫn `/adminlogin` cũ tự chuyển về `/login`.
- Hai tài khoản demo được tạo qua `php artisan migrate --seed`:
  - Admin: `admin@universaltea.test` / `password`
  - Customer: `test@example.com` / `password`
- Tài liệu hướng dẫn đầy đủ: **[HUONG_DAN_CHAY_WINDOWS.md](HUONG_DAN_CHAY_WINDOWS.md)**.
- Windows: chạy **`CAI_DAT_LAN_DAU.cmd`** lần đầu, sau đó dùng **`CHAY_WEB.cmd`** để bật website.

Tài khoản demo không phù hợp cho môi trường public. Không đưa `.env` vào kho mã nguồn.

## Ảnh chụp thật
Chạy `TAI_ANH_THUC_TE.cmd` một lần để tải ảnh thật vào `public/images/menu/real`, sau đó nhấn Ctrl+F5 trên trình duyệt. Chi tiết và nguồn ảnh: `HUONG_DAN_ANH_THUC_TE.md`. Ảnh sẽ không nằm sẵn trong ZIP vì không thể đóng gói trực tiếp ảnh từ nguồn trong môi trường này.

**Nếu nâng cấp từ bản cũ:** xem `HUONG_DAN_GOP_DANG_NHAP.md`. Không cần xóa database hay tải lại ảnh.
