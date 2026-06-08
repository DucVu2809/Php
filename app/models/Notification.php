<?php
namespace App\Models;

use App\Core\Database;

class Notification 
{
    public static function countUnread(): int 
    {
        $sql = "SELECT COUNT(*) as total FROM `notifications` WHERE is_read = 0";
        $result = Database::run($sql)->fetch();
        return (int) ($result['total'] ?? 0);
    }
    public static function markAllAsRead(): bool
    {
        return Database::run("UPDATE `notifications` SET `is_read` = 1 WHERE `is_read` = 0") ? true : false;
    }
}