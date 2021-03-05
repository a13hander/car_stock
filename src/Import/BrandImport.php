<?php

namespace Stock\Import;

use Illuminate\Support\Collection;
use Stock\Models\Brand;
use function sluggify;

class BrandImport
{
    private Brand $builder;
    private Collection $newItems;

    public function __construct(Brand $builder)
    {
        $this->builder = $builder;
        $this->newItems = collect();
    }

    public function handle(Collection $cars): void
    {
        $exists = $this->builder->newQuery()->pluck('name')->toArray();
        $newBrands = $cars->pluck('brand')
            ->unique()
            ->filter(fn(string $brand) => in_array($brand, $exists) === false);

        foreach ($newBrands as $brand) {
            $this->builder->newQuery()->create([
                'name' => $brand,
                'slug' => sluggify($brand),
            ]);
        }

        $this->newItems = $newBrands;
    }

    public function getLastImportedItems(): Collection
    {
        return $this->newItems;
    }
}
