<?php

namespace Stock\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Stock\Enums\StockEnum;
use Stock\Filters\StockCarFilter;

class CarRequest extends FormRequest
{
    public const RANGE_VALIDATION = 'nullable|numeric|min:1|max:99999999';

    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        $driveTypes = $this->getTypes(StockEnum::DRIVE_TYPE_MAPPING);
        $gearboxTypes = $this->getTypes(StockEnum::TRANSMISSION_TYPE_MAPPING);
        $bodyTypes = $this->getTypes(StockEnum::BODY_TYPE_MAPPING);
        $engineTypes = $this->getTypes(StockEnum::FUEL_TYPE_MAPPING);
        $types = $this->getTypes(StockEnum::TYPE_MAPPING);

        $orders = implode(',', StockCarFilter::ORDERS);

        return [
            'brand_models' => 'nullable|array',

            'year_from' => static::RANGE_VALIDATION,
            'year_to' => static::RANGE_VALIDATION,

            'kilometrage_from' => static::RANGE_VALIDATION,
            'kilometrage_to' => static::RANGE_VALIDATION,

            'drive_type' => "nullable|array",
            'drive_type.*' => "string|in:{$driveTypes}",

            'gearbox_type' => "nullable|array",
            'gearbox_type.*' => "string|in:{$gearboxTypes}",

            'body_type' => "nullable|array",
            'body_type.*' => "string|in:{$bodyTypes}",

            'fuel_type' => "nullable|array",
            'fuel_type.*' => "string|in:{$engineTypes}",

            'type' => "string|in:{$types}",

            'engine_volume_from' => static::RANGE_VALIDATION,
            'engine_volume_to' => static::RANGE_VALIDATION,

            'price_from' => static::RANGE_VALIDATION,
            'price_to' => static::RANGE_VALIDATION,

            'order_by' => "nullable|string|in:{$orders}",

            'offset' => "nullable|numeric|min:0",
            'limit' => "nullable|numeric|min:1|max:" . StockCarFilter::LIMIT_MAX,
        ];
    }

    public function attributes(): array
    {
        return [
            'location_slug' => '??????????????',

            'year_from' => '?????? ????',
            'year_to' => '?????? ????',

            'kilometrage_from' => '???????????? ????',
            'kilometrage_to' => '???????????? ????',

            'drive_type' => '?????? ??????????????',
            'transmission' => '?????? ??????????????',
            'body_type' => '?????? ????????????',
            'fuel_type' => '?????? ??????????????????',

            'engine_capacity_from' => '?????????? ?????????????????? ????',
            'engine_capacity_to' => '?????????? ?????????????????? ????',

            'price_from' => '???????? ????',
            'price_to' => '???????? ????',

            'order_by' => '????????????????????',

            'offset' => '????????????????',
            'limit' => '??????????',
        ];
    }

    protected function getTypes(array $mappings): string
    {
        return implode(',', array_keys($mappings));
    }
}
