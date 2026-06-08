<?php

/**
 * Router cho PHP built-in server (php -S).
 *
 * Cách chạy nhanh không cần Apache:
 *     php -S localhost:8000 -t public public/router.php
 * Sau đó mở http://localhost:8000
 *
 * - File tĩnh có thật (css, js, ảnh): trả về trực tiếp.
 * - Còn lại: chuyển cho front controller index.php xử lý định tuyến.
 */

declare(strict_types=1);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$file = __DIR__ . $path;

if ($path !== '/' && is_file($file)) {
    return false;   // để built-in server tự phục vụ file tĩnh
}

require __DIR__ . '/index.php';
