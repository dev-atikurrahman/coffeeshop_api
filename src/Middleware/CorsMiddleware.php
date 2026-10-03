<?php

declare(strict_types=1);

namespace Coffeeshop\Api\Middleware;

use Coffeeshop\Api\Core\Request;

final class CorsMiddleware
{
    public function handle(
        Request $request,
        callable $next
    ): mixed {
        header(
            'Access-Control-Allow-Origin: *'
        );

        header(
            'Access-Control-Allow-Headers: Content-Type, Authorization'
        );

        header(
            'Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS'
        );

        if ($request->method() === 'OPTIONS') {
            http_response_code(204);
            exit;
        }

        return $next($request);
    }
}
