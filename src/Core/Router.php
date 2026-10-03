<?php

declare(strict_types=1);

namespace Coffeeshop\Api\Core;

use RuntimeException;

final class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    public function put(string $path, callable $handler): void
    {
        $this->add('PUT', $path, $handler);
    }

    public function patch(string $path, callable $handler): void
    {
        $this->add('PATCH', $path, $handler);
    }

    public function delete(string $path, callable $handler): void
    {
        $this->add('DELETE', $path, $handler);
    }

    public function dispatch(Request $request): mixed
    {
        $method = $request->method();
        $uri = $request->uri();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = $this->convertToRegex($route['path']);

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);

                return ($route['handler'])(
                    $request,
                    ...array_values($matches)
                );
            }
        }

        Response::error(
            'Route not found',
            404
        );
    }

    private function add(
        string $method,
        string $path,
        callable $handler
    ): void {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
        ];
    }

    private function convertToRegex(string $path): string
    {
        $pattern = preg_replace(
            '/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/',
            '([^/]+)',
            $path
        );

        return '#^' . $pattern . '$#';
    }
}
