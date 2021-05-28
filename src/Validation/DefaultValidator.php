<?php

namespace Stock\Validation;

use App\Models\CarStock\Car as CarDB;
use Stock\Dto\Car;
use Stock\Enums\StockEnum;

class DefaultValidator implements Validator
{
    protected $fields = [
        'price',
        'images',
        'vin',
    ];

    public function validate(Car $car): ValidateResult
    {
        $result = new ValidateResult();

        foreach ($this->fields as $field) {
            $err = $this->{$field}($car);

            if ($err !== null) {
                $result->addError($err);
            }
        }

        return $result;
    }

    protected function price(Car $car): ?ValidationError
    {
        if ($car->price < 0) {
            return new ValidationError('price', 'Цена меньше нуля');
        }

        return null;
    }

    protected function images(Car $car): ?ValidationError
    {
        if ($car->type == StockEnum::TYPE_USED && empty($car->images)) {
            return new ValidationError('images', 'Нет изображений');
        }

        return null;
    }

    protected function vin(Car $car): ?ValidationError
    {
        if (CarDB::query()->where('vin', $car->vin)->get()->count() > 0) {
            return new ValidationError('vin', 'Авто с таким вином уже существует');
        }

        return null;
    }
}
