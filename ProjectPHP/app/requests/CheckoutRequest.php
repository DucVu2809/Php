<?php

declare(strict_types=1);

namespace App\Requests;

class CheckoutRequest
{
    public static function validate(array $data): array
    {
        $errors = [];

        if (trim($data['customer_name'] ?? '') === '') {
            $errors['customer_name'] = 'Vui lòng nhập họ và tên người nhận.';
        }

        $phone = trim($data['customer_phone'] ?? '');
        if ($phone === '') {
            $errors['customer_phone'] = 'Vui lòng nhập số điện thoại.';
        } elseif (!preg_match('/^0\d{9,10}$/', $phone)) {
            $errors['customer_phone'] = 'Số điện thoại không hợp lệ (VD: 0901234567).';
        }

        if (trim($data['customer_address'] ?? '') === '') {
            $errors['customer_address'] = 'Vui lòng nhập địa chỉ giao hàng.';
        }

        $email = trim($data['customer_email'] ?? '');
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['customer_email'] = 'Email không hợp lệ.';
        }

        return $errors;
    }
}
