<?php

namespace Stock\Import;

use App\Models\CarStock\Brand;
use App\Models\CarStock\CarModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Stock\Dto\Car;

class ModelImport
{
    private Brand $brandBuilder;
    private CarModel $builder;
    private Collection $newItems;

    public function __construct(Brand $brandBuilder, CarModel $builder)
    {
        $this->brandBuilder = $brandBuilder;
        $this->builder = $builder;
        $this->newItems = collect();
    }

    public function handle(Collection $cars): void
    {
        $newItems = collect();

        /** @var Brand $brand */
        foreach ($this->brandBuilder->newQuery()->get() as $brand) {
            $models = $cars->filter(fn(Car $car) => Str::slug($car->brand) == $brand->slug)
                ->pluck('model')
                ->unique();
            $exists = $brand->car_models()->pluck('slug')->toArray();

            $newModels = $models->filter(fn(string $model) => in_array(Str::slug($model), $exists) === false);

            foreach ($newModels as $model) {
                $this->builder->newQuery()->create([
                    'name' => $model,
                    'slug' => Str::slug($model),
                    'brand_id' => $brand->id,
                ]);
            }

            $newItems[$brand->name] = $newModels;
        }

        $this->newItems = $newItems;
    }

    public function getLastImportedItems(): Collection
    {
        return $this->newItems;
    }
}
