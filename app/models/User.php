<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

class User extends Model
{
    protected string $table = 'users';

    public function findByEmail(string $email): ?array
    {
        $row = Database::run(
            "SELECT * FROM `users` WHERE `email` = :email LIMIT 1",
            ['email' => $email]
        )->fetch();
        return $row ?: null;
    }

    public function emailExists(string $email): bool
    {
        return $this->findByEmail($email) !== null;
    }

    public function register(array $data): int
    {
        return $this->create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'phone'    => $data['phone'] ?? null,
            'address'  => $data['address'] ?? null,
            'role'     => 'customer',
        ]);
    }

    public function allCustomers(): array
    {
        return Database::run(
            "SELECT * FROM `users` ORDER BY `created_at` DESC"
        )->fetchAll();
    }
}
