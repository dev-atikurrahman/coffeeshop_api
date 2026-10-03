<?php

declare(strict_types=1);

use Coffeeshop\Api\Config\Database;
use Coffeeshop\Api\Controllers\AuthController;
use Coffeeshop\Api\Core\Response;
use Coffeeshop\Api\Core\Router;
use Coffeeshop\Api\Repositories\UserRepository;
use Coffeeshop\Api\Services\AuthService;
use Coffeeshop\Api\Services\JwtService;


/** @var Router $router */

$jwtService     = new JwtService();
$userRepository = new UserRepository(new Database());
$authService    = new AuthService($userRepository, $jwtService);
$authController = new AuthController($authService);

$router->get('/api/test', function () {
    Response::success(message: 'CoffeeShop API is running.');
});

$router->post('/api/auth/register', [$authController, 'register']);
$router->post('/api/auth/login', [$authController, 'login']);

/*
 * Protected route-এর উদাহরণ (পরে ব্যবহার করবেন):
 *
 * $router->get('/api/me', [$profileController, 'show'], [new AuthMiddleware($jwtService)]);
 * $router->get('/api/admin/orders', [$orderController, 'index'],
 *     [new AuthMiddleware($jwtService), new AdminMiddleware()]);
 */