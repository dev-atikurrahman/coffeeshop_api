<?php

declare(strict_types=1);

use Coffeeshop\Api\Config\Database;
use Coffeeshop\Api\Controllers\AuthController;
use Coffeeshop\Api\Core\Router;
use Coffeeshop\Api\Repositories\UserRepository;
use Coffeeshop\Api\Services\AuthService;
use Coffeeshop\Api\Services\JwtService;

$database = new Database();

$userRepository = new UserRepository(
    $database
);

$jwtService = new JwtService();

$authService = new AuthService(
    $userRepository,
    $jwtService
);

$authController = new AuthController(
    $authService
);

$router->post(
    '/api/auth/register',
    [$authController, 'register']
);

$router->post(
    '/api/auth/login',
    [$authController, 'login']
);

$router->get(
    '/api/test',
    function () {
        \Coffeeshop\Api\Core\Response::success(
            data: null,
            message: 'CoffeeShop API is running.'
        );
    }
);
