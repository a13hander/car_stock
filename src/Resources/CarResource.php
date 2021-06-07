<?php

namespace Stock\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\CarStock\Car;

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
            'price' => $self->getPriceFormatted(),
            'year' => $self->year,
            'kilometrage' => $self->kilometrage,
            'transmission' => $self->gearbox_type,
            'body_type' => $self->body_type,
            'fuel_type' => $self->fuel_type,
            'engine_size' => $self->engine_volume,
            'power' => $self->engine_power,
            'url' => $self->getUrl(),
            'main_image' => $self->getMainImage()?->image,
            'color' => $self->color,
            'sold' => config('stock.use_soft_delete', true) ? $self->trashed() : false,
            'type' => $self->type
        ];
    }
}
