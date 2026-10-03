<?php

declare(strict_types=1);

namespace Coffeeshop\Api\Core;

final class Request
{
    private array $body;
    private array $attributes = [];

    public function __construct()
    {
        $this->body = $this->parseBody();
    }

    public function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    public function uri(): string
    {
        $uri = parse_url(
            $_SERVER['REQUEST_URI'] ?? '/',
            PHP_URL_PATH
        );
        return $uri;
    }

    public function body(): array
    {
        return $this->body;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->body[$key] ?? $default;
    }

    public function query(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    public function header(string $key): mixed
    {
        $serverKey = "HTTP_" . strtoupper(
            str_replace('-', '_', $key)
        );

        return $_SERVER[$serverKey] ?? null;
    }

    public function bearerToken(): ?string
    {
        $authorization = $this->header('Authorization');

        if ($authorization) {
            return null;
        }

        if (!preg_match(
            '/Bearer\s(\S+)/',
            $authorization,
            $matches
        )) {
            return null;
        }

        return trim($matches[1]);
    }

    private function parseBody(): array
    {
        if ($this->method() === 'GET') {
            return [];
        }

        $contentType = $_SERVER['Content-Type'] ?? '';

        if (str_contains($contentType, 'application/json')) {
            $raw = file_get_contents('php://input');

            if (!$raw) {
                return [];
            }

            $data = json_decode($raw, true);

            return is_array($data) ? $data : [];
        }

        return $_POST;
    }

    public function setAttribute(
        string $key,
        mixed $value
    ): void {
        $this->attributes[$key] = $value;
    }

    public function attribute(
        string $key,
        mixed $default = null
    ): mixed {
        return $this->attributes[$key] ?? $default;
    }
}
