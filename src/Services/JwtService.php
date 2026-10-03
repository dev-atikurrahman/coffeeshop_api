<?php

declare(strict_types=1);

namespace Coffeeshop\Api\Services;

use Coffeeshop\Api\Config\Env;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use RuntimeException;

final class JwtService
{
    public function generate(
        int $userId,
        string $email,
        string $role
    ): string {
        $secret = Env::get('JWT_SECRET');

        if (!$secret) {
            throw new RuntimeException(
                'JWT_SECRET is not configured.'
            );
        }

        $issuedAt = time();

        $expiration = $issuedAt + (int) Env::get(
            'JWT_EXPIRATION',
            '86400'
        );

        $payload = [
            'iat' => $issuedAt,
            'exp' => $expiration,
            'sub' => $userId,
            'email' => $email,
            'role' => $role,
        ];

        return JWT::encode(
            $payload,
            $secret,
            'HS256'
        );
    }

    public function verify(string $token): array
    {
        $secret = Env::get('JWT_SECRET');

        if (!$secret) {
            throw new RuntimeException(
                'JWT_SECRET is not configured.'
            );
        }

        $decoded = JWT::decode(
            $token,
            new Key($secret, 'HS256')
        );

        return (array) $decoded;
    }
}
