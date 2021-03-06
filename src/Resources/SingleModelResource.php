<?php

namespace Stock\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\CarStock\CarModel;

class SingleModelResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var CarModel $self */
        $self = $this;
        return [
            'id' => $self->id,
            'name' => $self->name,
            'value' => $self->slug,
        ];
    }
}
