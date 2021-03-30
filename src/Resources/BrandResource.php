<?php

namespace Stock\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\CarStock\Brand;

class BrandResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var Brand $self */
        $self = $this;

        return [
            'id' => $self->id,
            'name' => $self->name,
            'value' => $self->slug,
        ];
    }
}
