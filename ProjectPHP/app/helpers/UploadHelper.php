<?php

declare(strict_types=1);

namespace App\Helpers;

class UploadHelper
{
    private const ALLOWED = [
        'image/jpeg'    => 'jpg',
        'image/png'     => 'png',
        'image/webp'    => 'webp',
        'image/svg+xml' => 'svg',
    ];

    public static function image(array $file, string $subdir = 'products'): ?string
    {
        if (!isset($file['tmp_name']) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return null;
        }

        $type = mime_content_type($file['tmp_name']);
        if (!isset(self::ALLOWED[$type])) {
            return null;
        }

        $dir = UPLOAD_PATH . '/' . trim($subdir, '/');
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $name = date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . self::ALLOWED[$type];
        if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $name)) {
            return null;
        }

        return $name;
    }
}
