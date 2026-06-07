<?php

/**
 * Model Cấu hình website (key/value).
 *
 * Nạp toàn bộ cấu hình một lần rồi cache trong bộ nhớ để dùng lại
 * trong suốt request (layout gọi nhiều lần: hotline, địa chỉ, email...).
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

class Setting extends Model
{
    protected string $table = 'settings';

    /** @var array<string,string>|null Cache cấu hình trong RAM. */
    private static ?array $cache = null;

    /**
     * Lấy giá trị cấu hình theo key, trả về $default nếu không có.
     */
    public function get(string $key, string $default = ''): string
    {
        return $this->load()[$key] ?? $default;
    }

    /**
     * Lấy toàn bộ cấu hình dưới dạng mảng key => value.
     *
     * @return array<string,string>
     */
    public function load(): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        $rows = Database::run("SELECT `key`, `value` FROM `settings`")->fetchAll();
        self::$cache = [];
        foreach ($rows as $row) {
            self::$cache[$row['key']] = (string) $row['value'];
        }
        return self::$cache;
    }

    public function set(string $key, string $value): void
    {
        Database::run(
            "INSERT INTO `settings` (`key`, `value`) VALUES (:k, :v)
             ON DUPLICATE KEY UPDATE `value` = :v2",
            ['k' => $key, 'v' => $value, 'v2' => $value]
        );
        self::$cache = null;
    }
}
