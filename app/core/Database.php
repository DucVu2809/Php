<?php

/**
 * Lớp truy cập cơ sở dữ liệu (PDO Singleton).
 *
 * Mở đúng một kết nối PDO dùng chung cho toàn bộ vòng đời request và
 * bọc sẵn các thao tác truy vấn thường dùng để Model gọi lại cho gọn.
 */

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use RuntimeException;

class Database
{
    /** @var PDO|null Kết nối dùng chung (lazy-init). */
    private static ?PDO $connection = null;

    /**
     * Trả về kết nối PDO dùng chung, khởi tạo ở lần gọi đầu tiên.
     */
    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $config = require ROOT_PATH . '/config/database.php';

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );

        try {
            self::$connection = new PDO(
                $dsn,
                $config['username'],
                $config['password'],
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
            // Đảm bảo session MySQL sử dụng utf8mb4
            self::$connection->exec("SET NAMES utf8mb4");
        } catch (PDOException $exception) {
            throw new RuntimeException(
                'Không kết nối được cơ sở dữ liệu. Hãy kiểm tra MySQL (XAMPP) đã chạy '
                . 'và đã import database/schema.sql + seed.sql chưa. Chi tiết: '
                . $exception->getMessage()
            );
        }

        return self::$connection;
    }

    /**
     * Chạy một câu lệnh có tham số và trả về PDOStatement đã thực thi.
     *
     * @param array<string,mixed> $params
     */
    public static function run(string $sql, array $params = []): \PDOStatement
    {
        $statement = self::connection()->prepare($sql);
        $statement->execute($params);
        return $statement;
    }
}
