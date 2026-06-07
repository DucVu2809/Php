<?php

/**
 * Khởi động ứng dụng: nạp cấu hình, đăng ký autoloader, mở session.
 *
 * Được nạp một lần duy nhất từ front controller (public/index.php).
 */

declare(strict_types=1);

require dirname(__DIR__, 2) . '/config/config.php';

/**
 * Autoloader theo chuẩn PSR-4 rút gọn cho namespace gốc "App\".
 *
 * Quy ước: thư mục viết thường (core, controllers, models, helpers),
 * tên file class giữ nguyên PascalCase — vd App\Core\Database => app/core/Database.php
 */
spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relative = substr($class, strlen($prefix));   // vd: Core\Database
    $parts    = explode('\\', $relative);
    $fileName = array_pop($parts);                  // Database

    $dir = implode('/', array_map('strtolower', $parts));   // core
    $path = APP_PATH . '/' . ($dir !== '' ? $dir . '/' : '') . $fileName . '.php';

    if (is_file($path)) {
        require $path;
    }
});

// Nạp các hàm helper toàn cục (url, asset, e, format_price...)
require APP_PATH . '/helpers/functions.php';

// Mở session phục vụ giỏ hàng và đăng nhập
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hiển thị lỗi rõ ràng khi đang phát triển
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    ini_set('display_errors', '0');
}
