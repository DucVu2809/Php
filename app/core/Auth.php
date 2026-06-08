<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\User;

class Auth
{
    private const KEY = 'auth_user_id';

    public static function attempt(string $email, string $password): bool
    {
        $user = (new User())->findByEmail($email);
        if ($user === null || (int) $user['is_active'] !== 1) {
            return false;
        }
        if (!password_verify($password, $user['password'])) {
            return false;
        }
        $_SESSION[self::KEY] = (int) $user['id'];
        return true;
    }

    public static function login(int $userId): void
    {
        $_SESSION[self::KEY] = $userId;
    }

    public static function logout(): void
    {
        unset($_SESSION[self::KEY]);
    }

    public static function check(): bool
    {
        return isset($_SESSION[self::KEY]);
    }

    public static function id(): ?int
    {
        return isset($_SESSION[self::KEY]) ? (int) $_SESSION[self::KEY] : null;
    }

    public static function user(): ?array
    {
        $id = self::id();
        return $id === null ? null : (new User())->find($id);
    }

    public static function isAdmin(): bool
    {
        $user = self::user();
        return $user !== null && $user['role'] === 'admin';
    }
}
