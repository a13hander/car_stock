<?php

namespace Stock\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Stock\Filters\StockCarFilter;
use Stock\Models\Brand;
use Stock\Requests\CarRequest;
use Stock\Resources\BrandModelResource;
use Stock\Resources\BrandResource;
use Stock\Resources\CarResource;

class StockController
{
    public function getBrands(Brand $brands): ResourceCollection
    {
        return BrandResource::collection(
            $brands->newQuery()
                ->has('models.cars')
                ->orderBy('name')
                ->get()
        );
    }

    public function getBrandModels(Request $request, Brand $brand): ResourceCollection
    {
        $brands = $request->input('brands', []);

        $brandModels = $brand
            ->newQuery()
            ->with('models')
            ->has('models.cars')
            ->findMany($brands);

        return BrandModelResource::collection($brandModels);
    }

    public function getCars(CarRequest $request, StockCarFilter $stockFilter): ResourceCollection
    {
        $query = $stockFilter->filtrate($request);

        $meta = $stockFilter->getMetaData($request, $query);
        $cars = $stockFilter
            ->orderAndLimit($request, $query)
            ->with(['model.brand'])
            ->get();

        return CarResource::collection($cars)->additional(compact('meta'));
    }
}
