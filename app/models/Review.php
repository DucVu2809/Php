<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

class Review extends Model
{
    protected string $table = 'reviews';

    public function adminAll(): array
    {
        return Database::run(
            "SELECT r.*, p.name AS product_name, p.slug AS product_slug
             FROM `reviews` r
             LEFT JOIN `products` p ON p.id = r.product_id
             ORDER BY r.created_at DESC"
        )->fetchAll();
    }

    public function approvedFor(int $productId): array
    {
        return Database::run(
            "SELECT * FROM `reviews`
             WHERE `product_id` = :id AND `is_approved` = 1
             ORDER BY `created_at` DESC",
            ['id' => $productId]
        )->fetchAll();
    }

    public function summaryFor(int $productId): array
    {
        $row = Database::run(
            "SELECT COUNT(*) AS c, COALESCE(AVG(`rating`), 0) AS a
             FROM `reviews` WHERE `product_id` = :id AND `is_approved` = 1",
            ['id' => $productId]
        )->fetch();
        return ['count' => (int) $row['c'], 'avg' => round((float) $row['a'], 1)];
    }

    public function approve(int $id): void
    {
        Database::run("UPDATE `reviews` SET `is_approved` = 1 WHERE `id` = :id", ['id' => $id]);
    }

    public function countPending(): int
    {
        return (int) Database::run(
            "SELECT COUNT(*) AS c FROM `reviews` WHERE `is_approved` = 0"
        )->fetch()['c'];
    }
}
