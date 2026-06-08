<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\Model;
use Throwable;

class Order extends Model
{
    protected string $table = 'orders';

    public function createWithItems(array $order, array $items): int
    {
        $pdo = Database::connection();
        $pdo->beginTransaction();
        try {
            $orderId = $this->create($order);

            $stmt = $pdo->prepare(
                "INSERT INTO `order_items`
                    (`order_id`, `product_id`, `product_name`, `price`, `quantity`)
                 VALUES (:order_id, :product_id, :product_name, :price, :quantity)"
            );
            foreach ($items as $item) {
                $stmt->execute([
                    'order_id'     => $orderId,
                    'product_id'   => $item['id'],
                    'product_name' => $item['name'],
                    'price'        => $item['price'],
                    'quantity'     => $item['quantity'],
                ]);
            }

            $pdo->commit();
            return $orderId;
        } catch (Throwable $exception) {
            $pdo->rollBack();
            throw $exception;
        }
    }

    public function findByCode(string $code): ?array
    {
        $row = Database::run(
            "SELECT * FROM `orders` WHERE `code` = :code LIMIT 1",
            ['code' => $code]
        )->fetch();
        return $row ?: null;
    }

    public function items(int $orderId): array
    {
        return Database::run(
            "SELECT * FROM `order_items` WHERE `order_id` = :id",
            ['id' => $orderId]
        )->fetchAll();
    }

    public function recent(int $limit = 50): array
    {
        $stmt = Database::connection()->prepare(
            "SELECT * FROM `orders` ORDER BY `created_at` DESC LIMIT :limit"
        );
        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function generateCode(): string
    {
        return 'DH' . date('ymd') . strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
    }

    public function updateStatus(int $id, string $status): void
    {
        Database::run(
            "UPDATE `orders` SET `status` = :status WHERE `id` = :id",
            ['status' => $status, 'id' => $id]
        );
    }

    public function totalRevenue(): float
    {
        return (float) Database::run(
            "SELECT COALESCE(SUM(`total`), 0) AS s FROM `orders` WHERE `status` <> 'cancelled'"
        )->fetch()['s'];
    }

    public function countByStatus(?string $status = null): int
    {
        if ($status === null) {
            return (int) Database::run("SELECT COUNT(*) AS c FROM `orders`")->fetch()['c'];
        }
        return (int) Database::run(
            "SELECT COUNT(*) AS c FROM `orders` WHERE `status` = :s",
            ['s' => $status]
        )->fetch()['c'];
    }
}
