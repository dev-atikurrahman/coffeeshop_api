<?php

declare(strict_types=1);

namespace Coffeeshop\Api\Services;

use Coffeeshop\Api\Exceptions\ApiException;
use Coffeeshop\Api\Exceptions\AuthenticationException;
use Coffeeshop\Api\Exceptions\ValidationException;
use Coffeeshop\Api\Repositories\UserRepository;
use Coffeeshop\Api\Security\PasswordHasher;
use Coffeeshop\Api\Core\Validator;

final class AuthService
{
    public function __construct(
        private readonly UserRepository $users,
        private readonly JwtService $jwt
    ) {}

    public function register(array $data): array
    {
        $validator = new Validator();

        $validator
            ->required('name', $data['name'] ?? null)
            ->string('name', $data['name'] ?? null)
            ->maxLength('name', $data['name'] ?? null, 100)
            ->required('email', $data['email'] ?? null)
            ->string('email', $data['email'] ?? null)
            ->email('email', $data['email'] ?? null)
            ->maxLength('email', $data['email'] ?? null, 255)
            ->required('password', $data['password'] ?? null)
            ->string('password', $data['password'] ?? null)
            ->minLength('password', $data['password'] ?? null, 8);

        if ($validator->fails()) {
            throw new ValidationException(
                $validator->errors()
            );
        }

        $name = trim($data['name']);
        $email = strtolower(trim($data['email']));
        $password = $data['password'];

        if ($this->users->emailExists($email)) {
            throw new ApiException(
                'Email is already registered.',
                409
            );
        }

        $passwordHash = PasswordHasher::hash(
            $password
        );

        $user = $this->users->create(
            name: $name,
            email: $email,
            passwordHash: $passwordHash
        );

        $token = $this->jwt->generate(
            $user->id,
            $user->email,
            $user->role
        );

        return [
            'user' => $user->toArray(),
            'token' => $token,
        ];
    }

    public function login(array $data): array
    {
        $validator = new Validator();

        $validator
            ->required('email', $data['email'] ?? null)
            ->string('email', $data['email'] ?? null)
            ->email('email', $data['email'] ?? null)
            ->required('password', $data['password'] ?? null)
            ->string('password', $data['password'] ?? null);

        if ($validator->fails()) {
            throw new ValidationException(
                $validator->errors()
            );
        }

        $email = strtolower(
            trim($data['email'])
        );

        $user = $this->users->findByEmail(
            $email
        );

        if (
            $user === null ||
            !PasswordHasher::verify(
                $data['password'],
                $user->passwordHash
            )
        ) {
            throw new AuthenticationException(
                'Invalid email or password.'
            );
        }

        $token = $this->jwt->generate(
            $user->id,
            $user->email,
            $user->role
        );

        return [
            'user' => $user->toArray(),
            'token' => $token,
        ];
    }
}
