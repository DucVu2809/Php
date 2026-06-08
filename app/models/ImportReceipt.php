<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\Model;
use Throwable;

class ImportReceipt extends Model
{
    protected string $table = 'import_receipts';

    public function createWithDetails(array $receipt, array $details): int
    {
        $pdo = Database::connection();
        $pdo->beginTransaction();
        try {
            $receiptId = $this->create($receipt);

            $stmt = $pdo->prepare(
                "INSERT INTO `import_receipt_details`
                    (`receipt_id`, `product_id`, `quantity`, `cost_price`)
                 VALUES (:receipt_id, :product_id, :quantity, :cost_price)"
            );
            $bump = $pdo->prepare("UPDATE `products` SET `stock` = `stock` + :q WHERE `id` = :id");

            foreach ($details as $line) {
                $stmt->execute([
                    'receipt_id' => $receiptId,
                    'product_id' => $line['product_id'],
                    'quantity'   => $line['quantity'],
                    'cost_price' => $line['cost_price'],
                ]);
                $bump->execute(['q' => $line['quantity'], 'id' => $line['product_id']]);
            }

            $pdo->commit();
            return $receiptId;
        } catch (Throwable $exception) {
            $pdo->rollBack();
            throw $exception;
        }
    }

    public function allWithUser(): array
    {
        return Database::run(
            "SELECT r.*, u.name AS created_by_name
             FROM `import_receipts` r
             LEFT JOIN `users` u ON u.id = r.created_by
             ORDER BY r.created_at DESC"
        )->fetchAll();
    }

    public function details(int $receiptId): array
    {
        return Database::run(
            "SELECT d.*, p.name AS product_name
             FROM `import_receipt_details` d
             LEFT JOIN `products` p ON p.id = d.product_id
             WHERE d.receipt_id = :id",
            ['id' => $receiptId]
        )->fetchAll();
    }

    public function generateCode(): string
    {
        return 'PN' . date('ymd') . strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
    }
}
