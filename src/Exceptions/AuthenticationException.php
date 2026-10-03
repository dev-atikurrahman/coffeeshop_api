<?php

declare(strict_types=1);

namespace Coffeeshop\Api\Exceptions;

final class AuthenticationException extends ApiException
{
    public function __construct(
        string $message = 'Authentication failed.'
    ) {
        parent::__construct($message, 401);
    }
}
