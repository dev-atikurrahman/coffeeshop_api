<?php

declare(strict_types=1);

namespace Coffeeshop\Api\Core;


final class Router
{
    private array $routes = [];
    private array $globalMiddleware = [];

    public function use(object $middleware): void
    {
        $this->globalMiddleware[] = $middleware;
    }

    public function get(string $path, callable $handler, array $middleware = []): void
    {
        $this->add('GET', $path, $handler, $middleware);
    }

    public function post(string $path, callable $handler, array $middleware = []): void
    {
        $this->add('POST', $path, $handler, $middleware);
    }

    public function put(string $path, callable $handler, array $middleware = []): void
    {
        $this->add('PUT', $path, $handler, $middleware);
    }

    public function patch(string $path, callable $handler, array $middleware = []): void
    {
        $this->add('PATCH', $path, $handler, $middleware);
    }

    public function delete(string $path, callable $handler, array $middleware = []): void
    {
        $this->add('DELETE', $path, $handler, $middleware);
    }

    public function dispatch(Request $request): mixed
    {
        $method = $request->method();
        $uri    = $request->uri();

        $final = function (Request $request) use ($method, $uri) {
            foreach ($this->routes as $route) {
                if ($route['method'] !== $method) {
                    continue;
                }

                if (!preg_match($this->convertToRegex($route['path']), $uri, $matches)) {
                    continue;
                }

                array_shift($matches);
                $params = array_values($matches);

                $core = fn(Request $req) => ($route['handler'])($req, ...$params);

                return $this->runPipeline($route['middleware'], $request, $core);
            }

            Response::error('Route not found', 404);
        };

        return $this->runPipeline($this->globalMiddleware, $request, $final);
    }

    private function runPipeline(array $middleware, Request $request, callable $core): mixed
    {
        $pipeline = array_reduce(
            array_reverse($middleware),
            fn(callable $next, object $mw) => fn(Request $req) => $mw->handle($req, $next),
            $core
        );

        return $pipeline($request);
    }

    private function add(string $method, string $path, callable $handler, array $middleware): void
    {
        $this->routes[] = [
            'method'     => $method,
            'path'       => rtrim($path, '/') ?: '/',
            'handler'    => $handler,
            'middleware' => $middleware,
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
