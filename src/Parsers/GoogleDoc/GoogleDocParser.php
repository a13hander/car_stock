<?php

namespace Stock\Parsers\GoogleDoc;

use Exception;
use Stock\Dto\Car;
use Stock\Dto\IncompleteCar;
use Stock\Enums\StockEnum;
use Stock\Fetchers\FetchResult;
use Stock\Parsers\Parser;

class GoogleDocParser extends Parser
{
    public function parse($data): FetchResult
    {
        $result = new FetchResult();
        foreach ($data as $index => $carData) {
            if ($index == 0) {
                continue;
            }

            $car = new Car();

            foreach ($this->fieldsMap as $column => $index) {
                $car->{$column} = $carData[$index];
            }

            $car->type = StockEnum::TYPE_NEW;
            $car->images = [];

            $validate = $this->validator->validate($car);

            if ($validate->hasErrors()) {
                $result->addIncompleteCar(new IncompleteCar($car, $validate->getErrors()));
                continue;
            }

            $result->addCar($car);
        }

        return $result;
    }
}
