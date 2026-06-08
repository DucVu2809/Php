# XINMAI — Tổng kho máy xây dựng (Website PHP)

Website thương mại điện tử bán máy móc, thiết bị xây dựng, xây bằng **PHP 8 thuần
theo mô hình MVC** (không dùng framework) + **MySQL/MariaDB**, giao diện lấy cảm
hứng từ các trang tổng kho máy xây dựng.

> Đồ án môn **Lập trình Web PHP**. Phần AI (PhishGuard - phát hiện email lừa đảo)
> là deliverable riêng, độc lập với website này.

---

## 1. Yêu cầu môi trường

- PHP >= 8.0 (đã test trên PHP 8.0.30 của XAMPP)
- MySQL hoặc MariaDB (XAMPP)
- Trình duyệt web

## 2. Cài đặt cơ sở dữ liệu

Mở terminal tại thư mục dự án và import (đường dẫn theo XAMPP mặc định):

```bash
# Tạo bảng
"D:/Xampp/mysql/bin/mysql.exe" -u root < database/schema.sql

# Nạp dữ liệu mẫu (16 sản phẩm, 8 danh mục, 8 hãng, 1 admin)
"D:/Xampp/mysql/bin/mysql.exe" -u root < database/seed.sql
```

Hoặc dùng **phpMyAdmin**: tạo DB `xinmai_shop` rồi import lần lượt 2 file trên.

Thông tin kết nối nằm ở [config/database.php](config/database.php) — mặc định
`root` / không mật khẩu, khớp XAMPP.

## 3. Chạy website

**Cách 1 — PHP built-in server (nhanh, không cần Apache):**

```bash
"D:/Xampp/php/php.exe" -S localhost:8000 -t public public/router.php
```

Mở trình duyệt: <http://localhost:8000>

**Cách 2 — Apache (XAMPP):** trỏ DocumentRoot vào thư mục `public/`, hoặc đặt cả
dự án trong `htdocs` rồi chỉnh `BASE_URL` trong [config/config.php](config/config.php)
cho khớp đường dẫn con.

### Tài khoản quản trị mẫu
- Email: `admin@xinmai.vn`
- Mật khẩu: `admin123`

*(Trang quản trị sẽ được hoàn thiện ở giai đoạn tiếp theo.)*

---

## 4. Cấu trúc thư mục

```
ProjectPHP/
├── public/                 # Thư mục web gốc (DocumentRoot)
│   ├── index.php           # Front controller — điểm vào duy nhất
│   ├── router.php          # Router cho php -S
│   ├── .htaccess           # Rewrite cho Apache
│   └── assets/             # css / js / images / uploads
├── app/
│   ├── core/               # Khung MVC: Router, Database, Model, Controller, View, Cart
│   ├── controllers/        # HomeController, ProductController, CartController
│   ├── models/             # Product, Category, Brand, Setting
│   ├── helpers/            # functions.php (url, asset, e...), FormatHelper
│   └── views/              # layouts, home, products, cart, pages, errors, partials
├── config/                 # config.php, database.php
├── routes/web.php          # Khai báo định tuyến
└── database/               # schema.sql, seed.sql
```

## 5. Tính năng đã hoàn thành (giai đoạn 1)

- [x] Khung MVC + Router hỗ trợ tham số động, autoloader PSR-4
- [x] Trang chủ: banner, danh mục, sản phẩm nổi bật & mới
- [x] Trang danh mục + lọc (hãng, giá), sắp xếp, phân trang
- [x] Tìm kiếm sản phẩm
- [x] Trang chi tiết: thư viện ảnh, thông số kỹ thuật, sản phẩm liên quan
- [x] Giỏ hàng (session) thêm/sửa/xoá qua AJAX
- [x] Trang giới thiệu, liên hệ, 404
- [x] Giao diện responsive (desktop / tablet / mobile)

## 6. Giai đoạn tiếp theo (dự kiến)

- [ ] Đăng ký / đăng nhập khách hàng
- [ ] Đặt hàng (checkout) + áp mã giảm giá
- [ ] Trang quản trị: sản phẩm, đơn hàng, người dùng, kho, coupon
