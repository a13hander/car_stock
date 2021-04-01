<?php

namespace Stock\Parsers\Feed;

use Exception;
use SimpleXMLElement;
use Stock\Dto\Car;
use Stock\Dto\IncompleteCar;
use Stock\Enums\StockEnum;
use Stock\Fetchers\FetchResult;
use Stock\Parsers\Parser;

class XmlParser extends Parser
{
    public function parse($filename): FetchResult
    {
        $xml = simplexml_load_file($filename);
        $result = new FetchResult();

        foreach ($xml->Ad as $ad) {
            $car = $this->parseCar($ad);
            $validate = $this->validator->validate($car);

            if ($validate->hasErrors()) {
                $result->addIncompleteCar(new IncompleteCar($car, $validate->getErrors()));
                continue;
            }

            $result->addCar($car);
        }

        return $result;
    }

    protected function parseCar(SimpleXMLElement $ad): Car
    {
        $car = new Car();
        $car->type = StockEnum::TYPE_USED;

        $car->id = $this->id($ad);
        $car->vin = $this->vin($ad);
        $car->brand = $this->brand($ad);
        $car->model = $this->model($ad);
        $car->body_type = $this->bodyType($ad);
        $car->fuel_type = $this->fuelType($ad);
        $car->drive_type = $this->driveType($ad);
        $car->gearbox_type = $this->gearboxType($ad);
        $car->wheel_type = $this->wheelType($ad);
        $car->engine_power = $this->enginePower($ad);
        $car->engine_volume = $this->engineVolume($ad);
        $car->doors = $this->doors($ad);
        $car->year = $this->year($ad);
        $car->kilometrage = $this->kilometrage($ad);
        $car->color = $this->color($ad);
        $car->accident = $this->accident($ad);
        $car->owners = $this->owners($ad);
        $car->address = $this->address($ad);
        $car->description = $this->description($ad);
        $car->price = $this->price($ad);
        $car->images = $this->images($ad);

        return $car;
    }

    protected function id(SimpleXMLElement $element): string
    {
        $field = $this->fieldsMap['id'];

        return $element->{$field};
    }

    protected function vin(SimpleXMLElement $element): string
    {
        $field = $this->fieldsMap['vin'];

        return $element->{$field};
    }

    protected function brand(SimpleXMLElement $element): string
    {
        $field = $this->fieldsMap['brand'];

        return $element->{$field};
    }

    protected function model(SimpleXMLElement $element): string
    {
        $field = $this->fieldsMap['model'];

        return $element->{$field};
    }

    protected function bodyType(SimpleXMLElement $element): string
    {
        $field = $this->fieldsMap['body_type'];

        return $element->{$field};
    }

    protected function fuelType(SimpleXMLElement $element): string
    {
        $field = $this->fieldsMap['fuel_type'];

        return $element->{$field};
    }

    protected function driveType(SimpleXMLElement $element): string
    {
        $field = $this->fieldsMap['drive_type'];

        return $element->{$field};
    }

    protected function gearboxType(SimpleXMLElement $element): string
    {
        $field = $this->fieldsMap['gearbox_type'];

        return $element->{$field};
    }

    protected function wheelType(SimpleXMLElement $element): string
    {
        $field = $this->fieldsMap['wheel_type'];

        return $element->{$field};
    }

    protected function enginePower(SimpleXMLElement $element): int
    {
        $field = $this->fieldsMap['engine_power'];

        return (int)$element->{$field};
    }

    protected function engineVolume(SimpleXMLElement $element): float
    {
        $field = $this->fieldsMap['engine_volume'];

        return (float)$element->{$field};
    }

    protected function doors(SimpleXMLElement $element): int
    {
        $field = $this->fieldsMap['doors'];

        return (int)$element->{$field};
    }

    protected function year(SimpleXMLElement $element): int
    {
        $field = $this->fieldsMap['year'];

        return (int)$element->{$field};
    }

    protected function kilometrage(SimpleXMLElement $element): int
    {
        $field = $this->fieldsMap['kilometrage'];

        return (int)$element->{$field};
    }

    protected function color(SimpleXMLElement $element): string
    {
        $field = $this->fieldsMap['color'];

        return $element->{$field};
    }

    protected function accident(SimpleXMLElement $element): string
    {
        $field = $this->fieldsMap['accident'];

        return $element->{$field};
    }

    protected function owners(SimpleXMLElement $element): int
    {
        $field = $this->fieldsMap['owners'];

        return (int)$element->{$field};
    }

    protected function address(SimpleXMLElement $element): string
    {
        $field = $this->fieldsMap['address'];

        return $element->{$field};
    }

    protected function description(SimpleXMLElement $element): string
    {
        $field = $this->fieldsMap['description'];

        return $element->{$field};
    }

    protected function price(SimpleXMLElement $element): int
    {
        $field = $this->fieldsMap['price'];

        return (int)$element->{$field};
    }

    protected function images(SimpleXMLElement $element): array
    {
        $field = $this->fieldsMap['images'];
        $images = [];

        try {
            /** @var SimpleXMLElement $image */
            foreach ($element->{$field}->Image as $image) {
                $images[] = (string)$image->attributes()['url'];
            }
        } catch (Exception) {
            return [];
        }

        return $images;
    }
}
