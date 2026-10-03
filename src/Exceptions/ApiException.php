<?php

declare(strict_types=1);

namespace Coffeeshop\Api\Exceptions;

use Exception;

class ApiException extends Exception
{
    public function __construct(
        string $message,
        private readonly int $statusCode = 400,
        private readonly mixed $errors = null
    ) {
        parent::__construct($message);
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }

    public function errors(): mixed
    {
        return $this->errors;
    }
}
