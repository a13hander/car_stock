<?php

namespace Stock\Import;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Stock\Dto\Car as CarDto;
use App\Models\CarStock\Car;
use App\Models\CarStock\CarModel;
use Stock\Dto\IncompleteCar;
use Stock\Events\ImportCarError;

class CarsImport
{
    private Car $builder;
    private Collection $newItems;
    private CarModel $carModel;
    /** @var callable */
    private $conditions;

    private bool $useSoftDelete;

    public function __construct(Car $builder, CarModel $carModel)
    {
        $this->builder = $builder;
        $this->newItems = collect();
        $this->carModel = $carModel;

        $this->useSoftDelete = config('stock.use_soft_delete', true);
    }

    public function handle(Collection $cars): void
    {
        $this->removeCars($cars);

        $failedCars = [];

        /** @var CarDto $carDto */
        foreach ($cars as $carDto) {
            $car = $this->getCar($carDto->vin);

            if (is_null($car)) {
                try {
                    $newCar = $this->createCar($carDto);
                    $this->newItems->push($newCar);
                } catch (Exception $exception) {
                    $failedCars[] = new IncompleteCar($carDto, ['Ошибка при создании авто: ' . $exception->getMessage()]);
                }

                continue;
            }

            if ($this->useSoftDelete && $car->trashed()) {
                $car->restore();
            }

            try {
                $this->updateCar($car, $carDto);
            } catch (Exception $exception) {
                $failedCars[] = new IncompleteCar($carDto, ['Ошибка при обновлении авто: ' . $exception->getMessage()]);
            }
        }

        ImportCarError::dispatch($failedCars);
    }

    public function getLastImportedItems(): Collection
    {
        return $this->newItems;
    }

    protected function removeCars(Collection $cars): void
    {
        $existsVins = $this->newQuery()->pluck('vin');
        $importVins = $cars->pluck('vin');

        $toRemove = $existsVins->diff($importVins);

        if ($toRemove->isNotEmpty()) {
            $vinsRemove = $toRemove->toArray();
            $builder = $this->newQuery()->whereIn('vin', $vinsRemove);

            if ($this->useSoftDelete) {
                $builder->delete();
            } else {
                $builder->forceDelete();
            }
        }
    }

    protected function getCar(string $vin): ?Car
    {
        return $this->newQuery()->firstWhere('vin', $vin);
    }

    protected function createCar(CarDto $carDto): Car
    {
        $model = $this->resolveModel($carDto->brand, $carDto->model) ?? throw new Exception('Модель не найдена');
        $attrs = array_merge($carDto->toArray(), [
            'model_id' => $model->id,
        ]);

        /** @var Car $car */
        $car = $this->newQuery()->create($attrs);

        foreach ($carDto->images as $image) {
            $car->images()->create([
                'image' => $image,
                'original_image' => $image,
                'from_import' => 1,
            ]);
        }

        return $car;
    }

    protected function updateCar(Car $car, CarDto $carDto): void
    {
        $model = $this->resolveModel($carDto->brand, $carDto->model) ?? throw new Exception('Модель не найдена');
        $attrs = array_merge($carDto->toArray(), [
            'model_id' => $model->id,
            'id' => $car->id,
        ]);

        $car->fill($attrs);
        $car->save();

        if (!$this->compareArrays($carDto->images, $car->images()->imported()->get()->pluck('original_image')->toArray())) {
            $car->images()->imported()->delete();

            foreach ($carDto->images as $image) {
                $car->images()->create([
                    'image' => $image,
                    'original_image' => $image,
                    'from_import' => 1,
                ]);
            }
        }
    }

    protected function resolveModel(string $brand, string $model): ?CarModel
    {
        return $this->carModel
            ->newQuery()
            ->whereHas('brand', fn(Builder $builder) => $builder->where('slug', Str::slug($brand)))
            ->where('slug', Str::slug($model))
            ->first();
    }

    protected function newQuery(): Builder
    {
        $query = $this->builder->newQuery();

        if ($this->useSoftDelete) {
            $query = $query->withTrashed();
        }

        if (is_callable($this->conditions)) {
            $function = $this->conditions;
            $query = $function($query);
        }

        return $query;
    }

    public function setCondition(callable $conditions): void
    {
        $this->conditions = $conditions;
    }

    protected function compareArrays(array $a, array $b): bool
    {
        return (
            count($a) == count($b)
            && count(array_diff($a, $b)) == 0
            && count(array_diff($b, $a)) == 0
        );
    }
}
