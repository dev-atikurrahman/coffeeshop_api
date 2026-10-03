<?php

declare(strict_types=1);

namespace Coffeeshop\Api\Middleware;

use Coffeeshop\Api\Core\Request;
use Coffeeshop\Api\Core\Response;
use Coffeeshop\Api\Services\JwtService;
use Throwable;

final class AuthMiddleware
{
    public function __construct(
        private readonly JwtService $jwt
    ) {}

    public function handle(Request $request, callable $next): mixed
    {
        $token = $request->bearerToken();

        if (!$token) {
            Response::error('Authentication token is required.', 401);
        }

        try {
            $payload = $this->jwt->verify($token);
        } catch (Throwable) {
            Response::error('Invalid or expired token.', 401);
        }

        $request->setAttribute('auth', $payload);

        return $next($request);
    }
}
