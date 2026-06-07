<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;

class AdminMiddleware
{
    public static function handle(): void
    {
        if (!Auth::check()) {
            flash('error', 'Vui lòng đăng nhập để tiếp tục.');
            header('Location: ' . BASE_URL . '/dang-nhap');
            exit;
        }
        if (!Auth::isAdmin()) {
            http_response_code(403);
            flash('error', 'Bạn không có quyền truy cập khu vực quản trị.');
            header('Location: ' . BASE_URL . '/');
            exit;
        }
    }
}
