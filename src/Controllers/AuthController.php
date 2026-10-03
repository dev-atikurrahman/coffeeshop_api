<?php

declare(strict_types=1);

namespace Coffeeshop\Api\Controllers;

use Coffeeshop\Api\Core\Request;
use Coffeeshop\Api\Core\Response;
use Coffeeshop\Api\Services\AuthService;

final class AuthController
{
    public function __construct(
        private readonly AuthService $authService
    ) {}

    public function register(
        Request $request
    ): never {
        $result = $this->authService->register(
            $request->body()
        );

        Response::success(
            data: $result,
            message: 'Registration successful.',
            statusCode: 201
        );
    }

    public function login(
        Request $request
    ): never {
        $result = $this->authService->login(
            $request->body()
        );

        Response::success(
            data: $result,
            message: 'Login successful.'
        );
    }
}
