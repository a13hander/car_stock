<?php

namespace Stock\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Stock\Models\Car;

class CarResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var Car $self */
        $self = $this;

        return [
            'id' => $self->id,
            'brand' => $self->car_model->brand->name,
            'model' => $self->car_model->name,
            'price' => $self->price_formatted,
            'benefit' => $self->benefit,
            'year' => $self->year,
            'kilometrage' => $self->kilometrage,
            'transmission' => $self->transmission,
            'body_type' => $self->body_type,
            'fuel_type' => $self->fuel_type,
            'engine_size' => $self->engine_size,
            'power' => $self->power,
            'url' => $self->getUrl(),
            'main_image' => $self->main_image->preview,
            'color' => $self->color,
            'sold' => $self->trashed(),
            'points' => $self->getPoints()->pluck('text')->toArray()
        ];
    }
}
