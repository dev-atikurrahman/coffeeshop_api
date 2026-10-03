<?php

declare(strict_types=1);

namespace Coffeeshop\Api\Middleware;

use Coffeeshop\Api\Core\Request;
use Coffeeshop\Api\Core\Response;

final class AdminMiddleware
{
    public function handle(
        Request $request,
        callable $next
    ): mixed {
        $auth = $request->attribute('auth');

        if (!$auth) {
            Response::error(
                'Authentication required.',
                401
            );
        }

        if (($auth['role'] ?? null) !== 'admin') {
            Response::error(
                'Admin access required.',
                403
            );
        }

        return $next($request);
    }
}
