<?php

declare(strict_types=1);

namespace Coffeeshop\Api\Core;

final class Validator
{
    private array $errors = [];

    public function required(
        string $field,
        mixed $value
    ): self {
        if (
            $value === null ||
            $value === '' ||
            (is_string($value) && trim($value) === '')
        ) {
            $this->errors[$field][] = 'This field is required.';
        }

        return $this;
    }

    public function email(
        string $field,
        mixed $value
    ): self {
        if (
            $value !== null &&
            $value !== '' &&
            !filter_var($value, FILTER_VALIDATE_EMAIL)
        ) {
            $this->errors[$field][] = 'Invalid email address.';
        }

        return $this;
    }

    public function minLength(
        string $field,
        mixed $value,
        int $length
    ): self {
        if (
            is_string($value) &&
            mb_strlen($value) < $length
        ) {
            $this->errors[$field][] =
                "Minimum {$length} characters required.";
        }

        return $this;
    }

    public function string(string $field, mixed $value): self
    {
        if ($value !== null && !is_string($value)) {
            $this->errors[$field][] = 'Must be a string.';
        }

        return $this;
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
