# Cơ sở dữ liệu – đề tài quản lý sản phẩm

## 5 bảng trọng tâm

| Bảng | Chức năng | Khóa chính / Liên kết |
|---|---|---|
| `users` | Lưu tài khoản người dùng | `id`, `email` unique, mật khẩu băm, `is_active` |
| `roles` | Vai trò `admin` / `customer` | `id`, `name`, `guard_name` |
| `model_has_roles` | Gán vai trò cho người dùng | `role_id` tham chiếu `roles.id`; `model_id` và `model_type` là liên kết đa hình theo chuẩn Spatie |
| `food_categories` | Danh mục | `id`; `slug` unique |
| `foods` | Tên, mô tả, giá, kho, ảnh, trạng thái | `id`; `category_id` tham chiếu `food_categories.id`; `slug` unique |

## Quan hệ
- 1 `food_categories` có N `foods` (`foods.category_id`).
- 1 `roles` được gán cho N tài khoản thông qua `model_has_roles`.
- Mỗi tài khoản demo được gán một vai trò; Spatie cho phép mô hình N-N nếu mở rộng về sau.
- Các bảng hệ thống `sessions`, `cache`, `jobs`, v.v. vẫn được tạo bởi Laravel.
- Những bảng `orders`, `chat_sessions`, `chat_messages` vẫn nằm trong migration gốc để tương thích dữ liệu, nhưng ngoài phạm vi bản QLSP này.

## Quyền
- `admin`: CRUD danh mục, CRUD sản phẩm, xem catalog.
- `customer`: xem catalog, tìm kiếm, xem chi tiết, chỉnh sửa hồ sơ của chính mình; truy cập `/admin/*` bị chặn (HTTP 403).

## Lưu ý nghiệp vụ
- Không cho xóa danh mục khi đang chứa sản phẩm; mặc dù migration gốc cấu hình cascade, controller sẽ chủ động chặn.
- Không cho xóa sản phẩm có `orders` liên kết từ dữ liệu nguồn cũ (FK RESTRICT).
- Ảnh minh họa SVG có sẵn trong `public/images/menu`; ảnh mới upload lưu trên disk `public` của Laravel.
