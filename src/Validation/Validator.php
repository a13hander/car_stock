<?php

namespace Stock\Validation;

use Stock\Dto\Car;

interface Validator
{
    public function validate(Car $car): ValidateResult;
}
