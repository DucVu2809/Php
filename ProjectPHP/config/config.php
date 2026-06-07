<?php

/**
 * Cấu hình chung của ứng dụng.
 *
 * Gom mọi hằng số cấu hình về một chỗ để dễ thay đổi khi triển khai,
 * tránh rải "magic value" khắp mã nguồn.
 */

declare(strict_types=1);

// --- Thông tin website ----------------------------------------------------
define('APP_NAME',   'XINMAI - Tổng kho máy xây dựng');
define('APP_ENV',    'development');           // development | production
define('APP_DEBUG',  APP_ENV === 'development');

// --- Đường dẫn thư mục (tuyệt đối) ----------------------------------------
define('ROOT_PATH',   dirname(__DIR__));
define('APP_PATH',    ROOT_PATH . '/app');
define('VIEW_PATH',   APP_PATH . '/views');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('UPLOAD_PATH', PUBLIC_PATH . '/assets/uploads');

/**
 * Đường dẫn gốc website (base path) — TỰ ĐỘNG PHÁT HIỆN.
 *
 * Nhờ đó CSS, ảnh và các liên kết luôn đúng dù chạy theo cách nào:
 *   - PHP built-in server:  php -S localhost:8000 -t public public/router.php
 *     => DocumentRoot là /public, nằm "sâu hơn" thư mục dự án  => BASE_URL = ''
 *   - XAMPP, đặt trong htdocs/ProjectPHP, vào http://localhost/ProjectPHP/
 *     => BASE_URL = '/ProjectPHP'  (nhờ .htaccess ở gốc chuyển hướng vào /public)
 *
 * Muốn ép cứng giá trị, đặt biến môi trường APP_BASE_URL (vd '/ProjectPHP').
 */
if (($manualBase = getenv('APP_BASE_URL')) !== false) {
    define('BASE_URL', rtrim($manualBase, '/'));
} else {
    $docRoot  = str_replace('\\', '/', rtrim((string) ($_SERVER['DOCUMENT_ROOT'] ?? ''), '/'));
    $rootPath = str_replace('\\', '/', ROOT_PATH);

    // Nếu thư mục dự án nằm trong DocumentRoot => phần dư chính là base path.
    $detectedBase = '';
    if ($docRoot !== '' && stripos($rootPath, $docRoot) === 0) {
        $detectedBase = rtrim(substr($rootPath, strlen($docRoot)), '/');
    }
    define('BASE_URL', $detectedBase);
}

// --- Quy ước nghiệp vụ ----------------------------------------------------
define('PRODUCTS_PER_PAGE', 12);   // số sản phẩm mỗi trang danh mục
define('CURRENCY_SUFFIX',  '₫');   // ký hiệu tiền tệ hiển thị
