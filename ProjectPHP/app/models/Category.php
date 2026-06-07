<?php

/**
 * Model Danh mục sản phẩm.
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

class Category extends Model
{
    protected string $table = 'categories';

    /**
     * Lấy các danh mục đang hiển thị, sắp theo thứ tự ưu tiên.
     *
     * @return array<int,array<string,mixed>>
     */
    public function active(): array
    {
        $sql = "SELECT * FROM `categories`
                WHERE `is_active` = 1
                ORDER BY `sort_order` ASC, `name` ASC";
        return Database::run($sql)->fetchAll();
    }

    /**
     * Tìm danh mục theo slug (đang hiển thị).
     *
     * @return array<string,mixed>|null
     */
    public function findBySlug(string $slug): ?array
    {
        $sql = "SELECT * FROM `categories` WHERE `slug` = :slug AND `is_active` = 1 LIMIT 1";
        $row = Database::run($sql, ['slug' => $slug])->fetch();
        return $row ?: null;
    }

    /**
     * Lấy danh mục kèm số lượng sản phẩm đang bán (cho menu / trang chủ).
     *
     * @return array<int,array<string,mixed>>
     */
    public function withProductCount(): array
    {
        $sql = "SELECT c.*, COUNT(p.id) AS product_count
                FROM `categories` c
                LEFT JOIN `products` p
                    ON p.category_id = c.id AND p.is_active = 1
                WHERE c.is_active = 1
                GROUP BY c.id
                ORDER BY c.sort_order ASC, c.name ASC";
        return Database::run($sql)->fetchAll();
    }
}
