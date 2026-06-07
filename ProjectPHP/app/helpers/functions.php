<?php

/**
 * Các hàm helper toàn cục dùng trong view và controller.
 *
 * Đặt ở dạng hàm thường (không namespace) để gọi nhanh trong template.
 */

declare(strict_types=1);

if (!function_exists('url')) {
    /**
     * Tạo URL nội bộ có thêm BASE_URL ở đầu.
     */
    function url(string $path = ''): string
    {
        return BASE_URL . '/' . ltrim($path, '/');
    }
}

if (!function_exists('asset')) {
    /**
     * Tạo đường dẫn tới tài nguyên tĩnh trong /assets.
     */
    function asset(string $path): string
    {
        return BASE_URL . '/assets/' . ltrim($path, '/');
    }
}

if (!function_exists('e')) {
    /**
     * Escape HTML để chống XSS khi in dữ liệu ra view.
     */
    function e(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('product_image')) {
    /**
     * Trả về URL ảnh sản phẩm; nếu thiếu file thì dùng ảnh mặc định.
     */
    function product_image(?string $file): string
    {
        if ($file && is_file(PUBLIC_PATH . '/assets/uploads/products/' . $file)) {
            return asset('uploads/products/' . $file);
        }
        return asset('images/no-image.svg');
    }
}

if (!function_exists('old')) {
    /**
     * Lấy lại giá trị form đã nhập sau khi submit lỗi (flash trong session).
     */
    function old(string $key, string $default = ''): string
    {
        return (string) ($_SESSION['_old'][$key] ?? $default);
    }
}

if (!function_exists('flash')) {
    function flash(string $key, ?string $message = null): ?string
    {
        if ($message !== null) {
            $_SESSION['_flash'][$key] = $message;
            return null;
        }
        $value = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);
        return $value;
    }
}
