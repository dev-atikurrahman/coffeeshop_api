<?php

declare(strict_types=1);

namespace Coffeeshop\Api\Models;

final class User
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly string $passwordHash,
        public readonly string $role = 'user',
    ) {}


    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id'])
                ? (int) $data['id']
                : null,

            name: (string) ($data['name'] ?? ''),

            email: (string) ($data['email'] ?? ''),

            passwordHash: (string) (
                $data['password_hash']
                ?? $data['passwordHash']
                ?? ''
            ),

            role: (string) (
                $data['role'] ?? 'user'
            ),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];
    }
}
