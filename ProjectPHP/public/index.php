<?php

/**
 * Front Controller — điểm vào duy nhất của toàn bộ ứng dụng.
 *
 * Mọi request đều được điều hướng về đây (qua .htaccess hoặc php -S router),
 * sau đó Router phân phối tới controller phù hợp.
 */

declare(strict_types=1);

// Header HTML phải có charset utf-8 từ đầu (trước output)
header('Content-Type: text/html; charset=utf-8');

use App\Core\Router;

require dirname(__DIR__) . '/app/core/bootstrap.php';

/** @var Router $router */
$router = require ROOT_PATH . '/routes/web.php';

try {
    $router->dispatch(
        $_SERVER['REQUEST_METHOD'],
        $_SERVER['REQUEST_URI']
    );
} catch (Throwable $exception) {
    http_response_code(500);
    if (APP_DEBUG) {
        echo '<pre style="padding:20px;font:14px/1.5 monospace;color:#b00;">';
        echo 'Lỗi hệ thống: ' . e($exception->getMessage()) . "\n\n";
        echo e($exception->getFile()) . ':' . $exception->getLine() . "\n\n";
        echo e($exception->getTraceAsString());
        echo '</pre>';
    } else {
        echo '<h1>500 - Đã có lỗi xảy ra</h1><p>Vui lòng thử lại sau.</p>';
    }
}
