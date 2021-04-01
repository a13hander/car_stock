<?php

namespace Stock;

use Stock\Events\ImportComplete;

class GoogleDocImporter extends AbstractImporter
{
    public function import(bool $force = false): void
    {
        $result = $this->fetcher->fetch();
        $cars = collect($result->getCars());

        $this->importBrands($cars);
        $this->importModels($cars);
        $this->importCars($cars);

        ImportComplete::dispatch($result);
    }
}
