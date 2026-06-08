<?php

/**
 * Model Thương hiệu / Hãng sản xuất.
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

class Brand extends Model
{
    protected string $table = 'brands';

    /**
     * Lấy các thương hiệu đang hiển thị, sắp theo tên.
     *
     * @return array<int,array<string,mixed>>
     */
    public function active(): array
    {
        $sql = "SELECT * FROM `brands` WHERE `is_active` = 1 ORDER BY `name` ASC";
        return Database::run($sql)->fetchAll();
    }
}
