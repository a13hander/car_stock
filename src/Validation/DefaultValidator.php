<?php

namespace Stock\Validation;

use Stock\Dto\Car;

class DefaultValidator implements Validator
{
    protected $fields = [
        'price',
        'images',
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
        if (empty($car->images)) {
            return new ValidationError('images', 'Нет изображений');
        }

        return null;
    }
}
