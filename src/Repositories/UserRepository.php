<?php

declare(strict_types=1);

namespace Coffeeshop\Api\Repositories;

use Coffeeshop\Api\Config\Database;
use Coffeeshop\Api\Models\User;
use PDO;

final class UserRepository
{
    private PDO $db;

    public function __construct(Database $database)
    {
        $this->db = $database->connect();
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->db->prepare(
            'SELECT id, name, email, password_hash, role
            FROM users
            WHERE id = :id
            LIMIT 1'
        );

        $stmt->execute([
            'id' => $id,
        ]);

        $data = $stmt->fetch();

        return $data
            ? User::fromArray($data)
            : null;
    }

    public function findByEmail(
        string $email
    ): ?User {
        $stmt = $this->db->prepare(
            'SELECT id, name, email, password_hash, role
            FROM users
            WHERE email = :email
            LIMIT 1'
        );

        $stmt->execute([
            'email' => $email,
        ]);

        $data = $stmt->fetch();

        return $data
            ? User::fromArray($data)
            : null;
    }

    public function create(
        string $name,
        string $email,
        string $passwordHash
    ): User {
        $stmt = $this->db->prepare(
            'INSERT INTO users
                (name, email, password_hash, role)
            VALUES
                (:name, :email, :password_hash, :role)'
        );

        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password_hash' => $passwordHash,
            'role' => 'user',
        ]);

        $id = (int) $this->db->lastInsertId();

        return $this->findById($id);
    }

    public function emailExists(
        string $email
    ): bool {
        $stmt = $this->db->prepare(
            'SELECT 1
            FROM users
            WHERE email = :email
            LIMIT 1'
        );

        $stmt->execute([
            'email' => $email,
        ]);

        return (bool) $stmt->fetchColumn();
    }
}
