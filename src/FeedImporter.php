<?php

namespace Stock;

use Illuminate\Database\Eloquent\Builder;
use Stock\Events\ImportComplete;

class FeedImporter extends AbstractImporter
{
    public function import(bool $force = false): void
    {
        if (!$this->fetcher->hasDifferences() && !$force) {
            $this->logger->info('Импорт не содержит новых данных, для принудительного запуска добавьте ключ --force');
            return;
        }

        $result = $this->fetcher->fetch();
        $cars = collect($result->getCars());

        $this->importLocations($cars);
        $this->importBrands($cars);
        $this->importModels($cars);

        $this->carsImport->setCondition(function (Builder $builder) {
            return $builder->used();
        });
        $this->importCars($cars);

        ImportComplete::dispatch($result);
    }
}
