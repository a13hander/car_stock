<?php

namespace Stock\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Stock\Models\Brand;

class BrandModelResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var Brand $self */
        $self = $this;
        return [
            'id' => $self->id,
            'name' => $self->name,
            'value' => $self->slug,
            'models' => SingleModelResource::collection($self->models)
        ];
    }
}
