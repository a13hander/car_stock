<?php

namespace Stock\Import;

use Illuminate\Support\Collection;
use App\Models\CarStock\Brand;
use Illuminate\Support\Str;

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
        $exists = $this->builder->newQuery()->pluck('slug')->toArray();
        $newBrands = $cars->pluck('brand')
            ->unique()
            ->filter(fn(string $brand) => in_array(Str::slug($brand), $exists) === false);

        foreach ($newBrands as $brand) {
            $this->builder->newQuery()->create([
                'name' => $brand,
                'slug' => Str::slug($brand),
            ]);
        }

        $this->newItems = $newBrands;
    }

    public function getLastImportedItems(): Collection
    {
        return $this->newItems;
    }
}
