<?php

declare(strict_types=1);

namespace App\Requests;

class ProductRequest
{
    public static function validate(array $data): array
    {
        $errors = [];

        if (trim($data['name'] ?? '') === '') {
            $errors['name'] = 'Vui lòng nhập tên sản phẩm.';
        }

        if (!isset($data['price']) || !is_numeric($data['price']) || (float) $data['price'] < 0) {
            $errors['price'] = 'Giá bán không hợp lệ.';
        }

        if (trim($data['category_id'] ?? '') === '') {
            $errors['category_id'] = 'Vui lòng chọn danh mục.';
        }

        return $errors;
    }
}
