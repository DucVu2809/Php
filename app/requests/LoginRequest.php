<?php

declare(strict_types=1);

namespace App\Requests;

class LoginRequest
{
    public static function validate(array $data): array
    {
        $errors = [];

        $email = trim($data['email'] ?? '');
        if ($email === '') {
            $errors['email'] = 'Vui lòng nhập email.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ.';
        }

        if (($data['password'] ?? '') === '') {
            $errors['password'] = 'Vui lòng nhập mật khẩu.';
        }

        return $errors;
    }
}
