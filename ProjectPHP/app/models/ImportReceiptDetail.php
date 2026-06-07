<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

class ImportReceiptDetail extends Model
{
    protected string $table = 'import_receipt_details';

    public function forReceipt(int $receiptId): array
    {
        return Database::run(
            "SELECT * FROM `import_receipt_details` WHERE `receipt_id` = :id",
            ['id' => $receiptId]
        )->fetchAll();
    }
}
