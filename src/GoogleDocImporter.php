<?php

namespace Stock;

use Illuminate\Database\Eloquent\Builder;
use Stock\Events\ImportComplete;

class GoogleDocImporter extends AbstractImporter
{
    public function import(bool $force = false): void
    {
        $result = $this->fetcher->fetch();
        $cars = collect($result->getCars());

        $this->importBrands($cars);
        $this->importModels($cars);

        $this->carsImport->setCondition(function (Builder $builder) {
            return $builder->new();
        });
        $this->importCars($cars);

        ImportComplete::dispatch($result);
    }
}
