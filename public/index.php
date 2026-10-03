<?php

declare(strict_types=1);

use Coffeeshop\Api\Config\Env;
use Coffeeshop\Api\Core\Request;
use Coffeeshop\Api\Core\Response;
use Coffeeshop\Api\Core\Router;
use Coffeeshop\Api\Exceptions\ApiException;
use Coffeeshop\Api\Middleware\CorsMiddleware;

require __DIR__ . '/../vendor/autoload.php';

Env::load(__DIR__ . '/../.env');

if (Env::get('APP_DEBUG') === 'true') {
    ini_set('display_errors', '0');
    error_reporting(E_ALL);
}

try {
    $request = new Request();
    $router  = new Router();

    // Global middleware
    $router->use(new CorsMiddleware());

    // Routes
    require __DIR__ . '/../src/Routes/api.php';

    $router->dispatch($request);
} catch (ApiException $e) {
    Response::error($e->getMessage(), $e->statusCode(), $e->errors());
} catch (Throwable $e) {
    error_log($e->getMessage() . "\n" . $e->getTraceAsString());
    Response::error(
        Env::get('APP_DEBUG') === 'true' ? $e->getMessage() : 'Internal server error',
        500
    );
}
