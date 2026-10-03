<?php

declare(strict_types=1);

namespace Coffeeshop\Api\Exceptions;

final class ValidationException extends ApiException
{
    public function __construct(
        array $errors
    ) {
        parent::__construct(
            'Validation failed.',
            422,
            $errors
        );
    }
}
