<?php

namespace Stock\Dto;

class Car
{
    public string $id;
    public string $type;
    public string $vin;
    public string $brand;
    public string $model;
    public string $body_type;
    public string $fuel_type;
    public string $drive_type;
    public string $gearbox_type;
    public string $wheel_type;
    public int $engine_power;
    public float $engine_volume;
    public int $doors;
    public int $year;
    public int $kilometrage;
    public string $color;
    public string $accident;
    public int $owners;
    public string $address;
    public string $description;
    public int $price;
    public array $images;

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'vin' => $this->vin,
            'body_type' => $this->body_type,
            'fuel_type' => $this->fuel_type,
            'drive_type' => $this->drive_type,
            'gearbox_type' => $this->gearbox_type,
            'wheel_type' => $this->wheel_type,
            'engine_power' => $this->engine_power,
            'engine_volume' => $this->engine_volume,
            'doors' => $this->doors,
            'kilometrage' => $this->kilometrage,
            'color' => $this->color,
            'accident' => $this->accident,
            'owners' => $this->owners,
            'description' => $this->description,
            'price' => $this->price,
        ];
    }
}
