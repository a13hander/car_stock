<?php

namespace Stock\Validation;

class ValidateResult
{
    private array $errors = [];

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function addError(ValidationError $error): void
    {
        $this->errors[] = $error;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
