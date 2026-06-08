<?php

/**
 * Lớp Controller cơ sở.
 *
 * Cung cấp các tiện ích dùng chung cho mọi controller: render view,
 * chuyển hướng, và trả JSON cho các yêu cầu AJAX (giỏ hàng...).
 */

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    /**
     * Render view qua layout.
     *
     * @param array<string,mixed> $data
     */
    protected function view(string $view, array $data = [], string $layout = 'main'): void
    {
        View::render($view, $data, $layout);
    }

    /**
     * Chuyển hướng tới một đường dẫn nội bộ rồi dừng thực thi.
     */
    protected function redirect(string $path): void
    {
        header('Location: ' . BASE_URL . $path);
        exit;
    }

    /**
     * Trả dữ liệu JSON (dùng cho AJAX giỏ hàng).
     *
     * @param array<string,mixed> $data
     */
    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Lấy 1 tham số từ query string với giá trị mặc định.
     */
    protected function query(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }
}
