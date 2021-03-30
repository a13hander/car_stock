<?php

namespace Stock\Import;

use App\Models\CarStock\Location;
use Exception;
use Illuminate\Support\Collection;
use function sluggify;

class LocationImport
{
    private Location $builder;
    private Collection $newItems;

    public function __construct(Location $builder)
    {
        $this->builder = $builder;
        $this->newItems = collect();
    }

    public function handle(Collection $cars): void
    {
        $exists = $this->builder->newQuery()->pluck('address')->toArray();
        $addresses = $cars->pluck('address')->unique();

        $newItems = $addresses->filter(fn(string $address) => in_array($address, $exists) === false);

        foreach ($newItems as $address) {
            $this->builder->newQuery()->create([
                'address' => $address,
                'slug' => sluggify($address),
            ]);
        }

        $this->newItems = $newItems;
    }

    public function getLastImportedItems(): Collection
    {
        return $this->newItems;
    }
}
