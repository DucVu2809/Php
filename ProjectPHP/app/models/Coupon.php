<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

class Coupon extends Model
{
    protected string $table = 'coupons';

    public function active(): array
    {
        return Database::run(
            "SELECT * FROM `coupons` ORDER BY `id` DESC"
        )->fetchAll();
    }

    public function findValid(string $code): ?array
    {
        $row = Database::run(
            "SELECT * FROM `coupons`
             WHERE `code` = :code AND `is_active` = 1
               AND (`expires_at` IS NULL OR `expires_at` >= CURDATE())
             LIMIT 1",
            ['code' => $code]
        )->fetch();
        return $row ?: null;
    }

    public function discountFor(array $coupon, float $subtotal): float
    {
        if ($subtotal < (float) $coupon['min_order']) {
            return 0.0;
        }
        if ($coupon['type'] === 'percent') {
            return round($subtotal * (float) $coupon['value'] / 100);
        }
        return min((float) $coupon['value'], $subtotal);
    }
}
