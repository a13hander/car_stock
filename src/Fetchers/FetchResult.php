<?php

namespace Stock\Fetchers;

use Stock\Dto\Car;
use Stock\Dto\IncompleteCar;

class FetchResult
{
    private array $cars;
    private array $incompleteCars;

    public function __construct(array $cars = [], array $incompleteCars = [])
    {
        $this->cars = $cars;
        $this->incompleteCars = $incompleteCars;
    }

    public function addCar(Car $car): void
    {
        $this->cars[] = $car;
    }

    public function getCars(): array
    {
        return $this->cars;
    }

    public function hasIncompleteCars(): bool
    {
        return empty($this->incompleteCars) === false;
    }

    public function addIncompleteCar(IncompleteCar $car): void
    {
        $this->incompleteCars[] = $car;
    }

    public function getIncompleteCars(): array
    {
        return $this->incompleteCars;
    }
}
