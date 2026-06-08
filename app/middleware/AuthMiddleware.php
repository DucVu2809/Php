<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;

class AuthMiddleware
{
    public static function handle(): void
    {
        if (!Auth::check()) {
            flash('error', 'Vui lòng đăng nhập để tiếp tục.');
            header('Location: ' . BASE_URL . '/dang-nhap');
            exit;
        }
    }
}
