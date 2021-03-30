<?php

namespace Stock\Filters;

use Stock\Enums\StockEnum;
use App\Models\CarStock\CarModel;
use Illuminate\Database\Eloquent\Builder;

class StockCarFilter extends CarFilter
{
    public function setFilters(): void
    {
        $this
            ->setRangeFilter('year')
            ->setRangeFilter('kilometrage')
            ->setRangeFilter('price')
            ->setRangeFilter('engine_size')
            ->setEnumFilter('drive_type', StockEnum::DRIVE_TYPE_MAPPING)
            ->setEnumFilter('transmission', StockEnum::TRANSMISSION_TYPE_MAPPING)
            ->setEnumFilter('body_type', StockEnum::BODY_TYPE_MAPPING)
            ->setEnumFilter('fuel_type', StockEnum::FUEL_TYPE_MAPPING);

        // Filter by Brand & Model
        $this->setClosureFilter('brand_models', function (Builder $q, array $fields) {
            $params = collect($fields['brand_models'] ?? []);

            if ($params->isEmpty()) {
                return;
            }

            // получаем идентификаторы брендов для которых нет ограничения по модели
            $brandsWithoutModels = $params->where('models', [])->pluck('brand_id')->toArray();

            // получаем идентификаторы моделей
            $models = CarModel::query()->whereIn('brand_id', $brandsWithoutModels)->pluck('id')->toArray();

            // получаем идентификаторы моделей по которым стоит ограничение
            $modelsRestrict = $params->where('models', '<>', [])->pluck('models')->flatten()->toArray();

            $models = array_merge($models, $modelsRestrict);

            $q->whereIn('model_id', $models);
        });

        // @see static::ORDERS
        $this
            ->setOrder('date-desc', 'created_at', 'desc')
            ->setOrder('price-asc', 'price', 'asc')
            ->setOrder('price-desc', 'price', 'desc')
            ->setOrder('kilometrage-asc', 'kilometrage', 'asc');
    }
}
