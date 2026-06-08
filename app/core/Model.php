<?php

/**
 * Lớp Model cơ sở theo mẫu Active Record rút gọn.
 *
 * Mỗi Model con khai báo $table; các thao tác CRUD thông dụng được
 * cài sẵn ở đây để model con kế thừa, tránh lặp code truy vấn.
 */

declare(strict_types=1);

namespace App\Core;

use PDO;

abstract class Model
{
    /** @var string Tên bảng tương ứng với model. */
    protected string $table = '';

    /** @var string Khoá chính của bảng. */
    protected string $primaryKey = 'id';

    /**
     * Lấy tất cả bản ghi (có thể giới hạn số lượng).
     *
     * @return array<int,array<string,mixed>>
     */
    public function all(?int $limit = null): array
    {
        $sql = "SELECT * FROM `{$this->table}`";
        if ($limit !== null) {
            $sql .= ' LIMIT ' . (int) $limit;
        }
        return Database::run($sql)->fetchAll();
    }

    /**
     * Tìm một bản ghi theo khoá chính.
     *
     * @return array<string,mixed>|null
     */
    public function find(int $id): ?array
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id LIMIT 1";
        $row = Database::run($sql, ['id' => $id])->fetch();
        return $row ?: null;
    }

    /**
     * Tìm một bản ghi theo cặp cột = giá trị.
     *
     * @return array<string,mixed>|null
     */
    public function findBy(string $column, mixed $value): ?array
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE `{$column}` = :value LIMIT 1";
        $row = Database::run($sql, ['value' => $value])->fetch();
        return $row ?: null;
    }

    /**
     * Đếm số bản ghi khớp điều kiện WHERE (chuỗi an toàn do code tự dựng).
     *
     * @param array<string,mixed> $params
     */
    public function count(string $where = '1', array $params = []): int
    {
        $sql = "SELECT COUNT(*) AS total FROM `{$this->table}` WHERE {$where}";
        return (int) Database::run($sql, $params)->fetch()['total'];
    }

    /**
     * Thêm bản ghi mới, trả về id vừa tạo.
     *
     * @param array<string,mixed> $data
     */
    public function create(array $data): int
    {
        $columns      = array_keys($data);
        $placeholders = array_map(static fn (string $c): string => ':' . $c, $columns);
        $columnList   = implode(', ', array_map(static fn (string $c): string => "`{$c}`", $columns));

        $sql = "INSERT INTO `{$this->table}` ({$columnList}) VALUES (" . implode(', ', $placeholders) . ')';
        Database::run($sql, $data);

        return (int) Database::connection()->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $sets = implode(', ', array_map(static fn (string $c): string => "`{$c}` = :{$c}", array_keys($data)));
        $data['__id'] = $id;
        Database::run("UPDATE `{$this->table}` SET {$sets} WHERE `{$this->primaryKey}` = :__id", $data);
    }

    public function delete(int $id): void
    {
        Database::run("DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id", ['id' => $id]);
    }

    protected function db(): PDO
    {
        return Database::connection();
    }
}
