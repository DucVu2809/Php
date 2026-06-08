<?php

/**
 * Thông số kết nối cơ sở dữ liệu MySQL.
 *
 * Mặc định khớp với cấu hình XAMPP (user "root", không mật khẩu).
 * Khi triển khai thật, đổi mật khẩu và có thể nạp từ biến môi trường.
 */

declare(strict_types=1);

return [
    'host'     => getenv('DB_HOST') ?: '127.0.0.1',
    'port'     => getenv('DB_PORT') ?: '3306',
    'database' => getenv('DB_NAME') ?: 'xinmai_shop',
    'username' => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASS') ?: '',
    'charset'  => 'utf8mb4',
];
