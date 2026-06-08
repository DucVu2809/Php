<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Core\Auth;

class AuthHelper
{
    public static function check(): bool
    {
        return Auth::check();
    }

    public static function user(): ?array
    {
        return Auth::user();
    }

    public static function isAdmin(): bool
    {
        return Auth::isAdmin();
    }

    public static function name(): string
    {
        $user = Auth::user();
        return $user['name'] ?? 'Khách';
    }
}
