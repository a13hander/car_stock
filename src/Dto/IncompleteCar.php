<?php

namespace Stock\Dto;

class IncompleteCar
{
    private Car $car;
    private array $errors;

    public function __construct(Car $car, array $errors)
    {
        $this->car = $car;
        $this->errors = $errors;
    }

    public function getCar(): Car
    {
        return $this->car;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
